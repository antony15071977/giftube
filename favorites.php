<?php

session_start();

require_once('functions.php');

$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
else {
    $user_id = intval($_SESSION['user']['id']);

    $res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs_fav WHERE user_id = ' . $user_id);
    $items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];

    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_items = 3;
    $offset = ($current_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';
    $res_cat = mysqli_query($connect, $sql_cat);
    if($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }
    $user_id = intval($_SESSION['user']['id']);
    // 2. получить список избранных гифок у пользователя
    $sql_favs = 'SELECT g.id, title, img_path, likes_count, favs_count, views_count, u.name ' .
                'FROM gifs g ' .
                'JOIN users u ON g.user_id = u.id ' .
                'JOIN gifs_fav gf ON gf.gif_id = g.id ' .
                'AND gf.user_id = ' . $user_id . ' LIMIT ' . $page_items . ' OFFSET ' . $offset;
    $res_favs = mysqli_query($connect, $sql_favs);
    if($res_favs) {
        $favs = mysqli_fetch_all($res_favs, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }
}

$pagination = include_template('pagination.php', [
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$page_content = include_template('main.php', [
    'gifs' => $favs,
    'title' => 'Избранное',
    'pagination' => $pagination
]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Моё избранное'
    ]);
}
else {
    http_response_code(403);
}

print($layout_content);
