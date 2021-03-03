$(document).ready(function() {
    //== Проверка email ==
    //регулярное выражение для проверки email
    var pattern = /^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i;
    var mail = $('input[name=email]');
    var mail_valid = $('#email_valid');
    var sign_mail_non_valid = $('#error-mail');
    mail.focus();
    //== Проверка поля, если была возвращена с сервера ошибка
    if ($('#error-mail').hasClass('error')) {
        $('#valid_email_message').html('<span class="mesage_error">Невалидный емейл</span>');
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
            // Название файла, в котором будеemail на существование в базе данных
            url: "../signup/check_email.php",
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
    
    // Проверка поля имя пользователя
    var nickname = $('#nickname');
    var name_valid = $('#name_valid');
    var name_valid_message = $('#name_valid_message');
    var nickname_val = nickname.val();
    if (nickname_val.length < 5 || $('#error-name').hasClass('error')) {
        if ($('#error-name').hasClass('error')) {
            nickname.css("border", "1px solid red");
            name_valid.css("display", "none");
            $('#name_valid_message').html('<span class="mesage_error">Исправьте это поле</span>');
            nickname.focus();
        }
        MaincheckLogin();
    } else {
        $('#name_valid_message').text('');
        nickname.css("border", "1px solid green");
        name_valid.css("display", "block");
        MaincheckLogin();
    }

    function checkLogin() {
        // Место для отправки значения поля email на сервер, через Ajax
        return $.ajax({
            // Название файла, в котором будеemail на существование в базе данных
            url: "../signup/check_login.php",
            // Указывываем каким методом будут переданы данные
            type: "POST",
            // Указывываем в формате JSON какие данные нужно передать
            data: {
                login: nickname.val()
            },
            // Тип содержимого которого мы ожидаем получить от сервера.
            dataType: "html",
            // Функция которая будет выполнятся перед отправкой данных
            beforeSend: function() {
                $('#name_valid_message').html('<span class="mesage_error">Проверяется...</span>');
            },
            // Функция которая будет выполнятся после того как все данные будут успешно получены.
            success: function(data) {
                //Полученный ответ помещаем внутри тега span
                $('#name_valid_message').html(data);
            }
        });
    }

    function MaincheckLogin() {
        nickname.keyup(function() {
            if ($('#error-name').hasClass('error')) {
                $('#error-name').css("display", "none");
            }
            $('#name_valid_message').text('');
            nickname.css("border", "1px solid red");
            name_valid.css("display", "none");
            if (nickname.val().length > 4) {
                checkLogin();
            } else {
                nickname.css("border", "1px solid red");
                name_valid.css("display", "none");
                $('#name_valid_message').html('<span class="mesage_error">Не менее 5 символов</span>');
            }
            
        });
        nickname.blur(function() {
            if (nickname.val() !== '' && nickname.val().length < 5) {
                $('#name_valid_message').html('<span class="mesage_error">Минимум 5 символов</span>');
            } else {
                $('#name_valid_message').text('');
            }
            if (nickname.val() == '') {
               $('#name_valid_message').text('');
               nickname.css("border", "none");
            }
        });
    }
});