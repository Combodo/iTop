<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DBObjectSet;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class DisplayBlockTest extends ItopCustomDatamodelTestCase
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__ . '/Delta/add-enum-value-with-quote.xml';
	}

	public function renderChartAjaxProvider(): array
	{
		return [
			'simple string : name' => [
				'class to display'                => 'UserRequest',
				'class attribute to display'      => 'title',
				'class to edit'                   => 'UserRequest',
				'related class attribute to edit' => 'title',
				'expected'                        => "New'name",
				'nonExpected'                     => 'New&apos;name',
			],
			'enum : status'        => [
				// not working because we need to allow a new value for the enum
				'class to display'                => 'UserRequest',
				'attribute to display'            => 'status',
				'class to edit'                   => 'UserRequest',
				'related class attribute to edit' => 'status',
				'expected'                        => "New'status",
				'nonExpected'                     => 'New&apos;status',
			],
			'relation : Org name'  => [
				'class to display'                => 'UserRequest',
				'class attribute to display'      => 'org_name',
				'class to edit'                   => 'Organization',
				'related class attribute to edit' => 'name',
				'expected'                        => "New'org_name",
				'nonExpected'                     => 'New&apos;org_name',
			],
		];
	}

	/**
	 * @covers       \Combodo\iTop\Application\UI\DisplayBlock::RenderChartAjax
	 * @dataProvider renderChartAjaxProvider
	 */
	public function testRenderChartAjax(string $sClassToDisplay, string $sAttributeToDisplay, string $sRelatedClass, string $sRelatedClassAttributeToEdit, string $sExpected, string $sNonExpected): void
	{

		$oFilter = \DBObjectSearch::FromOQL("SELECT $sRelatedClass");
		$oSet = new DBObjectSet($oFilter);
		$oUser = $oSet->Fetch();
		$oUser->Set($sRelatedClassAttributeToEdit, $sExpected); // attribute that shouldn't be encoded
		$oUser->DBUpdate();

		$oDisplayBlock = new \DisplayBlock(
			\DBSearch::FromOQL("SELECT $sClassToDisplay"),
			\DisplayBlock::ENUM_STYLE_CHART_AJAX
		);

		$aExtraParams = [
			"group_by"        => $sAttributeToDisplay,
			"currentId"       => "fake-dashlet-id",
			"order_direction" => "asc",
			"order_by"        => $sAttributeToDisplay,
			"limit"           => 10,
		];
		/** @var \Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxPie\BlockChartAjaxPie $oBlock */
		$oBlock = $this->InvokeNonPublicMethod(get_class($oDisplayBlock), "RenderChartAjax", $oDisplayBlock, [$aExtraParams]);

		$aJSNames = json_decode($oBlock->sJSNames, true);

		$this->assertFalse(in_array($sNonExpected, $aJSNames));
		$this->assertTrue(in_array($sExpected, $aJSNames));
	}
}