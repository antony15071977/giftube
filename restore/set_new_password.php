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
$sql_upcat = 'SELECT * FROM upcategories';
$res_upcat = mysqli_query($connect, $sql_upcat);
if ($res_upcat) {
    $upcategories = mysqli_fetch_all($res_upcat, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($connect);
    print('Ошибка MySQL: '.$error);
}
if (isset($_GET['token']) && !empty($_GET['token'])) {
	$token = trim(htmlspecialchars($_GET['token']));
} else {
	$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong><br>Отсутствуют проверочные данные. Проверьте правильно ли вы скопировали ссылку.</p>';
	$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
}
if (isset($_GET['email']) && !empty($_GET['email'])) {
	$email = trim(htmlspecialchars($_GET['email']));
} else {
	$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong><br>Отсутствуют проверочные данные. Проверьте правильно ли вы скопировали ссылку.</p>';
	$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
}
if (isset($_GET['token']) && isset($_GET['email'])) {
	$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';
	$res_pass = mysqli_query($connect, $sql);
	if ($res_pass) {
		$user = $res_pass ? mysqli_fetch_all($res_pass, MYSQLI_ASSOC) : null;
		if ($user) {
			$send_token = $token;
			$token_origin = $user[0]['secretkey'];
			//Проверяем совпадает ли token
			if ($token_origin == $send_token) {
				//Место для вывода формы установки нового пароля
				$info_form = include_template('form_new_password.php', ['title' => 'Восстановление пароля', 'send_token' => $send_token, 'email' => $email]);
			} else {
				$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong> Неправильный проверочный код.</p>';
				$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
			}
		} else {
			$_SESSION["error_messages"] = 'Пользователя с таким емeйлом не найдено.';
			$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
		}
	} else {
		$error = mysqli_error($connect);
		$_SESSION["error_messages"] = '<p><strong>Ошибка!</strong>Сбой при выборе пользователя из БД. Код ошибки: '.$error.
		'</p>';
		$info_form = include_template('set_new_password.php', ['title' => 'Ошибка']);
	}
}
$layout_content = include_template('layout.php', ['content' => $info_form, 'upcategories' => $upcategories, 'categories' => $categories, 'username' => $_SESSION['user']['name'], 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'isFormPage' => $isFormPage, 'title' => 'Восстановление пароля']);
print($layout_content);