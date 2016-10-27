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
				$listaSat="<ul>";
        		foreach ($accountid as $elem) {
	            	$name=trim($elem);
	            	$listaSat.="<li>".$name."</li>";
	            	$sqlEmail="SELECT email1,accountid FROM vtiger_account WHERE accountname LIKE '%".$name."%'";
	            	$qryEmail=$adb->pquery($sqlEmail, array());
	            	$row = $adb->fetch_row($qryEmail);
					$email=$row[0];
					$accid=$row[1];
					$asunto="Info TuAgencia24 - Asignacion Asesora";
					//SI LA CUENTA SATELITE NO TIENE EMAIL ENVIAMOS CORREO DE ERROR AL ASESOR
					if (!$email){
						$email=$correo;
						$asunto="Info TuAgencia24 - Error al enviar correo de Asignacion de Satelite.";
					}
					if ($email){						
						$nombre="<p><b>Sres.</b> ".$name;					
						$mensaje = " 
						<html>
						<head> 
						<title>Info - Tu Agencia 24</title> 
						</head> 
						<body> 
						<img src='themes/images/banner_email.jpg'>
						<p>".$nombre."</p>
						<p>Estimado aliado comercial.</p>
						<p>Le damos la Bienvenida a Humbermar Tours C.A. Es un gusto anunciarle que su asesor asignado para atender sus solicitudes será:</p>
						<p><b>Asesor(a): </b>".$asesor."</p>				
						<p><b>Email: </b> ".$correo."</p>		
						<p><b>Telf. Oficina: </b> ".$telf."</p>								
						<p>
						El asesor a cargo estará a su disposición para las dudas que se presenten y  
						brindarle el apoyo necesario en las siguientes funciones: 
						<ul>
    					<li>Asesoramiento en Búsqueda, Cotización y Reservas. 
    					<li>Inducción en la plataforma <a href='http://treavi.com'>TREAVI.COM </a>
    					<li>Emisión eficiente.
    					<li>Soporte y Calculo de Reemisiones.
    					<li>Sistema de Control de Emisiones con OS-TICKET. 
    					<li>Sistema Gestión de Pagos y Clientes CRM.
    					</ul>
						<i>
						Sin más a que hacer referencia,		
						<p>Equipo TuAgencia24.com</p>
						</i>
						</body> 
						</html> "; 
						//VALIDAMOS SI NO SE LE HA ENVIADO EL CORREO DE ASIGNACION
						$sqlcheck="SELECT asignacionenviada FROM vtiger_account WHERE accountname LIKE '%".$name."%'";
	            		$qrycheck=$adb->pquery($sqlcheck, array());
	            		$rowcheck = $adb->fetch_row($qrycheck);
						$asig=$rowcheck[0];					
						//VALIDAMOS SI ES EL MISMO ASESOR
						$sqlAse="SELECT smownerid FROM vtiger_crmentity WHERE setype='Accounts' AND crmid=?";
						$qryAse=$adb->pquery($sqlAse, array($accid));
						$rowAse = $adb->fetch_row($qryAse);
						$userid2=$rowAse[0];			

						if (!$asig || ($userid<>$userid2))
						$envio=enviarEmail($email,$asunto,$mensaje);	

						if ($envio) {
							$sqlcheck="UPDATE vtiger_account SET asignacionenviada=1 WHERE accountname LIKE '%".$name."%'";
	            			$qrycheck=$adb->pquery($sqlcheck, array());
	            			$sqlacc="UPDATE vtiger_crmentity SET smownerid=? WHERE setype='Accounts' AND crmid=?";
	            			$qryacc=$adb->pquery($sqlacc, array($userid,$accid));
	            		}		

						$log->debug("Entering handle event ".$mensaje);
					}
	            }	
	            $listaSat.="</ul>";
	            //ENVIAMOS CORREO AL ASESOR
	            $asunto="Info TuAgencia24 - Asignacion de Satelites";
        		$nombre="<p>Hola, ".$asesor;					
				$mensaje = " 
				<html>
				<head> 
				<title>Info - Tu Agencia 24</title> 
				</head> 
				<body> 
				<p>".$nombre."</p>
				<p>Le notificamos que se le ha asignado las siguientes Satelites y/o Freelance:</p>
				<p>".$listaSat."</p>				
				<p>
				Usted estará a cargo para brindar el apoyo necesario en las siguientes funciones: 
				<ul>
				<li>Asesoramiento en Búsqueda, Cotización y Reservas. 
				<li>Inducción en la plataforma <a href='http://treavi.com'>TREAVI.COM </a>
				<li>Emisión eficiente.
				<li>Soporte y Calculo de Reemisiones.
				<li>Sistema de Control de Emisiones con OS-TICKET. 
				<li>Sistema Gestión de Pagos y Clientes CRM.
				</ul>
				<i>
				Saludos,		
				<p>Equipo TuAgencia24.com</p>
				</i>
				</body> 
				</html> ";   
				$envio2=enviarEmail($correo,$asunto,$mensaje);	          
	
        	}
    	}
    	return true;
    }
}

?>
	