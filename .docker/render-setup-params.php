<?php

/**
 * Prints the response file of the iTop unattended setup, for the Docker development environment.
 *
 * It starts from the ITIL templates shipped in setup/unattended-install/xml_setup/ and fills them with the
 * environment variables of the web container (see .env.dist). Meant to be run inside the web container:
 *
 *   php .docker/render-setup-params.php install|upgrade
 *
 * In "upgrade" mode, the database settings are read from the iTop configuration file (--use_itop_config),
 * so no credential is written in the response file.
 */

$sMode = $argv[1] ?? '';
if (!in_array($sMode, ['install', 'upgrade'], true)) {
	fwrite(STDERR, "Usage: php ".basename(__FILE__)." install|upgrade\n");
	exit(1);
}

$sTemplate = dirname(__DIR__).'/setup/unattended-install/xml_setup/'.($sMode === 'install' ? 'itil-fresh-install.xml' : 'itil-upgrade.xml');
$oDoc = new DOMDocument();
$oDoc->preserveWhiteSpace = true;
if (!$oDoc->load($sTemplate)) {
	fwrite(STDERR, "Cannot read $sTemplate\n");
	exit(1);
}
$oXPath = new DOMXPath($oDoc);

/**
 * @param string $sXPath Path of the node(s) to set, relative to the root node
 * @param string $sValue Raw value, escaped by DOM
 */
$SetValue = static function (string $sXPath, string $sValue) use ($oXPath): void {
	foreach ($oXPath->query('/installation/'.$sXPath) as $oNode) {
		$oNode->textContent = $sValue;
	}
};

/**
 * @param string $sName Environment variable
 * @param string|null $sDefault Value used when the variable is not set, null to make it mandatory
 *
 * @return string
 */
$GetEnv = static function (string $sName, ?string $sDefault = null): string {
	$sValue = getenv($sName);
	if ($sValue === false || $sValue === '') {
		if ($sDefault === null) {
			fwrite(STDERR, "Missing environment variable $sName (see .docker/.env.dist)\n");
			exit(1);
		}

		return $sDefault;
	}

	return $sValue;
};

// Symbolic links: changes in datamodels/ and extensions/ PHP files are live without running the setup again
$SetValue('use_symbolic_links', 'on');

if ($sMode === 'install') {
	$SetValue('database/server', 'db');
	$SetValue('database/user', $GetEnv('MARIADB_USER', 'itop'));
	$SetValue('database/pwd', $GetEnv('MARIADB_PASSWORD'));
	$SetValue('database/name', $GetEnv('MARIADB_DATABASE', 'itop'));
	$SetValue('database/db_tls_enabled', '0');
	$SetValue('url', 'http://localhost:'.$GetEnv('HTTP_PORT', '8080').'/');
	$SetValue('admin_account/user', $GetEnv('ITOP_ADMIN_LOGIN', 'admin'));
	$SetValue('admin_account/pwd', $GetEnv('ITOP_ADMIN_PASSWORD'));
	$SetValue('admin_account/language', $GetEnv('ITOP_LANGUAGE', 'EN US'));
	$SetValue('language', $GetEnv('ITOP_LANGUAGE', 'EN US'));
}

// Both modes: on upgrade, it controls whether the sample data of newly added modules are loaded
$SetValue('sample_data', $GetEnv('ITOP_SAMPLE_DATA', '1') === '1' ? '1' : '0');

echo $oDoc->saveXML();
