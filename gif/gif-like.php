<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
// 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
    $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}
if (isset($_SESSION['user'])) {
    $user_id = intval(trim($_SESSION['user']['id']));
    if (isset($_GET['id'])) {
    $gif_id = intval(trim($_GET['id']));
    }
    $Js = "<script src='../js/pagination.js'></script><script src='../js/gif.js'></script>";
    // 2. запрос для получения данных гифки по id
    $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
    'likes_count, favs_count, views_count, description, points, avg_points, votes '.
    'FROM gifs g '.
    'JOIN categories c ON g.category_id = c.id '.
    'JOIN users u ON g.user_id = u.id '.
    'WHERE g.id = '.$gif_id;
    $res_gif = mysqli_query($connect, $sql_gif);
    if ($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        if (!isset($gif)) {
            header('Location: /404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    // 4. all comments
   $sql_comments = 'SELECT c.dt_add, c.id, avatar_path, name, comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.' WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC  LIMIT 3';
    $res_comments = mysqli_query($connect, $sql_comments);
    if ($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    $res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id  WHERE g.id = "'.$gif_id.'" AND NOT moderation = 0');
    $count_comm = mysqli_fetch_assoc($res_count_comm)['cnt']; 
    // 5. запрос для списка похожих гифок
    if (!$is404error) {
       $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.urlCat '.
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
        $sql_add_like = "INSERT INTO gifs_like (user_id, gif_id) VALUES (?, ?)";
        $stmt = db_get_prepare_stmt($connect, $sql_add_like, [
            $user_id,
            $gif_id
        ]);
        $res_add_like = mysqli_stmt_execute($stmt);
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
        if ($res_add_like && $res_update_likes) {
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
        'likes_count, favs_count, views_count, description, points, avg_points, votes '.
        'FROM gifs g '.
        'JOIN categories c ON g.category_id = c.id '.
        'JOIN users u ON g.user_id = u.id '.
        'WHERE g.id = '.$gif_id;
        $res_gif = mysqli_query($connect, $sql_gif);
        if ($res_gif) {
            $gif = mysqli_fetch_assoc($res_gif);
            if (!isset($gif)) {
                header('Location: /404.php');
                http_response_code(404);
                $is404error = true;
            }
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        $page_content = include_template('gif.php', ['errors' => $errors, 'gif_id' => $gif_id, 'count_comm' => $count_comm, 'gif' => $gif, 'comments' => $comments, 'isFav' => $isFav, 'isLiked' => $isLiked, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
        $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'categories' => $categories, 'num_online' => $num_online, 'Js' => $Js, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
        print($layout_content);
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
                'likes_count, favs_count, views_count, description, points, avg_points, votes '.
                'FROM gifs g '.
                'JOIN categories c ON g.category_id = c.id '.
                'JOIN users u ON g.user_id = u.id '.
                'WHERE g.id = '.$gif_id;
                $res_gif = mysqli_query($connect, $sql_gif);
                if ($res_gif) {
                    $gif = mysqli_fetch_assoc($res_gif);
                    if (!isset($gif)) {
                        header('Location: /404.php');
                        http_response_code(404);
                        $is404error = true;
                    }
                } else {
                    $error = mysqli_error($connect);
                    print('Ошибка MySQL: '.$error);
                }
                $page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'gif_id' => $gif_id, 'count_comm' => $count_comm, 'comments' => $comments, 'isLiked' => $isLiked, 'isFav' => $isFav, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
                $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'categories' => $categories, 'num_online' => $num_online, 'Js' => $Js, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
                print($layout_content);
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