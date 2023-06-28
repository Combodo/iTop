<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Form;

use Combodo\iTop\Form\Field\SelectObjectField;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;
use DBObjectSearch;
use Organization;

class SelectObjectFieldTest extends ItopDataTestCase {
	public function testValidate(): void {
		$oSelectObjectField = new SelectObjectField('test');
		$oSelectObjectField->SetSearch(DBObjectSearch::FromOQL('SELECT '.Organization::class));

		// N°1150 the control was added for the REST API initially and was only triggered with the corresponding ContextTag
		$oRestContext = new ContextTag(ContextTag::TAG_REST);
		$this->ValidateSelectObjectField($oSelectObjectField);

		// retrying without REST context
		unset($oRestContext);
		$this->ValidateSelectObjectField($oSelectObjectField);
	}

	private function ValidateSelectObjectField(SelectObjectField $oSelectObjectField): void {
		$oSelectObjectField->SetCurrentValue(null);
		$this->assertTrue($oSelectObjectField->Validate(), 'No value must be valid');

		$sExistingOrganizationId = 1;
		$oSelectObjectField->SetCurrentValue($sExistingOrganizationId);
		$this->assertTrue($oSelectObjectField->Validate(), 'An existing object id must be valid');

		$sNonExistingOrganizationId = 999999;
		$oSelectObjectField->SetCurrentValue($sNonExistingOrganizationId);
		$this->assertFalse($oSelectObjectField->Validate(), 'An non existing object id must be invalid');
		$this->assertCount(1, $oSelectObjectField->GetErrorMessages());
		$this->assertStringContainsString($sNonExistingOrganizationId, $oSelectObjectField->GetErrorMessages()[0]);
	}
}