<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Fixtures;

use DBObjectSearch;
use DBUnionSearch;
use UserRightsProfile;

class N9687_CustomGetSelectFilterClass extends UserRightsProfile
{
	public function GetSelectFilter($oUser, $sClass, $aSettings = [])
	{
		// We just need the method to return an union search
		return new DBUnionSearch([
			DBObjectSearch::FromOQL("SELECT $sClass WHERE 1!=2"),
			DBObjectSearch::FromOQL("SELECT $sClass WHERE 1=1"),
		]);
	}
}
