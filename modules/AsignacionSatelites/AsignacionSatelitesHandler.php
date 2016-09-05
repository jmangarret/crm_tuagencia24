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
	            	$name=trim($elem);
	            	$sqlEmail="SELECT email1 FROM vtiger_account WHERE accountname LIKE '%".$name."%'";
	            	$qryEmail=$adb->pquery($sqlEmail, array());
	            	$row = $adb->fetch_row($qryEmail);
					$email=$row[0];
					$asunto="Info TuAgencia24 - Asignaci&oacute;n Asesora";
					//SI LA CUENTA SATELITE NO TIENE EMAIL ENVIAMOS CORREO DE ERROR AL ASESOR
					if (!$email){
						$email=$correo;
						$asunto="Info TuAgencia24 - Error al enviar correo de Asignaci&oacute;n de Satelite.";
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
						Su asesor a cargo estará a la mayor disposición posible para las dudas que se presenten, 
						brindándole asesoría en las siguientes funciones: 
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

						$envio=enviarEmail($email,$asunto,$mensaje);			
						$log->debug("Entering handle event ".$mensaje);
					}
	            }	            
	
        	}
    	}
    	return true;
    }
}

?>
	