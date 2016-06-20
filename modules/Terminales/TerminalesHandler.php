<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
class TerminalesHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) { 
    	global $log;
    	$log->debug("Entering handle TerminalesHandler 2 ");
		$adb = PearDatabase::getInstance();				
		
		if ($eventName=="vtiger.entity.beforesave"){	
		if ($entityData->isNew()){		
			 	$strFirma 	= $entityData->get('firma');
				$sql="SELECT COUNT(*) FROM vtiger_terminales WHERE firma=?";				
				$qry=$adb->pquery($sql, array($strFirma));
				$result=$adb->fetch_row($qry);		
				if ($result[0]>0){										
					echo "<script>";
					echo "alert('Firma ya Registrada: $strFirma');";
					echo "history.back();";				
					echo "</script>";				
					die();	
				}				
        	}	
    	}

   }
}	
?>
