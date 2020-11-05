<?php

$isGifPage = true;

require_once('functions.php');

//подключение к MySQL
$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {

    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';

    $res_cat = mysqli_query($connect, $sql_cat);

    if($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }


    if (isset($_GET['id'])) {
        $gif_id = $_GET['id'];
    }

    // 2. запрос для получения данных гифки по id
    $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
                'likes_count, favs_count, views_count, description ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.id = ' . $gif_id;

    $res_gif = mysqli_query($connect, $sql_gif);

    if($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        if(!isset($gif)) {
            header('Location: /error404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 3. запрос для списка комментариев к гифке
    $sql_comments = 'SELECT c.dt_add, avatar_path, name, comment_text ' .
                'FROM comments c ' .
                'JOIN gifs g ON g.id = c.gif_id ' .
                'JOIN users u ON c.user_id = u.id ' .
                'WHERE g.id = ' . $gif_id;

    $res_comments = mysqli_query($connect, $sql_comments);

    if($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 4. запрос для списка похожих гифок
    if(!$is404error) {
        $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count ' .
                    'FROM gifs g ' .
                    'JOIN categories c ON g.category_id = c.id ' .
                    'JOIN users u ON g.user_id = u.id ' .
                    'WHERE category_id = ' . $gif['category_id'] .
                    ' AND g.id NOT IN(' . $gif_id . ') ' .
                    ' LIMIT 6';

        $res_similar = mysqli_query($connect, $sql_similar);

        if($res_similar) {
            $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }
    }
}
if ($is404error) {

    $page_content = include_template('main.php', [
        'title' => '404 Страница не найдена',
        'is404error' => $is404error
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => '404 Страница не найдена'
    ]);

}
else {

    $page_content = include_template('gif.php', [
        'gif' => $gif,
        'comments' => $comments,
        'gifs' => $similar_gifs,
        'isGifPage' => $isGifPage
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => $gif['title']
    ]);

}

print($layout_content);