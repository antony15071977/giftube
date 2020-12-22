<?php
$isFormPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
// запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
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
		//Удаляем пользователей с таблицы users, которые не подтвердили свою почту в течении суток
		$sql_del_user = 'DELETE FROM `users` WHERE `email_status` = 0 AND `dt_add` < ( NOW() - INTERVAL 1 DAY )';
		$res_del = mysqli_query($connect, $sql_del_user);
		if (!$res_del) {
			$error = mysqli_error($connect);
			$info = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного аккаунта. Код ошибки: '.$error.'</p>';
		}
		//Удаляем пользователей из таблицы confirm_users, которые не подтвердили свою почту в течении сутки
		$sql_del_user = 'DELETE FROM `confirm_users` WHERE `dt_add` < ( NOW() - INTERVAL 1 DAY )';
		$res_del = mysqli_query($connect, $sql_del_user);
		if (!$res_del) {
			$error = mysqli_error($connect);
			$info = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного неподтвержденного аккаунта. Код ошибки: '.$error.'</p>';
		}
		// проверка на существование пользователя с таким же email
		$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';
		$res_pass = mysqli_query($connect, $sql);
		if ($res_pass) {
			$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
			if ($user) {
				$user_password = md5($sign_in['password'].":".$user[0]['secretkey']);
				if ($user_password == $user[0]['password']) {
					$email_status = $user[0]['email_status'];
					//Если email не подтверждён
					if ($email_status == 0) {
						// Сохраняем в сессию сообщение об ошибке.
						print("<p class='mesage_error'><p class='mesage_error'>Вы зарегистрированы, но Ваш почтовый адрес не подтверждён. Для подтверждения почты перейдите по ссылке из письма, которое получили после регистрации.</p> <p> <strong> Внимание! </strong> Ссылка для подтверждения почты, действительна 24 часа с момента регистрации. Если Вы не подтвердите Ваш email в течении этого времени, то Ваш аккаунт будет удалён.</p></p>");
						exit();
					} else {
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
								// Сохраняем сообщение об ошибке.
								print("<p class='mesage_error'>Ошибка функционала 'запомнить меня'</p>");
								exit();
							}
							/* 
							    Устанавливаем куку.
							    Параметры функции setcookie():
							    1 параметр - Название куки
							    2 параметр - Значение куки
							    3 параметр - Время жизни куки. Мы указали 30 дней
							*/
							//Устанавливаем куку с токеном
							setcookie("cookie_token", $cookie_token, time() + (1000 * 60 * 60 * 24 * 30));
						} else {
							//Если галочка "запомнить меня" не была поставлена, то мы удаляем куки
							if (isset($_COOKIE["cookie_token"])) {
								//Очищаем поле cookie_token из базы данных
								$update_cookie_token = "UPDATE users SET cookie_token='' WHERE email = '".$email."'";
								$res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
								//Удаляем куку cookie_token
								setcookie("cookie_token", "", time() - 3600);
							}
						}
						//Возвращаем пользователя на страницу, с которой пришел
						$current_url = $_POST['current_url'];
						echo "<p class='success_messages'>Успешная авторизация</p>
						<script type='text/javascript'>
                    		jQuery(document).ready(function($) {
                    			setTimeout(function() {
								var url = \"$current_url\";
                        		$(location).attr('href', url);
								}, 350);
                    			});
                    		</script>";
                    	exit();
					}
				} else {
					$errors['password'] = 'Вы ввели неверный пароль';
				}
			} else {
				$errors['email'] = 'Пользователя с таким емeйлом не найдено.';
			}
			if (count($errors)) {
				$signin_form = include_template('signin-popup.php', ['sign_in' => $sign_in, 'errors' => $errors, 'dict' => $dict]);
				print($signin_form);
				exit();
			}
		}
	} else {
		$signin_form = include_template('signin-popup.php');
		print($signin_form);
		exit();
	}
	$page_content = include_template('main.php', ['form' => $signin_form, 'title' => 'Вход для своих', 'isFormPage' => $isFormPage]);
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'signin_errors' => $errors, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'Js' => $Js, 'title' => 'Вход на сайт']);
	print($layout_content);
} else {
	header("Location: /");
}