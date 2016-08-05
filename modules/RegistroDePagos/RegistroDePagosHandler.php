<?php
//include('include/PHPMailer/enviar_email.php');
class RegistroDePagosHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
    	$log->debug("Entering handle event pagos ".$a);
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'RegistroDePagos') {  
        	if ($eventName == 'vtiger.entity.beforesave') {          						
				//$idp=$entityData->getId(); //OBTIENE ID DEL PAGO
				//$idpago=$_REQUEST["record"];
				//$this->updatePagos($idpago,$idVenta,NULL);
				$host= $_SERVER["HTTP_HOST"];
				$idpago		=$_REQUEST["record"];
				$idVenta=$_REQUEST["registrodeventasid"];
				$statuspago=$_REQUEST["pagostatus"];
				$email="tuagencia.sistemas01@gmail.com";
				$nombre="Hola,";
				$asunto="Prueba CRM - Emitir SOTO (Pago Verificado)";
				$mensaje = " 
				<html>
				<head> 
				<title>Info - Tu Agencia 24</title> 
				</head> 
				<body> 
				<p>".$nombre.",</p>
				<p>Se ha verificado un nuevo pago para la Emisión de SOTO:</p>
				<p><b>Pago: </b>".$idpago."</p>				
				<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$idVenta."'>Click aqui</a></p>		
				<BR><BR><BR>
				<i>
				Gracias,		
				<p>Equipo TuAgencia24.com</p>
				</i>
				</body> 
				</html> "; 
				//Verificamos si es un SOTO
				$sqlSoto="SELECT 1 FROM vtiger_localizadores WHERE registrodeventasid=? AND gds= ?";
				$result = $adb->pquery($sqlSoto, array($idVenta,"Servi"));	
				$row = $adb->fetch_row($result);
				$esSoto=$row[0];

				if ($statuspago=="Verificado" && $esSoto)				
				$envio=enviarEmail($email,$asunto,$mensaje);			
				if ($envio){
					$sqlVentaUpdate="UPDATE vtiger_registrodeventas SET statussoto='Emitir Soto' WHERE registrodeventasid=?";
					$result = $adb->pquery($sqlVentaUpdate, array($idVenta));	
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
	