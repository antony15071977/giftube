<?php

session_start();

require_once('config.php');
require_once('functions.php');


if (isset($_SESSION['user'])) {
    $user_id = intval($_SESSION['user']['id']);
    $gif_id = intval($_GET['id']);

    if (!isset($_GET['rem'])) {
        mysqli_query($connect,  "START TRANSACTION");
        $sql_add_fav = "INSERT INTO gifs_fav (user_id, gif_id) VALUES ($user_id, $gif_id)";
        $res_add_favs = mysqli_query($connect, $sql_add_fav);
             // обновляем количество добавлений в избранное
        $sql_update_favs = "UPDATE gifs SET favs_count = favs_count + 1 WHERE id = " . $gif_id;
        $res_update_favs = mysqli_query($connect, $sql_update_favs);
        if ($res_add_favs&&$res_update_favs) {
            mysqli_query($connect, "COMMIT");
        }
        else {
            mysqli_query($connect, "ROLLBACK");
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }         
        header('Location: /gif.php?id=' . $gif_id);
    }
    else {
        mysqli_query($connect,  "START TRANSACTION");
        $sql = 'DELETE FROM gifs_fav WHERE user_id = ' . $user_id . ' AND gif_id = ' . $gif_id;
        $res = mysqli_query($connect, $sql);

            // обновляем количество добавлений в избранное
        $sql_update_favs = "UPDATE gifs SET favs_count = favs_count - 1 WHERE id = " . $gif_id;
        $res_update_favs = mysqli_query($connect, $sql_update_favs);
        if ($sql_update_favs&&$res_update_favs) {
            mysqli_query($connect, "COMMIT");
            header('Location: /gif.php?id=' . $gif_id); 
        }
        else {
            mysqli_query($connect, "ROLLBACK");
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        } 
    }        
}


