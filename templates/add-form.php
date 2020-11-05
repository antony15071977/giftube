<form class="form" action="" method="post" enctype="multipart/form-data">
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
                        <input class="visually-hidden" type="file" name="gif[path]" id="preview" value="">
                        <label for="preview" class="">
                            <span>Выбрать файл</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form__column">
            <div class="form__row">
                <label class="form__label" for="category">Категория:</label>
                <select class="form__input form__input--select " name="gif[category]" id="category">
                    <option value="">Выберите категорию</option>

                    <?php foreach($categories as $category): ?>
                        <option value="4"><?= $category['name']; ?></option>
                    <?php endforeach; ?>

                </select>
            </div>
            <div class="form__row">
                <label class="form__label" for="name">Название:</label>
                <input class="form__input " type="text" name="gif[title]" id="name" value="" placeholder="Введите название">
            </div>
            <div class="form__row">
                <label class="form__label" for="description">Описание:</label>
                <textarea class="form__input " name="gif[description]" id="description" rows="5" cols="80" placeholder="Краткое описание"></textarea>
            </div>
        </div>
    </div>

    <!-- Сообщение об ошибках -->
    <div class="form__errors">
        <p>Пожалуйста, исправьте следующие ошибки:</p>
        <ul>
            <li><strong>Категория:</strong> Это поле должно быть заполнено</li>
            <li><strong>Название:</strong> Это поле должно быть заполнено</li>
            <li><strong>Описание:</strong> Это поле должно быть заполнено</li>
        </ul>
    </div>
    <!-- end Сообщение об ошибках -->

    <div class="form__controls">
        <input class="button form__control" type="submit" name="" value="Добавить">
    </div>
</form>
