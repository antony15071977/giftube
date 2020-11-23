<!-- Блок для вывода сообщений -->
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
<form class="form" action="../signin.php" method="post">
    <div class="form__column">

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
        <?php $classname = isset($errors['email']) ? "form__input--error" : "";
        $value = isset($sign_in['email']) ? $sign_in['email'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="email">E-mail:</label>
            <input class="form__input <?= $classname; ?>" type="text" name="email" maxlength="100" id="email" required="required" value="<?= $value; ?>" placeholder="Укажите e-mail"><span id="email_valid">&#x2714;</span>
            <?php if(isset($errors['email'])) : ?>
                <div class="error-notice">
                    <span id="error-mail" class="error-notice__icon error"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
        <span id="valid_email_message"></span>

        <?php $classname = isset($errors['password']) ? "form__input--error" : "";
        $value = isset($sign_in['password']) ? $sign_in['password'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input <?= $classname; ?>" type="password" name="password" minlength="6" required="required" id="password" value="<?= $value; ?>" placeholder="Введите пароль">
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
            <a href="#" id="s-h-pass">Показать пароль</a>
        </div>
    </div>

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Войти">
    </div>
</form>