<?php

// Copyright (C) 2010-2016 Combodo SARL
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

use \Closure;
use \Combodo\iTop\Form\Validator\Validator;
use \Combodo\iTop\Form\Validator\MandatoryValidator;

/**
 * Description of Field
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
abstract class Field
{
	const DEFAULT_LABEL = '';
	const DEFAULT_HIDDEN = false;
	const DEFAULT_READ_ONLY = false;
	const DEFAULT_MANDATORY = false;
	const DEFAULT_VALID = true;

	protected $sId;
	protected $sGlobalId;
	protected $sFormPath;
	protected $sLabel;
	protected $bHidden;
	protected $bReadOnly;
	protected $bMandatory;
	protected $aValidators;
	protected $bValid;
	protected $aErrorMessages;
	protected $currentValue;
	protected $onFinalizeCallback;

	/**
	 * Default constructor
	 *
	 * @param string $sId
	 * @param Closure $onFinalizeCallback (Used in the $oForm->AddField($sId, ..., function() use ($oManager, $oForm, '...') { ... } ); )
	 */
	public function __construct($sId, Closure $onFinalizeCallback = null)
	{
		$this->sId = $sId;
		// No space in such an id, that could be used as a DOM node id
		$this->sGlobalId = 'field_' . str_replace(' ', '_', $sId) . '_' . uniqid();
		$this->sLabel = static::DEFAULT_LABEL;
		$this->bHidden = static::DEFAULT_HIDDEN;
		$this->bReadOnly = static::DEFAULT_READ_ONLY;
		$this->bMandatory = static::DEFAULT_MANDATORY;
		$this->aValidators = array();
		$this->bValid = static::DEFAULT_VALID;
		$this->aErrorMessages = array();
		$this->onFinalizeCallback = $onFinalizeCallback;
	}

	/**
	 * Returns the field id within its container form
	 *
	 * @return string
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 * Returns a unique field id within the top level form
	 *
	 * @return string
	 */
	public function GetGlobalId()
	{
		return $this->sGlobalId;
	}

	/**
	 * Returns the id of the container form
	 *
	 * @return string
	 */
	public function GetFormPath()
	{
		return $this->sFormPath;
	}

	/**
	 *
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetHidden()
	{
		return $this->bHidden;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetReadOnly()
	{
		return $this->bReadOnly;
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetMandatory()
	{
		return $this->bMandatory;
	}

	/**
	 *
	 * @return array
	 */
	public function GetValidators()
	{
		return $this->aValidators;
	}

	/**
	 * Returns the current validation state of the field (true|false).
	 * It DOESN'T make the validation, see Validate() instead.
	 *
	 * @return boolean
	 */
	public function GetValid()
	{
		return $this->bValid;
	}

	/**
	 *
	 * @return array
	 */
	public function GetErrorMessages()
	{
		return $this->aErrorMessages;
	}

	/**
	 *
	 * @return array
	 */
	public function GetCurrentValue()
	{
		return $this->currentValue;
	}


	public function GetDisplayValue()
	{
		return $this->currentValue;
	}
	
	/**
	 * Sets the field formpath
	 * Usually Called by the form when adding the field
	 *
	 * @param string $sFormPath
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetFormPath($sFormPath)
	{
		$this->sFormPath = $sFormPath;
		return $this;
	}

	/**
	 *
	 * @param type $sLabel
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetLabel($sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 *
	 * @param boolean $bHidden
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetHidden($bHidden)
	{
		$this->bHidden = $bHidden;
		return $this;
	}

	/**
	 *
	 * @param boolean $bReadOnly
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetReadOnly($bReadOnly)
	{
		$this->bReadOnly = $bReadOnly;
		return $this;
	}

	/**
	 * Sets if the field is mandatory or not.
	 * Setting the value will automatically add/remove a MandatoryValidator to the Field
	 *
	 * @param boolean $bMandatory
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetMandatory($bMandatory)
	{
		// Before changing the property, we check if it was already mandatory. If not, we had the mandatory validator
		if ($bMandatory && !$this->bMandatory)
		{
			$this->AddValidator(new MandatoryValidator());
		}

		if (!$bMandatory)
		{
			foreach ($this->aValidators as $iKey => $oValue)
			{
				if ($oValue::Getname() === MandatoryValidator::GetName())
				{
					unset($this->aValidators[$iKey]);
				}
			}
		}

		$this->bMandatory = $bMandatory;
		return $this;
	}

	/**
	 *
	 * @param array $aValidators
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetValidators($aValidators)
	{
		$this->aValidators = $aValidators;
		return $this;
	}

	/**
	 * Note : Function is protected as bValid should not be set from outside
	 *
	 * @param boolean $bValid
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	protected function SetValid($bValid)
	{
		$this->bValid = $bValid;
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @param array $aErrorMessages
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	protected function SetErrorMessages($aErrorMessages)
	{
		$this->aErrorMessages = $aErrorMessages;
		return $this;
	}

	/**
	 *
	 * @param mixed $currentValue
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetCurrentValue($currentValue)
	{
		$this->currentValue = $currentValue;
		return $this;
	}

	/**
	 *
	 * @param Closure $onFinalizeCallback
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetOnFinalizeCallback(Closure $onFinalizeCallback)
	{
		$this->onFinalizeCallback = $onFinalizeCallback;
		return $this;
	}

	/**
	 *
	 * @param Validator $oValidator
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function AddValidator(Validator $oValidator)
	{
		$this->aValidators[] = $oValidator;
		return $this;
	}

	/**
	 *
	 * @param Validator $oValidator
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function RemoveValidator(Validator $oValidator)
	{
		foreach ($this->aValidators as $iKey => $oValue)
		{
			if ($oValue === $oValidator)
			{
				unset($this->aValidators[$iKey]);
			}
		}
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be add from outside
	 *
	 * @param string $sErrorMessage
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	protected function AddErrorMessage($sErrorMessage)
	{
		$this->aErrorMessages[] = $sErrorMessage;
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	protected function EmptyErrorMessages()
	{
		$this->aErrorMessages = array();
		return $this;
	}

	/**
	 * Returns if the field is editable. Meaning that it is not editable nor hidden.
	 * 
	 * @return boolean
	 */
	public function IsEditable()
	{
		return (!$this->bReadOnly && !$this->bHidden);
	}

	public function OnCancel()
	{
		// Overload when needed
	}

	public function OnFinalize()
	{
		if ($this->onFinalizeCallback !== null)
		{
			// Note : We MUST have a temp variable to call the Closure. otherwise it won't work when the Closure is a class member
			$callback = $this->onFinalizeCallback;
			$callback($this);
		}
	}

	/**
	 * Checks the validators to see if the field's current value is valid.
	 * Then sets $bValid and $aErrorMessages.
	 *
	 * @return boolean
	 */
	public function Validate()
	{
		$this->SetValid(true);
		$this->EmptyErrorMessages();

		$bEmpty = ( ($this->GetCurrentValue() === null) || ($this->GetCurrentValue() === '') );

		if (!$bEmpty || $this->GetMandatory())
		{
			foreach ($this->GetValidators() as $oValidator)
			{
				if (!preg_match($oValidator->GetRegExp(true), $this->GetCurrentValue()))
				{
					$this->SetValid(false);
					$this->AddErrorMessage($oValidator->GetErrorMessage());
				}
			}
		}

		return $this->GetValid();
	}
}
