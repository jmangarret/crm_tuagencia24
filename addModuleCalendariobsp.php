<?php
include_once ('vtlib/Vtiger/Module.php');

 $Vtiger_Utils_Log = true;

 $MODULENAME = 'CalendarioBSP';
 $TABLENAME = 'vtiger_calendariobsp';

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

        // Field Setup
        $blockInstance = new Vtiger_Block();					
	    $blockInstance->label = 'LBL_CALENDARIO_INFORMATION';		
        $moduleInstance->addBlock($blockInstance);				


        $blockInstance2 = new Vtiger_Block();					
        $blockInstance2->label = 'LBL_CUSTOM_INFORMATION';		
        $moduleInstance->addBlock($blockInstance2);				

/////////////////////////FIN BLOQUE///////////////////////////

/////////////////////////INICIO CAMPOS///////////////////////////

        $fieldInstance1  = new Vtiger_Field();                          
        $fieldInstance1->name = 'nombre';                                       
        $fieldInstance1->label = 'Nombre';                                      
        $fieldInstance1->table = $TABLENAME;                      
        $fieldInstance1->uitype = 1;                                            
        $fieldInstance1->column = 'nombre';                                     
        $fieldInstance1->columntype = 'VARCHAR(255)';           
        $fieldInstance1->typeofdata = 'V~O';                            
        $blockInstance->addField($fieldInstance1);                      


        $fieldInstance2  = new Vtiger_Field();
        $fieldInstance2->name = 'fecha_desde';
        $fieldInstance2->label = 'Fecha Desde';
        $fieldInstance2->table = $TABLENAME;
        $fieldInstance2->uitype = 5;
        $fieldInstance2->column = 'fecha_desde';
        $fieldInstance2->columntype = 'DATE';
        $fieldInstance2->typeofdata = 'D~M';
        $blockInstance->addField($fieldInstance2);


        $fieldInstance3  = new Vtiger_Field();
        $fieldInstance3->name = 'fecha_hasta';
        $fieldInstance3->label = 'Fecha Hasta';
        $fieldInstance3->table = $TABLENAME;
        $fieldInstance3->uitype = 5;
        $fieldInstance3->column = 'fecha_hasta';
        $fieldInstance3->columntype = 'DATE';
        $fieldInstance3->typeofdata = 'D~M';
        $blockInstance->addField($fieldInstance3);


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