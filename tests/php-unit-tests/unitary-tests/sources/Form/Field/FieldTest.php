<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Form\Field;

use Combodo\iTop\Form\Field\StringField;
use Combodo\iTop\Form\Field\SubFormField;
use Combodo\iTop\Form\Validator\CustomRegexpValidator;
use Combodo\iTop\Form\Validator\IntegerValidator;
use Combodo\iTop\Form\Validator\MandatoryValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class FieldTest extends ItopTestCase
{
    public function testIsValidationDisabled(): void
	{
		$oField = new StringField('test');
		$oField->SetCurrentValue('toto@johny.invalid');
		$sDumbEmailValidatorErrorMessage = 'dumb email validator error message';
		$oDumbEmailValidator = new CustomRegexpValidator('\d+@\d+\.\d{2,3}', $sDumbEmailValidatorErrorMessage);
		$oField->AddValidator($oDumbEmailValidator);

		$bIsFieldValid = $oField->Validate();
		$this->assertFalse($bIsFieldValid);
		$this->assertCount(1, $oField->GetErrorMessages());
		$this->assertSame($sDumbEmailValidatorErrorMessage, $oField->GetErrorMessages()[0]);

		/** @noinspection PhpRedundantOptionalArgumentInspection */
		$oField->SetValidationDisabled(true);
		$this->assertTrue($oField->Validate());
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

	public function testValidateWithTwoValidatorsFirstWrong(): void
	{
		$oField = new StringField('test');
		$oField->SetCurrentValue('string with spaces');

		$sFirstValidatorInvalidResultErrorMsg = 'dumb email validator error message';
		$oFirstValidatorInvalidResult = new CustomRegexpValidator('^[a-z]+$', $sFirstValidatorInvalidResultErrorMsg);
		$oField->AddValidator($oFirstValidatorInvalidResult);

		$oSecondValidatorValidResult = new CustomRegexpValidator('^.+$', 'valid');
		$oField->AddValidator($oSecondValidatorValidResult);

		$this->assertFalse($oField->Validate());
		$this->assertCount(1, $oField->GetErrorMessages());
		$this->assertSame($sFirstValidatorInvalidResultErrorMsg, $oField->GetErrorMessages()[0]);
	}

	public function testSubFormFieldValidation(): void
	{
		$oSubFormField = new SubFormField('test_subformfield');

		$oField = new StringField('test_field');
		$oField->SetMandatory(true);

		$oSubFormField->GetForm()->AddField($oField);

		$bIsSubFormFieldValid = $oSubFormField->Validate();
		$this->assertFalse($bIsSubFormFieldValid);
		$this->assertCount(1, $oSubFormField->GetErrorMessages());

		$oField->SetCurrentValue('test string');
		$bIsSubFormFieldValidAfterFieldUpdate = $oSubFormField->Validate();
		$this->assertTrue($bIsSubFormFieldValidAfterFieldUpdate);
		$this->assertCount(0, $oSubFormField->GetErrorMessages());
	}

	public function testRemoveValidatorsOfClass(): void {
		$oField = new StringField('test');

		$this->assertCount(0, $oField->GetValidators());
		$oField->RemoveValidatorsOfClass(CustomRegexpValidator::class);
		$this->assertCount(0, $oField->GetValidators());

		$oField->AddValidator(new IntegerValidator());
		$this->assertCount(1, $oField->GetValidators());
		$oField->RemoveValidatorsOfClass(CustomRegexpValidator::class);
		$this->assertCount(1, $oField->GetValidators());

		$oField->AddValidator(new CustomRegexpValidator('^.*$'));
		$this->assertCount(2, $oField->GetValidators());
		$oField->RemoveValidatorsOfClass(CustomRegexpValidator::class);
		$this->assertCount(1, $oField->GetValidators());

		$oField->AddValidator(new CustomRegexpValidator('^.*$'));
		$oField->AddValidator(new CustomRegexpValidator('^.*$'));
		$this->assertCount(3, $oField->GetValidators());
		$oField->RemoveValidatorsOfClass(CustomRegexpValidator::class);
		$this->assertCount(1, $oField->GetValidators());
	}
}