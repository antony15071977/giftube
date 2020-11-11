<?php

session_start();

require_once('config.php');
require_once('functions.php');

if (isset($_SESSION['user'])) {
    $user_id = intval($_SESSION['user']['id']);
    $gif_id = intval($_GET['id']);

    if (!isset($_GET['rem'])) {
        mysqli_query($connect,  "START TRANSACTION");
        $sql_add_like = "INSERT INTO gifs_like (user_id, gif_id) VALUES ($user_id, $gif_id)";
        $res_add_favs = mysqli_query($connect, $sql_add_like);
            // подсчет лайков
        $sql_count_likes = 'SELECT count(id) FROM gifs_like WHERE gif_id = ' . $gif_id;
        $res_count_likes = mysqli_query($connect, $sql_count_likes);
        if($res_count_likes) {
            $count_likes = mysqli_fetch_assoc($res_count_likes);                    
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }
                // end подсчет лайков

                // Обновление лайков в таблице с гифками
        $sql_update_likes = "UPDATE gifs SET likes_count = " .
        $count_likes['count(id)'] .
        " WHERE id = " . $gif_id;
        $res_update_likes = mysqli_query($connect, $sql_update_likes);
                // end обновление лайков в таблице с гифками
        if ($sql_add_like&&$res_update_likes) {
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
        $sql = 'DELETE FROM gifs_like WHERE user_id = ' . $user_id . ' AND gif_id = ' . $gif_id;
        $res = mysqli_query($connect, $sql);
        if ($res) {
                // подсчет лайков
            $sql_count_likes = 'SELECT count(id) FROM gifs_like WHERE gif_id = ' . $gif_id;
            $res_count_likes = mysqli_query($connect, $sql_count_likes);
            if($res_count_likes) {
                $count_likes = mysqli_fetch_assoc($res_count_likes);                    
            } else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }
                // end подсчет лайков

                // Обновление лайков в таблице с гифками
            $sql_update_likes = "UPDATE gifs SET likes_count = " .
            $count_likes['count(id)'] .
            " WHERE id = " . $gif_id;
            $res_update_likes = mysqli_query($connect, $sql_update_likes);
                // end обновление лайков в таблице с гифками
            if ($res&&$res_update_likes) {
                mysqli_query($connect, "COMMIT");
                header('Location: /gif.php?id=' . $gif_id); 
            }
            else {
                mysqli_query($connect, "ROLLBACK");
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            } 
        }
        else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }
    }
}
