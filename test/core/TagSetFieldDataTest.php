<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 10/09/2018
 * Time: 11:02
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use TagSetFieldData;

class TagSetFieldDataTest extends ItopDataTestCase
{
	// Need database COMMIT in order to create the FULLTEXT INDEX of MySQL
	const USE_TRANSACTION = false;

	/**
	 * @throws \CoreException
	 */
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

	/**
	 * @throws \CoreException
	 */
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
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'zembrek', 'Fourth');
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
	 * @throws \Exception
	 */
	public function testDoCheckToDelete()
	{
		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$oTagData->DBDelete();

		// Create a tag
		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		//Use it
		$oTicket = $this->CreateTicket(1);
		$oTicket->Set(TAG_ATTCODE, 'tag1');
		$oTicket->DBWrite();

		// Try to delete the tag, must complain !
		try
		{
			$oTagData->DBDelete();
		} catch (\DeleteException $e)
		{
			static::assertTrue(true);

			return;
		}
		// Should not pass here
		static::assertFalse(true);
	}

	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
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

	/**
	 * Test invalid tag codes
	 * @dataProvider InvalidTagCodeProvider
	 *
	 * @expectedException \CoreException
	 *
	 * @param string $sTagCode
	 *
	 * @throws \CoreException
	 */
	public function testInvalidTagCode($sTagCode)
	{
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, $sTagCode, 'First');
		// Should not pass here
		static::assertFalse(true);
	}

	public function InvalidTagCodeProvider()
	{
		return array(
			'No space' => array('tag1 1'),
			'No _' => array('tag_1'),
			'No -' => array('tag-1'),
			'No %' => array('tag%1'),
			'Less than 21 chars' => array('012345678901234567890'),
			'At least one char' => array(''),
			'No #' => array('#tag'),
			'No !' => array('tag!'),
		);
	}

	/**
	 * Test invalid tag labels
	 * @expectedException \CoreException
	 * @throws \CoreException
	 */
	public function testInvalidTagLabel()
	{
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First|Second');
		// Should not pass here
		static::assertFalse(true);
	}

	/**
	 * Test that tag code cannot be modified
	 * @expectedException \CoreException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function testUpdateCode()
	{
		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$oTagData->Set('tag_code', 'tag2');
		$oTagData->DBWrite();
	}
}
