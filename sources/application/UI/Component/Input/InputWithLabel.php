<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Input;


use Combodo\iTop\Application\UI\UIBlock;

class InputWithLabel extends UIBlock
{
	public const HTML_TEMPLATE_REL_PATH = 'components/input/inputwithlabel';

	/** @var string */
	protected $sLabel;
	/** @var \Combodo\iTop\Application\UI\Component\Input\Input */
	protected $oInput;

	/**
	 * InputWithLabel constructor.
	 *
	 * @param string $sLabel
	 * @param \Combodo\iTop\Application\UI\Component\Input\Input $oInput
	 */
	public function __construct(string $sLabel, \Combodo\iTop\Application\UI\Component\Input\Input $oInput, ?string $sId)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->oInput = $oInput;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Component\Input\Input
	 */
	public function GetInput(): \Combodo\iTop\Application\UI\Component\Input\Input
	{
		return $this->oInput;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Component\Input\Input $oInput
	 *
	 * @return $this
	 */
	public function SetInput(\Combodo\iTop\Application\UI\Component\Input\Input $oInput): InputWithLabel
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

}