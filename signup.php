<?php

$isFormPage = true;

require_once('config.php');
require_once('functions.php');
$Js = '<script src="../js/register.js"></script>';

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
	$sign_up = $_POST;

	$required = ['email', 'password', 'name', 'confirm_password'];
	$errors = [];
	$dict = [
		'email' => 'E-mail',
		'password' => 'Пароль',
		'name' => 'Имя',
		'confirm_password' => 'Подтверждение пароля',
		'avatar' => 'Фото'
	];

	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено.';
		}
	}

	$email = $sign_up['email'];

        //проверка email на корректность
	if (!empty($email)) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Email должен быть корректным';
		}
	}

        // проверка на существование пользователя с таким же email
	if (!empty($email)) {
		$sql = 'SELECT id FROM users WHERE email = "' . $email . '"';
		$res_email = mysqli_query($connect, $sql);
		if($res_email) {
			$emails = mysqli_fetch_all($res_email, MYSQLI_ASSOC);
			if(!empty($emails)) {
				$errors['email'] = 'Введённый вами email <strong>'.$email. '</strong> уже зарегистрирован. Введите другой email.';
			}
		}
	}

        // load avatar
	if (isset($_FILES['avatar']['name'])) {
		if(!empty($_FILES['avatar']['name'])) {
			$tmp_name = $_FILES['avatar']['tmp_name'];
			$file = $_FILES['avatar']['name'];

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($finfo, $tmp_name);

                // Получаем расширение загруженного файла
			$extension = strtolower(substr(strrchr($file, '.'), 1));
                //Генерируем новое имя файла
			$file = uniqid() . '.' .  $extension;
                //Папка назначения
			$dest = 'uploads/avatar/';

			if (($file_type == "image/gif") || ($file_type == "image/jpeg") || ($file_type == "image/png") || ($file_type == "image/pjepg")) {
				move_uploaded_file($tmp_name, $dest . $file);
				$sign_up['avatar_path'] = $dest . $file;
			}
			else {
				$errors['avatar'] = 'Файл с таким расширением невозможно загрузить';
			}
		}
	}

	//проверка пароля на длину
	$password = $sign_up['password'];
	if (strlen($password) < 6) {
		$errors['password'] = 'Пароль должен быть более 6 символов';
	}

	//проверка повтора пароля
	$confirm_password = $sign_up['confirm_password'];
	if ($confirm_password !== $password) {
		$errors['confirm_password'] = 'Пароли должны совпадать.';
	}

	//проверка логина на длину
	$name = htmlspecialchars($sign_up['name']);
	if (strlen($name) < 5) {
		$errors['name'] = 'Логин должен быть не менее 5 символов.';
	}

	// проверка на существование пользователя с таким же login
	if (!empty($name)) {
		$sql = 'SELECT id FROM users WHERE name = "' . $name . '"';
		$res_login = mysqli_query($connect, $sql);
		if($res_login) {
			$logins = mysqli_fetch_all($res_login, MYSQLI_ASSOC);
			if(!empty($logins)) {
				$errors['name'] = 'Введённый вами логин <strong>'.$name. '</strong> уже зарегистрирован. Придумайте другой логин.';
			}
		}
	}

	if (count($errors)) {
		$signup_form = include_template('signup-form.php', [
			'sign_up' => $sign_up,
			'errors' => $errors,
			'dict' => $dict
		]);
	}
	else {
            // хэш от пароля
		$password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

		$sql = 'INSERT INTO users (dt_add, name, email, password, avatar_path) ' .
		'VALUES (NOW(), ?, ?, ?, ?)';

		$stmt = db_get_prepare_stmt($connect, $sql, [
			$sign_up['name'],
			$sign_up['email'],
			$password,
			$sign_up['avatar_path']
		]);

		$res = mysqli_stmt_execute($stmt);

		if ($res) {
			$user_id = mysqli_insert_id($connect);
			header('Location: /signin.php');
		}

	}

}
else {	
	$signup_form = include_template('signup-form.php');
}

$page_content = include_template('main.php', [
	'form' => $signup_form,
	'title' => 'Регистрация',
	'isFormPage' => $isFormPage,
]);

$layout_content = include_template('layout.php', [
	'content' => $page_content,
	'categories' => $categories,
	'title' => 'Регистрация пользователя',
	'Js' => $Js
]);

print($layout_content);
