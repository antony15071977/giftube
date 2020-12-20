<?php 
    //Проверяем, если пользователь не авторизован, то выводим форму восстановления, 
    //иначе выводим сообщение о том, что он уже авторизован
    if (!isset($_SESSION['user'])) {

        if(!isset($_GET["hidden_form"])){
?><h2 class="content__header-text">Восстановление пароля.</h2>
<p>Введите Ваш емейл:</p>
<form class="form" action="/restore/reset_password.php" id="restore-popup" method="post">
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
    </div>

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Восстановить">
    </div>
</form>
<script src="../js/auth.js"></script> 
<script type="text/javascript">
    $(document).ready(function() {
        $('#restore-popup').submit(function(e){
            e.preventDefault();
            e.stopImmediatePropagation;
            data = $('#restore-popup').serialize();
            $.ajax({
                url: '/restore/reset_password-popup.php',
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

<?php
        }//закрываем условие hidden_form

    }
    else { 
        //Иначе, если пользователь уже авторизирован, то выводим этот блок
?>
        <div id="authorized">
            <h2>Вы уже авторизованы</h2>
        </div>

<?php
    }
?>