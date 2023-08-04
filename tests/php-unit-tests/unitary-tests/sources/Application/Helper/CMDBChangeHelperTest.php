<?php

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use CMDBChangeOpSetAttributeLongText;
use Combodo\iTop\Application\Helper\CMDBChangeHelper;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use UserRequest;

class CMDBChangeHelperTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	public function testGetAttributeNewValueFromChangeOp()
	{
		$sDescription1 = 'Original description value';
		$sDescription2 = 'Description value 2';
		$sDescription3 = 'Description value 3';
		$sDescription4 = 'Description value 4';

		$oUserRequest = $this->CreateUserRequest(1, [
			'ref' => null,
			'title' => __METHOD__,
			'description' => $sDescription1,
		]);

		// Generating history values
		$oUserRequest->Set('description', $sDescription2);
		$oUserRequest->DBWrite();
		$oUserRequest->Set('description', $sDescription3);
		$oUserRequest->DBWrite();
		$oUserRequest->Set('description', $sDescription4);
		$oUserRequest->DBWrite();

		// Testing values from CMDBChangeOp
		$sUserRequestDescriptionChangeOp = 'SELECT ' . CMDBChangeOpSetAttributeLongText::class . ' WHERE objclass = :objclass AND objkey = :objkey AND attcode = :attcode';
		$oObjectFollowingChangeOpFilter = DBObjectSearch::FromOQL($sUserRequestDescriptionChangeOp, [
			'objclass' => UserRequest::class,
			'objkey' => $oUserRequest->GetKey(),
			'attcode' => 'description',
		]);
		$oSet = new DBObjectSet($oObjectFollowingChangeOpFilter, ['date' => true]);
		$aUserRequestChangeOpObjects = $oSet->ToArray(false);

		$this->assertCount(3, $aUserRequestChangeOpObjects);

		$sChangeOp1NewDescriptionRawValue = CMDBChangeHelper::GetAttributeNewValueFromChangeOp($aUserRequestChangeOpObjects[0]);
		$this->assertSame('<p>'.$sDescription2.'</p>', $sChangeOp1NewDescriptionRawValue);

		$sChangeOp2NewDescriptionRawValue = CMDBChangeHelper::GetAttributeNewValueFromChangeOp($aUserRequestChangeOpObjects[1]);
		$this->assertSame('<p>' . $sDescription3 . '</p>', $sChangeOp2NewDescriptionRawValue);

		$sChangeOp3NewDescriptionRawValue = CMDBChangeHelper::GetAttributeNewValueFromChangeOp($aUserRequestChangeOpObjects[2]);
		$this->assertSame('<p>' . $sDescription4 . '</p>', $sChangeOp3NewDescriptionRawValue);
	}
}
