@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<div class="container-fluid">
    <form id="subscriptionUserForm" class="py-3">
        {{csrf_field()}}
        <div class="mb-4 row">
            <label for="field_name" class="col-sm-3 col-form-label">フィールド名 <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="field_name" name="field_name" class="form-control" autocomplete="off">
                <div id="field_name-validate" class="text-sunset-orange validate-msg"></div>
            </div>
        </div>
        <div class="mb-4 row">
            <label for="sub_domain" class="col-sm-3 col-form-label">コントロールタイプ <span
                    class="text-sunset-orange">*</span></label>
            <div class="col-sm-9">
                <select id="member_field_format_master_id" name="member_field_format_master_id" class="form-select" aria-label="契約ユーザー">
                    <option value="" disabled selected>--選択--
                    </option>
                    @foreach ($fieldFormatMasters as $fieldFormatMaster)
                    <option
                        value="{{ $fieldFormatMaster->member_field_format_master_id }}">
                        {{ $fieldFormatMaster->member_field_format_master_name }}
                    </option>
                    @endforeach
                </select>
                <div id="member_field_format_master_id-validate" class="text-sunset-orange validate-msg"></div>
            </div>
        </div>
        <div class="row">
            <label class="col-form-label">フィールドのフォーマット</label>
        </div>

        <!-- Get partial view on selected value changed -->
        <div id="dynamic_partial"></div>

        <div class="mb-4 row">
            <label for="csv_input_rule" class="col-sm-3 col-form-label">CSVでの入力ルール</label>
            <div class="col-sm-9">
                <input type="text" id="csv_input_rule" name="field_name" class="form-control" placeholder="テキストを入力してください。" autocomplete="off">
                <div id="csv_input_rule-validate" class="text-sunset-orange validate-msg"></div>
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
                <span
                    class="ms-2 square-8 rounded-circle bg-white fs-9 text-royal-blue d-inline-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-plus p-0 m-0"></i>
                </span>
            </button>
        </div>
    </form>
</div>
@include('partials.select2')
@include('partials.form')
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


        $('#member_field_format_master_id').on('change', (event) => {
            const selectedValue = $(event.target).val(); // Lấy giá trị của dropdown
            $.ajax({
                url: `/dynamic-field/get_dynamic_field_partial/${selectedValue}`, // URL động
                type: 'GET',
                success: (res) => {
                    console.log(res); // Log kết quả trả về
                    $('#dynamic_partial').html(res); // Thay nội dung của dynamic_partial
                },
                error: (err) => {
                    console.error('Error fetching dynamic field partial:', err);
                }
            });
        });



        $('#subscriptionUserForm').on('submit', (event) => {
            event.preventDefault();
            clearValidate();
            disabledFormBtns()
            const formFields = getFormFields();
            const currentUrl = window.location.href;
            const subscriptionUserId = currentUrl.split('/').pop();
            $.ajax({
                type: 'POST',
                url: `/subscription_user/create`,
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
        const field_name = $('#field_name').val();
        const sub_domain = $('#sub_domain').val();
        const member_field_format_master_id = $('#member_field_format_master_id').val();
        const pref_id = $('#pref_id').val();
        const zip = $('#first_zip').val() + '-' + $('#last_zip').val();
        const address1 = $('#address1').val();
        const address2 = $('#address2').val();
        const tel = $('#first_tel').val() + $('#second_tel').val() + $('#last_tel').val();
        const manager_mail = $('#manager_mail').val();

        const formFields = {
            "field_name": field_name,
            "sub_domain": sub_domain,
            "member_field_format_master_id": member_field_format_master_id,
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
        $('#field_name').val('');
        $('#sub_domain').val('');
        $('#member_field_format_master_id').val('');
        $('#pref_id').val('');
        $('#zip').val('');
        $('#address1').val('');
        $('#address2').val('');
        $('#tel').val('');
        $('#manager_mail').val('');
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

    // function clearValidate() {
    //     $('#field_name-validate').text("");
    //     $('#sub_domain-validate').text("");
    //     $('#member_field_format_master_id-validate').text("");
    //     $('#pref_id-validate').text("");
    //     $('#zip-validate').text("");
    //     $('#address1-validate').text("");
    //     $('#address2-validate').text("");
    //     $('#tel-validate').text("");
    //     $('#manager_mail-validate').text("");
    // }
</script>


@endsection