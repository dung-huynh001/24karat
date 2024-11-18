/* Add Validate Rules */
$.validator.addMethod(
    "hasSpecialCharacter",
    function (value, element) {
        return this.optional(element) || /[!@#$%^&*\-_=+]/.test(value);
    },
    "パスワードは最低1特殊文字を含む必要があります。特殊文字 （ !@#$%^&*-_+=）"
);

$.validator.addMethod(
    "hasDigital",
    function (value, element) {
        return this.optional(element) || /\d/.test(value);
    },
    "パスワードは最低1桁数を含む必要があります。"
);

$.validator.addMethod(
    "customEmail",
    function (value, element) {
        // Biểu thức regex để kiểm tra định dạng email
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return this.optional(element) || emailPattern.test(value);
    },
    "電子メール アドレスの形式が正しくありません。"
);

/* Modify error messages */
$.extend($.validator.messages, {
    required: "必須フィールドに入力してください",
    email: "電子メール アドレスの形式が正しくありません。",
    minlength: "パスワード（確認）の文字数は、8文字以上である必要があります。",
    equalTo: "パスワード（確認）と新しいパスワードが一致しません。",
});

/* Input Typing */
function typingNumber(event) {
    let charCode = event.which ? event.which : event.keyCode;
    return charCode >= 48 && charCode <= 57;
}
