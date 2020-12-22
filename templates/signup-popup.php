<?php 
    //Проверяем, если пользователь не авторизован, то выводим форму регистрации, 
    //иначе выводим сообщение о том, что он уже зарегистрирован
    if (!isset($_SESSION['user'])) {

        if(!isset($_GET["hidden_form"])){
        unset($_SESSION["error_messages"]);
        unset($_SESSION["success_messages"]);
        ?>
        <h2 class="content__header-text">Регистрация</h2>
        <form id="reg_form"class="form" action="/signup/signup.php" method="post" enctype="multipart/form-data">
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
                $value_email = isset($sign_up['email']) ? $sign_up['email'] : ""; ?>
                <div class="form__row">
                    <label class="form__label" for="email">E-mail:</label>
                    <input class="form__input <?= $classname; ?>" type="text" name="email" id="email" maxlength="100" value="<?= $value_email; ?>" placeholder="Ваш e-mail" required="required"><span id="email_valid">&#x2714;</span>            
                    <?php if(isset($errors['email'])) : ?>
                        <div class="error-notice">
                            <span id="error-mail" class="error-notice__icon error"></span>
                            <span class="error-notice__tooltip">Измените это поле</span>
                        </div>
                    <?php endif; ?>
                </div>
                <span id="valid_email_message"></span>
                <?php $classname = isset($errors['password']) ? "form__input--error" : "";
                $value_password = isset($sign_up['password']) ? $sign_up['password'] : ""; ?>
                <div class="form__row">
                    <label class="form__label" for="password">Пароль (миним. 6 симв.) (Набор из букв и цифр (латиница)):</label>
                    <input class="form__input <?= $classname; ?>" type="password" name="password" id="password" minlength="6" required="required" value="<?= $value_password; ?>" placeholder="Задайте пароль" pattern="^[a-zA-Z0-9]+$" >           
                    <span id="pass_valid">&#x2714;</span>            
                    <?php if(isset($errors['password'])) : ?>
                        <div class="error-notice">
                            <span id="error-pass" class="error-notice__icon error"></span>
                            <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                        </div>
                    <?php endif; ?>
                </div>
                <span id="valid_password_message" class="mesage_error"></span>
                <?php $classname = isset($errors['confirm_password']) ? "form__input--error" : "";
                $value_confpassword = isset($sign_up['confirm_password']) ? $sign_up['confirm_password'] : ""; ?>
                <div class="form__row">
                    <label class="form__label" for="confirm_password">Еще раз пароль:</label>
                    <input class="form__input <?= $classname; ?>" type="password" name="confirm_password" id="confirm_password" minlength="6" required="required" value="<?= $value_confpassword; ?>" placeholder="Повторите пароль" pattern="^[a-zA-Z0-9]+$">
                    <span id="conf_pass_valid">&#x2714;</span>
                     <?php if(isset($errors['confirm_password'])) : ?>
                        <div class="error-notice">
                            <span id="error-confirm" class="error-notice__icon error"></span>
                            <span class="error-notice__tooltip">Пароли должны совпадать</span>
                        </div>
                    <?php endif; ?>           
                </div>
                <span id="valid_confirm_password_message" class="mesage_error"></span>
                <div>
                    <a href="#" id="s-h-pass">Показать пароль</a>
                </div>
                <?php $classname = isset($errors['name']) ? "form__input--error" : "";
                $value_name = isset($sign_up['name']) ? $sign_up['name'] : ""; ?>
                <div class="form__row">
                    <label class="form__label" for="nickname">Имя (миним. 5 симв.):</label>
                    <input class="form__input <?= $classname; ?>" type="text" name="name" id="nickname" minlength="5" required="required" value="<?= $value_name; ?>" placeholder="Ваш никнейм на сайте">
                    <span id="name_valid">&#x2714;</span>
                    <?php if(isset($errors['name'])) : ?>
                        <div class="error-notice">
                            <span id="error-name" class="error-notice__icon  error"></span>
                            <span class="error-notice__tooltip">Минимум 5 символов</span>
                        </div>
                    <?php endif; ?>
                </div>
                <span id="name_valid_message" class="mesage_error"></span>
                <?php $value = isset($sign_up['avatar_path']) ? $sign_up['avatar_path'] : "Выбрать файл:"; ?>
                <div class="form__row">
                    <label class="form__label" for="avatar">Аватар (опционально):</label>
                    <div class="form__input-file">
                        <input class="visually-hidden" type="file" name="avatar" id="preview" value="<?= $value; ?>">
                        <label for="preview">
                            <span>Выбрать изображение</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form__controls">
                <input class="button form__control" type="submit" name="" value="Отправить">
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#reg_form').submit(function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation;
                    var formRg = $('#reg_form')[0];
                    var formData = new FormData(formRg);
                    $.ajax({
                        url: '/signup/signup-popup.php',
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.loading-overlay').show();
                        },
                        complete: function() {
                            $('.loading-overlay').hide();
                        },
                        success: function(dataResult){
                            $('.modal__content').html(dataResult);
                        }
                    });
                });
            });
        </script>
        <script src="../js/register.js"></script>
                    
        <?php
        }//закрываем условие hidden_form

    }
    else { 
        //Иначе, если пользователь уже авторизирован, то выводим этот блок
?>
        <div id="authorized">
            <h2>Вы уже зарегистрированы</h2>
        </div>

<?php
    }
?>
