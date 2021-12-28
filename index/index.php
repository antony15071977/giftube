<?php
$isMainPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
$href = $address_site;
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
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
if ($_GET['tab'] == 'new') {
	// 3. создаем запрос для получения списка свежих гифок
	$sql_gifs = 'SELECT g.id, g.dt_add, title, likes_count, favs_count, views_count, points, question,  avg_points, votes, g.url, c.urlCat, u.name, u.avatar_path FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id ORDER BY g.dt_add DESC LIMIT '.$page_items.' OFFSET '.$offset;
	$res_gifs = mysqli_query($connect, $sql_gifs);
	if ($res_gifs) {
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
} elseif ($_GET['tab'] == 'rating') {
	// 2. создаем запрос для получения списка самых рейтинговых по звездам
	$sql_gifs = 'SELECT g.id, g.dt_add, title, likes_count, favs_count, views_count, points, avg_points, question, votes, g.url, c.urlCat, u.name, u.avatar_path FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id ORDER BY avg_points DESC LIMIT '.$page_items.' OFFSET '.$offset;
	
	//отправляем запрос и получаем результат
	$res_gifs = mysqli_query($connect, $sql_gifs);
	//запрос выполнен успешно
	if ($res_gifs) {
		//получаем гифки в виде двумерного массива
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		//получаем текст последней ошибки
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
}
else {
	// 3. создаем запрос для получения списка топовых гифок
	$sql_gifs = 'SELECT g.id, g.dt_add, title, likes_count, favs_count, views_count, points, avg_points, question, votes, g.url, c.urlCat, u.name, u.avatar_path FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id ORDER BY likes_count DESC LIMIT '.$page_items.' OFFSET '.$offset;
	//отправляем запрос и получаем результат
	$res_gifs = mysqli_query($connect, $sql_gifs);
	//запрос выполнен успешно
	if ($res_gifs) {
		//получаем гифки в виде двумерного массива
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		//получаем текст последней ошибки
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
}
$param = '';
$param = isset($_GET['tab']) ? ('&tab='.$_GET['tab'].'&') : '';
$url = "/index/index.php";
$Js = "<script src='../js/pagination.js'></script>";
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
// Вариант, когда не работает яваскрипт и срабатывает переход по ссылке
if ($_GET['mode'] == 'w_js') {
    $page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'title' => 'Смешные гифки', 'isMainPage' => $isMainPage]);
    $layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'href' => $href, 'upcategories' => $upcategories, 'categories' => $categories, 'items_count' => $items_count, 'Js' => $Js, 'title' => 'Главная страница', 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isMainPage' => $isMainPage]);
    print($layout_content);
    exit();
}
if ($_GET['update'] == 'true') {
    $page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'title' => 'Смешные гифки', 'isMainPage' => $isMainPage]);
    print($page_content);
    exit();
}
if (isset($_GET['tab']) && isset($_GET['page']) || isset($_GET['page']) || isset($_GET['tab']) || isset($_GET['top'])) {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'title' => 'Смешные гифки', 'isMainPage' => $isMainPage]);
		print($page_content);
		exit();
} else {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'pagination' => $pagination, 'title' => 'Смешные гифки', 'isMainPage' => $isMainPage]);
}
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'categories' => $categories, 'href' => $href, 'upcategories' => $upcategories, 'items_count' => $items_count, 'Js' => $Js, 'title' => 'Главная страница', 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isMainPage' => $isMainPage]);
} else {
	$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'upcategories' => $upcategories, 'Js' => $Js, 'title' => 'Главная страница', 'items_count' => $items_count, 'href' => $href, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isMainPage' => $isMainPage]);
}
print($layout_content);