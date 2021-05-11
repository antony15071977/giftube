<?php
require_once('../../config/config.php');
require_once('../../config/check_cookie.php');
require_once('../functions.php');
if( isset( $_POST['select']) ){ 
	// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно
	
	$files      = $_FILES; // полученные файлы
	foreach( $files as $file ){
		$file_name = $file['name'];
	// Получаем расширение загруженного файла
	$extension = pathinfo($file_name, PATHINFO_EXTENSION);
	if ($extension == null) {
		header("Location: /admin/upload.php");
		}
	if (strpos($file_type, 'image') === false) {
			$data = 'Загрузите только картинки';
			echo json_encode(array(
		        'result'    => 'error',
		        'data'      => $data
		    ));
		    exit();
		}
	$file_size = $file['size'];
		if ($file_size > 200000) {
			$data = 'Максимальный размер файла: 200Кб';
				echo json_encode(array(
			        'result'    => 'error',
			        'data'      => $data
			));
			exit();
		}
	}
	// $uploaddir = './uploads'; // . - текущая папка где находится submit.php
	$uploaddir = $_POST['select'];
	// cоздадим папку если её нет
	if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );

	$files      = $_FILES; // полученные файлы
	$done_files = array();

	// переместим файлы из временной директории в указанную
	foreach( $files as $file ){
		$file_name = $file['name'];

		if( move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" ) ){
			$done_files[] = realpath( "$uploaddir/$file_name" );
		}
	}

	$data = $done_files ? array('files' => $done_files ) : array('error' => 'Ошибка загрузки файлов.');

	die( json_encode( $data ) );
} else {
	$data = 'Уйди по хорошему!';
			echo json_encode(array(
		        'result'    => 'error',
		        'data'      => $data
		    ));
		    exit();
}
?>