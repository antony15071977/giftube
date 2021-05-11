<h2 class="">Редактировать User</h2>
<br>
<form class="form" id="edit-form" action="/admin/users/users.php" method="post" enctype="multipart/form-data">
    <div class="form__columns">
        <div class="form__column form__column--short">
            <div class="form__row">
                <label class="form__label" for="preview">Avatar файл:</label>
                <div class="upload">
                    <?php 
                    $classname = isset($errors['gif-img']) ? "form__input--error" : "";
                    $value = isset($gif['avatar_path']) ? '/uploads/avatar/'.$gif['avatar_path'] : "";
                    $path = isset($gif['avatar_path']) ? '/uploads/avatar/'.$gif['avatar_path'] : "/img/no-pic.png"; 
                    ?>
                    <div class="preview" id="image" style="width: 192px; height: 192px;">
                        <img class="preview__img <?= $classname; ?>" src="<?= $path; ?>" width="192" height="192" alt="">
                    </div>
                    <div class="form__input-file">
                        <input class="visually-hidden" type="file" name="gif-img" id="preview" value="<?= $value; ?>">
                        <input class="visually-hidden" name="gif-img" value="<?= $value; ?>">
                        <label for="preview" class="">
                            <span>Выбрать файл</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
       <div class="form__column">
            <?php $classname = isset($errors['name']) ? "form__input--error" : "";
            $value = isset($gif['author']) ? $gif['author'] : "";
            $value_input = isset($gif['name']) ? $gif['name'] : ""; ?>
            <div class="form__row author">
                <label class="form__label" for="author_input">Автор:</label>
                <input class="form__input <?= $classname; ?>" type="text" name="author_input" id="author_input" value="<?= $value_input; ?>" placeholder="Введите автора">
                <input type="hidden" name="author" id="author" value="<?= $value; ?>">
                <div id="search_author-result"></div>
                <?php if(isset($errors['author'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['email']) ? "form__input--error" : "";
            $value = isset($gif['email']) ? $gif['email'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="email">Email:</label>
                <input class="form__input <?= $classname; ?>" name="email" id="email" placeholder="Email" value="<?= $value; ?>">
                <?php if(isset($errors['email'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['status']) ? "form__input--error" : "";
            $value = $gif['status']==3 ? 'Admin' : ($gif['status']==1 ? 'Забанен' : 'Зареган'); ?>
            <div class="form__row">
                <label class="form__label" for="status">Status:</label>
                <select class="form__input form__input--select <?= $classname; ?>" name="status" id="status" placeholder="status">
                <option value="">Выберите статус</option>
                <option value="1" 
                            <?php if (isset($gif['status']) && ($gif['status'] == 1)) { print(' selected'); }; ?> >Забанен</option>
                <option value="2" 
                            <?php if (isset($gif['status']) && ($gif['status'] == 2)) { print(' selected'); }; ?> >Зареган</option>
                </select>
                <?php if(isset($errors['status'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>

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

    <div class="form__controls">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input class="button form__control" type="submit" name="submit_add" id="submit_add" data-url="/admin/users/users.php" onclick="EditItem(<?= $id; ?>); return false;" value="Редактировать">
    </div>
</form>
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image').css('background', 'transparent url('+e.target.result +') center top / cover no-repeat');
                    $('.preview__img').css('display', 'none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#preview").change(function(){
            readURL(this);
        });
</script>