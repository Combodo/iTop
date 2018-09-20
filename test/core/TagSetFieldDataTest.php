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
		$sTagClass = TagSetFieldData::GetTagDataClassName(TAG_CLASS, TAG_ATTCODE);
		$oTagData = $this->createObject($sTagClass, array(
			'code' => 'tag1',
			'label' => 'First',
		));
		$this->debug("Created {$oTagData->Get('obj_class')}::{$oTagData->Get('obj_attcode')}");

		static::assertEquals(TAG_CLASS, $oTagData->Get('obj_class'));
		static::assertEquals(TAG_ATTCODE, $oTagData->Get('obj_attcode'));
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
			'At least 3 chars' => array(''),
			'At least 3 chars 1' => array('a'),
			'At least 3 chars 2' => array('ab'),
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
	 * Test that tag code cannot be modified if used
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function testUpdateCode()
	{
		$oTagData = $this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$oTagData->Set('code', 'tag2');
		$oTagData->DBWrite();

		//Use it
		$oTicket = $this->CreateTicket(1);
		$oTicket->Set(TAG_ATTCODE, 'tag2');
		$oTicket->DBWrite();

		// Try to change the code of the tag, must complain !
		try
		{
			$oTagData->Set('code', 'tag1');
			$oTagData->DBWrite();

		} catch (\CoreException $e)
		{
			static::assertTrue(true);

			return;
		}
		// Should not pass here
		static::assertFalse(true);
	}

	/**
	 * Check that the code length is correctly checked
	 *
	 * @throws \CoreException
	 */
	public function testMaxTagCodeLength()
	{
		/** @var \AttributeTagSet $oAttdef */
		$oAttdef = \MetaModel::GetAttributeDef(TAG_CLASS, TAG_ATTCODE);

		$iMaxLength = $oAttdef->GetTagCodeMaxLength();
		$sTagCode = str_repeat('a', $iMaxLength);

		// Should work
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, $sTagCode, $sTagCode);

		// Too long
		$sTagCode = str_repeat('a', $iMaxLength + 1);
		try
		{
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, $sTagCode, $sTagCode);
		} catch (\CoreException $e)
		{
			$this->debug('Awaited: '.$e->getMessage());
			static::assertTrue(true);
			return;
		}
		// Failed
		static::assertFalse(true);
	}

	public function testMaxTagsAllowed()
	{
		/** @var \AttributeTagSet $oAttDef */
		$oAttDef = \MetaModel::GetAttributeDef(TAG_CLASS, TAG_ATTCODE);
		$iMaxTags = $oAttDef->GetTagMaxNb();
		for ($i = 0; $i < $iMaxTags; $i++)
		{
			$sTagCode = 'MaxTag'.$i;
			$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, $sTagCode, $sTagCode);
		}
		$oTicket = $this->CreateTicket(1);
		$this->debug("Max number of tags is $iMaxTags");
		$sValue = '';
		for ($i = 0; $i < ($iMaxTags + 1); $i++)
		{
			try
			{
				$sTagCode = 'MaxTag'.$i;
				$sValue .= "$sTagCode ";
				$oTicket->Set(TAG_ATTCODE, $sValue);
				$oTicket->DBWrite();
			} catch (\Exception $e)
			{
				// Should fail on the last iteration
				static::assertEquals($iMaxTags, $i);
				$this->debug("Setting (".($i+1).") tag(s) failed");
				return;
			}
			$this->debug("Setting (".($i+1).") tag(s) worked");
		}

		static::assertFalse(true);
	}
}
