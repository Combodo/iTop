<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Component\Form;


use Combodo\iTop\Application\UI\Layout\UIContentBlock;

/**
 * Class Form
 *
 * @package Combodo\iTop\Application\UI\Component\Form
 */
class Form extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-form';
	public const HTML_TEMPLATE_REL_PATH = 'components/form/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/form/layout';

	/** @var string */
	protected $sOnSubmitJsCode;

	public function __construct(string $sName = null)
	{
		parent::__construct($sName);
		$this->sOnSubmitJsCode = null;
	}

	public function SetOnSubmitJsCode(string $sJsCode): void
	{
		$this->sOnSubmitJsCode = $sJsCode;
	}

	/**
	 * @return string
	 */
	public function GetOnSubmitJsCode(): ?string
	{
		return $this->sOnSubmitJsCode;
	}


}