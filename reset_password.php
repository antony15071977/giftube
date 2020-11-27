<?php

$isFormPage = true;

require_once('config.php');
require_once('functions.php');
require_once('statistic/statistic.php');
$Js = '<script src="../js/auth.js"></script>';

    // запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: ' . $error);
}
   
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sign_in = $_POST;

	$required = ['email'];
	$errors = [];
	$dict = [
		'email' => 'E-mail'
	];

	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}

	$email = htmlspecialchars($sign_in['email']);

        //проверка email на корректность
	if (!empty($email)) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Email должен быть корректным';
		}
	}

	//Удаляем пользователей с таблицы users, которые не подтвердили свою почту в течении суток

	$sql_del_user = 'DELETE FROM `users` WHERE `email_status` = 0 AND `dt_add` < ( NOW() - INTERVAL 1 DAY )';
	$res_del = mysqli_query($connect, $sql_del_user);
	if(!$res_del){
		$error = mysqli_error($connect);
    	$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного аккаунта. Код ошибки: '.$error.'</p>';
	}

	//Удаляем пользователей из таблицы confirm_users, которые не подтвердили свою почту в течении сутки

	$sql_del_user = 'DELETE FROM `confirm_users` WHERE `dt_add` < ( NOW() - INTERVAL 1 DAY )';
	$res_del = mysqli_query($connect, $sql_del_user);
	if(!$res_del){
		$error = mysqli_error($connect);
    	$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Сбой при удалении просроченного неподтвержденного аккаунта. Код ошибки: '.$error.'</p>';
	}

        // проверка на существование пользователя с таким же email
	$email = htmlspecialchars($sign_in['email']);
	$sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
	$res_pass = mysqli_query($connect, $sql);
	if($res_pass) {
		$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;

		if($user) {
			$email_status = $user[0]['email_status'];
               //Если email не подтверждён
                if($email_status == 0){
 
                    // Сохраняем в сессию сообщение об ошибке. 
                    $_SESSION["error_messages"] =  "<p class='mesage_error' >
                    Вы не можете восстановить свой пароль, потому что указанный адрес электронной почты ($email) не подтверждён. Вы зарегистрированы, но Ваш почтовый адрес не подтверждён. Для подтверждения почты перейдите по ссылке из письма, которое получили после регистрации.</p>
                        <p><strong>Внимание!</strong> Ссылка для подтверждения почты, действительна 24 часа с момента регистрации. Если Вы не подтвердите Ваш email в течении этого времени, то Ваш аккаунт будет удалён.</p>";
                        header("Location: /reset_password.php?hidden_form=1");
                }
                else {
                    //место для добавления логики восстановления
                    $token= $user[0]['secretkey'];
                    //Составляем ссылку на страницу установки нового пароля.
        			$link_reset_password = $address_site."set_new_password.php?email=$email&token=$token";
 
         			//Составляем заголовок письма
         			$subject = "Восстановление пароля от сайта ".$_SERVER['HTTP_HOST'];
 
         			//Устанавливаем кодировку заголовка письма и кодируем его
         			$subject = "=?utf-8?B?".base64_encode($subject)."?=";
 
         			//Составляем тело сообщения
         			$message = 'Здравствуйте! <br/> <br/> Для восстановления пароля от сайта <a href="http://'.$_SERVER['HTTP_HOST'].'"> '.$_SERVER['HTTP_HOST'].' </a>, перейдите по этой <a href="'.$link_reset_password.'">ссылке</a>.';
          
         			//Составляем дополнительные заголовки для почтового сервиса mail.ru
         			//Переменная $email_admin объявлена в файле config.php
         			$headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
          
         			//Отправляем сообщение с ссылкой на страницу установки нового пароля и проверяем отправлена ли она успешно или нет. 
         			if(mail($email, $subject, $message, $headers)){
 
             			$_SESSION["success_messages"] = "<p class='success_message' >Ссылка на страницу установки нового пароля, была отправлена на указанный E-mail <a href='mailto:$email'<strong> ".$email." </strong></a> </p>";
 
             			//Отправляем пользователя на страницу восстановления пароля и убираем форму для ввода email
             			
             			$Js = "";
             			$signin_form = include_template('set_new_password.php', [
								'title' => 'Проверьте ваш емейл'
						]);
						$layout_content = include_template('layout.php', [
								'content' => $signin_form,
								'categories' => $categories,
								'username' => $_SESSION['user']['name'],
								'num_online' => $num_online,
								'num_visitors_hosts' => $row[0]['hosts'],
								'num_visitors_views' => $row[0]['views'],
								'hosts_stat_month' => $hosts_stat_month,
								'views_stat_month' => $views_stat_month,
								'Js' => $Js,
								'title' => 'Помощь в восстановлении пароля'
						]);

						print($layout_content);
 
         			}else{
             			$_SESSION["error_messages"] = "<p class='mesage_error' >Ошибка при отправлении письма на почту ".$email." с cсылкой на страницу установки нового пароля. </p>";
 
             			$Js = "";
             			$signin_form = include_template('set_new_password.php', [
								'title' => 'Ошибка'
						]);
						$layout_content = include_template('layout.php', [
								'content' => $signin_form,
								'categories' => $categories,
								'username' => $_SESSION['user']['name'],
								'num_online' => $num_online,
								'num_visitors_hosts' => $row[0]['hosts'],
								'num_visitors_views' => $row[0]['views'],
								'hosts_stat_month' => $hosts_stat_month,
								'views_stat_month' => $views_stat_month,
								'Js' => $Js,
								'title' => 'Помощь в восстановлении пароля'
						]);

						print($layout_content);
         			}
                }
		}
		else {
			$errors['email'] = 'Пользователя с таким емeйлом не найдено.';
		}
	
		if (count($errors)) {
			$signin_form = include_template('reset_password.php', [
				'sign_in' => $sign_in,
				'errors' => $errors,
				'dict' => $dict
			]);
		}
	}
}
else {
	$signin_form = include_template('reset_password.php');
}

$layout_content = include_template('layout.php', [
		'content' => $signin_form,
		'categories' => $categories,
		'username' => $_SESSION['user']['name'],
		'num_online' => $num_online,
		'num_visitors_hosts' => $row[0]['hosts'],
		'num_visitors_views' => $row[0]['views'],
		'hosts_stat_month' => $hosts_stat_month,
		'views_stat_month' => $views_stat_month,
		'Js' => $Js,
		'isFormPage' => $isFormPage,
		'title' => 'Помощь в восстановлении пароля'
]);


print($layout_content);
