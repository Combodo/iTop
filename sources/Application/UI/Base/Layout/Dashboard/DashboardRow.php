<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout\Dashboard;

use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\UIBlock;

class DashboardRow extends UIBlock
{
	use tJSRefreshCallback;
	public const BLOCK_CODE = 'ibo-dashboard-row';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashboard/row/layout';

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
	 * @param \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardColumn $oDashboardColumn
	 *
	 * @return $this
	 */
	public function AddDashboardColumn(DashboardColumn $oDashboardColumn)
	{
		$oDashboardColumn->SetColumnIndex($this->iCols);
		$this->aDashboardColumns[] = $oDashboardColumn;
		$this->iCols++;
		return $this;
	}

	public function GetSubBlocks(): array
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
	 * @return $this
	 */
	public function SetRowIndex(int $iRowIndex)
	{
		$this->iRowIndex = $iRowIndex;
		return $this;
	}
}