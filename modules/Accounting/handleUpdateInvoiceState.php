<?php
/*+**********************************************************************************
   * The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
   ************************************************************************************/
require_once("modules/Accounting/AccountingUtils.php");


function handleUpdateInvoiceStatus($entity){
	updateInvoiceStatus($entity);
}

function handleUpdateInvoiceStatus_Paid($entity){
	updateInvoiceStatus($entity, true);
}

function handleUpdateInvoiceStatus_Pending($entity){
	updateInvoiceStatus($entity, false);
}

?>
