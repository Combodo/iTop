<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Field;


use BinaryExpression;
use DBObjectSet;
use FieldExpression;
use ScalarExpression;
use utils;

/**
 * Description of MultipleSelectObjectField
 *
 * @author Acc
 * @since 3.1.0
 */
class MultipleSelectObjectField extends SelectObjectField
{
	const DEFAULT_MULTIPLE_VALUES_ENABLED = true;

	function SetCurrentValue($currentValue)
	{
		if ($currentValue != null) {
			$this->currentValue = $currentValue;
		} else {
			$this->currentValue = "";
		}

		return $this;
	}


	public function Validate()
	{
		$this->SetValid(true);
		$this->EmptyErrorMessages();

		if ($this->GetReadOnly() === false) {
			if (count($this->currentValue) > 0) {
				foreach ($this->currentValue as $sCode => $value) {
					if (utils::IsNullOrEmptyString($value) || ($value == 0)) {
						continue;
					}
					$oSearchForExistingCurrentValue = $this->oSearch->DeepClone();
					$oSearchForExistingCurrentValue->AddCondition('id', $value, '=');
					$oCheckIdAgainstCurrentValueExpression = new BinaryExpression(
						new FieldExpression('id', $oSearchForExistingCurrentValue->GetClassAlias()), '=', new ScalarExpression($value)
					);
					$oSearchForExistingCurrentValue->AddConditionExpression($oCheckIdAgainstCurrentValueExpression);
					$oSetForExistingCurrentValue = new DBObjectSet($oSearchForExistingCurrentValue);
					$iObjectsCount = $oSetForExistingCurrentValue->CountWithLimit(1);

					if ($iObjectsCount === 0) {
						$this->SetValid(false);
						$this->AddErrorMessage("Value $value does not match the corresponding filter set");

						return $this->GetValid();
					}
				}
			}
		}

		foreach ($this->GetValidators() as $oValidator) {
			foreach ($this->currentValue as $value) {
				if (!preg_match($oValidator->GetRegExp(true), $value)) {
					$this->SetValid(false);
					$this->AddErrorMessage($oValidator->GetErrorMessage());
				}
			}
		}

		return $this->GetValid();

	}

	/**
	 * Resets current value if not among allowed ones.
	 * By default, reset is done ONLY when the field is not read-only.
	 *
	 * @param boolean $bAlways Set to true to verify even when the field is read-only.
	 *
	 * @throws \CoreException
	 */
	public function VerifyCurrentValue(bool $bAlways = false)
	{
		if (!$this->GetReadOnly() || $bAlways) {
			if (count($this->currentValue) > 0) {
				foreach ($this->currentValue as $sCode => $value) {
					$oValuesScope = $this->GetSearch()->DeepClone();
					$oBinaryExp = new BinaryExpression(new FieldExpression('id', $oValuesScope->GetClassAlias()), '=',
						new ScalarExpression($value));
					$oValuesScope->AddConditionExpression($oBinaryExp);
					$oValuesSet = new DBObjectSet($oValuesScope);
				}
			} else {
				$this->currentValue = [];
			}
		}
	}
}
