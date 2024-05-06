<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

use Combodo\iTop\Application\UI\Base\Component\Dialog\DialogUIBlockFactory;

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
	 *      label: string,
	 *      tooltip: string,
	 *      icon_classes: string,
	 *      js_row_action: string,
	 *      confirmation => {
	 *          message: string,
	 *          message_row_data: string,
	 *          do_not_show_again_pref_key: string
	 *      }
	 * }
	 */
	protected $aRowActions = [];

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
}