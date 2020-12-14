<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;

class InputWithLabel extends UIBlock
{
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/inputwithlabel';

	/** @var string */
	protected $sLabel;
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput */
	protected $oInput;
	/** @var bool */
	protected $bHasBr;

	public function __construct(string $sLabel, AbstractInput $oInput, ?string $sId, ?bool $bHasBr = null)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->oInput = $oInput;

		if (is_null($bHasBr)) {
			$this->bHasBr = ($oInput instanceof TextArea);
		} else {
			$this->bHasBr = $bHasBr;
		}
	}

	public function GetInput(): AbstractInput
	{
		return $this->oInput;
	}

	public function SetInput(AbstractInput $oInput): InputWithLabel
	{
		$this->oInput = $oInput;

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
	 * @return InputWithLabel
	 */
	public function SetLabel(string $sLabel): InputWithLabel
	{
		$this->sLabel = $sLabel;

		return $this;
	}

	public function HasBr(): bool
	{
		return $this->bHasBr;
	}
}