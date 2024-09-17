<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use RestUtils;
use Ticket;
use UserRequest;


class RestUtilsTest extends ItopDataTestCase
{
	public function testGetFieldListForSingleClass(): void
	{
		$aList = RestUtils::GetFieldList(Ticket::class, (object) ['output_fields' => 'ref,start_date,end_date'], 'output_fields');
		$this->assertSame([Ticket::class => ['ref', 'start_date', 'end_date']], $aList);
	}

	public function testGetFieldListForSingleClassWithInvalidFieldNameFails(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('output_fields: invalid attribute code \'something\'');
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

	/**
	 * @dataProvider extendedOutputDataProvider
	 */
	public function testIsExtendedOutputRequest(bool $bExpected, string $sFields): void
	{
		$this->assertSame($bExpected, RestUtils::HasRequestedExtendedOutput($sFields));
	}

	/**
	 * @dataProvider allFieldsOutputDataProvider
	 */
	public function testIsAllFieldsOutputRequest(bool $bExpected, string $sFields): void
	{
		$this->assertSame($bExpected, RestUtils::HasRequestedAllOutputFields($sFields));
	}

	public function extendedOutputDataProvider(): array
	{
		return [
			[false, 'ref,start_date,end_date'],
			[false, '*'],
			[true, '*+'],
			[false, 'Ticket:ref'],
			[true, 'Ticket:ref;UserRequest:ref'],
		];
	}

	public function allFieldsOutputDataProvider(): array
	{
		return [
			[false, 'ref,start_date,end_date'],
			[true, '*'],
			[true, '*+'],
			[false, 'Ticket:ref'],
			[false, 'Ticket:ref;UserRequest:ref'],
		];
	}
}
