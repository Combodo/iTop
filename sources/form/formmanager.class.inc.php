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

use \Combodo\iTop\Form\Form;
use \Combodo\iTop\Renderer\FormRenderer;

/**
 * Description of formmanager
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
abstract class FormManager
{
    protected $oForm;
    protected $oRenderer;

    static function FromJSON($sJson)
    {
        // Overload in child class when needed
        $aJson = json_decode($sJson, true);

        $oFormManager = new static();

        $sFormRendererClass = $aJson['formrenderer_class'];
        $oFormRenderer = new $sFormRendererClass();
        $oFormRenderer->SetEndpoint($aJson['formrenderer_endpoint']);
        $oFormManager->SetRenderer($oFormRenderer);

        return $oFormManager;
    }

    public function __construct()
    {
        // Overload in child class when needed
    }

    public function GetForm()
    {
        return $this->oForm;
    }

    public function GetRenderer()
    {
        return $this->oRenderer;
    }

    public function SetRenderer(FormRenderer $oRenderer)
    {
        $this->oRenderer = $oRenderer;
        return $this;
    }

    public function GetClass()
    {
        return get_class($this);
    }

    public function ToJSON()
    {
        // Overload in child class when needed
        return array(
            'id' => $this->oForm->GetId(),
            'formmanager_class' => $this->GetClass(),
            'formrenderer_class' => get_class($this->GetRenderer()),
            'formrenderer_endpoint' => $this->GetRenderer()->GetEndpoint()
        );
    }

    abstract public function Build();

    abstract public function OnUpdate($aArgs = null);

    abstract public function OnSubmit($aArgs = null);

    abstract public function OnCancel($aArgs = null);
}
