<?
include("conexion.php");

$f1=date("Y-m");
$desde=date("Y-m-d")." 00:00";
$hasta=date("Y-m-d")." 23:59";
echo $desde . " - " . $hasta;
$hoy=date("Y-m-d");
if ($_REQUEST['desde']) 	$f1=fecha_mysql($_REQUEST['desde']);
if ($_REQUEST['solicitud']) $sol=$_REQUEST['solicitud'];

//ABIERTOS
$sql=" SELECT staff_id, 'Abiertos' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where created like '%".$f1."%' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor']; 
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//CERRADOS
$sql.=" UNION ALL SELECT staff_id, 'Cerrados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where closed like '%".$f1."%' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//EN PROGRESO
$sql.=" UNION ALL SELECT staff_id, 'En Progreso' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where status_id=7 and updated like '%".$f1."%' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//ASIGNADOS
//timestamp between '2016-08-26 00:00' AND '2016-08-29 23:59'
$sql.=" UNION ALL SELECT  staff_id, 'Asignados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket_event as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where state='assigned' AND timestamp like '%".$f1."%' ";	
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

echo $sql;
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
			$params=$reg[0]."::".$reg[1]."::".$f1."::".$_REQUEST['solicitud'];
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

