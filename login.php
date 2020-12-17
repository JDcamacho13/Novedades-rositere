<?php

include_once 'php/includes/userSession.php';
$user = new UserSession();

if(isset($_SESSION['usuario'])){
    $nombre=$user->getCurrentUser();
    
    header("Location: ./index.php");

}else{
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Novedades Rositere</title>
        <link href="css/style-login.css" rel='stylesheet' type='text/css' />
        <link rel="icon" href="./images/tienda-online.png">
    </head>
    <body>
        <div id="errores"></div>
        <div class="login-page" id="login-page">
            <div class="form">
                <h2>Iniciar Sesión</h2>
                <form class="register-form" id="form">
                    <input type="text" id="usuario" placeholder="Nombre de Usuario"/>
                    <input type="password" id="password" placeholder="Clave"/>
                    <input type="submit" id="submit" value="Enviar"> <br />
                    <p>¿No tienes una cuenta? Registrate <a href="register.php">AQUÍ</a></p>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/login.js"></script>
    </body>
</html>

    
    <?php

}

?>
