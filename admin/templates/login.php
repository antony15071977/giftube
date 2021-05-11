<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Login page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login page">
    <link id="bs-css" href="css/bootstrap-cyborg.min.css" rel="stylesheet">
    <link href="css/charisma-app.css" rel="stylesheet">
    <script src="bower_components/jquery/jquery.min.js"></script>
    <link rel="shortcut icon" href="img/favicon.ico">
    <script src="../js/auth.js">
</script>
</head>

<body>
<div class="ch-container">
    <div class="row">
        
    <div class="row">
        <div class="col-md-12 center login-header">
            <h2>Login page</h2>
            <br>
            <div class="block_for_messages">
            <?php
                //Если в сессии существуют сообщения об ошибках, то выводим их
                if(isset($_SESSION["error_messages"]) && !empty($_SESSION["error_messages"])){
                    echo $_SESSION["error_messages"];

                    //Уничтожаем ячейку error_messages, чтобы сообщения об ошибках не появились заново при обновлении страницы
                    unset($_SESSION["error_messages"]);
                }

                //Если в сессии существуют радостные сообщения, то выводим их
                if(isset($_SESSION["success_messages"]) && !empty($_SESSION["success_messages"])){
                    echo $_SESSION["success_messages"];
                    
                    //Уничтожаем ячейку success_messages,  чтобы сообщения не появились заново при обновлении страницы
                    unset($_SESSION["success_messages"]);
                }
            ?>
            </div>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                Используйте свой логин и пароль админа.
            </div>
            <!-- Сообщение об ошибках -->
            <?php if(isset($errors)) : ?>
                <div class="form__errors">
                    <p>Пожалуйста, исправьте следующие ошибки:</p>
                    <ul>
                        <?php foreach($errors as $error => $val) : ?>
                            <li><strong><?= $dict[$error]; ?>:</strong> <?= $val; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- end Сообщение об ошибках -->
            <form class="form-horizontal" action="/admin/signin.php" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <?php $classname = isset($errors['email']) ? "form__input--error" : "";
                        $value_email = isset($sign_in['email']) ? $sign_in['email'] : "";
                        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['email'])) {
                            $value_email = $_GET['email'];
                        } 
                        ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input type="text" class="form-control <?= $classname; ?>" type="text" name="email" maxlength="100" id="email" required="required"  value="<?= $value_email; ?>" placeholder="Укажите e-mail">
                        <span id="email_valid">&#x2714;</span>
                        <?php if(isset($errors['email'])) : ?>
                            <div class="error-notice">
                                <span id="error-mail" class="error-notice__icon error"></span>
                                <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span id="valid_email_message"></span>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <?php $classname = isset($errors['password']) ? "form__input--error" : "";
                        $value = isset($sign_in['password']) ? $sign_in['password'] : ""; ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input class="form-control <?= $classname; ?>" type="password" name="password" required="required" minlength="6"  id="password" value="<?= $value; ?>" placeholder="Введите пароль">
                        <span id="pass_valid">&#x2714;</span>
                        <?php if(isset($errors['password'])) : ?>
                            <div class="error-notice">
                                <span id="error-pass" class="error-notice__icon error"></span>
                                <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span id="valid_password_message" class="mesage_error"></span>
                    <div>
                        <br>
                        <a href="#" id="s-h-pass">Показать пароль</a>
                    </div>
                    <a class="form__label" href="/restore/reset_password.php">Забыли пароль?</a>
                    <br>
                    <br>
                    <div class="clearfix"></div>

                    <div class="input-prepend">
                        <label class="remember" for="remember"><input type="checkbox" name="remember" checked="checked" id="remember"> Запомнить меня</label>
                    </div>
                    <div class="clearfix"></div>

                    <p class="center col-md-5">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </p>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->


</body>
</html>
