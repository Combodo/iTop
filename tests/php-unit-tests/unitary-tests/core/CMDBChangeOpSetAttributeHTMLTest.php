<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBChangeOpSetAttributeHTML;
use CMDBObject;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;

class CMDBChangeOpSetAttributeHTMLTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/**
	 * The previous value is displayed inside an <li> of the activity panel, an <li> in it must not be able to close that <li>
	 *
	 * @covers CMDBChangeOpSetAttributeHTML::GetDescription
	 */
	public function testGetDescription_PreviousValueIsWrapped()
	{
		CMDBObject::SetTrackInfo('Unit test');
		$oTicket = $this->CreateUserRequest(1, ['description' => '<div><li>x</li></div>']);
		$oTicket->Set('description', '<p>y</p>');
		$oTicket->DBUpdate();

		$oSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOpSetAttributeHTML WHERE objclass = :class AND objkey = :key AND attcode = "description"');
		$oSet = new DBObjectSet($oSearch, [], ['class' => get_class($oTicket), 'key' => $oTicket->GetKey()]);
		$this->assertEquals(1, $oSet->Count());
		$oChangeOp = $oSet->Fetch();

		$this->assertStringContainsString('<blockquote><div><li>x</li></div></blockquote>', $oChangeOp->GetDescription());
	}

	/**
	 * A previous value that was stored without being sanitized (direct DB write, HTMLNullSanitizer, ...) must not break the page or run scripts
	 *
	 * @covers CMDBChangeOpSetAttributeHTML::GetDescription
	 */
	public function testGetDescription_PreviousValueIsSanitized()
	{
		$oTicket = $this->CreateUserRequest(1);
		/** @var CMDBChangeOpSetAttributeHTML $oChangeOp */
		$oChangeOp = MetaModel::NewObject(CMDBChangeOpSetAttributeHTML::class, [
			'objclass' => get_class($oTicket),
			'objkey' => $oTicket->GetKey(),
			'attcode' => 'description',
			'prevdata' => '<div><li>x</li></div></div><script>alert(1)</script>',
		]);

		$sDescription = $oChangeOp->GetDescription();

		$this->assertStringNotContainsString('<script', $sDescription);
		$sPreviousValue = substr($sDescription, strpos($sDescription, '<blockquote>'));
		$this->assertStringEndsWith('</blockquote>', $sPreviousValue);
		$this->assertEquals(substr_count($sPreviousValue, '<div'), substr_count($sPreviousValue, '</div>'), 'Tags of the previous value should be balanced');
	}
}
