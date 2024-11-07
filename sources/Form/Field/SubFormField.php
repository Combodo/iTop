<?php

// Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Form\Form;

/**
 * Description of SubFormField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class SubFormField extends Field
{
    protected $oForm;

    /**
     * @inheritDoc
     */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		$this->oForm = new Form('subform_' . $sId);
		parent::__construct($sId, $onFinalizeCallback);
	}

	/**
	 *
	 * @return \Combodo\iTop\Form\Form
	 */
	public function GetForm()
	{
		return $this->oForm;
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Form $oForm
     *
	 * @return \Combodo\iTop\Form\Field\SubFormField
	 */
	public function SetForm(Form $oForm)
	{
		$this->oForm = $oForm;
		return $this;
	}

    /**
     * Checks the validators to see if the field's current value is valid.
     * Then sets $bValid and $aErrorMessages.
     *
     * @inheritDoc
     */
	public function Validate()
	{
		return $this->oForm->Validate();
	}

	/**
	 *
	 * @return boolean
	 */
	public function GetValid()
	{
		return $this->oForm->GetValid();
	}

	/**
	 *
	 * @return array
	 */
	public function GetErrorMessages()
	{
		$aRet = array();
		foreach ($this->oForm->GetErrorMessages() as $sSubFieldId => $aSubFieldMessages)
		{
			$aRet[] = $sSubFieldId.': '.implode(', ', $aSubFieldMessages);
		}
		return $aRet;
	}

	/**
	 *
	 * @return array
	 */
	public function GetCurrentValue()
	{
		return $this->oForm->GetCurrentValues();
	}

    /**
     *
     * @param array $value
     *
     * @return \Combodo\iTop\Form\Field\SubFormField
     *
     * @throws \Exception
     */
	public function SetCurrentValue($value)
	{
		$this->oForm->SetCurrentValues($value);
		return $this;
	}

	/**
	 * Sets the mandatory flag on all the fields on the form
	 *
	 * @param boolean $bMandatory
	 */
	public function SetMandatory(bool $bMandatory)
    {
        foreach ($this->oForm->GetFields() as $oField) {
            $oField->SetMandatory($bMandatory);
        }

        return parent::SetMandatory($bMandatory);
    }

	/**
	 * Sets the read-only flag on all the fields on the form
	 *
	 * @param boolean $bReadOnly
	 */
	public function SetReadOnly(bool $bReadOnly)
	{
		foreach ($this->oForm->GetFields() as $oField)
		{
			$oField->SetReadOnly($bReadOnly);
			$oField->SetMandatory(false);
		}
        return parent::SetReadOnly($bReadOnly);
	}

	/**
	 * Sets the hidden flag on all the fields on the form
	 *
	 * @param boolean $bHidden
	 */
	public function SetHidden(bool $bHidden)
	{
		foreach ($this->oForm->GetFields() as $oField)
		{
			$oField->SetHidden($bHidden);
		}
        return parent::SetHidden($bHidden);
	}

	/**
	 * @param $sFormPath
	 * @return Form|null
	 */
	public function FindSubForm(string $sFormPath)
	{
		return $this->oForm->FindSubForm($sFormPath);
	}

    /**
     * @throws \Exception
     */
    public function OnFinalize()
	{
		$sFormId = 'subform_' . $this->sId;
		if ($this->sFormPath !== null)
		{
			$sFormId = $this->sFormPath . '-' . $sFormId;
		}
		$this->oForm->SetId($sFormId);

		// Calling first the field callback,
		// Then only calling finalize on the subform's fields
		parent::OnFinalize();
		$this->oForm->Finalize();
	}

}
