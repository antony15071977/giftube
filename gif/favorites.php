<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
if (isset($_SESSION['user'])) {
    $res_count_gifs_all = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
    $items_count_all = mysqli_fetch_assoc($res_count_gifs_all)['cnt'];
    $user_id = intval(trim($_SESSION['user']['id']));
    $res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs_fav WHERE user_id = '.$user_id);
    $items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_items = 3;
    $offset = ($current_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);
    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';
    $res_cat = mysqli_query($connect, $sql_cat);
    if ($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    $sql_subcat = 'SELECT * FROM upcategories';
    $res_subcat = mysqli_query($connect, $sql_subcat);
    if ($res_subcat) {
        $upcategories = mysqli_fetch_all($res_subcat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    // 2. получить список избранных гифок у пользователя
    $sql_favs = 'SELECT g.id, g.dt_add, title, url, question, likes_count, category_id, avg_points,  c.urlCat, favs_count, views_count, u.name, u.avatar_path FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id JOIN gifs_fav gf ON gf.gif_id = g.id AND gf.user_id = '.$user_id.' LIMIT '.$page_items.' OFFSET '.$offset;
    $res_favs = mysqli_query($connect, $sql_favs);
    if ($res_favs) {
        $favs = mysqli_fetch_all($res_favs, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    $url = "/gif/favorites.php";
    $Js = "<link rel='stylesheet' href='../rating/rating.css'>
    <script src='../js/pagination.js'></script>";
    $pagination = include_template('pagination.php', ['pages_count' => $pages_count, 'items_count' => $items_count, 'url' => $url, 'pages' => $pages, 'current_page' => $current_page]);
    // Вариант, когда не работает яваскрипт и срабатывает переход по ссылке
    if ($_GET['mode'] == 'w_js' && isset($_SESSION['user'])) {
        $page_content = include_template('main.php', ['gifs' => $favs, 'pagination' => $pagination, 'title' => 'Избранное']);
        $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'upcategories' => $upcategories,  'categories' => $categories, 'Js' => $Js, 'title' => 'Моё избранное', 'num_online' => $num_online, 'items_count' => $items_count_all, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isMainPage' => $isMainPage]);
        print($layout_content);
        exit();
    }
    if (isset($_GET['page']) || isset($_GET['fav'])) {
        $page_content = include_template('main.php', ['gifs' => $favs, 'title' => 'Избранное', 'pagination' => $pagination]);
        print($page_content);
        exit();
    } else {
        $page_content = include_template('main.php', ['gifs' => $favs, 'title' => 'Избранное', 'pagination' => $pagination]);
    }
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'Js' => $Js, 'items_count' => $items_count_all, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Моё избранное']);
} else {
    header('Location: /');
}
print($layout_content);