<form class="form" action="" method="post">
    <div class="form__column">

        <!-- Сообщение об ошибках -->
        <div class="form__errors">
            <p>Пожалуйста, исправьте следующие ошибки:</p>
            <ul>
                <li><strong>E-mail:</strong> Это поле должно быть заполнено</li>
                <li><strong>Пароль:</strong> Это поле должно быть заполнено</li>
            </ul>
        </div>
        <!-- end Сообщение об ошибках -->

        <div class="form__row">
            <label class="form__label" for="email">E-mail:</label>
            <input class="form__input " type="text" name="login[email]" id="email" value="" placeholder="Укажите e-mail">
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input " type="password" name="login[password]" id="password" placeholder="Введите пароль">
        </div>
    </div>

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Войти">
    </div>
</form>
