<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Hook;

use Combodo\iTop\Portal\Twig\PortalTwigContext;

interface iAbstractPortalTabContentExtension
{
	/**
	 * Indicates if the extension is active or not
	 * @return bool
	 */
	public function IsActive(): bool;

	/**
	 * Tab code name where to add the section
	 *
	 * @return string tab code
	 */
	public function GetTabCode(): string;

	/**
	 * Handle actions based on posted vars
	 */
	public function HandlePortalForm(array &$aData): void;

	/**
	 * List twigs and variables for the tab content per block
	 *
	 * @return PortalTwigContext
	 */
	public function GetPortalTabContentTwigs(): PortalTwigContext;

	/**
	 * Get the section rank in the tab
	 *
	 * @return float rank order
	 */
	public function GetSectionRank(): float;

}