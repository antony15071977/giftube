<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'title' => 'Избранное'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Моё избранное'
]);

print($layout_content);
