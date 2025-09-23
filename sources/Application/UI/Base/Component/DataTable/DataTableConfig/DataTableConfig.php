<?php

namespace Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableConfig;

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTable;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class DataTableConfig extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatableconfig';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/datatable/config/layout';

	/** @var DataTable */
	private $oDataTable;

	public function __construct(DataTable $oDataTable, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->oDataTable = $oDataTable;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\DataTable\DataTable
	 */
	private function GetDataTable()
	{
		return $this->oDataTable;
	}

	public function GetOption(string $sOption)
	{
		return $this->GetDataTable()->GetOption($sOption);
	}

	public function GetTableId()
	{
		return $this->GetDataTable()->GetId();
	}

}