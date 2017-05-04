<?php
//jmangarret nov2016 - Invocado desde DetailViewBlockView.tpl para consultar ticket por Num y no por ID del Ticket
include_once("../../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);
$sqlTicket="SELECT ticket_id FROM osticket1911.ost_ticket WHERE ost_ticket.number LIKE '%".$_REQUEST["number"]."%'";
$qryTicket=mysql_query($sqlTicket);
$ticket_id=mysql_result($qryTicket, 0);
header("location: http://ticket.tuagencia24.com/upload/scp/tickets.php?id=".$ticket_id);
?>