<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;

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
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\Input */
	protected $oInput;

	/**
	 * @param string $sLabel
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput $oInput
	 * @param string|null $sId
	 */
	public function __construct(string $sLabel, AbstractInput $oInput, ?string $sId)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->oInput = $oInput;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput
	 */
	public function GetInput()
	{
		return $this->oInput;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput $oInput
	 *
	 * @return $this
	 */
	public function SetInput(AbstractInput $oInput)
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