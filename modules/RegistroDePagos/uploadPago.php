<?php
include_once("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);

$id=$_REQUEST["id"];
$user=$_REQUEST["user"];
$fecha=date("Y-m-d H:i:s");

$filepath="storage/";
$year  = date('Y');
$month = date('F');
if (!is_dir($filepath . $year)) {
	mkdir($filepath . $year);
}
if (!is_dir($filepath . $year . "/" . $month)) {
	mkdir($filepath . "$year/$month");
}
$output_dir=$filepath;

if(isset($_FILES["myfile"]))
{
	$ret = array();
	
	$error =$_FILES["myfile"]["error"];

	$sql="SELECT CONCAT(referencia,'_',bancoemisor) FROM vtiger_registrodepagos WHERE registrodepagosid=".$id;
	$qry=mysql_query($sql);
	$referencia=mysql_result($qry, 0);

	$IdCrm=mysql_query("CALL getCrmId();");
	$IdCrm=mysql_query("SELECT @idcrm;");
	$resultIdCrm=mysql_fetch_row($IdCrm);
	$crmId=$resultIdCrm[0];

	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 	 	$fileNameST=$crmId."_".$id."_".$fileName;
 	 	$fileNameBD=$id."_".$fileName;
 	 	//$fileName=$id."_".$fileName;
 		//copy($_FILES["myfile"]["tmp_name"],"../../".$output_dir.$fileName);
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileNameST);
    	$ret[]= $fileNameST;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileNameST);
	  	$ret[]= $fileNameST;
	  }
	
	}
	
	$filetype =$_FILES["myfile"]["type"];

	$sqlSetCrm="CALL setCrmEntity('RegistroDePagos Attachment','$referencia','$fecha',$crmId,$user)";
	$setCrm=mysql_query($sqlSetCrm);

	$sql2 ="insert into vtiger_attachments(attachmentsid, name, description, type, path) ";
	$sql2.="values($crmId, '$fileNameBD', NULL, '$filetype', 'modules/RegistrodePagos/$output_dir')";
	mysql_query($sql2);

	//si existe
	$delquery = "delete from vtiger_seattachmentsrel where crmid = $id and attachmentsid = $crmId";
	mysql_query($delquery);
	
	$sql3 = "insert into vtiger_seattachmentsrel values($id, $crmId)";
	mysql_query($sql3);

	chmod("$output_dir.$fileName", 777);
	
    echo json_encode($ret);
 }
 ?>