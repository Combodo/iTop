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
 * `php .make\release\update-xml.php`
 *
 * If no parameter provided then the current XML version will be used as target version
 *
 * @since 2.7.0 simple version change using regexp (not doing conversion)
 * @since 3.1.0 N°5405 now does a real conversion
 * @since 3.1.0 N°5633 allow to use without parameter
 ******************************************************************************/



require_once (__DIR__.'/../../approot.inc.php');
require_once (__DIR__.DIRECTORY_SEPARATOR.'update.classes.inc.php');



if (count($argv) === 1)
{
	echo '/!\ No version passed: assuming target XML version is current XML version ('.ITOP_DESIGN_LATEST_VERSION.")\n";
	$sVersionLabel = ITOP_DESIGN_LATEST_VERSION;
} else {
	$sVersionLabel = $argv[1];
}

if (empty($sVersionLabel))
{
	echo 'Version passed as parameter is empty !';
	exit(2);
}

$oFileVersionUpdater = new DatamodelsXmlFiles();
$oFileVersionUpdater->UpdateAllFiles($sVersionLabel);
