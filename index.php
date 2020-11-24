<?php

$isMainPage = true;

require_once('config.php');
require_once('functions.php');
require_once('check_cookie.php');
require_once('statistic/statistic.php'); 

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
if($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: ' . $error);
}

if (isset($_GET['tab'])) {

        // 3. создаем запрос для получения списка свежих гифок
	$sql_gifs = 'SELECT g.id, name, title, img_path, likes_count, favs_count, views_count ' .
	'FROM gifs g ' .
	'JOIN users u ON g.user_id = u.id ' .
	'ORDER BY g.dt_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

	$res_gifs = mysqli_query($connect, $sql_gifs);

	if($res_gifs) {
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	}
	else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: ' . $error);
	}
}
else {
        // 2. создаем запрос для получения списка топовых гифок
	$sql_gifs = 'SELECT g.id, name, title, img_path, likes_count, favs_count, views_count ' .
	'FROM gifs g ' .
	'JOIN users u ON g.user_id = u.id ' .
	'ORDER BY likes_count DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

        //отправляем запрос и получаем результат
	$res_gifs = mysqli_query($connect, $sql_gifs);
        //запрос выполнен успешно
	if($res_gifs) {
            //получаем гифки в виде двумерного массива
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	}
	else {
            //получаем текст последней ошибки
		$error = mysqli_error($connect);
		print('Ошибка MySQL: ' . $error);
	}
}

$param = isset($_GET['tab']) && $_GET['tab'] == 'new' ? ('tab=' . $_GET['tab'] . '&') : '';

$pagination = include_template('pagination.php', [
	'param' => $param,
	'pages_count' => $pages_count,
	'pages' => $pages,
	'current_page' => $current_page
]);

$page_content = include_template('main.php', [
	'gifs' => $gifs,
	'pagination' => $pagination,
	'title' => 'Смешные гифки',
	'isMainPage' => $isMainPage
]);

if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', [
		'username' => $_SESSION['user']['name'],
		'content' => $page_content,
		'categories' => $categories,
		'title' => 'Главная страница',
		'num_online' => $num_online,
		'num_visitors_hosts' => $row[0]['hosts'],
		'num_visitors_views' => $row[0]['views'],
		'hosts_stat_month' => $hosts_stat_month,
		'views_stat_month' => $views_stat_month,
		'isMainPage' => $isMainPage
	]);
}
else {
	$layout_content = include_template('layout.php', [
		'content' => $page_content,
		'categories' => $categories,
		'title' => 'Главная страница',
		'num_online' => $num_online,
		'num_visitors_hosts' => $row[0]['hosts'],
		'num_visitors_views' => $row[0]['views'],
		'hosts_stat_month' => $hosts_stat_month,
		'views_stat_month' => $views_stat_month,

		'isMainPage' => $isMainPage
	]);
}

print($layout_content);