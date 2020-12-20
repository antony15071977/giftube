<?php
require_once('../config/config.php');
require_once('../config/functions.php');
if (isset($_SESSION['user'])) {
    $user_id = intval($_SESSION['user']['id']);
    $gif_id = intval($_GET['id']);
    // 2. запрос для получения данных гифки по id
    $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
    'likes_count, favs_count, views_count, description '.
    'FROM gifs g '.
    'JOIN categories c ON g.category_id = c.id '.
    'JOIN users u ON g.user_id = u.id '.
    'WHERE g.id = '.$gif_id;
    $res_gif = mysqli_query($connect, $sql_gif);
    if ($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        if (!isset($gif)) {
            header('Location: /error404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    // 4. all comments
    $sql_comments = 'SELECT c.dt_add, avatar_path, name, comment_text '.
    'FROM comments c '.
    'JOIN gifs g ON g.id = c.gif_id '.
    'JOIN users u ON c.user_id = u.id '.
    'WHERE g.id = '.$gif_id;
    $res_comments = mysqli_query($connect, $sql_comments);
    if ($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    // 5. запрос для списка похожих гифок
    if (!$is404error) {
        $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count, favs_count, views_count '.
        'FROM gifs g '.
        'JOIN categories c ON g.category_id = c.id '.
        'JOIN users u ON g.user_id = u.id '.
        'WHERE category_id = '.$gif['category_id'].
        ' AND g.id NOT IN('.$gif_id.
        ') '.
        ' LIMIT 6';
        $res_similar = mysqli_query($connect, $sql_similar);
        if ($res_similar) {
            $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
    }
    if (!isset($_GET['rem'])) {
        mysqli_query($connect, "START TRANSACTION");
        $sql_add_like = "INSERT INTO gifs_like (user_id, gif_id) VALUES ($user_id, $gif_id)";
        $res_add_favs = mysqli_query($connect, $sql_add_like);
        // подсчет лайков
        $sql_count_likes = 'SELECT count(id) FROM gifs_like WHERE gif_id = '.$gif_id;
        $res_count_likes = mysqli_query($connect, $sql_count_likes);
        if ($res_count_likes) {
            $count_likes = mysqli_fetch_assoc($res_count_likes);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        // end подсчет лайков
        // Обновление лайков в таблице с гифками
        $sql_update_likes = "UPDATE gifs SET likes_count = ".$count_likes['count(id)']." WHERE id = ".$gif_id;
        $res_update_likes = mysqli_query($connect, $sql_update_likes);
        // end обновление лайков в таблице с гифками
        if ($sql_add_like && $res_update_likes) {
            mysqli_query($connect, "COMMIT");
            $isLiked = true;
            $isFav = false;
            $sql_fav = 'SELECT id FROM gifs_fav WHERE user_id = '.$user_id.
            ' AND gif_id = '.$gif_id;
            $res_fav = mysqli_query($connect, $sql_fav);
            if ($res_fav) {
                $fav = mysqli_fetch_assoc($res_fav);
                if (!empty($fav)) {
                    $isFav = true;
                }
            }
        } else {
            mysqli_query($connect, "ROLLBACK");
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        // 2. запрос для получения данных гифки по id
        $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
        'likes_count, favs_count, views_count, description '.
        'FROM gifs g '.
        'JOIN categories c ON g.category_id = c.id '.
        'JOIN users u ON g.user_id = u.id '.
        'WHERE g.id = '.$gif_id;
        $res_gif = mysqli_query($connect, $sql_gif);
        if ($res_gif) {
            $gif = mysqli_fetch_assoc($res_gif);
            if (!isset($gif)) {
                header('Location: /error404.php');
                http_response_code(404);
                $is404error = true;
            }
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        $page_content = include_template('gif.php', ['errors' => $errors, 'gif_id' => $gif_id, 'gif' => $gif, 'comments' => $comments, 'isFav' => $isFav, 'isLiked' => $isLiked, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
        print($page_content);
        exit();
    } else {
        mysqli_query($connect, "START TRANSACTION");
        $sql = 'DELETE FROM gifs_like WHERE user_id = '.$user_id.
        ' AND gif_id = '.$gif_id;
        $res = mysqli_query($connect, $sql);
        if ($res) {
            // подсчет лайков
            $sql_count_likes = 'SELECT count(id) FROM gifs_like WHERE gif_id = '.$gif_id;
            $res_count_likes = mysqli_query($connect, $sql_count_likes);
            if ($res_count_likes) {
                $count_likes = mysqli_fetch_assoc($res_count_likes);
            } else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: '.$error);
            }
            // end подсчет лайков
            // Обновление лайков в таблице с гифками
            $sql_update_likes = "UPDATE gifs SET likes_count = ".
            $count_likes['count(id)'].
            " WHERE id = ".$gif_id;
            $res_update_likes = mysqli_query($connect, $sql_update_likes);
            // end обновление лайков в таблице с гифками
            if ($res && $res_update_likes) {
                mysqli_query($connect, "COMMIT");
                $isLiked = false;
                $isFav = false;
                $sql_fav = 'SELECT id FROM gifs_fav WHERE user_id = '.$user_id.
                ' AND gif_id = '.$gif_id;
                $res_fav = mysqli_query($connect, $sql_fav);
                if ($res_fav) {
                    $fav = mysqli_fetch_assoc($res_fav);
                    if (!empty($fav)) {
                        $isFav = true;
                    }
                }
                // 2. запрос для получения данных гифки по id
                $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
                'likes_count, favs_count, views_count, description '.
                'FROM gifs g '.
                'JOIN categories c ON g.category_id = c.id '.
                'JOIN users u ON g.user_id = u.id '.
                'WHERE g.id = '.$gif_id;
                $res_gif = mysqli_query($connect, $sql_gif);
                if ($res_gif) {
                    $gif = mysqli_fetch_assoc($res_gif);
                    if (!isset($gif)) {
                        header('Location: /error404.php');
                        http_response_code(404);
                        $is404error = true;
                    }
                } else {
                    $error = mysqli_error($connect);
                    print('Ошибка MySQL: '.$error);
                }
                $page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'gif_id' => $gif_id, 'comments' => $comments, 'isLiked' => $isLiked, 'isFav' => $isFav, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
                print($page_content);
                exit();
            } else {
                mysqli_query($connect, "ROLLBACK");
                $error = mysqli_error($connect);
                print('Ошибка MySQL: '.$error);
            }
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
    }
}