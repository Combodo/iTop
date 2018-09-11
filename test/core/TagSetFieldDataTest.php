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
	 * @expectedException \CoreException
	 *
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
		$oTagData->DBDelete($oDeletionPlan);
	}

	public function testComputeValues()
	{
	}

	public function testGetAllowedValues()
	{

	}
}
