<?php

include_once 'php/includes/userSession.php';
$user = new UserSession();

if(isset($_SESSION['usuario'])){
    $nombre=$user->getCurrentUser();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Novedades Rositere</title>
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link rel="stylesheet/less" type="text/css" href="css/style.less">
        <link rel="icon" href="./images/tienda-online.png">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>
    <body>
        <input type="text" name="permiso" id="permiso" value="<?php echo($_SESSION['permisos'])?>">
        <header>
            <div class="wrapper">
                <div class="logo">Novedades Rositere</div>
                
                <nav>
                <?php

                    if(!isset($_SESSION['usuario'])){
                    ?>
                        <a href="login.php">Ingresar</a>
                    <?php
                    }else{
                        ?>
                        <span class="usuario"><i class="fas fa-bars" style="color: white;"></i></span>
                        <?php
                    }
                ?>
                    
                </nav>
            </div>
        </header>
        <div class="container-dolar">
            <div id="dolar">
                Precio del Dolar: Cargando....
            </div>
        </div>
       
        <div id="resultado" class="cards"></div>

        <div class="right-panel" id="menu">
            <div class="exit"><span style="font-size: 25px;cursor:pointer" id="exit"><i class="fas fa-arrow-right"></span></i></div>
            <div class="info-usuario">
                <div style="width: 100%; text-align: center;"><i class="fas fa-user-circle" style="color: white; font-size:120px;"></i></div>
                <h2><?php echo($_SESSION['nombre']. " " . $_SESSION['apellido'])?></h2>
                <?php 
                    if($_SESSION['permisos'] == 1){
                        echo("<p>Administrador</p>");
                    }
                ?>
            </div>
            <div class="options-menu">
                <ul>
                    
                    <li><a href="./index.php"><i class="fas fa-home" style="color: white"></i>&nbsp&nbspInicio</a></li>

                    <?php 
                        if($_SESSION['permisos'] == 1){
                            echo("<li><a href='./agregar.php'><i class='fas fa-ad' style='color: white'></i>&nbsp&nbspAgregar nuevo producto</a></li>");
                        }
                    ?>

                    <li><a href="./php/logout.php"><i class="fas fa-sign-out-alt" style="color: white"></i>&nbsp&nbspCerrar Sesión</a></li>
                    
                </ul>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="./js/diseño.js"></script> 
        <script src="./js/editar.js"></script> 
        <script src="./js/actualizarVista.js"></script> 
        <script src="./js/menu.js"></script> 
        <script src="./js/less.js"></script>
    </body>
</html>
