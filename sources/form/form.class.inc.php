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

namespace Combodo\iTop\Form;

use \Exception;
use \Dict;
use \Combodo\iTop\Form\Field\Field;

/**
 * Description of Form
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class Form
{
    protected $sId;
    protected $aFields;
    protected $aDependencies;
    protected $bValid;
    protected $aErrorMessages;

    public function __construct($sId)
    {
        $this->sId = $sId;
        $this->aFields = array();
        $this->aDependencies = array();
        $this->bValid = true;
        $this->aErrorMessages = array();
    }

    public function GetId()
    {
        return $this->sId;
    }

    public function GetFields()
    {
        return $this->aFields;
    }

    public function GetDependencies()
    {
        return $this->aDependencies;
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

    public function GetField($sId)
    {
        if (!array_key_exists($sId, $this->aFields))
        {
            throw new Exception('Field with ID "' . $sId . '" was not found in the Form.');
        }
        return $this->aFields[$sId];
    }

    public function HasField($sId)
    {
        return array_key_exists($sId, $this->aFields);
    }

    public function AddField(Field $oField, $aDependsOnIds = array())
    {
        $this->aFields[$oField->GetId()] = $oField;
        return $this;
    }

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

    public function AddFieldDependencies($sFieldId, array $aDependsOnIds)
    {
        foreach ($aDependsOnIds as $sDependsOnId)
        {
            $this->AddFieldDependency($sFieldId, $sDependsOnId);
        }
        return $this;
    }

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

    public function Finalize()
    {
        //TODO : Call GetOrderedFields
        // Must call OnFinalize on each fields, regarding the dependencies order
        // On a SubFormField, will call its own Finalize
        foreach ($this->aFields as $sId => $oField)
        {
            $oField->OnFinalize();
        }
    }

    public function Validate()
    {
        $this->SetValid(true);
        $this->EmptyErrorMessages();
        
        foreach ($this->aFields as $oField)
        {
            if (!$oField->Validate())
            {
                $this->SetValid(false);
                foreach ($oField->GetErrorMessages() as $sErrorMessage)
                {
                    $this->AddErrorMessage(Dict::S($sErrorMessage), $oField->Getid());
                }
            }
        }
        
        return $this->GetValid();
    }

}
