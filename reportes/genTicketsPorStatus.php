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
/*
date_default_timezone_set("America/Caracas");
include_once("phpReportGen.php");
$prg = new phpReportGenerator();
$prg->widtd = "auto";
$prg->cellpad = "0";
$prg->cellspace = "0";
$prg->border = "1";
//$prg->header_color = "#666666";
//$prg->header_textcolor="#FFFFFF";
$prg->body_alignment = "left";
//$prg->body_color = "#CCCCCC";
//$prg->body_textcolor = "black";
$prg->surrounded = '1';
*/
$f1=date("Y-m");
if ($_REQUEST['desde']) $f1=fecha_mysql($_REQUEST['desde']);

$sql=" SELECT staff_id, 'Abiertos' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where created like '%".$f1."%' ";
if ($_REQUEST['asesor']) $sql.= " AND staff_id=".$_REQUEST['asesor']; 
$sql.=" GROUP BY staff_id ";

$sql.=" UNION ALL SELECT staff_id, 'Cerrados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where closed like '%".$f1."%' ";
if ($_REQUEST['asesor']) $sql.= " AND staff_id=".$_REQUEST['asesor'];
$sql.=" GROUP BY staff_id ";

$sql.=" UNION ALL SELECT staff_id, 'En Progreso' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket where status_id=7 and updated like '%".$f1."%' ";
if ($_REQUEST['asesor']) $sql.= " AND staff_id=".$_REQUEST['asesor'];
$sql.=" GROUP BY staff_id ";

$sql.=" UNION ALL SELECT  staff_id, 'Asignados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket_thread where created like '%".$f1."%' and title like '%asignado a%' ";	
if ($_REQUEST['asesor']) $sql.= " AND staff_id=".$_REQUEST['asesor'];
$sql.=" GROUP BY staff_id ";


//$sql.= "order by ticket_number DESC, org_name DESC ";

//detectamos cuantos registros son en general para totalizar y calcular
//$listado= mysql_query($sql);
//$totalRegistros=mysql_num_rows($listado); 
//Consultamos los registros reales a mostrar segun la paginacion.	

//$sql.=" limit $init, $totRegPorPag ";

//echo $sql;

//echo $_REQUEST['desde'] . $_REQUEST['hasta'];
/*
$res = mysql_query($sql);
$prg->mysql_resource = $res;	
$prg->title = "Reporte osTickets por Status";
$prg->generateReport();
*/
//include("paginar.php");

?>
<div id="resultado">
<h3>Mostrando resultados para la fecha <?php echo $f1; ?></h3>
<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Asesor</b></th>
			<th><b>Status</b></th>
			<th><b>Total</b></th>
			<th><b>Detalle</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		$listado = mysql_query($sql);
		while($reg= mysql_fetch_row($listado))
		{
			$i++;
			$query = "SELECT CONCAT(firstname,' ',lastname) FROM osticket1911.ost_staff WHERE staff_id=".$reg[0];
			$result=mysql_query($query);
			$asesor=mysql_fetch_row($result);
			echo "<tr>";
			echo "<td>".$asesor[0]."</td>";
			echo "<td>".$reg[1]."</td>";
			echo "<td nowrap>".$reg[2]."</td>";
			echo "<td>";
			$params=$reg[0]."::".$reg[1]."::".$f1;
			echo "<span class='actionImages'>
					<a href='#' onclick='javascript:window.open(\"reportes/detTicketsPorStatus.php?params=".$params."\",1, \"type=fullWindow,fullscreen,scrollbars=yes,toolbar=yes\")' >
						<i title='Ver Detalles' class='icon-th-list alignMiddle'></i>
					</a>
				  </span>";
			echo "</td>";
			echo "</tr>";


		}
		?>
	<tbody>
</table>
</div>

