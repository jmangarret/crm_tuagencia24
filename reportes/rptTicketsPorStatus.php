<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte osTickets</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
include("librerias.php");
?>
</head>
<body>	
	<div id="container">
	<table align="center">
	<form>
		<tr class="listViewHeaders">
			<th><b>Tipo de Solicitud: </b><th><select id="solicitud" name="solicitud"><option value="">--Todos--</option></select>
		<tr class="listViewHeaders">
			<th><b>Asesor(a): </b><th><select id="asesor" name="asesor"><option value="">--Todos--</option></select>
		<tr class="listViewHeaders">
			<th><b>Fecha: </b><th><input id="date1" type="text" name="desde">
		
		<tr ><th colspan="2">
			<input type="button" Value="Consultar" id="submit"> 
			
 			<script type="text/javascript">
            $(document).ready(function () {                
                $('#date1').datepicker({
                    format: "dd/mm/yyyy"
                });  

              	$.ajax({	
              		data: '',					
					url: 'reportes/ostAsesoras.php',
					type: 'get',
					success: function(responses){						
						$("#asesor").append(responses);
					}
				});	
            	
            	$.ajax({	
              		data: '',					
					url: 'reportes/ostSolicitudes.php',
					type: 'get',
					success: function(responses){						
						$("#solicitud").append(responses);
					}
				});	
            	
                $('#submit').click(function() {		    		
		        	$.ajax({
					method: "GET",
					url: "reportes/genTicketsPorStatus.php",
					type : 'GET',
					dataType:"html",
					data: {"desde":$('#date1').val(), "asesor":$('#asesor').val(), "solicitud":$('#solicitud').val()},
					success: function(response){     
					     $('#resultado').html(response);
					  	}
					});									        	      
	    		});    

            });
        	</script>
	</form>
	</table>	
	</div>
	<?php
		include("genTicketsPorStatus.php");
	?>
</body>
</html>
