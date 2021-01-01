<?php
require_once('../config/config.php');
require_once('../config/functions.php');
if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['comment'] != ''){
    $comm_id = intval($_POST['id']);
    $comment = stripslashes($_POST['comment']);
    $name = trim(strval($_POST['name']));
    $server_name = trim(strval($_SESSION['user']['name']));
    $html = "";
    if ($name == $server_name) {
        $update_comment = "UPDATE comments SET comment_text='".$comment."' WHERE id = '".$comm_id."'";
        $res_update_comment = mysqli_query($connect, $update_comment);
        if ($res_update_comment) {
            $html = "Успешно обновлено";
            echo json_encode(array(
            'result'    => 'success',
            'html'      => $html
            )); 
        } else {
            $html = "Ошибка редактирования";
            echo json_encode(array(
            'result'    => 'error',
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


    






