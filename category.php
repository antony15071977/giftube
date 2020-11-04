<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'title' => 'Категория'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все гифки в категории ...'
]);

print($layout_content);
