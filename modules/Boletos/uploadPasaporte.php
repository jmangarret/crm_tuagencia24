<?php
function initStorageFileDirectory() {
	$filepath = 'storage/';

	$year  = date('Y');
	$month = date('F');
	$day   = date('j');
	$week  = '';

	if (!is_dir($filepath . $year)) {
		//create new folder
		mkdir($filepath . $year);
	}

	if (!is_dir($filepath . $year . "/" . $month)) {
		//create new folder
		mkdir($filepath . "$year/$month");
	}

	if ($day > 0 && $day <= 7)
		$week = 'week1';
	elseif ($day > 7 && $day <= 14)
		$week = 'week2';
	elseif ($day > 14 && $day <= 21)
		$week = 'week3';
	elseif ($day > 21 && $day <= 28)
		$week = 'week4';
	else
		$week = 'week5';

	if (!is_dir($filepath . $year . "/" . $month . "/" . $week)) {
		//create new folder
		mkdir($filepath . "$year/$month/$week");
	}

	$filepath = $filepath . $year . "/" . $month . "/" . $week . "/";

	return $filepath;
}
//$output_dir = "uploads/";
$output_dir = initStorageFileDirectory();
$id=$_REQUEST["id"];
$user=$_REQUEST["user"];
$fecha=echo date("Y-m-d H:i:s");
if(isset($_FILES["myfile"]))
{
	$ret = array();
	
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 	 	$fileName=$id."_".$fileName
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
	$filetype =$_FILES["myfile"]["type"];
	//Buscamos nuevo ID crmentity para el adjuntp
	$sql_current_id="SELECT MAX(crmid) AS crmid FROM vtiger_crmentity";
	$qry_current_id=mysql_query($sql_current_id);
	$current_id=mysql_result($qry_current_id, "crmid");

	//Creamos registro asociado del CRM
	$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) ";
	$sql1.="values($current_id, $user, $user, 'Boletos Attachment', 'Pasaporte adjunto','$fecha','$fecha')";
	mysql_query($sql1);

	$sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path)";
	$sql2.=" values($current_id, '$fileName', NULL, '$filetype', '$output_dir')";
	mysql_query($sql2);

	//si existe
	$delquery = 'delete from vtiger_seattachmentsrel where crmid = $id and attachmentsid = $current_id';
	mysql_query($delquery);
	
	$sql3 = 'insert into vtiger_seattachmentsrel values($id, $current_id)';
	mysql_query($sql3);

    echo json_encode($ret);
 }
 ?>