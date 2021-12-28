<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
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
if (isset($_SESSION['user'])&&$_SESSION['user']['status']==3) {
    if (isset($_GET["ok"])) {
        $com_id = htmlspecialchars(intval($_GET['ok']));
        $comment = htmlspecialchars($_GET['comment']);
        $update_comment = "UPDATE comments SET comment_text='".$comment."' WHERE id = '".$com_id."'";
        $res_update_comment = mysqli_query($connect, $update_comment);
        $_SESSION["success_messages"] = "Ответ одобрен и опубликован.";
    }
    if (isset($_GET["del"])) {
        $com_id = htmlspecialchars(intval($_GET['del']));
        $res=mysqli_query($connect,"DELETE FROM comments WHERE id='".$com_id."'");
        $_SESSION["success_messages"] = "Ответ удален.";
    }
    if (isset($_GET["del"]) && isset($_GET["user_id"])) {
        $com_id = htmlspecialchars(intval($_GET['del']));
        $user_id = htmlspecialchars(intval($_GET['user_id']));
        $res=mysqli_query($connect,"DELETE FROM comments WHERE id='".$com_id."'");
        $update_user = "UPDATE users SET status=0 WHERE id = '".$user_id."'";
        $res_update_user = mysqli_query($connect, $update_user);
        $_SESSION["success_messages"] = "Ответ удален. Пользователь заблокирован.";
    }
    if ($_GET["comment"]) {
        $comment = htmlspecialchars($_GET['comment']);
        $com_id = htmlspecialchars(intval($_GET['id']));
        $res=mysqli_query($connect,"SELECT q.comment_text, g.dt_add, q.id, q.gif_id, u.name, g.title, g.url, c.urlCat FROM comments q JOIN gifs g ON g.id = q.gif_id JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id  WHERE q.id='".$com_id."'");
        $com=mysqli_fetch_array($res);
    }
    if ($_POST["comment"]!='') {
        $com_id = htmlspecialchars(intval($_POST['com_id']));  
        $res=mysqli_query($connect,"UPDATE comments
     SET comment_text='".htmlspecialchars($_POST["comment"])."', moderation=1 WHERE id='".$com_id."'");
       $_SESSION["success_messages"] = "Ответ исправлен, одобрен и опубликован.";
    }
} else {
    $_SESSION["error_messages"] = "У вас нет прав администратора";
    if(isset($_COOKIE["cookie_token"])){
        //Очищаем поле cookie_token из базы данных
        $update_cookie_token = "UPDATE users SET cookie_token='' WHERE email = '".$email."'";
        $res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
    //Удаляем куку cookie_token
        setcookie("cookie_token", "", time() - 3600);
    }
    unset($_SESSION['user']);
    $Js = '<script src="../js/auth.js"></script>';
    $page_content = include_template('comment-moderation.php');
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'Js' => $Js, 'upcategories' => $upcategories, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
    print($layout_content);
    exit();
}
$page_content = include_template('comment-moderation.php', ['com' => $com]);
$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content,  'categories' => $categories, 'upcategories' => $upcategories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
print($layout_content);