<?php

    include_once 'includes/funcs.php';

    $data;
    $errors = [];

    if(!empty($_POST)){
        
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $result = false;

        if(empty($nombre)){
            $errors[] = "Debes ingresar un nombre";
        }

        if(empty($apellido)){
            $errors[] = "Debes ingresar un apellido";
        }

        if(empty($usuario)){
            $errors[] = "Debes ingresar un nombre de usar $usuario";
        }

        if(empty($password)){
            $errors[] = "Debes ingresar una clave";
        }


        if(usuarioExiste($usuario))
		{
			$errors[] = "El nombre de usuario $usuario ya existe";
        }
        
        if(count($errors) < 1){
            $pass_hash = hashPassword($password);
            $result = registro($nombre, $apellido, $usuario, $pass_hash);
        }

    }

    if(!$result){
        $data = resultBlock($errors); 
        echo json_encode($data);
    }else{
        echo json_encode(0);
    }

?>