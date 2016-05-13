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

$field0 = new Vtiger_Field();
$field0->name = 'localizadorid'; //Usually matches column name
$field0->table = 'vtiger_boletos';
$field0->column = 'localizadorid'; //Must be lower case
$field0->label = 'ID Localizador'; //Upper case preceeded by LBL_
$field0->columntype = 'INT(11)'; //
$field0->uitype = 10; //Campo select Cuenta
$field0->typeofdata = 'N~M'; //V=Varchar?, M=Mandatory, O=Optional
$block->addField($field0);
$field0->setRelatedModules(Array('Localizadores'));

$field01=new Vtiger_Field();
$field01->label='Tipo de Vuelo';
$field01->name='tipodevuelo';
$field01->table='vtiger_boletos';
$field01->column='tipodevuelo';
$field01->columntype = 'VARCHAR(50)';
$field01->uitype = 16; //PICKLIST
$field01->typeofdata = 'V~M';
$block->addField($field01);

$field1=new Vtiger_Field();
$field1->label='Monto Base';
$field1->name='monto_base';
$field1->table='vtiger_boletos';
$field1->column='monto_base';
$field1->columntype = 'DOUBLE(8,2)';
$field1->uitype = 7; //moneda
$field1->typeofdata = 'N~M';
$block->addField($field1);

$field2=new Vtiger_Field();
$field2->label='Fecha Emision';
$field2->name='fecha_emision';
$field2->table='vtiger_boletos';
$field2->column='fecha_emision';
$field2->columntype = 'DATE';
$field2->uitype = 5; //calendar
$field2->typeofdata = 'D~M';
$block->addField($field2);

$field3=new Vtiger_Field();
$field3->label='Pasajero';
$field3->name='passenger';
$field3->table='vtiger_boletos';
$field3->column='passenger';
$field3->columntype = 'VARCHAR(100)';
$field3->uitype = 2; //text mandatory
$field3->typeofdata = 'V~M';
$block->addField($field3);

$field4=new Vtiger_Field();
$field4->label='Itinerario';
$field4->name='itinerario';
$field4->table='vtiger_boletos';
$field4->column='itinerario';
$field4->columntype = 'VARCHAR(50)';
$field4->uitype = 2; //text mandatory
$field4->typeofdata = 'V~M';
$block->addField($field4);

$field5=new Vtiger_Field();
$field5->label='Status';
$field5->name='status';
$field5->table='vtiger_boletos';
$field5->column='status';
$field5->columntype = 'VARCHAR(50)';
$field5->uitype = 2; //text mandatory
$field5->typeofdata = 'V~M';
$block->addField($field5);

$field6=new Vtiger_Field();
$field6->label='Comision Satelite';
$field6->name='comision_sat';
$field6->table='vtiger_boletos';
$field6->column='comision_sat';
$field6->columntype = 'DOUBLE(8,2)';
$field6->uitype = 7; //text mandatory
$field6->typeofdata = 'N~M';
$block->addField($field6);

$field7=new Vtiger_Field();
$field7->label='Fee Aerolinea';
$field7->name='fee_airline';
$field7->table='vtiger_boletos';
$field7->column='fee_airline';
$field7->columntype = 'DOUBLE(8,2)';
$field7->uitype = 7; //money
$field7->typeofdata = 'N~O'; //optinal
$block->addField($field7);

$block->save($module);
$module->initWebservice();
echo 'Code successfully executed';
?>
