<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class Select extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-select';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/select/select';

	/** @var string */
	protected $sName;
	/** @var string */
	protected $sValue;
	/** @var bool */
	protected $bSubmitOnChange = false;


	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
	}

	public function AddOption(SelectOption $oOption)
	{
		$this->AddSubBlock($oOption);
	}

	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName
	 *
	 * @return $this
	 */
	public function SetName(string $sName)
	{
		$this->sName = $sName;

		return $this;
	}

	public function GetValue(): ?string
	{
		return $this->sValue;
	}

	/**
	 * @param string|null $sValue
	 *
	 * @return $this
	 */
	public function SetValue(?string $sValue)
	{
		$this->sValue = $sValue;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetSubmitOnChange(): bool
	{
		return $this->bSubmitOnChange;
	}

	/**
	 * @param bool $bSubmitOnChange
	 *
	 * @return $this
	 */
	public function SetSubmitOnChange(bool $bSubmitOnChange)
	{
		$this->bSubmitOnChange = $bSubmitOnChange;
		return $this;
	}

}