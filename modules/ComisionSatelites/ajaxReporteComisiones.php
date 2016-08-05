<?php

	include("../../config.inc.php");

	$user=$dbconfig['db_username'];
	$pass=$dbconfig['db_password'];
	$bd=$dbconfig['db_name'];
	mysql_connect("localhost",$user,$pass);
	mysql_select_db($bd);

	//RUIEPE 04/08/2016 - SELECT SATELITE
	if ($_REQUEST["accion"]=="select_satelite")
	{
		$query = "SELECT accountid, accountname FROM `vtiger_account` 
		WHERE `account_type` LIKE 'Satelite' ORDER BY accountname ASC ";

		if($filtro = mysql_query($query))
		{
		    if (mysql_num_rows($filtro) > 0)
		    {
		        while ($row = mysql_fetch_array($filtro)) 
		        {	
		        	if ($row["accountname"]!=NULL) {
		        		echo"<option value=".$row["accountid"].">".$row["accountname"]."</option>";
		        	}
		        }
		    }
		}
	}

	//RURIEPE 04/08/2016 - SELECT TIPOS DE COMSIONES
	if ($_REQUEST["accion"]=="select_tcomision")
	{
		$query = "SELECT tiposdecomisionesid,nombre FROM vtiger_tiposdecomisiones
		ORDER BY nombre ASC";

		if($filtro = mysql_query($query))
		{
		    if (mysql_num_rows($filtro) > 0)
		    {
		         while ($row = mysql_fetch_array($filtro)) 
		        {	
		        	if ($row["nombre"]!=NULL) {
		        		echo"<option value=".$row["tiposdecomisionesid"].">".$row["nombre"]."</option>";
		        	}
		        }
		    }
		}
	}

	//RESPONSE LISTAR RESULTADOS DE LA BUSQUEDA
	if ($_REQUEST["accion"]=="listarBusqueda")
	{
		
		//CONSULTA QUE SE REALILZA SI EL $_REQUEST["satelite"] ES DIFERENTE DE VACIO
		if ($_REQUEST["satelite"]!="" AND $_REQUEST["tcomision"]!="")
		{

			$query="SELECT cs.tipodeformula,cs.base,cs.activa,acc.accountname,tc.nombre
			FROM vtiger_comisionsatelites AS cs
			INNER JOIN vtiger_account AS acc ON acc.accountid = cs.accountid
			INNER JOIN vtiger_tiposdecomisiones AS tc ON tc.tiposdecomisionesid = cs.tipodecomisionid 
			WHERE cs.accountid='".$_REQUEST["satelite"]."' AND cs.tipodecomisionid='".$_REQUEST['tcomision']."'";
		}
		else if ($_REQUEST["tcomision"]!="")
		{
			$query="SELECT cs.tipodeformula,cs.base,cs.activa,acc.accountname,tc.nombre
			FROM vtiger_comisionsatelites AS cs
			INNER JOIN vtiger_account AS acc ON acc.accountid = cs.accountid
			INNER JOIN vtiger_tiposdecomisiones AS tc ON tc.tiposdecomisionesid = cs.tipodecomisionid 
			WHERE cs.tipodecomisionid='".$_REQUEST["tcomision"]."' ";
		
		}
		else if ($_REQUEST["satelite"]!="")
		{
			$query="SELECT cs.tipodeformula,cs.base,cs.activa,acc.accountname,tc.nombre
			FROM vtiger_comisionsatelites AS cs
			INNER JOIN vtiger_account AS acc ON acc.accountid = cs.accountid
			INNER JOIN vtiger_tiposdecomisiones AS tc ON tc.tiposdecomisionesid = cs.tipodecomisionid 
			WHERE cs.accountid='".$_REQUEST["satelite"]."' ";
		
		}
		else 
		{
			$query="SELECT cs.tipodeformula,cs.base,cs.activa,acc.accountname,tc.nombre
			FROM vtiger_comisionsatelites AS cs
			INNER JOIN vtiger_account AS acc ON acc.accountid = cs.accountid
			INNER JOIN vtiger_tiposdecomisiones AS tc ON tc.tiposdecomisionesid = cs.tipodecomisionid";
		
		}
?>

<div class="bottomscroll-div">
	<input type="hidden" value="" id="orderBy">
	<input type="hidden" value="" id="sortOrder">
	<span class="listViewLoadingImageBlock hide modal noprint" id="loadingListViewModal">
		<img class="listViewLoadingImage" src="layouts/vlayout/skins/softed/images/loading.gif" alt="no-image" title="Cargando..."/>
		<p class="listViewLoadingMsg">Cargando, por favor espera.........</p>
	</span>

<table class="table table-bordered listViewEntriesTable">
	<thead>
		<tr class="listViewHeaders">

			<!--Label para check-->
			<th width="5%" class="wide">
			<input type="checkbox" id="listViewEntriesMainCheckBox"/></th>

			<!--Label para el filtro localizador-->
			<th nowrap  class="wide"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="ASC" data-columnname="localizador">Cuenta Satélite&nbsp;&nbsp;</a></th>

			<!--Label para el filtro procesado-->
			<th nowrap  class="wide"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="ASC" data-columnname="procesado">Tipo de Comisión&nbsp;&nbsp;</a></th>

			<!--Label para el filtro gds-->
			<th nowrap  class="wide"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="ASC" data-columnname="gds">Tipo de Fórmula&nbsp;&nbsp;</a></th>

			<!--Label para el filtro asignado a-->
			<th nowrap  class="wide"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="ASC" data-columnname="status">Base de Comisión&nbsp;&nbsp;</a></th>
		
			<!--Label para el filtro boleto-->
			<th nowrap colspan="2" class="wide"><a href="javascript:void(0);" class="listViewHeaderValues" data-nextsortorderval="ASC" data-columnname="boleto1">Activa&nbsp;&nbsp;</a></th>
		</tr>
	</thead>
<?php



if($filtro = mysql_query($query))
		{
		    if (mysql_num_rows($filtro) > 0)
		    {
		        while ($row = mysql_fetch_array($filtro)) 
		        {	
		        	
?>

	<tr class="listViewEntries" data-id='<?=$row["comisionsatelitesid"]?>' data-recordUrl='index.php?module=Localizadores&view=Detail&record=<?=$row["comisionsatelitesid"]?>' id="Localizadores_listView_row_1">

	<!--Check para cada fila-->
	<td  width="5%" class="wide">
		<input type="checkbox" value="<?=$row["comisionsatelitesid"]?>" class="listViewEntriesCheckBox"/>
	</td>

	<!--Array para mostrar campo localizador-->
	<td class="listViewEntryValue wide" data-field-type="string" data-field-name="localizador" nowrap><?=$row["accountname"]?></td>


	<!--Array para mostrar campo localizador-->
	<td class="listViewEntryValue wide" data-field-type="string" data-field-name="localizador" nowrap><?=$row["nombre"]?></td>


	<!--Array para mostrar campo localizador-->
	<td class="listViewEntryValue wide" data-field-type="string" data-field-name="localizador" nowrap><?=$row["tipodeformula"]?></td>

		<!--Array para mostrar campo Tarifa-->
	<td class="listViewEntryValue wide" data-field-type="currency" data-field-name="amount" nowrap><?=number_format($row["base"], 2, '.', ',');?></td>

	<!--Array para mostrar campo localizador-->
	<td class="listViewEntryValue wide" data-field-type="string" data-field-name="localizador" nowrap><?=$row["activa"]?></td>



	<td nowrap class="wide">
		<div class="actions pull-right">
			<span class="actionImages">
				<a href="index.php?module=ComisionSatelite&view=Detail&record=<?=$row["comisionsatelitesid"]?>&mode=showDetailViewByMode&requestMode=full">
					<i title="Complete Details" class="icon-th-list alignMiddle"></i>
				</a>&nbsp;

				<a href='index.php?module=ComisionSatelite&view=Edit&record=<?=$row["comisionsatelitesid"]?>'>
					<i title="Editar" class="icon-pencil alignMiddle"></i>
				</a>
			</span>
		</div>
	</td>
</tr>
<?php
	}
		}
			}
			else
			{
				echo "Error en la consulta SQL: ".mysql_error();
			}
?>

</table>	
</div>

<?php 	
	}
	///FIN RESPONSE LISTAR RESULTADOS DE LA BUSQUEDA
?>
		