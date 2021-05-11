<?php
require_once('../../config/config.php');
require_once('functions.php');
require_once('../../config/check_cookie.php');
require_once('../../statistic/statistic.php');
$id = $_POST['edit'];
$sql_gif = 'SELECT id, dt_add, name, email, avatar_path, status FROM users WHERE id = "'.$id.'"';
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
$sql = "SELECT id FROM users WHERE name = '".$gif['name']."'";
    $res = mysqli_query($connect, $sql);
    if ($res) {
        $user = mysqli_fetch_assoc($res);
        $author = $user['id'];
        $gif["author"] = $author;
    }
$page_content = include_template('edit-user-form.php', ['gif' => $gif, 'categories' => $categories, 'id' => $id]);
echo json_encode(array(
    'result'    => 'simple',
    'page_content'      => $page_content
));
exit();
