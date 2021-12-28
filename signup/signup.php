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
$sql_subcat = 'SELECT * FROM upcategories';
$res_subcat = mysqli_query($connect, $sql_subcat);
if ($res_subcat) {
	$upcategories = mysqli_fetch_all($res_subcat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
if (isset($_SESSION['user'])) {
	header("Location: /");
}
$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
// 2. send form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sign_up = $_POST;
	$title = 'Регистрация';
	$required = ['email', 'captcha'];
	$errors = [];
	$dict = ['email' => 'E-mail', 'name' => 'Имя', 'avatar' => 'Фото', 'captcha' => 'Результат сложения'];
	$email = trim(htmlspecialchars($sign_up['email']));
	$name = (empty($_POST['name'])) ? $email : $_POST['name'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено.';
		}
	}
	$captcha = trim(strip_tags($_POST['captcha']));
	if($captcha != $_SESSION['captcha']){
		$errors['captcha'] = 'Введите правильный результат сложения';
	}
	
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
			if (strpos($file_type, 'image') !== false) {
				move_uploaded_file($tmp_name, $dest.$file);
				$sign_up['avatar_path'] = $file;
			} else {
				$errors['avatar'] = 'Файл с таким расширением невозможно загрузить';
			}
		}
	}
	
	if (!empty($name)) {
		//проверка логина на длину
		
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
	} 
	if (count($errors)) {
		$signup_form = include_template('signup-form.php', [
			'sign_up' => $sign_up, 
			'errors' => $errors, 
			'dict' => $dict
		]);
	} else {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?"; 
			$length = rand(10, 16); 
			$password_origin = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
			 
			//Составляем заголовок письма
			$subject = "Подтверждение почты на сайте ".$_SERVER['HTTP_HOST'];
			//Устанавливаем кодировку заголовка письма и кодируем его
			$subject = "=?utf-8?B?".base64_encode($subject).
			"?=";
			//Составляем тело сообщения
			$message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).
			', неким пользователем, использующим этот емейл, была произведена регистрация на сайте <a href="'.$address_site.
			'">'.$_SERVER['HTTP_HOST'].
			'</a>. 
			Ваш пароль: "'.$password_origin.'"</br>
			Изменить его можно потом в личном кабинете, либо нажав на ссылку Восстановить пароль.
			После регистрации Вы автоматически залогинены в Вашем аккаунте. Наш сайт запоминает ваши параметры: ip адрес и cookies. Если они совпадают, сессия доступа восстанавливается автоматически и пароль вводить не нужно. Если параметры отличаются от первоначальных, вас попросят ввести пароль при просмотре.
			Почему ip адрес и cookies могут отличаться от первоначальных: 1) вы активировали аккаунт на одном устройстве, а пытаетесь посмотреть на другом устройстве. 2) вы активировали аккаунт в одном браузере, а пытаетесь посмотреть в другом браузере. 3) вы активировали аккаунт, когда пользовались wi-fi сетью, и пытаетесь зайти, подключившись к новой wi-fi сети. 4) вы активировали аккаунт
			 с мобильного телефона с мобильным интернетом. У мобильных устройств ip адрес меняется достаточно часто. 5) вы включили режим инкогнито в браузере.';
			//Составляем дополнительные заголовки для почтового сервиса mail.ru
			$headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
			//Отправляем сообщение с ссылкой для подтверждения регистрации на указанную почту и проверяем отправлена ли она успешно или нет. 
			if (mail($email, $subject, $message, $headers)) {
				$_SESSION["success_messages"] = "<h4 class='success_message'><strong>Регистрация прошла успешно!!!</strong></h4><p class='success_message'> Аккаунт, зарегистрированный на почту <strong>$email</strong> будет залогинен автоматически через 3 секунды, при смене IP адреса или при удалении cookies, которые мы использовали для Вашей автоматической регистрации, Вам понадобится ввести пароль, который мы Вам отправили на адрес электронной почты, указанный Вами при регистрации.</p>";
			} else {
				$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка при отправлении письма с ссылкой подтверждения на почту".$email.". Попробуйте еще раз.</p>";
				header("Location: /signup/signup.php?hidden_form=1");
				exit();
			}
			// Завершение запроса добавления пользователя в таблицу users
			$secret_key = md5(uniqid()).md5(uniqid());
			// пароль = хэш от пароля + secretkey
			$password = md5($password_origin.":".$secret_key);
			$avatar_path = isset($sign_up['avatar_path']) ? '/'.$sign_up['avatar_path'] : '';
			$sql = 'INSERT INTO users (dt_add, name, email, password, avatar_path, secretkey, status) '.
			'VALUES (NOW(), ?, ?, ?, ?, ?, 2)';
			$stmt = db_get_prepare_stmt($connect, $sql, [
				$name,
				$email,
				$password,
				$avatar_path,
				$secret_key
			]);
			$res = mysqli_stmt_execute($stmt);
			if ($res) {
				$user_id = mysqli_insert_id($connect);
				$page_content = include_template('main.php', [
					'title' => $title, 
					'isFormPage' => $isFormPage, 
				]);
				//Отправляем пользователя на страницу регистрации и убираем форму регистрации
				header("Location: /signup/signup.php?hidden_form=1");
				$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';
				$res_pass = mysqli_query($connect, $sql);
				if ($res_pass) {
					$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
					if ($user) {
						//место для добавления данных в сессию
						// Если введенные данные совпадают с данными из базы, то сохраняем логин и пароль в массив сессий.
						$_SESSION['user'] = $user[0];
						//Создаём токен
						$cookie_token = md5($user[0]['secretkey'].":".$_SERVER["REMOTE_ADDR"]).md5($user[0]['dt_add']);
						//Добавляем созданный токен в базу данных
						$update_cookie_token = "UPDATE users SET cookie_token='".$cookie_token."' WHERE email = '".$email."'";
						$res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
						if (!$res_update_cookie_token) {
							// Сохраняем сообщение об ошибке.
							print("<p class='mesage_error'>Ошибка функционала 'login'</p>");
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
							setcookie("cookie_token", $cookie_token, time()+(1000*60*60*24*300), "/");
								 
							//Возвращаем пользователя на страницу, с которой пришел
							echo "<script type='text/javascript'>
		                    		jQuery(document).ready(function($) {
		                    			setTimeout(function() {
		                    			var url = \"/\";
                        		$(location).attr('href', url);
										}, 350);
		                    			});
		                    		</script>";
					} 
				}

			} else {
				$_SESSION["error_messages"] = "<p class='mesage_error'>Ошибка при занесении нового пользователя в БД. Попробуйте еще раз. </p>";
				$_SESSION["success_messages"] = "";
				mysqli_query($connect, "ROLLBACK");
				$title = 'Что то пошло не так...';
				$signup_form = include_template('signup-form.php');
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
	'upcategories' => $upcategories,
	'signup_errors' => $errors,
	'items_count' => $items_count,
	'username' => $_SESSION['user']['name'], 
	'num_visitors_hosts' => $row[0]['hosts'], 
	'num_visitors_views' => $row[0]['views'], 
	'hosts_stat_month' => $hosts_stat_month, 
	'views_stat_month' => $views_stat_month, 
	'title' => 'Регистрация пользователя', 
	'num_online' => $num_online, 
	'Js' => $Js
]);
print($layout_content);