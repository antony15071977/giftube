<?php
$isGifPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
if (isset($_GET['id']) || isset($_POST['gif_id'])) {
    $gif_id = intval($_GET['id'] ?? intval($_POST['gif_id']));
}
if ($_POST['content'] == 'hide') {
    $sql_comments = 'SELECT c.dt_add, c.id, avatar_path, name, comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.'WHERE g.id = '.$gif_id.' ORDER BY c.dt_add DESC  LIMIT 3';
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
    $res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id);
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
// end если гифка добавлена в избранное
// 3. add comment
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['comment'] != '') {
        $gif_id = intval($_POST['gif_id']);
        $comment = stripslashes($_POST['comment']);
        $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, '.
        'likes_count, favs_count, views_count, description '.
        'FROM gifs g '.
        'JOIN categories c ON g.category_id = c.id '.
        'JOIN users u ON g.user_id = u.id '.
        'WHERE g.id = '.$gif_id;
        $res_gif = mysqli_query($connect, $sql_gif);
        if ($res_gif) {
            $gif = mysqli_fetch_assoc($res_gif);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
        $required = ['comment'];
        $errors = [];
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
            $sql_comments = 'SELECT c.dt_add, c.id, avatar_path, name, comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.'WHERE g.id = '.$gif_id.' ORDER BY c.dt_add DESC  LIMIT 3';
            $res_comments = mysqli_query($connect, $sql_comments);
            if ($res_comments) {
                $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
            } else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: '.$error);
            }
            if (isset($_SESSION['user'])) {
                $page_content = include_template('gif-comments.php', ['errors' => $errors, 'comments' => $comments, 'gif_id' => $gif_id]);
                    print($page_content);
                    exit();
            }
        }
    }
}
// 4. all comments
$sql_comments = 'SELECT c.dt_add, c.id, avatar_path, name, comment_text '.'FROM comments c '.'JOIN gifs g ON g.id = c.gif_id '.'JOIN users u ON c.user_id = u.id '.'WHERE g.id = '.$gif_id.' ORDER BY c.dt_add DESC  LIMIT 3';
$res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id);
$count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['count_add'])) {
    $gif_id = (int)$_POST['gif_id'];
    $countView = (int)$_POST['count_add'];  // количество записей, получаемых за один раз
    $startIndex = (int)$_POST['count_show']; // с какой записи начать выборку
    $sql_comments = 'SELECT c.dt_add, c.id, avatar_path, name, comment_text FROM comments c JOIN gifs g ON g.id = c.gif_id JOIN users u ON c.user_id = u.id WHERE g.id = '.$gif_id.'  ORDER BY c.dt_add DESC LIMIT '.$startIndex.', '.$countView;
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
            $html .= "
                <article class='comment'>
                    <img width='100' height='100' class='comment__picture' src='{$comment['avatar_path']}'>
                    <div class='comment__data'>
                            <div class='comment__author'>{$comment['name']}</div>
                            <div class='comment__author'>[{$comment['dt_add']}]</div>
                            <p class='comment__text ".
                            (($comment['name'] == $_SESSION['user']['name']) ? "inlineEdit" : "")
                        ."' data-id='{$comment['id']}'>{$comment['comment_text']}</p>".
                            (($comment['name'] == $_SESSION['user']['name']) ? "<span class='comment__author comment__sign'><img class='comment__edit' src='img/pen.png'>Нажмите на свой комментарий, чтобы отредактировать</span>" : "")
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
$page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'comments' => $comments, 'gif_id' => $gif_id, 'count_comm' => $count_comm, 'gifs' => $similar_gifs, 'isGifPage' => $isGifPage]);
if (isset($_SESSION['user'])) {
    $page_content = include_template('gif.php', ['errors' => $errors, 'gif' => $gif, 'count_comm' => $count_comm,  'comments' => $comments, 'gifs' => $similar_gifs, 'gif_id' => $gif_id, 'isGifPage' => $isGifPage, 'isFav' => $isFav, 'isLiked' => $isLiked]);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            print($page_content);
            exit();
    }
}
