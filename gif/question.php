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
    if (isset($_GET["question"])&&isset($_GET["user_id"])&&isset($_GET["url"])&&isset($_GET["title"])&&isset($_GET["category"])&&$_GET['ok'] == 'true') {
        $sql = 'INSERT INTO gifs (dt_add, category_id, user_id, title, question, url) VALUES (NOW(), ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, [
         $_GET["category"],
         $_GET["user_id"],
         $_GET["title"],
         $_GET["question"],
         $_GET["url"]
        ]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $_SESSION["success_messages"] = "Вопрос одобрен и опубликован.";
        } else {
            $_SESSION["success_messages"] = "Что то пошло не так, чего то не хватает для занесения в базу данных, проверьте урл, обновите страницу, возможно поможет.";
        }
    } 
    if (isset($_GET["del"])) {
        $user_id = htmlspecialchars(intval($_GET['del']));
        $update_user = "UPDATE users SET status=0 WHERE id = '".$user_id."'";
        $res_update_user = mysqli_query($connect, $update_user);
        if ($res_update_user) {
            $_SESSION["success_messages"] = "Вопрос удален. Пользователь заблокирован.";
        } else {
            $_SESSION["success_messages"] = "Что то пошло не так, чего то не хватает для занесения в базу данных, проверьте урл, обновите страницу, возможно поможет.";
        }
        
    }
    if (isset($_GET["question"])&&isset($_GET["user_id"])&&isset($_GET["url"])&&isset($_GET["title"])&&isset($_GET["category"])&&isset($_GET["nameCat"])&&$_GET['edit'] == 'true') {
        $category = intval($_GET["category"]);
        $user_id = intval($_GET["user_id"]);
        $title = htmlspecialchars($_GET["title"]);
        $question = htmlspecialchars($_GET["question"]);
        $url = $_GET["url"];
        $nameCat = $_GET["nameCat"];
    }
    if ($_POST["question"]!='' && isset($_POST["user_id"]) && isset($_POST["url"]) && isset($_POST["title"]) && isset($_POST["category"])) {
        $category = intval($_POST["category"]);
        $user_id = intval($_POST["user_id"]);
        $title = htmlspecialchars($_POST["title"]);
        $question = htmlspecialchars($_POST["question"]);
        $url = $_POST["url"];
        $sql = "INSERT INTO gifs (dt_add, category_id, user_id, title, question, url) VALUES (NOW(), ?, ?, ?, ?, ?)";
            $stmt = db_get_prepare_stmt($connect, $sql, [$category, $user_id, $title, $question, $url]);
            $res = mysqli_stmt_execute($stmt);
            if (!$res) {
                $error = mysqli_error($connect);
                print($error);
            }
       $_SESSION["success_messages"] = "Вопрос исправлен, одобрен и опубликован.";
       $question = NULL;
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
    $page_content = include_template('question-moderation.php');
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'Js' => $Js, 'upcategories' => $upcategories, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
    print($layout_content);
    exit();
}
$page_content = include_template('question-moderation.php', ['category' => $category, 'user_id' => $user_id, 'title' => $title, 'question' => $question, 'categories' => $categories, 'url' => $url, 'nameCat' => $nameCat]);
$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content,  'categories' => $categories, 'upcategories' => $upcategories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
print($layout_content);