<?php
    //Добавляем файл подключения к БД
    require_once('config.php');
    require_once('functions.php');
     
    if(isset($_POST["login"])) {
 
        $login =  trim($_POST["login"]);
 
        $login = htmlspecialchars($login, ENT_QUOTES);
 
        //Проверяем, нет ли уже такого login в БД.
        $sql = 'SELECT id FROM users WHERE name = "' . $login . '"';
        $res_login = mysqli_query($connect, $sql);
        if($res_login) {
            $logins = mysqli_fetch_all($res_login, MYSQLI_ASSOC);
            if(!empty($logins)) {
                echo "<span class='mesage_error'>Пользователь с таким логином уже зарегистрирован</span>
                    <script type='text/javascript'>
                    jQuery(document).ready(function($) {
                        var nickname = $('#nickname');
                        var name_valid = $('#name_valid');
                        nickname.css('border', '1px solid red');
                        name_valid.css('display', 'none');
                    });
                    </script>";
            }
            else {
                echo "<span class='success_message'>Логин свободен</span>
                    <script type='text/javascript'>
                    jQuery(document).ready(function($) {
                        var nickname = $('#nickname');
                        var name_valid = $('#name_valid');
                        nickname.css('border', '1px solid green');
                        name_valid.css('display', 'block');
                    });
                    </script>";
            }
        }
    }
?>