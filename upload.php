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
	<div id="fileuploader"></div>
<?php
	$sqlPass="SELECT vtiger_attachments.attachmentsid, vtiger_attachments.name, vtiger_attachments.path FROM vtiger_attachments
		INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
		INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
		WHERE vtiger_crmentity.setype = 'Boletos Attachment' and vtiger_seattachmentsrel.crmid = $id 
		ORDER BY vtiger_attachments.attachmentsid DESC";
	$qryPass=mysql_query($sqlPass);
	$idAttachment	=mysql_result($qryPass, 0, "attachmentsid");
	$nameAttachment	=mysql_result($qryPass, 0, "name");
	$pathAttachment	=mysql_result($qryPass, 0, "path");
	chmod($pathAttachment.$idAttachment."_".$nameAttachment,777);	
	echo "<img 	src='".$pathAttachment.$idAttachment."_".$nameAttachment."' 
				alt='".$pathAttachment.$idAttachment."_".$nameAttachment."' 
				title='".$pathAttachment.$idAttachment."_".$nameAttachment."'>";
}

?>
