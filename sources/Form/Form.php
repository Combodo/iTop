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

namespace Combodo\iTop\Form;

use Combodo\iTop\Form\Field\CaseLogField;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Form\Field\SubFormField;
use Dict;
use Exception;

/**
 * Description of Form
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class Form
{
	protected $sId;
	protected $sTransactionId;
	/** @var \Combodo\iTop\Form\Field\Field[] */
	protected $aFields;
	protected $aDependencies;
	protected $bValid;
	protected $aErrorMessages;
	protected $iEditableFieldCount;

	/**
	 * Default constructor
	 *
	 * @param string $sId
	 */
	public function __construct($sId)
	{
		$this->sId = $sId;
		$this->sTransactionId = null;
		$this->aFields = array();
		$this->aDependencies = array();
		$this->bValid = true;
		$this->aErrorMessages = array();
		$this->iEditableFieldCount = null;
	}

	/**
	 *
	 * @return string
	 */
	public function GetId()
	{
		return $this->sId;
	}

	/**
	 *
	 * @param string $sId
	 * @return \Combodo\iTop\Form\Form
	 */
	public function SetId($sId)
	{
		// Setting id for the form itself
		$this->sId = $sId;
		// Then setting formpath to its fields
		foreach ($this->aFields as $oField)
		{
			$oField->SetFormPath($sId);
		}

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetTransactionId()
	{
		return $this->sTransactionId;
	}

	/**
	 *
	 * @param string $sTransactionId
	 * @return \Combodo\iTop\Form\Form
	 */
	public function SetTransactionId($sTransactionId)
	{
		$this->sTransactionId = $sTransactionId;
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function GetFields()
	{
		return $this->aFields;
	}

	/**
	 *
	 * @return array
	 */
	public function GetDependencies()
	{
		return $this->aDependencies;
	}

	/**
	 * Returns a hash array of "Field id" => "Field value"
	 *
	 * @return array
	 */
	public function GetCurrentValues()
	{
		$aValues = array();
		foreach ($this->aFields as $sId => $oField)
		{
			$aValues[$sId] = $oField->GetCurrentValue();
		}
		return $aValues;
	}

    /**
     *
     * @param array $aValues Must be a hash array of "Field id" => "Field value"
     *
     * @return \Combodo\iTop\Form\Form
     *
     * @throws \Exception
     */
	public function SetCurrentValues($aValues)
	{
		foreach ($aValues as $sId => $value)
		{
			$oField = $this->GetField($sId);
			$oField->SetCurrentValue($value);
		}

		return $this;
	}

	/**
	 * Returns the current validation state of the form (true|false).
	 * It DOESN'T make the validation, see Validate() instead.
	 *
	 * @return boolean
	 */
	public function GetValid()
	{
		return $this->bValid;
	}

	/**
	 * Note : Function is protected as bValid should not be set from outside
	 *
	 * @param boolean $bValid
	 * @return \Combodo\iTop\Form\Form
	 */
	protected function SetValid($bValid)
	{
		$this->bValid = $bValid;
		return $this;
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
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @param array $aErrorMessages
	 * @param string $sFieldId
	 * @return \Combodo\iTop\Form\Form
	 */
	protected function SetErrorMessages($aErrorMessages, $sFieldId = null)
	{
		if ($sFieldId === null)
		{
			$this->aErrorMessages = $aErrorMessages;
		}
		else
		{
			$this->aErrorMessages[$sFieldId] = $aErrorMessages;
		}
		return $this;
	}

	/**
	 * If $sFieldId is not set, the $sErrorMessage will be added to the general form messages
	 *
	 * Note : Function is protected as aErrorMessages should not be add from outside
	 *
	 * @param string $sErrorMessage
	 * @param string $sFieldId
	 * @return \Combodo\iTop\Form\Form
	 */
	protected function AddErrorMessage($sErrorMessage, $sFieldId = '_main')
	{
		if (!isset($this->aErrorMessages[$sFieldId]))
		{
			$this->aErrorMessages[$sFieldId] = array();
		}
		$this->aErrorMessages[$sFieldId][] = $sErrorMessage;
		return $this;
	}

	/**
	 * Note : Function is protected as aErrorMessages should not be set from outside
	 *
	 * @return \Combodo\iTop\Form\Form
	 */
	protected function EmptyErrorMessages()
	{
		$this->aErrorMessages = array();
		return $this;
	}

	/**
	 *
	 * @param string $sId
	 * @return \Combodo\iTop\Form\Field\Field
	 * @throws Exception
	 */
	public function GetField($sId)
	{
		if (!array_key_exists($sId, $this->aFields))
		{
			throw new Exception('Field with ID "' . $sId . '" was not found in the Form of ID "' . $this->sId . '".');
		}
		return $this->aFields[$sId];
	}

	/**
	 *
	 * @param string $sId
	 * @return boolean
	 */
	public function HasField($sId)
	{
		return array_key_exists($sId, $this->aFields);
	}

	/**
	 *
	 * @param \Combodo\iTop\Form\Field\Field $oField
	 * @param array $aDependsOnIds
	 * @return \Combodo\iTop\Form\Form
	 */
	public function AddField(Field $oField, $aDependsOnIds = array())
	{
		$oField->SetFormPath($this->sId);
		$this->aFields[$oField->GetId()] = $oField;
		return $this;
	}

	/**
	 *
	 * @param string $sId
	 * @return \Combodo\iTop\Form\Form
	 */
	public function RemoveField($sId)
	{
		if (array_key_exists($sId, $this->aFields))
		{
			unset($this->aFields[$sId]);
		}
		return $this;
	}

	/**
	 * Returns a array (list) of the fields ordered by their dependencies.
	 *
	 * @return array
	 */
	public function GetOrderedFields()
	{
		// TODO : Do this so it flatten the array
		return $this->aFields;
	}

	/**
	 * Returns an array of field ids the $sFieldId depends on.
	 *
	 * @param string $sFieldId
	 * @return array
	 * @throws Exception
	 */
	public function GetFieldDependencies($sFieldId)
	{
		if (!array_key_exists($sFieldId, $this->aDependencies))
		{
			throw new Exception('Field with ID "' . $sFieldId . '" had no dependancies declared in the Form.');
		}
		return $this->aDependencies[$sFieldId];
	}

	/**
	 *
	 * @param string $sFieldId
	 * @param array $aDependsOnIds
	 * @return \Combodo\iTop\Form\Form
	 */
	public function AddFieldDependencies($sFieldId, array $aDependsOnIds)
	{
		foreach ($aDependsOnIds as $sDependsOnId)
		{
			$this->AddFieldDependency($sFieldId, $sDependsOnId);
		}
		return $this;
	}

	/**
	 *
	 * @param string $sFieldId
	 * @param string $sDependsOnId
	 * @return \Combodo\iTop\Form\Form
	 */
    public function AddFieldDependency($sFieldId, $sDependsOnId)
    {
        if (!array_key_exists($sFieldId, $this->aDependencies))
        {
            $this->aDependencies[$sFieldId] = array();
        }
        $this->aDependencies[$sFieldId][] = $sDependsOnId;
        return $this;
    }

    /**
     * Returns a hash array of the fields impacts on other fields. Key being the field that impacts the fields stored in the value as a regular array
     * (It kind of reversed the dependencies array)
     *
     * eg :
     * - 'service' => array('subservice', 'template')
     * - 'subservice' => array()
     * - ...
     *
     * @return array
     */
    public function GetFieldsImpacts()
    {
        $aRes = array();

        foreach ($this->aDependencies as $sImpactedFieldId => $aDependentFieldsIds)
        {
            foreach ($aDependentFieldsIds as $sDependentFieldId)
            {
                if (!array_key_exists($sDependentFieldId, $aRes))
                {
                    $aRes[$sDependentFieldId] = array();
                }
                $aRes[$sDependentFieldId][] = $sImpactedFieldId;
            }
        }

        return $aRes;
    }

    /**
     * Returns the number of editable fields in this form.
     *
     * @param bool $bForce
     *
     * @return integer
     */
	public function GetEditableFieldCount($bForce = false)
	{
		// Count is usally done by the Finalize function but it can be done there if Finalize hasn't been called yet or if we choose to force it.
		if (($this->iEditableFieldCount === null) || ($bForce === true))
		{
			$this->iEditableFieldCount = 0;
			foreach ($this->aFields as $oField)
			{
				if ($oField->IsEditable())
				{
					$this->iEditableFieldCount++;
				}
			}
		}

		return $this->iEditableFieldCount;
	}

	/**
	 * Returns true if the form has at least one editable field
	 *
	 * @return boolean
	 */
	public function HasEditableFields()
	{
		return ($this->GetEditableFieldCount() > 0);
	}

	/**
	 * Returns true if the form has at least one editable field
	 *
	 * @return boolean
	 */
	public function HasVisibleFields()
	{
		$bRet = false;
		foreach ($this->aFields as $oField)
		{
			if (!$oField->GetHidden())
			{
				$bRet = true;
				break;
			}
		}
		return $bRet;
	}

	/**
	 * Forces the form to a read only state by setting read only to true on all its fields
	 * 
	 * @return \Combodo\iTop\Form\Form
	 */
	public function MakeReadOnly()
	{
		foreach ($this->GetFields() as $oField)
		{
			$oField->SetReadOnly(true);
		}

		return $this;
	}

	/**
	 * @param $sFormPath
	 * @return Form|null
	 */
	public function FindSubForm($sFormPath)
	{
		$ret = null;
		if ($sFormPath == $this->sId)
		{
			$ret = $this;
		}
		else
		{
			foreach ($this->aFields as $oField)
			{
				if ($oField instanceof SubFormField)
				{
					$ret = $oField->FindSubForm($sFormPath);
					if ($ret !== null) break;
				}
			}
		}
		return $ret;
	}

    /**
     * Resets CaseLog fields value in the form and its sub-forms
     *
     * @return Form
     */
	public function ResetCaseLogFields()
    {
        foreach($this->GetFields() as $oField)
        {
            if($oField instanceof CaseLogField)
            {
                $oField->SetCurrentValue(null);
            }
            elseif($oField instanceof SubFormField)
            {
                $oField->GetForm()->ResetCaseLogFields();
            }
        }

        return $this;
    }

    /**
     * Finalizes each field, following the dependencies so that a field can compute its value or other properties,
     * depending on other fields
     *
     * @throws \Exception
     */
    public function Finalize()
    {
		$aFieldList = array(); // Fields ordered by dependence
		// Clone the dependency data : $aDependencies will be truncated as the fields are added to the list
		$aDependencies = $this->aDependencies;
		$bMadeProgress = true; // Safety net in case of circular references

		foreach ($aDependencies as $sImpactedBy => $aSomeFields)
		{
			foreach ($aSomeFields as $i => $sSomeId)
			{
				if (!array_key_exists($sSomeId, $this->aFields))
				{
					throw new Exception('Unmet dependency : Field ' . $sImpactedBy . ' expecting field ' . $sSomeId . ' which is not in the Form');
				}
			}
		}

		while ($bMadeProgress && count($aFieldList) < count($this->aFields))
		{
			$bMadeProgress = false;
			foreach ($this->aFields as $sId => $oField)
			{
				if (array_key_exists($sId, $aFieldList))
					continue;
				if (isset($aDependencies[$sId]) && count($aDependencies[$sId]) > 0) continue;
				// Add the field at the end of the list
				$aFieldList[$sId] = $oField;
				$bMadeProgress = true;

				// Track that this dependency has been solved
				foreach ($aDependencies as $sImpactedBy => $aSomeFields)
				{
					foreach ($aSomeFields as $i => $sSomeId)
					{
						if ($sSomeId == $sId)
						{
							unset($aDependencies[$sImpactedBy][$i]);
						}
					}
				}
			}
		}
		if (!$bMadeProgress)
		{
			throw new Exception('Unmet dependencies (might be a circular reference) : ' . implode(', ', array_keys($aDependencies)));
		}
		foreach ($aFieldList as $sId => $oField)
		{
			$oField->OnFinalize();
			if ($oField->IsEditable())
			{
				$this->iEditableFieldCount++;
			}
		}
    }

	/**
	 * Validate the form and return if it's valid or not
	 * 
	 * @return boolean
	 */
    public function Validate()
    {
        $this->SetValid(true);
        $this->EmptyErrorMessages();

	    foreach ($this->aFields as $oField) {
		    if (!$oField->Validate()) {
			    $this->SetValid(false);
			    foreach ($oField->GetErrorMessages() as $sErrorMessage) {
				    $this->AddErrorMessage(Dict::S($sErrorMessage), $oField->Getid());
			    }
		    }
	    }

        return $this->GetValid();
    }

}
