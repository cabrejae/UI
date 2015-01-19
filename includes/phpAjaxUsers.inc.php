<?php
//Incluimos el archivo de funciones y conexión a base de datos
include('mainFunctions.inc.php');

// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios
sleep(2);
// Inicializamos variables de mensajes JSON
$respuestaOK= false;
$mensajeError = "No se Puede ejecutar la aplicación";
$contenidoOK = "";



$statusTipoOK = array("Masculino" => "btn-success",
					  "Femenino" => "btn-warning");


 // Validar conexión con la base de datos
$mysqli = new mysqli(server, user, pass, mainDataBase);
if (mysqli_connect_error()) {
	$errorDbConexion = true;
}else{
	$errorDbConexion = false;
	$mysqli -> query('SET NAMES "utf8"');
}

if($errorDbConexion == false){
	// Validamos que existan las variables post
	if(isset($_POST) && !empty($_POST)){
		switch ($_POST['accion']) {
			case 'addUser':
				//Armamos el query
				$query = sprintf("INSERT INTO tafiliados
					              SET afi_Nombre='%s', afi_Apellido='%s', emp_Id='%s'",
								  $mysqli->real_escape_string($_POST['afi_Nombre']),$_POST['afi_Apellido'],$_POST['emp_Id']);	
				
				// Ejecutamos el query
				$resultadoQuery = mysqli_query($mysqli,$query);
				//$resultadoQuery = $mysqli -> query($query);
				$id_userOK = $mysqli -> insert_id;

				//Subir Imagen
				//$file = $_FILES['archivo']['name'];
				if($resultadoQuery == true){
					$respuestaOK = true;
					$mensajeError = "Se ha agregado el registro correctamente";
					$contenidoOK = '
						<tr>
							<td>'.$_POST['afi_Nombre'].'</td>
							<td>'.$_POST['afi_Apellido'].'</td>
							<td>'.$_POST['emp_Nombre'].'</td>
							<td class="hide">'.$_POST['emp_Id'].'</td>
							<td class="centerTXT"><a data-accion="editar" class="btn btn-mini" href="'.$id_userOK.'">Editar</a> <a data-accion="eliminar" class="btn btn-mini" href="'.$id_userOK.'">Eliminar</a></td>
						<tr>
					';
//							<td class="centerTXT"><span class="btn btn-mini '.$statusTipoOK[$_POST['afi_Sexo']].'">'.$_POST['afi_Sexo'].'</span></td>
				}
				else{
					$mensajeError = "No se puede guardar el registro en la base de datos";
				}
				break;
			case 'editUser':
				//Armamos el query
				$query = sprintf("UPDATE tafiliados
					              SET afi_Nombre='%s', afi_Apellido='%s', emp_Id='%s'  
					              WHERE afi_Id=%d LIMIT 1",
								  $_POST['afi_Nombre'],$_POST['afi_Apellido'],$_POST['emp_Id'],$_POST['afi_Id']);	
				
				// Ejecutamos el query
				$resultadoQuery = mysqli_query($mysqli,$query);
				//$resultadoQuery = $mysqli -> query($query);

				//Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';
					$contenidoOK = consultaUsers($mysqli);
				}else{
					$mensajeError = 'No se ha actualizado el registro';
				}

			break;
			case 'eliminar':
				//Armamos el query
				$query = sprintf("DELETE FROM tafiliados
								 WHERE afi_Id=%d LIMIT 1",
								 $_POST['afi_Id']);

				// Ejecutamos el query
				$resultadoQuery = mysqli_query($mysqli,$query);
				//$resultadoQuery = $mysqli -> query($query);

				//Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';
					$contenidoOK = consultaUsers($mysqli);
					//$contenidoOK = 'prueba de contenidoOK';
				}else{
					$mensajeError = 'No se ha eliminado el registro';
				}
			break;
		
			default:
				$mensajeError = 'Esta acción no se encuentra disponible';

			break;

		}
	
	}
	else{
		$mensajeError = 'NO se puede ejecutar la aplicación';
	}

}
else{
	$mensajeError = 'No se puede establecer conexión con la base de datos';
}

mysqli_close($mysqli);
// Armamos array para convertir a JSON
$salidaJson = array("respuesta" => $respuestaOK,
					"mensaje" => $mensajeError,
					"contenido" => $contenidoOK);

echo json_encode($salidaJson);


?>
