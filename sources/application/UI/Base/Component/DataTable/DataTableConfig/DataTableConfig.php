<?php

use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class DataTableConfig extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-datatableconfig';
	public const HTML_TEMPLATE_REL_PATH = 'base/components/datatable/config/layout';

	/** @var DataTableBlock */
	private $oDataTable;

	public function __construct(DataTableBlock $oDataTable, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->oDataTable = $oDataTable;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableBlock
	 */
	private function GetDataTable(): DataTableBlock
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