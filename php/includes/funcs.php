<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	include_once 'dataBase.php';
	include_once 'userSession.php';

	$Sesion = new UserSession();
	
	function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}
	
	function minMax($min, $max, $valor){
		if(strlen(trim($valor)) < $min)
		{
			return true;
		}
		else if(strlen(trim($valor)) > $max)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function usuarioExiste($usuario)
	{
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
	
	function emailExiste($email)
	{	
		$DB = new DB();

		$sql = "SELECT * FROM usuarios WHERE correo = '$email'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function generateToken()
	{
		$gen = md5(uniqid(mt_rand(), false));	
		return $gen;
	}
	
	function hashPassword($password) 
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}
	
	function resultBlock($errors){

		$resultado;

		if(count($errors) > 0)
		{
			$resultado = "<div id='error' class='alert alert-danger' role='alert'><center>
			<ul class='list-unstyled'>";
			foreach($errors as $error)
			{
				$resultado .= "<li>".$error."</li>";
			}
			$resultado .= "</ul>";
			$resultado .= "</center></div>";

			return $resultado;
		}
	}
	
	function registraUsuario($usuario, $pass_hash, $email, $activo, $token){
		
		$DB = new DB();
		
		try{
			$query = $DB->connect()->query("INSERT INTO `usuarios` ( `usuario`, `password`, `correo`, 
			`activacion`, `token`,`fecha_registro`) VALUES ('$usuario', '$pass_hash', '$email', '$activo', '$token', NOW())");
			
			$sql = "SELECT id FROM usuarios WHERE usuario = '$usuario'";
			$query = $DB->connect()->query($sql);

			if($query->rowCount() > 0){
				foreach( $query as $row){

					$idUsuario = $row['id'];

					$query = $DB->connect()->query("INSERT INTO `fotos_cuentas` ( `usuario`, `foto_r`, `foto_e`)
					VALUES ('$idUsuario', 0 , 0);");

					return $row['id'];
				}
			}
			else{
				return false;
			}

			return 1;
		}catch(exeption $e){

			return 0;
		}
		
			
		
	}
	
	function enviarEmail($email, $asunto, $cuerpo){

		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server tings
			$mail->SMTPDebug = 0;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'correorie@gmail.com';                     // SMTP username
			$mail->Password   = 'rieprogramacion2020';                               // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->setFrom('correorie@gmail.com', 'RIE');
			$mail->addAddress($email);     // Add a recipient

			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->CharSet = 'UTF-8';
			$mail->Subject = $asunto;
			$mail->Body    = $cuerpo;

			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	function validaIdToken($id, $token){
		
		$DB = new DB();		

		$sql = "SELECT activacion FROM usuarios WHERE id = '$id' AND token = '$token' LIMIT 1";
		$query = $DB->connect()->query($sql);

		if($query->rowCount() > 0){
			foreach( $query as $row){
				if($row['activacion'] == 1){
					$msg = "La cuenta ya se activo anteriormente.";
				}else{
					if(activarUsuario($id)){

						$msg = 'Cuenta activada.';

					}else {
						$msg = 'Error al Activar Cuenta';

					}
				}
			}
		}else{
			$msg = 'No existe el registro para activar.';
		}

		return $msg;
	}
	
	function activarUsuario($id){

		$DB = new DB();

		try{
			$sql = "UPDATE usuarios SET activacion = 1 WHERE id = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}


	}
	
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
	
	function lastSession($id)
	{
		$DB = new DB();

		$sql = "UPDATE usuarios SET last_session=NOW(), token_password='', password_request=0  WHERE id = '$id'";
		$query = $DB->connect()->query($sql);
	}
	
	function isActivo($usuario)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
		$stmt->bind_param('ss', $usuario, $usuario);
		$stmt->execute();
		$stmt->bind_result($activacion);
		$stmt->fetch();
		
		if ($activacion == 1)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}	
	
	function generaTokenPass($user_id)
	{
		$DB = new DB();
		
		$token = generateToken();

		$sql = "UPDATE usuarios SET token_password = '$token', password_request=1 WHERE id = '$user_id'";
		$query = $DB->connect()->query($sql);
		
		return $token;
	}
	
	function getValor($campo, $campoWhere, $valor)
	{
		$DB = new DB();

		$sql = "SELECT $campo FROM usuarios WHERE $campoWhere = '$valor' LIMIT 1";
		$query = $DB->connect()->query($sql);

		if($query->rowCount() > 0){
			foreach( $query as $row){
				
				return $row[$campo];
				
			}
		}else{
			return null;
		}
	}
	
	function getPasswordRequest($id)
	{
		global $mysqli;
		
		$stmt = $mysqli->prepare("SELECT password_request FROM usuarios WHERE id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($_id);
		$stmt->fetch();
		
		if ($_id == 1)
		{
			return true;
		}
		else
		{
			return null;	
		}
	}
	
	function verificaTokenPass($user_id, $token){

		$DB = new DB();

		$sql = "SELECT activacion FROM usuarios WHERE id = '$user_id' AND token_password = '$token' AND password_request = 1 LIMIT 1";
		$query = $DB->connect()->query($sql);

		if($query->rowCount() > 0){
			foreach( $query as $row){
				
				if($row['activacion'] == 1){

					return true;

				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
	
	function cambiaPassword($password, $user_id, $token){
		
		$DB = new DB();

		try{

			$sql = "UPDATE `usuarios` SET `password` = '$password', `token_password` = '', `password_request` = 0 WHERE `usuarios`.`id` = '$user_id'";

			$query = $DB->connect()->query($sql);

			return true;

		}catch(exeption $e){
			return false;
		}

	}

	function buscarAutocompletar($texto, $tipo_busqueda, $status){

		$res= array();
		$id = array();
		$DB = new DB();

		$texto.='%';

		$tabla;

		if($tipo_busqueda == 'cedula'){
			$tabla = 'representantelegal';
			$buscar = 'cedula';
		}
		if($tipo_busqueda == 'usuario'){
			$tabla = 'usuarios';
			$buscar = 'usuario';
		}
		if($tipo_busqueda == 'nombreempresa'){
			$tabla = 'empresa';
			$buscar = 'nombreempresa';
		}

		$sql = "SELECT id FROM $tabla WHERE UPPER($tipo_busqueda) LIKE UPPER('$texto')";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

			while($r = $query->fetch()){

				array_push($id, $r['id']);

			}
		}

		foreach($id as $key){

			$sql = "SELECT * FROM usuarios,empresa,representantelegal WHERE usuarios.id='$key' AND empresa.id='$key' AND representantelegal.id='$key'";
			$query = $DB->connect()->query($sql);

			if(!$status){
				if($query->rowCount()){

					while($r = $query->fetch()){
						array_push($res, $r[$buscar]);
					}
				}
			}else{

				if($query->rowCount()){

					while($r = $query->fetch()){
			
						array_push($res, array($r['id'],$r['usuario'],$r['correo'],$r['last_session'],$r['fecha_registro'], $r['nacion'],$r['cedula'],$r['primernombre'],$r['segundonombre'],$r['primerapellido'],$r['segundoapellido'],$r['nivel'],$r['nacimiento'],$r['civil'],$r['email'],$r['instagram'],$r['facebook'],$r['rif'],$r['ciudad'],$r['nombreempresa'],
						$r['actividad'],$r['emailempresa'],$r['antiguedad'],$r['oficina'],$r['telefono1']
						,$r['telefono2'],$r['direccionfiscal'],$r['instagramempresa'], $r['facebookempresa'],
						$r['descripcion'], $r['motivo'],$r['areaorientacion'],$r['interes'],$r['marketingdigital'],
						$r['administrativo'],$r['juridico'],$r['financiera'],$r['coaching'],$r['presenciaweb']
						,$r['alianzas'],$r['capacitacion'],$r['videotutoriales'], $r['tecnologico'],$r['otrasexperiencias'],
						$r['otras']));
			
					}
				}
			
			}


		}
		
		return $res;

	}

	function buscarEntreFechas($fecha1, $fecha2){

		$res= array();

		$DB = new DB();

		$id = array();

		$sql = "SELECT id FROM `usuarios` WHERE fecha_registro BETWEEN '$fecha1' AND '$fecha2'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

			while($r = $query->fetch()){

				array_push($id, $r['id']);
				
			}
		}

		foreach($id as $key){

			$sql = "SELECT *, date_format(last_session, '%d/%m/%Y %r'),date_format(fecha_registro, '%d/%m/%Y') FROM usuarios,representantelegal,empresa WHERE empresa.id = '$key' AND representantelegal.id='$key' AND usuarios.id='$key'";
			$query = $DB->connect()->query($sql);

			if($query->rowCount()){

				while($r = $query->fetch()){

					array_push($res, array($r['id'],$r['usuario'],$r['correo'],$r["date_format(last_session, '%d/%m/%Y %r')"],$r["date_format(fecha_registro, '%d/%m/%Y')"], $r['nacion'],$r['cedula'],$r['primernombre'],$r['segundonombre'],$r['primerapellido'],$r['segundoapellido'],$r['nivel'],$r['nacimiento'],$r['civil'],$r['email'],$r['instagram'],$r['facebook'],$r['rif'],$r['ciudad'],$r['nombreempresa'],
						$r['actividad'],$r['emailempresa'],$r['antiguedad'],$r['oficina'],$r['telefono1']
						,$r['telefono2'],$r['direccionfiscal'],$r['instagramempresa'], $r['facebookempresa'],
						$r['descripcion'], $r['motivo'],$r['areaorientacion'],$r['interes'],$r['marketingdigital'],
						$r['administrativo'],$r['juridico'],$r['financiera'],$r['coaching'],$r['presenciaweb']
						,$r['alianzas'],$r['capacitacion'],$r['videotutoriales'], $r['tecnologico'],$r['otrasexperiencias'],
						$r['otras']));
				}
			}
		}	
		
		return $res;

	}
	
	function registraRepresentante($nacion, $cedula, $primernombre, $segundonombre, $primerapellido, $segundoapellido, $nivel, $nacimiento, $civil, $email, $instagram, $facebook ,$id){
		
		$DB = new DB();
		
		try{
			$query = $DB->connect()->query("INSERT INTO representantelegal (id,nacion,cedula,primernombre,segundonombre,primerapellido,segundoapellido,nivel,nacimiento,civil,email,instagram,facebook) 
            VALUES ('$id','$nacion', '$cedula', '$primernombre', '$segundonombre', '$primerapellido', '$segundoapellido', '$nivel', '$nacimiento', '$civil', '$email', '$instagram', '$facebook')");

			$sql = "SELECT * FROM `empresa` WHERE id='$id'";
			$query = $DB->connect()->query($sql);

			if($query->rowCount()){

				$sql = "UPDATE usuarios SET datos_registrados = 1 WHERE id = '$id'";
				$query = $DB->connect()->query($sql);

				$_SESSION['datosR'] = 1;

				header('Location: '. $_SERVER['name'].'/RIE/index.php');
			}
			else{
				header('Location: '. $_SERVER['name'].'/RIE/empresa.php');
			}
			return 1;
		}catch(exeption $e){

			return 0;
		}
		
			
		
	}


	function registraEmpresa($id,$rif, $ciudad, $nombreempresa, $actividad, $emailempresa, $antiguedad, $oficina, $telefono1, $telefono2, $direccionfiscal, $instagramempresa, $facebookempresa, $descripcion, $motivo, $areaorientacion, $interes, $marketingdigital, $administrativo, $juridico, $financiera, $coaching, $presenciaweb, $alianzas, $capacitacion, $videotutoriales, $tecnologico, $otrasexperiencias, $otras){

		$DB = new DB();
		
		try{
			$query = $DB->connect()->query("INSERT INTO empresa (id,rif,ciudad,nombreempresa,actividad,emailempresa,antiguedad,oficina,telefono1,telefono2,direccionfiscal,instagramempresa,facebookempresa,descripcion,motivo,areaorientacion,interes,marketingdigital,administrativo,juridico,financiera,coaching,presenciaweb,alianzas,capacitacion,videotutoriales,tecnologico,otrasexperiencias,otras) 
			VALUES ('$id','$rif', '$ciudad', '$nombreempresa', '$actividad', '$emailempresa', '$antiguedad', '$oficina', '$telefono1', '$telefono2', '$direccionfiscal', '$instagramempresa', '$facebookempresa', '$descripcion', '$motivo', '$areaorientacion', '$interes', '$marketingdigital', '$administrativo', '$juridico', '$financiera', '$coaching', '$presenciaweb', '$alianzas', '$capacitacion', '$videotutoriales', '$tecnologico', '$otrasexperiencias', '$otras')");
			
			$sql = "SELECT * FROM `representantelegal` WHERE id='$id'";
			$query = $DB->connect()->query($sql);

			if($query->rowCount()){

				$sql = "UPDATE usuarios SET datos_registrados = 1 WHERE id = '$id'";
				$query = $DB->connect()->query($sql);

				$_SESSION['datosR'] = 1;

				header('Location: '. $_SERVER['name'].'/RIE/index.php');
			}
			else{
				header('Location: '. $_SERVER['name'].'/RIE/empresa.php');
			}
			return 1;

			return 1;
		}catch(exeption $e){

			return 0;
		}


	}

	function buscarParaReporte($key){

		$DB = new DB();
		$res = array();

		$sql = "SELECT * FROM usuarios,empresa,representantelegal WHERE usuarios.id='$key' AND empresa.id='$key' AND representantelegal.id='$key'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

			while($r = $query->fetch()){
			
				array_push($res, array($r['id'],$r['usuario'],$r['correo'],$r['last_session'],$r['fecha_registro'], $r['nacion'],$r['cedula'],$r['primernombre'],$r['segundonombre'],$r['primerapellido'],$r['segundoapellido'],$r['nivel'],$r['nacimiento'],$r['civil'],$r['email'],$r['instagram'],$r['facebook'],$r['rif'],$r['ciudad'],$r['nombreempresa'],
				$r['actividad'],$r['emailempresa'],$r['antiguedad'],$r['oficina'],$r['telefono1']
				,$r['telefono2'],$r['direccionfiscal'],$r['instagramempresa'], $r['facebookempresa'],
				$r['descripcion'], $r['motivo'],$r['areaorientacion'],$r['interes'],$r['marketingdigital'],
				$r['administrativo'],$r['juridico'],$r['financiera'],$r['coaching'],$r['presenciaweb']
				,$r['alianzas'],$r['capacitacion'],$r['videotutoriales'], $r['tecnologico'],$r['otrasexperiencias'],
				$r['otras']));
	
			}
			
		}
		
		return $res;

	}

?>

