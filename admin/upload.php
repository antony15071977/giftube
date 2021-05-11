<?php
require_once('../config/config.php');
require_once('../config/check_cookie.php');
require_once('status.php');
require_once('functions.php');
require_once('../statistic/statistic.php');


$page_content = include_template('upload.php', ['reply' => null]);    
$layout_content = include_template('layout.php', ['title' => 'Админпанель/загрузка', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_upload' => 'class="active"']);
print($layout_content);