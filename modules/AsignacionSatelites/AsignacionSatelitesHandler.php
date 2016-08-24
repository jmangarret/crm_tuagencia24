<?php
class AsignacionSatelitesHandler extends VTEventHandler {
    function handleEvent($eventName, $entityData) {  
    	global $log, $adb;
    	$log->debug("Entering handle event AsignacionSatelitesHandler");
        $moduleName = $entityData->getModuleName();
        if ($moduleName == 'AsignacionSatelites') {  
        	if ($eventName == 'vtiger.entity.aftersave') {          						
        		$esNuevo=$entityData->isNew();

				$accountid=	$_REQUEST["accountid"];
				$userid=	$_REQUEST["assigned_user_id"];
								
				$ownerModel = Users_Record_Model::getInstanceById($userid, 'Users');
				$asesor = $ownerModel->get('first_name')." ".$ownerModel->get('last_name');
				$correo = $ownerModel->get('email1');
				$telf 	= $ownerModel->get('phone_work');

        		foreach ($accountid as $elem) {
	            	$names=trim($elem);
	            	$sqlEmail="SELECT email1 FROM vtiger_account WHERE accountname LIKE '%".$names."%'";
	            	$qryEmail=$adb->pquery($sqlEmail, array());
	            	$row = $adb->fetch_row($qryEmail);
					$email=$row[0];
					if ($email){
						$nombre="Hola, ".$names;
						$asunto="Info TuAgencia24 - Asignacion Asesora";
						$mensaje = " 
						<html>
						<head> 
						<title>Info - Tu Agencia 24</title> 
						</head> 
						<body> 
						<p>".$nombre."</p>
						<p>Se le ha asignado un(a) Asesor(a) de TuAgencia24:</p>
						<p><b>Nombre: </b>".$asesor."</p>				
						<p><b>Email: </b> ".$correo."</p>		
						<p><b>Telf. Oficina: </b> ".$telf."</p>		
						<BR><BR>
						<i>
						Saludos,		
						<p>Equipo TuAgencia24.com</p>
						</i>
						</body> 
						</html> "; 
						$envio=enviarEmail($email,$asunto,$mensaje);			
						$log->debug("Entering handle event ".$mensaje);
					}	
	            }	            
	            /*
				$sql="SELECT e.crmid				
				FROM  vtiger_crmentity AS e
				INNER JOIN vtiger_asignacionsatelites AS a ON a.asignacionsatelites=e.crmid 					
				WHERE e.smownerid= ? AND a.accountid= ? AND e.deleted = 0 
				";
				*/				
        	}
    	}
    	return true;
    }
}

?>
	