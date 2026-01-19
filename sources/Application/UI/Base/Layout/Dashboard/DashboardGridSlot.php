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

		$this->iPositionX = $iPositionX;
		$this->iPositionY = $iPositionY;
		$this->iWidth = $iWidth;
		$this->iHeight = $iHeight;
	}


	public function HasPositionX(): bool
	{
		return !is_null($this->iPositionX);
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

	public function HasPositionY(): bool
	{
		return !is_null($this->iPositionX);
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

	public function HasWidth(): bool
	{
		return !is_null($this->iWidth);
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

	public function HasHeight(): bool
	{
		return !is_null($this->iHeight);
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

	public function GetSubBlocks(): array
	{
		return [$this->oDashlet];
	}
}
