<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Novedades Rositere</title>
        <link href="css/style-login.css" rel='stylesheet' type='text/css' />
    </head>
    <body>
        <div id="errores"></div>
        <div class="login-page">
            <div class="form">
                <h2>Iniciar Sesión</h2>
                <form class="register-form">
                <input type="text" id="usuario" placeholder="Nombre de Usuario"/>
                <input type="password" id="password" placeholder="Clave"/>
                <input type="button" id="submit" value="Enviar"> <br />
                <p>¿No tienes una cuenta? Registrate <a href="registro.php">AQUÍ</a></p>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/login.js"></script>
    </body>
</html>
