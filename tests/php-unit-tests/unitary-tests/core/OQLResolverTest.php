<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DBObjectSet;
use DBSearch;
use MetaModel;

class OQLResolverTest extends ItopCustomDatamodelTestCase
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/Delta/delta_oql_resolver.xml';
	}

	public function testQueryOnMagicalFields()
	{
		// Given
		$sObjectKey = $this->GivenObjectInDB('OQLResolverChild', ['name' => 'toto', 'status' => 'new']);
		$oObject = MetaModel::GetObject('OQLResolverChild', $sObjectKey);

		// When actions ApplyStimulus then next action fails
		$sFilter = "SELECT OQLResolverChild WHERE ISNULL(cumulatedpending_laststart)";
		$oSearch = DBSearch::FromOQL($sFilter);

		$oSet = new DBObjectSet($oSearch);
		$oSet->OptimizeColumnLoad(['OQLResolverChild' => ['cumulatedpending']]);

		$oActual = $oSet->Fetch();

		// Then
		// Check status...
		$this->assertEquals($oObject->Get('name'), $oActual->Get('name'), 'The query should have returned the object');
	}
}
