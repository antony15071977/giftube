<?php
// session_save_path('sessions');
session_start();
$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}

$address_site = "https://test.channail4.com/";
$email_admin = "admin@CHANNAIL4.COM";