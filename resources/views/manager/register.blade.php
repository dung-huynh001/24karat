@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')

<div class="container-fluid">
    <form id="managerForm" class="py-3">
        {{csrf_field()}}
        <div class="mb-3 row">
            <label for="subscription_user" class="col-sm-2 col-form-label">契約ユーザー <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <select id="subscription_user" name="subscription_user" class="form-select" aria-label="契約ユーザー">
                    <option value="" disabled selected>--選択--</option>
                    @foreach ($subscriptionUsers as $subscriptionUser)
                    <option value="{{ $subscriptionUser['subscription_user_id'] }}"
                        {{ old('subscription_user') == $subscriptionUser['subscription_user_id'] ? 'selected' : '' }}>
                        {{ $subscriptionUser['company_name'] }}
                    </option>
                    @endforeach
                </select>
                <div id="subscription_user-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">名前 <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" autocomplete="off">
                <div id="name-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">メールアドレス <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input id="email" name="email" class="form-control" placeholder="email@example.com" autocomplete="off">
                <div id="email-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">新しいパスワード <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control" autocomplete="off">
                    <span class="position-absolute top-50 translate-middle" style="right: 0; z-index: 9; cursor: pointer;">
                        <i class="fa-regular fa-eye-slash" id="toggle_password"></i>
                    </span>
                </div>
                <div id="password-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="confirm_password" class="col-sm-2 col-form-label">パスワード（確認）<span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <div class="position-relative">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="off">
                    <span class="position-absolute top-50 translate-middle" style="right: 0; z-index: 9; cursor: pointer;">
                        <i class="fa-regular fa-eye-slash" id="toggle_confirm_password"></i>
                    </span>
                </div>
                <div id="confirm_password-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center">
            <a id="btn_cancel" href="{{route('manager.list')}}" type="button" class="btn btn-lavender d-flex align-items-center">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-circle-chevron-left"></i>
                </span>
                <span>＜戻る</span>
            </a>
            <button id="btn_submit" type="submit" class="btn btn-royal-blue d-flex align-items-center">
                <span>登録</span>
                <span class="ms-2 square-8 rounded-circle bg-white fs-9 text-royal-blue d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-plus p-0 m-0"></i>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(() => {
        $('input').on('input', (event) => {
            const inputId = event.target.id;
            if (inputId) {
                $(`#${inputId}-validate`).html("");
            }
        });

        $('select').on('change', (event) => {
            const selectId = event.target.id;
            if (selectId) {
                $(`#${selectId}-validate`).html("");
            }
        });

        $('#toggle_password').on('click', (event) => {
            const togglePassword = $(event.target);
            const passwordInput = $('#password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';

            passwordInput.attr('type', type);
            togglePassword.toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });

        $('#toggle_confirm_password').on('click', (event) => {
            const togglePassword = $(event.target);
            const passwordInput = $('#confirm_password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';

            passwordInput.attr('type', type);
            togglePassword.toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });

        $('#managerForm').on('submit', (event) => {
            event.preventDefault();
            clearValidate();
            disabledFormBtns()
            const formFields = getFormFields();
            $.ajax({
                type: 'POST',
                url: '{{route("manager.registerAPI")}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    ...formFields,
                },
                success: (response) => {
                    if (response == "200") {
                        // $.toast({
                        //     heading: '成功',
                        //     text: 'マネージャー登録が成功しました',
                        //     icon: 'success',
                        //     position: 'top-right'
                        // })
                        window.location.assign('/manager/list');
                        clearValidate();
                        clearForm();
                        enableFormBtns();
                    }
                },
                error: (xhr) => {
                    const errors = JSON.parse(xhr.responseText);
                    if (xhr.status == 422) {
                        showValidateError(errors);
                    } else {
                        $.toast({
                            heading: '失敗',
                            text: errors,
                            icon: 'warning',
                            position: 'top-right'
                        });
                    }
                    enableFormBtns();
                }
            });
        });
    });

    function getFormFields() {
        const subscription_user = $('#subscription_user').val();
        const name = $('#name').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirm_password = $('#confirm_password').val();

        const formFields = {
            "subscription_user": subscription_user,
            "name": name,
            "email": email,
            "password": password,
            "confirm_password": confirm_password,
        }

        return formFields;
    }

    function disabledFormBtns() {
        $('#btn_submit').addClass('disabled');
        $('#btn_cancel').addClass('disabled');
    }

    function enableFormBtns() {
        $('#btn_submit').removeClass('disabled');
        $('#btn_cancel').removeClass('disabled');
    }

    function clearForm() {
        $('#subscription_user').val('');
        $('#name').val('');
        $('#email').val('');
        $('#password').val('');
        $('#confirm_password').val('');
    }



    function showValidateError(errors) {
        Object.keys(errors).forEach((key) => {
            let errHtml = '';
            errors[key].forEach((error) => {
                if (typeof error === "object") {
                    Object.values(error).forEach((nestedError) => {
                        errHtml += `<span>${nestedError}</span><br>`;
                    });
                } else {
                    errHtml += `<span>${error}</span><br>`;
                }
            });
            $(`#${key}-validate`).html(errHtml);
        });
    }

    function clearValidate() {
        $('#subscription_user-validate').text("");
        $('#name-validate').text("");
        $('#email-validate').text("");
        $('#password-validate').text("");
        $('#confirm_password-validate').text("");
    }
</script>


@endsection