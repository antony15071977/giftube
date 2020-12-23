<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
if (isset($_GET['id'])) {
	$category_id = intval(trim($_GET['id']));
}
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE category_id = '.$category_id);
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_items = 9;
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
// 2. запрос для получения названия категории
$sql_cat_name = 'SELECT name FROM categories WHERE id = '.$category_id;
$res_cat_name = mysqli_query($connect, $sql_cat_name);
if ($res_cat_name) {
	$category_name = mysqli_fetch_assoc($res_cat_name);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
// 3. запрос для получения списка гифок по категории
$sql_gifs = 'SELECT g.id, name, title, img_path, likes_count, favs_count, views_count '.
'FROM gifs g '.
'JOIN users u ON g.user_id = u.id '.
'WHERE g.category_id = '.$category_id.
' ORDER BY g.dt_add DESC LIMIT '.$page_items.
' OFFSET '.$offset;
$res_gifs = mysqli_query($connect, $sql_gifs);
if ($res_gifs) {
	$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
$Js = "<script src='../js/pagination.js'></script>";
$url = "/gif/category.php";
$param = isset($_GET['id']) ? ('&id='.$_GET['id'].'&') : '';
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'cat_id' => $category_id, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
if ($_GET['mode'] == 'w_js') {
    $page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'title' => $category_name['name']]);
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'categories' => $categories, 'Js' => $Js, 'title' => 'Все гифки в категории «'.$category_name['name'].
		'»', 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
    print($layout_content);
    exit();
}
if (isset($_GET['id']) && isset($_GET['page'])) {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => $category_name['name'], 'pagination' => $pagination]);
	print($page_content);
	exit();
} else {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => $category_name['name'], 'pagination' => $pagination]);
}
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'Js' => $Js, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все гифки в категории «'.$category_name['name'].
		'»'
	]);
} else {
	$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'Js' => $Js, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все гифки в категории «'.$category_name['name'].
		'»'
	]);
}
print($layout_content);