<?php
/**
 * Copyright (c) 2010-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 27/08/2018
 * Time: 17:26
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use Exception;
use ormTagSet;

define('MAX_TAGS', 12);

/**
 * @group itopFaqLight
 * Tests of the ormTagSet class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ormTagSetTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'Second');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag3', 'Third');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag4', 'Fourth');
	}

	public function testGetTagDataClass()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		static::assertEquals($oTagSet->GetTagDataClass(), 'TagSetFieldDataFor_'.TAG_CLASS.'__'.TAG_ATTCODE);
	}

	public function testGetValue()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		static::assertEquals($oTagSet->GetValues(), array());

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag1'));

		$oTagSet->Add('tag2');
		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2'));
	}

	public function testAddTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag1'));

		$oTagSet->SetValues(array('tag1', 'tag2'));
		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2'));

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag2'));

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2'));
	}


	/**
	 * @expectedException \CoreException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function testMaxTagLimit()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, 3);

		$oTagSet->SetValues(array('tag1', 'tag2', 'tag3'));

		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2', 'tag3'));

		try
		{
			$oTagSet->SetValues(array('tag1', 'tag2', 'tag3', 'tag4'));
		}
		catch (CoreException $e)
		{
			$this->debug('Awaited: '.$e->getMessage());
			throw $e;
		}
	}

	public function testEquals()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->Add('tag1');
		static::assertTrue($oTagSet1->Equals($oTagSet1));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(array('tag1'));

		static::assertTrue($oTagSet1->Equals($oTagSet2));

		$oTagSet1->Add('tag2');
		static::assertFalse($oTagSet1->Equals($oTagSet2));
	}

	public function testSetValue()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);

		$oTagSet->SetValues(array('tag1'));
		static::assertEquals($oTagSet->GetValues(), array('tag1'));

		$oTagSet->SetValues(array('tag1', 'tag2'));
		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2'));

	}

	public function testRemoveTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet->Remove('tag_unknown');
		static::assertEquals($oTagSet->GetValues(), array());

		$oTagSet->SetValues(array('tag1'));
		$oTagSet->Remove('tag_unknown');
		static::assertEquals($oTagSet->GetValues(), array('tag1'));

		$oTagSet->SetValues(array('tag1', 'tag2'));
		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag2'));

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag1', 'tag2'));

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag2'));

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), array('tag2'));

		$oTagSet->Remove('tag2');
		static::assertEquals($oTagSet->GetValues(), array());
	}

	public function testGetDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues(array('tag1', 'tag2'));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(array('tag1', 'tag3', 'tag4'));

		$aDelta = $oTagSet1->GetDelta($oTagSet2);
		static::assertCount(2, $aDelta);
		static::assertCount(2, $aDelta['added']);
		static::assertCount(1, $aDelta['removed']);
	}

	public function testApplyDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues(array('tag1', 'tag2'));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(array('tag1', 'tag3', 'tag4'));

		$aDelta = $oTagSet1->GetDelta($oTagSet2);

		$oTagSet1->ApplyDelta($aDelta);

		static::assertTrue($oTagSet1->Equals($oTagSet2));
	}

	/**
	 * @param $aInitialTags
	 * @param $aDiffTags
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 *
	 * @dataProvider GetModifiedProvider
	 */
	public function testGetModified($aInitialTags, $aDiffAndExpectedTags)
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues($aInitialTags);

		foreach($aDiffAndExpectedTags as $aTestItem)
		{
			$oTagSet1->GenerateDiffFromArray($aTestItem['diff']);
			static::assertEquals($aTestItem['modified'], $oTagSet1->GetModified());
		}
	}

	public function GetModifiedProvider()
	{
		return array(
			array(
				array('tag2'),
				array(
					array('diff' => array('tag1', 'tag2'), 'modified' => array('tag1')),
					array('diff' => array('tag2'), 'modified' => array('tag1')),
					array('diff' => array(), 'modified' => array('tag1', 'tag2')),
				)
			),
			array(
				array('tag1', 'tag2'),
				array(
					array('diff' => array('tag1', 'tag3'), 'modified' => array('tag2', 'tag3')),
					array('diff' => array('tag1', 'tag2'), 'modified' => array('tag2', 'tag3')),
					array('diff' => array('tag1', 'tag2', 'tag3', 'tag4'), 'modified' => array('tag2', 'tag3', 'tag4')),
				)
			),
			array(
				array(),
				array(
					array('diff' => array('tag2'), 'modified' => array('tag2')),
					array('diff' => array('tag1', 'tag2'), 'modified' => array('tag1', 'tag2')),
					array('diff' => array('tag2'), 'modified' => array('tag1', 'tag2')),
				)
			),
		);
	}

	/**
	 * @param $aInitialTags
	 * @param $aDelta
	 * @param $aExpectedTags
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 * @dataProvider BulkModifyProvider
	 */
	public function testBulkModify($aInitialTags, $aDelta, $aExpectedTags)
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues($aInitialTags);

		$oTagSet1->ApplyDelta($aDelta);

		static::assertEquals($aExpectedTags, $oTagSet1->GetValues());
	}

	public function BulkModifyProvider()
	{
		return array(
			'Add one tag' => array(
				array('tag1', 'tag2'),
				array('added' => array('tag3')),
				array('tag1', 'tag2', 'tag3')
			),
			'Remove one tag' => array(
				array('tag1', 'tag2'),
				array('removed' => array('tag2')),
				array('tag1')
			),
			'Remove unexisting tag' => array(
				array('tag1', 'tag2'),
				array('removed' => array('tag3')),
				array('tag1', 'tag2')
			),
			'Add one and remove one tag' => array(
				array('tag1', 'tag2'),
				array('added' => array('tag3'), 'removed' => array('tag2')),
				array('tag1', 'tag3')
			),
			'Remove first tag' => array(
				array('tag1', 'tag2'),
				array('removed' => array('tag1')),
				array('tag2')
			),
		);
	}
}
