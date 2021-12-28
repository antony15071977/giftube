<div class="block_for_messages">
    <?php
        if(isset($_SESSION["error_messages"]) && !empty($_SESSION["error_messages"])){
            echo $_SESSION["error_messages"];
            unset($_SESSION["error_messages"]);

            ?>
            <form class="form" action="/signin/signin.php" method="post">
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
                        <label class="form__label" for="email">E-mail:</label>
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
                    <a class="form__label" href="/restore/reset_password.php">Забыли пароль?</a>
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
        <?php

        }if(isset($_SESSION["success_messages"]) && !empty($_SESSION["success_messages"])){
            echo $_SESSION["success_messages"];
            unset($_SESSION["success_messages"]);            
        }
    ?>
</div>
<?php if ($question != NULL && $category != NULL && $url != NULL && $user_id != NULL && $title != NULL): ?>    
    <div align="center">
        <h2 class="content__header-text">Редактирование вопроса</h2>
        <table style="border:1px solid #000; margin:5px; background-color:#eee; color: black;">
            <tr align="center">
                <td width="190">Пользователь id=<b><?= $user_id; ?></b></td>
                <td width="200">Title - <b><?= $title; ?></b></td>
                <td width="170">Категория - <b><?= $nameCat; ?></b>
                </td>
                <td></td>
            </tr>
            <tr align="center">
                <form method="POST" action="/gif/question.php">
                    <td colspan="3">
                        <textarea cols="70" rows="5" name="question"><?= $question; ?></textarea>
                    </td>
                    <td colspan="4">
                        <p>Изменить категорию:</p>
                        <select class="<?= $classname; ?>" name="category" id="category">
                            <option value="">Изменить категорию</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>" <?php if (isset($category) && ($category == $cat['id'])) { print(' selected'); }; ?> ><?= $cat['nameCat']; ?></option>
                            <?php endforeach; ?>

                        </select>
                        <br>
                        <br>
                        <input type="hidden" name="user_id" value='<?= $user_id; ?>'>
                        <input type="hidden" name="title" value='<?= $title; ?>'>
                        <input type="hidden" name="url" value='<?= $url; ?>'>
                        <input type="submit" value="Сохранить">
                    <br>
                    <a href="/gif/question.php?del=<?= $user_id; ?>">Заблокировать юзера</a>
                    </td>
                </form>
            </tr>
        </table>
            <br>
            <br>
    </div>

<?php endif; ?>