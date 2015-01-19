<?php

// Constantes conexión con la base de datos

define("server", 'localhost');
define("user", 'root');
define("pass", 'root');
define("mainDataBase", 'ui');
$errorDbConexion = true;

// Función para extraer el listado de usurios
function consultaUsers(){
//		$statusTipoOK = array("Masculino" => "btn-success",
//					  "Femenino" => "btn-warning");

	global 	$errorDbConexion;

	$errorDbConexion = false;
	$salida = '';
	$mysqli = new mysqli(server, user, pass, mainDataBase);
	
	if (mysqli_connect_error()) {
	    $errorDbConexion = true;
	}else{
		$errorDbConexion = false;
		$mysqli -> query('SET NAMES "utf8"');
		$consulta = mysqli_query($mysqli,"CALL sp_afiliados");

		if($consulta -> num_rows != 0){
		
			// convertimos el objeto
			while($listadoOK = $consulta -> fetch_assoc())
			{
				$salida .= '
					<tr>
						<td>'.$listadoOK['afi_Nombre'].'</td>
						<td>'.$listadoOK['afi_Apellido'].'</td>
						<td>'.$listadoOK['emp_Nombre'].'</td>
						<td class="hide">'.$listadoOK['emp_Id'].'</td>
						<td class="centerTXT"><a data-accion="editar" class="btn btn-mini" href="'.$listadoOK['afi_Id'].'">Editar</a> <a data-accion="eliminar" class="btn btn-mini" href="'.$listadoOK['afi_Id'].'">Eliminar</a></td>
					<tr>
				';
				//	<td>'.$listadoOK['emp_Nombre'].'</td>	<td class="centerTXT"><span class="btn btn-mini '.$statusTipoOK[$listadoOK['afi_Sexo']].'">'.$listadoOK['afi_Sexo'].'</span></td>

				//		<td class="centerTXT"><span class="btn btn-mini '.$statusTipo[$listadoOK['afi_Sexo']].'">'.$listadoOK['afi_Sexo'].'</span></td>

			}
		}
		else{
			$salida = '
				<tr id="sinDatos">
					<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE DATOS'.mysqli_error($mysqli).'</td>
	   			</tr>
			';
		}
		mysqli_close($mysqli);
	}	
	return $salida;
}

//Función para extrer las empresas para llenar el select
function consultarEmpresas(){
	global 	$errorDbConexion;
	$salida = '';
	$mysqli = new mysqli(server, user, pass, mainDataBase);
	if (mysqli_connect_error()) {
	    $errorDbConexion = true;
	}else{
		$errorDbConexion = false;
		$mysqli -> query('SET NAMES "utf8"');
		$consultaEmpresas= mysqli_query($mysqli ,"SELECT emp_Id,emp_Nombre FROM tempresas ORDER BY emp_Nombre ASC");
	
		if($consultaEmpresas -> num_rows != 0){
		
			// convertimos el objeto
			while($listadoOK = $consultaEmpresas -> fetch_assoc())
			{
				$salida .= '<option value="'.$listadoOK['emp_Id'].'">'.$listadoOK['emp_Nombre'].'</option>';
			}
	
		}
		else{
			$salida = '
				<tr id="sinDatos">
					<td colspan="5" class="centerTXT">PILU NO HAY REGISTROS EN LA BASE DE DATOS='.mysqli_error($mysqli).'</td>
	   			</tr>
			';
		}
		mysqli_close($mysqli);
	}
	return $salida;
}










	
/*	if(defined('server') && defined('user') && defined('pass') && defined('mainDataBase'))
	{
		// Constantes conexión con la base de datos

		//Conexión con la base de datos
        $mysqli = new mysqli(server, user, pass, mainDataBase);
	
		// Verificamos si hay error al conectar
		if (mysqli_connect_error()) {
	    $errorDbConexion = true;
	}
	//Evitando problemas con acentos
	$errorDbConexion = false;
	$mysqli -> query('SET NAMES "utf8"');
	return $mysqli;
}

*/

?>