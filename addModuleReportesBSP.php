<?php
include_once ('vtlib/Vtiger/Module.php');

 $Vtiger_Utils_Log = true;

 $MODULENAME = 'ReportesBSP';
 $TABLENAME = 'vtiger_reportesbsp';


 $moduleInstance = Vtiger_Module::getInstance($MODULENAME);
 if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
         die("Module already present - choose a different name.");
 }

 /////////////////////////INICIO MODULO///////////////////////////

        $moduleInstance = new Vtiger_Module(); 
        $moduleInstance->name = $MODULENAME;     
        $moduleInstance->parent= 'Satelites';
        $moduleInstance->save();              

        // Schema Setup
        $moduleInstance->initTables();       


        $menuInstance = Vtiger_Menu::getInstance('Satelites');  
        $menuInstance->addModule($moduleInstance);          

/////////////////////////FIN MODULO///////////////////////////


/////////////////////////INICIO BLOQUE///////////////////////////

        // Field SetupmoduleInstance
        $blockInstance = new Vtiger_Block();					
	    $blockInstance->label = 'LBL_REPORTEBSP_INFORMATION';		
        $moduleInstance->addBlock($blockInstance);				


        $blockInstance2 = new Vtiger_Block();					
        $blockInstance2->label = 'LBL_CUSTOM_INFORMATION';		
        $moduleInstance->addBlock($blockInstance2);				

/////////////////////////FIN BLOQUE///////////////////////////

/////////////////////////INICIO CAMPOS///////////////////////////

        $fieldInstance1  = new Vtiger_Field();                          
        $fieldInstance1->name = 'calendariobspid';                                       
        $fieldInstance1->label = 'Calendario';                                      
        $fieldInstance1->table = $TABLENAME;                      
        $fieldInstance1->uitype = 10;                                            
        $fieldInstance1->column = 'calendariobspid';                                     
        $fieldInstance1->columntype = 'INT';           
        $fieldInstance1->typeofdata = 'N~M';                            
        $blockInstance->addField($fieldInstance1);                      
        $fieldInstance1->setRelatedModules(Array('CalendarioBSP'));

        $fieldInstance2  = new Vtiger_Field();
        $fieldInstance2->name = 'accountid';
        $fieldInstance2->label = 'Cuenta/Satelite';
        $fieldInstance2->table = $TABLENAME;
        $fieldInstance2->uitype = 10;
        $fieldInstance2->column = 'accountid';
        $fieldInstance2->columntype = 'INT';
        $fieldInstance2->typeofdata = 'N~M';
        $blockInstance->addField($fieldInstance2);
        $fieldInstance2->setRelatedModules(Array('Accounts'));


        $fieldInstance3  = new Vtiger_Field();
        $fieldInstance3->name = 'total_abonos';
        $fieldInstance3->label = 'Total Abonos(Pagos)';
        $fieldInstance3->table = $TABLENAME;
        $fieldInstance3->uitype = 71;
        $fieldInstance3->column = 'total_abonos';
        $fieldInstance3->columntype = 'DOUBLE(8,2)';
        $fieldInstance3->typeofdata = 'N~O';
        $fieldInstance3->displaytype = '2';
        $blockInstance->addField($fieldInstance3);

        $fieldInstance4  = new Vtiger_Field();
        $fieldInstance4->name = 'total_cargos';
        $fieldInstance4->label = 'Total Cargos(Ventas+Fee-Comision)';
        $fieldInstance4->table = $TABLENAME;
        $fieldInstance4->uitype = 71;
        $fieldInstance4->column = 'total_cargos';
        $fieldInstance4->columntype = 'DOUBLE(8,2)';
        $fieldInstance4->typeofdata = 'N~O';
        $fieldInstance4->displaytype = '2';
        $blockInstance->addField($fieldInstance4);

        $fieldInstance5  = new Vtiger_Field();
        $fieldInstance5->name = 'saldo';
        $fieldInstance5->label = 'Saldo';
        $fieldInstance5->table = $TABLENAME;
        $fieldInstance5->uitype = 71;
        $fieldInstance5->column = 'saldo';
        $fieldInstance5->columntype = 'DOUBLE(8,2)';
        $fieldInstance5->typeofdata = 'N~O';
        $fieldInstance5->displaytype = '2';
        $blockInstance->addField($fieldInstance5);

        $fieldInstance6 = new Vtiger_Field();
        $fieldInstance6->name = 'pagado'; //Usually matches column name
        $fieldInstance6->table = $TABLENAME;
        $fieldInstance6->column = 'pagado'; //Must be lower case
        $fieldInstance6->label = 'Pagado'; //Upper case preceeded by LBL_
        $fieldInstance6->columntype = 'VARCHAR(3)'; //
        $fieldInstance6->displaytype = 2; 
        $fieldInstance6->uitype = 56; 
        $fieldInstance6->typeofdata = 'V~O'; //V=Varchar?, M=Mandatory, O=Optional
        $blockInstance->addField($fieldInstance6);

        $moduleInstance->setEntityIdentifier($fieldInstance1);  //Define cual es el campo por el cual se realizaran las busquedas

/////////////////////////FIN CAMPOS///////////////////////////


/////////////////////////INICIO CAMPOS OBLIGATORIOS ///////////////////////////

        // // Recommended common fields every Entity module should have (linked to core table)
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

/////////////////////////FIN CAMPOS OBLIGATORIOS ///////////////////////////



/////////////////////////INICIO FILTRO OBLIGATORIO ///////////////////////////

        // // Filter Setup
        $filter1 = new Vtiger_Filter();
        $filter1->name = 'All';
        $filter1->isdefault = true;
        $moduleInstance->addFilter($filter1);
        $filter1->  addField($fieldInstance1)->
                    addField($fieldInstance2, 1)->
                    addField($fieldInstance3, 2)->
                    addField($mfield1, 3)->
                    addField($mfield2, 4)->
                    addField($mfield3, 5); //campos que se agregaran al filtro

/////////////////////////FIN FILTRO OBLIGATORIO ///////////////////////////

        // // Sharing Access Setup
        $moduleInstance->setDefaultSharing();

        // // Webservice Setup
        $moduleInstance->initWebservice();

         mkdir('modules/'.$MODULENAME); //crear en la carpeta modulo del vtiger la carpeta correspondiente al modulo que se creo
         echo "OK\n";
// }


?>