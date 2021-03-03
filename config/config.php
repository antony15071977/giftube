<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "giftube");
$connectSearch = new PDO("mysql:host=localhost; dbname=giftube", "root", "");
mysqli_set_charset($connect, "utf8");
if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
$address_site = "http://giftube/";
$email_admin = "admin@blabla";