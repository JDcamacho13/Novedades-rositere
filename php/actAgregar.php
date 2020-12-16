<?php

include "includes/funcs.php";

if(!productoExiste($_POST['nombre'])){
    if (($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpg")){
            if(agregarProducto($_POST['nombre'], $_POST['precio'], "images/".$_FILES['file']['name'])){
                if (move_uploaded_file($_FILES["file"]["tmp_name"], "../images/".$_FILES['file']['name'])){
                
                    echo 1;
                }else{
                    echo 0;
                }
            }
    }else{
        echo 0;
    }
}else{
    echo 2;
}
    
