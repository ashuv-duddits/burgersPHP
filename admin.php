<?php
//Соединение с базой данных
$db_hostname = 'localhost'; // хост БД
$db_database = 'burgers'; // имя базы данных
$db_username = 'root'; // имя пользователя
$db_password = ''; // пароль
$db_port = 3307; // порт
$mysql = new mysqli($db_hostname, $db_username, $db_password, $db_database, $db_port);
if ($mysql->connect_error) {
    die($mysql->connect_error);
}
//Получаем всех пользователей
$ret = $mysql->query("SELECT * FROM users");
if (!$ret) {
    print_r($mysql->error);
    die();
}
$receive = $ret->fetch_all(MYSQLI_ASSOC);
foreach ($receive as $key => $value) {
    echo "<b style='font-size: 20px'>".$value['name']." сделал следующие заказы:</b><br />";
    $id = $value['id'];
    $ret = $mysql->query("SELECT * FROM details WHERE user_id = '$id'");
    if (!$ret) {
        print_r($mysql->error);
        die();
    }
    $receive = $ret->fetch_all(MYSQLI_ASSOC);
    foreach ($receive as $key => $value) {
        echo "Заказ №".$value['id'].": ".$value['comment']."</br />";
    }
}
