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
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>
    <body>
        <header>
            <div class="wrapper">
                <div class="logo">Novedades Rositere</div>
                
                <nav>
                <?php

                    if(!isset($_SESSION['usuario'])){
                    ?>
                        <center><a href="login.php">Ingresar</a></center>
                    <?php
                    }else{
                        ?>
                        <center><span class="usuario"><?php echo $nombre?></span></center>
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
            <div class="info-usuario"></div>
            <div class="options-menu">
                <ul>
                    <a href="./php/logout.php">Cerrar Sesión</a>
                    
                </ul>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="./js/diseño.js"></script> 
        <script src="./js/actualizarVista.js"></script> 
        <script src="./js/menu.js"></script> 
        <script src="./js/less.js"></script>
    </body>
</html>
