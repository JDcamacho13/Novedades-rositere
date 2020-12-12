<?php

class UserSession{

    public function __construct(){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        } 
    }

    public function setCurrentUser($user,$permisos,$id){
        $_SESSION['usuario'] = $user;
        $_SESSION['permisos'] = $permisos;
        $_SESSION['InumeroD'] = $id;
    }

    public function getCurrentUser(){
        return $_SESSION['usuario'];
    }

    public function closeSession(){
        session_unset();
        session_destroy();
    }
}

?>