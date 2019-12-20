<?php

// Copyright (C) 2010-2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use Closure;

/**
 * Description of MultipleChoicesField
 *
 * Choices = Set of items that can be picked
 * Values = Items that have been picked
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.3.0
 */
abstract class MultipleChoicesField extends Field
{
	/** @var bool DEFAULT_MULTIPLE_VALUES_ENABLED */
	const DEFAULT_MULTIPLE_VALUES_ENABLED = false;

	/** @var bool $bMultipleValuesEnabled */
	protected $bMultipleValuesEnabled;
	/** @var array $aChoices */
	protected $aChoices;

	/**
	 * @inheritDoc
	 */
	public function __construct($sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->bMultipleValuesEnabled = static::DEFAULT_MULTIPLE_VALUES_ENABLED;
		$this->aChoices = array();
		$this->currentValue = array();
	}

	/**
	 * @inheritDoc
	 */
	public function GetCurrentValue()
	{
		$value = null;
		if (!empty($this->currentValue))
		{
			if ($this->bMultipleValuesEnabled)
			{
				$value = $this->currentValue;
			}
			else
			{
				reset($this->currentValue);
				$value = current($this->currentValue);
			}
		}

		return $value;
	}

	/**
	 * Sets the current value for the MultipleChoicesField.
	 *
	 * @param mixed $currentValue Can be either an array of values (in case of multiple values) or just a simple value
	 * @return $this
	 */
	public function SetCurrentValue($currentValue)
	{
		if (is_array($currentValue))
		{
			$this->currentValue = $currentValue;
		}
		elseif (is_null($currentValue))
		{
			$this->currentValue = array();
		}
		else
		{
			$this->currentValue = array($currentValue);
		}
		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetMultipleValuesEnabled()
	{
		return $this->bMultipleValuesEnabled;
	}

	/**
	 * @param bool $bMultipleValuesEnabled
	 *
	 * @return $this
	 */
	public function SetMultipleValuesEnabled($bMultipleValuesEnabled)
	{
		$this->bMultipleValuesEnabled = $bMultipleValuesEnabled;
		return $this;
	}

	/**
	 * @param array $aValues
	 *
	 * @return $this
	 */
	public function SetValues($aValues)
	{
		$this->currentValue = $aValues;
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function AddValue($value)
	{
		$this->currentValue = $value;
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function RemoveValue($value)
	{
		if (array_key_exists($value, $this->currentValue))
		{
			unset($this->currentValue[$value]);
		}
		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function IsAmongValues($value)
	{
		return in_array($value, $this->currentValue);
	}

	/**
	 * @return array
	 */
	public function GetChoices()
	{
		return $this->aChoices;
	}

	/**
	 * @param array $aChoices
	 *
	 * @return $this
	 */
	public function SetChoices($aChoices)
	{
		$this->aChoices = $aChoices;
		return $this;
	}

	/**
	 * @param string $sId
	 * @param null   $choice
	 *
	 * @return $this
	 */
	public function AddChoice($sId, $choice = null)
	{
		if ($choice === null)
		{
			$choice = $sId;
		}
		$this->aChoices[$sId] = $choice;
		return $this;
	}

	/**
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function RemoveChoice($sId)
	{
		if (in_array($sId, $this->aChoices))
		{
			unset($this->aChoices[$sId]);
		}
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function Validate()
	{
		$this->SetValid(true);
		$this->EmptyErrorMessages();

		foreach ($this->GetValidators() as $oValidator)
		{
			foreach ($this->currentValue as $value)
			{
				if (!preg_match($oValidator->GetRegExp(true), $value))
				{
					$this->SetValid(false);
					$this->AddErrorMessage($oValidator->GetErrorMessage());
				}
			}
		}

		return $this->GetValid();
	}

}
