<?php
require_once('../config/config.php');
require_once('../config/check_cookie.php');
require_once('functions.php');
require_once('status.php');
require_once('../statistic/statistic.php');
// всего пользователей
$res_count_users = mysqli_query($connect, 'SELECT count(*) AS cnt FROM users');
$users_count = mysqli_fetch_assoc($res_count_users)['cnt'];
// новых пользователей за день
$res=mysqli_query($connect,"SELECT count(*) AS cnt FROM `users` WHERE DATE(`dt_add`)=CURDATE()");
$users_count_day = mysqli_fetch_assoc($res)['cnt'];
// новых пользователей за неделю
$res=mysqli_query($connect,"SELECT count(*) AS cnt FROM `users` WHERE DATE(`dt_add`)> NOW() - INTERVAL 7 DAY");
$users_count_week = mysqli_fetch_assoc($res)['cnt'];
// новых пользователей за месяц
$res = mysqli_query($connect, "SELECT count(*) AS cnt FROM users WHERE `dt_add` > LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND `dt_add` < DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)");
$users_count_month = mysqli_fetch_assoc($res)['cnt'];
// Всего комментариев
$res_count_comm = mysqli_query($connect, 'SELECT count(*) AS cnt FROM comments WHERE NOT moderation = 0');
$count_comm = mysqli_fetch_assoc($res_count_comm)['cnt'];
// Новые комментарии за день
$res=mysqli_query($connect,"SELECT count(*) AS cnt FROM comments WHERE NOT moderation = 0 AND DATE(`dt_add`)=CURDATE()");
$count_comm_day = mysqli_fetch_assoc($res)['cnt'];
// Новые комментарии за неделю
$res=mysqli_query($connect,"SELECT count(*) AS cnt FROM comments WHERE NOT moderation = 0 AND DATE(`dt_add`)> NOW() - INTERVAL 7 DAY");
$count_comm_week = mysqli_fetch_assoc($res)['cnt'];
// Новые комментарии за месяц
$res=mysqli_query($connect,"SELECT count(*) AS cnt FROM comments WHERE NOT moderation = 0 AND `dt_add` > LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND `dt_add` < DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)");
$count_comm_month = mysqli_fetch_assoc($res)['cnt'];
// Всего Items
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
// Новые Items за день
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE DATE(`dt_add`)=CURDATE()');
$items_count_day = mysqli_fetch_assoc($res_count_gifs)['cnt'];
// Новые Items за неделю
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE DATE(`dt_add`)> NOW() - INTERVAL 7 DAY');
$items_count_week = mysqli_fetch_assoc($res_count_gifs)['cnt'];
// Новые Items за месяц
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE `dt_add` > LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND `dt_add` < DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY)');
$items_count_month = mysqli_fetch_assoc($res_count_gifs)['cnt'];




$page_content = include_template('main.php', ['users_count_week' => $users_count_week, 'items_count_day' => $items_count_day, 'items_count_week' => $items_count_week, 'items_count_month' => $items_count_month, 'count_comm_week' => $count_comm_week, 'count_comm_month' => $count_comm_month, 'items_count' => $items_count, 'count_comm' => $count_comm, 'count_comm_day' => $count_comm_day, 'users_count' => $users_count, 'users_count_day' => $users_count_day, 'users_count_month' => $users_count_month, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month]);    
$layout_content = include_template('layout.php', ['title' => 'Админпанель', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_main' => 'class="active"']);
print($layout_content);