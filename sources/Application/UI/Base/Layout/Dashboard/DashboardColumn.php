<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout\Dashboard;


use Combodo\iTop\Application\UI\Base\tJSRefreshCallback;
use Combodo\iTop\Application\UI\Base\UIBlock;

class DashboardColumn extends UIBlock
{
	use tJSRefreshCallback;
	public const BLOCK_CODE = 'ibo-dashboard-column';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashboard/column/layout';

	/** @var UIBlock[] */
	protected $aUIBlocks;
	/** @var int */
	protected $iColumnIndex;
	/** @var int */
	protected $iCellIndex;
	/** @var bool */
	protected $bEditMode;
	/** @var bool */
	protected $bLastRow;

	public function __construct(bool $bEditMode = false, bool $bLastRow = false)
	{
		parent::__construct();
		$this->aUIBlocks = [];
		$this->iColumnIndex = 0;
		$this->iCellIndex = 0;
		$this->bEditMode = $bEditMode;
		$this->bLastRow = $bLastRow;
	}

	/**
	 *
	 * @param UIBlock $oUIBlock
	 *
	 * @return $this
	 */
	public function AddUIBlock(UIBlock $oUIBlock): DashboardColumn
	{
		$this->aUIBlocks[] = $oUIBlock;
		return $this;
	}

	public function GetSubBlocks(): array
	{
		return $this->aUIBlocks;
	}

	/**
	 * @return int
	 */
	public function GetColumnIndex(): int
	{
		return $this->iColumnIndex;
	}

	/**
	 * @param int $iColumnIndex
	 *
	 * @return $this
	 */
	public function SetColumnIndex(int $iColumnIndex)
	{
		$this->iColumnIndex = $iColumnIndex;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsEditMode(): bool
	{
		return $this->bEditMode;
	}

	/**
	 * @param bool $bEditMode
	 *
	 * @return $this
	 */
	public function SetEditMode(bool $bEditMode)
	{
		$this->bEditMode = $bEditMode;
		return $this;
	}

	/**
	 * @return int
	 */
	public function GetCellIndex(): int
	{
		return $this->iCellIndex;
	}

	/**
	 * @param int $iCellIndex
	 *
	 * @return $this
	 */
	public function SetCellIndex(int $iCellIndex)
	{
		$this->iCellIndex = $iCellIndex;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsLastRow(): bool
	{
		return $this->bLastRow;
	}

	/**
	 * @param bool $bLastRow
	 *
	 * @return $this
	 */
	public function SetLastRow(bool $bLastRow)
	{
		$this->bLastRow = $bLastRow;
		return $this;
	}
}