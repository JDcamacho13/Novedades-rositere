<?php
include_once 'dataBase.php';
include_once 'userSession.php';

function login($usuario, $password)
	{

		$DB = new DB();

		global $Sesion;

		$sql = "SELECT id,activacion,`password`,usuario,permisos,datos_registrados FROM usuarios WHERE usuario = '$usuario' || correo = '$usuario'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount() > 0){
			foreach( $query as $row){
				if($row['activacion'] == 1){
					
					$validaPassw = password_verify($password, $row['password']);

					if($validaPassw){

						lastSession($row['id']);
						$Sesion->setCurrentUser($row['usuario'],$row['permisos'],$row['id'],$row['datos_registrados']);

					}else{
						$errors = "La contraseña es incorrecta";
						return $errors;
					}

				}else{
					$errors = 'El usuario no esta activo';
					return $errors;
				}
			}
		}else{
			$errors = "El nombre de usuario o correo electrónico no existe";
			return $errors;
		}

		
    }
?>