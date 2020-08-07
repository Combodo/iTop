<?php

/*******************************************************************************
 * Tool to automate version update before release
 *
 * Will update version in the following files :
 *
 * * datamodels/2.x/.../module.*.php
 * * datamodels/2.x/version.xml
 * * css/css-variables.scss $version
 *
 * Usage :
 * `php .make\release\update-versions.php "2.7.0-rc"`
 *
 * @since 2.7.0
 ******************************************************************************/



require_once (__DIR__.'/../../approot.inc.php');
require_once (__DIR__.DIRECTORY_SEPARATOR.'update.classes.inc.php');



/** @var \FileVersionUpdater[] $aFilesUpdaters */
$aFilesUpdaters = array(
	new iTopVersionFileUpdater(),
	new CssVariablesFileUpdater(),
	new DatamodelsModulesFiles(),
);

if (count($argv) === 1)
{
	echo '/!\ You must pass the new version as parameter';
	exit(1);
}
$sVersionLabel = $argv[1];
if (empty($sVersionLabel))
{
	echo 'Version passed as parameter is empty !';
	exit(2);
}

foreach ($aFilesUpdaters as $oFileVersionUpdater)
{
	$oFileVersionUpdater->UpdateAllFiles($sVersionLabel);
}
