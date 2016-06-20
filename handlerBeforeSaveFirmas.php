<?php
require_once 'include/utils/utils.php';
require 'include/events/include.inc';
$em = new VTEventsManager($adb);
$em->registerHandler("vtiger.entity.beforesave", "modules/Terminales/TerminalesHandler.php", "TerminalesHandler", "ModuleName in ['Terminales']");
echo 'Custom Handler Registered !';

?>
