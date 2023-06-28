<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Form;

use Combodo\iTop\Form\Field\LinkedSetField;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ormLinkSet;
use Person;

class LinkedSetFieldTest extends ItopDataTestCase {
	public function testValidate(): void {
		$sLinkedClass = Ticket::class;
		$oLinkedSetField = new LinkedSetField('test');
		$oLinkedSetField->SetIndirect(false);
		$oLinkedSetField->SetTargetClass($sLinkedClass);
		$oLinkedSetField->SetLinkedClass($sLinkedClass);
		$oLinkedSetField->SetLnkAttributesToDisplay(['title' => 'title']);

		$oSetThreeExistingTickets = new ormLinkSet(Person::class, 'tickets_list');
		$this->CreateTestOrganization();
		$oUserRequest1 = $this->CreateUserRequest(1);
		$oUserRequest2 = $this->CreateUserRequest(2);
		$oUserRequest3 = $this->CreateUserRequest(3);
		$oSetThreeExistingTickets->AddItem($oUserRequest1);
		$oSetThreeExistingTickets->AddItem($oUserRequest2);
		$oSetThreeExistingTickets->AddItem($oUserRequest3);
		$oLinkedSetField->SetCurrentValue($oSetThreeExistingTickets);
		$this->assertTrue($oLinkedSetField->Validate(), 'A set with existing objects and no modifications must be OK');

		$oUserRequest1->Set('title', 'this a modified title !');
		$this->assertTrue($oLinkedSetField->Validate(), 'A set with existing objects and a valid modification must be OK');

		$oUserRequest1->Set('title', '');
		$this->assertFalse($oLinkedSetField->Validate(), 'A set with existing objects and an invalid modification must be KO');
	}
}