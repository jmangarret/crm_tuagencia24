<link href="libraries/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="libraries/jquery/uploadfile/css/uploadfile.css" rel="stylesheet">
<script src="libraries/jquery/jquery.min.js"></script>
<script src="libraries/jquery/uploadfile/js/jquery.uploadfile.min.js"></script>
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
$user=$_REQUEST["user"];
///FUNCION PARA CAPTURAR RUTA DE UPLOADS DEL CRM
function initStorageFileDirectory() {
	$filepath = 'storage/';

	$year  = date('Y');
	$month = date('F');
	$day   = date('j');
	$week  = '';

	if (!is_dir($filepath . $year)) {
		//create new folder
		mkdir($filepath . $year);
	}

	if (!is_dir($filepath . $year . "/" . $month)) {
		//create new folder
		mkdir($filepath . "$year/$month");
	}

	if ($day > 0 && $day <= 7)
		$week = 'week1';
	elseif ($day > 7 && $day <= 14)
		$week = 'week2';
	elseif ($day > 14 && $day <= 21)
		$week = 'week3';
	elseif ($day > 21 && $day <= 28)
		$week = 'week4';
	else
		$week = 'week5';

	if (!is_dir($filepath . $year . "/" . $month . "/" . $week)) {
		//create new folder
		mkdir($filepath . "$year/$month/$week");
	}

	$filepath = $filepath . $year . "/" . $month . "/" . $week . "/";

	return $filepath;
}
$output_dir = initStorageFileDirectory();

//INICIO DE REQUESTS
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
	        formData: {
	        	"id":"<?php echo $id; ?>",
	        	"user":"<?php echo $user; ?>",
	        	"ruta":"<?php echo $output_dir; ?>",
	        },
	        allowDuplicates: false,
	        multiple: false,	        
	        showDelete:true,
	        showPreview: true,
	        showProgress: true,
	        previewHeight: 'auto',
			previewWidth: '400px',
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