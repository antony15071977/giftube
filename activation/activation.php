<?php
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../statistic/statistic.php');
//запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if ($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: '.$error);
}
if (empty($_GET)) {
	$info = '<p><strong>Ошибка!</strong><br>Сюда нельзя зайти прямо так)</p>';
	$info_form = include_template('activation.php', ['info' => $info, 'title' => 'Ошибка']);
	$layout_content = include_template('layout.php', ['content' => $info_form, 'categories' => $categories, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Активация пользователя']);
	print($layout_content);
	exit();
}
if (isset($_GET['token']) && !empty($_GET['token'])) {
	$token = htmlspecialchars($_GET['token'], ENT_QUOTES);
} else {
	$info = '<p><strong>Ошибка!</strong><br>Отсутствует проверочный код.</p>';
}
if (isset($_GET['email']) && !empty($_GET['email'])) {
	$email = htmlspecialchars($_GET['email'], ENT_QUOTES);
} else {
	$info = '<p><strong>Ошибка!</strong><br>Отсутствует адрес электронной почты.</p>';
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
//Делаем запрос на выборке токена из таблицы confirm_users
$sql_token = "SELECT * FROM `confirm_users` WHERE `email` = '".$email."'";
$res_token = mysqli_query($connect, $sql_token);
if ($res_token) {
	$token_row = $res_token ? mysqli_fetch_all($res_token, MYSQLI_ASSOC) : null;
	//Если такой пользователь существует
	if (!empty($token_row)) {
		$token_in = $token_row[0]['token'];
		//Проверяем совпадает ли token
		if ($token == $token_in) {
			mysqli_query($connect,  "START TRANSACTION");
			//Обновляем статус почтового адреса
			$sql_update_user = "UPDATE `users` SET `email_status` = 1 WHERE `email` = '".$email.
			"'";
			$res_update_user = mysqli_query($connect, $sql_update_user);
			if (!$res_update_user) {
				$error = mysqli_error($connect);
				$info = '<p><strong>Ошибка!</strong>Сбой при обновлении статуса пользователя. Код ошибки: '.$error.'</p>';
			} else {
				//Удаляем данные пользователя из временной таблицы confirm_users
				$sql_del_conf_user = "DELETE FROM `confirm_users` WHERE `email` = '".$email."'";
				$res_sql_del_conf_user = mysqli_query($connect, $sql_del_conf_user);
				if (!$res_sql_del_conf_user) {
					mysqli_query($connect, "ROLLBACK");
					$error = mysqli_error($connect);
					$info = '<p><strong>Ошибка!</strong>Сбой при удалении данных пользователя из временной таблицы. Код ошибки: '.$error.'</p>';
				} else {
					mysqli_query($connect, "COMMIT");
					$_SESSION["success_messages"] = '<h1 class="success_message text_center">Почта успешно подтверждена!</h1><br><p class = "text_center"> Теперь Вы можете войти в свой аккаунт.</p>';
					header("Location: /signin/signin.php?email=$email");
					exit();
				}
			}
		} else {
			$info = '<p><strong>Ошибка!</strong>Неправильный проверочный код.</p>';
		}
	} else {
		$info = '<p><strong>Ошибка!</strong>Такой пользователь не зарегистрирован либо уже активирован.</p>';
	}
} else {
	$error = mysqli_error($connect);
	$info = '<p><strong>Ошибка!</strong> Сбой при выборе пользователя из БД. Код ошибки: '.$error.'</p>';
}
$info_form = include_template('activation.php', ['info' => $info, 'title' => 'Активация']);
$layout_content = include_template('layout.php', ['content' => $info_form, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Активация пользователя']);
print($layout_content);