<?php
$isFormPage = true;
require_once('../config/config.php');
require_once('../config/functions.php');
require_once('../config/check_cookie.php');
require_once('../statistic/statistic.php');
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
$Js = "<script src='../js/gif.js'></script>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$gif = $_POST;
	$required = ['category', 'gif-title', 'gif-url', 'gif-question'];
	$errors = [];
	$dict = ['category' => 'Категория', 'gif-title' => 'Title вопроса автоматически не заполнился, попробуйте добавить вопрос еще раз', 'gif-question' => 'Вопрос', 'gif-url' => 'Адрес вопроса автоматически не заполнился, попробуйте добавить вопрос еще раз'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}
	$question = stripslashes($gif['gif-question']);
	$user_id = intval($_SESSION['user']['id']);
	$user_name = $_SESSION['user']['name'];
	$url = $gif['gif-url'];
	$title = stripslashes($gif['gif-title']);
	$category = intval($gif['category']);
	$nameCat = $gif['nameCat'];
	if (count($errors)) {
		$add_form = include_template('add-form.php', ['gif' => $gif, 'upcategories' => $upcategories, 'categories' => $categories, 'errors' => $errors, 'dict' => $dict]);
		$page_content = include_template('main.php', ['form' => $add_form, 'title' => 'Добавить вопрос', 'isFormPage' => $isFormPage]);
	} else {
		//Составляем заголовок письма
        $subject = "Новый вопрос на сайте ".$_SERVER['HTTP_HOST'];
        //Устанавливаем кодировку заголовка письма и кодируем его
        $subject = "=?utf-8?B?".base64_encode($subject).
                "?=";
        //Составляем тело сообщения
        $message = 'Здравствуйте!<br/><br/>Сегодня '.date("d.m.Y", time()).' пользователем <b> '.$_SESSION['user']['name'].'  </b>был задан вопрос на сайте <b> <a href="'.$address_site.
        '">'.$_SERVER['HTTP_HOST'].'</a>.</b>
        А вот и сам ВОПРОС: <br>"'.$question.'"<br>
        Чтобы одобрить и опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/question.php?question='.$question.'&user_id='.$user_id.'&url='.$url.'&title='.$title.'&category='.$category.'&ok=true">"ОДОБРИТЬ"</a>.
        Чтобы не публиковать его ничего не делайте.
        Чтобы не публиковать его и занести пользователя в черный список, нажмите на ссылку <a href="'.$address_site.'gif/question.php?del='.$user_id.'">"ЗАНЕСТИ ЮЗЕРА В ЧЕРНЫЙ СПИСОК"</a>.
        Чтобы отредактировать и потом опубликовать его, нажмите на ссылку <a href="'.$address_site.'gif/question.php?question='.$question.'&user_id='.$user_id.'&url='.$url.'&title='.$title.'&category='.$category.'&nameCat='.$nameCat.'&edit=true">"РЕДАКТИРОВАТЬ"</a>.
        ';
        //Составляем дополнительные заголовки для почтового сервиса mail.ru
        $headers = "FROM: $email_admin\r\nReply-to: $email_admin\r\nContent-type: text/html; charset=utf-8\r\n";
        //Отправляем сообщение с ссылкой для подтверждения регистрации на указанную почту и проверяем отправлена ли она успешно или нет. 

		if (!mail($email_admin, $subject, $message, $headers)) {
			$_SESSION["error_messages"] = '<p style="color:red; font-size:22px;">Произошла непредвиденная ошибка, попробуйте еще раз</p>';
            $add_form = include_template('question-moderation.php');
		} else {
			$_SESSION["success_messages"] = '<p style="color:green; font-size:22px;">Ваш вопрос в ближайшее время появится на сайте после одобрения модератором</p>';
           $add_form = include_template('question-moderation.php');
		}
	}
} else {
	$add_form = include_template('add-form.php', ['gif' => $gif, 'upcategories' => $upcategories, 'categories' => $categories]);
}
$page_content = include_template('main.php', ['form' => $add_form, 'title' => 'Задать вопрос', 'isFormPage' => $isFormPage]);
if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', ['username' => $_SESSION['user']['name'], 'content' => $page_content, 'Js' => $Js, 'upcategories' => $upcategories, 'categories' => $categories, 'num_online' => $num_online, 'num_visitors_hosts' => $row[0]['hosts'], 'num_visitors_views' => $row[0]['views'], 'hosts_stat_month' => $hosts_stat_month, 'views_stat_month' => $views_stat_month, 'title' => 'Добавление нового вопроса']);
} else {
	header('Location: /');
}
print($layout_content);