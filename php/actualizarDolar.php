<?php

include_once 'includes/dataBase.php';

$DB = new DB();

$precio = $_POST['precio'];
$fecha = $_POST['fechaHora'];

$sql = "UPDATE dolar SET precio_dolar = '$precio', fecha='$fecha' WHERE id = 1";
$query = $DB->connect()->query($sql);

echo true;

?>