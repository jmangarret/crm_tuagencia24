<?
include("librerias.php");
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
$param=explode("::", $_REQUEST["params"]);
$asesor =$param[0];
$status =$param[1];
$f1		=$param[2];

if ($status=="Abiertos")
$sql =" SELECT ost.number, topic_id, created, updated, ost.ticket_id from osticket1911.ost_ticket as ost where created like '%".$f1."%' AND staff_id=".$asesor; 
if ($status=="Cerrados")
$sql=" SELECT ost.number, topic_id, created, updated, ost.ticket_id from osticket1911.ost_ticket where closed like '%".$f1."%' AND staff_id=".$asesor; 
if ($status=="En Progreso")
$sql=" SELECT ost.number, topic_id, created, updated, ost.ticket_id from osticket1911.ost_ticket where status_id=7 and updated like '%".$f1."%' AND staff_id=".$asesor; 
if ($status=="Asignados")
$sql=" SELECT tk.number, tk.topic_id, tk.created, tk.updated, tk.ticket_id from osticket1911.ost_ticket_thread as tt inner join osticket1911.ost_ticket as tk where tt.created like '%".$f1."%' and tt.title like '%asignado a%' AND tt.staff_id=".$asesor; 


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
$query = "SELECT CONCAT(firstname,' ',lastname) FROM osticket1911.ost_staff WHERE staff_id=".$asesor;
$result=mysql_query($query);
$asesor=mysql_fetch_row($result);

			
?>
<div id="resultado">
<h3><?php echo $asesor[0]; ?> - Tickets <?php echo $status; ?> para la fecha <?php echo $f1; ?></h3>
<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Ticket</b></th>
			<th><b>Tema</b></th>
			<th><b>Creado</b></th>
			<th><b>Actualizado</b></th>
			<th><b>Abrir Ticket</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$listado = mysql_query($sql);
		while($reg= mysql_fetch_row($listado))
		{
			$query = "SELECT topic FROM osticket1911.ost_help_topic WHERE topic_id=".$reg[1];
			$result=mysql_query($query);
			$topic=mysql_fetch_row($result);

			echo "<tr>";
			echo "<td>".$reg[0]."</td>";
			echo "<td>".$topic[0]."</td>";
			echo "<td>".$reg[2]."</td>";
			echo "<td>".$reg[3]."</td>";
			echo "<td>";
			echo "<span class='actionImages'>
					<a target='_parent' href='http://ticket.tuagencia24.com/upload/scp/tickets.php?id=".$reg[4]."'>
						<i title='Ver Ticket' class='icon-th-list alignMiddle'></i>
					</a>
				</span>";
			echo "";
			echo "</td>";
			echo "</tr>";
			
		}
		?>
	<tbody>
</table>
</div>

