<?php
    require_once('../config/config.php');
    require_once('../config/functions.php');
    if(isset($_POST["login"])) {
        $login = trim(htmlspecialchars($_POST['login']));
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