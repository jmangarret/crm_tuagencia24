<?
include("conexion.php");

//$f1=date("Y-m");
$f1=date("Y-m-d")." 00:00";
$f2=date("Y-m-d")." 23:59";

$hoy=date("Y-m-d");
if ($_REQUEST['desde']) 	$f1=fecha_mysql($_REQUEST['desde'])." 00:00";
if ($_REQUEST['hasta']) 	$f2=fecha_mysql($_REQUEST['hasta'])." 23:59";
if ($_REQUEST['solicitud']) $solic=$_REQUEST['solicitud'];
/*
//ABIERTOS
$sql=" SELECT staff_id, 'Abiertos' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where created between '".$f1."' AND '".$f2."' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor']; 
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//ASIGNADOS
//timestamp between '2016-08-26 00:00' AND '2016-08-29 23:59'
$sql.=" UNION ALL SELECT  staff_id, 'Asignados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket_event as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where state='assigned' AND timestamp between '".$f1."' AND '".$f2."' ";	
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//EN PROGRESO
$sql.=" UNION ALL SELECT staff_id, 'En Progreso' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where status_id=7 and updated between '".$f1."' AND '".$f2."' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";

//CERRADOS
$sql.=" UNION ALL SELECT staff_id, 'Cerrados' AS Status,count(*) AS TotalMes  from osticket1911.ost_ticket as tk ";
if ($_REQUEST['solicitud']) $sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" where closed between '".$f1."' AND '".$f2."' ";
if ($_REQUEST['asesor']) 	$sql.= " AND staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']) $sql.= " AND td.subject = '".$solic."'"; 
$sql.=" GROUP BY staff_id ";
*/

//NUEVO SQL CONJUNTO CREADOS

$sql="SELECT tk.staff_id, CONCAT(firstname,' ',lastname) as name, 'Creados' as status, count(*) AS Total, status_id 
	FROM  osticket1911.ost_staff as st 
	INNER JOIN osticket1911.ost_ticket AS tk ON tk.staff_id=st.staff_id
	INNER JOIN osticket1911.ost_ticket_status as ts ON tk.status_id=ts.id";
if ($_REQUEST['solicitud']) 
$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" WHERE tk.created between '".$f1."' AND '".$f2."'";
if ($_REQUEST['asesor']) 	
$sql.=" AND tk.staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']){
	if ($_REQUEST['solicitud']==88) 
		$sql.=" AND (td.subject = '".$solic."' OR td.subject='88,Cotizacion PopPup') "; 
	else
		$sql.=" AND td.subject = '".$solic."'"; 
}
$sql.=" GROUP BY staff_id ";

$sql.=" UNION ALL SELECT tk.staff_id, CONCAT(firstname,' ',lastname) as name, ts.name as status, count(*) AS Total, status_id 
	FROM  osticket1911.ost_staff as st 
	INNER JOIN osticket1911.ost_ticket AS tk ON tk.staff_id=st.staff_id
	INNER JOIN osticket1911.ost_ticket_status as ts ON tk.status_id=ts.id";
if ($_REQUEST['solicitud']) 
$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
$sql.=" WHERE tk.created between '".$f1."' AND '".$f2."'";
if ($_REQUEST['asesor']) 	
$sql.=" AND tk.staff_id=".$_REQUEST['asesor'];
if ($_REQUEST['solicitud']){
	if ($_REQUEST['solicitud']==88) 
		$sql.=" AND (td.subject = '".$solic."' OR td.subject='88,Cotizacion PopPup') "; 
	else
		$sql.=" AND td.subject = '".$solic."'"; 
}
$sql.=" GROUP BY status_id, staff_id";

//echo $sql;
?>
<div id="resultado">
<h3>Mostrando Resultados Desde <?php echo $f1; ?> Hasta <?php echo $f2; ?></h3>
<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Asesor</b></th>
			<th><b>Status</b></th>
			<th><b>Total</b></th>
			<th><b>Acum.</b></th>
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
			/*
			$query = "SELECT CONCAT(firstname,' ',lastname) FROM osticket1911.ost_staff WHERE staff_id=".$reg[0];
			$result=mysql_query($query);
			$asesor=mysql_fetch_row($result);			
			*/
			switch ($reg[2]) {
			    case 'Abierto':
			        $color="#81BEF7";			        
			        break;
			    case 'Creados':
			        $color="#F5F6CE";
			        break;
			    case 'En Progreso':
			        $color="#A9F5A9";
			        break;
			    case 'Cerrado':
			        $color="#F6CEE3";
			        break;
			}
			//SQL ACUMULADOS
			$sqlAcum="SELECT COUNT(*) FROM  osticket1911.ost_staff as st 
						INNER JOIN osticket1911.ost_ticket AS tk ON tk.staff_id=st.staff_id
						INNER JOIN osticket1911.ost_ticket_status as ts ON tk.status_id=ts.id";
			if ($_REQUEST['solicitud']) 
				$sqlAcum.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
			$sqlAcum.=" WHERE tk.staff_id=".$reg[0];
			if ($reg[2]<>'Creados')
			$sqlAcum.=" AND ts.name='".$reg[2]."'";
			if ($_REQUEST['solicitud']){
				if ($_REQUEST['solicitud']==88) 
					$sqlAcum.=" AND (td.subject = '".$solic."' OR td.subject='88,Cotizacion PopPup') "; 
				else
					$sqlAcum.=" AND td.subject = '".$solic."'"; 
			}
			$qryAcum=mysql_query($sqlAcum);
			$rowAcum=mysql_fetch_row($qryAcum);
			$acum=$rowAcum[0];

			echo "<tr style='background-color:".$color."'>";
			echo "<td>".$reg[1]."</td>";
			echo "<td>".$reg[2]."</td>";
			echo "<td>".$reg[3]."</td>";
			echo "<td>".$acum."</td>";
			echo "<td>";
			$params=$reg[0]."::".$reg[2]."::".$f1."::".$f2."::0::".$_REQUEST['solicitud'];
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

