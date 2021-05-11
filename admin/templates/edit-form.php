<h2 class="">Редактировать ITEM</h2>
<br>
<form class="form" id="edit-form" action="/admin/item.php" method="post" enctype="multipart/form-data">
    <div class="form__columns">
        <div class="form__column form__column--short">
            <div class="form__row">
                <label class="form__label" for="preview">GIF файл:</label>
                <div class="upload">
                    <?php 
                    $classname = isset($errors['gif-img']) ? "form__input--error" : "";
                    $value = isset($gif['img_path']) ? '/uploads/'.$gif['img_path'] : "";
                    $path = isset($gif['img_path']) ? '/uploads/'.$gif['img_path'] : "/img/no-pic.png"; 
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
            <?php $classname = isset($errors['category']) ? "form__input--error" : ""; ?>
            <div class="form__row">
                <label class="form__label" for="category">Категория:</label>

                <select class="form__input form__input--select <?= $classname; ?>" name="category" id="category">
                    <option value="">Выберите категорию</option>
                    <?php foreach($categories as $category): ?>

                        <option value="<?= $category['id']; ?>" 
                            <?php if (isset($gif['category_id']) && ($gif['category_id'] == $category['id'])) { print(' selected'); }; ?> ><?= $category['nameCat']; ?></option>
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
            $value = isset($gif['title']) ? $gif['title'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="name">Название:</label>
                <input class="form__input <?= $classname; ?>" type="text" name="gif-title" id="name" onKeyUp="send()" value="<?= $value; ?>" placeholder="Введите название">
                <?php if(isset($errors['gif-title'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
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
            <?php if(isset($errors['gif-url'])) : ?>
                <div class="error-notice">
                    <span class="error-notice__icon"></span>
                    <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                </div>
            <?php endif; ?>
            <?php $classname = isset($errors['gif-url']) ? "form__input--error" : "";
            $value = isset($gif['url']) ? $gif['url'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="name">ЧПУ:</label>
                <input class="form__input <?= $classname; ?>" type="text" name="gif-url" id="gif-url" value="<?= $value; ?>" placeholder="Введите ЧПУ">
                <?php if(isset($errors['gif-url'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $classname = isset($errors['gif-description']) ? "form__input--error" : "";
            $value = isset($gif['description']) ? $gif['description'] : ""; ?>
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
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input class="button form__control" type="submit" name="submit_add" id="submit_add" data-url="/admin/Item/item.php" onclick="EditItem(<?= $id; ?>); return false;" value="Редактировать">
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

    function send(){
        var text = document.getElementById('name').value;
        var transl = new Array();
            transl['А']='A';     transl['а']='a';
            transl['Б']='B';     transl['б']='b';
            transl['В']='V';     transl['в']='v';
            transl['Г']='G';     transl['г']='g';
            transl['Д']='D';     transl['д']='d';
            transl['Е']='E';     transl['е']='e';
            transl['Ё']='Yo';    transl['ё']='yo';
            transl['Ж']='Zh';    transl['ж']='zh';
            transl['З']='Z';     transl['з']='z';
            transl['И']='I';     transl['и']='i';
            transl['Й']='J';     transl['й']='j';
            transl['К']='K';     transl['к']='k';
            transl['Л']='L';     transl['л']='l';
            transl['М']='M';     transl['м']='m';
            transl['Н']='N';     transl['н']='n';
            transl['О']='O';     transl['о']='o';
            transl['П']='P';     transl['п']='p';
            transl['Р']='R';     transl['р']='r';
            transl['С']='S';     transl['с']='s';
            transl['Т']='T';     transl['т']='t';
            transl['У']='U';     transl['у']='u';
            transl['Ф']='F';     transl['ф']='f';
            transl['Х']='X';     transl['х']='x';
            transl['Ц']='C';     transl['ц']='c';
            transl['Ч']='Ch';    transl['ч']='ch';
            transl['Ш']='Sh';    transl['ш']='sh';
            transl['Щ']='Sсh';    transl['щ']='sсh';
            transl['Ъ']='"';     transl['ъ']='"';
            transl['Ы']='Y\'';    transl['ы']='y\'';
            transl['Ь']='\'';    transl['ь']='\'';
            transl['Э']='E\'';    transl['э']='e\'';
            transl['Ю']='Yu';    transl['ю']='yu';
            transl['Я']='Ya';    transl['я']='ya';
            transl[' ']='_';

            var result = '';
            for(i=0;i<text.length;i++) {
                if(transl[text[i]] != undefined) { result += transl[text[i]]; }
                else { result += text[i]; }
            }
            document.getElementById('gif-url').value = result;
        }
</script>