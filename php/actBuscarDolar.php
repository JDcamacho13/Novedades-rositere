<?php

include_once 'includes/dataBase.php';

$DB = new DB();

$sql = "SELECT * FROM dolar";
$query = $DB->connect()->query($sql);

if($query->rowCount()){

    $reslut;

    while($r = $query->fetch()){
        $result = array($r['precio_dolar'],$r['fecha']);
    }

    echo json_encode($result);
    
}


?>