<?php

/**
 * Copyright (c) 2010-2024 Combodo SAS
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
 */
class ormTagSetTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
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
		static::assertEquals($oTagSet->GetValues(), []);

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag1']);

		$oTagSet->Add('tag2');
		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2']);
	}

	public function testAddTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag1']);

		$oTagSet->SetValues(['tag1', 'tag2']);
		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2']);

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag2']);

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2']);
	}

	/**
	 */
	public function testMaxTagLimit()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, 3);

		$oTagSet->SetValues(['tag1', 'tag2', 'tag3']);

		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2', 'tag3']);

		try {
			$oTagSet->SetValues(['tag1', 'tag2', 'tag3', 'tag4']);
		} catch (CoreException $e) {
			static::assertEquals('Maximum number of tags (3) reached for FAQ:domains', $e->getMessage());
			return;
		}
		static::assertFalse(true);
	}

	public function testEquals()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->Add('tag1');
		static::assertTrue($oTagSet1->Equals($oTagSet1));

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(['tag1']);

		static::assertTrue($oTagSet1->Equals($oTagSet2));

		$oTagSet1->Add('tag2');
		static::assertFalse($oTagSet1->Equals($oTagSet2));
	}

	public function testSetValue()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);

		$oTagSet->SetValues(['tag1']);
		static::assertEquals($oTagSet->GetValues(), ['tag1']);

		$oTagSet->SetValues(['tag1', 'tag2']);
		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2']);

	}

	public function testRemoveTag()
	{
		$oTagSet = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet->Remove('tag_unknown');
		static::assertEquals($oTagSet->GetValues(), []);

		$oTagSet->SetValues(['tag1']);
		$oTagSet->Remove('tag_unknown');
		static::assertEquals($oTagSet->GetValues(), ['tag1']);

		$oTagSet->SetValues(['tag1', 'tag2']);
		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag2']);

		$oTagSet->Add('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag1', 'tag2']);

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag2']);

		$oTagSet->Remove('tag1');
		static::assertEquals($oTagSet->GetValues(), ['tag2']);

		$oTagSet->Remove('tag2');
		static::assertEquals($oTagSet->GetValues(), []);
	}

	public function testGetDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues(['tag1', 'tag2']);

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(['tag1', 'tag3', 'tag4']);

		$aDelta = $oTagSet1->GetDelta($oTagSet2);
		static::assertCount(2, $aDelta);
		static::assertCount(2, $aDelta['added']);
		static::assertCount(1, $aDelta['removed']);
	}

	public function testApplyDelta()
	{
		$oTagSet1 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet1->SetValues(['tag1', 'tag2']);

		$oTagSet2 = new ormTagSet(TAG_CLASS, TAG_ATTCODE, MAX_TAGS);
		$oTagSet2->SetValues(['tag1', 'tag3', 'tag4']);

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

		foreach ($aDiffAndExpectedTags as $aTestItem) {
			$oTagSet1->GenerateDiffFromArray($aTestItem['diff']);
			static::assertEquals($aTestItem['modified'], $oTagSet1->GetModified());
		}
	}

	public function GetModifiedProvider()
	{
		return [
			[
				['tag2'],
				[
					['diff' => ['tag1', 'tag2'], 'modified' => ['tag1']],
					['diff' => ['tag2'], 'modified' => ['tag1']],
					['diff' => [], 'modified' => ['tag1', 'tag2']],
				],
			],
			[
				['tag1', 'tag2'],
				[
					['diff' => ['tag1', 'tag3'], 'modified' => ['tag2', 'tag3']],
					['diff' => ['tag1', 'tag2'], 'modified' => ['tag2', 'tag3']],
					['diff' => ['tag1', 'tag2', 'tag3', 'tag4'], 'modified' => ['tag2', 'tag3', 'tag4']],
				],
			],
			[
				[],
				[
					['diff' => ['tag2'], 'modified' => ['tag2']],
					['diff' => ['tag1', 'tag2'], 'modified' => ['tag1', 'tag2']],
					['diff' => ['tag2'], 'modified' => ['tag1', 'tag2']],
				],
			],
		];
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
		return [
			'Add one tag' => [
				['tag1', 'tag2'],
				['added' => ['tag3']],
				['tag1', 'tag2', 'tag3'],
			],
			'Remove one tag' => [
				['tag1', 'tag2'],
				['removed' => ['tag2']],
				['tag1'],
			],
			'Remove unexisting tag' => [
				['tag1', 'tag2'],
				['removed' => ['tag3']],
				['tag1', 'tag2'],
			],
			'Add one and remove one tag' => [
				['tag1', 'tag2'],
				['added' => ['tag3'], 'removed' => ['tag2']],
				['tag1', 'tag3'],
			],
			'Remove first tag' => [
				['tag1', 'tag2'],
				['removed' => ['tag1']],
				['tag2'],
			],
		];
	}
}
