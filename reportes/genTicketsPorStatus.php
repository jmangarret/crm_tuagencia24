<?
include("conexion.php");
$options="";		
$items=$_REQUEST["items"];
$init=$_REQUEST["init"];
$pag=$_REQUEST["pag"];
//Condiciones e Inicializacion de Variables para la paginacion, luego estas variables son enviadas por la url al paginar resultados
if (!$items) $items=100; //Total de Registros por Pagina $totRegPag
$totRegPorPag=$items;
if (!$init) $init=0; //Posicion 0, Primer registro. Inicio de lote de registros, a partir de que num de registro se muestra el query
if (!$pag) $pag=1; // Num. de pagina segun el total de registros y el totRegPag.

date_default_timezone_set("America/Caracas");
include_once("phpReportGen.php");
$prg = new phpReportGenerator();
$prg->width = "auto";
$prg->cellpad = "0";
$prg->cellspace = "0";
$prg->border = "1";
//$prg->header_color = "#666666";
//$prg->header_textcolor="#FFFFFF";
$prg->body_alignment = "left";
//$prg->body_color = "#CCCCCC";
//$prg->body_textcolor = "black";
$prg->surrounded = '1';

$f1=date("Y-m");

$sql="	
SELECT  'Abiertos' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where created like '%".$f1."%'
UNION ALL
SELECT  'Cerrados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where closed like '%".$f1."%'
UNION ALL
SELECT  'En Progreso' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where status_id=7 and updated like '%".$f1."%'
UNION ALL
SELECT  'Asignados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket_thread where created like '%".$f1."%' and title like '%asignado a%'
";	
//POR FECHA
//if ($_REQUEST['desde'] && $_REQUEST['hasta']){
if ($_REQUEST['desde']){
	$f1=fecha_mysql($_REQUEST['desde']);
	$f2=fecha_mysql($_REQUEST['hasta']);
	$sql="	
	SELECT  'Abiertos' AS Status,count(*) AS TotalFecha  from osticket1911.ost_ticket where created like '%".$f1."%'
	UNION ALL
	SELECT  'Cerrados' AS Status,count(*) AS TotalFecha  from osticket1911.ost_ticket where closed like '%".$f1."%'
	UNION ALL
	SELECT  'En Progreso' AS Status,count(*) AS TotalFecha  from osticket1911.ost_ticket where status_id=7 and updated like '%".$f1."%'
	UNION ALL
	SELECT  'Asignados' AS Status,count(*) AS TotalFecha  from osticket1911.ost_ticket_thread where created like '%".$f1."%' and title like '%asignado a%'
	";	

}	
//$sql.= "order by ticket_number DESC, org_name DESC ";

//detectamos cuantos registros son en general para totalizar y calcular
//$listado= mysql_query($sql);
//$totalRegistros=mysql_num_rows($listado); 
//Consultamos los registros reales a mostrar segun la paginacion.	

//$sql.=" limit $init, $totRegPorPag ";

//echo $sql;

//echo $_REQUEST['desde'] . $_REQUEST['hasta'];

$res = mysql_query($sql);
$prg->mysql_resource = $res;	
$prg->title = "Reporte osTickets por Status";
$prg->generateReport();

//include("paginar.php");

?>
