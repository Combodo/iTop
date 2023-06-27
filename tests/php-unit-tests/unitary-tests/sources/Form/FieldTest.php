<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Form;

use Combodo\iTop\Form\Field\StringField;
use Combodo\iTop\Form\Validator\IntegerValidator;
use Combodo\iTop\Form\Validator\MandatoryValidator;
use Combodo\iTop\Form\Validator\Validator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class FieldTest extends ItopTestCase
{
	public function testIsValidationDisabled(): void
	{
		$oField = new StringField('test');
		$oField->SetCurrentValue('toto@johny.invalid');
		$oDumbEmailValidator = new Validator('\d+@\d+\.\d{2,3}');
		$oField->AddValidator($oDumbEmailValidator);

		$bIsFieldValid = $oField->Validate();
		$this->assertFalse($bIsFieldValid);

		$oField->SetValidationDisabled(true);
		$bIsFieldValidWithValidationDisabled = $oField->Validate();
		$this->assertTrue($bIsFieldValidWithValidationDisabled);
	}

	public function testSetMandatory(): void
	{
		$oField = new StringField('test');
		$this->assertCount(0, $oField->GetValidators());

		$oField->SetMandatory(true);
		$aValidatorsAfterSetMandatoryTrue = $oField->GetValidators();
		$this->assertCount(1, $aValidatorsAfterSetMandatoryTrue);
		$this->assertIsObject($aValidatorsAfterSetMandatoryTrue[0]);
		$this->assertSame(MandatoryValidator::class, get_class($aValidatorsAfterSetMandatoryTrue[0]));

		$oField->SetMandatory(false);
		$this->assertCount(0, $oField->GetValidators());
	}

	public function testAddValidator(): void
	{
		$oField = new StringField('test');
		$this->assertCount(0, $oField->GetValidators());

		$oField->SetCurrentValue('not a numeric value');
		$this->assertTrue($oField->Validate());

		$oField->AddValidator(new IntegerValidator());
		$aValidatorsAfterAddingIntegerValidator = $oField->GetValidators();
		$this->assertCount(1, $aValidatorsAfterAddingIntegerValidator);
		$this->assertIsObject($aValidatorsAfterAddingIntegerValidator[0]);
		$this->assertSame(IntegerValidator::class, get_class($aValidatorsAfterAddingIntegerValidator[0]));
		$this->assertFalse($oField->Validate());
		$this->assertCount(1, $oField->GetErrorMessages());
	}
}