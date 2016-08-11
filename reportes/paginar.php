<?
$url=$_SERVER['SCRIPT_NAME'];
$url=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
echo "<div align=center>";
if ($totalRegistros>$totRegPorPag) 
{
  $i=0;
  $j=1;   
  echo "<font size=2 face='Verdana, Arial, Helvetica, sans-serif'>P&aacute;gina: ";
  while ($i<$totalRegistros) 
  { 
    if ($pag==$j)
    echo "<a href='$url"."&items=$totRegPorPag&$pag=$j&init=$i'><button name='btn' ><u><b>$j</b></u></button></a>";
    else
    echo "<a href='$url"."&items=$totRegPorPag&pag=$j&init=$i'><button name='btn' >$j</button></a>";
    $j++; 
    $i=$i+$totRegPorPag; 
  }
}
$desde=$init+1;
$hasta=$pag*$totRegPorPag;
echo "<br>Total Registros: ".$totalRegistros." - Mostrando de la posici&oacute;n: $desde al $hasta";
/*
echo "<br>&Iacute;tems por P&aacute;gina: ";
echo "<select name='items' onchange='location.href=\"$url?items=\"+this.value'>";
for ($p=5;$p<=60;$p=$p+5){
    if ($items==$p)
    echo "<option value='$p' selected>$p</option>";    
    else
    echo "<option value='$p'>$p</option>";    
    
}
echo "</select>";
*/
echo "<p>&nbsp;</p>";
echo "<p>&nbsp;</p>";
echo "<p>&nbsp;</p>";
echo "</div>";
?>
