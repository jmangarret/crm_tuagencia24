<?php

include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$module = Vtiger_Module::getInstance('ReporteSatelites');
if ($module) $module->delete();
$del1="modules/".$module;
$del2="languages/en_us/".$module.".php";
$del3="languages/.../".$module.".php";
$del4="layouts/vlayout/modules/".$module;
$del5="cron/".$module;

if (is_dir($del1)) rmdir($del1); else echo "Fallo ".$del1;
if (is_dir($del1)) rmdir($del2); else echo "Fallo ".$del2;
if (is_dir($del1)) rmdir($del3); else echo "Fallo ".$del3;
if (is_dir($del1)) rmdir($del4); else echo "Fallo ".$del4;
if (is_dir($del1)) rmdir($del5); else echo "Fallo ".$del5;

?>
