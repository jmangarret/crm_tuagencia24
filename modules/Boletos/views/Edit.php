<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Boletos_Edit_View extends Vtiger_Edit_View {

	public function process(Vtiger_Request $request) {
	global $current_user, $adb;
	$roleid = $current_user->roleid;

	$moduleName = $request->getModule();

	$recordId = $request->get('record');
        $recordModel = $this->record;
        if(!$recordModel){
           if (!empty($recordId)) {
               $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
           } 
            $this->record = $recordModel;
        }

		$viewer = $this->getViewer($request);

		$viewer->assign('IMAGE_DETAILS', $recordModel->getImageDetails());

		parent::process($request);
	}

}
