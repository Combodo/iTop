<?php
/**
 * Copyright (c) 2010-2018 Combodo SARL
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
use Exception;
use ormTagSet;

/**
 * Tests of the ormTagSet class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ormTagSetTest extends ItopDataTestCase
{

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
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		static::assertEquals($oTagSet->GetTagDataClass(), 'TagSetFieldDataFor_Ticket_tagfield');
	}

	public function testGetValue()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		static::assertEquals($oTagSet->GetValue(), array());

		$oTagSet->AddTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag1'));

		$oTagSet->AddTag('tag2');
		static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));
	}

	public function testAddTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE);

		$oTagSet->AddTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag1'));

		$oTagSet->SetValue(array('tag1', 'tag2'));
		static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

		$oTagSet->RemoveTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag2'));

		$oTagSet->AddTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));
	}

	public function testEquals()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet1->AddTag('tag1');
		static::assertTrue($oTagSet1->Equals($oTagSet1));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet2->SetValue(array('tag1'));

		static::assertTrue($oTagSet1->Equals($oTagSet2));

		$oTagSet1->AddTag('tag2');
		static::assertFalse($oTagSet1->Equals($oTagSet2));
	}

	public function testSetValue()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE);

		$oTagSet->SetValue(array('tag1'));
		static::assertEquals($oTagSet->GetValue(), array('tag1'));

		$oTagSet->SetValue(array('tag1', 'tag2'));
		static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

	}

	public function testRemoveTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet->RemoveTag('tag_unknown');
		static::assertEquals($oTagSet->GetValue(), array());

		$oTagSet->SetValue(array('tag1'));
		$oTagSet->RemoveTag('tag_unknown');
		static::assertEquals($oTagSet->GetValue(), array('tag1'));

		$oTagSet->SetValue(array('tag1', 'tag2'));
		$oTagSet->RemoveTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag2'));

		$oTagSet->AddTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag1', 'tag2'));

		$oTagSet->RemoveTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag2'));

		$oTagSet->RemoveTag('tag1');
		static::assertEquals($oTagSet->GetValue(), array('tag2'));

		$oTagSet->RemoveTag('tag2');
		static::assertEquals($oTagSet->GetValue(), array());
	}

	public function testGetDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet1->SetValue(array('tag1', 'tag2'));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet2->SetValue(array('tag1', 'tag3', 'tag4'));

		$aDelta = $oTagSet1->GetDelta($oTagSet2);
		static::assertCount(2, $aDelta);
		static::assertCount(2, $aDelta['added']);
		static::assertCount(1, $aDelta['removed']);
	}

	public function testApplyDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet1->SetValue(array('tag1', 'tag2'));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet2->SetValue(array('tag1', 'tag3', 'tag4'));

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
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet1->SetValue($aInitialTags);

		foreach($aDiffAndExpectedTags as $aTestItem)
		{
			$oTagSet1->GenerateDiffFromTags($aTestItem['diff']);
			static::assertEquals($aTestItem['modified'], $oTagSet1->GetModifiedTags());
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
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE);
		$oTagSet1->SetValue($aInitialTags);

		$oTagSet1->ApplyDelta($aDelta);

		static::assertEquals($aExpectedTags, $oTagSet1->GetValue());
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
