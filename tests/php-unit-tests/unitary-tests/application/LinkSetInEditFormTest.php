<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @author Alexandre Forestier
 * @covers \cmdbAbstractObject::DisplayBareRelations
 */
class LinkSetInEditFormTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	private const CONFIG_PARAM = 'max_linkset_items_in_edit_form';
	private const LINKED_ITEMS_COUNT = 3;

	private $iPreviousMaxItems;

	protected function setUp(): void
	{
		parent::setUp();
		$this->iPreviousMaxItems = MetaModel::GetConfig()->Get(static::CONFIG_PARAM);
	}

	protected function tearDown(): void
	{
		MetaModel::GetConfig()->Set(static::CONFIG_PARAM, $this->iPreviousMaxItems);
		parent::tearDown();
	}

	/**
	 * @dataProvider LinkSetInEditFormProvider
	 */
	public function testLinkSetIsEditedInFormOnlyUpToTheConfiguredSize(int $iMaxItems, bool $bExpectedInForm)
	{
		MetaModel::GetConfig()->Set(static::CONFIG_PARAM, $iMaxItems);

		$oLocation = $this->createObject('Location', [
			'name'   => 'Location with physical devices',
			'org_id' => $this->getTestOrgId(),
		]);
		for ($i = 1; $i <= static::LINKED_ITEMS_COUNT; $i++) {
			$this->createObject('Peripheral', [
				'name'        => 'Peripheral '.$i,
				'org_id'      => $this->getTestOrgId(),
				'location_id' => $oLocation->GetKey(),
			]);
		}
		$oLocation->Reload();

		// The page opens its own output buffer: close it once the tabs are rendered
		$iOutputBufferLevel = ob_get_level();
		$oLocation->DisplayBareRelations(new AjaxPage(''), true);
		while (ob_get_level() > $iOutputBufferLevel) {
			ob_end_clean();
		}

		$aFieldsMap = (array)$this->GetNonPublicProperty($oLocation, 'aFieldsMap');
		$this->assertSame(
			$bExpectedInForm,
			array_key_exists('physicaldevice_list', $aFieldsMap),
			$bExpectedInForm
				? 'The linkset should be editable in the modification form'
				: 'The linkset should be displayed as in the object details, not loaded in the modification form'
		);
	}

	public function LinkSetInEditFormProvider(): array
	{
		return [
			'No limit'                          => [0, true],
			'Limit greater than the item count' => [static::LINKED_ITEMS_COUNT + 1, true],
			'Limit equal to the item count'     => [static::LINKED_ITEMS_COUNT, true],
			'Limit lower than the item count'   => [static::LINKED_ITEMS_COUNT - 1, false],
		];
	}
}
