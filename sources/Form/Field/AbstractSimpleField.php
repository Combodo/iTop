<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Field;

use Combodo\iTop\Form\Validator\MandatoryValidator;

/**
 * @since 3.1.0 N°6414
 */
class AbstractSimpleField extends Field
{
	final public function Validate()
	{
		$this->SetValid(true);
		$this->EmptyErrorMessages();

		if ($this->bValidationDisabled) {
			return $this->GetValid();
		}

		$bEmpty = (($this->GetCurrentValue() === null) || ($this->GetCurrentValue() === ''));

		if (!$bEmpty || $this->GetMandatory()) {
			foreach ($this->GetValidators() as $oValidator) {
				$aValidationErrorMessages = $oValidator->Validate($this->GetCurrentValue());

				if (count($aValidationErrorMessages) > 0) {
					$this->SetValid(false);
					foreach ($aValidationErrorMessages as $sErrorMessage) {
						$this->AddErrorMessage($sErrorMessage);
					}
				}
			}
		}

		return $this->GetValid();
	}

	public function SetMandatory(bool $bMandatory)
	{
		// Before changing the property, we check if it was already mandatory. If not, we had the mandatory validator
		if ($bMandatory && !$this->bMandatory) {
			$this->AddValidator($this->GetMandatoryValidatorInstance());
		}

		if (false === $bMandatory) {
			foreach ($this->aValidators as $iKey => $oValue) {
				if ($oValue instanceof MandatoryValidator) {
					unset($this->aValidators[$iKey]);
				}
			}
		}

		$this->bMandatory = $bMandatory;

		return parent::SetMandatory($bMandatory);
	}
}