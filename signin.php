<?php

$isFormPage = true;

require_once('functions.php');
require_once('data.php');

$signin_form = include_template('signin-form.php');

$page_content = include_template('main.php', [
    'form' => $signin_form,
    'title' => 'Вход для своих',
    'isFormPage' => $isFormPage
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Вход на сайт'
]);

print($layout_content);
