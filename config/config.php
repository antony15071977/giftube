<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "giftube");
$conn = new PDO("mysql:host=localhost; dbname=giftube", "root", "");
$connectSearch = new PDO("mysql:host=localhost; dbname=giftube; charset=utf8", "root", "");
mysqli_set_charset($connect, "utf8");
if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
$address_site = "http://ask-me/";
$email_admin = "i.avraamy2@gmail.com";
if (defined('dbOn')) {
    $mysqli = new mysqli('localhost', 'root', '', 'giftube');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
}

