<?php
require_once('../config/config.php');
if(isset($_COOKIE["cookie_token"])){
	$email = $_SESSION['user']['email'];
	//Очищаем поле cookie_token из базы данных
	$update_cookie_token = "UPDATE users SET cookie_token='' WHERE email = '".$email."'";
	$res_update_cookie_token = mysqli_query($connect, $update_cookie_token);
//Удаляем куку cookie_token
	setcookie("cookie_token", "", time()-3600, "/");
}
unset($_SESSION['user']);
header("Location: /admin/signin.php");
