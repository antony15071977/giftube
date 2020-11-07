<form class="form" action="../signup.php" method="post" enctype="multipart/form-data">
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
        $value = isset($sign_up['email']) ? $sign_up['email'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="email">E-mail:</label>
            <input class="form__input <?= $classname; ?>" type="text" name="email" id="email" value="<?= $value; ?>" placeholder="Ваш e-mail">
            <?php if(isset($errors['email'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
        <?php $classname = isset($errors['password']) ? "form__input--error" : "";
        $value = isset($sign_up['password']) ? $sign_up['password'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input <?= $classname; ?>" type="password" name="password" id="password" value="<?= $value; ?>" placeholder="Задайте пароль">
            <?php if(isset($errors['password'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
        <?php $classname = isset($errors['name']) ? "form__input--error" : "";
        $value = isset($sign_up['name']) ? $sign_up['name'] : ""; ?>
        <div class="form__row">
            <label class="form__label" for="nickname">Имя:</label>
            <input class="form__input <?= $classname; ?>" type="text" name="name" id="nickname" value="<?= $value; ?>" placeholder="Ваш никнейм на сайте">
            <?php if(isset($errors['name'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
        <?php $value = isset($sign_up['avatar_path']) ? $sign_up['avatar_path'] : "Выбрать файл:"; ?>
        <div class="form__row">
            <label class="form__label" for="avatar">Фото:</label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="avatar" id="preview" value="<?= $value; ?>">
                <label for="preview">
                    <span>Выбрать файл</span>
                </label>
            </div>
        </div>
    </div>
    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Отправить">
    </div>
</form>