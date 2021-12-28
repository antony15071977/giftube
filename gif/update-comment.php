<?php
require_once('../config/config.php');
require_once('../config/functions.php');
if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['comment'] != ''){
    $comm_id = intval($_POST['id']);
    $comment = stripslashes($_POST['comment']);
    $name = trim(strval($_POST['name']));
    $server_name = trim(strval($_SESSION['user']['name']));
    $user_id = $_SESSION['user']['id'];
    $html = "";
    if ($name == $server_name) {

        //Составляем заголовок письма
        $subject = "Новое изменение ответа на сайте ".$_SERVER['HTTP_HOST'];
        //Устанавливаем кодировку заголовка письма и кодируем его
        $subject = "=?utf-8?B?".base64_encode($subject).
                "?=";
        //Составляем тело сообщения
        $message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).' пользователем '.$_SESSION['user']['name'].' был изменен ответ на сайте <a href="'.$address_site.
        '">'.$_SERVER['HTTP_HOST'].'</a>.
        А вот и сам Ответ: '.$comment.'<br>
        Чтобы одобрить и опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/comment-postmoderation.php?comment='.$comment.'&ok='.$comm_id.'">"ОДОБРИТЬ"</a>.
        Чтобы удалить его безвозвратно, нажмите на ссылку <a href="'.$address_site.'gif/comment-postmoderation.php?del='.$comm_id.'">"УДАЛИТЬ"</a>.
        Чтобы удалить его безвозвратно и занести пользователя в черный список, нажмите на ссылку <a href="'.$address_site.'gif/comment-postmoderation.php?del='.$comm_id.'&user_id='.$user_id.'">"УДАЛИТЬ и ЗАНЕСТИ В ЧЕРНЫЙ СПИСОК"</a>.
        Чтобы отредактировать и потом опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/comment-postmoderation.php?comment='.$comment.'&id='.$comm_id.'">"РЕДАКТИРОВАТЬ"</a>.
        ';
        //Составляем дополнительные заголовки для почтового сервиса mail.ru
        $headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
        //Отправляем сообщение с ссылкой для подтверждения регистрации на указанную почту и проверяем отправлена ли она успешно или нет. 


        if (!mail($email_admin, $subject, $message, $headers)) {
            $html = "Произошла непредвиденная ошибка, обновите страницу и попробуйте еще раз.";
            echo json_encode(array(
            'result'    => 'error',
            'html'      => $html
            ));
        } else {
            $html = "Будет обновлено после модерации";
            echo json_encode(array(
            'result'    => 'success',
            'html'      => $html
            )); 
        }


    } else {
        $html = "У вас нет прав";
        echo json_encode(array(
            'result'    => 'error',
            'html'      => $html
            ));
    }
} else {
    $html = "Ошибка редактирования";
    echo json_encode(array(
            'result'    => 'error',
            'html'      => $html
            ));
}


    






