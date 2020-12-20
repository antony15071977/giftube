$(document).ready(function() {
   
    // ==Валидация полей с паролем ==
    // Значок глаза
    $('#s-h-pass').click(function() {
        var type = $('#password').attr('type') == "text" ? "password" : 'text',
            c = $(this).text() == "Скрыть пароль" ? "Показать пароль" : "Скрыть пароль";
        $(this).text(c);
        $('#password').prop('type', type);
        var type2 = $('#confirm_password').attr('type') == "text" ? "password" : 'text';
        $('#confirm_password').prop('type', type);
        return false;
    });
    // Проверка паролей
    var password = $('input[name=password]');
    var pass_valid = $('#pass_valid');
    var password_val = password.val();
    var sign_pass_non_valid = $('#error-pass');
    var confirm_password = $('input[name=confirm_password]');
    var conf_pass_valid = $('#conf_pass_valid');
    var confirm_password_val = confirm_password.val();
    //== Проверка поля, если была возвращена с сервера ошибка
    if (password_val.length < 6 && $('#error-pass').hasClass('error')) {
        $('#valid_password_message').text('Исправьте это поле');
        password.css("border", "1px solid red");
        pass_valid.css("display", "none");
        password.focus();
        password.focus(keyupPassword());
    } else {
        //== Проверка пароля если страница регистрации была загружена после ошибки НЕ в пароле 
        if (password.val() != '' || password.val().length > 6) {
            $('#valid_password_message').text('');
            password.css("border", "1px solid green");
            pass_valid.css("display", "block");
            password.focus(keyupPassword());
        }
        //== Это ситуация, когда страница регистрации загружена впервые
        $('#valid_password_message').text('');
        password.focus();
        password.focus(keyupPassword());
    }

    function keyupPassword() {
        password.on('keyup', function() {
            if ($('#error-pass').hasClass('error')) {
                $('#error-pass').css("display", "none");
            }
            if (password.val() != '') {
                $('#valid_password_message').text('');
                if (confirm_password.val() != '') {
                    if (password.val() !== confirm_password.val() || confirm_password.val().length < 6) {
                        //Выводим сообщение об ошибке
                        $('#valid_confirm_password_message').text('Пароли должны совпадать');
                        confirm_password.css("border", "1px solid red");
                        conf_pass_valid.css("display", "none");
                    } else {
                        // Убираем сообщение об ошибке у поля для ввода второго пароля
                        $('#valid_confirm_password_message').text('');
                        confirm_password.css("border", "1px solid green");
                        conf_pass_valid.css("display", "block");
                    }
                }
                //Если длина введённого пароля меньше шести символов, то выводим сообщение об ошибке
                if (password.val().length < 6) {
                    //Выводим сообщение об ошибке
                    $('#valid_password_message').text('');
                    password.css("border", "1px solid red");
                    pass_valid.css("display", "none");
                } else {
                    // Убираем сообщение об ошибке у поля для ввода пароля
                    $('#valid_password_message').text('');
                    password.css("border", "1px solid green");
                    pass_valid.css("display", "block");
                }
                password.blur(function() {
                    if (password.val().length < 6) {
                        //Выводим сообщение об ошибке
                        $('#valid_password_message').text('Минимальная длина пароля 6 символов');
                    }
                });
            } else {
                $('#valid_password_message').text('Введите пароль');
                password.css("border", "1px solid red");
                pass_valid.css("display", "none");
            }
        });
    }
    if (password.val() !== confirm_password.val() || $('#error-confirm').hasClass('error')) {
        $('#valid_confirm_password_message').text('Исправьте это поле');
        confirm_password.css("border", "1px solid red");
        conf_pass_valid.css("display", "none");
        confirm_password.focus();
        confirm_password.focus(keyupConfPassword());
    } else {
        //== Проверка пароля если страница регистрации была загружена после ошибки НЕ в повторе пароля
        if (password.val() == confirm_password.val() && confirm_password.val() != '') {
            $('#valid_confirm_password_message').text('');
            confirm_password.css("border", "1px solid green");
            conf_pass_valid.css("display", "block");
        }
        //== Это ситуация, когда страница регистрации загружена впервые
        $('#valid_confirm_password_message').text('');
        confirm_password.focus(keyupConfPassword());
    }

    function keyupConfPassword() {
        confirm_password.on('keyup', function() {
            if ($('#error-confirm').hasClass('error')) {
                $('#error-confirm').css("display", "none");
            }
            if (confirm_password.val() != '') {
                $('#valid_confirm_password_message').text('');
                //Если длина введённого пароля меньше шести символов, то выводим сообщение об ошибке
                if (password.val() !== confirm_password.val() && confirm_password.val() != '' || confirm_password.val().length < 6) {
                    //Выводим сообщение об ошибке
                    $('#valid_confirm_password_message').text('Исправьте это поле');
                    confirm_password.css("border", "1px solid red");
                    conf_pass_valid.css("display", "none");
                } else {
                    // Убираем сообщение об ошибке у поля для ввода второго пароля
                    $('#valid_confirm_password_message').text('');
                    confirm_password.css("border", "1px solid green");
                    conf_pass_valid.css("display", "block");
                }
                confirm_password.blur(function() {
                    if (password.val() !== confirm_password.val()) {
                        //Выводим сообщение об ошибке
                        $('#valid_confirm_password_message').text('Пароли должны совпадать');
                    }
                });
            } else {
                $('#valid_confirm_password_message').text('Введите повтор пароля');
                confirm_password.css("border", "1px solid red");
                conf_pass_valid.css("display", "none");
            }
        });
    }
 
});