<?php

$isFormPage = true;

require_once('data.php');
require_once('functions.php');

$signup_form = include_template('signup-form.php');

$page_content = include_template('main.php', [
    'form' => $signup_form,
    'title' => 'Регистрация',
    'isFormPage' => $isFormPage
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация пользователя'
]);

print($layout_content);
