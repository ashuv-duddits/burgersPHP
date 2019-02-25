<?php
//Соединение с базой данных
/*
$db_hostname = 'localhost'; // хост БД
$db_database = 'burgers'; // имя базы данных
$db_username = 'root'; // имя пользователя
$db_password = ''; // пароль
$db_port = 3307; // порт
$mysql = new mysqli($db_hostname, $db_username, $db_password, $db_database, $db_port);
if ($mysql->connect_error) {
    die($mysql->connect_error);
}
*/
try {
    $pdo = new PDO("mysql:host=localhost;port=3307;dbname=burgers", "root", "");
} catch (PDOException $e) {
    echo $e->getMessage();
    die();
}
