<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxPie\BlockChartAjaxPie;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DBSearch;
use DisplayBlock;
use MetaModel;
use UserRequest;

class DisplayBlockTest extends ItopCustomDatamodelTestCase
{
	const CREATE_TEST_ORG = true;
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__ . '/Delta/add-enum-value-with-quote.xml';
	}

	public function renderChartAjaxProvider(): array
	{
		return [
			'simple string : name' => [ // chart with UserRequest title (evaluating string/scalar escaping)
				'class to display'                => 'UserRequest',
				'class attribute to display'      => 'title',
				'class to edit'                   => 'UserRequest',
				'related class attribute to edit' => 'title',
				'expected'                        => "New'name",
				'nonExpected'                     => 'New&apos;name',
			],
			'enum : status'        => [ // chart with UserRequest status (evaluating enum escaping)
				// not working because we need to allow a new value for the enum
				'class to display'                => 'UserRequest',
				'attribute to display'            => 'status',
				'class to edit'                   => 'UserRequest',
				'related class attribute to edit' => 'status',
				'expected'                        => "New'status",
				'nonExpected'                     => 'New&apos;status',
			],
			'relation : Org name'  => [ // chart with related organization name title (evaluating ext key escaping)
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
	 * @dataProvider renderChartAjaxProvider
	 */
	public function testRenderChartAjax(string $sClassToDisplay, string $sAttributeToDisplay, string $sRelatedClass, string $sRelatedClassAttributeToEdit, string $sExpected, string $sNonExpected): void
	{
		$this->markTestSkipped("Waiting for N°7313 to be fixed, this test was made during the first attempt to resolve N°7313, but as it broke N°7592, N°7594, N°7600 & N°7605, we reverted the change until we make a proper fix in Expression::MakeValueLabel()");

		$oUserRequest = new UserRequest();
		$oUserRequest->Set('title', 'MyTitle');
		$oUserRequest->Set('org_id', $this->getTestOrgId());
		$oUserRequest->Set('description', "MyDescription");
		$oUserRequest->DBInsert();

		if ($sRelatedClass !== "UserRequest") {
			$oInstanceRelatedClass = MetaModel::GetObject($sRelatedClass, $this->getTestOrgId());
		} else {
			$oInstanceRelatedClass = $oUserRequest;
		}

		$oInstanceRelatedClass->Set($sRelatedClassAttributeToEdit, $sExpected); // attribute that shouldn't be encoded
		$oInstanceRelatedClass->DBUpdate();

		$oDisplayBlock = new DisplayBlock(
			DBSearch::FromOQL("SELECT $sClassToDisplay"),
			DisplayBlock::ENUM_STYLE_CHART_AJAX
		);

		$aExtraParams = [
			"group_by"        => $sAttributeToDisplay,
			"currentId"       => "fake-dashlet-id",
			"order_direction" => "asc",
			"order_by"        => $sAttributeToDisplay,
			"limit"           => 10,
		];
		/** @var BlockChartAjaxPie $oBlock */
		$oBlock = $this->InvokeNonPublicMethod(get_class($oDisplayBlock), "RenderChartAjax", $oDisplayBlock, [$aExtraParams]);

		$aJSNames = json_decode($oBlock->sJSNames, true);

		$this->assertFalse(in_array($sNonExpected, $aJSNames));
		$this->assertTrue(in_array($sExpected, $aJSNames));
	}
}