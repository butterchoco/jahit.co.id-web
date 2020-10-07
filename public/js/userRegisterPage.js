let passwordType = true;
$("#passwordHelp").click(() => {
    if (passwordType) {
        $("#passwordHelp").html('<i class="far fa-eye"></i>');
        $("#password").attr("type", "text");
    } else {
        $("#passwordHelp").html('<i class="far fa-eye-slash"></i>');
        $("#password").attr("type", "password");
    }
    passwordType = !passwordType;
});

let confirmationPasswordType = true;
$("#confirmationPasswordHelp").click(() => {
    if (confirmationPasswordType) {
        $("#confirmationPasswordHelp").html('<i class="far fa-eye"></i>');
        $("#password-confirm").attr("type", "text");
    } else {
        $("#confirmationPasswordHelp").html('<i class="far fa-eye-slash"></i>');
        $("#password-confirm").attr("type", "password");
    }
    confirmationPasswordType = !confirmationPasswordType;
});
