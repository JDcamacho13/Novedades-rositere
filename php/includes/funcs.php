<?php
include_once 'dataBase.php';
include_once 'userSession.php';

$Sesion = new UserSession;

function login($usuario, $password){

	$DB = new DB();

	global $Sesion;

	$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
	$query = $DB->connect()->query($sql);

	if($query->rowCount() > 0){
		foreach( $query as $row){
			
				
			$validaPassw = password_verify($password, $row['password']);

			if($validaPassw){

				$Sesion->setCurrentUser($row['usuario'],$row['permisos'],$row['id'],$row['nombre'],$row['apellido']);

			}else{
				$errors = "La contraseña es incorrecta";
				return $errors;
			}

			
		}
	}else{
		$errors = "El nombre de usuario o correo electrónico no existe";
		return $errors;
	}

		
}


function registro($nombre, $apellido, $usuario, $password){
	$DB = new DB();
		
		try{
			$query = $DB->connect()->query("INSERT INTO `usuarios` ( `nombre`,`apellido`,`usuario`, `password`,
			`permisos`) VALUES ('$nombre', '$apellido', '$usuario', '$password', 0)");

			return true;
		}catch(exeption $e){

			return false;
		}
}

function usuarioExiste($usuario){
	$DB = new DB();

	$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
	$query = $DB->connect()->query($sql);

	if($query->rowCount() > 0){
		return true;
	}
	else{
		return false;
	}
}

function hashPassword($password){
	$hash = password_hash($password, PASSWORD_DEFAULT);
	return $hash;
}

function resultBlock($errors){

	$resultado;

	if(count($errors) > 0)
	{
		$resultado = "<div id='error' class='alert alert-danger error' role='alert'><center>
		<h2>Error</h2><ul class='list'>";
		foreach($errors as $error)
		{
			$resultado .= "<li>".$error."</li>";
		}
		$resultado .= "</ul>";
		$resultado .= "</center></div>";

		return $resultado;
	}
}

function productoExiste($nombre){
	$DB = new DB();

	$sql = "SELECT * FROM productos WHERE nombre = '$nombre'";
	$query = $DB->connect()->query($sql);

	if($query->rowCount() > 0){
		return true;
	}
	else{
		return false;
	}
}


function agregarProducto($nombre, $precio, $rutaFoto){
	$DB = new DB();
		
		try{
			$query = $DB->connect()->query("INSERT INTO `productos` ( `nombre`,`precio_dolares`,`foto`) 
			VALUES ('$nombre', '$precio', '$rutaFoto')");

			return true;
		}catch(exeption $e){

			return false;
		}
}

?>