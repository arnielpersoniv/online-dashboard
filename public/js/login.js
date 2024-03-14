$('#btn_login').on('click', function() {
    $('#btn_login').empty();
    $('#btn_login').append('Please wait...');
    $('#btn_login').prop("disabled", true);
    $('#form_login').submit();
})