<?php

    include_once 'includes/funcs.php';

    $data;
    $errors = [];

    if(!empty($_POST)){

        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $errors[]= login($usuario, $password);

    }

    if(!is_null($errors[0])){
        $data = resultBlock($errors); 
        echo json_encode($data);
    }else{
        echo json_encode(0);
    }

?>