function showEditPassword(value) {
    var placeholder = document.getElementById('group-password-placeholder');
    var edit = document.getElementById('group-password-edit');
    
    if (value) {
        placeholder.hidden = true;
        edit.hidden = false;
    } else {
        placeholder.hidden = false;
        edit.hidden = true;
    }
}

function checkPasswordConfirm() {
    var password = document.getElementsByName('password-plain')[0];
    var passwordConfirm = document.getElementsByName('password-confirm')[0];
    var textError = document.getElementById('text-password-confirm-error');
    
    if (password.value != passwordConfirm.value && passwordConfirm.value != '') {
        textError.hidden = false;
    } else {
        textError.hidden = true;
    }
}