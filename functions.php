<?php
function queryOne($bd, $query)
{
    $ret = $bd->query($query);
    if (!$ret) {
        //print_r($bd->error); //mysqli
        print_r($bd->errorInfo()); //pdo
        die();
    }
    //$receive = $ret->fetch_assoc(); //mysqli
    $receive = $ret->fetch(PDO::FETCH_ASSOC); //mysqli
    return $receive;
}

function queryAll($bd, $query)
{
    $ret = $bd->query($query);
    if (!$ret) {
        //print_r($bd->error); //mysqli
        print_r($bd->errorInfo()); //pdo
        die();
    }
    //$receive = $ret->fetch_all(MYSQLI_ASSOC); //mysqli
    $receive = $ret->fetchAll(PDO::FETCH_ASSOC); //pdo
    return $receive;
}

function queryInsert($bd, $query)
{
    $ret = $bd->query($query);
    if (!$ret) {
        //print_r($bd->error); //mysqli
        print_r($bd->errorInfo()); //pdo
        die();
    }
    //$receive = $bd->insert_id; //mysqli
    $receive = $bd->lastInsertId(); //pdo
    return $receive;
}

function mailToUser($heading, $desc, $content, $thanks)
{
    $currentDate = date("d-m-Y_H-i-s");
    $filePath = "mail/mail_".$currentDate.".txt";
    $result = file_put_contents($filePath, $heading.$desc.$content.$thanks);
    if ($result) {
        echo json_encode("Ваше письмо отправлено курьеру");
    } else {
        echo json_encode("Ошибка при записи в файл");
    }
}

function createUser($bd)
{
    //$email = mysqli_real_escape_string($bd, $_POST['email']); //mysql
    $email = $bd->quote($_POST['email']); //pdo
    //$query = "SELECT * FROM users WHERE email='$email'"; //mysql
    $query = "SELECT * FROM users WHERE email=$email"; //pdo
    $receive = queryOne($bd, $query);
    if (empty($receive) && !empty($_POST['email'])) {
        /*insert*/
        //$name = mysqli_real_escape_string($bd, $_POST['name']); //mysql
        $name = $bd->quote($_POST['name']); //pdo
        //$phone = mysqli_real_escape_string($bd, $_POST['phone']); //mysql
        $phone = $bd->quote($_POST['phone']); //pdo
        //$query = "INSERT INTO users (`name`, email, phone) VALUES ('$name', '$email', '$phone');"; //mysql
        $query = "INSERT INTO users (`name`, email, phone) VALUES ($name, $email, $phone);"; //pdo
        $receive = queryInsert($bd, $query);
        $userId = $receive;
        /*insert*/
        //$street = mysqli_real_escape_string($bd, $_POST['street']); //mysql
        $street = $bd->quote($_POST['street']); //mysql
        $home = intval($_POST['home']);
        $part = intval($_POST['part']);
        $appt = intval($_POST['appt']);
        $floor = intval($_POST['floor']);
        //$query = "INSERT INTO addresses (street, home, part, appt, floor) VALUES ('$street', '$home', '$part', '$appt', '$floor');"; //mysql
        $query = "INSERT INTO addresses (street, home, part, appt, floor) VALUES ($street, '$home', '$part', '$appt', '$floor');"; //pdo
        $receive = queryInsert($bd, $query);
        $addressId = $receive;
    } else {
        //$query = "SELECT * FROM users WHERE email='$email'"; //mysql
        $query = "SELECT * FROM users WHERE email=$email"; //pdo
        $receive = queryOne($bd, $query);
        $userId = $receive['id'];
        $query = "SELECT * FROM details WHERE user_id ='$userId'";
        $receive = queryOne($bd, $query);
        $addressId = $receive['address_id'];
    }
    return ['userId' => $userId, 'addressId' => $addressId];
}

function mailToMail($heading, $desc, $content, $thanks)
{
    try {
// Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
            ->setUsername('senderevich@mail.ru')
            ->setPassword('$daasdk820&1fdfss2')
        ;

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Заявка с моего сайта Бургеров'))
            ->setFrom(['senderevich@mail.ru' => 'senderevich@mail.ru'])
            ->setTo(['ashuv_vyksa@mail.ru'])
            ->setBody($heading.$desc.$content.$thanks);
        ;

// Send the message
        $result = $mailer->send($message);
        var_dump(['res' => $result]);
    } catch (Exception $e) {
        var_dump($e->getMessage());
        echo '<pre>' . print_r($e->getTrace(), 1);
    }
}
