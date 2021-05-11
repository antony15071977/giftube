<?php
require_once('../config/config.php');
require_once('functions.php');
require_once('../statistic/statistic.php');
if (!isset($_SESSION['user'])) {
	//  send form
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$sign_in = $_POST;
		$required = ['email', 'password'];
		$errors = [];
		$dict = ['email' => 'E-mail', 'password' => 'Пароль'];
		foreach($required as $key) {
			if (empty($_POST[$key])) {
				$errors[$key] = 'Это поле должно быть заполнено';
			}
		}
		$email = trim(htmlspecialchars($sign_in['email']));
		//проверка email на корректность
		if (!empty($email)) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = 'Email должен быть корректным';
			}
		}
		//проверка пароля на длину
		$password = trim(htmlspecialchars($sign_in['password']));
		if (strlen($password) < 6) {
			$errors['password'] = 'Пароль должен быть более 6 символов';
		}		
		// проверка на существование пользователя с таким же email
		$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';
		$res_pass = mysqli_query($connect, $sql);
		if ($res_pass) {
			$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
			if ($user) {
				$user_password = md5($sign_in['password'].":".$user[0]['secretkey']);
				if ($user_password == $user[0]['password']) {
					//место для добавления данных в сессию
						// Если введенные данные совпадают с данными из базы, то сохраняем логин и пароль в массив сессий.
						$_SESSION['user'] = $user[0];
						// Обработка галочки "запомнить меня"
						if (isset($_POST["remember"])) {
							//Создаём токен
							$cookie_token = md5($user[0]['secretkey'].":".$_SERVER["REMOTE_ADDR"]).md5($user[0]['dt_add']);
							//Добавляем созданный токен в базу данных
							$update_cookie_token = "UPDATE users SET cookie_token='".$cookie_token."' WHERE email = '".$email."'";
							$res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
							if (!$res_update_cookie_token) {
								// Сохраняем в сессию сообщение об ошибке. 
								$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка функционала 'запомнить меня'</p>";
								//Возвращаем пользователя на страницу регистрации
								header("Location: /admin/signin.php");
							}
							/* 
							    Устанавливаем куку.
							    Параметры функции setcookie():
							    1 параметр - Название куки
							    2 параметр - Значение куки
							    3 параметр - Время жизни куки. Мы указали 30 дней
							*/
							//Устанавливаем куку с токеном
							setcookie("cookie_token", $cookie_token, time()+(1000* 60*60*24*30), "/");
						} else {
							//Если галочка "запомнить меня" не была поставлена, то мы удаляем куки
							if (isset($_COOKIE["cookie_token"])) {
								//Очищаем поле cookie_token из базы данных
								$update_cookie_token = "UPDATE users SET cookie_token='' WHERE email = '".$email."'";
								$res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
								//Удаляем куку cookie_token
								setcookie("cookie_token", "", time()-3600, "/");
							}
						}
						//Возвращаем пользователя на главную страницу
						header("Location: /admin/index.php");
					
				} else {
					$errors['password'] = 'Вы ввели неверный пароль';
				}
			} else {
				$errors['email'] = 'Пользователя с таким емeйлом не найдено.';
				$errors['password'] = 'К неизвестному емейлу пароль невозможно применить.';
			}
			if (count($errors)) {
				$layout_content = include_template('login.php', ['sign_in' => $sign_in, 'errors' => $errors, 'dict' => $dict]);
			}
		}
	} else {
		$layout_content = include_template('login.php', []);
	}
	print($layout_content);
} else {
	header("Location: /admin/index.php");
}