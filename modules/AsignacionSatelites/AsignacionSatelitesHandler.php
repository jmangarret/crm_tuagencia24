<?php
class AsignacionSatelitesHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
    	$log->debug("Entering handle event ");
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'AsignacionSatelites') {  
        	if ($eventName == 'vtiger.entity.beforesave') {          						
        		$esNuevo=$entityData->isNew();

				$host= 		$_SERVER["HTTP_HOST"];
				$idrecord=	$_REQUEST["record"];
				$accountid=	$_REQUEST["accountid"];
				$userid=	$_REQUEST["smownerid"];

				$sql="SELECT e.crmid				
				FROM  vtiger_crmentity AS e
				INNER JOIN vtiger_asignacionsatelites AS a ON a.asignacionsatelites=e.crmid 					
				WHERE e.smownerid= ? AND a.accountid= ? AND e.deleted = 0 
				";
				$result = $adb->pquery($sql, array($userid,$accountid));	
				$row = $adb->fetch_row($result);
				$rows=$row[0];
				if ($rows>0){
					header("location:index.php");
					die("Registro duplicado...");
				}


				
        	}
    	}
    	return true;
    }
}

?>
	