<?php
//INVOCADO DESDE Util.php, requiere variable custom para indicar que codigo incluir
if ($custom==1){ //custom de function getPickListValues	
	//jmangarret 18ago2016 valores picklist 15, 16, 33 para cuentas satelites
	if ($fieldName=="accountid"){
		$sqlAsig="SELECT accountid FROM vtiger_asignacionsatelites";
		$qryAsig=$db->pquery($sqlAsig, array());
		$num_rows = $db->num_rows($qryAsig);
		$names="''";
	    for($i=0; $i<$num_rows; $i++) {				
	        $acc=$db->query_result($qryAsig,$i,$fieldName);
	        $exp=explode("|##|", $acc);
	        foreach ($exp as $elem) {
	        	$names.="'".trim($elem)."',";
	        }	            
	    }
	    $names.="''";
		$query = 'SELECT accountname as accountid FROM vtiger_account WHERE account_type=\'Satelite\' OR account_type=\'Freelance\'';
		if (!$_REQUEST["record"])
		$query.=' AND accountname NOT IN ('.$names.')';	
	} 
	//Fin 18ago2015 

	//RURIEPE - Llenar el picklist de destino en el modulo tarifasaereas
	if($fieldName=="aeropuerto1")
	{
		$fieldName = "aeropuerto";
		$query = "SELECT aeropuerto FROM vtiger_aeropuerto"; 
	} 
	//RURIEPE Fin  

	//jmangarret - Data source de picklist nro de ticket en Registro de Venta
	if($fieldName=="nrodeticket")
	{
		$query = "SELECT ost_ticket.number as nrodeticket FROM osticket1911.ost_ticket 
					WHERE status_id=3 AND topic_id=19 AND ticket_id>16000 ORDER BY ticket_id DESC"; 
	} 
	//jmangarret Fin	
}

?>