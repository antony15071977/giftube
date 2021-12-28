<?php
$isGifPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');

$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
// 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
    $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}
$sql_upcat = 'SELECT * FROM upcategories';
$res_upcat = mysqli_query($connect, $sql_upcat);
if ($res_upcat) {
    $upcategories = mysqli_fetch_all($res_upcat, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}

if (isset($_GET['url'])) {
    $gif_url = '';
    $gif_url = trim(htmlentities($_GET['url']));

    // 1. запрос для получения данных гифки по url
    // узнаем категорию, чтоб по категории определить нет ли в апкатегории 0
    $sql_gif_up_cat = 'SELECT c.upcategories_id FROM gifs g JOIN categories c ON g.category_id = c.id WHERE g.url = "'.$gif_url.'"';
    $res_gif = mysqli_query($connect, $sql_gif_up_cat);
    if ($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        $gif_up_cat = $gif['upcategories_id'];
        if (!isset($gif)) {
            header('Location: /404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    
    if ($gif_up_cat==0) {
        $sql_gif = 'SELECT g.id, g.dt_add, g.url, category_id, u.name, u.avatar_path, title, question, likes_count, favs_count, views_count, points, avg_points, votes, c.nameCat, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id  WHERE g.url = "'.$gif_url.'"';
    } else {
        $sql_gif = 'SELECT g.id, g.dt_add, g.url, category_id, u.name, u.avatar_path, title, question, likes_count, favs_count, views_count, points, avg_points, votes, c.nameCat, c.urlCat, up.up_id, up.name_up_Cat, up.url_up_Cat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN upcategories up ON up.up_id = c.upcategories_id JOIN users u ON g.user_id = u.id WHERE g.url = "'.$gif_url.'"';
    }
    
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
// узнаем категорию, чтоб по категории определить нет ли в апкатегории 0
    $sql_gif_up_cat = 'SELECT c.upcategories_id FROM gifs g JOIN categories c ON g.category_id = c.id WHERE g.id = '.$gif_id;
    $res_gif = mysqli_query($connect, $sql_gif_up_cat);
    if ($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        $gif_up_cat = $gif['upcategories_id'];
        if (!isset($gif)) {
            header('Location: /404.php');
            http_response_code(404);
            $is404error = true;
        }
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    
    if ($gif_up_cat==0) {
        $sql_gif = 'SELECT g.id, g.dt_add, g.url, category_id, u.name, u.avatar_path, title, question, likes_count, favs_count, views_count, points, avg_points, votes, c.nameCat, c.urlCat FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'WHERE g.id = '.$gif_id;
    } else {
        $sql_gif = 'SELECT g.id, g.dt_add, g.url, category_id, u.name, u.avatar_path, title, question, likes_count, favs_count, views_count, points, avg_points, votes, c.nameCat, c.urlCat, up.up_id, up.name_up_Cat, up.url_up_Cat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN upcategories up ON up.up_id = c.upcategories_id JOIN users u ON g.user_id = u.id WHERE g.id = '.$gif_id;
    }

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
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text FROM comments c '.'JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC';
} else {
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id  WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC  LIMIT 3';
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

$href_amp = $address_site.'amp/'.$gif['urlCat'].'/'.$gif['url'].'/';
$href = $address_site.$gif['urlCat'].'/'.$gif['url'].'/';
// 5. запрос для списка похожих гифок
if (!$is404error) {
    $sql_similar = 'SELECT g.id, g.dt_add, category_id, u.name, u.avatar_path, title, question, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'WHERE category_id = '.$gif['category_id'].' AND g.id NOT IN('.$gif_id.') '.' LIMIT 6';
    $res_similar = mysqli_query($connect, $sql_similar);
    if ($res_similar) {
        $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
}
//add comment
if (isset($_SESSION['user'])) {
    $user_id = intval($_SESSION['user']['id']);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_GET['id']) || isset($_POST['gif_id'])) {
            $gif_id = intval($_GET['id']) ?? intval($_POST['gif_id']);
        }
        $comment = $_POST['comment'];
        $sql_gif = 'SELECT g.id, g.dt_add, category_id, u.name, title, question, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.nameCat, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE g.id = '.$gif_id;
        $res_gif = mysqli_query($connect, $sql_gif);
        if ($res_gif) {
            $gif = mysqli_fetch_assoc($res_gif);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        $required = ['comment'];
        $errors = [];
        $dict = ['comment' => 'Ответ'];
        foreach($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле должно быть заполнено';
            }
        }
        if (!count($errors)) {
            $sql = "INSERT INTO comments (dt_add, user_id, gif_id, comment_text) VALUES (NOW(), ?, ?, ?)";
            $stmt = db_get_prepare_stmt($connect, $sql, [$user_id, $gif_id, $comment]);
            $res = mysqli_stmt_execute($stmt);
            if (!$res) {
                $error = mysqli_error($connect);
                print($error);
            }
            $last_id = mysqli_insert_id($connect);
            //Составляем заголовок письма
                    $subject = "Новый ответ на сайте ".$_SERVER['HTTP_HOST'];
                    //Устанавливаем кодировку заголовка письма и кодируем его
                    $subject = "=?utf-8?B?".base64_encode($subject).
                    "?=";
                    //Составляем тело сообщения
            $message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).
            ' пользователем '.$_SESSION['user']['name'].' был оставлен ответ на сайте <a href="'.$address_site.
            '">'.$_SERVER['HTTP_HOST'].
            '</a>.
            А вот и сам Ответ: '.$comment.'<br>
            Чтобы одобрить и опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/comment-moderation.php?ok='.$last_id.'">"ОДОБРИТЬ"</a>.
            Чтобы удалить его безвозвратно, нажмите на ссылку <a href="'.$address_site.'gif/comment-moderation.php?del='.$last_id.'">"УДАЛИТЬ"</a>.
            Чтобы удалить его безвозвратно и занести пользователя в черный список, нажмите на ссылку <a href="'.$address_site.'gif/comment-moderation.php?del='.$last_id.'">"УДАЛИТЬ"</a>.
            Чтобы отредактировать и потом опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/comment-moderation.php?comment='.$comment.'&id='.$last_id.'">"РЕДАКТИРОВАТЬ"</a>.
            ';
            //Составляем дополнительные заголовки для почтового сервиса mail.ru
            $headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
            //Отправляем сообщение с ссылкой для подтверждения регистрации на указанную почту и проверяем отправлена ли она успешно или нет. 
            if (!mail($email_admin, $subject, $message, $headers)) {
                print("<p class='mesage_error'>Ошибка при отправлении письма с ссылкой подтверждения. Попробуйте еще раз.</p>");
                exit();
            }
            header('Location: '.$address_site.'post-comment/'.$gif['urlCat'].'/'.$gif['url'].'/');
            exit();    
        }
    }
}
if ($is404error) {
    $page_content = include_template('main.php', ['username' => $_SESSION['user']['name'], 'title' => '404 Страница не найдена', 'is404error' => $is404error]);
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href_amp' => $href_amp, 'href' => $href, 'gif' => $gif, 'Js' => $Js, 'items_count' => $items_count, 'categories' => $categories, 'upcategories' => $upcategories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
} else {
    $Js = "<script src='../js/pagination.js'></script>
        <script src='../js/gif.js'></script>
        <script src='../rating/rating.js'></script>";
    $page_content = include_template('gif.php', ['username' => $_SESSION['user']['name'], 'errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked, 'dict' => $dict]);
    if (isset($_SESSION['user'])) {
        $page_content = include_template('gif.php', ['username' => $_SESSION['user']['name'], 'errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked, 'dict' => $dict]);
        $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href' => $href, 'href_amp' => $href_amp, 'gif' => $gif, 'Js' => $Js, 'items_count' => $items_count, 'categories' => $categories, 'upcategories' => $upcategories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
    } else {
        $layout_content = include_template('layout.php', ['content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'href_amp' => $href_amp, 'gif' => $gif, 'items_count' => $items_count, 'href' => $href, 'num_online' => $num_online, 'Js' => $Js, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => $gif['title']]);
    }
}
print($layout_content);