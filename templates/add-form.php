<form class="form" id="add-form" action="/gif/add.php" method="post" enctype="multipart/form-data">
    <div class="form__columns">
        

        <div class="form__column">
            <?php $classname = isset($errors['category']) ? "form__input--error" : ""; ?>
            <div class="form__row">
                <label class="form__label" for="category">Категория:</label>

                <select class="form__input form__input--select <?= $classname; ?>" name="category" id="category" onchange="select()">
                    <option value="">Выберите категорию</option>
                    <?php foreach($categories as $category): ?>

                        <option value="<?= $category['id']; ?>" <?php if (isset($gif['category']) && ($gif['category'] == $category['id'])) { print(' selected'); }; ?> ><?= $category['nameCat']; ?></option>
                    <?php endforeach; ?>

                </select>
                <?php  $value = isset($gif['nameCat']) ? $gif['nameCat'] : ""; ?>
                <input type="hidden" id="nameCat" name="nameCat" value="<?= $value; ?>">
                <?php if(isset($errors['category'])) : ?>
                    <div class="error-notice">
                        <span class="error-notice__icon"></span>
                        <span class="error-notice__tooltip">Это поле должно быть заполнено</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php  $value = isset($gif['gif-title']) ? $gif['gif-title'] : ""; ?>
            <input type="hidden" class="form__input <?= $classname; ?>" type="text" name="gif-title" id="gif-title" value="<?= $value; ?>"> 
            
            <?php  $value = isset($gif['gif-url']) ? $gif['gif-url'] : ""; ?>
            <input type="hidden" class="form__input <?= $classname; ?>" type="text" name="gif-url" id="gif-url" value="<?= $value; ?>">
                
            
            <?php $classname = isset($errors['gif-question']) ? "form__input--error" : "";
            $value = isset($gif['gif-question']) ? $gif['gif-question'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="question">Вопрос:</label>
                <textarea class="form__input <?= $classname; ?>" name="gif-question" id="question" rows="5" cols="80" onKeyUp="sendUrl()" placeholder="Задайте Ваш вопрос здесь"><?= $value; ?></textarea>
                <?php if(isset($errors['gif-question'])) : ?>
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
        <input class="button form__control" type="submit" name="" id="submit_add" onclick="addItem(); return false;" value="Задать вопрос">
    </div>
</form>
<script>
    function select(){
        $category_name = $('#category option:selected').text();
        document.getElementById('nameCat').value = $category_name;
    }
    function sendUrl(){
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
            transl['Ъ']='';     transl['ъ']='';
            transl['Ы']='Y';    transl['ы']='y';
            transl['Ь']='';    transl['ь']='';
            transl['Э']='E';    transl['э']='e';
            transl['Ю']='Yu';    transl['ю']='yu';
            transl['Я']='Ya';    transl['я']='ya';
            transl[' ']='_';
            transl[',']='';
            transl['.']='';
            var text = document.getElementById('question').value;
            var result = '';
            for(i=0;i<text.length;i++) {
                if(transl[text[i]] != undefined) { result += transl[text[i]]; }
                else { result += text[i]; }
            }
            if ($('#gif-url').val().length < 70) {
                    document.getElementById('gif-url').value = result;
            }          
                        
            var title = document.getElementById('question').value;
            if ($('#gif-title').val().length < 70) {
                    document.getElementById('gif-title').value = title;
            }
    }
</script>