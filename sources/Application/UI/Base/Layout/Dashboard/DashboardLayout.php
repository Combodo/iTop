<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Dashboard;

use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOption;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Dict;

use function Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;

// TODO 3.3 Remove old dashboard methods, make dict entries for elements, etc
class DashboardLayout extends UIBlock
{
	public const BLOCK_CODE = 'ibo-dashboard';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashboard/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/layouts/dashboard/dashboard.js',
		'js/layouts/dashboard/dashboard-grid.js',
		'js/layouts/dashboard/dashboard-grid-slot.js',
		'js/layouts/dashboard/dashlet.js',
	];

	/** @var string */
	protected $sTitle;
	/** @var \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock */
	protected $oToolbar;
	/** @var DashboardRow[] */
	protected $aDashboardRows;
	/** @var int */
	protected $iRows;

	protected $oDashboardGrid;

	protected $oTitleInput;
	protected $oRefreshInput;
	protected $oButtonsToolbar;

	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aDashboardRows = [];
		$this->iRows = 0;
		$this->sTitle = '';
		$this->oToolbar = new UIContentBlock(null, ['ibo-dashboard--top-bar-toolbar']);
		$this->oTitleInput = $this->MakeTitleInput();
		$this->oRefreshInput = $this->MakeRefreshInput();
		$this->oButtonsToolbar = $this->MakeButtonsToolbar();
	}

	public function MakeTitleInput()
	{
		$oTitleInput = new \Combodo\iTop\Application\UI\Base\Component\Input\Input();
		$oTitleInput->SetName('dashboard_title');
		$oTitleInput->SetType('text');
		$oTitleInput->SetPlaceholder('Enter the dashboard title...');

		return $oTitleInput;
	}

	public function MakeRefreshInput()
	{
		$oRefreshInput = \Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory::MakeForSelect('refresh_interval');
		$aRefreshRateOptions = [
			['value' => '0', 'label' => 'No auto-refresh'],
			['value' => '30', 'label' => 'Every 30 seconds'],
			['value' => '60', 'label' => 'Every 1 minute'],
			['value' => '300', 'label' => 'Every 5 minutes'],
			['value' => '600', 'label' => 'Every 10 minutes'],
			['value' => '1800', 'label' => 'Every 30 minutes'],
			['value' => '3600', 'label' => 'Every 1 hour'],
		];

		foreach ($aRefreshRateOptions as $aOptionData) {
			$oOption = SelectOptionUIBlockFactory::MakeForSelectOption($aOptionData['value'], $aOptionData['label'], false);
			$oRefreshInput->AddOption($oOption);
		}

		return $oRefreshInput;
	}
	public function MakeButtonsToolbar()
	{
		$oContainer = new UIContentBlock(null, ['ibo-dashboard--buttons-toolbar']);
		$oCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'), 'cancel', 'cancel');
		$oSaveButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Save'), 'save', 'save');

		$oContainer->AddSubBlock($oCancelButton);
		$oContainer->AddSubBlock($oSaveButton);

		return $oContainer;
	}

	/**
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardRow $oDashboardRow
	 *
	 * @return $this
	 */
	public function AddDashboardRow(DashboardRow $oDashboardRow)
	{
		$oDashboardRow->SetRowIndex($this->iRows);
		$this->aDashboardRows[] = $oDashboardRow;
		$this->iRows++;

		return $this;
	}

	public function GetSubBlocks(): array
	{
		return array_merge($this->aDashboardRows, [$this->oToolbar, $this->oRefreshInput, $this->oTitleInput, $this->oButtonsToolbar, $this->oDashboardGrid]);
	}

	/**
	 * @see static::$sTitle
	 * @return bool
	 */
	public function HasTitle(): bool
	{
		return strlen($this->sTitle) > 0;
	}

	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @see static::$oToolbar
	 * @return bool
	 */
	public function HasToolbar(): bool
	{
		return $this->oToolbar->HasSubBlocks();
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 */
	public function GetToolbar()
	{
		return $this->oToolbar;
	}

	public function SetTitle(string $sTitle)
	{
		$this->sTitle = $sTitle;

		$this->oTitleInput->SetValue($sTitle);
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Layout\Dashboard\DashboardRow[]
	 */
	public function GetDashboardRows(): array
	{
		return $this->aDashboardRows;
	}

	public function SetGrid(DashboardGrid $oDashboardGrid)
	{
		$this->oDashboardGrid = $oDashboardGrid;

		return $this;
	}

	public function GetGrid()
	{
		return $this->oDashboardGrid;
	}

	public function GetTitleInput()
	{
		return $this->oTitleInput;
	}

	public function GetRefreshInput()
	{
		return $this->oRefreshInput;
	}

	public function GetButtonsToolbar()
	{
		return $this->oButtonsToolbar;
	}
}
