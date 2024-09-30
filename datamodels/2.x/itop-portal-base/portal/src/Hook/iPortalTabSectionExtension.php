<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Hook;

use Combodo\iTop\Portal\Twig\PortalTwigContext;

interface iPortalTabSectionExtension
{
	/**
	 * Get the target reference of the page to display this tab
	 *
	 * @return string
	 */
	public function GetTarget(): string;

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
	 *
	 * @return PortalTwigContext
	 */
	public function GetPortalTwigContext(): PortalTwigContext;

	/**
	 * Get the section rank in the tab
	 *
	 * @return float rank order
	 */
	public function GetSectionRank(): float;

}