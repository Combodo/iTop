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

namespace Combodo\iTop\Renderer;

use \Combodo\iTop\Form\Form;

/**
 * Description of FormRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
abstract class FormRenderer
{
    protected $oForm;
    protected $sEndpoint;
    protected $aSupportedFields;
    protected $sBaseLayout;
    protected $aOutputs;

    /**
     * Default constructor
     * 
     * @param \Combodo\iTop\Form\Form $oForm
     */
    public function __construct(Form $oForm = null)
    {
        if ($oForm !== null)
        {
            $this->oForm = $oForm;
        }
        $this->sBaseLayout = '';
        $this->InitOutputs();
    }

    public function GetForm()
    {
        return $this->oForm;
    }

    public function SetForm($oForm)
    {
        $this->oForm = $oForm;
        return $this;
    }

    public function GetEndpoint()
    {
        return $this->sEndpoint;
    }

    public function SetEndpoint($sEndpoint)
    {
        $this->sEndpoint = $sEndpoint;
        return $this;
    }

    public function GetBaseLayout()
    {
        return $this->sBaseLayout;
    }

    public function SetBaseLayout($sBaseLayout)
    {
        $this->sBaseLayout = $sBaseLayout;
        return $this;
    }

    public function GetFieldRendererClass($oField)
    {
        if (array_key_exists(get_class($oField), $this->aSupportedFields))
        {
            return $this->aSupportedFields[get_class($oField)];
        }
        else
        {
            // TODO : We might want to throw an exception.
            return null;
        }
    }

    /**
     * Returns the field identified by the id $sId in $this->oForm.
     *
     * @param string $sId
     * @return Combodo\iTop\Renderer\FieldRenderer
     */
    public function GetFieldRendererClassFromId($sId)
    {
        return $this->GetFieldRendererClass($this->oForm->GetField($sId));
    }

    /**
     *
     * @return array
     */
    public function GetOutputs()
    {
        return $this->aOutputs;
    }

    /**
     * Registers a Renderer class for the specified Field class.
     * 
     * If the Field class is not fully qualified, the default "Combodo\iTop\Form\Field" will be prepend.
     * If the Field class already had a registered Renderer, it is replaced.
     *
     * @param string $sFieldClass
     * @param string $sRendererClass
     */
    public function AddSupportedField($sFieldClass, $sRendererClass)
    {
        $sFieldClass = (strpos($sFieldClass, '\\') !== false) ? $sFieldClass : 'Combodo\\iTop\\Form\\Field\\' . $sFieldClass;

        $this->aSupportedFields[$sFieldClass] = $sRendererClass;

        return $this;
    }

    public function InitOutputs()
    {
        $this->aOutputs = array();
        return $this;
    }

    abstract public function Render();
}
