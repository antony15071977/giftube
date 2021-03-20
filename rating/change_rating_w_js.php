<?php
if (is_numeric($_GET["obj_id"]) and $_GET["stars"]>=0 and $_GET["stars"]<=5) {
 $obj = trim(strip_tags(htmlspecialchars($_GET["obj_id"])));
 $ocenka = trim(strip_tags(htmlspecialchars($_GET["stars"])));
 $time = $_SERVER["REQUEST_TIME"];
 $ip = $_SERVER["REMOTE_ADDR"];
 require_once('../config/config.php');
 $sql_gif = 'SELECT g.url, c.urlCat FROM gifs g JOIN categories c ON g.category_id = c.id WHERE g.id = "'.$obj.'"';
        $res_gif = mysqli_query($connect, $sql_gif);
        if ($res_gif) {
            $gif = mysqli_fetch_assoc($res_gif);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: '.$error);
        }
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
    header('Location: '.$address_site.'vote/'.$gif['urlCat'].'/'.$gif['url'].'/');    
 }
 else { 
    header('Location: '.$address_site.'voted/'.$gif['urlCat'].'/'.$gif['url'].'/'); 
    }
}
?>