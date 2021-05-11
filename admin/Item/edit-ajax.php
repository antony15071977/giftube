<?php
require_once('../../config/config.php');
require_once('functions.php');
require_once('../../config/check_cookie.php');
require_once('../../statistic/statistic.php');
// 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
	echo json_encode(array(
                'result'    => 'error',
                'page_content'      => 'Ошибка MySQL: '.$error
            ));
	exit();
}
$id = htmlspecialchars(intval($_POST['edit']));
$sql_gif = 'SELECT g.id, u.name, c.nameCat, g.category_id,  description, title, img_path, g.url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE g.id = "'.$id.'"';
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
$page_content = include_template('edit-form.php', ['gif' => $gif, 'categories' => $categories, 'id' => $id]);
echo json_encode(array(
    'result'    => 'simple',
    'page_content'      => $page_content
));
exit();
