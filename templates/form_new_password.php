  <div class="content__main-col">
    <header class="content__header content__header--left-pad">
      <h2 class="content__header-text"><?= $title; ?></h2>
      <a class="button button--transparent content__header-button" href="/">Назад</a>
    </header>
    <?php 
    //Проверяем, если пользователь не авторизован, то выводим форму восстановления, 
    //иначе выводим сообщение о том, что он уже авторизован
    if (!isset($_SESSION['user'])) {
      ?>
      <h2 class="content__header-text">Установка нового пароля</h2>

      <form id="reg_form"class="form" action="../update_password.php" method="post" enctype="multipart/form-data">
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

          <?php $classname = isset($errors['password']) ? "form__input--error" : "";
          $value = isset($sign_up['password']) ? $sign_up['password'] : ""; ?>
          <div class="form__row">
            <label class="form__label" for="password">Пароль (миним. 6 симв.):</label>
            <input class="form__input <?= $classname; ?>" type="password" name="password" id="password"  value="<?= $value; ?>" placeholder="Задайте пароль" >           
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
          $value = isset($sign_up['confirm_password']) ? $sign_up['confirm_password'] : ""; ?>
          <div class="form__row">
            <label class="form__label" for="confirm_password">Еще раз пароль:</label>
            <input class="form__input <?= $classname; ?>" type="password" name="confirm_password" id="confirm_password"  value="<?= $value; ?>" placeholder="Повторите пароль">
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
          <input type="hidden" name="token" value="<?= $send_token ?>">
          <input type="hidden" name="email" value="<?= $email ?>">
        </div>
        <div class="form__controls">
          <input class="button form__control" type="submit" name="set_new_password" value="Изменить пароль">
        </div>
      </form>

      <?php
      

    }
    else { 
        //Иначе, если пользователь уже авторизирован, то выводим этот блок
      ?>
      <div id="authorized">
        <h2 class="content__header-text">Вы уже авторизованы</h2>
      </div>

      <?php
    }
    ?>
  </div>

