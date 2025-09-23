<?php

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class MenuNodeTest extends ItopDataTestCase {
	private \UserRequest $oUR;

	protected function setUp(): void {
		parent::setUp();

		clearstatcache();

		$aUserRequestCustomParams = [
			'title' => "GABUZOMEU",
			'org_id' => $this->CreateOrganization(uniqid())->GetKey(),
		];
		$this->oUR = $this->CreateUserRequest(666, $aUserRequestCustomParams);
	}


	public function RenderOQLSearchProvider()
	{
		$aUseCases = [];
		$aValues = [false, true];
		foreach ($aValues as $bSearchPane) {
			foreach ($aValues as $bSearchOpen) {
				foreach ($aValues as $bAutoreload) {
					$aUseCases[] = [
						'bSearchPane' => $bSearchPane,
						'bSearchOpen' => $bSearchOpen,
						"bAutoreload" => $bAutoreload,
					];
				}

			}

			return $aUseCases;
		}
	}

	/**
	 * @dataProvider RenderOQLSearchProvider
	 */
	public function testRenderOQLSearch($bSearchPane, $bSearchOpen, $bAutoreload)
	{
		$sOql = <<<OQL
SELECT UserRequest
OQL;

		$sContent = $this->CallRenderOQLSearch($bSearchPane, $bSearchOpen, $bAutoreload, $sOql);

		$this->assertTrue(false !== strpos($sContent, $this->oUR->Get('title')), $sContent);
	}

	/**
	 * @covers N°7750 - Bug with OQL set as Shortcut
	 */
	public function testRenderOQLSearchOqlWithDateFormatOnDeadline()
	{
		static::StartStopwatchInThePast($this->oUR, 'ttr', 10);

		$sOql = <<<OQL
SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (DATE_FORMAT(`UserRequest`.`ttr_escalation_deadline`, '%Y%v') != DATE_FORMAT(NOW(), '%Y%v'))
OQL;

		try{
			$sContent = $this->CallRenderOQLSearch(true, true, true, $sOql);
			$this->assertTrue(false !== strpos($sContent, $this->oUR->Get('title')), $sContent);
		} catch(\Exception $e){
			echo($e->getMessage());
			$this->fail('Without N°7750 fix Exception raised => TypeError : date(): Argument #2 ($timestamp) must be of type ?int, string given');
		}
	}

	public function CallRenderOQLSearch(bool $bSearchPane, bool $bSearchOpen, bool $bAutoreload, string $sOql) : string
	{
		$sTitle = 'title';
		$oPage = new WebPage($sTitle);

		if ($bAutoreload) {
			$aExtraParams = [
				'auto_reload' => "5", //value in seconds
			];
		} else {
			$aExtraParams = [];
		}

		\OQLMenuNode::RenderOQLSearch($sOql, $sTitle, 'shortcut_XXX', $bSearchPane, $bSearchOpen, $oPage, $aExtraParams, true);

		$oResponse = $oPage->GenerateResponse();
		return $oResponse->getContent();
	}
}

