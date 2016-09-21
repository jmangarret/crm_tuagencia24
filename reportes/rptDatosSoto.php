<?php
include('../config.inc.php');
/*
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/config.inc.php');
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/include/PHPMailer/class.phpmailer2.php');
include('/var/www/vhosts/registro.tuagencia24.com/vtigercrm/include/PHPMailer/enviar_email_cron.php');
*/
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect('localhost',$user,$pass);
mysql_select_db($bd);
//LOC 17118 PROD 21238
$apartirde=23781;

//SQL PARA VENTAS SIN LOCALIZADORES
$sql="	SELECT 'RegistroDeVentas' AS Modulo, 'Venta sin Localizador' AS Error, e.crmid,r.registrodeventasname as referencia,e.smownerid				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_registrodeventas AS r ON r.registrodeventasid=e.crmid 				
		WHERE r.registrodeventastype LIKE '%Boleto%' AND e.deleted = 0 AND e.crmid>$apartirde AND e.crmid NOT IN 
		(SELECT vtiger_crmentityrel.crmid
			FROM vtiger_crmentity
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.relmodule='Localizadores' AND e.crmid>$apartirde 
		)";

//SQL PARA LOCALIZADORES SOTO SIN BOLETOS
$sql.=" UNION ";
$sql.="	SELECT 'Localizadores' AS Modulo, 'Localizador sin Boletos' AS Error, e.crmid,l.localizador as referencia,e.smownerid				
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_localizadores AS l ON l.localizadoresid=e.crmid 				
		WHERE l.gds LIKE '%Servi%' AND e.deleted = 0 AND e.crmid>$apartirde AND e.crmid NOT IN 
		(SELECT vtiger_crmentityrel.crmid
			FROM vtiger_crmentity
			INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentityrel.relmodule='Boletos' AND e.crmid>$apartirde 
		)";


//SQL PARA VALIDAR NUM. DE DIGITOS DEL BOLETO, DEBE TENER MINIMO 13 CARACTERES O SER MAYOR QUE CERO
$sql.=" UNION ";
$sql.="	SELECT 'Boletos' AS Modulo, 'Num. de Boleto Errado' AS Error, e.crmid,b.boleto1 as referencia,e.smownerid
		FROM  vtiger_crmentity AS e
		INNER JOIN vtiger_boletos AS b ON b.boletosid=e.crmid 	
		INNER JOIN vtiger_localizadores AS l ON l.localizadoresid=b.localizadorid					
		WHERE (LENGTH(boleto1) <13 OR CAST(boleto1 AS UNSIGNED)<1) AND e.deleted = 0 AND e.crmid>$apartirde 		
		";
//echo $sql;
?>

<div id="resultado">

<table width="60%" align=center class="table table-bordered table-hover">
	<thead>
		<tr class="listViewHeaders"> 
			<th><b>Item</b></th>
			<th><b>Modulo</b></th>
			<th><b>Error</b></th>
			<th><b>Referencia</b></th>			
			<th><b>Usuario</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		$listado = mysql_query($sql);
		while($reg= mysql_fetch_row($listado))
		{
			$i++;
			$sqlUser="SELECT CONCAT(first_name,' ',last_name) as name FROM vtiger_users WHERE id=".$reg[4];
			$qryUser=mysql_query($sqlUser);
			$usuario=mysql_result($qryUser, 0, 'name');
			echo "<tr style='background-color:".$color."'>";
			echo "<td>".$i."</td>";
			echo "<td>";
			echo "<a target=_blank href='index.php?module=".$reg[0]."&view=Detail&record=".$reg[2]."'>";
			echo $reg[0];
			echo "</a>";
			echo "</td>";
			echo "<td>".$reg[1]."</td>";
			echo "<td>".$reg[3]."</td>";
			echo "<td>".$usuario."</td>";
			echo "</tr>";
		}		
		?>
	<tbody>
</table>
</div>