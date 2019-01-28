<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

global $aOldFiles;

$aOldFiles = array();

/**
 * @param $sFromDir
 *
 * @throws \Exception
 */
function UpdateCore($sFromDir)
{
	global $aOldFiles;


	CoreUpdater::CopyDir($sFromDir, APPROOT);


	// Remove unused files
	foreach ($aOldFiles as $sFile)
	{
		if (is_file($sFile))
		{
			@unlink($sFile);
		}
		elseif (is_dir($sFile))
		{
			CoreUpdater::RRmdir($sFile);
		}
	}
}
