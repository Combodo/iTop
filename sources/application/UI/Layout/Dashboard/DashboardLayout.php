<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Layout\Dashboard;

use Combodo\iTop\Application\UI\UIBlock;

class DashboardLayout extends UIBlock
{
	public const BLOCK_CODE = 'ibo-dashboard';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/dashboard/layout';

	/** @var DashboardRow[] */
	protected $aDashboardRows;
	/** @var int */
	protected $iRows;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aDashboardRows = [];
		$this->iRows = 0;
	}

	/**
	 *
	 * @param \Combodo\iTop\Application\UI\Layout\Dashboard\DashboardRow $oDashboardRow
	 *
	 * @return DashboardLayout
	 */
	public function AddDashboardRow(DashboardRow $oDashboardRow): DashboardLayout
	{
		$oDashboardRow->SetRowIndex($this->iRows);
		$this->aDashboardRows[] = $oDashboardRow;
		$this->iRows++;
		return $this;
	}

	public function GetSubBlocks()
	{
		return $this->aDashboardRows;
	}
}