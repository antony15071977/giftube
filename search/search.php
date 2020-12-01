<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
// 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
// 2. запрос для получения списка гифок
$gifs = [];
$search = isset($_GET['q']) ? $_GET['q'] : '';
//переменная для получения логического списка гифок
$search = $search.'*';
if ($search) {
	$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE MATCH(title, description) AGAINST('.
		'"'.$search.
		'"'.
		'IN BOOLEAN MODE)');
	$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
	$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
	//сколько позиций на странице
	$page_items = 3;
	$offset = ($current_page - 1) * $page_items;
	$pages_count = ceil($items_count / $page_items);
	$pages = range(1, $pages_count);
	$sql_gifs = 'SELECT g.id, g.dt_add, category_id, user_id, title, description, img_path, likes_count, favs_count, views_count, u.name '.
	'FROM gifs g '.
	'JOIN users u ON g.user_id = u.id '.
	'JOIN categories c ON g.category_id = c.id '.
	'WHERE MATCH(title, description) AGAINST(? IN BOOLEAN MODE) '.
	'ORDER BY g.dt_add DESC LIMIT '.$page_items.
	' OFFSET '.$offset;
	$stmt = db_get_prepare_stmt($connect, $sql_gifs, [$search]);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	if ($res) {
		$gifs = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}
}
$param = isset($_GET['q']) ? ('q='.$_GET['q'].
	'&') : '';
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'pages' => $pages, 'current_page' => $current_page]);
if (!$items_count) {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Ничего не найдено']);
} else {
	$page_content = include_template('main.php', ['gifs' => $gifs, 'title' => 'Результаты поиска', 'pagination' => $pagination]);
}
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'categories' => $categories, 'title' => 'Результаты поиска', 'search' => $search, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
} else {
	$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => 'Результаты поиска', 'search' => $search, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
}
print($layout_content);