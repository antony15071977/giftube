<?php

$connect = mysqli_connect("localhost", "u0973448_default", "MFlIB9!0", "u0973448_default");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
$address_site = "https://test.channail4.com/";
$email_admin = "admin@CHANNAIL4.COM";