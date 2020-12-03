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
class DataTableBlock extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatable';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/datatable/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/datatable/layout';

	protected $aOptions;//list of specific options for display datatable
	protected $sAjaxUrl;
	protected $sAjaxData;
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
	public function GetAjaxData(): string
	{
		return $this->sAjaxData;
	}

	/**
	 * @param mixed $sAjaxData
	 */
	public function SetAjaxData(string $sAjaxData): void
	{
		$this->sAjaxData = $sAjaxData;
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
