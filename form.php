<?php
require "login.php";
require "queryOne.php";
require "queryAll.php";
require "queryInsert.php";
require "mailToUser.php";
require "createUser.php";

//Авторизация или регистрация пользователя по email
$user = createUser($pdo);
$userId = $user['userId'];
$addressId = $user['addressId'];

//Сохранение данных о заказе
//$comment = mysqli_real_escape_string($mysql, $_POST['comment']); //mysqli
$comment = $pdo->quote($_POST['comment']); //pdo
//$payment = mysqli_real_escape_string($mysql, $_POST['payment']); //mysql
$payment = $pdo->quote($_POST['payment']); //pdo
$callback = $_POST['callback'] === "true" ? 1 : 0;
//$query = "INSERT INTO details (user_id, address_id, comment, payment, callback) VALUES ('$userId', '$addressId', '$comment', '$payment', '$callback');"; //mysql
$query = "INSERT INTO details (user_id, address_id, comment, payment, callback) VALUES ('$userId', '$addressId', $comment, $payment, '$callback');"; //pdo
queryInsert($pdo, $query);

//Отправка письма (сохранение данных в файл)
//$email = mysqli_real_escape_string($mysql, $_POST['email']); //mysql
$email = $pdo->quote($_POST['email']); //pdo
//$query = "SELECT details.id, details.address_id FROM details LEFT JOIN users ON details.user_id = users.id WHERE users.email = '$email' ORDER BY details.id DESC;"; //mysql
$query = "SELECT details.id, details.address_id FROM details LEFT JOIN users ON details.user_id = users.id WHERE users.email = $email ORDER BY details.id DESC;"; //pdo
$receive = queryAll($pdo, $query);
$ordersAmount = count($receive);

$receive = queryOne($pdo, $query);
$id = $receive['id'];
$addressId = $receive['address_id'];

$query = "SELECT * FROM addresses WHERE id = '$addressId';";
$receive = queryOne($pdo, $query);
$street = $receive['street'];
$home = $receive['home'];
$part = $receive['part'];
$appt = $receive['appt'];
$floor = $receive['floor'];

$heading = "Заказ №{$id}\n";
$desc = "Ваш заказ будет отправлен по адресу: улица $street, д.$home, к.$part, кв.$appt, эт.$floor\n";
$content = "DarkBeefBurger за 500 рублей, 1 шт\n";
$thanks = $ordersAmount == 1 ? "Спасибо - это ваш первый заказ" : "Спасибо! Это уже $ordersAmount заказ";
mailToUser($heading, $desc, $content, $thanks);
