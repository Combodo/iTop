<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;

use utils;

/**
 * Trait tInputLabel Label for input
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
trait tInputLabel
{
	/** @var bool If true the label will be positioned before the input */
	protected $bIsLabelBefore = true;
	/** @var string|null Label to display with the input (null for no label) */
	protected $sLabel = null;
	/**
	 * @var string|null $sDescription for tooltip
	 * @since 3.0.1
	 */
	protected $sDescription = null;

	/**
	 * @return bool
	 */
	public function IsLabelBefore(): bool
	{
		return $this->bIsLabelBefore;
	}

	/**
	 * @param bool $bIsLabelBefore {@see tInputLabel::$bIsLabelBefore}
	 *
	 * @return $this
	 */
	public function SetIsLabelBefore(bool $bIsLabelBefore)
	{
		$this->bIsLabelBefore = $bIsLabelBefore;
		if ($this->bIsLabelBefore) {
			$this->AddCSSClass('ibo-input--label-left');
			$this->RemoveCSSClass('ibo-input--label-right');
		} else {
			$this->AddCSSClass('ibo-input--label-right');
			$this->RemoveCSSClass('ibo-input--label-left');
		}

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
	 * @param string|null $sLabel {@see tInputLabel::$sLabel}
	 *
	 * @return $this
	 */
	public function SetLabel(?string $sLabel)
	{
		$this->sLabel = $sLabel;
		if (!is_null($sLabel)) {
			if ($this->bIsLabelBefore) {
				$this->AddCSSClass('ibo-input--label-left');
				$this->RemoveCSSClass('ibo-input--label-right');
			} else {
				$this->AddCSSClass('ibo-input--label-right');
				$this->RemoveCSSClass('ibo-input--label-left');
			}
		} else {
			$this->RemoveCSSClass('ibo-input--label-right');
			$this->RemoveCSSClass('ibo-input--label-left');
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function HasLabel(): bool
	{
		return utils::StrLen($this->sLabel) > 0;
	}

	/**
	 * @return string|null
	 * @since 3.0.1
	 */
	public function GetDescription(): ?string
	{
		return $this->sDescription;
	}

	/**
	 * @param string|null $sDescription
	 * @return $this
	 * @since 3.0.1
	 */
	public function SetDescription(?string $sDescription)
	{
		$this->sDescription = $sDescription;

		return $this;
	}

	/**
	 * @return bool
	 * @since 3.0.1
	 */
	public function HasDescription(): bool
	{
		return utils::StrLen($this->sDescription) > 0;
	}
}