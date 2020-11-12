<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Component\DataTable\StaticTable\FormTable;


use Combodo\iTop\Application\UI\Component\DataTable\StaticTable\FormTableRow\FormTableRow;
use Combodo\iTop\Application\UI\Component\DataTable\StaticTable\StaticTable;
use Combodo\iTop\Application\UI\iUIBlock;

/**
 * Class FormTable
 *
 * @package Combodo\iTop\Application\UI\Component\FormTable
 */
class FormTable extends StaticTable
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-formtable';
	public const HTML_TEMPLATE_REL_PATH = 'components/datatable/static/formtable/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/datatable/static/formtable/layout';

	/** @var string */
	private $sRef;

	/** @var iUIBlock[] */
	private $aRows;

	public function __construct(string $sRef, string $sContainerCSSClass = '')
	{
		parent::__construct("dt_{$sRef}", $sContainerCSSClass);
		$this->SetRef($sRef);
		$this->aRows = [];
	}

	/**
	 * @return string
	 */
	public function GetRef(): string
	{
		return $this->sRef;
	}

	/**
	 * @param string $sRef
	 */
	public function SetRef(string $sRef): void
	{
		$this->sRef = $sRef;
	}

	public function GetRows(): array
	{
		return $this->aRows;
	}

	public function AddRow(FormTableRow $oRow): self
	{
		$this->aRows[] = $oRow;
		return $this;
	}
}