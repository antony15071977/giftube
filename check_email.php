<?php
    //Добавляем файл подключения к БД
    require_once('config.php');
    require_once('functions.php');
     
    if(isset($_POST["email"])) {
 
        $email =  trim($_POST["email"]);
 
        $email = htmlspecialchars($email, ENT_QUOTES);
 
        //Проверяем, нет ли уже такого адреса в БД.
        $sql = 'SELECT id FROM users WHERE email = "' . $email . '"';
        $res_email = mysqli_query($connect, $sql);
        if($res_email) {
            $emails = mysqli_fetch_all($res_email, MYSQLI_ASSOC);
            if(!empty($emails)) {
                echo "<span class='mesage_error'>Пользователь с таким почтовым адресом уже зарегистрирован</span>
                    <script type='text/javascript'>
                    jQuery(document).ready(function($) {
                        var mail = $('input[name=email]');
                        var mail_valid = $('#email_valid');
                        mail.css('border', '1px solid red');
                        mail_valid.css('display', 'none');
                    });
                    </script>";
            }
            else {
                echo "<span class='success_message'>Емейл свободен</span>
                    <script type='text/javascript'>
                    jQuery(document).ready(function($) {
                        var mail = $('input[name=email]');
                        var mail_valid = $('#email_valid');
                        mail.css('border', '1px solid green');
                        mail_valid.css('display', 'block');
                    });
                    </script>";
            }
        }
    }
?>