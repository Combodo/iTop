<?php

namespace Combodo\iTop\Application\UI\Component\DataTable\StaticTable;

use Combodo\iTop\Application\UI\Layout\UIContentBlock;

/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Tables with static data
 * Class StaticTable
 */
class StaticTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatable';
	public const HTML_TEMPLATE_REL_PATH = 'components/datatable/static/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/datatable/static/layout';

	/**
	 * @var array of 'entry name' => [
	 *  'description' => tooltip,
	 *  'label' => label to display,
	 *  'class' => cell CSS class,
	 *  'metadata' => [key => value] transformed into data-key="value"
	 * ]
	 */
	private $aColumns;

	/**
	 * @var array of [
	 *  '@class' => css class of the row,
	 *  'entry name' => [
	 *      'value_html' => value to display in the cell,
	 *      'value_raw' => real value put into data-value-raw
	 *  ], ...
	 * ]
	 */
	private $aData;
	
	public function __construct(string $sId = null, string $sContainerCSSClass = '')
	{
		parent::__construct($sId, $sContainerCSSClass);
		$this->aColumns = [];
		$this->aData = [];
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

}