<?php
include("../../config.inc.php");
include("../../include/PHPMailer/enviar_email.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);

$idinicio=17100; //LOCAL ESDE JUNIO
$idinicio=21238; //PRODUCCION DESDE JULIO
/*
$sql="	SELECT vtiger_crmentity.crmid,vtiger_localizadores.localizador, vtiger_localizadores.contactoid,vtiger_localizadores.registrodeventasid,
		FROM vtiger_localizadores 
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_localizadores.localizadoresid 
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid 
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid  
		WHERE vtiger_crmentity.deleted = 0 AND (vtiger_crmentityrel.crmid = 17186 OR vtiger_crmentityrel.relcrmid = 17186)";
*/
$sql="	SELECT e.crmid,r.registrodeventasname,e.smownerid				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_registrodeventas AS r ON r.registrodeventasid=e.crmid 				
		WHERE r.registrodeventastype LIKE '%Boleto%' AND e.deleted = 0 AND e.crmid>21238 AND e.crmid NOT IN 
		(SELECT vtiger_crmentityrel.crmid
			FROM vtiger_crmentity
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.relmodule='Localizadores' AND e.crmid>21238 
		)";

$result = mysql_query($sql);	
$rows = mysql_num_rows($result);
if ($rows>0){
	while ($row=mysql_fetch_array($result)){
		# code...
		$id=$row["crmid"];
		$venta=$row["registrodeventasname"];
		$userid=$row["smownerid"];
		$sqlUser="SELECT CONCAT(first_name,' ',last_name), email1 FROM vtiger_users WHERE id=".$userid;
		$qryUser=mysql_query($sqlUser);
		$rowUser=mysql_fetch_row($qryUser);
		$user=$rowUser[0];
		$email=$rowUser[1];

	}
}

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

if ($esSoto)				
$envio=enviarEmail($email,$asunto,$mensaje);			
if ($envio){
	$log->debug("correo SOTO Enviado");
	//Por base de datos el procedure setCrmEntityRel actualiza el status Soto de la venta a Reservado
}else{
	$log->debug("Error Enviando correo SOTO ".$envio);
}
?>