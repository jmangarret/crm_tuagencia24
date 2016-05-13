<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class Timecontrol extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	// Variable to esablish start value on resume
	// true: dates and start time will be set to "now"
	// false: only start time will be set to "now"
	var $now_on_resume=true;
	var $USE_RTE = 'true';

	var $table_name = 'vtiger_timecontrol';
	var $table_index= 'timecontrolid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_timecontrolcf', 'timecontrolid');
	var $related_tables = Array('vtiger_timecontrolcf'=>array('timecontrolid','vtiger_timecontrol', 'timecontrolid'));

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_timecontrol', 'vtiger_timecontrolcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_timecontrol'   => 'timecontrolid',
	    'vtiger_timecontrolcf' => 'timecontrolid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Timecontrol Number' => array('timecontrol', 'timecontrolnr'),
    'Title'=> Array('timecontrol', 'title'),
    'Date Start' => array('timecontrol', 'date_start'),
    'Time Start' => array('timecontrol', 'time_start'),
    'Total Time' => array('timecontrol', 'totaltime'),
    'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Timecontrol Number' => 'timecontrolnr',
    'Title'=> 'title',
    'Date Start' => 'date_start',
    'Time Start' => 'time_start',
    'Total Time' => 'totaltime',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'timecontrolnr';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Timecontrol Number' => array('timecontrol', 'timecontrolnr'),
		'Title'=> Array('timecontrol', 'title')
	);
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Timecontrol Number' => 'timecontrolnr',
		'Title'=> 'title'
	);

	// For Popup window record selection
	var $popup_fields = Array('timecontrolnr');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'timecontrolnr';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'title';

	// Required Information for enabling Import feature
	var $required_fields = Array();

	// Callback function list during Importing
	var $special_functions = Array('set_import_assigned_user');

	var $default_order_by = 'date_start';
	var $default_sort_order='DESC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'timecontrolnr', 'date_start', 'time_start');
	
	function __construct() {
		global $log, $currentModule;
		$this->column_fields = getColumnFields($currentModule);
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	function getSortOrder() {
		global $currentModule;

		$sortorder = $this->default_sort_order;
		if($_REQUEST['sorder']) $sortorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else if($_SESSION[$currentModule.'_Sort_Order']) 
			$sortorder = $_SESSION[$currentModule.'_Sort_Order'];

		return $sortorder;
	}

	function getOrderBy() {
		global $currentModule;
		
		$use_default_order_by = '';		
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		
		$orderby = $use_default_order_by;
		if($_REQUEST['order_by']) $orderby = $this->db->sql_escape_string($_REQUEST['order_by']);
		else if($_SESSION[$currentModule.'_Order_By'])
			$orderby = $_SESSION[$currentModule.'_Order_By'];
		return $orderby;
	}

	function save_module($module) {
	  $this->updateTimesheetTotalTime();
	}
	
	/**     Update totaltime field   */
	function updateTimesheetTotalTime() {
	  global $adb;
	  if ($this->column_fields['date_end']!='' && $this->column_fields['time_end']!='') {
	    $query = "select date_start, time_start, date_end, time_end from vtiger_timecontrol where timecontrolid={$this->id}";
	    $res = $adb->query($query);
	    $date = $adb->query_result($res, 0, 'date_start');
	    $time = $adb->query_result($res, 0, 'time_start');
	    list($year, $month, $day) = split('-', $date);
	    list($hour, $minute) = split(':', $time);
	    $starttime = mktime($hour, $minute, 0, $month, $day, $year);
	    $date = $adb->query_result($res, 0, 'date_end');
	    $time = $adb->query_result($res, 0, 'time_end');
	    list($year, $month, $day) = split('-', $date);
	    list($hour, $minute) = split(':', $time);
	    $endtime = mktime($hour, $minute, 0, $month, $day, $year);
	    $counter = round(($endtime-$starttime)/60);
	    $totaltime = str_pad(floor($counter/60), 2, '0', STR_PAD_LEFT).':'.str_pad($counter%60, 2, '0', STR_PAD_LEFT);
	    $query = "update vtiger_timecontrol set totaltime='{$totaltime}' where timecontrolid={$this->id}";
	    $adb->query($query);
	  }
	}

	function trash($module,$record) {
		global $adb;
		parent::trash($module,$record);
		if (vtlib_isModuleActive('TCTotals')) {
			include_once 'modules/TCTotals/TCTotalsHandler.php';
			$tcdata=$adb->query("select smownerid,date_start from vtiger_timecontrol inner join vtiger_crmentity on crmid=timecontrolid where timecontrolid=$record");
			$workdate=$adb->query_result($tcdata,0,'date_start');
			$tcuser=$adb->query_result($tcdata,0,'smownerid');
			TCTotalsHandler::updateTotalTimeForUserOnDate($tcuser, $workdate);
		}
	}

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord, $query='') {
		// $srcrecord could be empty
	}

	/**
	 * Get list view query (send more WHERE clause condition if required)
	 */
	function getListQuery($module, $usewhere='') {
		$query = "SELECT vtiger_crmentity.*, $this->table_name.*";
		
		// Keep track of tables joined to avoid duplicates
		$joinedTables = array();

		// Select Custom Field Table Columns if present
		if(!empty($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$query .= " FROM $this->table_name";

		$query .= "	INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index";

		$joinedTables[] = $this->table_name;
		$joinedTables[] = 'vtiger_crmentity';
		
		// Consider custom table join as well.
		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index";
			$joinedTables[] = $this->customFieldTable[0]; 
		}
		$query .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid";
		$query .= " LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";

		$joinedTables[] = 'vtiger_users';
		$joinedTables[] = 'vtiger_groups';
		
		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM vtiger_field" .
				" INNER JOIN vtiger_fieldmodulerel ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid" .
				" WHERE uitype='10' AND vtiger_fieldmodulerel.module=?", array($module));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);
		
		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');
			
			$other =  CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);
			
			if(!in_array($other->table_name, $joinedTables)) {
				$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
				$joinedTables[] = $other->table_name;
			}
		}

		global $current_user;
		$query .= $this->getNonAdminAccessControlQuery($module,$current_user);
		$query .= "	WHERE vtiger_crmentity.deleted = 0 ".$usewhere;
		return $query;
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (vtiger_crmentity.smownerid in($current_user->id) OR vtiger_crmentity.smownerid IN 
					(
						SELECT vtiger_user2role.userid FROM vtiger_user2role 
						INNER JOIN vtiger_users ON vtiger_users.id=vtiger_user2role.userid 
						INNER JOIN vtiger_role ON vtiger_role.roleid=vtiger_user2role.roleid 
						WHERE vtiger_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					) 
					OR vtiger_crmentity.smownerid IN 
					(
						SELECT shareduserid FROM vtiger_tmp_read_user_sharing_per 
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					) 
					OR 
						(";
		
					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " vtiger_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " vtiger_groups.groupid IN 
						(
							SELECT vtiger_tmp_read_group_sharing_per.sharedgroupid 
							FROM vtiger_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

	/**
	 * Create query to export the records.
	 */
	function create_export_query($where)
	{
		global $current_user;
		$thismodule = $_REQUEST['module'];
		
		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery($thismodule, "detail_view");
		
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, vtiger_users.user_name AS user_name 
					FROM vtiger_crmentity INNER JOIN $this->table_name ON vtiger_crmentity.crmid=$this->table_name.$this->table_index";

		if(!empty($this->customFieldTable)) {
			$query .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index"; 
		}

		$query .= " LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		$query .= " LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id and vtiger_users.status='Active'";
		
		$linkedModulesQuery = $this->db->pquery("SELECT distinct fieldname, columnname, relmodule FROM vtiger_field" .
				" INNER JOIN vtiger_fieldmodulerel ON vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid" .
				" WHERE uitype='10' AND vtiger_fieldmodulerel.module=?", array($thismodule));
		$linkedFieldsCount = $this->db->num_rows($linkedModulesQuery);

		for($i=0; $i<$linkedFieldsCount; $i++) {
			$related_module = $this->db->query_result($linkedModulesQuery, $i, 'relmodule');
			$fieldname = $this->db->query_result($linkedModulesQuery, $i, 'fieldname');
			$columnname = $this->db->query_result($linkedModulesQuery, $i, 'columnname');
			
			$other = CRMEntity::getInstance($related_module);
			vtlib_setup_modulevars($related_module, $other);
			
			$query .= " LEFT JOIN $other->table_name ON $other->table_name.$other->table_index = $this->table_name.$columnname";
		}

		$query .= $this->getNonAdminAccessControlQuery($thismodule,$current_user);
		$where_auto = " vtiger_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		return $query;
	}

	/**
	 * Initialize this instance for importing.
	 */
	function initImport($module) {
		$this->db = PearDatabase::getInstance();
		$this->initImportableFields($module);
	}

	/**
	 * Create list query to be shown at the last step of the import.
	 * Called From: modules/Import/UserLastImport.php
	 */
	function create_import_query($module) {
		global $current_user;
		$query = "SELECT vtiger_crmentity.crmid, case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name, $this->table_name.* FROM $this->table_name
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index
			LEFT JOIN vtiger_users_last_import ON vtiger_users_last_import.bean_id=vtiger_crmentity.crmid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			WHERE vtiger_users_last_import.assigned_user_id='$current_user->id'
			AND vtiger_users_last_import.bean_type='$module'
			AND vtiger_users_last_import.deleted=0";
		return $query;
	}

	/**
	 * Delete the last imported records.
	 */
	function undo_import($module, $user_id) {
		global $adb;
		$count = 0;
		$query1 = "select bean_id from vtiger_users_last_import where assigned_user_id=? AND bean_type='$module' AND deleted=0";
		$result1 = $adb->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error()); 
		while ( $row1 = $adb->fetchByAssoc($result1))
		{
			$query2 = "update vtiger_crmentity set deleted=1 where crmid=?";
			$result2 = $adb->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error()); 
			$count++;			
		}
		return $count;
	}
	
	/**
	 * Transform the value while exporting
	 */
	function transform_export_value($key, $value) {
		return parent::transform_export_value($key, $value);
	}

	/**
	 * Function which will set the assigned user id for import record.
	 */
	function set_import_assigned_user()
	{
		global $current_user, $adb;
		$record_user = $this->column_fields["assigned_user_id"];
		
		if($record_user != $current_user->id){
			$sqlresult = $adb->pquery("select id from vtiger_users where id = ? union select groupid as id from vtiger_groups where groupid = ?", array($record_user, $record_user));
			if($this->db->num_rows($sqlresult)!= 1) {
				$this->column_fields["assigned_user_id"] = $current_user->id;
			} else {			
				$row = $adb->fetchByAssoc($sqlresult, -1, false);
				if (isset($row['id']) && $row['id'] != -1) {
					$this->column_fields["assigned_user_id"] = $row['id'];
				} else {
					$this->column_fields["assigned_user_id"] = $current_user->id;
				}
			}
		}
	}
	
	/** 
	 * Function which will give the basic query to find duplicates
	 */
	function getDuplicatesQuery($module,$table_cols,$field_values,$ui_type_arr,$select_cols='') {
		$select_clause = "SELECT ". $this->table_name .".".$this->table_index ." AS recordid, vtiger_users_last_import.deleted,".$table_cols;

		// Select Custom Field Table Columns if present
		if(isset($this->customFieldTable)) $query .= ", " . $this->customFieldTable[0] . ".* ";

		$from_clause = " FROM $this->table_name";

		$from_clause .= "	INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = $this->table_name.$this->table_index";

		// Consider custom table join as well.
		if(isset($this->customFieldTable)) {
			$from_clause .= " INNER JOIN ".$this->customFieldTable[0]." ON ".$this->customFieldTable[0].'.'.$this->customFieldTable[1] .
				      " = $this->table_name.$this->table_index"; 
		}
		$from_clause .= " LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid";
		
		$where_clause = "	WHERE vtiger_crmentity.deleted = 0";
		$where_clause .= $this->getListViewSecurityParameter($module);
					
		if (isset($select_cols) && trim($select_cols) != '') {
			$sub_query = "SELECT $select_cols FROM  $this->table_name AS t " .
				" INNER JOIN vtiger_crmentity AS crm ON crm.crmid = t.".$this->table_index;
			// Consider custom table join as well.
			if(isset($this->customFieldTable)) {
				$sub_query .= " LEFT JOIN ".$this->customFieldTable[0]." tcf ON tcf.".$this->customFieldTable[1]." = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $select_cols HAVING COUNT(*)>1";	
		} else {
			$sub_query = "SELECT $table_cols $from_clause $where_clause GROUP BY $table_cols HAVING COUNT(*)>1";
		}	
		
		
		$query = $select_clause . $from_clause .
					" LEFT JOIN vtiger_users_last_import ON vtiger_users_last_import.bean_id=" . $this->table_name .".".$this->table_index .
					" INNER JOIN (" . $sub_query . ") AS temp ON ".get_on_clause($field_values,$ui_type_arr,$module) .
					$where_clause .
					" ORDER BY $table_cols,". $this->table_name .".".$this->table_index ." ASC";
					
		return $query;		
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			// TODO Handle post installation actions
			self::addTSRelations(true);
		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
			self::addTSRelations(false);
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

	static function addTSRelations($dorel=true) {
		$Vtiger_Utils_Log = true;
		include_once('vtlib/Vtiger/Module.php');

		$module = Vtiger_Module::getInstance('Timecontrol');

		$pdoModule = Vtiger_Module::getInstance('Products');
		if ($dorel) $pdoModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$pdoModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&product_id=$RECORD$');

		$srvModule = Vtiger_Module::getInstance('Services');
		if ($dorel) $srvModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$srvModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&product_id=$RECORD$');

		$helpdeskModule = Vtiger_Module::getInstance('HelpDesk');
		if ($dorel) $helpdeskModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$helpdeskModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$quotesModule = Vtiger_Module::getInstance('Quotes');
		if ($dorel) $quotesModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$quotesModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$salesorderModule = Vtiger_Module::getInstance('SalesOrder');
		if ($dorel) $salesorderModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$salesorderModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$purchaseorderModule = Vtiger_Module::getInstance('PurchaseOrder');
		if ($dorel) $purchaseorderModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$purchaseorderModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$invoiceModule = Vtiger_Module::getInstance('Invoice');
		if ($dorel) $invoiceModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$invoiceModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$potentialsModule = Vtiger_Module::getInstance('Potentials');
		if ($dorel) $potentialsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$potentialsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$campaignsModule = Vtiger_Module::getInstance('Campaigns');
		if ($dorel) $campaignsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$campaignsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$vendorsModule = Vtiger_Module::getInstance('Vendors');
		if ($dorel) $vendorsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$vendorsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$contactsModule = Vtiger_Module::getInstance('Contacts');
		if ($dorel) $contactsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$contactsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$accountsModule = Vtiger_Module::getInstance('Accounts');
		if ($dorel) $accountsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$accountsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$leadsModule = Vtiger_Module::getInstance('Leads');
		if ($dorel) $leadsModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$leadsModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$prjModule = Vtiger_Module::getInstance('Project');
		if ($dorel) $prjModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$prjModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$prjmModule = Vtiger_Module::getInstance('ProjectMilestone');
		if ($dorel) $prjmModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$prjmModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$prjtModule = Vtiger_Module::getInstance('ProjectTask');
		if ($dorel) $prjtModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$prjtModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$astModule = Vtiger_Module::getInstance('Assets');
		if ($dorel) $astModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$astModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');

		$sctoModule = Vtiger_Module::getInstance('ServiceContracts');
		if ($dorel) $sctoModule->setRelatedList($module, 'Timecontrol', Array('ADD'), 'get_dependents_list');
		$sctoModule->addLink('DETAILVIEWBASIC', 'Timecontrol', 'index.php?module=Timecontrol&action=EditView&relatedto=$RECORD$');
	}

	/** 
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	// function save_related_module($module, $crmid, $with_module, $with_crmid) { }
	
	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }
}
?>
