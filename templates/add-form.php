<form class="form" id="add-form" action="/gif/add.php" method="post" enctype="multipart/form-data">
    <div class="form__columns">
        <div class="form__column form__column--short">
            <div class="form__row">
                <label class="form__label" for="preview">GIF файл:</label>
                <div class="upload">
                    <div class="preview">
                        <button class="preview__remove" type="button">Удалить</button>
                        <img class="preview__img" src="img/no-pic.png" alt="" width="192" height="192">
                    </div>
                    <div class="form__input-file">
                        <?php $value = isset($gif['img_path']) ? $gif['img_path'] : ""; ?>
                        <input class="visually-hidden" type="file" name="gif-img" id="preview" value="<?= $value; ?>">
                        <label for="preview" class="">
                            <span>Выбрать файл</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form__column">
            <?php $classname = isset($errors['category']) ? "form__input--error" : ""; ?>
            <div class="form__row">
                <label class="form__label" for="category">Категория:</label>

                <select class="form__input form__input--select <?= $classname; ?>" name="category" id="category">
                    <option value="">Выберите категорию</option>
                    <?php foreach($categories as $category): ?>

                        <option value="<?= $category['id']; ?>" <?php if (isset($gif['category']) && ($gif['category'] == $category['id'])) { print(' selected'); }; ?> ><?= $category['name']; ?></option>
                    <?php endforeach; ?>

                </select>
                <?php if(isset($errors['category'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['gif-title']) ? "form__input--error" : "";
            $value = isset($gif['gif-title']) ? $gif['gif-title'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="name">Название:</label>
                <input class="form__input <?= $classname; ?>" type="text" name="gif-title" id="name" value="<?= $value; ?>" placeholder="Введите название">
                <?php if(isset($errors['gif-title'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['gif-description']) ? "form__input--error" : "";
            $value = isset($gif['gif-description']) ? $gif['gif-description'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="description">Описание:</label>
                <textarea class="form__input <?= $classname; ?>" name="gif-description" id="description" rows="5" cols="80" placeholder="Краткое описание"><?= $value; ?></textarea>
                <?php if(isset($errors['gif-description'])) : ?>
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
        <input class="button form__control" type="submit" name="" id="submit" value="Добавить">
    </div>
</form>