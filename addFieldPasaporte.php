<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
require_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Menu.php');
require_once('vtlib/Vtiger/Block.php');
require_once('vtlib/Vtiger/Field.php');
$Vtiger_Utils_Log = true;
$module=Vtiger_Module::getInstance('Boletos');
$block = Vtiger_Block::getInstance('LBL_BLOCK_BOLETOS',$module);

$field1=new Vtiger_Field();
$field1->label='Pasaporte';
$field1->name='pasaporte';
$field1->table='vtiger_boletos';
$field1->column='pasaporte';
$field1->columntype = 'VARCHAR(100)';
$field1->uitype = 69; //file
$field1->typeofdata = 'V~O';
$block->addField($field1);

$block->save($module);
$module->initWebservice();
echo 'Code successfully executed';
?>
