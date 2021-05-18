<?php
require_once('../../config/config.php');
require_once('../../config/check_cookie.php');
require_once('functions.php');
// require_once('../../statistic/statistic.php');
if (isset($_GET["del"])) {
	$id = htmlspecialchars(intval($_GET['del']));
    $res=mysqli_query($connect,"DELETE FROM gifs WHERE id='".$id."'");
    if (!$res) {
    	echo json_encode(array(
    	'result'    => 'error',
    	'error'     => 'Ошибка MySQL'));
		exit();
	}
    echo json_encode(array(
                'result'    => 'success'
            ));
    exit();
}
if (isset($_POST["id"])) {
	$id = htmlspecialchars(intval($_POST['id']));
	$required = ['category', 'gif-title', 'gif-url', 'gif-description', 'author'];
	$errors = [];
	$dict = ['gif-img' => 'Гифка', 'category' => 'Категория', 'gif-title' => 'Название', 'gif-description' => 'Описание', 'gif-url' => 'ЧПУ', 'author' => 'Автор'];
	foreach($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле должно быть заполнено';
		}
	}
	$user_id = htmlspecialchars(intval($_POST['author']));
    $category_id = trim(htmlspecialchars($_POST['category']));
    $author_input = trim(htmlspecialchars($_POST['author_input']));
    $sql = "SELECT nameCat, urlCat FROM categories WHERE id = '".$category_id."'";
    $res = mysqli_query($connect, $sql);
    if ($res) {
        $categoris_array = mysqli_fetch_assoc($res);
        $category_name = $categoris_array['nameCat'];
        $category_url = $categoris_array['urlCat'];
    }
    if (!empty($_FILES['gif-img']['name'])) {
		if (empty($_FILES['gif-img']['name'])) {
			$errors['gif-img'] = 'Вы не загрузили файл';
		} else {
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
			$dest = '../../uploads/';
			if ($extension !== 'gif') {
				$errors['gif-img'] = 'Загрузите гифку в формате GIF';
			}
			if ($file_size > 200000) {
				$errors['gif-img'] = 'Максимальный размер файла: 200Кб';
			} else {
				move_uploaded_file($tmp_name, $dest.$file);
				$gif['img_path'] = $file;
				$img_path = $gif['img_path'];
			}
		}
	} else {
		$img_path = $_POST['gif-img'];
	}
	if (count($errors)) {
		$sql_cat = 'SELECT * FROM categories';
		$res_cat = mysqli_query($connect, $sql_cat);
		if ($res_cat) {
		    $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
		} else {
		    $error = mysqli_error($connect);
		    print('Ошибка MySQL: '.$error);
		}
		$sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, likes_count, favs_count, views_count, description, points, avg_points, votes, g.url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id JOIN users u ON g.user_id = u.id WHERE g.id = '.$id;
	    $res_gif = mysqli_query($connect, $sql_gif);
	    if ($res_gif) {
	        $gif = mysqli_fetch_assoc($res_gif);
	        $gif_id = $gif['id'];
	        if (!isset($gif)) {
	            header('Location: /404.php');
	            http_response_code(404);
	            $is404error = true;
	        }
	        $sql = "SELECT id FROM users WHERE name = '".$gif['name']."'";
	            $res = mysqli_query($connect, $sql);
	            if ($res) {
	                $user = mysqli_fetch_assoc($res);
	                $author = $user['id'];
	                $gif["author"] = $author;
	            }
	    } else {
	        $error = mysqli_error($connect);
	        print('Ошибка MySQL: '.$error);
	    }
		$edit_form = include_template('edit-form.php', ['gif' => $gif, 'categories' => $categories, 'errors' => $errors, 'dict' => $dict]);
		echo json_encode(array(
                'result'    => 'error',
                'edit_form'      => $edit_form
            ));
		exit();
	}
    $res=mysqli_query($connect,"UPDATE gifs SET category_id='".$_POST['category']."', user_id='".$user_id."', title='".$_POST['gif-title']."', description='".$_POST['gif-description']."', img_path='".$img_path."', url='".$_POST['gif-url']."'  WHERE id=".$id."");
    if ($res) {
	    $html = "
	              <td>{$id}</td>
	              <td>{$category_name}</td>
	              <td>{$author_input}</td>
	              <td>{$_POST['gif-title']}</td>
	              <td>{$_POST['gif-description']}</td>
	              <td>{$img_path}</td>
	              <td>{$_POST['gif-url']}</td>
	              <td class=\"center\">
	                <a target=\"_blank\" class=\"btn btn-success\" href=\"/{$category_url}/{$_POST['gif-url']}/\">
	                  <i class=\"glyphicon glyphicon-zoom-in icon-white\"></i>
	                  View
	                </a>
	                <a class=\"btn btn-info\" href=\"/admin/Item/item.php?edit={$id}\" onclick=\"AddEdit('/admin/Item/edit-ajax.php', '{$id}'); return false;\">
	                  <i class=\"glyphicon glyphicon-edit icon-white\"></i>
	                  Edit
	                </a>
	                <a class=\"btn btn-danger\" href=\"/admin/Item/item.php?del={$id}\" onclick=\"Delete({$id}); return false;\">
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

$res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
$items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_items = 9;
$offset = ($current_page - 1) * $page_items;
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);
if ($_GET['tab'] == 'new') {
	// 3. создаем запрос для получения списка свежих гифок
	$sql_gifs = 'SELECT g.id, u.name, c.nameCat, description, title, img_path, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'ORDER BY g.dt_add DESC LIMIT '.$page_items.
	' OFFSET '.$offset;
	$res_gifs = mysqli_query($connect, $sql_gifs);
	if ($res_gifs) {
		$gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
	} else {
		$error = mysqli_error($connect);
		print('Ошибка MySQL: '.$error);
	}
} elseif ($_GET['tab'] == 'rating') {
	// 2. создаем запрос для получения списка самых рейтинговых по звездам
	$sql_gifs = 'SELECT g.id, u.name, c.nameCat, description, title, img_path, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.'ORDER BY avg_points DESC LIMIT '.$page_items.' OFFSET '.$offset;
	
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
else {
	// по порядку
	$sql_gifs = 'SELECT g.id, u.name, c.nameCat, description, title, img_path, g.url, c.urlCat '.'FROM gifs g '.'JOIN categories c ON g.category_id = c.id '.'JOIN users u ON g.user_id = u.id '.' LIMIT '.$page_items.' OFFSET '.$offset;
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
$url = "/admin/Item/item.php";
$pagination = include_template('pagination.php', ['param' => $param, 'pages_count' => $pages_count, 'items_count' => $items_count, 'pages' => $pages, 'url' => $url, 'current_page' => $current_page]);
if ($_GET['mode'] == 'w_js') {
	$page_content = include_template('item.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);

	$layout_content = include_template('layout.php', ['Js' => $Js, 'title' => 'Админпанель/Items', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_items' => 'class="active"']);
	print($layout_content);
	exit();
}
if (isset($_GET['page'])) {
	$page_content = include_template('item.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);
	print($page_content);
	exit();
}
$page_content = include_template('item.php', ['gifs' => $gifs, 'pagination' => $pagination, ]);

$layout_content = include_template('layout.php', ['Js' => $Js, 'title' => 'Админпанель/Items', 'content' => $page_content, 'username' => $_SESSION['user']['name'], 'status' => '2', 'active_items' => 'class="active"']);
print($layout_content);