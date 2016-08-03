<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);
require_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Menu.php');
require_once('vtlib/Vtiger/Block.php');
require_once('vtlib/Vtiger/Field.php');
$Vtiger_Utils_Log = true;
$module=Vtiger_Module::getInstance('Boletos');
$block = Vtiger_Block::getInstance('LBL_BLOCK_GENERAL_INFORMATION',$module);
/*
$field0 = new Vtiger_Field();
$field0->name = 'porcentaje'; //Usually matches column name
$field0->table = 'vtiger_boletos';
$field0->column = 'porcentaje'; //Must be lower case
$field0->label = 'Fee Satelite'; //Upper case preceeded by LBL_
$field0->columntype = 'DOUBLE(8,2)'; //
$field0->uitype = 71; //Campo moneda
$field0->displaytype = 2; //Campo oculto solo detalle
$field0->typeofdata = 'N~O'; //V=Varchar?, M=Mandatory, O=Optional
$block->addField($field0);

$block->save($module);
$module->initWebservice();
echo 'Code successfully executed';
*/
?>
