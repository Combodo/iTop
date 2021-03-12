<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\FormTableRow;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class FormTableRow
 *
 * @package Combodo\iTop\Application\UI\Base\Component\FormTableRow
 */
class FormTableRow extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-formtablerow';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/datatable/static/formtablerow/layout';

	/** @var string */
	private $sRef;
	/** @var int */
	private $iRowId;
	/**
	 * @var array
	 * [
	 *  'entry name' => [
	 *      'description' => tooltip,
	 *      'label' => label to display,
	 *      'class' => cell CSS class,
	 *      'metadata' => [key => value] transformed into data-key="value"
	 *  ], ...
	 * ]
	 */
	private $aColumns;

	/**
	 * @var array
	 * [
	 *  'entry name' => [
	 *      'value_html' => value to display in the cell,
	 *      'value_raw' => real value put into data-value-raw
	 *  ], ...
	 * ]
	 */
	private $aData;

	public function __construct(string $sRef, array $aColumns, array $aData, int $iRowId)
	{
		parent::__construct();
		$this->SetRef($sRef);
		$this->SetColumns($aColumns);
		$this->SetData($aData);
		$this->SetRowId($iRowId);
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

	/**
	 * @return array
	 */
	public function GetColumns(): array
	{
		return $this->aColumns;
	}

	/**
	 * @param array $aColumns
	 */
	public function SetColumns(array $aColumns): void
	{
		$this->aColumns = $aColumns;
	}

	/**
	 * @return array
	 */
	public function GetData(): array
	{
		return $this->aData;
	}

	/**
	 * @param array $aData
	 */
	public function SetData(array $aData): void
	{
		$this->aData = $aData;
	}

	/**
	 * @return int
	 */
	public function GetRowId(): int
	{
		return $this->iRowId;
	}

	/**
	 * @param int $iRowId
	 */
	public function SetRowId(int $iRowId): void
	{
		$this->iRowId = $iRowId;
	}
}