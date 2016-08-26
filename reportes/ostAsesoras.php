<?php
include("conexion.php");
//Lista de asesoras osTickets
$query = "SELECT staff_id, CONCAT(firstname,' ',lastname) FROM osticket1911.ost_staff WHERE isactive=1 AND group_id=4 ORDER BY firstname ASC";
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