<?php

$isFormPage = true;

require_once('config.php');
require_once('functions.php');
require_once('statistic/statistic.php');

    // запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: ' . $error);
}
    //  send form
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

	//Удаляем пользователей с таблицы users, которые не подтвердили свою почту в течении суток

	$sql_del_user = 'DELETE FROM `users` WHERE `email_status` = 0 AND `dt_add` < ( NOW() - INTERVAL 1 DAY )';
	$res_del = mysqli_query($connect, $sql_del_user);
	if(!$res_del){
		$error = mysqli_error($connect);
    	$info = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного аккаунта. Код ошибки: '.$error.'</p>';
	}

	//Удаляем пользователей из таблицы confirm_users, которые не подтвердили свою почту в течении сутки

	$sql_del_user = 'DELETE FROM `confirm_users` WHERE `dt_add` < ( NOW() - INTERVAL 1 DAY )';
	$res_del = mysqli_query($connect, $sql_del_user);
	if(!$res_del){
		$error = mysqli_error($connect);
    	$info = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного неподтвержденного аккаунта. Код ошибки: '.$error.'</p>';
	}

        // проверка на существование пользователя с таким же email
	$email = htmlspecialchars($sign_in['email']);
	$sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
	$res_pass = mysqli_query($connect, $sql);
	if($res_pass) {
		$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;

		if($user) {
			if (password_verify($sign_in['password'], $user[0]['password'])) {
				$email_status = $user[0]['email_status'];
                //Если email не подтверждён
                    if($email_status == 0){
 
                    // Сохраняем в сессию сообщение об ошибке. 
                    $_SESSION["error_messages"] = "<p class='mesage_error' >Вы зарегистрированы, но Ваш почтовый адрес не подтверждён. Для подтверждения почты перейдите по ссылке из письма, которое получили после регистрации.</p>
                        <p><strong>Внимание!</strong> Ссылка для подтверждения почты, действительна 24 часа с момента регистрации. Если Вы не подтвердите Ваш email в течении этого времени, то Ваш аккаунт будет удалён.</p>";
 
             
                    //Возвращаем пользователя на страницу авторизации
                    header("Location: /signin.php");
                    }
                    else {
                    //место для добавления данных в сессию
                    // Если введенные данные совпадают с данными из базы, то сохраняем логин и пароль в массив сессий.
                    $_SESSION['user'] = $user[0];
 
                    //Возвращаем пользователя на главную страницу
                    header("Location: /");
                    }

			}
			else {
				$errors['password'] = 'Вы ввели неверный пароль';
			}
		}
		else {
			$errors['email'] = 'Пользователя с таким емeйлом не найдено.';
		}
	
		if (count($errors)) {
			$signin_form = include_template('signin-form.php', [
				'sign_in' => $sign_in,
				'errors' => $errors,
				'dict' => $dict
			]);
		}
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
		'num_online' => $num_online,
		'title' => 'Вход на сайт'
	]);
}
else {
	$layout_content = include_template('layout.php', [
		'content' => $page_content,
		'categories' => $categories,
		'num_online' => $num_online,
		'title' => 'Вход на сайт'
	]);
}

print($layout_content);
