 <!-- content starts -->
 <div>
    <ul class="breadcrumb">
        <li>
            <a href="/admin/index.php">Главная</a>
        </li>
        <li>
            <a href="#">Загрузка</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-edit"></i> Загрузка файлов</h2>
            </div>
            <div class="box-content">
                <form name="upload" id="upload" enctype="multipart/form-data" method="POST" action="/admin/upload/submit.php">
                    <div class="control-group">
                        <label class="control-label" for="select">Выберите папку загрузки</label>
                        <div class="controls">
                            <select id="select" name="select">
                                <option value="../../uploads">uploads</option>
                                <option value="../../uploads/avatar">avatar</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <label for="InputFile">File input</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                        <input type="file" multiple="multiple" accept=".txt,image/*" name="InputFile" id="InputFile" required="required">
                    </div>
                    <input type="submit" class="upload_files label-info btn btn-default" value="Загрузить">
                </form>
                <div class="ajax-reply"></div>
            </div>
        </div>     
    </div>
</div>