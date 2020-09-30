<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Input\Select;


use Combodo\iTop\Application\UI\UIBlock;

class SelectOption extends UIBlock
{
	public const HTML_TEMPLATE_REL_PATH = 'components/input/select/selectoption';

	/** @var string */
	protected $sValue;
	/** @var string */
	protected $sLabel;
	/** @var bool */
	protected $bSelected;

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
	public function SetValue(string $sValue): SelectOption
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
	public function SetLabel(string $sLabel): SelectOption
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
	public function SetSelected(bool $bSelected): SelectOption
	{
		$this->bSelected = $bSelected;
		return $this;
	}

}