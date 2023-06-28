<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Form\Validator;

use Combodo\iTop\Form\Field\StringField;
use Combodo\iTop\Form\Validator\MandatoryValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class ValidatorTest extends ItopTestCase
{
	public function testMandatoryValidator()
	{
		$oField = new StringField('test');
		$oField->SetMandatory(true);
		$oField->SetCurrentValue('there is a value !');

		$bIsMandatoryFieldValidWithExistingValue = $oField->Validate();
		$this->assertTrue($bIsMandatoryFieldValidWithExistingValue);

		$oField->SetCurrentValue(null);
		$bIsMandatoryFieldValidWithNoValue = $oField->Validate();
		$this->assertFalse($bIsMandatoryFieldValidWithNoValue);
		$this->assertNotEmpty($oField->GetErrorMessages());
		$this->assertCount(1, $oField->GetErrorMessages());
		$this->assertStringContainsString(MandatoryValidator::DEFAULT_ERROR_MESSAGE, $oField->GetErrorMessages()[0]);
	}
}