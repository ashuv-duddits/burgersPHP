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