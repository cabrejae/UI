// id de user global
var idUser_ok = 0;
var accion_ok = 'noAccion';

 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);


$(function(){
		
    /*$("#imagen").change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#imagen")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
    });*/
		$("#afi_FechaNacimiento").datepicker({
			changeMonth: true,
      		changeYear: true,
      		dateFormat: "dd/mm/yy"
		});

		// creación de ventana con formulario con jquery ui
		$('#agregarUser').dialog({
			autoOpen: false,
			modal:true,
			width:950,
			height:'auto',
			resizable: false,
			close:function(){
				$('#formUsers fieldset > span').removeClass('error').empty();
				$('#formUsers input[type="text"]').val('');
		    	$('#formUsers select > option').removeAttr('selected');
		    	//$('#id_user').val('0');
			}
		});

		// Diálogo confirmación de eliminación
		$('#dialog-borrar').dialog({
			autoOpen: false,
			modal:true,
			width:350,
			height:'auto',
			resizable: false,
			buttons: {
				Si: function() {
					$.ajax({
		            beforeSend: function(){
		                
		            },
		            cache: false,
		            type: "POST",
		            dataType: "json",
		            url:"includes/phpAjaxUsers.inc.php",
		            data:"accion=" + accion_ok + "&afi_Id=" + idUser_ok + "&id=" + Math.random(),
		            success: function(response){
		            	// Validar mensaje de error
		            	if(response.respuesta == false){
		            		alert(response.mensaje);
		            	}
		            	else{
		            		// si es exitosa la operación
		                	$('#dialog-borrar').dialog('close');

		                	$('#listaUsuariosOK').empty();
		                	
		                	$('#listaUsuariosOK').append(response.contenido);

						}

		            },
		            error:function(){
		                alert('ERROR GENERAL DEL SISTEMA, INTENTE MAS TARDE');
		            }
		        });	
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});

		// funcionalidad del botón que abre el formulario
		$('#goNuevoUser').on('click',function(){
			// Asignamos valor a la variable acción
			$('#accion').val('addUser');

			// Abrimos el Formulario
			$('#agregarUser').dialog({
				title:'Agregar Afiliado',
				autoOpen:true
			});
		});

		// Validar Formulario
		$('#formUsers').validate({

		    submitHandler: function(){
		        
		        var str = $('#formUsers').serialize();

		        //alert(str);

		        $.ajax({
		            beforeSend: function(){
		                $('#formUsers .ajaxLoader').show();
		            },
		            cache: false,
		            type: "POST",
		            dataType: "json",
		            url:"includes/phpAjaxUsers.inc.php",
		            data:str + "&id=" + Math.random(),
		            success: function(response){

		            	// Validar mensaje de error
		            	if(response.respuesta == false){
		            		alert(response.mensaje);
		            	}
		            	else{

		            		// si es exitosa la operación
		                	$('#agregarUser').dialog('close');

		                	// alert(response.contenido);
		                	
		                	if($('#sinDatos').length){
		                		$('#sinDatos').remove();
		                	}
		                	
		                	// Validad tipo de acción
		                	if($('#accion').val() == 'editUser'){
		                		$('#listaUsuariosOK').empty();
		                	}

		                	$('#listaUsuariosOK').append(response.contenido);

						}

		            	$('#formUsers .ajaxLoader').hide();

		            },
		            error:function(){
		                alert('ERROR GENERAL DEL SISTEMA, INTENTE MAS TARDE');
		            }
		        });

		        return false;

		    },
		    errorPlacement: function(error, element) {
		    //    error.appendTo(element.prev("span").append());
		   	//errorPlacement: function(error, element) {
           		var p = $("<p />");
           		var j = $("<p />");
           		//p.html(element.attr("placeholder"));    
           		p.html(error);  
           		$("#muestra").append(p);

           		
           		  
           		//$("#muestra").append(p);      
		    	
		    }

		});


		// Edición de Registros
		$('body').on('click','#listaUsuariosOK a',function (e){
			e.preventDefault();

			//alert($(this).attr('data-accion'));
			

			//alert ($(this).parent().parent().children('td:eq(3)').text());

			// Id Usuario

			idUser_ok = $(this).attr('href');
			accion_ok = $(this).attr('data-accion');

			$('#afi_Id').val(idUser_ok);

			if( accion_ok == 'editar'){
				// Valor de la acción
				$('#accion').val('editUser');

				// Llenar el formulario con los datos del registro seleccionado
				$('#afi_Nombre').val($(this).parent().parent().children('td:eq(0)').text());
				$('#afi_Apellido').val($(this).parent().parent().children('td:eq(1)').text());
				$('#emp_Id option[value='+ $(this).parent().parent().children('td:eq(3)').text() +']').attr('selected',true);

				// Seleccionar status
				//$('#usr_status option[value='+ $(this).parent().parent().children('td:eq(3)').text() +']').attr('selected',true);

				// Abrimos el Formulario
				$('#agregarUser').dialog({
					title:'Editar Afiliado',
					autoOpen:true
				});

			}else if($(this).attr('data-accion') == 'eliminar'){

				$('#dialog-borrar').dialog('open');
			}
				

		});
		$("#emp_Id").change(function(){
			// alert($('#emp_Id option:selected').html());
           // alert($('select[id=emp_Id]').text());
            //$('#emp_Nombre').val($('this:selected').text());
			$('#emp_Nombre').val($('#emp_Id option:selected').html());
		});
});