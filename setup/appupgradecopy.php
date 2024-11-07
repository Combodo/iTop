<?php

use Combodo\iTop\CoreUpdate\Service\CoreUpdater;

/**
 * iTop
 *
 * @copyright   Copyright (C) 2010,2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */

function AppUpgradeCopyFiles($sSourceDir)
{
	CoreUpdater::CopyDir($sSourceDir, APPROOT);
	// Update Core update files
	$sSource = realpath($sSourceDir.'/datamodels/2.x/itop-core-update');
	if ($sSource !== false)
	{
		CoreUpdater::CopyDir($sSource, APPROOT.'env-production/itop-core-update');
	}
}