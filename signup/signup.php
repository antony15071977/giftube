<?php
$isFormPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
$Js = '<script src="../js/register.js"></script>';
// 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
// 2. send form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sign_up = $_POST;
	$title = 'Регистрация';
	$required = ['email', 'password', 'name', 'confirm_password', 'captcha'];
	$errors = [];
	$dict = ['email' => 'E-mail', 'password' => 'Пароль', 'name' => 'Имя', 'confirm_password' => 'Подтверждение пароля', 'avatar' => 'Фото', 'captcha' => 'Результат сложения'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено.';
		}
	}
	if($_POST['captcha'] != $_SESSION['captcha']){
		$errors['captcha'] = 'Введите правильный результат сложения';
	}
	$email = trim(htmlspecialchars($sign_up['email']));
	//проверка email на корректность
	if (!empty($email)) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Email должен быть корректным';
		}
	}
	// проверка на существование пользователя с таким же email
	if (!empty($email)) {
		$sql = 'SELECT id FROM users WHERE email = "'.$email.'"';
		$res_email = mysqli_query($connect, $sql);
		if ($res_email) {
			$emails = mysqli_fetch_all($res_email, MYSQLI_ASSOC);
			if (!empty($emails)) {
				$errors['email'] = 'Введённый вами email <strong>'.$email.'</strong> уже зарегистрирован. Введите другой email.';
			}
		}
	}
	// load avatar
	if (isset($_FILES['avatar']['name'])) {
		if (!empty($_FILES['avatar']['name'])) {
			$tmp_name = $_FILES['avatar']['tmp_name'];
			$file = $_FILES['avatar']['name'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($finfo, $tmp_name);
			// Получаем расширение загруженного файла
			$extension = strtolower(substr(strrchr($file, '.'), 1));
			//Генерируем новое имя файла
			$file = uniqid().
			'.'.$extension;
			//Папка назначения
			$dest = '../uploads/avatar/';
			if (($file_type == "image/gif") || ($file_type == "image/jpeg") || ($file_type == "image/png") || ($file_type == "image/pjepg")) {
				move_uploaded_file($tmp_name, $dest.$file);
				$sign_up['avatar_path'] = $dest.$file;
			} else {
				$errors['avatar'] = 'Файл с таким расширением невозможно загрузить';
			}
		}
	}
	//проверка пароля на длину
	$password = trim(htmlspecialchars($sign_up['password']));
	if (strlen($password) < 6) {
		$errors['password'] = 'Пароль должен быть более 6 символов';
	}
	//проверка повтора пароля
	$confirm_password = trim(htmlspecialchars($sign_up['confirm_password']));
	if ($confirm_password !== $password) {
		$errors['confirm_password'] = 'Пароли должны совпадать.';
	}
	//проверка логина на длину
	$name = trim(htmlspecialchars($sign_up['name']));
	if (strlen($name) < 5) {
		$errors['name'] = 'Логин должен быть не менее 5 символов.';
	}
	// проверка на существование пользователя с таким же login
	if (!empty($name)) {
		$sql = 'SELECT id FROM users WHERE name = "'.$name.'"';
		$res_login = mysqli_query($connect, $sql);
		if ($res_login) {
			$logins = mysqli_fetch_all($res_login, MYSQLI_ASSOC);
			if (!empty($logins)) {
				$errors['name'] = 'Введённый вами логин <strong>'.$name.
				'</strong> уже зарегистрирован. Придумайте другой логин.';
			}
		}
	}
	if (count($errors)) {
		$signup_form = include_template('signup-form.php', [
			'sign_up' => $sign_up, 
			'errors' => $errors, 
			'dict' => $dict
		]);
	} else {
		//Составляем зашифрованный и уникальный token
		$token = md5($email.time());
		mysqli_query($connect,  "START TRANSACTION");
		//Добавляем данные в таблицу confirm_users
		$sql_nonconf_user = 'INSERT INTO confirm_users (dt_add, email, token) '.
		'VALUES (NOW(), ?, ?)';
		$stmt = db_get_prepare_stmt($connect, $sql_nonconf_user, [
			$email,
			$token
		]);
		$res_sql_nonconf_user = mysqli_stmt_execute($stmt);
		if (!$res_sql_nonconf_user) {
			// Сохраняем в сессию сообщение об ошибке. 
			$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка запроса на добавление пользователя в БД (confirm), попробуйте еще раз.</p>";
			$title = 'Что то пошло не так...';
			$signup_form = include_template('signup-form.php');
		} else {
			//Составляем заголовок письма
			$subject = "Подтверждение почты на сайте ".$_SERVER['HTTP_HOST'];
			//Устанавливаем кодировку заголовка письма и кодируем его
			$subject = "=?utf-8?B?".base64_encode($subject).
			"?=";
			//Составляем тело сообщения
			$message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).
			', неким пользователем, использующим этот емейл, была произведена регистрация на сайте <a href="'.$address_site.
			'">'.$_SERVER['HTTP_HOST'].
			'</a>. Если это были Вы, то, пожалуйста, подтвердите адрес вашей электронной почты, перейдя по этой ссылке: <a href="'.$address_site.'activation/activation.php?token='.$token.'&email='.$email.'">'.$address_site.'activation/'.$token.'</a><br/><br/> В противном случае, если это были не Вы, то, просто игнорируйте это письмо.<br/><br/><strong>Внимание!</strong> Ссылка действительна 24 часа. После чего Ваш аккаунт будет удален из базы.';
			//Составляем дополнительные заголовки для почтового сервиса mail.ru
			$headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
			//Отправляем сообщение с ссылкой для подтверждения регистрации на указанную почту и проверяем отправлена ли она успешно или нет. 
			if (mail($email, $subject, $message, $headers)) {
				$_SESSION["success_messages"] = "<h4 class='success_message'><strong>Регистрация прошла успешно!!!</strong></h4><p class='success_message'> Теперь необходимо подтвердить введенный адрес электронной почты. Для этого перейдите по ссылке, указанной в сообщении, которое мы вам отправили на почту <strong>$email</strong></p>";
			} else {
				$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка при отправлении письма с ссылкой подтверждения на почту".$email.". Попробуйте еще раз.</p>";
				mysqli_query($connect, "ROLLBACK");
				header("Location: /signup/signup.php?hidden_form=1");
				exit();
			}
			//Удаляем пользователей с таблицы users, которые не подтвердили свою почту в течении суток
			$sql_del_user = 'DELETE FROM `users` WHERE `email_status` = 0 AND `dt_add` < ( NOW() - INTERVAL 1 DAY )';
			$res_del = mysqli_query($connect, $sql_del_user);
			if (!$res_del) {
				$error = mysqli_error($connect);
				$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного аккаунта. Код ошибки: '.$error.'</p>';
			}
			//Удаляем пользователей из таблицы confirm_users, которые не подтвердили свою почту в течении сутки
			$sql_del_user = 'DELETE FROM `confirm_users` WHERE `dt_add` < ( NOW() - INTERVAL 1 DAY )';
			$res_del = mysqli_query($connect, $sql_del_user);
			if (!$res_del) {
				$error = mysqli_error($connect);
				$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного неподтвержденного аккаунта. Код ошибки: '.$error.'</p>';
			}
			// Завершение запроса добавления пользователя в таблицу users
			$secret_key = md5(uniqid()).md5(uniqid());
			// пароль = хэш от пароля + secretkey
			$password = md5($sign_up['password'].":".$secret_key);
			$sql = 'INSERT INTO users (dt_add, name, email, password, avatar_path, secretkey) '.
			'VALUES (NOW(), ?, ?, ?, ?, ?)';
			$stmt = db_get_prepare_stmt($connect, $sql, [
				$sign_up['name'],
				$sign_up['email'],
				$password,
				$sign_up['avatar_path'],
				$secret_key
			]);
			$res = mysqli_stmt_execute($stmt);
			if ($res&&$res_sql_nonconf_user) {
				mysqli_query($connect, "COMMIT");
				$user_id = mysqli_insert_id($connect);
				$page_content = include_template('main.php', [
					'title' => $title, 
					'isFormPage' => $isFormPage, 
				]);
				//Отправляем пользователя на страницу регистрации и убираем форму регистрации
				header("Location: /signup/signup.php?hidden_form=1");
			} else {
				$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка при занесении нового пользователя в БД. Попробуйте еще раз. </p>";
				$_SESSION["success_messages"] = "";
				mysqli_query($connect, "ROLLBACK");
				$title = 'Что то пошло не так...';
				$signup_form = include_template('signup-form.php');
			}
		}
	}
} else {
	$title = 'Регистрация';
	if(isset($_SESSION["success_messages"]) && !empty($_SESSION["success_messages"])) {
		$title = 'Поздравляем!';
		$Js = '';
	}
	if (isset($_SESSION["error_messages"]) && !empty($_SESSION["error_messages"])) {
		$title = 'Что то пошло не так...';
		$Js = '';
	}
	$signup_form = include_template('signup-form.php');
}
$page_content = include_template('main.php', [
	'form' => $signup_form, 
	'title' => $title, 
	'isFormPage' => $isFormPage, 
]);
$layout_content = include_template('layout.php', [
	'content' => $page_content, 
	'categories' => $categories,
	'signup_errors' => $errors, 
	'num_visitors_hosts' => $row[0]['hosts'], 
	'num_visitors_views' => $row[0]['views'], 
	'hosts_stat_month' => $hosts_stat_month, 
	'views_stat_month' => $views_stat_month, 
	'title' => 'Регистрация пользователя', 
	'num_online' => $num_online, 
	'Js' => $Js
]);
print($layout_content);