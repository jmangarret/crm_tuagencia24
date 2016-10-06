<link href="libraries/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="https://rawgithub.com/hayageek/jquery-upload-file/master/css/uploadfile.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://rawgithub.com/hayageek/jquery-upload-file/master/js/jquery.uploadfile.min.js"></script>
<h3><img class="alignMiddle" src="themes/images/icono_user.png" title="Adjuntar Pasaporte"> Subir Archivo</h3>
<?php
include_once("config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);
$module=$_REQUEST["module"];
$id=$_REQUEST["id"];

if ($module=="Boletos"){
	$sql="SELECT passenger FROM vtiger_boletos WHERE boletosid=".$id;
	$qry=mysql_query($sql);
	$pasajero=mysql_result($qry, 0);
	echo "<strong>Pasajero: </strong>".$pasajero;
	?>
	<script>
	$(document).ready(function() {
	    $("#fileuploader").uploadFile({
	    	url:"modules/Boletos/uploadPasaporte.php",
	        fileName:"myfile",
	        formData: {"id":"<?php echo $id; ?>"},
	        allowDuplicates: false,
	        multiple: false,
	        multiple: false,
	        showDelete:true,
	        maxFileCount: 1,
	        maxFileCountErrorStr:"Cant Maxima de Archivos: ",	
	        duplicateErrorStr:"No permitido. Archivo ya existe.",
	        
	    });
	});
	</script>
	<?
}

?>
<div id="fileuploader"></div>