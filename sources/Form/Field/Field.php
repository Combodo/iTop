<?php

/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Form\Field;

use Closure;
use Combodo\iTop\Form\Validator\Validator;
use Combodo\iTop\Form\Validator\MandatoryValidator;

/**
 * Description of Field
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since iTop 2.3.0
 */
abstract class Field
{
    const ENUM_DISPLAY_MODE_COSY = 'cosy';          // Label above value
    const ENUM_DISPLAY_MODE_COMPACT = 'compact';    // Label and value side by side
    const ENUM_DISPLAY_MODE_DENSE = 'dense';        // Label and value side by side, closely

	const DEFAULT_LABEL = '';
	const DEFAULT_METADATA = array();
	const DEFAULT_HIDDEN = false;
	const DEFAULT_READ_ONLY = false;
	const DEFAULT_MANDATORY = false;
    const DEFAULT_DISPLAY_MODE = self::ENUM_DISPLAY_MODE_COSY;
	const DEFAULT_VALID = true;

	protected $sId;
	protected $sGlobalId;
	protected $sFormPath;
	protected $sLabel;
	protected $aMetadata;
	protected $bHidden;
	protected $bReadOnly;
	protected $bMandatory;
	protected $sDisplayMode;
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
		$this->aMetadata = static::DEFAULT_METADATA;
		$this->bHidden = static::DEFAULT_HIDDEN;
		$this->bReadOnly = static::DEFAULT_READ_ONLY;
		$this->bMandatory = static::DEFAULT_MANDATORY;
		$this->sDisplayMode = static::DEFAULT_DISPLAY_MODE;
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
	 * Return an array of $sName => $sValue metadata.
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public function GetMetadata()
	{
		return $this->aMetadata;
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
	 * Note: This not implemented yet! Just a pre-conception for CaseLogField
	 *
	 * @todo Implement
	 * @return boolean
	 */
	public function GetMustChange()
	{
		// TODO
		return false;
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
     * @return string
     */
	public function GetDisplayMode()
    {
        return $this->sDisplayMode;
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
	 * @return mixed
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
	 * @return $this
	 */
	public function SetFormPath($sFormPath)
	{
		$this->sFormPath = $sFormPath;
		return $this;
	}

	/**
	 *
	 * @param string $sLabel
	 * @return $this
	 */
	public function SetLabel($sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * Must be an array of $sName => $sValue metadata.
	 *
	 * @param array $aMetadata
	 *
	 * @return $this
	 * @since 2.7.0
	 */
	public function SetMetadata($aMetadata)
	{
		$this->aMetadata = $aMetadata;
		return $this;
	}

	/**
	 *
	 * @param boolean $bHidden
	 * @return $this
	 */
	public function SetHidden($bHidden)
	{
		$this->bHidden = $bHidden;
		return $this;
	}

	/**
	 *
	 * @param boolean $bReadOnly
	 * @return $this
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
	 * @return $this
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
	 * Sets if the field is must change or not.
	 * Note: This not implemented yet! Just a pre-conception for CaseLogField
	 *
	 * @todo Implement
	 * @param boolean $bMustChange
	 * @return $this
	 */
	public function SetMustChange($bMustChange)
	{
		// TODO.
		return $this;
	}

    /**
     *
     * @param string $sDisplayMode
     * @return $this
     */
	public function SetDisplayMode($sDisplayMode)
    {
        $this->sDisplayMode = $sDisplayMode;
        return $this;
    }

	/**
	 *
	 * @param array $aValidators
	 * @return $this
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
	 * @return $this
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
	 * @return $this
	 */
	protected function SetErrorMessages($aErrorMessages)
	{
		$this->aErrorMessages = $aErrorMessages;
		return $this;
	}

	/**
	 *
	 * @param mixed $currentValue
	 * @return $this
	 */
	public function SetCurrentValue($currentValue)
	{
		$this->currentValue = $currentValue;
		return $this;
	}

	/**
	 *
	 * @param Closure $onFinalizeCallback
	 * @return $this
	 */
	public function SetOnFinalizeCallback(Closure $onFinalizeCallback)
	{
		$this->onFinalizeCallback = $onFinalizeCallback;
		return $this;
	}

	/**
	 * Add a metadata to the field. If the metadata $sName already exists, it will be overwritten.
	 *
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this;
	 * @since 2.7.0
	 */
	public function AddMetadata($sName, $sValue)
	{
		$this->aMetadata[$sName] = $sValue;
		return $this;
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Validator\Validator $oValidator
	 * @return $this
	 */
	public function AddValidator(Validator $oValidator)
	{
		$this->aValidators[] = $oValidator;
		return $this;
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Validator\Validator $oValidator
	 * @return $this
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
	 * @return $this
	 */
	protected function AddErrorMessage($sErrorMessage)
	{
		$this->aErrorMessages[] = $sErrorMessage;
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @return $this
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
