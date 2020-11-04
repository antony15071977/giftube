<?php

$isGifPage = true;

require_once('functions.php');
require_once('data.php');

$page_content = include_template('gif.php', [
    'gifs' => $gifs,
    'isGifPage' => $isGifPage
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Название гифки'
]);


print($layout_content);
