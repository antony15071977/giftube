$(document).ready(function() {
    //== Проверка email ==
    //регулярное выражение для проверки email
    var pattern = /^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i;
    var mail = $('input[name=email]');
    var mail_valid = $('#email_valid');
    var sign_mail_non_valid = $('#error-mail');
    //== Проверка поля, если была возвращена с сервера ошибка
    if ($('#error-mail').hasClass('error')) {
        $('#valid_email_message').html('<span class="mesage_error">Незарегистрированный емейл</span>');
        mail.css("border", "1px solid red");
        mail_valid.css("display", "none");
        mail.focus();
        mail.focus(keyupMail());
    } else {
        //== Проверка пароля если страница регистрации была загружена после ошибки НЕ в емейле 
        if (mail.val().search(pattern) == 0) {
            $('#valid_email_message').text('');
            mail.css("border", "1px solid green");
            mail_valid.css("display", "block");
            checkMail();
        }
        //== Это ситуация, когда страница регистрации загружена впервые
        $('#valid_email_message').text('');
        mail.focus();
        mail.focus(keyupMail());
    }

    function keyupMail() {
        mail.on('keyup', function() {
            if ($('#error-mail').hasClass('error')) {
                $('#error-mail').css("display", "none");
            }
            if (mail.val() != '') {
                $('#valid_email_message').html('<span></span>');
                if (mail.val().search(pattern) == 0) {
                    mail.css("border", "1px solid green");
                    mail_valid.css("display", "block");
                    checkMail();
                } else {
                    mail.css("border", "1px solid red");
                    mail_valid.css("display", "none");
                    $('#valid_email_message').html('<span class="mesage_error">Не правильный Email</span>');
                }
                mail.blur(function() {
                    if (mail.val().search(pattern) == 0) {
                        checkMail();
                    } else {
                        mail.css("border", "1px solid red");
                        mail_valid.css("display", "none");
                        $('#valid_email_message').html('<span class="mesage_error">Не правильный Email</span>');
                    }
                });
            } else {
                $('#valid_email_message').html('<span class="mesage_error">Введите Ваш email</span>');
                mail.css("border", "1px solid red");
                mail_valid.css("display", "none");
            }
        });
    }

    function checkMail() {
        // Место для отправки значения поля email на сервер, через Ajax
        return $.ajax({
            // Название файла, в котором будет проверяться email на существование в базе данных
            url: "../signin/check_email_auth.php",
            // Указывываем каким методом будут переданы данные
            type: "POST",
            // Указывываем в формате JSON какие данные нужно передать
            data: {
                email: mail.val()
            },
            // Тип содержимого которого мы ожидаем получить от сервера.
            dataType: "html",
            // Функция которая будет выполнятся перед отправкой данных
            beforeSend: function() {
                $('#valid_email_message').html('<span class="mesage_error">Проверяется...</span>');
            },
            // Функция которая будет выполнятся после того как все данные будут успешно получены.
            success: function(data) {
                //Полученный ответ помещаем внутри тега span
                $('#valid_email_message').html(data);
            }
        });
    }
    // ==Валидация полей с паролем ==
    // Значок глаза
    $('#s-h-pass').click(function() {
        var type = $('#password').attr('type') == "text" ? "password" : 'text',
            c = $(this).text() == "Скрыть пароль" ? "Показать пароль" : "Скрыть пароль";
        $(this).text(c);
        $('#password').prop('type', type);
        return false;
    });

    // Проверка паролей
    var password = $('input[name=password]');
    var pass_valid = $('#pass_valid');
    var password_val = password.val();
    var sign_pass_non_valid = $('#error-pass');
    
    //== Проверка поля, если была возвращена с сервера ошибка
    if ($('#error-pass').hasClass('error')) {
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
        else {
        //== Это ситуация, когда страница регистрации загружена впервые
        $('#valid_password_message').text('');
        password.focus(keyupPassword());
        }
    }

    function keyupPassword() {
        password.on('keyup', function() {
            if ($('#error-pass').hasClass('error')) {
                $('#error-pass').css("display", "none");
            }
            if (password.val() != '') {
                $('#valid_password_message').text('');
                
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
        
    

});