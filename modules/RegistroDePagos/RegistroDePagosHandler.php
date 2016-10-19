<?php
//include('include/PHPMailer/enviar_email.php');
include_once('modules/Boletos/BoletosFunciones.php');
class RegistroDePagosHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
    	$log->debug("Entering handle event pagos ".$a);
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'RegistroDePagos') {  
        	if ($eventName == 'vtiger.entity.aftersave') {          										
				//$idpago=$_REQUEST["record"];
				//$this->updatePagos($idpago,$idVenta,NULL);
				$idpago		=$_REQUEST["record"];
				if ($entityData->isNew()){
					$idpago=$entityData->getId();
				}
				$idVenta=$_REQUEST["registrodeventasid"];
				$statuspago=$_REQUEST["pagostatus"];

				$sql="SELECT registrodeventasname FROM vtiger_registrodeventas WHERE registrodeventasid=?";
				$result = $adb->pquery($sql, array($idVenta));	
				$row = $adb->fetch_row($result);
				$venta=$row[0];

				//Verificamos si es un SOTO
				$esSoto=esVentaSoto($idVenta);				
				$idloc=getLocId($idVenta,"RegistroDeVentas");
				$cantBoletos=getCantBoletos($idloc);
				$valPass=validarPasaportes($idloc);
				//Verificamos Pagos
				$pagosCheck=getPagosVerificados($idVenta);
				$log->debug("Entering handle event pagoscheck esSoto:".$esSoto ."/pagoscheck:".$pagosCheck." /cantBoletos:".$cantBoletos);
				if ($pagosCheck && $esSoto && $cantBoletos>0 && !$valPass){						
					$log->debug("Entering handle event pagoscheckeados esSoto ");										
					$log->debug("Entering handle event pagoscheckeados esSoto y subio pasaportes");					
					$email="tuagencia.sistemas01@gmail.com";					
					$asunto="SOTO CRM - Emitir SOTO (Pago Verificado)";
					$mensaje = getPlantillaEmitirSoto($idVenta,$venta);
					$envio=enviarEmail($email,$asunto,$mensaje);
					if ($envio){
						setStatusSoto($idVenta,"Emitir Soto");
					}									
				}		
				//Validar pasaporte si es primera vez que carga pago (Por verificar)
				if ($esSoto && $cantBoletos>0 && $valPass){
					//Notificar falta de pasaporte adjunto
					$log->debug("Entering handle event pagoscheckeados esSot, no subio pasaportes");					
					$email="tuagencia.sistemas01@gmail.com";
					$asunto="SOTO CRM - Subir Pasaporte (Reservado de SOTO)";
					$mensaje=getPlantillaSubirPasaporte($idloc, $loc);					
					$envio=enviarEmail($email,$asunto,$mensaje);					
				}		
				

        	}
    	}
    	return true;
    }
    function updatePagos($idpago, $idVenta, $action=""){
    	/* MIGRADO A SP TOTVENTASPAGADAS
		global $log, $current_module, $adb, $current_user;
		$log->debug("Entering handle update pagos");
        if ($action=="DELETE"){
        	$log->debug("Entering handle delete pagos");
			$sql="UPDATE vtiger_registrodepagos SET registrodeventasid=NULL where registrodepagosid = ? ";
			$result = $adb->pquery($sql, array($idpago));	
		}	
		$sql="SELECT cf_1621, cf_881 FROM vtiger_registrodeventascf WHERE registrodeventasid = ?";
		$result = $adb->pquery($sql, array($idVenta));	
		$row = $adb->fetchByAssoc($result);
		$cambioParalelo=$row["cf_881"];
		$status=$row["cf_1621"];

		if (!$cambioParalelo) $cambioParalelo=1; 
		
		//CONSULTAMOS TOTALES DE BOLETOS
		$sql="SELECT totalventabs, totalventadolares FROM vtiger_registrodeventas WHERE registrodeventasid = ?";
		$result = $adb->pquery($sql, array($idVenta));	
		$row = $adb->fetch_row($result);
		$totalVentaBs=$row[0];			
		$totalVentaDolares=$row[1];			
				
		//CONSULTAMOS TOTALES DE PAGOS
		$sql="SELECT SUM(amount) AS pagoDolares FROM vtiger_registrodepagos WHERE currency='USD' AND registrodeventasid = ?";
		$result = $adb->pquery($sql, array($idVenta));					
		$row = $adb->fetch_row($result);
		$totalPagoDolares=$row[0];

		$sql="SELECT SUM(amount) AS PagoBs FROM vtiger_registrodepagos WHERE currency='VEF' AND registrodeventasid = ?";
		$result = $adb->pquery($sql, array($idVenta));					
		$row = $adb->fetch_row($result);
		$totalPagoBs=$row[0];

		//CALCULAMOS TOTALES PENDIENTES
		$totalPendienteBs=$totalVentaBs - $totalPagoBs;
		$totalPendienteDolares=$totalVentaDolares - $totalPagoDolares;
		
		if ($totalPagoBs>$totalVentaBs && $totalPendienteDolares>=0 && ($totalVentaBs>0 || $totalVentaDolares>0)){
			$difBs = $totalPagoBs - $totalVentaBs;
			$dolaresPagadosEnBs = $difBs / $cambioParalelo; 
			$totalPendienteDolares = $totalVentaDolares - $totalPagoDolares - $dolaresPagadosEnBs;
			$totalPendienteBs = $totalPendienteDolares * $cambioParalelo;

		}

		//ACTUALIZAMOS TOTALES
		$sql="UPDATE vtiger_registrodeventas SET totalpagadobs=?, totalpagadodolares=?, totalpendientebs=?, totalpendientedolares=? WHERE registrodeventasid = ?";		
		$result = $adb->pquery($sql, array($totalPagoBs, $totalPagoDolares,$totalPendienteBs, $totalPendienteDolares,$idVenta));	

		//ACTUALIZAMOS STATUS DE LA VENTA
		if ($status<>"Procesada"){
		if ($totalPendienteDolares<=0 && $totalPendienteBs<=0){			
			$sqlStatus="UPDATE vtiger_registrodeventascf SET cf_1621='No Procesada' WHERE registrodeventasid = ?";
			$result = $adb->pquery($sqlStatus, array($idVenta));	
		}else{
			if ($status=="No Procesada" || $status=="Pagada"){
				$sqlStatus="UPDATE vtiger_registrodeventascf SET cf_1621='Pendiente de Pago' WHERE registrodeventasid = ?";
				$result = $adb->pquery($sqlStatus, array($idVenta));	

			}
		}
		}	
		return true;
		*/
    }
}

?>
	