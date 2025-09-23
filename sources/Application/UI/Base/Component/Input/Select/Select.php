<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Input\Select;


use Combodo\iTop\Application\UI\Base\Component\Input\tInputLabel;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class Select extends UIContentBlock
{
	use tInputLabel;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-select';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/select/select';

	/** @var string Input name for the form */
	protected $sName;
	/** @var bool if true submit the form as soon as a change is detected */
	protected $bSubmitOnChange = false;
	/** @var bool Allow multiple selection */
	protected $bIsMultiple = false;


	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->bIsMultiple = false;
	}

	/**
	 * @param SelectOption $oOption Select option UIBlock
	 */
	public function AddOption(SelectOption $oOption)
	{
		$this->AddSubBlock($oOption);
	}

	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName {@see Select::$sName}
	 *
	 * @return $this
	 */
	public function SetName(string $sName)
	{
		$this->sName = $sName;

		return $this;
	}

	/**
	 * @return bool {@see Select::$bSubmitOnChange}
	 */
	public function GetSubmitOnChange(): bool
	{
		return $this->bSubmitOnChange;
	}

	/**
	 * @param bool $bSubmitOnChange {@see Select::$bSubmitOnChange}
	 *
	 * @return $this
	 */
	public function SetSubmitOnChange(bool $bSubmitOnChange)
	{
		$this->bSubmitOnChange = $bSubmitOnChange;

		return $this;
	}

	/**
	 * @return bool {@see Select::$bIsMultiple}
	 */
	public function IsMultiple(): bool
	{
		return $this->bIsMultiple;
	}

	/**
	 * @param bool $bIsMultiple {@see Select::$bIsMultiple}
	 */
	public function SetIsMultiple(bool $bIsMultiple)
	{
		$this->bIsMultiple = $bIsMultiple;

		return $this;
	}
}