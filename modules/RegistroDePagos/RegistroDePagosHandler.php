<?php
class RegistroDePagosHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log;
    	$log->debug("Entering handle event pagos ".$a);
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'RegistroDePagos') {  
        	if ($eventName == 'vtiger.entity.beforesave') {          				
				$idVenta = $entityData->get('registrodeventasid');
				$sqlValOrigen="SELECT registrodeventasid FROM vtiger_registrodeventas WHERE origendeventa>0 AND registrodeventasid= ?";
				$result = $adb->pquery($sqlValOrigen, array($idVenta));	
				$row = $adb->fetchByAssoc($result);
				$valId=$row["registrodeventasid"];
				if ($valId>0){
					echo "<script>";
					echo "alert('Debe modificar el Registro de Venta e Indicar el Origen de la misma para poder cargar pagos.')";
					echo "history.back()";
					echo "</script>";
					$log->debug("Cancelando accion handle event before pagos ".$a);
					return false;
				}
				//$qryValOrigen=mysql_query($sqlValOrigen);

				//$idp=$entityData->getId(); //OBTIENE ID DEL PAGO
				//$idpago=$_REQUEST["record"];
				//$this->updatePagos($idpago,$idVenta,NULL);
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
	