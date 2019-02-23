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
//try {
//    $pdo = new PDO("mysql:host=localhost;port=3307;dbname=burgers".$dbname, "root", "");
//} catch (PDOException $e) {
//    echo $e->getMessage();
//    die();
//}

//Авторизация или регистрация пользователя по email
$email = mysqli_real_escape_string($mysql, $_POST['email']);
$ret = $mysql->query("SELECT * FROM users WHERE email='$email'");
if (!$ret) {
    print_r($mysql->error);
    die();
}
if (empty($ret->fetch_assoc()) && !empty($_POST['email'])) {
    /*insert*/
    $name = mysqli_real_escape_string($mysql, $_POST['name']);
    $phone = mysqli_real_escape_string($mysql, $_POST['phone']);
    $ret = $mysql->query("INSERT INTO users (`name`, email, phone) VALUES ('$name', '$email', '$phone');");
    if (!$ret) {
        echo "query error: ".$mysql->error;
        die();
    }
    $userId = $mysql->insert_id;
    /*insert*/
    $street = mysqli_real_escape_string($mysql, $_POST['street']);
    $home = intval($_POST['home']);
    $part = intval($_POST['part']);
    $appt = intval($_POST['appt']);
    $floor = intval($_POST['floor']);
    $ret = $mysql->query("INSERT INTO addresses (street, home, part, appt, floor) VALUES ('$street', '$home', '$part', '$appt', '$floor');");
    if (!$ret) {
        echo "query error: ".$mysql->error;
        die();
    }
    $addressId = $mysql->insert_id;
} else {
    $ret = $mysql->query("SELECT * FROM users WHERE email='$email'");
    if (!$ret) {
        print_r($mysql->error);
        die();
    }
    $userId = $ret->fetch_assoc()['id'];
    $ret = $mysql->query("SELECT * FROM details WHERE user_id ='$userId'");
    if (!$ret) {
        print_r($mysql->error);
        die();
    }
    $addressId = $ret->fetch_assoc()['address_id'];
}

/*insert*/
$comment = mysqli_real_escape_string($mysql, $_POST['comment']);
$payment = mysqli_real_escape_string($mysql, $_POST['payment']);
$callback = $_POST['callback'] === "true" ? 1 : 0;
$ret = $mysql->query("INSERT INTO details (user_id, address_id, comment, payment, callback) VALUES ('$userId', '$addressId', '$comment', '$payment', '$callback');");
if (!$ret) {
    echo "query error: ".$mysql->error;
    die();
}

//Получение необходимых данные из БД
$ret = $mysql->query("SELECT details.id, details.address_id FROM details LEFT JOIN users ON details.user_id = users.id WHERE users.email = '$email' ORDER BY details.id DESC;");
if (!$ret) {
    print_r($mysql->error);
    die();
}
$ordersAmount = count($ret->fetch_all());
$ret = $mysql->query("SELECT details.id, details.address_id FROM details LEFT JOIN users ON details.user_id = users.id WHERE users.email = '$email' ORDER BY details.id DESC;");
if (!$ret) {
    print_r($mysql->error);
    die();
}
$receive = $ret->fetch_assoc();
$id = $receive['id'];
$addressId = $receive['address_id'];

$ret = $mysql->query("SELECT * FROM addresses WHERE id = '$addressId';");
if (!$ret) {
    print_r($mysql->error);
    die();
}

$receive = $ret->fetch_assoc();
$street = $receive['street'];
$home = $receive['home'];
$part = $receive['part'];
$appt = $receive['appt'];
$floor = $receive['floor'];

$heading = "Заказ №{$id}\n";
$desc = "Ваш заказ будет отправлен по адресу: улица $street, д.$home, к.$part, кв.$appt, эт.$floor\n";
$content = "DarkBeefBurger за 500 рублей, 1 шт\n";
$thanks = $ordersAmount == 1 ? "Спасибо - это ваш первый заказ" : "Спасибо! Это уже $ordersAmount заказ";

//Отправка письма (сохранение данных в файл)
$currentDate = date("d-m-Y_H-i-s");
$filePath = "mail/mail_".$currentDate.".txt";
$result = file_put_contents($filePath, $heading.$desc.$content.$thanks);

if ($result) {
    echo json_encode("Ваше письмо отправлено курьеру");
} else {
    echo json_encode("Ошибка при записи в файл");
}

//$email = "ashuv_vyksa@mail.ru";
//$email = $_POST['email'];
//$ret = $pdo->query("SELECT * FROM users WHERE email = $email;");
//if (!$ret) {
//    print_r($pdo->errorInfo());
//    die();
//}

//if (!empty($_REQUEST['ajax'])) {
//    echo json_encode($_POST['name']);
//}
