<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Form;

use Combodo\iTop\Renderer\FormRenderer;
use CoreException;

/**
 * Description of formmanager
 *
 * @package Combodo\iTop\Form
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
	 * @param string|string[] $sJson
	 *
	 * @return $this
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
        // N°7455 - Ensure form renderer class extends FormRenderer
        if (false === is_a($sFormRendererClass, FormRenderer::class, true))
        {
            throw new CoreException('Form renderer class must extend '.FormRenderer::class);
        }

		/** @var \Combodo\iTop\Renderer\FormRenderer $oFormRenderer */
		$oFormRenderer = new $sFormRendererClass();
		$oFormRenderer->SetEndpoint($aJson['formrenderer_endpoint']);
		$oFormManager->SetRenderer($oFormRenderer);

		$oFormManager->SetForm(new Form($aJson['id']));
		$oFormManager->GetForm()->SetTransactionId($aJson['transaction_id']);
		$oFormManager->GetRenderer()->SetForm($oFormManager->GetForm());

		return $oFormManager;
	}

	/**
	 * FormManager constructor.
	 */
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
	 * @return $this
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
	 * @return $this
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
	 * @return array
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

	/**
	 * @param array|null $aArgs
	 *
	 * @return mixed
	 */
	abstract public function OnUpdate($aArgs = null);

	/**
	 * @param array|null $aArgs
	 *
	 * @return array
	 *
	 * @since 2.7.4 3.0.0 N°3430
	 */
	public function OnSubmit($aArgs = null)
	{
		$aData = array(
			'valid' => true,
			'messages' => array(
				'success' => array(),
				'warnings' => array(), // Not used as of today, just to show that the structure is ready for change like this.
				'error' => array(),
			),
		);

		$this->CheckTransaction($aData);

		return $aData;
	}

	/**
	 * @param array $aData
	 *
	 * @since 2.7.4 3.0.0 N°3430
	 */
	public function CheckTransaction(&$aData)
	{
		$isTransactionValid = \utils::IsTransactionValid($this->oForm->GetTransactionId(), false); //The transaction token is kept in order to preserve BC with ajax forms (the second call would fail if the token is deleted). (The GC will take care of cleaning the token for us later on)
		if (!$isTransactionValid) {
			$aData['messages']['error'] += [
				'_main' => [\Dict::S('UI:Error:InvalidToken')] //This message is generic, if you override this method you should use a more precise message. @see \Combodo\iTop\Portal\Form\ObjectFormManager::CheckTransaction
			];
			$aData['valid'] = false;
		}
	}

	/**
	 * @param array|null $aArgs
	 *
	 * @return mixed
	 */
	abstract public function OnCancel($aArgs = null);
}
