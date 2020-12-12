<?php
    include_once 'includes/userSession.php';


    $userSession = new userSession();

    $userSession->closeSession();

    header("Location: http://".$_SERVER['SERVER_NAME']."/novedades-rositere/index.php");
?>