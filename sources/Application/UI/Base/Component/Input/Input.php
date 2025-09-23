<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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

	protected $sLabel = null;

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
	public function IsChecked(): bool
	{
		return $this->bIsChecked;
	}

	/**
	 * @return bool
	 */
	public function IsDisabled(): bool
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
	public function IsReadonly(): bool
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

	/**
	 * @return string|null
	 */
	public function GetLabel(): ?string
	{
		return $this->sLabel;
	}

	/**
	 * @param null $sLabel
	 *
	 * @return $this
	 */
	public function SetLabel($sLabel)
	{
		$this->sLabel = $sLabel;

		return $this;
	}

	public function HasLabel(): bool
	{
		return !is_null($this->sLabel);
	}
}