<?php
include('BoletosFunciones.php');
include_once('../../include/PHPMailer/enviar_email.php');
class BoletosHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'Boletos') {  
        	if ($eventName == 'vtiger.entity.aftersave') {          	
				$boletoid=$entityData->getId(); 
				$checkpassport = $entityData->get('pasaportecheck');
				$localizadorid = $entityData->get('localizadorid');
				$log->debug("Entering handle event boletos checkpass:".$checkpassport);
				if ($checkpassport){
					//Verificamos si los boletos tienen venta asociada
					$sqlvta =" SELECT l.registrodeventasid, r.registrodeventasname FROM vtiger_localizadores l ";
					$sqlvta.=" INNER JOIN vtiger_registrodeventas r ON l.registrodeventasid=r.registrodeventasid ";
					$sqlvta.=" WHERE localizadoresid=? ";
					$qryvta=	$adb->pquery($sqlvta, array($localizadorid));	
					$idVenta=	$adb->query_result($qryvta,0,'registrodeventasid');
					$venta=		$adb->query_result($qryvta,0,'registrodeventasname');
					/*
					$sql="SELECT registrodeventasname FROM vtiger_registrodeventas WHERE registrodeventasid=?";
					$result = $adb->pquery($sql, array($idVenta));	
					$venta=$adb->query_result($result,0,'registrodeventasname');
					*/
					$cantPagos=getCantPagos($idVenta);	//Preguntar Validacion de pagos para verificar pasaporte??? 
					if ($idVenta>0 && $cantPagos>0){
						//Buscamos pagos no eliminados
						$pagosCheck=getPagosVerificados($idVenta);							
						if ($pagosCheck){
							//Todos los Pagos verificados
							//Si estan verificados: Status SOTO Emitir SOTO y Enviamos Correo a Gerencia.
							$setStatus=setStatusSoto($idVenta,"Emitir Soto");
							if ($setStatus){
								$email="tuagencia.sistemas01@gmail.com";
								$asunto="SOTO CRM - Emitir SOTO (Pago Verificado)";
								$mensaje = getPlantillaEmitirSoto($idVenta,$venta);	
								$envio=enviarEmail($email,$asunto,$mensaje);				
							}					
						}else{
							//Verificamos si la forma de pago es Credito, para ver que status colocar
							$formaDePago=getFormaDePago($localizadorid);
							if ($formaDePago=="Credito"){
								//Pasa directo a Emitir Soto
								$setStatus=setStatusSoto($idVenta,"Emitir Soto");
								if ($setStatus){
									$email="tuagencia.sistemas01@gmail.com";
									$asunto="SOTO CRM - Emitir SOTO (Pago con TDC)";
									$mensaje = getPlantillaEmitirSoto($idVenta,$venta);	
									$envio=enviarEmail($email,$asunto,$mensaje);				
								}													
							}else{
								//Sino enviamos correo a administracion para que verifique los pagos. Status SOTO Confirmar Pago		
								$setStatus=setStatusSoto($idVenta,"Confirmar Pago");
								if ($setStatus){
									$email="tuagencia.sistemas01@gmail.com";
									$asunto="SOTO CRM - Confirmar Pagos (Pasaporte Verificado)";
									$mensaje = getPlantillaVerificarPagos($idVenta,$venta);	
									$envio=enviarEmail($email,$asunto,$mensaje);				
								}													
							}							
						}						
					}						
				}
				$log->debug("Entering handle event boletos, idVenta".$idVenta ."/cantPagos:".$cantPagos." /pagosCheck:".$pagosCheck);						
        	}
    	}
    	return true;
    }
    function updateBoletos($idBoleto, $idVenta, $action=""){
		global $log, $current_module, $adb, $current_user;
		/* ELIMINADO POR NUEVA VERSION
	    if ($action=="DELETE"){
        	$log->debug("Entering handle delete sotos");
			$sql="UPDATE vtiger_boletos SET registrodeventasid=NULL where boletosid = ? ";
			$result = $adb->pquery($sql, array($idBoleto));	
		}
		$log->debug("Entering handleboletos ");
		BoletosHandler::updateVentas($idVenta);
		$sqlTotal="UPDATE vtiger_boletos SET totalboletos=amount*cantidad where boletosid=?";
		$result=$adb->pquery($sqlTotal,array($idBoleto));
		*/
		return true;
    }
}

?>