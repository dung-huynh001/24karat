@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<link rel="stylesheet" href="{{ asset('/assets/lib/croppie-2.6.5/croppie.min.css') }}" />
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
        <div class="mb-3 row">
            <label for="avatar" class="col-sm-2 col-form-label">アバター</label>
            <div class="col-sm-10">
                <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                <!-- Input contain Data to send to server -->
                <input type="hidden" id="avatar_data" name="avatar_data">
                <div id="avatar-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <!-- Modal Croppie -->
        <div id="cropModal" class="modal croppie-modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="croppieContainer" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelCrop">キャンセル</button>
                        <button type="button" class="btn btn-primary" id="saveCrop">保存</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Avatar -->
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
                <div id="avatarPreview" class="border p-2" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden;">
                    <img id="avatarPreviewImg" src="{{url('/assets/images/default-user.png')}}" alt="Avatar Preview" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <a id="btn_cancel" href="{{route('manager.list')}}" type="button" class="btn btn-lavender d-flex align-items-center">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-circle-chevron-left"></i>
                </span>
                <span>戻る</span>
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

@include('partials.select2')
@include('partials.form')
<script src="{{ asset('/assets/lib/croppie-2.6.5/croppie.min.js') }}"></script>
<script>
    $(document).ready(() => {

        let croppieInstance;

        $('#avatar').on('click', (e) => {
            resetAvatar();
        });

        $('#avatar').on('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    $('#cropModal').show();
                    croppieInstance = new Croppie(document.getElementById('croppieContainer'), {
                        viewport: {
                            width: 150,
                            height: 150,
                            type: 'circle'
                        },
                        boundary: {
                            width: 300,
                            height: 300
                        },
                        showZoomer: true,
                    });
                    croppieInstance.bind({
                        url: event.target.result,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Cancel
        $('#cancelCrop').on('click', () => {
            $('#cropModal').hide();
            croppieInstance.destroy();
            const val = $('#avatar').val();
            resetAvatar();
        });

        // Save cropped image
        $('#saveCrop').on('click', () => {
            croppieInstance.result({
                type: 'base64', // Return result like base64
                size: 'viewport',
            }).then((base64) => {
                console.log("hello")
                // Show preview
                $('#avatarPreviewImg').attr('src', base64);

                // Assign base64 image to hidden input to send to server
                $('#avatar_data').val(base64);
                $('#cropModal').hide();
                croppieInstance.destroy();
            });
        });

        function resetAvatar() {
            $('#avatar').val(null);
            $('#avatar_data').val(null);
            $('#avatarPreviewImg').attr('src', "{{url('/assets/images/default-user.png')}}");
        }

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
                        localStorage.setItem('create-success', 'true');
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

    $("#btn_test_submit").on('click', function(event) {
        getFormFields();
    });

    function getFormFields() {
        const formData = {};
        const subscription_user = $('#subscription_user').val();
        formData['subscription_user'] = subscription_user;
        $('#managerForm')
            .serializeArray()
            .forEach((field) => {
                formData[field.name] = field.value;
            });

        console.log(formData);

        return formData;
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