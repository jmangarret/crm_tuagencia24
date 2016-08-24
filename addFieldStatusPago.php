<?php
$Vtiger_Utils_Log = true;

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED);

require_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Menu.php');
require_once('vtlib/Vtiger/Block.php');
require_once('vtlib/Vtiger/Field.php');

$Vtiger_Utils_Log = true;


$regpagos = Vtiger_Module::getInstance('RegistroDePagos'); //Se instancia el modulo en el cual se incluira el nuevo campo


$block = Vtiger_Block::getInstance('LBL_BLOCK_GENERAL_INFORMATION', $regpagos); //Se instancia el bloque en el cual se incluira el nuevo campo 


$fieldInstance = new Vtiger_Field();
$fieldInstance->name = 'pagostatus'; 
$fieldInstance->table = 'vtiger_registrodepagos';
$fieldInstance->column = 'pagostatus'; 
$fieldInstance->label = 'Status de Pago'; 
$fieldInstance->columntype = 'VARCHAR(255)'; 
$fieldInstance->uitype = 15; //picklist no basado en roles
$fieldInstance->typeofdata = 'V~O'; 
$block->addField($fieldInstance);
$fieldInstance->setPicklistValues(Array('Por Verificar','Verificado','Diferido','Rechazado')); //Opciones del picklist

$block->save($regpagos);
$regpagos->initWebservice();
?>
