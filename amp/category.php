<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
if (isset($_GET['url'])) {
    $cat_url = '';
    $cat_url = trim(htmlentities($_GET['url']));
    // 2. запрос для получения названия категории
	$sql_cat_name = 'SELECT * FROM categories WHERE urlCat = "'.$cat_url.'"';
	$res_cat_name = mysqli_query($connect, $sql_cat_name);
	if ($res_cat_name) {
		$category_name = mysqli_fetch_assoc($res_cat_name);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
	$category_id = $category_name['id'];
	$href = $address_site.'category/'.$category_name['urlCat'];
	$href_amp = $address_site.'amp/category/'.$category_name['urlCat'];
}

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
$sql_subcat = 'SELECT * FROM upcategories';
$res_subcat = mysqli_query($connect, $sql_subcat);
if ($res_subcat) {
	$upcategories = mysqli_fetch_all($res_subcat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
// 2. запрос для получения названия категории
$sql_cat_name = 'SELECT nameCat, urlCat FROM categories WHERE id = '.$category_id;
$res_cat_name = mysqli_query($connect, $sql_cat_name);
if ($res_cat_name) {
	$category_name = mysqli_fetch_assoc($res_cat_name);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
$href = $address_site.'category/'.$category_name['urlCat'];
$href_amp = $address_site.'amp/category/'.$category_name['urlCat'];
// 3. запрос для получения списка гифок по категории
$sql_gifs = 'SELECT g.id, category_id, title, likes_count, favs_count, question, views_count, points, avg_points, votes, g.url, c.urlCat, u.name '.
'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.
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
$param = isset($_GET['id']) || isset($_GET['url']) ? ('&id='.$category_id.'&') : '';
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'cat_id' => $category_id, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
if ($_GET['mode'] == 'w_js') {
    $page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'url' => 'category/'.$category_name['urlCat'], 'href_amp' => $href_amp, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].'»', 'category_name' => $category_name['nameCat']]);
    $layout_content = include_template('layout-amp-category.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href' => $href, 'upcategories' => $upcategories, 'categories' => $categories, 'Js' => $Js, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].
		'»', 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
    print($layout_content);
    exit();
}
if (isset($_GET['id']) && isset($_GET['page']) || isset($_GET['url']) && isset($_GET['page'])) {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].'»', 'category_name' => $category_name['nameCat'], 'url' => 'category/'.$category_name['urlCat'], 'pagination' => $pagination]);
	print($page_content);
	exit();
} else {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].'»', 'category_name' => $category_name['nameCat'], 'url' => 'category/'.$category_name['urlCat'], 'pagination' => $pagination]);
}
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout-amp-category.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href_amp' => $href_amp, 'href' => $href, 'Js' => $Js, 'upcategories' => $upcategories, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].
		'»'
	]);
} else {
	$layout_content = include_template('layout-amp-category.php', ['content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'href_amp' => $href_amp, 'href' => $href, 'Js' => $Js, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все вопросы в категории «'.$category_name['nameCat'].
		'»'
	]);
}
print($layout_content);