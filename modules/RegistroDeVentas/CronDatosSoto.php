<?php
//include('../../config.inc.php');
//include('../../include/PHPMailer/class.phpmailer2.php');
//include('../../include/PHPMailer/enviar_email_cron.php');
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/config.inc.php');
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/include/PHPMailer/class.phpmailer2.php');
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/include/PHPMailer/enviar_email_cron.php');
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect('localhost',$user,$pass);
mysql_select_db($bd);
//LOC 17118 PROD 21238
$apartirde=21238;

//SQL PARA VENTAS SIN LOCALIZADORES
$sql="	SELECT e.crmid,r.registrodeventasname,e.smownerid				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_registrodeventas AS r ON r.registrodeventasid=e.crmid 				
		WHERE r.registrodeventastype LIKE '%Boleto%' AND e.deleted = 0 AND e.crmid>$apartirde AND e.crmid NOT IN 
		(SELECT vtiger_crmentityrel.crmid
			FROM vtiger_crmentity
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.relmodule='Localizadores' AND e.crmid>$apartirde 
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

		$nombre="Hola, ".$user;
		$asunto="Informacion CRM - Verificar Datos (Venta sin LOC)";
		$mensaje = " 
		<html>
		<head> 
		<title>Info - Tu Agencia 24</title> 
		</head> 
		<body> 
		<p>".$nombre."</p>
		<p>Se ha encontrado un Registro de Ventas sin Localizadores asociados.</p>					
		<p>Por favor complete su registro:</p>							
		<p><b>Registro de Venta: </b> <a href='http://registro.tuagencia24.com/index.php?module=RegistroDeVentas&view=Detail&record=".$id."'>".$venta."</a></p>		
		<BR><BR>
		<i>
		Gracias,		
		<p>Equipo TuAgencia24.com</p>
		</i>
		</body> 
		</html> "; 		
		$envio=enviarEmail($email,$asunto,$mensaje);	
		if ($envio){
			echo "<p>Correo enviado a ".$user.", Venta sin LOC ".$venta;
		}

	}
}

//SQL PARA LOCALIZADORES SOTO SIN BOLETOS
$sql="	SELECT e.crmid,l.localizador,e.smownerid				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_localizadores AS l ON l.localizadoresid=e.crmid 				
		WHERE l.gds LIKE '%Servi%' AND e.deleted = 0 AND e.crmid>$apartirde AND e.crmid NOT IN 
		(SELECT vtiger_crmentityrel.crmid
			FROM vtiger_crmentity
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.relmodule='Boletos' AND e.crmid>$apartirde 
		)";
$result = mysql_query($sql);	
$rows = mysql_num_rows($result);
if ($rows>0){
	while ($row=mysql_fetch_array($result)){
		$id=$row["crmid"];
		$loc=$row["localizador"];
		$userid=$row["smownerid"];
		$sqlUser="SELECT CONCAT(first_name,' ',last_name), email1 FROM vtiger_users WHERE id=".$userid;
		$qryUser=mysql_query($sqlUser);
		$rowUser=mysql_fetch_row($qryUser);
		$user=$rowUser[0];
		$email=$rowUser[1];

		$nombre="Hola, ".$user;
		$asunto="Informacion CRM - Verificar Datos (LOC sin Boleto)";
		$mensaje = " 
		<html>
		<head> 
		<title>Info - Tu Agencia 24</title> 
		</head> 
		<body> 
		<p>".$nombre."</p>
		<p>Se ha encontrado un Localizador SOTO sin boletos registrados.</p>					
		<p>Por favor complete su registro:</p>							
		<p><b>Localizador: </b> <a href='http://registro.tuagencia24.com/index.php?module=Localizador&view=Detail&record=".$id."'>".$loc."</a></p>		
		<BR><BR>
		<i>
		Gracias,		
		<p>Equipo TuAgencia24.com</p>
		</i>
		</body> 
		</html> "; 		
		$envio=enviarEmail($email,$asunto,$mensaje);	
		if ($envio){
			echo "<p>Correo enviado a ".$user.", LOC sin Boleto ".$loc;
		}

	}
}

//SQL PARA VALIDAR NUM. DE DIGITOS DEL BOLETO, DEBE TENER MINIMO 13 CARACTERES
$sql="	SELECT e.crmid,b.localizadorid,b.boleto1,e.smownerid, l.localizador				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_boletos AS b ON b.boletosid=e.crmid 	
		INNER JOIN vtiger_localizadores AS l ON l.localizadoresid=b.localizadorid			
		WHERE LENGTH(boleto1) <13 AND e.deleted = 0 AND e.crmid>$apartirde 
		";
$result = mysql_query($sql);	
$rows = mysql_num_rows($result);
if ($rows>0){
	while ($row=mysql_fetch_array($result)){
		$id=$row["crmid"];
		$loc=$row["localizador"];		
		$userid=$row["smownerid"];
		$sqlUser="SELECT CONCAT(first_name,' ',last_name), email1 FROM vtiger_users WHERE id=".$userid;
		$qryUser=mysql_query($sqlUser);
		$rowUser=mysql_fetch_row($qryUser);
		$user=$rowUser[0];
		$email=$rowUser[1];

		$nombre="Hola, ".$user;
		$asunto="Informacion CRM - Verificar Datos (Boleto Incompleto)";
		$mensaje = " 
		<html>
		<head> 
		<title>Info - Tu Agencia 24</title> 
		</head> 
		<body> 
		<p>".$nombre."</p>
		<p>Se ha encontrado un registro de boletos con numero de Boleto invalido.</p>					
		<p>Por favor corrija el Num. de Boleto:</p>							
		<p><b>Boleto/Localizador: </b> <a href='http://registro.tuagencia24.com/index.php?module=Boletos&view=Detail&record=".$id."'>".$loc."</a></p>		
		<BR><BR>
		<i>
		Gracias,		
		<p>Equipo TuAgencia24.com</p>
		</i>
		</body> 
		</html> "; 		
		$envio=enviarEmail($email,$asunto,$mensaje);	
		if ($envio){
			echo "<p>Correo enviado a ".$user.", Boleto Incompleto ".$loc;
		}
	}
}
?>