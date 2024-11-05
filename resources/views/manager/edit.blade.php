@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<div class="container-fluid">
    <form id="managerForm" method="PATCH" class="py-3">
        {{csrf_field()}}
        <div class="mb-3 row d-none">
            <label for="" class="col-sm-2 col-form-label">ID <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="email" class="form-control" placeholder="email@example.com" disabled tabindex="-1"
                    value="{{$manager['admin_user_id']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">契約ユーザー </label>
            <div class="col-sm-10">
                <select class="form-select" id="subscription_user" name="subscription_user" aria-label="契約ユーザー">
                    <option value="" disabled selected>--選択--</option>
                    @foreach ($subscriptionUsers as $subscriptionUser)
                    <option value="{{$subscriptionUser['subscription_user_id']}}"
                        @if($manager['subscription_user_id']==$subscriptionUser['subscription_user_id']) selected
                        @endif>
                        {{$subscriptionUser['company_name']}}
                    </option>
                    @endforeach
                </select>
                <div id="subscription_user-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">名前 <span class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{$manager['name']}}">
                <div id="name-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">メールアドレス </label>
            <div class="col-sm-10">
                <input type="email" class="form-control" placeholder="email@example.com" disabled tabindex="-1"
                    value="{{$manager['email']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="chk_change_password">
                    <label class="form-check-label" for="chk_change_password">
                        パスワードを変更する
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="newPassword" class="col-sm-2 col-form-label">新しいパスワード </label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" disabled>
                <div id="password-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="confirmPassword" class="col-sm-2 col-form-label">パスワード（確認）</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" disabled>
                <div id="confirm_password-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center">
            <a id="btn_cancel" href="{{route("manager.list")}}" type="button" class="btn btn-lavender d-flex align-items-center shadow-sm">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-circle-chevron-left p-0 m-0"></i>
                </span>
                <span>＜戻る</span>
            </a>
            <button id="btn_submit" type="submit" class="btn btn-royal-blue d-flex align-items-center shadow-sm">
                <span>登録</span>
                <span class="ms-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-floppy-disk p-0 m-0"></i>
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

        $('#chk_change_password').on('change', (event) => {
            const chkChangePassword = event.target;
            const newPasswordInput = $('#password');
            const confirmPasswordInput = $('#confirm_password');
            if (chkChangePassword.checked) {
                newPasswordInput.removeAttr('disabled');
                confirmPasswordInput.removeAttr('disabled');
            } else {
                newPasswordInput.val("");
                confirmPasswordInput.val("");
                newPasswordInput.attr('disabled', true);
                confirmPasswordInput.attr('disabled', true);
            };
        });

        $('#managerForm').on('submit', (event) => {
            event.preventDefault();
            clearValidate();
            disabledFormBtns()
            const formFields = getFormFields();
            const currentUrl = window.location.href;
            const managerId = currentUrl.split('/').pop();
            $.ajax({
                type: 'PATCH',
                url: `/manager/update/${managerId}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    ...formFields,
                },
                success: (response) => {
                    if (response == "200") {
                        $.toast({
                            heading: '成功',
                            text: '正常に更新されました',
                            icon: 'success',
                            position: 'top-right'
                        })
                        clearValidate();
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
        const chk_change_password = $('#chk_change_password').is(':checked');

        const formFields = {
            "subscription_user": subscription_user,
            "name": name,
            "email": email,
            "password": password,
            "confirm_password": confirm_password,
            "chk_change_password": chk_change_password,
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