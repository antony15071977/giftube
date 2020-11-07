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
            <input class="form__input <?= $classname; ?>" type="text" name="email" id="email" value="<?= $value; ?>" placeholder="Укажите e-mail">
            <?php if(isset($errors['email'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>

        <?php $classname = isset($errors['password']) ? "form__input--error" : "";
        $value = isset($sign_in['password']) ? $sign_in['password'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input <?= $classname; ?>" type="password" name="password" id="password" value="<?= $value; ?>" placeholder="Введите пароль">
            <?php if(isset($errors['password'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Войти">
    </div>
</form>