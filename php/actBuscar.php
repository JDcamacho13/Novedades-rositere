<?php

include_once 'includes/dataBase.php';

$DB = new DB();

$sql = "SELECT * FROM productos,dolar";
$query = $DB->connect()->query($sql);

if($query->rowCount()){

    $result = array();
	
    while($r = $query->fetch()){

        array_push($result, array($r['id'],$r['nombre'],$r['precio_dolares'],$r['precio_dolar']));

    }
    
    echo json_encode($result);
    
}
?>