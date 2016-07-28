<?php
//Script PHP para ser ejecutado a traves de un cron linux los primeros de cada mes
include("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);

$fecha = date("Y-m-d"); 
$mes = date("m",strtotime($fecha));       //Obtengo el mes de la fecha
$año = date("Y",strtotime($fecha));       //Obtengo el año de la fecha

function getUltimoDiaMes($elAnio,$elMes) {
  return date("d",(mktime(0,0,0,$elMes+1,1,$elAnio)-1));
}
$ultimoDia = getUltimoDiaMes($año,$mes);

$ultimoid = "SELECT MAX(calendariobspid)+1 FROM vtiger_calendariobsp";  //Obtengo el ultimo id + 1 de la tabla vtiger_calendariobsp

$desde1 = $año. "-".$mes."-01"; //Concateno el mes y el año actual para la fecha desde1
$hasta1 = $año. "-".$mes."-07"; //Concateno el mes y el año actual para la fecha hasta1

$desde2 = $año. "-".$mes."-08"; //Concateno el mes y el año actual para la fecha desde2
$hasta2 = $año. "-".$mes."-15"; //Concateno el mes y el año actual para la fecha hasta2

$desde3 = $año. "-".$mes."-16"; //Concateno el mes y el año actual para la fecha desde3
$hasta3 = $año. "-".$mes."-22"; //Concateno el mes y el año actual para la fecha hasta3

$desde4 = $año. "-".$mes."-23"; //Concateno el mes y el año actual para la fecha desde4
$hasta4 = $año. "-".$mes."-".$ultimoDia; //Concateno el mes y el año actual para la fecha hasta4



//Inserto en la base de datos los registros para crear los 4 calendario mensuales
//calendario 1
mysql_query("CALL getNameReporte('$desde1','$hasta1');");
mysql_query("CALL getCrmId();");
mysql_query("CALL setCrmEntity('CalendarioBSP', @NOMBREREPORTE, '$fecha', @idcrm, 1);");
$query = "INSERT INTO vtiger_calendariobsp (calendariobspid, nombre, fecha_desde, fecha_hasta)
			VALUES (@idcrm,@NOMBREREPORTE,'$desde1','$hasta1')";
$result = mysql_query($query);

//calendario 2
mysql_query("CALL getNameReporte('$desde2','$hasta2');");
mysql_query("CALL getCrmId();");
mysql_query("CALL setCrmEntity('CalendarioBSP', @NOMBREREPORTE, '$fecha', @idcrm, 1);");
$query = "INSERT INTO vtiger_calendariobsp (calendariobspid, nombre, fecha_desde, fecha_hasta)
			VALUES (@idcrm,@NOMBREREPORTE,'$desde2','$hasta2')";
$result = mysql_query($query);

//calendario 3
mysql_query("CALL getNameReporte('$desde3','$hasta3');");
mysql_query("CALL getCrmId();");
mysql_query("CALL setCrmEntity('CalendarioBSP', @NOMBREREPORTE, '$fecha', @idcrm, 1);");
$query = "INSERT INTO vtiger_calendariobsp (calendariobspid, nombre, fecha_desde, fecha_hasta)
			VALUES (@idcrm,@NOMBREREPORTE,'$desde3','$hasta3')";
$result = mysql_query($query);

//calendario 4
mysql_query("CALL getNameReporte('$desde4','$hasta4');");
mysql_query("CALL getCrmId();");
mysql_query("CALL setCrmEntity('CalendarioBSP', @NOMBREREPORTE, '$fecha', @idcrm, 1);");
$query = "INSERT INTO vtiger_calendariobsp (calendariobspid, nombre, fecha_desde, fecha_hasta)
			VALUES (@idcrm,@NOMBREREPORTE,'$desde4','$hasta4')";
$result = mysql_query($query);

if (mysql_affected_rows()>0){
	echo "Registro insertado...";
}else{
	echo mysql_error().$query;	
}

?>