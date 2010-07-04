<?php


SetupWebPage::AddModule(
	__FILE__,
	'itop-tickets/1.0.0',
	array(
		// Identification
		//
		'label' => 'Tickets - prerequisite for ticket modules',

		// Setup
		//
		'dependencies' => array(
		),
		'mandatory' => true,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'model.itop-tickets.php',
		),
		'dictionary' => array(
			'en.dict.itop-tickets.php',
		),
		'data.struct' => array(
		),
		'data.sample' => array(
		),
		
		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',
	)
);

?>
