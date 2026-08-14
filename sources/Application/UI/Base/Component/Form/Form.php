<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Form;

use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class Form
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Form
 */
class Form extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-form';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/form/layout';

	/** @var string */
	protected $sOnSubmitJsCode;
	/** @var string */
	protected $sAction;
	/** @var string */
	protected $sEncType = "application/x-www-form-urlencoded";

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
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
	 * @return string
	 */
	public function GetAction(): ?string
	{
		return $this->sAction;
	}

	/**
	 * @param string $sAction
	 *
	 * @return Form
	 */
	public function SetAction(string $sAction)
	{
		$this->sAction = $sAction;
		return $this;
	}

	/**
	 * Override default enctype (default : "multipart/form-data")
		* @param string $sEncType
		* @return $this
	 *  @since 3.3.0
	 */
	public function SetEncType(string $sEncType)
	{
		$this->sEncType = $sEncType;
		return $this;
	}

	/**
* @return string
	 *  @since 3.3.0
	 */
	public function GetEncType(): string
	{
		return $this->sEncType;
	}

}
