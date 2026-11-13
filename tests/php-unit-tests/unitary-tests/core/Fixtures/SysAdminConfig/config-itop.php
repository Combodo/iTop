<?php

/**
 * Fake iTop administrator configuration, used by iTopConfigParserTest::testSysAdminConfigComputation()
 */

$MySettings = array(
	'db_host' => 'itop-admin-db-host',
	'db_pwd' => 'itop-admin-db-password',
	'app_root_url' => 'http://itop-admin.example.org/',
	'encryption_key' => 'itop-admin-encryption-key',
	'log_level_min' => array(
		'' => 'Error',
		'DeadLock' => 'Debug',
	),
);

$MyModuleSettings = array(
	'authent-ldap' => array(
		'host' => 'itop-admin-ldap-host',
		'default_pwd' => 'itop-admin-ldap-password',
	),
	'test-module' => array(
		'options' => array(
			'keep' => 'itop-admin-value',
			'override' => 'itop-admin-value',
		),
	),
);

$MyModules = array(
	'addons' => array(
		'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
	),
);
