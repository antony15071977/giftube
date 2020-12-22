<?php
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
if (isset($_POST["password"]) && !empty($_POST["password"])) {
	$required = ['password', 'confirm_password'];
	$errors = [];
	$dict = ['password' => 'Пароль', 'confirm_password' => 'Подтверждение пароля'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено.';
		}
	}
	//Проверяем, если существует переменная token в глобальном массиве POST
	if (isset($_POST['token']) && !empty($_POST['token'])) {
		$token = trim(htmlspecialchars($_POST['token']));
	} else {
		// Сохраняем в сессию сообщение об ошибке. 
		echo "<p class='mesage_error'><strong>Ошибка!</strong> Отсутствует проверочный код. Проверьте правильно ли вы скопировали ссылку.</p>";
		//Возвращаем пользователя на страницу установки нового пароля
		exit();
	}
	//Проверяем, если существует переменная email в глобальном массиве POST
	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$email = trim(htmlspecialchars($_POST['email']));
	} else {
		// Сохраняем в сессию сообщение об ошибке. 
		echo "<p class='mesage_error'><strong>Ошибка!</strong> Отсутствует адрес электронной почты. Проверьте правильно ли вы скопировали ссылку, по которой перешли для восстановления пароля</p>";
		//Возвращаем пользователя на страницу установки нового пароля
		exit();
	}
	if (!empty($_POST["password"])) {
		//Обрезаем пробелы с начала и с конца строки
		$password = trim(htmlspecialchars($_POST["password"]));
		//проверка пароля на длину
		if (strlen($password) < 6) {
			$errors['password'] = 'Пароль должен быть более 6 символов';
			$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'dict' => $dict]);
			print($info_form);
			exit();
		}
		//Проверяем, совпадают ли пароли
		if (!empty($_POST["confirm_password"])) {
			//Обрезаем пробелы с начала и с конца строки
			$confirm_password = trim($_POST["confirm_password"]);
			$confirm_password = trim(htmlspecialchars($_POST["confirm_password"]));
			//проверка повтора пароля
			if ($confirm_password !== $password || strlen($confirm_password) < 6) {
				$errors['confirm_password'] = 'Пароли должны совпадать и быть не менее 6 символов.';
				$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'password' => $password, 'confirm_password' => $confirm_password, 'dict' => $dict]);
				print($info_form);
				exit();
			}
		} else {
			// Сохраняем в сессию сообщение об ошибке. 
			echo 'Не заполнено поле повторения пароля';
			$Js = '<script type="text/javascript" src="../js/change_pass.js"></script>';
			$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'password' => $password, 'confirm_password' => $confirm_password, 'dict' => $dict]);
			print($info_form);
			exit();
		}
		$sql = 'SELECT * FROM users WHERE secretkey = "'.$token.
		'"';
		$res_pass = mysqli_query($connect, $sql);
		if ($res_pass) {
			$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
			if ($user) {
				// создаем новый секретный ключ
				$secret_key = md5(uniqid()).md5(uniqid());
				//Шифруем новый пароль
				$password = md5($_POST['password'].":".$secret_key);
				$sql_upd = 'UPDATE users SET password = "'.$password.'", secretkey = "'.$secret_key.'"  WHERE secretkey = "'.$token.'"';
				$res_upd = mysqli_query($connect, $sql_upd);
				if (!$res_upd) {
					$error = mysqli_error($connect);
					//Возвращаем пользователя на страницу установки нового пароля
					echo "<p class='mesage_error'><strong>Ошибка!</strong> Сбой при обновлении пароля. Код ошибки: '.$error.'</p>
					<script type='text/javascript'>
                    		jQuery(document).ready(function($) {
                    			setTimeout(function() {
								var url = \"/restore/reset_password.php\";
                        		$(location).attr('href', url);
								}, 350);
                    			});
                    		</script>";					
					exit();
				}
					$message = "<p class=\"success_messages\">Пароль успешно изменён!</strong><br>Используйте новый пароль, чтобы войти в свой аккаунт.</p>";
                	echo "<script type='text/javascript'>
                    		jQuery(document).ready(function($) {
                    			$.ajax({
			                        url: '/index/index.php',
			                        data: {
			                        	update: 'true'
			                        	},
			                        cache: false,
			                        success: function(dataResult){
			                          $('.content').html(dataResult);
			                          $.ajax({
				                        url: '/templates/signin-popup-update.php',
				                        data: {
				                        	message: '$message',
				                        	email: '$email'
				                        	},
				                        cache: false,
				                        success: function(dataResult){
				                        	$('.home').addClass('modal--overflow');
				                          	$('.modal__content').html(dataResult);
				                          	$('#modal1').parents('.overlay').addClass('open');
				                            setTimeout(function() {
				                              $('#modal1').addClass('open');
				                            }, 350);
				                            $(document).on('click', function(e) {
												var target = $(e.target);
												if ($(target).hasClass('overlay')) {
													$(target).find('.modal').each(function() {
														$(this).removeClass('open');
														$('.home').removeClass('modal--overflow');
													});
													setTimeout(function() {
														$(target).removeClass('open');
														$('.modal__content').html('');
													}, 350);
												}
											});
				                          }
	              						});
			                        }
              					});
                    		});
                    	</script>";
					exit();
			} else {
				echo "<p class='mesage_error'><strong>Ошибка!</strong> Пользователь не найден</p>
					<script type='text/javascript'>
                    		jQuery(document).ready(function($) {
                    			setTimeout(function() {
								var url = \"/restore/reset_password.php\";
                        		$(location).attr('href', url);
								}, 350);
                    			});
                    		</script>";					
				exit();
			}
		} else {
			echo "<p class='mesage_error'><strong>Ошибка!</strong> Пользователь не найден</p>
					<script type='text/javascript'>
                    		jQuery(document).ready(function($) {
                    			setTimeout(function() {
								var url = \"/restore/reset_password.php\";
                        		$(location).attr('href', url);
								}, 350);
                    			});
                    		</script>";					
			exit();
		}
	} else {
		// Сообщение об ошибке.
		$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'dict' => $dict]);
		print($info_form);
		exit();
	}
} else {
	echo '<p class="mesage_error"><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href=".$address_site.">Главную страницу</a>.</p>';
	exit();
}
$layout_content = include_template('layout.php', ['content' => $info_form, 'categories' => $categories, 'username' => $_SESSION['user']['name'], 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isFormPage' => $isFormPage, 'title' => 'Восстановление пароля']);
print($layout_content);