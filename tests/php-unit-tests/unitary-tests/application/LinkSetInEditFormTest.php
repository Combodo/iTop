<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;
use MetaModel;
use utils;

/**
 * @author Alexandre Forestier
 * @covers \cmdbAbstractObject::DisplayBareRelations
 * @covers \cmdbAbstractObject::IsLinkSetTooLargeForEditForm
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
		$oLocation = $this->CreateLocationWithPeripherals();

		$this->assertSame(
			$bExpectedInForm,
			$this->IsLinkSetInEditForm($oLocation, 'physicaldevice_list'),
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

	/**
	 * Pending changes (eg. form displayed again after a validation error) only exist in the form widget
	 */
	public function testLargeLinkSetWithPendingChangesIsKeptInEditForm()
	{
		MetaModel::GetConfig()->Set(static::CONFIG_PARAM, static::LINKED_ITEMS_COUNT - 1);
		$oLocation = $this->CreateLocationWithPeripherals();

		$oPendingPeripheral = MetaModel::NewObject('Peripheral', [
			'name'   => 'Pending peripheral',
			'org_id' => $this->getTestOrgId(),
		]);
		$oDevices = $oLocation->Get('physicaldevice_list');
		$oDevices->AddItem($oPendingPeripheral);
		$oLocation->Set('physicaldevice_list', $oDevices);

		$this->assertTrue(
			$this->IsLinkSetInEditForm($oLocation, 'physicaldevice_list'),
			'A linkset with pending changes should stay in the modification form, otherwise its changes would be lost'
		);
	}

	/**
	 * Attaching existing objects to a 1:n linkset in "add_remove" mode is only possible from the form widget
	 */
	public function testLargeAddRemoveLinkSetIsKeptInEditForm()
	{
		MetaModel::GetConfig()->Set(static::CONFIG_PARAM, static::LINKED_ITEMS_COUNT - 1);
		$oParentRequest = $this->CreateUserRequest(0);
		for ($i = 1; $i <= static::LINKED_ITEMS_COUNT; $i++) {
			$this->CreateUserRequest($i, ['parent_request_id' => $oParentRequest->GetKey()]);
		}
		$oParentRequest->Reload();

		$this->assertSame(
			LINKSET_EDITMODE_ADDREMOVE,
			MetaModel::GetAttributeDef('UserRequest', 'related_request_list')->GetEditMode(),
			'Test prerequisite: UserRequest::related_request_list should be a 1:n linkset in "add_remove" mode'
		);
		$this->assertTrue(
			$this->IsLinkSetInEditForm($oParentRequest, 'related_request_list'),
			'A 1:n linkset in "add_remove" mode should stay in the modification form, to attach existing objects'
		);
	}

	private function CreateLocationWithPeripherals(): DBObject
	{
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

		return $oLocation;
	}

	/**
	 * Render the tabs of the object in edition, then tell whether the linkset is part of the posted form
	 */
	private function IsLinkSetInEditForm(DBObject $oObject, string $sAttCode): bool
	{
		// The page opens its own output buffer: close it once the tabs are rendered
		$iOutputBufferLevel = ob_get_level();
		$oPage = new AjaxPage('');
		// As in a real modification form (some extensions, like attachments, require it in edition)
		$oPage->SetTransactionId(utils::GetNewTransactionId());
		$oObject->DisplayBareRelations($oPage, true);
		while (ob_get_level() > $iOutputBufferLevel) {
			ob_end_clean();
		}

		return array_key_exists($sAttCode, (array)$this->GetNonPublicProperty($oObject, 'aFieldsMap'));
	}
}
