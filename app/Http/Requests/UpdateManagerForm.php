<?php
namespace App\Http\Requests;
use App\Http\Requests\Common\BaseValidator;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
class UpdateManagerForm extends BaseValidator
{
    public function __construct(ValidatorFactory $validator)
    {
        parent::__construct($validator);
    }
    protected function rules()
    {
        return [
            'subscription_user' => 'required',
            'name' => 'required',
            'password' => 'required|string|min:8|regex:/[0-9]/|regex:/[!@#$%^&*\-_+=]/',
            'confirm_password' => 'required|string|min:8|same:password',
        ];
    }

    protected function messages()
    {
        return [
            'required' => '必須フィールドに入力してください',
            'password.min' => '新しいパスワードの文字数は、8文字以上である必要があります。',
            'password.regex' => [
                'regex:/[0-9]/' => 'パスワードは最低1桁数を含む必要があります。',
                'regex:/[!@#$%^&*\-_+=]/' => 'パスワードは最低1特殊文字を含む必要があります。特殊文字 （ !@#$%^&*-_+=）'
            ],
            'confirm_password.min' => 'パスワード（確認）の文字数は、8文字以上である必要があります。',
            'confirm_password.same' => 'パスワード（確認）と新しいパスワードが一致しません。',
        ];
    }

}