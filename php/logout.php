<?php
    include_once 'includes/userSession.php';


    $userSession = new userSession();

    $userSession->closeSession();

    header("Location: https://".$_SERVER['SERVER_NAME']."/index.php");
?>
