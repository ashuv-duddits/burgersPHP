<?php
function queryAll($bd, $query)
{
    $ret = $bd->query($query);
    if (!$ret) {
        //print_r($bd->error); //mysqli
        print_r($bd->errorInfo()); //pdo
        die();
    }
    //$receive = $ret->fetch_all(MYSQLI_ASSOC); //mysqli
    $receive = $ret->fetchAll(); //pdo
    return $receive;
}
