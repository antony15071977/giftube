<?php
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
$sql_subcat = 'SELECT * FROM upcategories';
$res_subcat = mysqli_query($connect, $sql_subcat);
if ($res_subcat) {
	$upcategories = mysqli_fetch_all($res_subcat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
if (isset($_POST["set_new_password"]) && !empty($_POST["set_new_password"])) {
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
		$_SESSION["error_messages"] = "<p class='mesage_error'><strong>Ошибка!</strong> Отсутствует проверочный код. Проверьте правильно ли вы скопировали ссылку.</p>";
		//Возвращаем пользователя на страницу установки нового пароля
		header("Location: /restore/reset_password.php?hidden_form=1");
	}
	//Проверяем, если существует переменная email в глобальном массиве POST
	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$email = trim(htmlspecialchars($_POST['email']));
	} else {
		// Сохраняем в сессию сообщение об ошибке. 
		$_SESSION["error_messages"] = "<p class='mesage_error'><strong>Ошибка!</strong> Отсутствует адрес электронной почты. Проверьте правильно ли вы скопировали ссылку, по которой перешли для восстановления пароля</p>";
		//Возвращаем пользователя на страницу установки нового пароля
		header("Location: /restore/reset_password.php?hidden_form=1");
	}
	if (!empty($_POST["password"])) {
		//Обрезаем пробелы с начала и с конца строки
		$password = trim(htmlspecialchars($_POST["password"]));
		//проверка пароля на длину
		if (strlen($password) < 6) {
			$errors['password'] = 'Пароль должен быть более 6 символов';
			$Js = '<script type="text/javascript" src="../js/change_pass.js"></script>';
			$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'dict' => $dict]);
		}
		//Проверяем, совпадают ли пароли
		if (!empty($_POST["confirm_password"])) {
			//Обрезаем пробелы с начала и с конца строки
			$confirm_password = trim(htmlspecialchars($_POST["confirm_password"]));
			$confirm_password = htmlspecialchars($confirm_password, ENT_QUOTES);
			//проверка повтора пароля
			if ($confirm_password !== $password || strlen($confirm_password) < 6) {
				$errors['confirm_password'] = 'Пароли должны совпадать и быть не менее 6 символов.';
				$Js = '<script type="text/javascript" src="../js/change_pass.js"></script>';
				$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'password' => $password, 'confirm_password' => $confirm_password, 'dict' => $dict]);
				$layout_content = include_template('layout.php', ['content' => $info_form, 'categories' => $categories, 'upcategories' => $upcategories,  'username' => $_SESSION['user']['name'], 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'Js' => $Js, 'isFormPage' => $isFormPage, 'title' => 'Восстановление пароля']);
				print($layout_content);
				exit();
			}
		} else {
			// Сохраняем в сессию сообщение об ошибке. 
			$errors['confirm_password'] = 'Не заполнено поле повторения пароля';
			$Js = '<script type="text/javascript" src="../js/change_pass.js"></script>';
			$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'password' => $password, 'confirm_password' => $confirm_password, 'dict' => $dict]);
			$layout_content = include_template('layout.php', ['content' => $info_form, 'categories' => $categories, 'upcategories' => $upcategories,  'username' => $_SESSION['user']['name'], 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'Js' => $Js, 'isFormPage' => $isFormPage, 'title' => 'Восстановление пароля']);
			print($layout_content);
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
					$_SESSION["error_messages"] == '<p><strong>Ошибка!</strong> Сбой при обновлении пароля. Код ошибки: '.$error.'</p>';
					//Возвращаем пользователя на страницу установки нового пароля
					header("Location: /restore/reset_password.php?hidden_form=1");
				}
				$_SESSION["success_messages"] = '<p><strong>Пароль успешно изменён!</strong><br>Теперь Вы можете войти в свой аккаунт.</p>';
				header("Location: /signin/signin.php?email=$email");
			} else {
				$info_form = include_template('set_new_password.php', ['title' => 'Ошибка: пользователь не найден']);
			}
		} else {
			$info_form = include_template('set_new_password.php', ['title' => 'Ошибка: пользователь не найден']);
		}
	} else {
		// Сообщение об ошибке.
		$Js = '<script type="text/javascript" src="../js/change_pass.js"></script>';
		$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $token, 'email' => $email, 'errors' => $errors, 'dict' => $dict]);
	}
} else {
	$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href=".$address_site.">Главную страницу</a>.</p>';
	$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
}
$layout_content = include_template('layout.php', ['content' => $info_form, 'upcategories' => $upcategories, 'categories' => $categories, 'username' => $_SESSION['user']['name'], 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'Js' => $Js, 'isFormPage' => $isFormPage, 'title' => 'Восстановление пароля']);
print($layout_content);