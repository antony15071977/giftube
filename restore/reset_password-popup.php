<?php
$isFormPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
// запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sign_in = $_POST;
	$required = ['email', 'captcha'];
	$errors = [];
	$dict = ['email' => 'E-mail', 'captcha' => 'Результат сложения'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}
	if($_POST['captcha'] != $_SESSION['captcha']){
		$errors['captcha'] = 'Введите правильный результат сложения';
		$signin_form = include_template('reset_password-popup.php', ['sign_in' => $sign_in, 'errors' => $errors, 'dict' => $dict]);
			print($signin_form);
			exit();
	}
	$email = trim(htmlspecialchars($sign_in['email']));
	//проверка email на корректность
	if (!empty($email)) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Email должен быть корректным';
		}
	}
	// проверка на существование пользователя с таким же email
	$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';
	$res_pass = mysqli_query($connect, $sql);
	if ($res_pass) {
		$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
		if ($user) {
			//место для добавления логики восстановления
				$token = $user[0]['secretkey'];
				//Составляем ссылку на страницу установки нового пароля.
				$link_reset_password = $address_site.
				"restore/set_new_password.php?email=$email&token=$token";
				//Составляем заголовок письма
				$subject = "Восстановление пароля от сайта ".$_SERVER['HTTP_HOST'];
				//Устанавливаем кодировку заголовка письма и кодируем его
				$subject = "=?utf-8?B?".base64_encode($subject).
				"?=";
				//Составляем тело сообщения
				$message = 'Здравствуйте!<br/><br/>Для восстановления пароля от сайта <a href="http://'.$_SERVER['HTTP_HOST'].
				'"> '.$_SERVER['HTTP_HOST'].
				' </a>, перейдите по этой <a href="'.$link_reset_password.
				'">ссылке</a>.';
				//Составляем дополнительные заголовки для почтового сервиса mail.ru
				//Переменная $email_admin объявлена в файле config.php
				$headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
				//Отправляем сообщение с ссылкой на страницу установки нового пароля и проверяем отправлена ли она успешно или нет. 
				if (mail($email, $subject, $message, $headers)) {
					echo "<p class='success_message'>Ссылка на страницу установки нового пароля была отправлена на указанный email <strong>$email</strong></p>";
					exit();
				} else {
					echo "<p class='mesage_error'>Ошибка при отправлении письма на почту ".$email." с cсылкой на страницу установки нового пароля.</p>";
					exit();
				}
		} else {
			$errors['email'] = 'Пользователя с таким емeйлом не найдено.';
		}
		if (count($errors)) {
			$signin_form = include_template('reset_password-popup.php', ['sign_in' => $sign_in, 'errors' => $errors, 'dict' => $dict]);
			print($signin_form);
			exit();
		}
	}
} else {
	$signin_form = include_template('reset_password-popup.php');
	print($signin_form);
	exit();
}