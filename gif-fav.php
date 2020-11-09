<?php

session_start();
$isFav = false;
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
            $sql = "INSERT INTO gifs_fav (user_id, gif_id) VALUES (?, ?)";
            $stmt = db_get_prepare_stmt($connect, $sql, [$user_id, $gif_id]);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                $sql_update_views = "UPDATE gifs SET favs_count = favs_count + 1 WHERE id = " . $gif_id;
                $res_update_views = mysqli_query($connect, $sql_update_views);
                header('Location: /gif.php?id=' . $gif_id);
            }
            else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }
        }
        else {
            $sql = 'DELETE FROM gifs_fav WHERE user_id = ' . $user_id . ' AND gif_id = ' . $gif_id;
            $res = mysqli_query($connect, $sql);
            if ($res) {
                $sql_update_views = "UPDATE gifs SET favs_count = favs_count - 1 WHERE id = " . $gif_id;
                $res_update_views = mysqli_query($connect, $sql_update_views);
                header('Location: /gif.php?id=' . $gif_id);
            }
            else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }
        }
    }
}