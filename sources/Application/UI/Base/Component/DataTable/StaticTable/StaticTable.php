<?php

namespace Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable;

use Combodo\iTop\Application\UI\Base\Component\DataTable\tTableRowActions;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use utils;

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Tables with static data
 * Class StaticTable
 */
class StaticTable extends UIContentBlock
{
	use tJSRefreshCallback;
	use tTableRowActions;

	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-datatable';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH        = 'base/components/datatable/static/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/datatable/static/layout';
	public const DEFAULT_JS_FILES_REL_PATH             = [
		'node_modules/datatables.net/js/jquery.dataTables.min.js',
		'node_modules/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
		'node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
		'node_modules/datatables.net-scroller/js/dataTables.scroller.min.js',
		'node_modules/datatables.net-select/js/dataTables.select.min.js',
		'js/field_sorter.js',
		'js/table-selectable-lines.js',
		'js/dataTables.main.js',
		'js/dataTables.settings.js',
		'js/dataTables.pipeline.js',
		'js/dataTables.row-actions.js',
	];

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
	private $aExtraParams;
	/*@var string $sUrlForRefresh*/
	private $sFilter;
	/** @var array $aOptions
	 * List of specific options for display datatable
	 */
	private $aOptions;

	public function __construct(string $sId = null, array $aContainerCSSClasses = [], array $aExtraParams = [])
	{
		parent::__construct($sId, $aContainerCSSClasses);
		$this->aColumns = [];
		$this->aData = [];
		$this->aExtraParams = $aExtraParams;
		$this->aOptions = [];
	}

	/**
	 * @return array
	 */
	public function GetColumns(): array
	{
		return $this->aColumns;
	}

	/**
	 * Return columns count.
	 *
	 * @return int
	 * @since 3.1.0
	 */
	public function GetColumnsCount(): int
	{
		return count($this->aColumns);
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
	 * @param string $sFilter
	 *
	 * @return $this
	 */
	public function SetFilter($sFilter)
	{
		$this->sFilter = $sFilter;

		return $this;
	}

	public function GetJSRefresh(): string
	{
		//$('#".$this->sId."').DataTable().clear().rows.add(data).draw()
		$aParams = [
			'style'        => 'list',
			'filter'       => $this->sFilter,
			'extra_params' => $this->aExtraParams,
		];

		return "$.post('".utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=refreshDashletList', ".json_encode($aParams).", 
					function (data) {
						$('#".$this->sId."').DataTable().clear();
						if (data.length>0)
	                    {
			                    $('#".$this->sId."').dataTable().fnAddData(data);
						}
					});";
	}

	/**
	 * @return mixed
	 */
	public function GetOption(string $sOption)
	{
		if (isset($this->aOptions[$sOption])) {
			return $this->aOptions[$sOption];
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function GetOptions(): array
	{
		return $this->aOptions;
	}

	/**
	 * @param array $aOptions
	 *
	 * @return $this
	 */
	public function SetOptions($aOptions)
	{
		$this->aOptions = $aOptions;

		return $this;
	}

	/**
	 * @param array $aOptions
	 *
	 * @return $this
	 */
	public function AddOption($sName, $sValue)
	{
		$this->aOptions[$sName] = $sValue;

		return $this;
	}
}