<?php
$isGifPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
if (isset($_GET['id']) || isset($_POST['gif_id'])) {
    $gif_id = intval($_GET['id'] ?? intval($_POST['gif_id']));
}
if ($_POST['content'] == 'hide') {
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text FROM comments c '.'JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC LIMIT 3';
    $res_comments = mysqli_query($connect, $sql_comments);
    if ($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    $page_content = include_template('gif-comments.php', ['errors' => $errors, 'comments' => $comments, 'gif_id' => $gif_id]);
    print($page_content);
    exit();
}
if ($_POST['comments'] == 'count') {
    $res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = "'.$gif_id.'" AND NOT moderation = 0');
    $count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
    if (!$res_count_comm) {
        $count_comm = 0;
    }
    echo json_encode(array(
            'count_comm' => $count_comm
    ));
    exit();
}
// 2. запрос для получения данных гифки по id
$sql_gif = 'SELECT g.id, category_id, u.name, title, likes_count, favs_count, views_count, question FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE g.id = '.$gif_id;
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
     echo json_encode(array(
            'result'    => 'error',
            'error'     => 'Ошибка MySQL: '.$error
        ));
     exit();
}
// Обновление просмотров гифки по id
$sql_update_views = "UPDATE gifs SET views_count = views_count + 1 WHERE id = ".$gif_id;
$res_update_views = mysqli_query($connect, $sql_update_views);
// если гифка добавлена в избранное
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $gif_id = $_GET['id'] ?? $_POST['gif_id'];
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
$sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.'WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC  LIMIT 3';
$res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = "'.$gif_id.'" AND NOT moderation = 0');
$count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['count_add'])) {
    $gif_id = (int)$_POST['gif_id'];
    $countView = (int)$_POST['count_add'];  // количество записей, получаемых за один раз
    $startIndex = (int)$_POST['count_show']; // с какой записи начать выборку
    $sql_comments = 'SELECT c.dt_add, c.id, u.avatar_path, u.name, c.comment_text FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id.' AND NOT moderation = 0 ORDER BY c.dt_add DESC LIMIT '.$startIndex.', '.$countView;
    $res_comments = mysqli_query($connect, $sql_comments);
    if ($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
    if(empty($comments)){
        // если комментов нет
        echo json_encode(array(
            'result'    => 'finish'
        ));
    } else {
        $html = "";
        foreach($comments as $comment){
            $comm_av_path = $comment['avatar_path'] != NULL ? $comment['avatar_path'] : 'user.svg';
            $html .= "
                <article class='comment'>
                    <img width='100' height='100' class='comment__picture' src='/uploads/avatar/{$comm_av_path}'>
                    <div class='comment__data'>
                            <div class='comment__author'>{$comment['name']}</div>
                            <div class='comment__author'>[{$comment['dt_add']}]</div>
                            <p class='comment__text ".
                            (($comment['name'] == $_SESSION['user']['name']) ? "inlineEdit" : "")
                        ."' data-id='{$comment['id']}'>{$comment['comment_text']}</p>".
                            (($comment['name'] == $_SESSION['user']['name']) ? "<span class='comment__author comment__sign'><img class='comment__edit' src='img/pen.png'>Нажмите на свой ответ, чтобы отредактировать</span>" : "")
                        ."</div>
                    </article>
            ";
        }
        echo json_encode(array(
            'result'    => 'success',
            'html'      => $html
        ));
    }
    exit();
}
$res_comments = mysqli_query($connect, $sql_comments);
if ($res_comments) {
    $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}
$sql_similar = 'SELECT g.id, category_id, u.name, title, question, likes_count, favs_count, views_count, points, avg_points, votes, g.url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE category_id = '.$gif['category_id'].' AND g.id NOT IN('.$gif_id.')  LIMIT 6';
    $res_similar = mysqli_query($connect, $sql_similar);
    if ($res_similar) {
        $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: '.$error);
    }
//add comment
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $gif_id = intval($_POST['gif_id']);
        $comment = stripslashes($_POST['comment']);
        $sql_gif = 'SELECT g.id, category_id, u.name, title, likes_count, favs_count, views_count, question FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE g.id = '.$gif_id;
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
            if (isset($_SESSION['user'])) {
                    //Составляем заголовок письма
                    $subject = "Новый ответ на сайте ".$_SERVER['HTTP_HOST'];
                    //Устанавливаем кодировку заголовка письма и кодируем его
                    $subject = "=?utf-8?B?".base64_encode($subject).
                    "?=";
                    //Составляем тело сообщения
            $message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).
            ' пользователем '.$_SESSION['user']['name'].' был оставлен ответ на сайте <a href="'.$address_site.'">'.$_SERVER['HTTP_HOST'].
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
                $error = "<p class='mesage_error'>Ошибка при отправлении письма с ссылкой подтверждения. Попробуйте еще раз.</p>";
                echo json_encode(array(
                    'result'    => 'error',
                    'error'     => $error
                ));
                exit();
            }
            echo json_encode(array(
                'result'    => 'success'
                ));
            exit();
            }
        } else {
            $error = "<p class='mesage_error'>Произошла непредвиденная ошибка, обновите страницу и попробуйте еще раз.</p>";
            echo json_encode(array(
                'result'    => 'error',
                'error'     => $error
                ));
            exit();
        }
    } 
}
$page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'comments' => $comments, 'gif_id' => $gif_id, 'count_comm' => $count_comm, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
if (isset($_SESSION['user'])) {
    $page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked]);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            print($page_content);
            exit();
    }
}
