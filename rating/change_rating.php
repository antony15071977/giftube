<?php
if (is_numeric($_POST["obj_id"]) and $_POST["stars"]>=0 and $_POST["stars"]<=5) {
 $obj = trim(strip_tags(htmlspecialchars($_POST["obj_id"])));
 $ocenka = trim(strip_tags(htmlspecialchars($_POST["stars"])));
 $time = $_SERVER["REQUEST_TIME"];
 $ip = $_SERVER["REMOTE_ADDR"];
 require_once('../config/config.php');
 $res = mysqli_query($connect, "DELETE FROM votes WHERE date<".($time-259200000));
 $res = mysqli_query($connect, "SELECT count(id) FROM votes
    WHERE obj_id=".$obj." and ip=INET_ATON('".$ip."')");
 $number = mysqli_fetch_array($res);
 if ($number[0]==0) {
    mysqli_query($connect, "START TRANSACTION");
    $res1 = mysqli_query($connect, "INSERT INTO votes (date,obj_id,ip,rating)
        values (".$time.", ".$obj.", INET_ATON('".$ip."'), ".$ocenka.")");
    $res2 = mysqli_query($connect, "UPDATE gifs
        SET points=(points+".$ocenka."), votes=(votes+1) WHERE id=".$obj." LIMIT 1");
    $res3 = mysqli_query($connect, "SELECT * FROM gifs WHERE id=".$obj." LIMIT 1");
    $stars = mysqli_fetch_array($res3);
    $rating = round($stars["points"]/$stars["votes"], 2);
    $res4 = mysqli_query($connect, "UPDATE gifs
        SET avg_points=".$rating." WHERE id=".$obj." LIMIT 1");
    if ($res1 && $res2 && $res4) {
            mysqli_query($connect, "COMMIT");
        } else {
           echo 'ERROR';
           exit(); 
        }
    echo '{"points":"Рейтинг: '.$rating.'",';
    echo '"votes":"Оценили: '.$stars["votes"].'",';
    echo '"rating":"'.$rating.'",';
    echo '"message":"Спасибо, Ваш голос учтен!"}';
 }
 else { $res = mysqli_query($connect, "SELECT * FROM gifs WHERE id=".$obj." LIMIT 1");
    $stars = mysqli_fetch_array($res);
    $rating = round($stars["points"]/$stars["votes"], 2);
    echo '{"message":"Вы уже голосовали!",';
    echo '"rating":"'.$rating.'"}';
}
}
?>