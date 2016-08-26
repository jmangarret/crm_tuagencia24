<?php
include("conexion.php");
//Lista de asesoras osTickets
$query = "SELECT list_id, value FROM osticket1911.ost_list_items WHERE list_id=6 ORDER BY value ASC";
if($filtro = mysql_query($query))
{
    if (mysql_num_rows($filtro) > 0)
    {
        while ($row = mysql_fetch_row($filtro)) 
        {	
        	echo"<option value='".$row[0]."'>".$row[1]."</option>";		        	
        }
    }
}
?>