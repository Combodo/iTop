<?php

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iTabbedPage
{
	/**
	 * @param string $sTabContainer
	 * @param string $sPrefix
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock|null $oParentBlock
	 *
	 * @return mixed
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '', iUIContentBlock $oParentBlock = null);

	/**
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 * @param string $sHtml
	 *
	 * @return mixed
	 */
	public function AddToTab($sTabContainer, $sTabCode, $sHtml);

	/**
	 * @param string $sTabContainer
	 *
	 * @return mixed
	 */
	public function SetCurrentTabContainer($sTabContainer = '');

	/**
	 * @param string $sTabCode
	 *
	 * @return mixed
	 */
	public function SetCurrentTab($sTabCode = '');

	/**
	 * Add a tab which content will be loaded asynchronously via the supplied URL
	 *
	 * Limitations:
	 * Cross site scripting is not not allowed for security reasons. Use a normal tab with an IFRAME if you want to
	 * pull content from another server. Static content cannot be added inside such tabs.
	 *
	 * @param string $sTabCode The (localised) label of the tab
	 * @param string $sUrl The URL to load (on the same server)
	 * @param boolean $bCache Whether or not to cache the content of the tab once it has been loaded. flase will cause
	 *     the tab to be reloaded upon each activation.
	 * @param string|null $sTabTitle
	 *
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null);

	public function GetCurrentTab();

	/**
	 * @param string $sTabCode
	 * @param string|null $sTabContainer
	 *
	 * @return mixed
	 */
	public function RemoveTab($sTabCode, $sTabContainer = null);

	/**
	 * Finds the tab whose title matches a given pattern
	 *
	 * @param string $sPattern
	 * @param string|null $sTabContainer
	 *
	 * @return mixed The name of the tab as a string or false if not found
	 */
	public function FindTab($sPattern, $sTabContainer = null);
}
