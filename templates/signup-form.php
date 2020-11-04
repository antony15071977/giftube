<form class="form" action="" method="post" enctype="multipart/form-data">
    <div class="form__column">
        <!-- Сообщение об ошибках -->
        <div class="form__errors">
            <p>Пожалуйста, исправьте следующие ошибки:</p>
            <ul>
                <li><strong>E-mail:</strong> Это поле должно быть заполнено</li>
                <li><strong>Пароль:</strong> Это поле должно быть заполнено</li>
                <li><strong>Имя:</strong> Это поле должно быть заполнено</li>
            </ul>
        </div>
        <!-- end Сообщение об ошибках -->
        <div class="form__row">
            <label class="form__label" for="email">E-mail:</label>
            <input class="form__input " type="text" name="signup[email]" id="email" value="" placeholder="Ваш e-mail">
        </div>
        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input " type="password" name="signup[password]" id="password" value="" placeholder="Задайте пароль">
        </div>
        <div class="form__row">
            <label class="form__label" for="nickname">Имя:</label>
            <input class="form__input " type="text" name="signup[name]" id="nickname" value="" placeholder="Ваш никнейм на сайте">
        </div>
        <div class="form__row">
            <label class="form__label" for="avatar">Фото:</label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="signup[avatar]" id="preview" value="Выбрать файл:">
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
