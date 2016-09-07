<?php
include("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);

$sql="SELECT registrodeventasname FROM vtiger_registrodeventas WHERE registrodeventasid=?";

$sql="SELECT 	vtiger_registrodeventas.registrodeventasname AS vtiger_registrodeventasregistrodeventasname,
				vtiger_crmentity.smownerid AS vtiger_crmentityassigned_user_id,
				vtiger_crmentity.createdtime AS vtiger_crmentitycreatedtime,
				vtiger_crmentity.modifiedtime AS vtiger_crmentitymodifiedtime,
				vtiger_crmentity.modifiedby AS vtiger_crmentitymodifiedby,
				vtiger_registrodeventas.statussoto AS vtiger_registrodeventasstatussoto,
				vtiger_crmentity.deleted 
				FROM  vtiger_crmentity 
				LEFT JOIN vtiger_registrodeventas ON vtiger_registrodeventas.registrodeventasid=vtiger_crmentity.crmid 				
				WHERE  vtiger_crmentity.crmid=?  LIMIT 1";

$result = $adb->pquery($sql, array($crmid));	
$row = $adb->fetch_row($result);
$venta=$row[0];

$email="tuagencia.sistemas01@gmail.com";
$nombre="Hola,";
$asunto="Prueba CRM - Verificar Datos (Reserva de SOTO)";
$mensaje = " 
<html>
<head> 
<title>Info - Tu Agencia 24</title> 
</head> 
<body> 
<p>".$nombre."</p>
<p>Se ha registrado una Reserva de SOTO para la Verificacion de Datos:</p>					
<p><b>Localizador: </b> <a href='http://".$host."/index.php?module=Localizadores&view=Detail&record=".$relcrmid."'>".$loc."</a></p>		
<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$crmid."'>".$venta."</a></p>		
<BR><BR><BR>
<i>
Gracias,		
<p>Equipo TuAgencia24.com</p>
</i>
</body> 
</html> "; 
//Verificamos si es un SOTO
$sqlSoto="SELECT COUNT(*) FROM vtiger_localizadores WHERE localizadoresid=? AND gds= ?";
$result = $adb->pquery($sqlSoto, array($relcrmid,"Servi"));	
$row = $adb->fetch_row($result);
$esSoto=$row[0];

if ($esSoto)				
$envio=enviarEmail($email,$asunto,$mensaje);			
if ($envio){
	$log->debug("correo SOTO Enviado");
	//Por base de datos el procedure setCrmEntityRel actualiza el status Soto de la venta a Reservado
}else{
	$log->debug("Error Enviando correo SOTO ".$envio);
}
?>