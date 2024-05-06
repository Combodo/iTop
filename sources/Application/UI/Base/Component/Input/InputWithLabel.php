<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * You might want to use a {@link \Combodo\iTop\Application\UI\Base\Component\Field\Field} component instead...
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
class InputWithLabel extends UIBlock
{
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/inputwithlabel';

	/** @var string */
	protected $sLabel;
	/** @var \Combodo\iTop\Application\UI\Base\UIBlock */
	protected $oInput;
	/** @var bool Label before input ? */
	protected $bBeforeInput;
	/**
	 * @var string|null $sDescription for tooltip
	 * @since 3.0.1
	 */
	protected $sDescription;

	/**
	 * @param string $sLabel
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oInput
	 * @param string|null $sId
	 */
	public function __construct(string $sLabel, UIBlock $oInput, ?string $sId)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->oInput = $oInput;
		$this->bBeforeInput = true;
	}

	/**
	 * @return UIBlock
	 */
	public function GetInput()
	{
		return $this->oInput;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oInput
	 *
	 * @return $this
	 */
	public function SetInput(UIBlock $oInput)
	{
		$this->oInput = $oInput;

		return $this;
	}

	/**
	 * @param bool $bBeforeInput
	 *
	 * @return $this
	 */
	public function SetBeforeInput(bool $bBeforeInput)
	{
		$this->bBeforeInput = $bBeforeInput;
		if ($bBeforeInput) {
			$this->oInput->AddCSSClass('ibo-input--label-left');
		} else {
			$this->oInput->AddCSSClass('ibo-input--label-right');
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsLabelBefore(): bool
	{
		return $this->bBeforeInput;
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
	 * @return InputWithLabel
	 */
	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
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
		return utils::IsNotNullOrEmptyString($this->sDescription);
	}

	public function GetSubBlocks(): array
	{
		return [$this->oInput->GetId() => $this->oInput];
	}
}