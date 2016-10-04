<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
include_once('modules/RegistroDePagos/RegistroDePagosHandler.php');
include_once('modules/Boletos/BoletosFunciones.php');
class RegistroDeVentasHandler extends VTEventHandler {
	function handleEvent($eventName, $data) {
		global $log, $current_module, $adb, $current_user;
		$id=$data->getId();      
		if($eventName == 'vtiger.entity.aftersave') {
			if ($data->isNew()){		
			 	//Inicializamos status de la venta	 	      
 				$sql="UPDATE vtiger_registrodeventascf SET  cf_1621='Pendiente de Pago' where registrodeventasid = ? ";
				$result = $adb->pquery($sql, array($id));	
        		}      

        	//BUSCAMOS LOS DATOS DE LA COTIZACION
        	$idQuote = $data->get('quoteid');
        	if ($idQuote){
        		$sqlQuotes="SELECT * FROM vtiger_quotes WHERE quoteid=?";
        		$result = $adb->pquery($sql, array($id));	
				$row = $adb->fetch_row($result);
				$nrows=$row[0];
        	}
        	$this->updateVentas($id);

        	//jmangarret oct2016 - WORKFLOW SOTO - Status Emitido
        	$gds=getVentaGds($id);
        	$tipodeventa=$_REQUEST["registrodeventastype"];
        	$statussoto=$_REQUEST["statussoto"];
        	if ($statussoto=="Emitido"){
        		if ($tipodeventa=="Boleto SOTO" || $gds=="Servi"){
        			$email="tuagencia.sistemas01@gmail.com";
					$asunto="SOTO CRM - Emitido (Actualizar Boletos)";
					$mensaje = getPlantillaEmitido($idVenta,$venta);	
					$envio=enviarEmail($email,$asunto,$mensaje);				
        		}	
        	}
        	$log->debug("Entering handle event venta gds:".$gds ."/tipo:".$tipodeventa."/statussoto:".$statussoto);						
        	
        }
    }
    function updateVentas($idVenta){
    		global $log, $current_module, $adb, $current_user;	
			//BUSCAMOS BOLETOS ASOCIADOS PARA EJECUTAR HANDLER
			/*
			$sql="SELECT COUNT(*) FROM vtiger_boletos WHERE registrodeventasid = ?";
			$result = $adb->pquery($sql, array($idVenta));	
			$row = $adb->fetch_row($result);
			$nbols=$row[0];
			if ($nbols>0){								
					//MONTO TOTAL EN DOLARES BOLETOS SOTOS
					$sql="SELECT SUM(amount*cantidad) as montoDs FROM vtiger_boletos WHERE currency='USD' AND registrodeventasid = ?";
					$result = $adb->pquery($sql, array($idVenta));	
					$row = $adb->fetch_row($result);
					$totalBoletosDolares=$row[0];
					
					//MONTO TOTAL EN BS BOLETOS NO SOTOS
					$sql="SELECT SUM(amount*cantidad) as montoBs FROM vtiger_boletos WHERE currency='VEF' AND registrodeventasid = ?";
					$result = $adb->pquery($sql, array($idVenta));	
					$row = $adb->fetch_row($result);
					$totalBoletosBs=$row[0];		
					$log->debug("Entering handle totalboletos ". $totalBoletosBs);
	
			}
			*/
			//BUSCAMOS PRODUCTS ASOCIADOS PARA EJECUTAR HANDLER
			$sql="SELECT COUNT(*) FROM vtiger_ventadeproductos WHERE registrodeventasid = ?";
			$result = $adb->pquery($sql, array($idVenta));	
			$row = $adb->fetch_row($result);
			$nprods=$row[0];
			if ($nprods>0){								
				//MONTO TOTAL EN DOLARES BOLETOS SOTOS
				$sql="SELECT IF(ISNULL(SUM(amount)),0,SUM(amount)) AS montoDs FROM vtiger_ventadeproductos WHERE currency='USD' AND registrodeventasid = ?";
				$result = $adb->pquery($sql, array($idVenta));	
				$row = $adb->fetch_row($result);
				$totalProductosDolares=$row[0];
				
				//MONTO TOTAL EN BS BOLETOS NO SOTOS
				$sql="SELECT IF(ISNULL(SUM(amount)),0,SUM(amount)) as montoBs FROM vtiger_ventadeproductos WHERE currency='VEF' AND registrodeventasid = ?";
				$result = $adb->pquery($sql, array($idVenta));	
				$row = $adb->fetch_row($result);
				$totalProductosBs=$row[0];	
				$log->debug("Entering handle totalproductos ". $totalProductosBs);

			}
			/*
			$totalBs=$totalBoletosBs+$totalProductosBs;
			$totalDs=$totalBoletosDolares+$totalProductosDolares;
			$log->debug("Entering handle totalboletosproductos ".$totalBoletosBs." - ".$totalProductosBs);
			*/
			if (!$totalProductosBs) $totalProductosBs=0;
			if (!$totalProductosDolares) $totalProductosDolares=0;
			/*
			$sql="UPDATE vtiger_registrodeventas SET 
					totalventabs		=IF(ISNULL(totalventabs),?,totalventabs+?), 
					totalventadolares	=IF(ISNULL(totalventadolares),?,totalventadolares+?) 
					WHERE registrodeventasid = ?";			
			$result = $adb->pquery($sql, array($totalProductosBs,$totalProductosBs, $totalProductosDolares,$totalProductosDolares,  $idVenta));	
			*/

			//PENDIENTE SUMA BOLETOS, INVOCAR SP SETCRMENTITYREL
			$sql="UPDATE vtiger_registrodeventas SET 
					totalventabs		=?, 
					totalventadolares	=? 
					WHERE registrodeventasid = ?";
			if ($totalProductosBs>0 || $totalProductosDolares>0) 
			$result = $adb->pquery($sql, array($totalProductosBs,$totalProductosDolares,$idVenta));	
			//$result = $adb->pquery($sql, array($totalBs, $totalDs, $totalBs, $totalDs, $idVenta));	query anterior may2016

			//if ($nprods>0 || $nbols>0){
			//	RegistroDePagosHandler::updatePagos(0,$idVenta);				
			//}			
			
			//$result = $adb->pquery("CALL setCrmEntityRel('RegistroDeVentas','Localizadores',".$idVenta.",0)", array());	
			
			$result = $adb->pquery("CALL totVentasPagadas(?)", array($idVenta));	

        	
			
	}	
}
?>
