<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Hook;

/**
 * Interface to provide content to a portal brick allowing tab extensibility.
 * This interface allows to provide new tabs to bricks.
 *
 * This interface should not be used directly, bricks willing to provide extensibility
 * should use an interface derived from this one.
 *
 * @api
 * @since iTop 3.2.1
 * @see \Combodo\iTop\Portal\Hook\iAbstractPortalTabContentExtension
 */
interface iAbstractPortalTabExtension
{
	/**
	 * True if the tab must be displayed or false if the tab must be hidden.
	 * When the tab is not displayed the other methods are not called.
	 *
	 * @return bool
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function IsTabPresent(): bool;

	/**
	 * Rank of the tab to allow sorting (ascending order)
	 *
	 * @return float order rank
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetTabRank(): float;

	/**
	 * Unique code for the AjaxTab (in the brick page)
	 *
	 * @return string the code must contain at least one character, cannot start with a number, and must not contain whitespaces (spaces, tabs, etc.).
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetTabCode(): string;

	/**
	 * Label of the tab
	 *
	 * @return string
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetTabLabel(): string;
}