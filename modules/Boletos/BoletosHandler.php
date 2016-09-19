<?php
include_once 'modules/Boletos/BoletosHandler.php';
class BoletosHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'Boletos') {  
        	if ($eventName == 'vtiger.entity.aftersave') {          
				$boletoid=$entityData->getId(); 
				$checkpassport = $entityData->get('pasaportecheck');
				$localizadorid = $entityData->get('localizadorid');
				if ($checkpassport){
					//Verificamos si los pagos estan verificados
					$sqlvta="SELECT registrodeventasid FROM vtiger_localizadores WHERE localizadoresid=?";
					$qryvta=$adb->pquery($sqlvta, array($localizadorid));	
					$ventaid=$adb->query_result($qryvta,0,'registrodeventasid');
					if ($ventaid>0){
						//Buscamos pagos no eliminados
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
						if ($cont>0){
							//Todos los Pagos verificados
							//Si estan verificados: Enviamos Correo a Gerencia y Status SOTO Emitir SOTO
						}else{
							//Sino enviamos correo a administracion para que verifique los pagos. Status SOTO Confirmar Pago		

						}
						
					}							
				}
				
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