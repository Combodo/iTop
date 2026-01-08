<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Dashboard;

use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletWrapper;
use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\UIBlock;

class DashboardGrid extends UIBlock
{
	use tJSRefreshCallback;

	public const BLOCK_CODE                     = 'ibo-dashboard-grid';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashboard/grid/layout';

	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/layouts/dashboard/dashboard-grid.js',
	];

	/** @var DashboardGridSlot[] */
	protected $aSlots;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aSlots = [];
	}

	/**
	 * @return DashboardGridSlot[]
	 */
	public function GetSlots(): array
	{
		return $this->aSlots;
	}

	public function SetSlots(array $aSlots): DashboardGrid
	{
		$this->aSlots = $aSlots;

		return $this;
	}

	public function AddSlot(DashboardGridSlot $oSlot): DashboardGrid
	{
		$this->aSlots[] = $oSlot;

		return $this;
	}

	public function AddDashlet(UIBlock $oDashlet, string $sDashletId, string $sDashletClass, array $aFormViewData): DashboardGrid
	{
		$oWrapper = new DashletWrapper($oDashlet, $sDashletClass, $sDashletId, $aFormViewData);
		$oSlot = new DashboardGridSlot(null, $oWrapper);
		$this->AddSlot($oSlot);

		return $this;
	}
}