<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


/**
 * Class Input
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
class Input extends AbstractInput
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-input';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/layout';

	public const INPUT_HIDDEN = 'hidden';

	protected $bIsChecked = false;

	/** @var string */
	protected $sType;

	public function GetType(): string
	{
		return $this->sType;
	}

	/**
	 * @param string $sType
	 *
	 * @return $this
	 */
	public function SetType(string $sType)
	{
		$this->sType = $sType;

		return $this;
	}

	/**
	 * @param $bChecked
	 *
	 * @return $this
	 */
	public function SetIsChecked($bIsChecked)
	{
		$this->bIsChecked = $bIsChecked;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsChecked()
	{
		return $this->bIsChecked;
	}
}