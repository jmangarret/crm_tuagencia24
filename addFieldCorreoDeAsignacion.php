<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
require_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Menu.php');
require_once('vtlib/Vtiger/Block.php');
require_once('vtlib/Vtiger/Field.php');
$Vtiger_Utils_Log = true;
$module=Vtiger_Module::getInstance('Accounts');
$block = Vtiger_Block::getInstance('LBL_ACCOUNT_INFORMATION',$module);

$field0 = new Vtiger_Field();
$field0->name = 'asignacionenviada'; //Usually matches column name
$field0->table = 'vtiger_account';
$field0->column = 'asignacionenviada'; //Must be lower case
$field0->label = 'Asignacion Enviada'; //Upper case preceeded by LBL_
$field0->columntype = 'Varchar(3)'; //
$field0->uitype = 56; //Campo check
$field0->displaytype=2; //Campo check
$field0->typeofdata = 'V~O'; //V=Varchar?, M=Mandatory, O=Optional
$block->addField($field0);


$block->save($module);
$module->initWebservice();
echo 'Code successfully executed';
?>
