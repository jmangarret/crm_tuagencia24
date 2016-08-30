<?
include("librerias.php");
include("conexion.php");

$param=explode("::", $_REQUEST["params"]);
$asesor =$param[0];
$status =$param[1];
$f1		=$param[2];
$f2		=$param[3];
$solic	=$param[4];

if ($status=="Abiertos"){
	$sql =" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk 
			WHERE created between '".$f1."' AND '".$f2."' AND staff_id=".$asesor; 	
	if ($solic){
		$sql =" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk ";
		$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
		$sql.=" WHERE created between '".$f1."' AND '".$f2."' AND staff_id=".$asesor. " AND td.subject='".$solic."'"; 
	}
}

if ($status=="Cerrados"){
	$sql =" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk 
			WHERE closed between '".$f1."' AND '".$f2."' AND staff_id=".$asesor; 
	if ($solic) {
		$sql =" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk "; 
		$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
		$sql.=" WHERE closed between '".$f1."' AND '".$f2."' AND staff_id=".$asesor." AND td.subject='".$solic."'"; 
	}	
}

if ($status=="En Progreso"){
	$sql=" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk 
			WHERE status_id=7 AND updated between '".$f1."' AND '".$f2."' AND staff_id=".$asesor; 
	if ($solic) {
		$sql=" SELECT tk.number, topic_id, created, updated, tk.ticket_id from osticket1911.ost_ticket as tk "; 
		$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
		$sql.=" WHERE status_id=7 AND updated between '".$f1."' AND '".$f2."' AND staff_id=".$asesor." AND td.subject='".$solic."'"; 	
	}	
}

if ($status=="Asignados"){
	$sql=" SELECT tk.number, tk.topic_id, tk.created, tk.updated, tk.ticket_id from osticket1911.ost_ticket_event as tt 
			inner join osticket1911.ost_ticket as tk ON tt.ticket_id=tk.ticket_id 
			WHERE tt.state='assigned' AND tt.timestamp between '".$f1."' AND '".$f2."' AND tt.staff_id=".$asesor; 
	if ($solic) {
		$sql=" SELECT tk.number, tk.topic_id, tk.created, tk.updated, tk.ticket_id from osticket1911.ost_ticket_event as tt 
				inner join osticket1911.ost_ticket as tk ON tt.ticket_id=tk.ticket_id ";
		$sql.=" INNER JOIN osticket1911.ost_ticket__cdata as td ON tk.ticket_id=td.ticket_id ";
		$sql.=" WHERE tt.state='assigned' AND tt.timestamp between '".$f1."' AND '".$f2."'  AND tt.staff_id=".$asesor." 
				AND td.subject='".$solic."'";  	
	}	
}

//echo $sql;

$query = "SELECT CONCAT(firstname,' ',lastname) FROM osticket1911.ost_staff WHERE staff_id=".$asesor;
$result=mysql_query($query);
$asesor=mysql_fetch_row($result);
			
?>
<div id="resultado">
<h3><?php echo $asesor[0]; ?> - Tickets <?php echo $status; ?> desde <?php echo $f1; ?> hasta <?php echo $f2; ?></h3>
<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Item</b></th>
			<th><b>Ticket</b></th>
			<th><b>Tema</b></th>
			<th><b>Creado</b></th>
			<th><b>Actualizado</b></th>
			<th><b>Abrir Ticket</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$item=1;
		$listado = mysql_query($sql);
		while($reg= mysql_fetch_row($listado))
		{
			$query = "SELECT topic FROM osticket1911.ost_help_topic WHERE topic_id=".$reg[1];
			$result=mysql_query($query);
			$topic=mysql_fetch_row($result);
			echo "<tr>";
			echo "<td>".$item."</td>";
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
			$item++;
		}
		?>
	<tbody>
</table>
</div>

