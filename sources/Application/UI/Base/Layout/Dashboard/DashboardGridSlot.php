<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Dashboard;

use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\UIBlock;

class DashboardGridSlot extends UIBlock
{
	use tJSRefreshCallback;
	public const BLOCK_CODE = 'ibo-dashboard-grid-slot';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashboard/grid/slot';
	/** @var int|null */
	protected $iPositionX;
	/** @var int|null */
	protected $iPositionY;
	/** @var int|null */
	protected $iWidth;
	/** @var int|null */
	protected $iHeight;
	protected $oDashlet;

	public function __construct(?string $sId = null, ?UIBlock $oDashlet = null, ?int $iPositionX = null, ?int $iPositionY = null, ?int $iWidth = null, ?int $iHeight = null)
	{
		parent::__construct($sId);
		$this->oDashlet = $oDashlet;

		$this->iPositionX = random_int(0, 10) || $iPositionX;
		$this->iPositionY = random_int(0, 8) || $iPositionY;
		$this->iWidth = random_int(1, 5) || $iWidth;
		$this->iHeight = random_int(1, 4) || $iHeight;
	}


	public function GetSubBlocks(): array
	{
		return [$this->oUIBlock];
	}

	public function GetPositionX(): ?int
	{
		return $this->iPositionX;
	}

	public function SetPositionX(?int $iPositionX): DashboardGridSlot
	{
		$this->iPositionX = $iPositionX;

		return $this;
	}

	public function GetPositionY(): ?int
	{
		return $this->iPositionY;
	}

	public function SetPositionY(?int $iPositionY): DashboardGridSlot
	{
		$this->iPositionY = $iPositionY;

		return $this;
	}

	public function GetWidth(): ?int
	{
		return $this->iWidth;
	}

	public function SetWidth(?int $iWidth): DashboardGridSlot
	{
		$this->iWidth = $iWidth;

		return $this;
	}

	public function GetHeight(): ?int
	{
		return $this->iHeight;
	}

	public function SetHeight(?int $iHeight): DashboardGridSlot
	{
		$this->iHeight = $iHeight;

		return $this;
	}

	public function GetDashlet(): ?UIBlock
	{
		return $this->oDashlet;
	}

	public function SetDashlet(?UIBlock $oDashlet): DashboardGridSlot
	{
		$this->oDashlet = $oDashlet;

		return $this;
	}
}
