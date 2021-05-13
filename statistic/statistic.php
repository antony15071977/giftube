<?php
$bot='';
$ip=$_SERVER['REMOTE_ADDR'];
$REQUEST_URI=$_SERVER['REQUEST_URI'];
$HTTP_USER_AGENT=$_SERVER['HTTP_USER_AGENT'];
$QUERY_STRING=$_SERVER['QUERY_STRING'];
$HTTP_REFERER=$_SERVER['HTTP_REFERER'];
$REMOTE_HOST=$_SERVER['REMOTE_HOST'];
if (strstr($_SERVER['HTTP_USER_AGENT'], 'Yandex')) $bot='Yandex';
elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Google')) $bot='Google';
elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Yahoo')) $bot='Yahoo';
elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Mail')) $bot='Mail';
if ($bot=='') {
$res=mysqli_query($connect,"INSERT INTO all_visits (ip,date) VALUES
     (INET_ATON('".$ip."'),'".time()."')");
$res=mysqli_query($connect,"SELECT count(id) FROM all_visits WHERE
     (ip=INET_ATON('".$ip."') and date>'".(time()-10)."') LIMIT 1");
 $count_visit=mysqli_fetch_array($res);
 if ($count_visit[0]>10) {
 $res=mysqli_query($connect,"INSERT INTO black_list_ip (ip,date) VALUES
     (INET_ATON('".$ip."'),'".time()."')");
// А как можно ограничить по количеству запросов в сутки с одного IP. Сейчас боты атакуют с промежутком в 4-5 секунд и скрипт уже не спасает.
// Удаляйте из БД записи старше 86400 секунд (24 часа) и в запросе SELECT count(id) замените (time()-10) на (time()-86400). В if ($count_visit[0]>10) значение 10 замените на нужное Вам количество посещений в сутки.
 $tmestamp = time();
$datum = date("H:i:s d.m.Y",$tmestamp);
$subject = "Сработал автобан";
$msg = "Пойман на странице $REQUEST_URI $datum, IP: $ip, User-агент $HTTP_USER_AGENT, Метод $REQUEST_METHOD, Строка запросов, если есть, с помощью которой была получена страница $QUERY_STRING, Адрес страницы, если есть, которая привела браузер пользователя на эту страницу $HTTP_REFERER, Удаленный хост, если есть, с которого пользователь просматривал текущую страницу $REMOTE_HOST";
mail($email_admin, $subject, $msg);

 $start_line=0;
 $lines='';
 $ln_hta='';

 $fh=fopen("../.htaccess", "a+");
 flock($fh, LOCK_EX);
 fseek($fh, 0);
 while (!feof($fh)) $lines.=fread($fh,2048);
 $lines=explode("\n", $lines);

  for ($n=0; $n<=count($lines); $n++) {
   if (strstr($lines[$n],"Order Allow,Deny")) $start_line=$n;
  }
  if ($start_line!=0) for ($n=0; $n<$start_line; $n++) $ln_hta[]=$lines[$n];
  else $ln_hta=$lines;

  $ln_hta[]="Order Allow,Deny";
  $ln_hta[]="Allow from all";

  $res=mysqli_query($connect,"SELECT INET_NTOA(ip) AS ip,date FROM black_list_ip
      ORDER BY INET_ATON(ip)");
  while ($bad_ip=mysqli_fetch_array($res)) {
   if (time()<($bad_ip[date]+900))$ln_hta[]=" deny from ".$bad_ip[ip];
  }
  $ln_hta=implode("\n",$ln_hta);
  ftruncate($fh, 0);
  fwrite($fh, $ln_hta);
  flock($fh, LOCK_UN);
  fclose($fh);
 }
}
// Получаем уникальный id сессии 
  $id_session = session_id(); 
$cron_time = filemtime("../statistic/cron.php");    //получаем время последнего изменения файла
  if (date("d")!=date("d",$cron_time)) {    //сравниваем день изменения файла с текущим
    file_put_contents("../statistic/cron.php","обновлено");    //перезаписываем файл cron_time
    mysqli_query($connect, "DELETE FROM `visits` WHERE date<NOW() - INTERVAL 30 DAY;");
    mysqli_query($connect, "DELETE FROM `all_visits` WHERE date<NOW() - INTERVAL 1 DAY;");
    }               
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
  // Будем считать, что пользователи, которые отсутствовали в течении 50 минут - покинули ресурс - удаляем их id_session из базы данных 
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