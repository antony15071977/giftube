<h2 class="content__header-text">Вход для своих</h2>
<?php $message = $_GET['message']; $email = $_GET['email']; ?>
<?= $message; ?>
<form action="/signin/signin-popup.php" class="form" id="popup-signin" method="post" name="popup-signin">
    <div class="form__column">
        <?php $value_email = $email; ?>
        <div class="form__row">
            <label class="form__label" for="email">E-mail:</label>
            <input class="form__input" id="email" maxlength="100" name="email" placeholder="Укажите e-mail" required="required" type="text" value="<?= $value_email; ?>">
            <span id="email_valid">&#x2714;</span>
            <?php if(isset($errors['email'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon error" id="error-mail"></span> <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div><span id="valid_email_message"></span>
        <div class="form__row">
            <label class="form__label" for="password">Пароль:</label>
            <input class="form__input" id="password" name="password" placeholder="Введите пароль" required="required" type="password" value="">
            <span id="pass_valid">&#x2714;</span>
            <?php if(isset($errors['password'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon error" id="error-pass"></span> <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
        </div>
        <span class="mesage_error" id="valid_password_message"></span>
        <div>
            <a href="#" id="s-h-pass">Показать пароль</a>
        </div>
        <a class="form__label" href="/restore/reset_password.php" onclick="reStore(); return false;">Забыли пароль?</a>
        <br>
        <br>
        <label class="form__label"><input checked="checked" name="remember" type="checkbox"> Запомнить меня</label>
    </div>
    <div class="form__controls">
        <input class="button form__control" name="" type="submit" value="Войти">
    </div>
</form>
<script src="../js/auth.js"></script> 
<script type="text/javascript">
    $(document).ready(function() {
        var password = $('input[name=password]');
            password.focus();
        $('#popup-signin').submit(function(e){
            e.preventDefault();
            e.stopImmediatePropagation;
            current_url = '/';
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