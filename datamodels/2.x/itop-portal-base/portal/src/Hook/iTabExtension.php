<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Hook\Display;

/**
 * Interface iMyAccountAjaxTabExtension
 * Define extensibility point to MyAccount screen
 */
interface iTabExtension
{
	/**
	 * Get the target reference of the page to display this tab
	 *
	 * @return string
	 */
	public function GetTarget(): string;

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
	 * True if the tab is cached or false if the tab must be reloaded each time the user click on it
	 *
	 * @return bool
	 */
	public function GetTabIsCached(): bool;

	/**
	 * Label of the tab
	 *
	 * @return string
	 */
	public function GetTabLabel(): string;
}