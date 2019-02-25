<?php
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