<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Input;


class Select extends InputWithLabel
{
	public const HTML_TEMPLATE_REL_PATH = 'components/input/select';

	/** @var array */
	protected $aOptions;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aOptions = [];
	}

	public function AddOption(SelectOption $oOption)
	{
		$this->aOptions[$oOption->GetId()] = $oOption;
	}

	public function GetSubBlocks()
	{
		return $this->aOptions;
	}
}