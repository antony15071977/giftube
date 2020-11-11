<?php

session_start();

$isFormPage = true;

require_once('config.php');
require_once('functions.php');

    // 1. запрос для получения списка категорий;
$sql_cat = 'SELECT * FROM categories';
$res_cat = mysqli_query($connect, $sql_cat);
if($res_cat) {
	$categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
} else {
	$error = mysqli_error($connect);
	print('Ошибка MySQL: ' . $error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$gif = $_POST;
	$required = ['category', 'gif-title', 'gif-description'];
	$errors = [];
	$dict = [
		'gif-img' => 'Гифка',
		'category' => 'Категория',
		'gif-title' => 'Название',
		'gif-description' => 'Описание'
	];

	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}

	if (isset($_FILES['gif-img']['name'])) {
		if (empty($_FILES['gif-img']['name'])) {
			$errors['gif-img'] = 'Вы не загрузили файл';
		}
		else {
			$tmp_name = $_FILES['gif-img']['tmp_name'];
			$file = $_FILES['gif-img']['name'];

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($finfo, $tmp_name);

                // Получаем расширение загруженного файла
			$extension = strtolower(substr(strrchr($file, '.'), 1));
                //Генерируем новое имя файла
			$file = uniqid() . '.' .  $extension;
                //Папка назначения
			$dest = 'uploads/';

			if ($extension !== 'gif') {
				$errors['gif-img'] = 'Загрузите гифку в формате GIF';
			}
			if ($file_size > 200000) {
				$errors['gif-img'] = 'Максимальный размер файла: 200Кб';
			}
			else {
				move_uploaded_file($tmp_name, $dest . $file);
				$gif['img_path'] = $dest . $file;
			}
		}
	}

	$user_id = $_SESSION['user']['id'];

	if (count($errors)) {
		$add_form = include_template('add-form.php', [
			'gif' => $gif,
			'categories' => $categories,
			'errors' => $errors,
			'dict' => $dict
		]);
	}
	else {


		$sql = 'INSERT INTO gifs (dt_add, category_id, user_id, title, description, ' .
		'img_path, likes_count, favs_count, views_count) ' .
		'VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';

		$stmt = db_get_prepare_stmt($connect, $sql, [
			$gif['category'],
			$user_id,
			$gif['gif-title'],
			$gif['gif-description'],
			$gif['img_path'],
			0,
			0,
			1
		]);

		$res = mysqli_stmt_execute($stmt);

		if ($res) {
			$gif_id = mysqli_insert_id($connect);
			header('Location: /gif.php?id=' . $gif_id);
		}
	}

}
else {
	$add_form = include_template('add-form.php', [
		'gif' => $gif,
		'categories' => $categories
	]);
}




$page_content = include_template('main.php', [
	'form' => $add_form,
	'title' => 'Добавить гифку',
	'isFormPage' => $isFormPage
]);

if (isset($_SESSION['user'])) {
	$layout_content = include_template('layout.php', [
		'username' => $_SESSION['user']['name'],
		'content' => $page_content,
		'categories' => $categories,
		'title' => 'Добавление новой гифки'
	]);
}
else {
	http_response_code(403);
}

print($layout_content);
