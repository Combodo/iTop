<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Hook;

use Combodo\iTop\Portal\Twig\PortalTwigContext;

/**
 * Interface to provide content to a portal brick allowing tab extensibility.
 * This interface allows to provide content to existing tabs.
 *
 * This interface should not be used directly, bricks willing to provide extensibility
 * should use an interface derived from this one.
 *
 * @api
 * @since iTop 3.2.1
 * @see \Combodo\iTop\Portal\Hook\iAbstractPortalTabExtension
 */
interface iAbstractPortalTabContentExtension
{
	/**
	 * Indicates if the extension is active or not
	 *
	 * @return bool true if the content has to be displayed
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function IsActive(): bool;

	/**
	 * Tab code name where to add the section
	 *
	 * @return string tab code (the code must contain at least one character, cannot start with a number, and must not contain whitespaces (spaces, tabs, etc.).)
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetTabCode(): string;

	/**
	 * Handle actions based on posted vars
	 *
	 * @param array $aData variables to pass to the brick's twig to display the result of the action
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function HandlePortalForm(array &$aData): void;

	/**
	 * List twigs and variables for the tab content per block
	 *
	 * @return PortalTwigContext containing twigs to display and associated variables to use
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetPortalTabContentTwigs(): PortalTwigContext;

	/**
	 * Get the section rank in the tab (used to sort the contents of a tab)
	 *
	 * @return float rank order
	 *
	 * @api
	 * @since iTop 3.2.1
	 */
	public function GetSectionRank(): float;
}