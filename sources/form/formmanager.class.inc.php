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
    /** @var \Combodo\iTop\Form\Form $oForm */
	protected $oForm;
	/** @var \Combodo\iTop\Renderer\FormRenderer $oRenderer */
	protected $oRenderer;

	/**
	 * Creates an instance of \Combodo\iTop\Form\FormManager from JSON data that must contain at least :
	 * - formrenderer_class : The class of the FormRenderer to use in the FormManager
	 * - formrenderer_endpoint : The endpoint of the renderer
	 *
	 * @param string $sJson
	 * @return \Combodo\iTop\Form\FormManager
	 */
	static function FromJSON($sJson)
	{
		// Overload in child class when needed
		if (is_array($sJson))
		{
			$aJson = $sJson;
		}
		else
		{
			$aJson = json_decode($sJson, true);
		}

		$oFormManager = new static();

		$sFormRendererClass = $aJson['formrenderer_class'];
		$oFormRenderer = new $sFormRendererClass();
		$oFormRenderer->SetEndpoint($aJson['formrenderer_endpoint']);
		$oFormManager->SetRenderer($oFormRenderer);

		$oFormManager->SetForm(new Form($aJson['id']));
		$oFormManager->GetForm()->SetTransactionId($aJson['transaction_id']);
		$oFormManager->GetRenderer()->SetForm($oFormManager->GetForm());

		return $oFormManager;
	}

	public function __construct()
	{
		// Overload in child class when needed
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
	 * @return \Combodo\iTop\Form\FormManager
	 */
	public function SetForm(Form $oForm)
	{
		$this->oForm = $oForm;
		return $this;
	}

	/**
	 *
	 * @return \Combodo\iTop\Renderer\FormRenderer
	 */
	public function GetRenderer()
	{
		return $this->oRenderer;
	}

	/**
	 *
	 * @param \Combodo\iTop\Renderer\FormRenderer $oRenderer
	 * @return \Combodo\iTop\Form\FormManager
	 */
	public function SetRenderer(FormRenderer $oRenderer)
	{
		$this->oRenderer = $oRenderer;
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetClass()
	{
		return get_class($this);
	}

	/**
	 * Creates a JSON string from the current object including :
	 * - id : Id of the current Form
	 * - formmanager_class
	 * - formrenderer_class
	 * - formrenderer_endpoint
	 *
	 * @return string
	 */
	public function ToJSON()
	{
		// Overload in child class when needed
		return array(
			'id' => $this->oForm->GetId(),
			'transaction_id' => $this->oForm->GetTransactionId(),
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
