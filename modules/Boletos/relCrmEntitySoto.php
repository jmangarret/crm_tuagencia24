<?php
//Llamado desde data/CRMEntity.php
//Verificar que este agregada esta linea al inicio de data/CRMEntity.php
//include_once("modules/Boletos/BoletosFunciones.php");
// jmangarret 09ago2016 Enviar correo si es SOTO
if ($module=="RegistroDeVentas" && $with_module=="Localizadores"){
	$log->debug("Enviando correo SOTO en CRMEntity.php");
	$host= $_SERVER["HTTP_HOST"];

	$sql="SELECT localizador FROM vtiger_localizadores WHERE localizadoresid=?";
	$result = $adb->pquery($sql, array($relcrmid));	
	$row = $adb->fetch_row($result);
	$idloc=$relcrmid;
	$loc=$row[0];

	$esSoto=esVentaSoto($crmid);
	$esServi=getLocGds($relcrmid);
		
	$log->debug("Es SOTO: ".$esSoto.", es SERVI: ".$esServi.", valPass: ".$valPass);
	
	if ($esSoto || $esServi){
		$boletos=getCantBoletos($relcrmid);
		if ($boletos>0){
			$valPass=validarPasaportes($relcrmid);
			if ($valPass==0 || !$valPass){
				$email="tuagencia.sistemas01@gmail.com";
				$asunto="SOTO CRM - Verificar Datos (Reserva de SOTO)";
				$mensaje=getPlantillaVerificarDatos($relcrmid, $loc);					
				$envio=enviarEmail($email,$asunto,$mensaje);					
			}else{
				//Notificar falta de pasaporte adjunto
				$email="tuagencia.sistemas01@gmail.com";
				$asunto="SOTO CRM - Subir Pasaporte (Reservado de SOTO)";
				$mensaje=getPlantillaSubirPasaporte($relcrmid, $loc);					
				$envio=enviarEmail($email,$asunto,$mensaje);					
			}	
		}		
	}				
	
	if ($envio){
		//SP BD setCrmEntityRel actualiza el status Soto de la Venta a Reservado
		$log->debug("correo SOTO Enviado");
		
	}else{
		$log->debug("Error Enviando correo SOTO ".$envio);
	}

}
//Fin enviar correo

?>
