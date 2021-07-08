<?php

namespace Combodo\iTop\Test\UnitTest\Core;


use CMDBObject;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @since 2.7.4 tests history objects creation
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CMDBObjectTest extends ItopDataTestCase
{
	/**
	 * @covers CMDBObject::SetCurrentChange
	 */
	public function testCurrentChange()
	{
		// save initial conditions
		$oInitialCurrentChange = CMDBObject::GetCurrentChange();
		$sInitialTrackInfo = CMDBObject::GetTrackInfo();
		// reset current change
		CMDBObject::SetCurrentChange(null);

		//-- new object with only track info
		$sTrackInfo = 'PHPUnit test';
		CMDBObject::SetTrackInfo($sTrackInfo);
		/** @var \DocumentWeb $oTestObject */
		$oTestObject = MetaModel::NewObject('DocumentWeb');
		$oTestObject->Set('name', 'PHPUnit test');
		$oTestObject->Set('org_id', 1);
		$oTestObject->Set('url', 'https://www.combodo.com');
		$oTestObject->DBWrite();
		self::assertFalse(CMDBObject::GetCurrentChange()->IsNew(), 'TrackInfo : Current change persisted');
		self::assertEquals($sTrackInfo, CMDBObject::GetCurrentChange()->Get('userinfo'),
			'TrackInfo : current change created with expected trackinfo');

		//-- new object with non persisted current change
		$sTrackInfo2 = $sTrackInfo.'_2';
		/** @var \CMDBChange $oCustomChange */
		$oCustomChange = MetaModel::NewObject('CMDBChange');
		$oCustomChange->Set('date', time());
		$oCustomChange->Set('userinfo', $sTrackInfo2);
		CMDBObject::SetCurrentChange($oCustomChange);
		$oTestObject->Set('url', 'https://fr.wikipedia.org');
		$oTestObject->DBUpdate();
		self::assertFalse(CMDBObject::GetCurrentChange()->IsNew(), 'SetCurrentChange : Current change persisted');
		self::assertEquals($sTrackInfo2, CMDBObject::GetCurrentChange()->Get('userinfo'),
			'SetCurrentChange : current change created with expected trackinfo');

		//-- new object with current change init using helper method
		$sTrackInfo3 = $sTrackInfo.'_3';
		CMDBObject::SetCurrentChangeFromParams($sTrackInfo3);
		$oTestObject->Set('url', 'https://en.wikipedia.org');
		$oTestObject->DBUpdate();
		self::assertFalse(CMDBObject::GetCurrentChange()->IsNew(), 'SetCurrentChangeFromParams : Current change persisted');
		self::assertEquals($sTrackInfo3, CMDBObject::GetCurrentChange()->Get('userinfo'),
			'SetCurrentChangeFromParams : current change created with expected trackinfo');

		// restore initial conditions
		$oTestObject->DBDelete();
		CMDBObject::SetCurrentChange($oInitialCurrentChange);
		CMDBObject::SetTrackInfo($sInitialTrackInfo);
	}
}