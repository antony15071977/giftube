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

<?php 
    //Проверяем, если пользователь не авторизован, то выводим форму регистрации, 
    //иначе выводим сообщение о том, что он уже зарегистрирован
    if (!isset($_SESSION['user'])) {
        $a = rand(1,10);
        $b = rand(1,10);
        if (isset($_SESSION['captcha'])) {
            unset($_SESSION['captcha']);
        }
       $_SESSION['captcha'] = $a + $b;
        if(!isset($_GET["hidden_form"])){
        ?>
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
                <?php $classname = isset($errors['name']) ? "form__input--error" : "";
                $value_name = isset($sign_up['name']) ? $sign_up['name'] : ""; ?>
                <div class="form__row">
                    <label class="form__label" for="nickname">Имя (миним. 5 симв.):</label>
                    <input class="form__input <?= $classname; ?>" type="text" name="name" id="nickname" minlength="5" value="<?= $value_name; ?>" placeholder="Ваш никнейм на сайте">
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
                        <div id="image" class="upload-file-container"></div>
                    </div>
                </div>
            </div>
            <div class="form__row">
               <label for="captcha" class="form__label">Проверочный код*</label>
                   <div class="verify-block"><?= $a; ?> + <?= $b; ?> = <span class="edit-field">
                    <input class="form-control" autocomplete="off" name="captcha" id="captcha" type="text"></span>
                    </div>
            </div>
            <div class="form__controls">
                <input class="button form__control" type="submit" name="" value="Отправить">
            </div>
        </form>
        <script>function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image').css('background', 'transparent url('+e.target.result +') center top / cover no-repeat');
                    $('#image').css('width', '80px');
                    $('#image').css('height', '80px');
                     $('#image').css('margin', '0px auto 20px');
                     $('#image').css('position', 'absolute');
                     $('#image').css('top', '0');
                     $('#image').css('right', '0');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#preview").change(function(){
            readURL(this);
        });</script>            
        <?php
        }//закрываем условие hidden_form

    }
    else { 
        //Иначе, если пользователь уже авторизирован, то выводим этот блок
?>
       <!--  <div id="authorized">
            <h2 style="color: red">Вы уже зарегистрированы</h2>
        </div> -->
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                setTimeout(function() {
                    var url = "/";
                    $(location).attr('href', url);
                }, 350);
            });
        </script>

<?php
    }
?>
