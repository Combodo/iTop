<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use utils;

/**
 * @covers utils
 */
class utilsTestWithDataModel extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

	/**
	 * @dataProvider GetMentionedObjectsFromTextProvider
	 * @covers       utils::GetMentionedObjectsFromText
	 *
	 * @throws \Exception
	 */
	public function testGetMentionedObjectsFromText($sInput, $aExceptedMentionedObjects)
	{
		MetaModel::GetConfig()->Set('mentions.allowed_classes', ['@' => 'Person', '😊#' => 'Team']);
		// Emulate the "Case provider mechanism" (reason: the data provider requires utils constants not available before the application startup)
		$aTestedMentionedObjects = utils::GetMentionedObjectsFromText($sInput);

		$this->AssertArraysHaveSameItems($aExceptedMentionedObjects, $aTestedMentionedObjects);
	}

	/**
	 * @since 3.0.0
	 */
	public function GetMentionedObjectsFromTextProvider(): array
	{
		$sAbsUrlAppRoot = 'https://myitop.com/itop/';

		return [
			'No object' => [
				"Begining
				Second line
				End",
				[],
			],
			'1 Object' => [
				<<<HTML
<p>Beginning</p><p>Before link <a data-role="object-mention" data-object-class="Person" data-object-key="12345" data-object-id="#Test Person" href="$sAbsUrlAppRoot/pages/UI.php?operation=details&class=Person&id=12345">@Test Person</a>After link</p><p>End</p>
HTML,
				[
					'Person' => ['12345'],
				],
			],
			'Should not match 1 Object if the mention prefix is missing' => [
				<<<HTML
<div class="ibo-activity-entry--main-information-content"><p>Beginning</p><p>Before link <a data-role="object-mention" data-object-class="Person" data-object-key="12345" data-object-id="#Test Person 1" href="$sAbsUrlAppRoot/pages/UI.php?operation=details&class=Person&id=12345">#Test Ticket</a> After link</p></div>
HTML,
				[],
			],
			'Should return 2 Objects' => [
				<<<HTML
<div class="ibo-activity-entry--main-information-content"><div class="ibo-activity-entry--main-information-content"><p>Beginning</p><p>Before link <a data-role="object-mention" data-object-class="Person" data-object-key="12345" data-object-id="#Test Person" href="$sAbsUrlAppRoot/pages/UI.php?operation=details&class=UserRequest&id=12345">@Test Person</a> After link</p><p>And <a data-role="object-mention" data-object-class="Person" data-object-id="@Agatha Christie" data-object-key="3" data-object-id="@Agatha Christie" href="$sAbsUrlAppRoot/pages/UI.php?operation=details&class=Person&id=3">@Agatha Christie</a></p><p>End</p></div></div>
HTML,
				[
					'Person' => ['12345', '3'],
				],
			],
			'Should process objects of different classes' => [
				<<<HTML
				Begining
				Before link <a data-object-class="Team" data-object-key="12345" href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=Team&id=12345&foo=bar\">😊#R-012345</a> After link
				And <a data-object-class="Person" data-object-key="3" href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=Person&id=3&foo=bar\">@Claude Monet</a>
				End
HTML,
				[
					'Team' => ['12345'],
					'Person' => ['3'],
				],
			],
		];
	}
}
