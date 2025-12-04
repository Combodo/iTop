<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboForm;

use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Symfony\Component\Form\FormView;

class TurboForm extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-form';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/turbo-form/layout';

	/** @var string|null */
	protected ?string $sOnSubmitJsCode;
	/** @var string|null */
	protected ?string $sAction;
	private FormView $oFormView;

	public function __construct(FormView $oFormView, string $sId = null)
	{
		parent::__construct($sId);
		$this->oFormView = $oFormView;
		$this->sOnSubmitJsCode = null;
		$this->sAction = null;
	}

	public function SetOnSubmitJsCode(string $sJsCode)
	{
		$this->sOnSubmitJsCode = $sJsCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetOnSubmitJsCode(): ?string
	{
		return $this->sOnSubmitJsCode;
	}

	/**
	 * @return string|null
	 */
	public function GetAction(): ?string
	{
		return $this->sAction;
	}

	/**
	 * @param string $sAction
	 *
	 * @return TurboForm
	 */
	public function SetAction(string $sAction): TurboForm
	{
		$this->sAction = $sAction;
		return $this;
	}

	public function GetFormView(): FormView
	{
		return $this->oFormView;
	}
}
