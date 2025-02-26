@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<div class="container-fluid">
    <form id="subscriptionUserForm" class="py-3">
        {{csrf_field()}}
        <div class="mb-4 row">
            <label for="company_name" class="col-sm-2 col-form-label">契約ユーザー名 <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <input type="text" id="company_name" name="company_name" class="form-control"
                    value="{{$subscriptionUser->company_name}}" autocomplete="off">
                <div id="company_name-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="sub_domain" class="col-sm-2 col-form-label">サブドメイン <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" id="sub_domain" name="sub_domain" class="form-control"
                        value="{{$subscriptionUser->sub_domain}}" autocomplete="off">
                    <span class="input-group-text">{{ env('APP_URL') }}</span>
                </div>
                <div id="sub_domain-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="barcode_type" class="col-sm-2 col-form-label">バーコード種類 <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-10">
                <select id="barcode_type" name="barcode_type" class="form-select" aria-label="契約ユーザー">
                    <option value="" disabled {{empty($subscriptionUser->barcode_type) ? 'selected' : ''}}>--選択--
                    </option>
                    @foreach ($barcodeOptions as $value => $label)
                        <option value="{{$value}}" {{$subscriptionUser->barcode_type == $value ? 'selected' : ''}}>{{$label}}
                        </option>
                    @endforeach
                </select>
                <div id="barcode_type-validate" class="text-sunset-orange"></div>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="pref_id" class="col-sm-2 col-form-label">都道府県</label>
            <div class="col-sm-10">
                <select id="pref_id" name="pref_id" class="form-select" aria-label="契約ユーザー">
                    <option value="" disabled {{empty($subscriptionUser->pref_id) ? 'selected' : ''}}>--選択--</option>
                    @foreach ($prefectures as $prefecture)
                        <option value="{{$prefecture->id}}" {{$subscriptionUser->pref_id == $value ? 'selected' : ''}}>
                            {{$prefecture->name}}</option>
                    @endforeach
                </select>
                <div id="pref_id-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="first_zip" class="col-sm-2 col-form-label">郵便番号</label>
            <div class="col-sm-6">
                <div class="input-group gap-2">
                    <input id="first_zip" name="first_zip" type="text" maxlength="3" class="form-control text-center"
                        value="{{substr($subscriptionUser->zip, 0, strpos($subscriptionUser->zip, '-'))}}"
                        autocomplete="off" onkeypress="return typingNumber(event)">
                    <input id="last_zip" name="last_zip" type="text" maxlength="4" class="form-control text-center"
                        value="{{substr($subscriptionUser->zip, strrpos($subscriptionUser->zip, '-') + 1)}}"
                        autocomplete="off" onkeypress="return typingNumber(event)">
                    <button type="button" class="btn btn-danger" onclick="autoFillAddress1()">自動入力</button>
                </div>
                <div id="zip-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="address1" class="col-sm-2 col-form-label">住所1</label>
            <div class="col-sm-10">
                <input type="text" id="address1" name="address1" class="form-control"
                    value="{{ $subscriptionUser->address1 }}" autocomplete="off">
                <div id="address1-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="address2" class="col-sm-2 col-form-label">住所2</label>
            <div class="col-sm-10">
                <input type="text" id="address2" name="address2" class="form-control"
                    value="{{ $subscriptionUser->address2 }}" autocomplete="off">
                <div id="address2-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="first_tel" class="col-sm-2 col-form-label">電話番号</label>
            <div class="col-sm-8">
                <div class="input-group gap-2">
                    <input id="first_tel" name="first_tel" type="text" maxlength="3" class="form-control text-center"
                        value="{{substr($subscriptionUser->tel, 0, 3)}}" autocomplete="off"
                        onkeypress="return typingNumber(event)">
                    <input id="second_tel" name="second_tel" type="text" maxlength="3" class="form-control text-center"
                        value="{{substr($subscriptionUser->tel, 3, 3)}}" autocomplete="off"
                        onkeypress="return typingNumber(event)">
                    <input id="last_tel" name="last_tel" type="text" maxlength="4" class="form-control text-center"
                        value="{{substr($subscriptionUser->tel, 6, 4)}}" autocomplete="off"
                        onkeypress="return typingNumber(event)">
                </div>
                <div id="tel-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="mb-4 row">
            <label for="manager_mail" class="col-sm-2 col-form-label">担当者メールアドレス</label>
            <div class="col-sm-10">
                <input id="manager_mail" name="manager_mail" class="form-control"
                    value="{{$subscriptionUser->manager_mail}}" autocomplete="off">
                <div id="manager_mail-validate" class="text-sunset-orange"></div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <a id="btn_cancel" href="{{route('subscription_user.list')}}" type="button"
                class="btn btn-lavender d-flex align-items-center">
                <span class="me-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-circle-chevron-left"></i>
                </span>
                <span>戻る</span>
            </a>
            <button id="btn_submit" type="submit" class="btn btn-royal-blue d-flex align-items-center">
                <span>登録</span>
                <span class="ms-2 fs-7 d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-floppy-disk p-0 m-0"></i>
                </span>
            </button>
        </div>
    </form>
</div>
@include('partials.select2')
@include('partials.form')
<script>
    let previousRequest = null;

    function autoFillAddress1() {
        const first_zip_val = $('#first_zip').val();
        const last_zip_val = $('#last_zip').val();
        const address1 = $('#address1');
        const code = first_zip_val + '' + last_zip_val;

        if (previousRequest && previousRequest.readyState !== 4) {
            previousRequest.abort();
        }

        previousRequest = $.ajax({
            url: `/subscription_user/autofill_address1/${code}`,
            type: 'GET',
            success: (res) => {
                address1.val(res);
            },
        });
    }

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


        $('#subscriptionUserForm').on('submit', (event) => {
            event.preventDefault();
            clearValidate();
            disabledFormBtns()
            const formFields = getFormFields();
            const currentUrl = window.location.href;
            const subscriptionUserId = currentUrl.split('/').pop();
            $.ajax({
                type: 'PATCH',
                url: `/subscription_user/update/${subscriptionUserId}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    ...formFields,
                },
                success: (response) => {
                    if (response == "200") {
                        localStorage.setItem('update-success', 'true');
                        window.location.assign('/subscription_user/list');
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
        const company_name = $('#company_name').val();
        const sub_domain = $('#sub_domain').val();
        const barcode_type = $('#barcode_type').val();
        const pref_id = $('#pref_id').val();
        const zip = $('#first_zip').val() + '-' + $('#last_zip').val();
        const address1 = $('#address1').val();
        const address2 = $('#address2').val();
        const tel = $('#first_tel').val() + $('#second_tel').val() + $('#last_tel').val();
        const manager_mail = $('#manager_mail').val();

        const formFields = {
            "company_name": company_name,
            "sub_domain": sub_domain,
            "barcode_type": barcode_type,
            "pref_id": pref_id,
            "zip": zip,
            "address1": address1,
            "address2": address2,
            "tel": tel,
            "manager_mail": manager_mail,
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
        $('#company_name-validate').text("");
        $('#sub_domain-validate').text("");
        $('#barcode_type-validate').text("");
        $('#pref_id-validate').text("");
        $('#zip-validate').text("");
        $('#address1-validate').text("");
        $('#address2-validate').text("");
        $('#tel-validate').text("");
        $('#manager_mail-validate').text("");
    }
</script>


@endsection