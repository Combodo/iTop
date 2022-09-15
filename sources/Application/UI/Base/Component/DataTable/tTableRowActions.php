<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\DataTable;

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
	 * @return DataTable
	 */
	public function SetRowActions(array $aRowActions): DataTable
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
}