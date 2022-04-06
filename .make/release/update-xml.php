<?php

/*******************************************************************************
 * Tool to automate datamodel version update in XML
 *
 * Will update version in the following files :
 *
 * datamodels/2.x/.../datamodel.*.xml
 *
 * Usage :
 * `php .make\release\update-xml.php "1.7"`
 *
 * @since 2.7.0
 ******************************************************************************/



require_once (__DIR__.'/../../approot.inc.php');
require_once (__DIR__.DIRECTORY_SEPARATOR.'update.classes.inc.php');



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

$oFileVersionUpdater = new DatamodelsXmlFiles();
$oFileVersionUpdater->UpdateAllFiles($sVersionLabel);
