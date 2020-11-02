<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Layout\Dashboard;

use Combodo\iTop\Application\UI\UIBlock;

class DashboardRow extends UIBlock
{
	public const BLOCK_CODE = 'ibo-dashboard-row';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/dashboard/row/layout';

	/** @var DashboardColumn[] */
	protected $aDashboardColumns;
	/** @var int */
	protected $iRowIndex;
	/** @var int */
	protected $iCols;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aDashboardColumns = [];
		$this->iRowIndex = 0;
		$this->iCols = 0;
	}

	/**
	 *
	 * @param \Combodo\iTop\Application\UI\Layout\Dashboard\DashboardColumn $oDashboardColumn
	 *
	 * @return DashboardRow
	 */
	public function AddDashboardColumn(DashboardColumn $oDashboardColumn): DashboardRow
	{
		$oDashboardColumn->SetColumnIndex($this->iCols);
		$this->aDashboardColumns[] = $oDashboardColumn;
		$this->iCols++;
		return $this;
	}

	public function GetSubBlocks()
	{
		return $this->aDashboardColumns;
	}

	/**
	 * @return int
	 */
	public function GetRowIndex(): int
	{
		return $this->iRowIndex;
	}

	/**
	 * @param int $iRowIndex
	 *
	 * @return DashboardRow
	 */
	public function SetRowIndex(int $iRowIndex): DashboardRow
	{
		$this->iRowIndex = $iRowIndex;
		return $this;
	}
}