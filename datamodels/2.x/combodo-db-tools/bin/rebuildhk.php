<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Rebuild the hierarchical keys control data
 */

use Combodo\iTop\Core\MetaModel\HierarchicalKey;

require_once ('../../../approot.inc.php');
require_once APPROOT.'application/startup.inc.php';

foreach(MetaModel::GetClasses() as $sClass)
{
	if (!MetaModel::HasTable($sClass))
	{
		continue;
	}

	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		// Check (once) all the attributes that are hierarchical keys
		if ((MetaModel::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef->IsHierarchicalKey())
		{
			echo "Rebuild hierarchical key $sAttCode from $sClass.\n";
			HierarchicalKey::Rebuild($sClass, $sAttCode, $oAttDef);
		}
	}
}

echo "Done\n";
