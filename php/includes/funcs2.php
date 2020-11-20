<?php

    include_once 'dataBase.php';
    include_once 'userSession.php';

    $Sesion = new UserSession();

    function registroInscripcion($nombre, $apellido, $email, $telefono, $inicio, $fechapago, $referencia, $origen, $destino, $monto, $moneda, $concepto, $emailconfirm, $observaciones,$comprobante,$id_usuario){

        $DB = new DB();

        try{

			$busqueda = $DB->connect()->query("SELECT id FROM inscripciones ORDER BY id DESC LIMIT 1");

			while($r = $busqueda->fetch()){
                $id_inscripcion = $r['id']+1;
            }

            $query = $DB->connect()->query("INSERT INTO inscripciones (nombre,apellido,email,telefono,inicio,fechapago,referencia,origen,destino,monto,moneda,concepto,emailconfirm,observaciones,comprobante, estado) 
			VALUES ('$nombre', '$apellido', '$email', '$telefono', '$inicio', '$fechapago', '$referencia', '$origen', '$destino', '$monto', '$moneda', '$concepto', '$emailconfirm', '$observaciones', '$comprobante', 1)");

			$query = $DB->connect()->query("INSERT INTO inscripcion_usuario (id_usuario,id_inscripcion) VALUES ('$id_usuario', '$id_inscripcion')");
        }catch(exeption $e){

            return 0;
        }
    
    
    }

    function buscarDatosCuenta($id){

        $DB = new DB();

        $sql = "SELECT * FROM usuarios WHERE id='$id'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

            $reslut;

			while($r = $query->fetch()){
                $result = array($r['usuario'],$r['correo']);
            }
            
            return $result;
			
		}
    }

    function actualizarUsuario($id, $usuario){
        $DB = new DB();

		try{
			$sql = "UPDATE usuarios SET usuario = '$usuario' WHERE id = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}
    }

    function actualizarCorreo($id, $correo){
        $DB = new DB();

		try{
			$sql = "UPDATE usuarios SET correo = '$correo' WHERE id = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}
    }

    function registroFotoEmpresa($id, $archivo){

        $DB = new DB();

		try{
			$sql = "UPDATE fotos_cuentas SET foto_e = '$archivo' WHERE usuario = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}

    }

    function registroFotoRepresentante($id, $archivo){

        $DB = new DB();

		try{
			$sql = "UPDATE fotos_cuentas SET foto_r = '$archivo' WHERE usuario = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}

    }

    function buscarFotosCuenta($id){

        $DB = new DB();

        $sql = "SELECT foto_r,foto_e FROM fotos_cuentas WHERE usuario='$id'";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

            $reslut;

			while($r = $query->fetch()){
                $result = array($r['foto_r'],$r['foto_e']);
            }
            
            return $result;
			
		}
    }

    function errorsPrint($errors){

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

	function buscarPagos($busqueda){

		$DB = new DB();

		if($busqueda == 0){
			$sql = "SELECT inscripciones.*, usuarios.usuario FROM inscripciones JOIN inscripcion_usuario ON inscripcion_usuario.id_inscripcion = inscripciones.id JOIN usuarios ON usuarios.id = inscripcion_usuario.id_usuario ORDER BY inscripciones.id DESC";
			$query = $DB->connect()->query($sql);

			if($query->rowCount()){

				$result = array();
	
				while($r = $query->fetch()){
			
					array_push($result, array($r['id'],$r['nombre'],$r['apellido'],$r['email'],$r['telefono'],
					$r['inicio'], $r['fechapago'],$r['referencia'],$r['origen'],$r['destino'],$r['monto'],$r['moneda']
					,$r['concepto'],$r['emailconfirm'],$r['observaciones'],$r['comprobante'],$r['estado'],$r['usuario']));
		
				}
				
				return $result;
				
			}else{
				$result = array();
				return $result;
			}
		}else{
			$sql = "SELECT inscripciones.*, usuarios.usuario FROM inscripciones JOIN inscripcion_usuario ON inscripcion_usuario.id_inscripcion = inscripciones.id JOIN usuarios ON usuarios.id = inscripcion_usuario.id_usuario WHERE estado = '$busqueda' ORDER BY inscripciones.id DESC";
			$query = $DB->connect()->query($sql);

			if($query->rowCount()){

				$result = array();
	
				while($r = $query->fetch()){
			
					array_push($result, array($r['id'],$r['nombre'],$r['apellido'],$r['email'],$r['telefono'],
					$r['inicio'], $r['fechapago'],$r['referencia'],$r['origen'],$r['destino'],$r['monto'],$r['moneda']
					,$r['concepto'],$r['emailconfirm'],$r['observaciones'],$r['comprobante'],$r['estado'],$r['usuario']));
		
				}
				
				return $result;
				
			}else{
				$result = array();
				return $result;
			}
		}

	}

	function actualizarEstadoPago($id, $cambio){
		$DB = new DB();

		try{
			$sql = "UPDATE inscripciones SET estado = '$cambio' WHERE id = '$id'";
			$query = $DB->connect()->query($sql);

			return true;
		}catch (Exception $e) {
			return false;
		}
	}

	function buscarPagosUsuario($id){
		$DB = new DB();
		
		$sql = "SELECT inscripciones.estado, inscripciones.concepto, inscripciones.fechapago, inscripciones.referencia, inscripciones.comprobante FROM inscripciones JOIN inscripcion_usuario ON inscripcion_usuario.id_inscripcion = inscripciones.id JOIN usuarios ON usuarios.id = inscripcion_usuario.id_usuario WHERE usuarios.id = '$id' ORDER BY inscripciones.id DESC";
		$query = $DB->connect()->query($sql);

		if($query->rowCount()){

			$result = array();

			while($r = $query->fetch()){
		
				array_push($result, array($r['estado'],$r['concepto'],$r['fechapago'],$r['referencia'],$r['comprobante']));
	
			}
			
			return $result;
			
		}else{
			$result = array();
			return $result;
		}
	}
?>