<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Hook;

interface iAbstractPortalTabExtension
{
	/**
	 * True if the tab must be displayed or false if the tab must be hidden.
	 * When the tab is not displayed the other methods are not called.
	 *
	 * @return bool
	 */
	public function IsTabPresent(): bool;

	/**
	 * Rank of the tab to allow sorting
	 *
	 * @return float order rank
	 */
	public function GetTabRank(): float;

	/**
	 * Unique code for the AjaxTab
	 *
	 * @return string
	 */
	public function GetTabCode(): string;

	/**
	 * Label of the tab
	 *
	 * @return string
	 */
	public function GetTabLabel(): string;
}