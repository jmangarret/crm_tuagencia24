<?
include("conexion.php");

$f1=date("Y")."-09-01 00:00";
$f2=date("Y-m-d")." 23:59";

$hoy=date("Y-m-d");
if ($_REQUEST['desde']) 	$f1=fecha_mysql($_REQUEST['desde'])." 00:00";
if ($_REQUEST['hasta']) 	$f2=fecha_mysql($_REQUEST['hasta'])." 23:59";

$sql="SELECT * FROM registro_boletos.boletos WHERE status_emission='Emitido' AND departureDate>'".$hoy."' ORDER BY departureDate ASC";

//echo $sql;
?>
<div id="resultado">
<h3>Mostrando Resultados Desde <?php echo $hoy; ?></h3>
<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Item</b></th>
			<th><b>Localizador</b></th>
			<th><b>Pasajero</b></th>
			<th><b>Boleto</b></th>
			<th><b>Aerolinea</b></th>
			<th><b>Ruta</b></th>
			<th><b>Salida</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		$listado = mysql_query($sql);
		while($reg= mysql_fetch_array($listado))
		{
			$i++;
			$departureDate=explode(" ", $reg["departureDate"]);
			echo "<tr style='background-color:".$color."'>";
			echo "<td>".$i."</td>";
			echo "<td>".$reg["localizador"]."</td>";
			echo "<td>".$reg["passenger"]."</td>";
			echo "<td>".$reg["ticketNumber"]."</td>";
			echo "<td>".$reg["airlineID"]."</td>";
			echo "<td>".$reg["itinerary"]."</td>";
			echo "<td>".$departureDate[0]."</td>";
			echo "</tr>";
		}
		?>
	<tbody>
</table>
</div>

