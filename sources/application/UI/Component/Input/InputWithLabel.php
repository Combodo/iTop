<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Input;


class InputWithLabel extends Input
{
	public const HTML_TEMPLATE_REL_PATH = 'components/input/inputwithlabel';

	/** @var string */
	protected $sLabel;

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