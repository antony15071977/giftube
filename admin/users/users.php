<?php
require_once('../../config/config.php');
require_once('../../config/check_cookie.php');
require_once('../status.php');
require_once('functions.php');
// require_once('../../statistic/statistic.php');
if (isset($_GET["del"])) {
	$id = htmlspecialchars(intval($_GET['del']));
	$sql_gif = 'SELECT status FROM users WHERE id = '.$id;
	    $res_gif = mysqli_query($connect, $sql_gif);
	    if ($res_gif) {
	        $gif = mysqli_fetch_assoc($res_gif);
	        $real_status = $gif['status'];
	    } else {
	        $error = mysqli_error($connect);
	        print('Ошибка MySQL: '.$error);
	    }
    if ($real_status == 3 ) {
    	echo json_encode(array(
                'result'    => 'error',
                'error'      => 'У вас нет прав редактировать админов'
            ));
		exit();
    }
    $res=mysqli_query($connect,"DELETE FROM users WHERE id='".$id."'");
    echo json_encode(array(
                'result'    => 'success'
            ));
    exit();
}
if (isset($_POST["id"])) {
	$id = htmlspecialchars(intval($_POST['id']));
	$required = ['author', 'email', 'status'];
	$errors = [];
	$dict = ['status' => 'Статус', 'gif-img' => 'Аватар', 'email' => 'Емейл', 'author' => 'Автор'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}
	$user_id = htmlspecialchars(intval($_POST['author']));
    $name = trim(htmlspecialchars($_POST['author_input']));
    $email = trim(htmlspecialchars($_POST['email']));
    $status = intval(htmlspecialchars($_POST['status']));
    $sql_gif = 'SELECT dt_add, status FROM users WHERE id = '.$id;
	    $res_gif = mysqli_query($connect, $sql_gif);
	    if ($res_gif) {
	        $gif = mysqli_fetch_assoc($res_gif);
	        $real_status = $gif['status'];
	    } else {
	        $error = mysqli_error($connect);
	        print('Ошибка MySQL: '.$error);
	    }
    if ($real_status == 3 ) {
    	$errors['status'] = 'У вас нет прав редактировать админов';
    }
    if (!empty($_FILES['gif-img']['name'])) {
			$tmp_name = $_FILES['gif-img']['tmp_name'];
			$file = $_FILES['gif-img']['name'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($finfo, $tmp_name);
			// Получаем расширение загруженного файла
			$extension = strtolower(substr(strrchr($file, '.'), 1));
			//Генерируем новое имя файла
			$file = uniqid().
			'.'.$extension;
			//Папка назначения
			$dest = '../../uploads/avatar/';
			if (strpos($file_type, 'image') === false) {
				$errors['gif-img'] = 'Можно загружать только изображения';
			}
			if ($file_size > 200000) {
				$errors['gif-img'] = 'Максимальный размер файла: 200Кб';
			} else {
				move_uploaded_file($tmp_name, $dest.$file);
				$gif['img_path'] = $file;
				$img_path = $gif['img_path'];
			}
		
	} else {
		$img_path = $_POST['gif-img'];
	}
	if (count($errors)) {
		$sql_gif = 'SELECT id, dt_add, name, email, avatar_path, status FROM users WHERE id = '.$id;
	    $res_gif = mysqli_query($connect, $sql_gif);
	    if ($res_gif) {
	        $gif = mysqli_fetch_assoc($res_gif);
	        $author = $gif['id'];
	        $gif["author"] = $author;
	    } else {
	        $error = mysqli_error($connect);
	        print('Ошибка MySQL: '.$error);
	    }
		$edit_form = include_template('edit-user-form.php', ['gif' => $gif, 'errors' => $errors, 'id' => $id, 'dict' => $dict]);
		echo json_encode(array(
                'result'    => 'error',
                'edit_form'      => $edit_form
            ));
		exit();
	}
    $res=mysqli_query($connect,"UPDATE users SET name='".$name."', avatar_path='".$img_path."', email='".$email."', status='".$status."' WHERE id=".$id."");
    if ($res) {
    	if ($status == 2) {
    		$status = '<span class="label-success label label-default">Зареган</span>';
    	} else {
    		$status = '<span class="label-default label label-danger">Забанен</span>';
    	}
	    $html = "
	              <td>{$id}</td>
	              <td>{$gif['dt_add']}</td>
	              <td>{$name}</td>
	              <td>{$email}</td>
	              <td>{$img_path}</td>
	              <td>{$status}</td>
	              <td class=\"center\">
	                <a target=\"_blank\" class=\"btn btn-success\" href=\"#\">
	                  <i class=\"glyphicon glyphicon-zoom-in icon-white\"></i>
	                  View
	                </a>
	                <a class=\"btn btn-info\" href=\"/admin/users/users.php?edit={$id}\" onclick=\"AddEdit('/admin/users/edit-ajax.php', '{$id}'); return false;\">
	                  <i class=\"glyphicon glyphicon-edit icon-white\"></i>
	                  Edit
	                </a>
	                <a class=\"btn btn-danger\" href=\"/admin/users/users.php?del={$id}\" data-url=\"/admin/users/users.php?del={$id}\" onclick=\"Delete({$id}); return false;\">
	                  <i class=\"glyphicon glyphicon-trash icon-white\"></i>
	                  Delete
	                </a>
	              </td>
	    ";
	    echo json_encode(array(
	            'result'    => 'success',
	            'html'      => $html
	        ));
	    exit();
	} else {
		echo json_encode(array(
	            'result'    => 'error'
	        ));
	    exit();
	}
}

$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM users');
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_items = 9;
$offset = ($current_page - 1) * $page_items;
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);
if ($_GET['tab'] == 'new') {
	// 3. создаем запрос для получения списка свежих гифок
	$sql_gifs = 'SELECT id, dt_add, name, email, avatar_path, status FROM users '.' ORDER BY dt_add DESC LIMIT '.$page_items.
	' OFFSET '.$offset;
	$res_gifs = mysqli_query($connect, $sql_gifs);
	if ($res_gifs) {
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
} 
else {
	// по порядку
	$sql_gifs = 'SELECT id, dt_add, name, email, avatar_path, status FROM users '.' LIMIT '.$page_items.' OFFSET '.$offset;
	//отправляем запрос и получаем результат
	$res_gifs = mysqli_query($connect, $sql_gifs);
	//запрос выполнен успешно
	if ($res_gifs) {
		//получаем гифки в виде двумерного массива
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		//получаем текст последней ошибки
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
}
$Js = "<link rel='stylesheet' href='/admin/css/pagination.css'>

<script src=\"/admin/bower_components/chosen/chosen.jquery.min.js\"></script>
";	
$param = '';
$param = isset($_GET['tab']) ? ('&tab='.$_GET['tab'].'&') : '';
$url = "/admin/users/users.php";
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
if ($_GET['mode'] == 'w_js') {
	$page_content = include_template('users.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);

	$layout_content = include_template('layout.php', ['Js' => $Js, 'title' => 'Админпанель/Users', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_users' => 'class="active"']);
	print($layout_content);
	exit();
}
if (isset($_GET['page'])) {
	$page_content = include_template('users.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);
	print($page_content);
	exit();
}
$page_content = include_template('users.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);

$layout_content = include_template('layout.php', ['Js' => $Js, 'title' => 'Админпанель/Users', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_users' => 'class="active"']);
print($layout_content);