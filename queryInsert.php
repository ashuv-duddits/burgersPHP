<?php
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