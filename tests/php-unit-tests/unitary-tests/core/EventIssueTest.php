<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class EventIssueTest extends ItopDataTestCase
{
	/**
	 * Data provider for test EventIssue Creation
	 *
	 * @return array data
	 * @since 3.1.2 N°3448 - Framework field size check not correctly implemented for multi-bytes languages/strings
	 */
	public function OnInsertProvider()
	{
		return [
			'all with ééé'                            => [
				str_repeat('é', 120),
				str_repeat('é', 120),
				str_repeat('é', 120),
				str_repeat('é', 120),
			],
			'all with 255 smiley' => [
				str_repeat('😎', 255),
				str_repeat('😎', 255),
				str_repeat('😎', 255),
				str_repeat('😎', 255),
			],
			'all with 255 characters,  us-ascii only' => [
				str_repeat('a', 255),
				str_repeat('a', 255),
				str_repeat('a', 255),
				str_repeat('a', 255),
			],
			'all with 255 é'                          => [
				str_repeat('é', 255),
				str_repeat('é', 255),
				str_repeat('é', 255),
				str_repeat('é', 255),
			],
		];
	}

	/**
	 * EventIssue has a OnInsert override that uses mb_strlen, so we need to test this specific case
	 *
	 * @covers       EventIssue::OnInsert
	 *
	 * @dataProvider OnInsertProvider
	 *
	 * @since 3.1.2 N°3448 - Framework field size check not correctly implemented for multi-bytes languages/strings
	 */
	public function testOnInsert(string $sIssue, string $sImpact, string $sPage, string $sMessage)
	{
		$oEventIssue = MetaModel::NewObject('EventIssue', [
			'issue'   => $sIssue,
			'impact'  => $sImpact,
			'page'    => $sPage,
			'message' => $sMessage,
		]);

		try {
			$oEventIssue->DBInsert();
		} catch (CoreException $e) {
			$this->fail('we should be able to persist the object though it contains long values in its attributes: '.$e->getMessage());
		}
		$this->assertTrue(true);
	}
}
