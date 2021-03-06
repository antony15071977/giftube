<?php
$isGifPage = true;
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
if (isset($_GET['url'])) {
    $gif_url = '';
    $gif_url = trim(htmlentities($_GET['url']));
    // 1. запрос для получения данных гифки по url
    $sql_gif = 'SELECT g.id, g.url, category_id, u.name, title, img_path, likes_count, favs_count, views_count, description, points, avg_points, votes, c.urlCat FROM gifs g '.
    'JOIN categories c ON g.category_id = c.id '.
    'JOIN users u ON g.user_id = u.id '.
    'WHERE g.url = "'.$gif_url.'"';
    $res_gif = mysqli_query($connect, $sql_gif);
    if ($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        $gif_id = $gif['id'];
        if (!isset($gif)) {
            header('Location: /404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
}

if (isset($_GET['id']) || isset($_POST['gif_id'])) {
    $gif_id = intval($_GET['id']) ?? intval($_POST['gif_id']);
}
// 2. запрос для получения данных гифки по id
$sql_gif = 'SELECT g.id, g.url, category_id, u.name, title, img_path, likes_count, favs_count, views_count, description, points, avg_points, votes, c.urlCat FROM gifs g '.
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
// Обновление просмотров гифки по id
$sql_update_views = "UPDATE gifs SET views_count = views_count + 1 WHERE id = ".$gif_id;
$res_update_views = mysqli_query($connect, $sql_update_views);
// если гифка добавлена в избранное
if (isset($_SESSION['user'])) {
    $user_id = intval($_SESSION['user']['id']);
    if (isset($_GET['id']) || isset($_POST['gif_id'])) {
        $gif_id = intval($_GET['id']) ?? intval($_POST['gif_id']);
    }
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
    $sql_like = 'SELECT id FROM gifs_like WHERE user_id = '.$user_id.
    ' AND gif_id = '.$gif_id;
    $res_like = mysqli_query($connect, $sql_like);
    if ($res_like) {
        $like = mysqli_fetch_assoc($res_like);
        if (!empty($like)) {
            $isLiked = true;
        }
    }
}
// 4. all comments
if ($_GET['comments'] == 'all') {
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.' WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC';
} else {
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.' WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC  LIMIT 3';
}
$res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id  WHERE g.id = "'.$gif_id.'" AND NOT moderation = 0');
$count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
$res_comments = mysqli_query($connect, $sql_comments);
if ($res_comments) {
    $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}

$href_canonical = $address_site.$gif['urlCat'].'/'.$gif['url'].'/';
// 5. запрос для списка похожих гифок
if (!$is404error) {
    $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'WHERE category_id = '.$gif['category_id'].' AND g.id NOT IN('.$gif_id.') '.' LIMIT 6';
    $res_similar = mysqli_query($connect, $sql_similar);
    if ($res_similar) {
        $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
}

if ($is404error) {
    $page_content = include_template('main.php', ['username' => $_SESSION['user']['name'], 'title' => '404 Страница не найдена', 'is404error' => $is404error]);
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href_canonical' => $href_canonical, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
} else {
    $page_content = include_template('gif-amp.php', ['username' => $_SESSION['user']['name'], 'errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked, 'dict' => $dict]);
    if (isset($_SESSION['user'])) {
        $page_content = include_template('gif-amp.php', ['username' => $_SESSION['user']['name'], 'errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked, 'dict' => $dict]);
        $layout_content = include_template('layout-amp.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href_canonical' => $href_canonical, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
    } else {
        $layout_content = include_template('layout-amp.php', ['content' => $page_content, 'categories' => $categories, 'href_canonical' => $href_canonical, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
    }
}
print($layout_content);