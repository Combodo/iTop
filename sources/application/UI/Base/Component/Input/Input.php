<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Input
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
class Input extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-input';
	public const HTML_TEMPLATE_REL_PATH = 'base/components/input/layout';

	public const INPUT_HIDDEN = 'hidden';

	/** @var string */
	protected $sType;
	/** @var string */
	protected $sName;
	/** @var string */
	protected $sValue;

	/**
	 * @return string
	 */
	public function GetType(): string
	{
		return $this->sType;
	}

	/**
	 * @param string $sType
	 *
	 * @return Input
	 */
	public function SetType(string $sType): Input
	{
		$this->sType = $sType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName
	 *
	 * @return Input
	 */
	public function SetName(string $sName): Input
	{
		$this->sName = $sName;
		return $this;
	}

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
	 * @return Input
	 */
	public function SetValue(string $sValue): Input
	{
		$this->sValue = $sValue;
		return $this;
	}


}