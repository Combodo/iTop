<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use Combodo\iTop\Application\UI\Base\Component\Dialog\DialogUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;

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
	 *      js_row_action: string,
	 *      confirmation => {
	 *          message: string,
	 *          message_row_data: string,
	 *          remember_choice_pref_key: string
	 *      }
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
	 * @return array|null
	 */
	public function GetRowActions(): ?array
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

		$oDialog = DialogUIBlockFactory::MakeNeutral('', '<div class="ibo-row-action--confirmation--explanation"></div>', 'table-row-action-confirmation-dialog');

		$oContent = UIContentBlockUIBlockFactory::MakeStandard();
		$oContent->AddCSSClass('ibo-row-action--confirmation--do-not-show-again');
		$checkBox = InputUIBlockFactory::MakeStandard('checkbox', 'do_not_show_again', false);
		$checkBox->AddCSSClass('ibo-row-action--confirmation--do-not-show-again--checkbox');
		$checkBox->SetLabel(\Dict::S('UI:UserPref:DoNotShowAgain'));
		$oContent->AddSubBlock($checkBox);
		$oDialog->AddSubBlock($oContent);

		return $oDialog;
	}

	public function GetRowActionsConfirmDialogInitializedFlag()
	{
		return static::$bDialogInitialized;
	}
}