<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
    'itop-admin-delegation-profiles/1.2.1',
    array(
        // Identification
        //
        'label' => 'Profiles per admin fonction',
        'category' => 'Datamodel',

        // Setup
        //
        'dependencies' => array(
	        'itop-config-mgmt/2.7.0' || 'itop-structure/3.0.0',
	        // itop-profiles-itil is here to ensure that the /itop_design/groups/group[@id="History"] alteration comes after those from that module.
	        // This allows to define the missing "History" group in iTop 2.7 / 3.0, while merging smoothly with iTop 3.1+
	        'itop-profiles-itil/2.7.0',
        ),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-admin-delegation-profiles.php'
		),
		'webservice' => array(
			
		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
		),
	)
);
