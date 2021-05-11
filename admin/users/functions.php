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
            }
            else {
            $user = null; 
            }
        }
    } 
}
if (!isset($_SESSION['user'])&&$_SESSION['user']['status']==2) {
// echo "У вас нет прав!";
    //разлогин и  exit();
}
function include_template($name, array $data = []) {
    $name = '../templates/' . $name;
    $result = '';
    if (!is_readable($name)) {
        return $result;
    }
    ob_start();
    extract($data);
    require $name;
    $result = ob_get_clean();
    return $result;
}
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }
    if ($data) {
        $types = '';
        $stmt_data = [];
        foreach ($data as $value) {
            $type = 's';
            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }
        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }
    return $stmt;
}