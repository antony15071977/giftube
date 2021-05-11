<?php
if(isset($_COOKIE["cookie_token"]) && !empty($_COOKIE["cookie_token"])){
    $cookie_token = $_COOKIE["cookie_token"];
    $select_user_data = "SELECT * FROM `users` WHERE cookie_token = '".$cookie_token."'";
    $res_select_user_data = mysqli_query($connect, $select_user_data);
    if(!$res_select_user_data){
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }
    else {
        $user = $res_select_user_data ? mysqli_fetch_all($res_select_user_data, MYSQLI_ASSOC) : null;
        if($user){
            $cookie_token = md5($user[0]['secretkey'].":".$_SERVER["REMOTE_ADDR"]).md5($user[0]['dt_add']);
            if ($cookie_token == $user[0]['cookie_token']) {
                $_SESSION['user'] = $user[0];
                if ($user[0]['status'] == 1) {
                    echo "<p class='mesage_error'>Вы забанены за нарушение правил использования сайта!</p>";
                    exit();
                }
            }
            else {
            $user = null; 
            }
        }
    } 
}
?>