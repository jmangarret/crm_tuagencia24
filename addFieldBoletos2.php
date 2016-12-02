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

$block = new Vtiger_Block();					
$block->label = 'LBL_PASAPORTE_INFORMATION';		
$module->addBlock($block);	

//$block = Vtiger_Block::getInstance('LBL_BLOCK_BOLETOS',$module);

$field1=new Vtiger_Field();
$field1->label='Pasaporte';
$field1->name='pasaporte';
$field1->table='vtiger_boletos';
$field1->column='pasaporte';
$field1->columntype = 'VARCHAR(100)';
$field1->uitype = 69; //file
$field1->typeofdata = 'V~M';
$block->addField($field1);

/*
$field0=new Vtiger_Field();
$field0->label='Pasaporte Verificado';
$field0->name='pasaportecheck';
$field0->table='vtiger_boletos';
$field0->column='pasaportecheck';
$field0->columntype = 'VARCHAR(2)';
$field0->uitype = 56; //check
$field0->typeofdata = 'V~O';
$field0->defaultvalue = '0';
$block->addField($field0);
*/
$field2=new Vtiger_Field();
$field2->label='Observacion';
$field2->name='observacion';
$field2->table='vtiger_boletos';
$field2->column='observacion';
$field2->columntype = 'VARCHAR(100)';
$field2->uitype = 19; //textarea
$field2->typeofdata = 'V~O';
$block->addField($field2);

$block->save($module);
$module->initWebservice();
echo 'Code successfully executed';
?>
