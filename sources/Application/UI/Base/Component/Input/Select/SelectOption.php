<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\UIBlock;

class SelectOption extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-select-option';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/select/selectoption';

	/** @var string */
	protected $sValue;
	/** @var string */
	protected $sLabel;
	/** @var bool */
	protected $bSelected;
	/** @var bool */
	protected $bDisabled;

	/**
	 * @return string
	 */
	public function GetValue(): string
	{
		return $this->sValue;
	}

	/**
	 * @param string $sValue
	 *
	 * @return SelectOption
	 */
	public function SetValue(string $sValue)
	{
		$this->sValue = $sValue;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 *
	 * @return SelectOption
	 */
	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsSelected(): bool
	{
		return $this->bSelected;
	}

	/**
	 * @param bool $bSelected
	 *
	 * @return SelectOption
	 */
	public function SetSelected(bool $bSelected)
	{
		$this->bSelected = $bSelected;
		return $this;
	}	
	
	/**
	 * @return bool
	 */
	public function IsDisabled(): bool
	{
		return $this->bDisabled;
	}

	/**
	 * @param bool $bDisabled
	 *
	 * @return $this
	 */
	public function SetDisabled(bool $bDisabled)
	{
		$this->bDisabled = $bDisabled;
		return $this;
	}

}