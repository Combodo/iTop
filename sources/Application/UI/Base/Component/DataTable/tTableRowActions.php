<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use Combodo\iTop\Application\UI\Base\Component\Html\Html;

/**
 * Trait tTableRowActions
 *
 * This brings the ability to add action rows to tables.
 *
 * @internal
 * @package Combodo\iTop\Application\UI\Base\Component\DataTable
 * @since 3.1.0
 */
trait tTableRowActions
{
	/** @var bool static dialog initialized flag to avoid multiple html markups */
	static public bool $bDialogInitialized = false;

	/**
	 * @var $aRowActions array array of row actions
	 * action => {
	 *      tooltip: string,
	 *      icon_classes: string,
	 *      js_row_action: string
	 * }
	 */
	protected $aRowActions;

	/**
	 * Set row actions.
	 *
	 * @param array $aRowActions
	 *
	 * @return $this
	 */
	public function SetRowActions(array $aRowActions)
	{
		$this->aRowActions = $aRowActions;

		return $this;
	}

	/**
	 * Get row actions.
	 *
	 * @return array
	 */
	public function GetRowActions(): array
	{
		return $this->aRowActions;
	}

	/**
	 * Return true if row actions is set and not empty.
	 *
	 * @return bool
	 */
	public function HasRowActions(): bool
	{
		return isset($this->aRowActions) && count($this->aRowActions);
	}

	/**
	 * Return row actions template.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Template\Template
	 */
	public function GetRowActionsTemplate()
	{
		return DataTableUIBlockFactory::MakeActionRowToolbarTemplate($this);
	}

	/**
	 * GetRowActionsConfirmDialog.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Html\Html
	 */
	public function GetRowActionsConfirmDialog()
	{
		static::$bDialogInitialized = true;

		$sDoNotShowAgain = \Dict::S('UI:UserPref:DoNotShowAgain');

		return new Html(
<<< HTML
    <div class="ibo-abstract-block-links-view-table--action-confirmation" data-role="ibo-datatable--row-action--confirmation-dialog" title="" style="display: none">
        <div class="ibo-abstract-block-links-view-table--action-confirmation-explanation"></div>
        <label class="ibo-abstract-block-links-view-table--action-confirmation-preference">
            <input type="checkbox" class="ibo-abstract-block-links-view-table--action-confirmation-preference-input" data-role="ibo-abstract-block-links-view-table--action-confirmation-preference-input">
            <span class="ibo-abstract-block-links-view-table--action-confirmation-preference-text">{$sDoNotShowAgain}</span>
        </label>
    </div>
HTML
		);
	}

	public function GetRowActionsConfirmDialogInitializedFlag()
	{
		return static::$bDialogInitialized;
	}
}