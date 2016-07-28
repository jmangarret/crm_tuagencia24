<?php
include("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);
$id= $_GET["id"];
$accion= $_GET["accion"];
echo $id;
//Accion ajajx para validar origen de la venta, impidiendo cargar pagos.
if ($accion=="valOrigen"){
	$sqlValOrigen="SELECT registrodeventasid FROM vtiger_registrodeventas WHERE origendeventa<>'' AND registrodeventasid= ".$id;	
	$result = mysql_query($sqlValOrigen);	
	$row = mysql_fetch_row($result);
	echo $row[0];
}
?>
