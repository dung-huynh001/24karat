/* Add Validate Rules */
$.validator.addMethod("hasSpecialCharacter", (value, element) => {
    return this.optional(element) || /[!@#$%^&*\-_=+]/.test(value);
});

$.validator.addMethod("hasDigital", (value, element) => {
    return this.optional(element) || /\d/.test(value);
});
