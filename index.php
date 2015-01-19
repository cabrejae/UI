<?php
// Omitir errores
ini_set("display_errors", false);
// Incluimos nustro script php de funciones y conexión a la base de datos
include('includes/mainFunctions.inc.php');

// llama la función para mostrar la lista de usuarios
$consultaUsuarios = consultaUsers();

// //Valido error en DB
if($errorDbConexion == true){
	$consultaUsuarios = '
		<tr id="sinDatos">
			<td colspan="5" class="centerTXT">ERROR AL CONECTAR CON LA BASE DE DATOS</td>
	   	</tr>';
}

// llama la función para mostrar la lista de empresas
$empresas = consultarEmpresas();

//Valido error en DB
if($errorDbConexion == true){

$empresas = '<tr id="sinDatos">
				<td colspan="5" class="centerTXT">ERROR AL CONECTAR CON LA BASE DE DATOS</td>
		   	</tr>';
}

?>
<!DOCTYPE html>
 
<html lang="es">
 
<head>
<title>:: Sistema de Gestión de Union Informatica ::</title>
<meta charset="utf-8" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<link type="text/css" href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />

<script type="text/javascript" src="js/jquery_ui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery_ui/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>

<script type="text/javascript" src="js/jquery-validation-1.10.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/localization/messages_es.js"></script>

<script type="text/javascript" src="js/mainJavaScript.js"></script>

</head>

<body>
		<div class="hide" id="agregarUser" Title="Agregar Afiliado">
	    	<form action="" method="post" id="formUsers" name="formUsers">
	    		<fieldset id="ocultos">
	    			<input type="hidden" id="accion" name="accion" class="{required:true}"/>
	    			<input type="hidden" id="afi_Id" name="afi_Id" class="{required:true}" value="0"/>
	    		</fieldset>
				<fieldset id="datosUser">
					<table>
						<tr>
							<td>Nombre</td>
							<td>Apellido</td>
							<td>Empresa</td>
						</tr>
						<tr>
							
							<td><input type="text" id="afi_Nombre" name="afi_Nombre" placeholder="Nombre" data-msg-required="El <b>Nombre</b> es Obligatorio" class="{required:true,maxlength:35} span3"/></td>
							<td><input type="text" id="afi_Apellido" name="afi_Apellido" placeholder="Apellido"  data-msg-required="El <b>Apellido</b> es Obligatorio" class="{required:true,maxlength:25} span3"/></td>
							<td class="hide"><input type="text" id="emp_Nombre" name="emp_Nombre" class="{required:true}" value=""></td>
							<td>
								<select id="emp_Id" name="emp_Id" class="{required:true	} span3">
									<option value="">Seleccione Una Opción</option>
                					<?php  echo $empresas ?>  
                				</select>
							</td>

						</tr>
						<tr>
							<td>DNI</td>
							<td>Sexo</td>
							<td>Estado Civil</td>
						</tr>
						<tr>
							<td><input type="text" id="afi_DNI" name="afi_DNI" placeholder="Número DNI"  data-msg-required="El <b>DNI</b> es Obligatorio" class="{required:false,maxlength:8} span3"/></td>
							<td>
								<select id="afi_Sexo" name="afi_Sexo" class="{required:false} span3">
									<option value="">Seleccione Una Opción</option>
									<option value="Masculino">Masculino</option>
									<option value="Femenino">Femenino</option>	        	
								</select>
							</td>
							<td>
								<select id="afi_EstadoCivil" name="afi_EstadoCivil"  data-msg-required="El <b>Estado Civil</b> es Obligatorio" class="{required:false} span3">
									<option value="">Seleccione Una Opción</option>
									<option value="Soltero">Soltero</option>
									<option value="Casado">Casado</option>	        	
									<option value="Divorciado">Divorciado</option>
									<option value="Separado">Separado</option>	
								</select>
							</td>
						</tr>
						<tr>
							<td>Fecha de Nacimiento</td>
							<td>CUIL</td>
							<td>Domicilio</td>
						</tr>
						<tr>
							<td><input type='text' id='afi_FechaNacimiento' name='afi_FechaNacimiento' data-msg-date="La <b>Fecha Nacimiento</b> es Invalida" readonly="readonly" data-msg-required="La <b>Fecha de Nacimiento</b> es Obligatoria" class="{date:true,required:false}"/></td>
							<td><input type="text" id="afi_CUIL" name="afi_CUIL" placeholder="CUIL"  data-msg-required="El <b>CUIL</b> es Obligatorio" class="{required:false} span3"/></td>
							<td><input type="text" id="afi_Domicilio" name="afi_Domicilio" placeholder="Domicilio" data-msg-required="El <b>Domicilio</b> es Obligatorio" class="{required:false,maxlength:40} span3"/></td>
							<td><input type="text" id="xxxxxx" name="xxxxxxxxxxx" placeholder="Empresa" class="{required:false,maxlength:20} span3"/></td>
						</tr>
						<tr>
							<td><input name="archivo" type="file" id="imagen" /><br/><br/></td>
        					<td><input type="button" value="Subir imagen" /><br/></td>
						</tr>
					</table>
				</fieldset>
				<fieldset id="btnAgregar" style="text-align:center;">
					<input type="submit" id="continuar" value="Continuar" />
				</fieldset>

				<fieldset id="ajaxLoader" class="ajaxLoader hide" >
					<img src="images/default-loader.gif">
					<p>Espere un momento...</p>
				</fieldset>
				<fieldset>
					<div id="muestra"></div>
				</fieldset>
	    </div>

	    <div id="dialog-borrar" title="Eliminar registro" class="hide">
			<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Este registro se borrará de forma permanente. ¿Esta seguro?</p>
		</div>
		
		<div id="wraper">
		    <section id="content">
		    	<div id="btnAddUser" class="center addUser">
		    		<button id="goNuevoUser" class="btn btn-inverse btn-small"><i class="icon-plus"></i>Agregar Afiliado</button>
		    	</div>
				<div id="listaOrganizadores">
					<table id="listadoUsers" class="table table-striped table-bordered table-hover table-condensed">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Empresa</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="listaUsuariosOK">
							<?php echo $consultaUsuarios ?>
						</tbody>
					</table>
				</div>

		    </section>
 		</div>
  		<footer>
	        Powered by Luis Fernando Cázares Bulbarela || 2012
		</footer>
</body>
</html>