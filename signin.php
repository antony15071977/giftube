<?php

session_start();

$isFormPage = true;

require_once('config.php');
require_once('functions.php');

    // 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: ' . $error);
}
    // 2. send form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sign_in = $_POST;

	$required = ['email', 'password'];
	$errors = [];
	$dict = [
		'email' => 'E-mail',
		'password' => 'Пароль'
	];

	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}

        //проверка email на корректность
	if (!empty($sign_in['email'])) {
		if (!filter_var($sign_in['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Email должен быть корректным';
		}
	}

        // проверка на существование пользователя с таким же email
	$sql = 'SELECT * FROM users WHERE email = "' . $sign_in['email'] . '"';
	$res_pass = mysqli_query($connect, $sql);
	if($res_pass) {
		$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;

		if($user) {
			if (password_verify($sign_in['password'], $user[0]['password'])) {
				$_SESSION['user'] = $user[0];
			} else {
				$errors['password'] = 'Вы ввели неверный пароль';
			}
		}
	}

	if (count($errors)) {
		$signin_form = include_template('signin-form.php', [
			'sign_in' => $sign_in,
			'errors' => $errors,
			'dict' => $dict
		]);
	}
	else {
		header('Location: /');
		exit();
	}
}
else {
	$signin_form = include_template('signin-form.php');
}

$page_content = include_template('main.php', [
	'form' => $signin_form,
	'title' => 'Вход для своих',
	'isFormPage' => $isFormPage
]);

if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', [
		'username' => $_SESSION['user']['name'],
		'content' => $page_content,
		'categories' => $categories,
		'title' => 'Вход на сайт'
	]);
}
else {
	$layout_content = include_template('layout.php', [
		'content' => $page_content,
		'categories' => $categories,
		'title' => 'Вход на сайт'
	]);
}

print($layout_content);
