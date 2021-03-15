<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
	
	protected $bIsDisabled = false;
	protected $bIsReadonly = false;

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

	/**
	 * @return bool
	 */
	public function IsDisabled()
	{
		return $this->bIsDisabled;
	}

	/**
	 * @param bool $bIsDisabled
	 *
	 * @return $this
	 */
	public function SetIsDisabled(bool $bIsDisabled)
	{
		$this->bIsDisabled = $bIsDisabled;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsReadonly()
	{
		return $this->bIsReadonly;
	}

	/**
	 * @param bool $bIsReadonly
	 *
	 * @return $this
	 */
	public function SetIsReadonly(bool $bIsReadonly)
	{
		$this->bIsReadonly = $bIsReadonly;
		return $this;
	}
}