<?php
/*
include("../../config.inc.php");
$raiz = "/home/jonathan/public_html/vhosts/vtigercrm600/";
include($raiz."modules/Boletos/BoletosFunciones.php");
*/
include("../../config.inc.php");
include("../Boletos/BoletosFunciones.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);
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
	$valpasaportes=0;
	$gds=getLocGds($_REQUEST["idloc"]);	
	if ($gds=="Servi"){
		$essoto=true;
		$response=$gds;
		//Validamos que hallan boletos asociados y que tengan todos pasaporte adjunto
		$boletos=getCantBoletos($_REQUEST["idloc"]);
		$valpasaportes=validarPasaportes($_REQUEST["idloc"]);		

	}
	if ($boletos==0 && $essoto) 		$response="Localizador sin Boletos";
	if ($boletos>0 && $valpasaportes>0) $response="Boletos sin Pasaporte";

	echo $response;
}

?>
