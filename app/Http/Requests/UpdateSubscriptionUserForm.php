<?php

namespace App\Http\Requests;

use App\Http\Requests\Common\BaseValidator;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

class UpdateSubscriptionUserForm extends BaseValidator
{
    public function __construct(ValidatorFactory $validator)
    {
        parent::__construct($validator);
    }
    protected function rules()
    {
        /*
            "company_name": company_name,
            "sub_domain": sub_domain,
            "barcode_type": barcode_type,
            "pref_id": pref_id,
            "zip": zip,
            "address1": address1,
            "address2": address2,
            "tel": tel,
            "manager_mail": manager_mail,
        */
        return [
            'company_name' => 'required',
            'sub_domain' => 'required',
            'barcode_type' => 'required',
            'manager_mail' => 'nullable|email',
        ];
    }

    protected function messages()
    {
        return [
            'required' => '必須フィールドに入力してください',
            'email' => '電子メール アドレスの形式が正しくありません。',
        ];
    }
}
