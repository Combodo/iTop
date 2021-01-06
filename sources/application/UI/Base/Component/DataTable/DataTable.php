<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use DataTableConfig;

/**
 * Class DataTable
 *
 * @package Combodo\iTop\Application\UI\Base\Component\DataTable
 * @since 3.0.0
 */
class DataTable extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatable';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'lib/datatables/js/jquery.dataTables.min.js',
		'lib/datatables/js/dataTables.bootstrap.min.js',
		'lib/datatables/js/dataTables.fixedHeader.min.js',
		'lib/datatables/js/dataTables.responsive.min.js',
		'lib/datatables/js/dataTables.scroller.min.js',
		'lib/datatables/js/dataTables.select.min.js',
		'js/dataTables.main.js',
		'js/dataTables.settings.js',
		'js/dataTables.pipeline.js',
	];

	protected $aOptions;//list of specific options for display datatable
	protected $sAjaxUrl;
	protected $aAjaxData;
	protected $aDisplayColumns;
	protected $aResultColumns;

	/**
	 * Panel constructor.
	 *
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->AddDeferredBlock(new DataTableConfig($this));
		$this->aDisplayColumns = [];
		$this->aOptions = [];
		$this->aResultColumns = [];
	}

	/**
	 * @return mixed
	 */
	public function GetAjaxUrl(): string
	{
		return $this->sAjaxUrl;
	}

	/**
	 * @param mixed $sAjaxUrl
	 */
	public function SetAjaxUrl(string $sAjaxUrl): void
	{
		$this->sAjaxUrl = $sAjaxUrl;
	}

	/**
	 * @return mixed
	 */
	public function GetAjaxData(string $sName)
	{
		if (isset($this->aAjaxData[$sName])) {
			return $this->aAjaxData[$sName];
		}
		return '';
	}

	/**
	 * @return mixed
	 */
	public function GetJsonAjaxData(): string
	{
		return json_encode($this->aAjaxData);
	}

	/**
	 * @param mixed $sAjaxData
	 */
	public function SetAjaxData(array $aAjaxData): void
	{
		$this->aAjaxData = $aAjaxData;
	}

	/**
	 * @return mixed
	 */
	public function GetDisplayColumns(): array
	{
		return $this->aDisplayColumns;
	}

	/**
	 * @param mixed $aColumns
	 */
	public function SetDisplayColumns($aColumns): void
	{
		$this->aDisplayColumns = $aColumns;
	}
	/**
 * @return mixed
 */
	public function GetResultColumns(): array
	{
		return $this->aResultColumns;
	}
	/**
	 * @return mixed
	 */
	public function GetResultColumnsAsJson(): string
	{
		return json_encode($this->aResultColumns);
	}

	/**
	 * @param mixed $aColumns
	 */
	public function SetResultColumns($aColumns): void
	{
		$this->aResultColumns = $aColumns;
	}

	public function GetOption(string $sOption)
	{
		if (isset($this->aOptions[$sOption])) {
			return $this->aOptions[$sOption];
		}
		return null;
	}

	/**
	 * @return mixed
	 */
	public function GetOptions(): array
	{
		return $this->aOptions;
	}

	/**
	 * @param mixed $aOptions
	 */
	public function SetOptions($aOptions): void
	{
		$this->aOptions = $aOptions;
	}
}
