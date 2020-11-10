<?php

session_start();

require_once('functions.php');

$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
else {
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $gif_id = $_GET['id'];

        if (!isset($_GET['rem'])) {
            $sql = "INSERT INTO gifs_like (user_id, gif_id) VALUES (?, ?)";
            $stmt = db_get_prepare_stmt($connect, $sql, [$user_id, $gif_id]);
            $res = mysqli_stmt_execute($stmt);
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
                header('Location: /gif.php?id=' . $gif_id);
            }
            else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }
        }
        else {
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
                header('Location: /gif.php?id=' . $gif_id);
            }
            else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }
        }
    }
}