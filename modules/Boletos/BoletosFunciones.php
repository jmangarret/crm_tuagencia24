<?php
function esVentaSoto($ventaid){
	global $adb;
	$sqlSoto="SELECT COUNT(*) FROM vtiger_registrodeventas WHERE registrodeventasid=? AND registrodeventastype= ?";
	$result = $adb->pquery($sqlSoto, array($ventaid,"Boleto SOTO"));	
	$row = $adb->fetch_row($result);
	$esSoto=$row[0];
	return $esSoto;
}
function getVentaGds($ventaid){
	global $adb;
	$sqlSoto="SELECT gds FROM vtiger_localizadores WHERE registrodeventasid=?";
	$result = $adb->pquery($sqlSoto, array($ventaid));	
	$row = $adb->fetch_row($result);
	$gds=$row[0];
	return $gds;
}
function getLocGds($locid){
	global $adb;
	$sqlSoto="SELECT gds FROM vtiger_localizadores WHERE localizadoresid=?";
	$result = $adb->pquery($sqlSoto, array($locid));	
	$row = $adb->fetch_row($result);
	$gds=$row[0];
	return $gds;
}
function setStatusSoto($ventaid,$status){
	global $adb;
	$sqlVentaUpdate="UPDATE vtiger_registrodeventas SET statussoto=? WHERE registrodeventasid=?";
	$result = $adb->pquery($sqlVentaUpdate, array($status,$ventaid));	
	$update=$adb->getAffectedRowCount($result);
	if ($update)
		return true;
	else
		return false;
}
function getPagosVerificados($ventaid){
	global $adb;
	$cont=0;
	$sqlpag ="SELECT registrodepagosid, pagostatus FROM vtiger_registrodepagos as r ";
	$sqlpag.="INNER JOIN vtiger_crmentity as e ON r.registrodepagosid=e.crmid ";
	$sqlpag.="WHERE e.deleted=0 AND registrodeventasid=? ";
	$qrypag=$adb->pquery($sqlpag, array($ventaid));	
	while ($rowpag=$adb->fetch_row($qrypag)) {
		$pagoid=$rowpag[0];
		$status=$rowpag[1];
		if ($status=='Verificado'){
			$cont++;
		}else{
			$cont--;
		}							
	}	
	if ($cont>0)
		return true;
	else
		return false;
}

function getPlantillaEmitirSoto($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje=" 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se han verificado los pagos para proceder a la <b>Emisión de SOTO</b>:</p>
	<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$ventaid."'>".$label."</a></p>		
	<BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 
	return $mensaje;
}
function getPlantillaVerificarPagos($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje=" 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se han verificado los datos del pasajero para proceder a <b>Verificar los Pagos</b>:</p>
	<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$ventaid."'>".$label."</a></p>		
	<BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 
	return $mensaje;
}
function getPlantillaVerificarDatos($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje = " 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
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
	return $mensaje;
}

?>