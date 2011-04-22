<?php


SetupWebPage::AddModule(
	__FILE__,
	'itop-tickets/1.0.0',
	array(
		// Identification
		//
		'label' => 'Tickets - prerequisite for ticket modules',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/1.0.0',
		),
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'model.itop-tickets.php',
		),
		'data.struct' => array(
			'data.struct.ta-actions.xml',
		),
		'data.sample' => array(
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(
		),
	)
);

?>
