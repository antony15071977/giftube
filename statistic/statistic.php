<?php
// Получаем уникальный id сессии 
  $id_session = session_id(); 
   
  // Проверяем, присутствует ли такой id в базе данных 
  $query = "SELECT * FROM session 
            WHERE id_session = '$id_session'"; 
  $ses = mysqli_query($connect, $query); 
  if(!$ses) exit("<p>Ошибка в запросе к таблице сессий</p>"); 
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
    echo ("<p>Ошибка в запросе к таблице сессий</p>");
  } else {
  // Если хоть кто-то есть - выводим таблицу 
  $num_online = mysqli_num_rows($ath);
  }
?>