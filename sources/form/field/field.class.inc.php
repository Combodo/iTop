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
    const DEFAULT_READ_ONLY = false;
    const DEFAULT_MANDATORY = false;
    const DEFAULT_VALID = true;

    protected $sId;
    protected $sGlobalId;
    protected $sFormPath;
    protected $sLabel;
    protected $bReadOnly;
    protected $bMandatory;
    protected $aValidators;
    protected $bValid;
    protected $aErrorMessages;
    protected $currentValue;
    protected $onFinalizeCallback;

    /**
     *
     * @param Closure $callback (Used in the $oForm->AddField($sId, ..., function() use ($oManager, $oForm, '...') { ... } ); )
     */
    public function __construct($sId, Closure $onFinalizeCallback = null)
    {
        $this->sId = $sId;
        $this->sGlobalId = 'field_'.$sId.uniqid();
        $this->sLabel = static::DEFAULT_LABEL;
        $this->bReadOnly = static::DEFAULT_READ_ONLY;
        $this->bMandatory = static::DEFAULT_MANDATORY;
        $this->aValidators = array();
        $this->bValid = static::DEFAULT_VALID;
        $this->aErrorMessages = array();
        $this->onFinalizeCallback = $onFinalizeCallback;
    }

	/**
     * Get the field id within its container form
     * @return string
     */
    public function GetId()
    {
        return $this->sId;
    }

    /**
     * Get a unique field id within the top level form
     * @return string
     */
    public function GetGlobalId()
    {
        return $this->sGlobalId;
    }

    /**
     * Get the id of the container form
     * @return string
     */
    public function GetFormPath()
    {
        return $this->sFormPath;
    }

    public function GetLabel()
    {
        return $this->sLabel;
    }

    public function GetReadOnly()
    {
        return $this->bReadOnly;
    }

    public function GetMandatory()
    {
        return $this->bMandatory;
    }

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

    public function GetErrorMessages()
    {
        return $this->aErrorMessages;
    }

    public function GetCurrentValue()
    {
        return $this->currentValue;
    }

    public function SetLabel($sLabel)
    {
        $this->sLabel = $sLabel;
        return $this;
    }

    public function SetReadOnly($bReadOnly)
    {
        $this->bReadOnly = $bReadOnly;
        return $this;
    }

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

    public function SetCurrentValue($currentValue)
    {
        $this->currentValue = $currentValue;
        return $this;
    }

    public function SetOnFinalizeCallback(Closure $onFinalizeCallback)
    {
        $this->onFinalizeCallback = $onFinalizeCallback;
        return $this;
    }

    /**
     * Called by the form when adding the field
     */
    public function SetFormPath($sFormPath)
    {
        $this->sFormPath = $sFormPath;
    }

    public function AddValidator(Validator $oValidator)
    {
        $this->aValidators[] = $oValidator;
        return $this;
    }

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
    abstract public function Validate();
}
