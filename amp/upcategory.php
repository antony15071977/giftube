<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
if (isset($_GET['url'])) {
    $cat_url = '';
    $cat_url = trim(htmlentities($_GET['url']));
    // 2. запрос для получения названия категории
	$sql_upcat_name = 'SELECT * FROM upcategories WHERE url_up_Cat = "'.$cat_url.'"';
	$res_upcat_name = mysqli_query($connect, $sql_upcat_name);
	if ($res_upcat_name) {
		$up_category_name = mysqli_fetch_assoc($res_upcat_name);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
	$up_category_id = $up_category_name['up_id'];
	$href = $address_site.'upcategory/'.$up_category_name['url_up_Cat'];
}
if (isset($_GET['id'])) {
	$up_category_id = intval(trim($_GET['id']));

	// 2. запрос для получения url категории
	$sql_upcat_url = 'SELECT * FROM upcategories WHERE up_id = "'.$up_category_id.'"';
	$res_upcat_url = mysqli_query($connect, $sql_upcat_url);
	if ($res_upcat_url) {
		$up_category_url = mysqli_fetch_assoc($res_upcat_url);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
	$cat_url = $up_category_url['url_up_Cat'];
$href = $address_site.'upcategory/'.$up_category_url['url_up_Cat'];
}

$sql_res_count_gifs = 'SELECT count(*) AS cnt FROM gifs g JOIN categories c ON g.category_id = c.id  JOIN upcategories up ON up.up_id = c.upcategories_id  WHERE up.url_up_Cat = "'.$cat_url.'"';
$res_count_gifs = mysqli_query($connect, $sql_res_count_gifs);
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
$sql_upcat = 'SELECT * FROM upcategories';
$res_upcat = mysqli_query($connect, $sql_upcat);
if ($res_upcat) {
	$upcategories = mysqli_fetch_all($res_upcat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
// 2. запрос для получения названия категории
$sql_up_cat_name = 'SELECT name_up_Cat, url_up_Cat FROM upcategories WHERE up_id = '.$up_category_id;
$res_up_cat_name = mysqli_query($connect, $sql_up_cat_name);
if ($res_up_cat_name) {
	$up_category_name = mysqli_fetch_assoc($res_up_cat_name);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
$href_amp = $address_site.'amp/upcategory/'.$up_category_name['url_up_Cat'];
// 3. запрос для получения списка гифок по категории
$sql_gifs = 'SELECT g.id, category_id, u.name, title,  likes_count, favs_count, views_count, points, question, avg_points, votes, g.url, c.urlCat, up.up_id, up.name_up_Cat, up.url_up_Cat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id JOIN upcategories up ON up.up_id = c.upcategories_id  WHERE up.up_id = '.$up_category_id.' ORDER BY g.dt_add DESC LIMIT '.$page_items.' OFFSET '.$offset;
$res_gifs = mysqli_query($connect, $sql_gifs);
if ($res_gifs) {
	$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
$Js = "<script src='../js/pagination.js'></script>";
$url = "/gif/upcategory.php";
$param = isset($_GET['id']) || isset($_GET['url']) ? ('&id='.$up_category_id.'&') : '';
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'cat_id' => $up_category_id, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);

if ($_GET['mode'] == 'w_js') {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'url' => $address_site.'upcategory/'.$up_category_name['url_up_Cat'], 'up_category_name' => $up_category_name['name_up_Cat'], 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»']);
    $layout_content = include_template('layout-amp-category.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href_amp' => $href_amp, 'href' => $href, 'upcategories' => $upcategories, 'categories' => $categories, 'Js' => $Js, 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»', 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
    print($layout_content);
    exit();
}
if (isset($_GET['id']) && isset($_GET['page']) || isset($_GET['url']) && isset($_GET['page'])) {
	
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»', 'url' => 'upcategory/'.$up_category_name['url_up_Cat'], 'up_category_name' => $up_category_name['name_up_Cat'], 'pagination' => $pagination]);
	print($page_content);
	exit();
} else {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»', 'url' => 'upcategory/'.$up_category_name['url_up_Cat'], 'up_category_name' => $up_category_name['name_up_Cat'], 'pagination' => $pagination]);
}
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout-amp-category.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'Js' => $Js, 'href_amp' => $href_amp, 'href' => $href, 'upcategories' => $upcategories, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»'
	]);
} else {
	$layout_content = include_template('layout-amp-category.php', ['content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'href_amp' => $href_amp, 'href' => $href, 'Js' => $Js, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Все вопросы в категории «'.$up_category_name['name_up_Cat'].'»'
	]);
}
print($layout_content);