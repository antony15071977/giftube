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
$sql_subcat = 'SELECT * FROM upcategories';
$res_subcat = mysqli_query($connect, $sql_subcat);
if ($res_subcat) {
	$upcategories = mysqli_fetch_all($res_subcat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}

$limit = '15';
$page = 1;
if($_GET['page'] > 1)
{
  $start = (($_GET['page'] - 1) * $limit);
  $page = $_GET['page'];
}
else
{
  $start = 0;
}
$query = "
SELECT g.id, title, question, url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id
";
if($_GET['q'] != '')
{
  $query .= ' WHERE question LIKE "%'.str_replace(' ','%', $_GET['q']).'%"';
}
$query .= 'ORDER BY g.id ASC ';
$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';
$statement = $connectSearch->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();
$statement = $connectSearch->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$items_count = $total_data;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_items = $limit;
$offset = ($current_page - 1) * $page_items;
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);
$url = "/search/fetch.php";
$pagination = include_template('pagination.php', ['pages_count' => $pages_count, 'items_count' => $items_count, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
$page_content = include_template('search.php', ['gifs' => $result, 'items_count' => $items_count, 'pagination' => $pagination, 'title' => 'Результаты поиска']);

if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'title' => 'Результаты поиска', 'search' => $search, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'Js' => $Js, 'views_stat_month' => $views_stat_month]);
} else {
	$layout_content = include_template('layout.php', ['content' => $page_content, 'upcategories' => $upcategories, 'categories' => $categories, 'Js' => $Js, 'title' => 'Результаты поиска', 'search' => $search, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);
}
print($layout_content);