<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;


use ApplicationContext;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableConfig\DataTableConfig;

/**
 * Class DataTable
 *
 * @package Combodo\iTop\Application\UI\Base\Component\DataTable
 * @since 3.0.0
 */
class DataTable extends UIContentBlock
{
	use tJSRefreshCallback;
	use tTableRowActions;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatable';

	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_LIVE_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
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

	protected $aOptions;//list of specific options for display datatable
	protected $sAjaxUrl;
	protected $aAjaxData;
	protected $aDisplayColumns;
	protected $aResultColumns;
	/**
	 * @var string
	 */
	protected $sJsonData;
	/*
	 * array of data to display the first page
	 */
	protected $aInitDisplayData;
	/**
	 * @var string JS Handler to be called when "open_creation_modal.object.itop" is fired on the table
	 */
	protected string $sModalCreationHandler;

	public const DEFAULT_ACTION_ROW_CONFIRMATION = true;


	/**
	 * Panel constructor.
	 *
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		// This block contains a form, so it has to be added later in the flow
		$this->AddDeferredBlock(new DataTableConfig($this));
		$this->aDisplayColumns = [];
		$this->aOptions = [];
		$this->aResultColumns = [];
		$this->sJsonData = '';
		$this->sModalCreationHandler = '';
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
	 *
	 * @return $this
	 */
	public function SetAjaxUrl(string $sAjaxUrl)
	{
		if (strlen($sAjaxUrl) > 0)
		{
			$oAppContext = new ApplicationContext();
			if(strpos ($sAjaxUrl,'?')) {
				$this->sAjaxUrl = $sAjaxUrl."&".$oAppContext->GetForLink();
			} else {
				$this->sAjaxUrl = $sAjaxUrl."?".$oAppContext->GetForLink();
			}
		}
		else
		{
			$this->sAjaxUrl = $sAjaxUrl;
		}

		return $this;
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
	 * Get $aAjaxData as a JSON
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

	/**
	 * @param string $sName
	 * @param mixed $sValue
	 */
	public function AddOption($sName, $sValue): void
	{
		$this->aOptions[$sName] = $sValue;
	}

	/**
	 *  Get $aInitDisplayData as a JSON This is data of first page
	 *
	 * @return string
	 */
	public function GetJsonInitDisplayData(): string
	{
		return json_encode($this->aInitDisplayData);
	}

	/**
	 *  Get $aInitDisplayData
	 * @return array
	 */
	public function GetInitDisplayData(): array
	{
		return $this->aInitDisplayData;
	}

	/**
	 * @param string $aData
	 *
	 * @return $this
	 */
	public function SetInitDisplayData(array $aData)
	{
		$this->aInitDisplayData = $aData;

		return $this;
	}

	public function GetJSRefresh(): string
	{
		return "$('#".$this->sId."').DataTable().clearPipeline();
				$('#".$this->sId."').DataTable().ajax.reload(null, false);";
	}

	public function GetDisabledSelect(): array
	{
		$aExtraParams = $this->aAjaxData['extra_params'];
		if (isset($aExtraParams['selection_enabled'])) {
			$aListDisabled = [];
			foreach ($aExtraParams['selection_enabled'] as $sKey => $bValue) {
				if ($bValue == false) {
					$aListDisabled[] = $sKey;
				}
			}

			return $aListDisabled;
		}

		return [];
	}

	/**
	 * @return string
	 */
	public function GetModalCreationHandler(): string
	{
		return $this->sModalCreationHandler;
	}

	/**
	 * @param string $sModalCreationHandler
	 * @return $this
	 */
	public function SetModalCreationHandler(string $sModalCreationHandler)
	{
		$this->sModalCreationHandler = $sModalCreationHandler;
		return $this;
	}

}
