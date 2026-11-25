<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use RestUtils;
use Ticket;
use UserRequest;

class RestUtilsTest extends ItopDataTestCase
{
	/**
	 * @group itopFaqLight
	 * @since 3.4.0
	 */
	public function testUpdateObjectFromFieldsReplacesAndClearsTagSet(): void
	{
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'Second');
		$oFaq = $this->CreateObjectWithTagSet();
		$oAttDef = MetaModel::GetAttributeDef(TAG_CLASS, TAG_ATTCODE);

		foreach ([['tag1'], ['tag1', 'tag2'], []] as $aCodes) {
			RestUtils::UpdateObjectFromFields($oFaq, [TAG_ATTCODE => $aCodes]);
			$this->assertSame($aCodes, $oAttDef->GetForJSON($oFaq->Get(TAG_ATTCODE)));
		}
	}

	/**
	 * @group itopFaqLight
	 * @since 3.4.0
	 */
	public function testMakeValueRejectsUnknownTagCode(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('domains: missing_tag is not defined as a valid tag for FAQ:domains');
		RestUtils::MakeValue(TAG_CLASS, TAG_ATTCODE, ['missing_tag']);
	}

	/**
	 * @group itopFaqLight
	 * @since 3.4.0
	 */
	public function testMakeValueRequiresArrayForTagSet(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('domains: A tag set must be defined by an array of tag codes');
		RestUtils::MakeValue(TAG_CLASS, TAG_ATTCODE, '["tag1"]');
	}

	public function testGetFieldListForSingleClass(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'ref,start_date,end_date'], 'output_fields');
		$this->assertSame([Ticket::class => ['ref', 'start_date', 'end_date']], $aList);
	}

	public function testGetFieldListForSingleClassWithInvalidFieldNameFails(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('output_fields: invalid attribute code \'something\' for class \'Ticket\'');
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'ref,something'], 'output_fields');
		$this->assertSame([Ticket::class => ['ref', 'start_date', 'end_date']], $aList);
	}

	public function testGetFieldListWithAsteriskOnParentClass(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => '*'], 'output_fields');
		$this->assertArrayHasKey(Ticket::class, $aList);
		$this->assertContains('operational_status', $aList[Ticket::class]);
		$this->assertNotContains('status', $aList[Ticket::class], 'Representation of Class Ticket should not contain status, since it is defined by children');
	}

	public function testGetFieldListWithAsteriskPlusOnParentClass(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => '*+'], 'output_fields');
		$this->assertArrayHasKey(Ticket::class, $aList);
		$this->assertArrayHasKey(UserRequest::class, $aList);
		$this->assertContains('operational_status', $aList[Ticket::class]);
		$this->assertContains('status', $aList[UserRequest::class]);
	}

	public function testGetFieldListForMultipleClasses(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'Ticket:ref,start_date,end_date;UserRequest:ref,status'], 'output_fields');
		$this->assertArrayHasKey(Ticket::class, $aList);
		$this->assertArrayHasKey(UserRequest::class, $aList);
		$this->assertContains('ref', $aList[Ticket::class]);
		$this->assertContains('end_date', $aList[Ticket::class]);
		$this->assertNotContains('status', $aList[Ticket::class]);
		$this->assertContains('status', $aList[UserRequest::class]);
		$this->assertNotContains('end_date', $aList[UserRequest::class]);
	}

	public function testGetFieldListForMultipleClassesWithInvalidFieldNameFails(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('output_fields: invalid attribute code \'something\'');
		RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'Ticket:ref;UserRequest:ref,something'], 'output_fields');
	}

	public function testGetFieldListForMultipleClassesWithInvalidFieldName(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'ref, something'], 'output_fields', false);
		$this->assertContains('ref', $aList[Ticket::class]);
		$this->assertNotContains('something', $aList[Ticket::class]);
	}

	public static function extendedOutputDataProvider(): array
	{
		return [
			[false, 'ref,start_date,end_date'],
			[false, '*'],
			[true, '*+'],
			[false, 'Ticket:ref'],
			[true, 'Ticket:ref;UserRequest:ref'],
		];
	}

	/**
	 * @dataProvider extendedOutputDataProvider
	 */
	public function testIsExtendedOutputRequest(bool $bExpected, string $sFields): void
	{
		$this->assertSame($bExpected, RestUtils::HasRequestedExtendedOutput($sFields));
	}

	public static function allFieldsOutputDataProvider(): array
	{
		return [
			[false, 'ref,start_date,end_date'],
			[true, '*'],
			[true, '*+'],
			[false, 'Ticket:ref'],
			[false, 'Ticket:ref;UserRequest:ref'],
		];
	}

	/**
	 * @dataProvider allFieldsOutputDataProvider
	 */
	public function testIsAllFieldsOutputRequest(bool $bExpected, string $sFields): void
	{
		$this->assertSame($bExpected, RestUtils::HasRequestedAllOutputFields($sFields));
	}
}
