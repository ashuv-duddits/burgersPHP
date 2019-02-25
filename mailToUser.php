<?php
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