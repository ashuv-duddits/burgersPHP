<?php
require "login.php";
require "functions.php";
//Получаем всех пользователей
$query = "SELECT * FROM users";
$receive = queryAll($pdo, $query);
foreach ($receive as $key => $value) {
    echo "<b style='font-size: 20px'>".$value['name']." <i style='font-size: 18px'>(email - ".$value['email'].")</i> сделал следующие заказы:</b><br />";
    $id = $value['id'];
    $query = "SELECT * FROM details WHERE user_id = '$id'";
    $receive = queryAll($pdo, $query);
    foreach ($receive as $key => $value) {
        echo "Заказ №".$value['id'].": ".$value['comment']."</br />";
    }
}
