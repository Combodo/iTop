<?php

/**
 *
 * Configuration file, generated for the unit tests
 *
 *
 *
 */
$MySettings = [
];

/**
 *
 * Modules specific settings
 *
 */
$MyModuleSettings = array(
	'combodo-hybridauth' => array (
		//debug to add traces...
		'debug' => false,
		'providers' => array (
			'Keycloak' =>
			/*
			ga
			bu
			zo meu
			*/
				array (
					'keys' =>
						array (
							/**
							 * sha
							 *
							 * dok
							 */
							'id' => 'my-clientid',
							'secret' => 'my-secret',
						),
					'enabled' => false,
				),
			//url to access IdP
			'url' => 'keycloak_url',
		),
	),
);

/**
 *
 * Data model modules to be loaded. Names are specified as relative paths
 *
 */
$MyModules = [
];
