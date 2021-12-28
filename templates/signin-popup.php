<h2 class="content__header-text">Вход для своих</h2>
<form id="popup-signin" class="form" action="/signin/signin-popup.php" method="post">
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
        $value_email = isset($sign_in['email']) ? $sign_in['email'] : "";
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['email'])) {
            $value_email = $_GET['email'];
        } 
        ?>
        <div class="form__row">
            <label class="form__label" for="email">E-mail:*</label>
            <input class="form__input <?= $classname; ?>" type="text" name="email" maxlength="100" id="email" required="required" value="<?= $value_email; ?>" placeholder="Укажите e-mail"><span id="email_valid">&#x2714;</span>
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
        <a class="form__label" href="/restore/reset_password.php" onclick="reStore(); return false;">Забыли пароль?</a>
        <br>
        <br>
        <label class="form__label">
            <input type="checkbox" name="remember" checked="checked" /> Запомнить меня
        </label>
    </div>

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Войти">
    </div>
</form>
<script src="../js/auth.js">
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#popup-signin').submit(function(e){
            e.preventDefault();
            e.stopImmediatePropagation;
            current_url = $(location).attr('href');
            data = $('#popup-signin').serialize() + '&current_url=' + current_url;
            $.ajax({
                url: '/signin/signin-popup.php',
                type: 'POST',
                data,
                cache: false,
                beforeSend: function() {
                    Before();
                },
                complete: function() {
                    Complete();
                },
                success: function(dataResult){
                    $('.modal__content').html(dataResult);
                }
            });
        });
    });
</script>
