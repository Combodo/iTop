<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


/**
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
class TextArea extends AbstractInput
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-textarea';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/input-textarea';

	/** @var int */
	protected $iCols;
	/** @var int */
	protected $iRows;
	/** @var bool */
	protected $bIsDisabled;

	public function __construct(string $sName, ?string $sValue, ?string $sId = null, ?int $iCols = null, ?int $iRows = null)
	{
		parent::__construct($sId);

		$this->sName = $sName;
		$this->sValue = $sValue;
		$this->iCols = $iCols;
		$this->iRows = $iRows;
		$this->bIsDisabled = false;
	}

	public function GetCols(): ?int
	{
		return $this->iCols;
	}

	/**
	 * @param int $iCols
	 *
	 * @return $this
	 */
	public function SetCols(int $iCols)
	{
		$this->iCols = $iCols;

		return $this;
	}

	public function GetRows(): ?int
	{
		return $this->iRows;
	}

	/**
	 * @param int $iRows
	 *
	 * @return $this
	 */
	public function SetRows(int $iRows)
	{
		$this->iRows = $iRows;

		return $this;
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


}