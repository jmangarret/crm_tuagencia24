<?
include("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);
$id= $_GET["id"];
$userid= $_GET["userid"];
$accion= $_GET["accion"];

if ($accion=="anularBoleto"){
	$sqlBoleto="UPDATE vtiger_boletos SET status='Anulado' WHERE boletosid=".$id;
	$qryBoleto=mysql_query($sqlBoleto);
	if (mysql_affected_rows()>0){
		$link= "<a href='index.php?module=Boletos&view=List'>Actualizar Lista</a>";		
		echo "Boleto Anulado con éxito!. $link";
	}	
}
if ($accion=="anularBoletosPorLote"){
	$cont=0;
	if (isset($id))
	foreach ($id as $idBoleto) {
		$sqlBoleto="UPDATE vtiger_boletos SET status='Anulado' WHERE boletosid=".$idBoleto;
		$qryBoleto=mysql_query($sqlBoleto);
		if (mysql_affected_rows()>0){
			$cont++;
		}
	}
	if ($cont>0){
		$link= "<a href='index.php?module=Boletos&view=List'>Actualizar Lista</a>";		
		echo "$cont Boleto(s) Anulado(s) con éxito!. $link";
	}				
}

if ($accion=="buscarClientePorBoletoId"){	
	$sqlCliente ="SELECT accountname FROM vtiger_account AS a ";
	$sqlCliente.="INNER JOIN vtiger_contactdetails 	AS c ON a.accountid=c.accountid ";
	$sqlCliente.="INNER JOIN vtiger_localizadores 	AS l ON l.contactoid=c.contactid ";
	$sqlCliente.="INNER JOIN vtiger_boletos 		AS b ON b.localizadorid=l.localizadoresid ";
	$sqlCliente.="WHERE boletosid=".$id;	
	$qryCliente=mysql_query($sqlCliente);
	$resCliente=mysql_fetch_row($qryCliente);
	$rowCliente=$resCliente[0];
	//$rowCliente=mysql_result($qryCliente, 0);

	$sqlContacto ="SELECT CONCAT(firstname,' ',lastname) FROM vtiger_contactdetails AS c ";	
	$sqlContacto.="INNER JOIN vtiger_localizadores 	AS l ON l.contactoid=c.contactid ";
	$sqlContacto.="INNER JOIN vtiger_boletos 		AS b ON b.localizadorid=l.localizadoresid ";
	$sqlContacto.="WHERE boletosid=".$id;
	$qryContacto=mysql_query($sqlContacto);	
	$resContacto=mysql_fetch_row($qryContacto);
	$rowContacto=$resContacto[0];
	//$rowContacto=mysql_result($qryContacto, 0);
	
	$cliente = (isset($rowCliente) ? $rowCliente."/ ".$rowContacto : $rowContacto);
	echo $cliente;
}

?>
