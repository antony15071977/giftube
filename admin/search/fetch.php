<?php
require_once('../../config/config.php');
require_once('functions.php');
require_once('../../config/check_cookie.php');
$limit = '9';
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
if ($_POST['search'] == 'items') {
	$query = "SELECT g.id, u.name, c.nameCat, description, title, img_path, g.url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id ";

	if ($_POST['option'] == 'id') {
		$query .= ' WHERE '.'g.'.$_POST['option'].' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'category_name') {
		$query .= ' WHERE '.'c.nameCat'.' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'user_name') {
		$query .= ' WHERE '.'u.name'.' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} else {
		$query .= ' WHERE '.$_POST['option'].' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	}
	$template = 'search.php';
}
elseif ($_POST['search'] == 'users') {
	$query = "SELECT id, dt_add, name, email, avatar_path, status FROM users ";

	if ($_POST['option'] == 'id') {
			$query .= ' WHERE '.$_POST['option'].' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'dt_add') {
			$query .= ' WHERE dt_add LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'name') {
			$query .= ' WHERE name LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'email') {
			$query .= ' WHERE email LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	} elseif ($_POST['option'] == 'status') {
			$query .= ' WHERE status LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	}
	else {
			$query .= ' WHERE '.$_POST['option'].' LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
	}
	$template = 'search_users.php';
}

$query .= 'ORDER BY id ASC ';
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
$url = "/admin/search/fetch.php";
$pagination = include_template('pagination.php', ['pages_count' => $pages_count, 'items_count' => $items_count, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
$page_content = include_template($template, ['gifs' => $result, 'items_count' => $items_count, 'pagination' => $pagination, 'title' => 'Результаты поиска']);
print($page_content);
exit();
?>