<?php
include_once ('vtlib/Vtiger/Module.php');
$Vtiger_Utils_Log = true;

// Module Create
$MODULENAME = 'RegistroDeCambios';
$TABLENAME = 'vtiger_registrodecambios';
$moduleInstance = new Vtiger_Module(); 
$moduleInstance->name = $MODULENAME;     
$moduleInstance->parent= 'Support';
$moduleInstance->save();			  

// Schema Setup
$moduleInstance->initTables(); 		 
$menuInstance = Vtiger_Menu::getInstance('Support');  
$menuInstance->addModule($moduleInstance);			

/////////////////////////INICIO BLOQUE///////////////////////////
$blockInstance = new Vtiger_Block();					
$blockInstance->label = 'LBL_REGISTRO_INFORMATION';		
$moduleInstance->addBlock($blockInstance);				

$blockInstance2 = new Vtiger_Block();					
$blockInstance2->label = 'LBL_CUSTOM_INFORMATION';		
$moduleInstance->addBlock($blockInstance2);				

/////////////////////////INICIO CAMPOS///////////////////////////
$fieldInstance1  = new Vtiger_Field();                          
$fieldInstance1->name = 'sistema';                                       
$fieldInstance1->label = 'Sistema';                                      
$fieldInstance1->table = $TABLENAME;                      
$fieldInstance1->uitype = 16;                                            
$fieldInstance1->column = 'sistema';                                     
$fieldInstance1->columntype = 'VARCHAR(255)';           
$fieldInstance1->typeofdata = 'V~M';                            
$blockInstance->addField($fieldInstance1); 
$fieldInstance1->setPicklistValues( Array ('CRM', 'Tickets', 'Pagina Web') );

$fieldInstance2  = new Vtiger_Field();                          
$fieldInstance2->name = 'modulo';                                     
$fieldInstance2->label = 'Modulo';                            
$fieldInstance2->table = $TABLENAME;                      
$fieldInstance2->uitype = 16;                                            
$fieldInstance2->column = 'modulo';                           
$fieldInstance2->columntype = 'VARCHAR(255)';           
$fieldInstance2->typeofdata = 'V~M';                            
$blockInstance->addField($fieldInstance2);   
$fieldInstance2->setPicklistValues( Array ('General', 'Contactos', 'Satelites', 'Localizadores', 'Boletos', 'Ventas', 'Pagos', 'Tickets', 'Tarifas', 'Comisiones') );                   

$fieldInstance3  = new Vtiger_Field();
$fieldInstance3->name = 'finalidad';
$fieldInstance3->label = 'Finalidad';
$fieldInstance3->table = $TABLENAME;
$fieldInstance3->uitype = 19;
$fieldInstance3->column = 'finalidad';
$fieldInstance3->columntype = 'VARCHAR(255)';
$fieldInstance3->typeofdata = 'V~M';
$blockInstance->addField($fieldInstance3);

$fieldInstance4  = new Vtiger_Field();
$fieldInstance4->name = 'archivos';
$fieldInstance4->label = 'Archivos';
$fieldInstance4->table = $TABLENAME;
$fieldInstance4->uitype = 19;
$fieldInstance4->column = 'archivos';
$fieldInstance4->columntype = 'VARCHAR(255)';
$fieldInstance4->typeofdata = 'V~M';
$blockInstance->addField($fieldInstance4);

$fieldInstance5  = new Vtiger_Field();
$fieldInstance5->name = 'sentanciassql';
$fieldInstance5->label = 'Sentencias SQL';
$fieldInstance5->table = $TABLENAME;
$fieldInstance5->uitype = 19;
$fieldInstance5->column = 'sentanciassql';
$fieldInstance5->columntype = 'VARCHAR(255)';
$fieldInstance5->typeofdata = 'V~M';
$blockInstance->addField($fieldInstance5);

$fieldInstance6  = new Vtiger_Field();
$fieldInstance6->name = 'taskstatus';
$fieldInstance6->label = 'Status';
$fieldInstance6->table = $TABLENAME;
$fieldInstance6->uitype = 16;
$fieldInstance6->column = 'taskstatus';
$fieldInstance6->columntype = 'VARCHAR(255)';
$fieldInstance6->typeofdata = 'V~M';
$blockInstance->addField($fieldInstance6);

$moduleInstance->setEntityIdentifier($fieldInstance2);

/////////////////////////INICIO CAMPOS OBLIGATORIOS ///////////////////////////
$mfield1 = new Vtiger_Field();
$mfield1->name = 'assigned_user_id';
$mfield1->label = 'Assigned To';
$mfield1->table = 'vtiger_crmentity';
$mfield1->column = 'smownerid';
$mfield1->uitype = 53;
$mfield1->typeofdata = 'V~M';
$blockInstance2->addField($mfield1);

$mfield2 = new Vtiger_Field();
$mfield2->name = 'createdTime';
$mfield2->label= 'Created Time';
$mfield2->table = 'vtiger_crmentity';
$mfield2->column = 'createdtime';
$mfield2->uitype = 70;
$mfield2->typeofdata = 'T~O';
$mfield2->displaytype= 2;
$blockInstance2->addField($mfield2);

$mfield3 = new Vtiger_Field();
$mfield3->name = 'modifiedTime';
$mfield3->label= 'Modified Time';
$mfield3->table = 'vtiger_crmentity';
$mfield3->column = 'modifiedtime';
$mfield3->uitype = 70;
$mfield3->typeofdata = 'T~O';
$mfield3->displaytype= 2;
$blockInstance2->addField($mfield3);

/////////////////////////INICIO FILTRO OBLIGATORIO ///////////////////////////
$filter1 = new Vtiger_Filter();
$filter1->name = 'All';
$filter1->isdefault = true;
$moduleInstance->addFilter($filter1);

//campos que se agregaran al filtro
$filter1->addField($fieldInstance1)
        ->addField($fieldInstance2, 1)
        ->addField($fieldInstance3, 2)
        ->addField($fieldInstance6, 3)
        ->addField($mfield1, 4)
        ->addField($mfield2, 5)
        ->addField($mfield3, 6); 

//// Sharing Access Setup
$moduleInstance->setDefaultSharing();

//// Webservice Setup
$moduleInstance->initWebservice();

echo "MODULE OK!\n";

?>