<?php
include_once('../../config.inc.php');   
$con = mysql_connect($dbconfig['db_server'],$dbconfig['db_username'],$dbconfig['db_password']);
$db  = mysql_select_db($dbconfig['db_name']);
include_once('../Boletos/BoletosFunciones.php');   
//accion getNumBoletos 
if ($_REQUEST["accion"]=="getNumBoletos"){
	$qry=mysql_query("select boleto1 from vtiger_boletos where localizadorid=".$_REQUEST["idloc"]);
	$cont=0;
	while ($row=mysql_fetch_row($qry)){
		$cont++;
		$boletos.=$row[0]. "  ";
		
	}
	if ($cont>0)
		echo "$cont Boleto(s): ".$boletos;
	else
		echo "Sin boletos asociados...";
}

//accion valBoletosSoto
if ($_REQUEST["accion"]=="valBoletosSoto"){
	$essoto=false;
	$boletos=0;
	$sinpasaporte=0;
	$gds=getLocGds($_REQUEST["idloc"]);	
	if ($gds=="Servi"){
		$essoto=true;
		$response=$gds;
		//Validamos que hallan boletos asociados y que tengan pasaporte adjunto
		$qry=mysql_query("select boletosid, pasaporte from vtiger_boletos where localizadorid=".$_REQUEST["idloc"]);		
		while ($row=mysql_fetch_row($qry)){
			$boletos++;
			if ($row[1]=="")
				$sinpasaporte++;			
		}			
	}//fin gds
	if ($boletos==0 && $essoto) 		$response="Localizador sin Boletos";
	if ($boletos>0 && $sinpasaporte>0) 	$response="Boletos sin Pasaporte";

	echo $response;
}

?>
