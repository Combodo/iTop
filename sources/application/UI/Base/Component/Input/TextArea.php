<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
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

	public function __construct(?string $sValue, ?string $sId = null, ?int $iCols = null, ?int $iRows = null)
	{
		parent::__construct($sId);

		$this->sValue = $sValue;
		$this->iCols = $iCols;
		$this->iRows = $iRows;
	}

	public function GetCols(): int
	{
		return $this->iCols;
	}

	public function SetCols(int $iCols): TextArea
	{
		$this->iCols = $iCols;

		return $this;
	}

	public function GetRows(): int
	{
		return $this->iRows;
	}

	public function SetRows(int $iRows): TextArea
	{
		$this->iRows = $iRows;

		return $this;
	}
}