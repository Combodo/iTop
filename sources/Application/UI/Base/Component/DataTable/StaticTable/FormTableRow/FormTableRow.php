<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
	 *
	 * @return $this
	 */
	public function SetRef(string $sRef)
	{
		$this->sRef = $sRef;

		return $this;
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
	 *
	 * @return $this
	 */
	public function SetColumns(array $aColumns)
	{
		$this->aColumns = $aColumns;

		return $this;
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
	 *
	 * @return $this
	 */
	public function SetData(array $aData)
	{
		$this->aData = $aData;

		return $this;
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
	 *
	 * @return $this
	 */
	public function SetRowId(int $iRowId)
	{
		$this->iRowId = $iRowId;

		return $this;
	}
}