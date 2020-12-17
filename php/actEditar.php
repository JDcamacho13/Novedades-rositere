<?php

    include_once 'includes/funcs.php';

    $data[0] = 0;

    if(!empty($_POST)){

        $id = $_POST['id'];
        $status = true;
        
        if($_POST['nombre'] != ""){
            $nombre = $_POST['nombre'];
            if(editarNombreP($id, $nombre)){
                $data[0] = 1;
                array_push($data, "Se cambio el nombre a: ". $nombre);
            }else{
                $status = false;
                $data[0] = 1;
                array_push($data, "Ya existe un producto con ese nombre");
            }
        }

        if(($_POST['precio'] != "") && $status){
            $precio = $_POST['precio'];
            if(editarPrecioP($id, $precio)){
                $data[0] = 1;
                array_push($data, "Se Cambio el precio a: ". $precio);
            }
        }

        echo json_encode($data);
    }
    

?>