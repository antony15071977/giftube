<?php
require_once('../config/config.php');
require_once('../config/functions.php');
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
if($_POST['query'] != '')
{
  $query .= ' WHERE question LIKE "%'.str_replace(' ','%', $_POST['query']).'%"';
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
print($page_content);
exit();
?>