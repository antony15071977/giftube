<?php

$isFormPage = true;

require_once('functions.php');
require_once('data.php');

$add_form = include_template('add-form.php', [
    'categories' => $categories,
]);

$page_content = include_template('main.php', [
    'form' => $add_form,
    'title' => 'Добавить гифку',
    'isFormPage' => $isFormPage
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление новой гифки'
]);

print($layout_content);
