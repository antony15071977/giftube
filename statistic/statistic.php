<?php
// Получаем уникальный id сессии 
  $id_session = session_id(); 
   
  // Проверяем, присутствует ли такой id в базе данных 
  $query = "SELECT * FROM session 
            WHERE id_session = '$id_session'"; 
  $ses = mysqli_query($connect, $query); 
  if(!$ses) echo"Ошибка в запросе к таблице сессий"; 
  // Если сессия с таким номером уже существует, 
  // значит пользователь online - обновляем время его последнего посещения 
  $username = $_SESSION['user']['name'] ?? "";
  if(mysqli_num_rows($ses)>0) 
  { 
    $query = "UPDATE session SET putdate = NOW(), user = '$username' WHERE id_session = '$id_session'"; 
    mysqli_query($connect, $query); 
  } 
  // Иначе, если такого номера нет - посетитель только что вошёл - помещаем в таблицу нового посетителя 
  else 
  { 
    $query = "INSERT INTO session 
              VALUES('$id_session', NOW(), '$_SESSION[user][name]')"; 
    if(!mysqli_query($connect, $query)) 
    { 
      echo "<p>Ошибка при добавлении пользователя</p>";
    } 
  } 
  // Будем считать, что пользователи, которые отсутствовали в течении 20 минут - покинули ресурс - удаляем их id_session из базы данных 
  $query = "DELETE FROM session 
            WHERE putdate < NOW() -  INTERVAL '50' MINUTE"; 
  mysqli_query($connect, $query);

$query = "SELECT * FROM session"; 
  $ath = mysqli_query($connect, $query); 
  if(!$ath){
    echo "Ошибка в запросе к таблице сессий";
  } else {
  // Если хоть кто-то есть - выводим таблицу 
  $num_online = mysqli_num_rows($ath);
  }
// Скрипт подсчета посетителей
// Получаем IP-адрес посетителя и сохраняем текущую дату
$visitor_ip = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d");

// Узнаем, были ли посещения за сегодня
$res = mysqli_query($connect, "SELECT `visit_id` FROM `visits` WHERE `date`='$date'");
if (!$res) {
  echo "Проблема при подключении к БД";
}

// Если сегодня еще не было посещений
if (mysqli_num_rows($res) == 0)
{
    // Очищаем таблицу ips
    mysqli_query($connect, "DELETE FROM `ips`");

    // Заносим в базу IP-адрес текущего посетителя
    mysqli_query($connect, "INSERT INTO `ips` SET `ip_address`='$visitor_ip'");

    // Заносим в базу дату посещения и устанавливаем кол-во просмотров и уник. посещений в значение 1
    $res_count = mysqli_query($connect, "INSERT INTO `visits` SET `date`='$date', `hosts`=1,`views`=1");
}

// Если посещения сегодня уже были
else
{
    // Проверяем, есть ли уже в базе IP-адрес, с которого происходит обращение
    $current_ip = mysqli_query($connect, "SELECT `ip_id` FROM `ips` WHERE `ip_address`='$visitor_ip'");

    // Если такой IP-адрес уже сегодня был (т.е. это не уникальный посетитель)
    if (mysqli_num_rows($current_ip) == 1)
    {
        // Добавляем для текущей даты +1 просмотр (хит)
        mysqli_query($connect, "UPDATE `visits` SET `views`=`views`+1 WHERE `date`='$date'");
    }

    // Если сегодня такого IP-адреса еще не было (т.е. это уникальный посетитель)
    else
    {
        // Заносим в базу IP-адрес этого посетителя
        mysqli_query($connect, "INSERT INTO `ips` SET `ip_address`='$visitor_ip'");

        // Добавляем в базу +1 уникального посетителя (хост) и +1 просмотр (хит)
        mysqli_query($connect, "UPDATE `visits` SET `hosts`=`hosts`+1,`views`=`views`+1 WHERE `date`='$date'");
    }
}
// Извлекаем статистику по текущей дате
$res = mysqli_query($connect, "SELECT `views`, `hosts` FROM `visits` WHERE `date`='$date'");
$row = mysqli_fetch_all($res, MYSQLI_ASSOC);

// Извлекаем статистику за месяц
$sql_hosts_stat_month = mysqli_query($connect, "SELECT SUM(`hosts`) as `sum` FROM `visits` ORDER BY `date` DESC LIMIT 30");
$row_hosts_stat_month = mysqli_fetch_array($sql_hosts_stat_month);
$hosts_stat_month = $row_hosts_stat_month[0];

$sql_views_stat_month = mysqli_query($connect, "SELECT SUM(`views`) as `sum` FROM `visits` ORDER BY `date` DESC LIMIT 30");
$row_views_stat_month = mysqli_fetch_array($sql_views_stat_month);
$views_stat_month = $row_views_stat_month[0];
?>