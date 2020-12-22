<?php
    require_once('../config/config.php');
    require_once('../config/functions.php');
    if(isset($_POST["email"])) {
        $email = trim(htmlspecialchars($_POST['email']));
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