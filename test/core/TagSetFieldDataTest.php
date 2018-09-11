<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 10/09/2018
 * Time: 11:02
 */

namespace Combodo\iTop\Test\UnitTest\Core;

define('TAG_CLASS', 'Ticket');
define('TAG_ATTCODE', 'tagfield');

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use TagSetFieldData;

class TagSetFieldDataTest extends ItopDataTestCase
{
	// Need commit to create the FULLTEXT INDEX of MySQL
	const USE_TRANSACTION = false;

	public function testGetAllowedValues()
	{
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iInitialCount = count($aAllowedValues);
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(1, $iCurrCount - $iInitialCount);
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'Second');
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(2, $iCurrCount - $iInitialCount);
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag3', 'Third');
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(3, $iCurrCount - $iInitialCount);
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag4', 'Fourth');
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(4, $iCurrCount - $iInitialCount);
	}

	public function testDoCheckToWrite()
	{
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iInitialCount = count($aAllowedValues);
		$this->debug("Currently $iInitialCount tags defined");
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'Second');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag3', 'Third');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag4', 'Fourth');
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(4, $iCurrCount - $iInitialCount);

		try
		{
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag4', 'Fourth');
		} catch (\CoreException $e)
		{
			$this->debug($e->getMessage());
		}
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(4, $iCurrCount - $iInitialCount);

		try
		{
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag4', 'zembrek');
		} catch (\CoreException $e)
		{
			$this->debug($e->getMessage());
		}
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(4, $iCurrCount - $iInitialCount);

		try
		{
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag5', 'Fourth');
		} catch (\CoreException $e)
		{
			$this->debug($e->getMessage());
		}
		$aAllowedValues = TagSetFieldData::GetAllowedValues(TAG_CLASS, TAG_ATTCODE);
		$iCurrCount = count($aAllowedValues);
		static::assertEquals(4, $iCurrCount - $iInitialCount);
	}

	/**
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function testDoCheckToDelete()
	{
		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$oTagData->DBDelete();

		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$oTicket = $this->CreateTicket(1);
		$oTicket->Set(TAG_ATTCODE, 'tag1');
		$oTicket->DBWrite();
		try
		{
			$oTagData->DBDelete();
		}
		catch (\CoreException $e)
		{
			static::assertTrue(true);
			return;
		}
		static::assertFalse(true);
	}

	public function testComputeValues()
	{
		$sTagClass = \MetaModel::GetTagDataClass(TAG_CLASS, TAG_ATTCODE);
		$oTagData = $this->createObject($sTagClass, array(
			'tag_code' => 'tag1',
			'tag_label' => 'First',
		));
		$this->debug("Created {$oTagData->Get('tag_class')}::{$oTagData->Get('tag_attcode')}");

		static::assertEquals(TAG_CLASS, $oTagData->Get('tag_class'));
		static::assertEquals(TAG_ATTCODE, $oTagData->Get('tag_attcode'));
	}

}
