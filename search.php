<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'title' => 'Результаты поиска'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Результаты поиска'
]);

print($layout_content);
