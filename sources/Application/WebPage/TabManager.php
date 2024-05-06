<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\TabContainer;
use DeprecatedCallsLog;
use Dict;


/**
 * Helper class to implement JQueryUI tabs inside a page
 */
class TabManager
{
	const ENUM_TAB_TYPE_HTML = 'html';
	const ENUM_TAB_TYPE_AJAX = 'ajax';

	const DEFAULT_TAB_TYPE = self::ENUM_TAB_TYPE_HTML;

	/**
	 * @var TabContainer[]
	 */
	protected $m_aTabs;
	protected $m_sCurrentTabContainer;
	protected $m_sCurrentTab;

	public function __construct()
	{
		$this->m_aTabs = [];
		$this->m_sCurrentTabContainer = '';
		$this->m_sCurrentTab = '';
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sPrefix
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
	 */
	public function AddTabContainer(string $sTabContainer, $sPrefix = ''): TabContainer
	{
		$oTabContainer = new TabContainer($sTabContainer, $sPrefix);
		$this->m_aTabs[$sTabContainer] = $oTabContainer;

		return $oTabContainer;
	}

	/**
	 * @param string $sHtml
	 *
	 * @throws \Exception
	 */
	public function AddToCurrentTab(string $sHtml): void
	{
		$this->AddToTab($this->m_sCurrentTabContainer, $this->m_sCurrentTab, $sHtml);
	}

	public function AddUIBlockToCurrentTab(iUIBlock $oBlock): iUIBlock
	{
		$this->AddUIBlockToTab($this->m_sCurrentTabContainer, $this->m_sCurrentTab, $oBlock);
		return $oBlock;
	}

	public function AddUIBlockToTab(string $sTabContainer, string $sTabCode, iUIBlock $oBlock, $sTabTitle = null): void
	{
		if (!$this->TabExists($sTabContainer, $sTabCode)) {
			$this->InitTab($sTabContainer, $sTabCode, static::ENUM_TAB_TYPE_HTML, $sTabTitle);
		}

		$oTab = $this->GetTab($sTabContainer, $sTabCode);

		// Append to the content of the tab
		$oTab->AddSubBlock($oBlock);
	}

	/**
	 * @return int
	 * @deprecated 3.0.0
	 */
	public function GetCurrentTabLength()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		return 0;
	}

	/**
	 * Truncates the given tab to the specifed length and returns the truncated part
	 *
	 * @param string $sTabContainer The tab container in which to truncate the tab
	 * @param string $sTab The name/identifier of the tab to truncate
	 * @param integer $iLength The length/offset at which to truncate the tab
	 *
	 * @return string The truncated part
	 * @deprecated 3.0.0
	 */
	public function TruncateTab(string $sTabContainer, string $sTab, int $iLength)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		return '';
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sTab
	 *
	 * @return bool
	 */
	public function TabExists(string $sTabContainer, string $sTab)
	{
		return isset($this->m_aTabs[$sTabContainer]) ? $this->m_aTabs[$sTabContainer]->TabExists($sTab) : false;
	}

	/**
	 * @return int
	 */
	public function TabsContainerCount()
	{
		return count($this->m_aTabs);
	}

	private function GetTab(string $sTabContainer, string $sTab): ?Tab
	{
		if ($this->TabExists($sTabContainer, $sTab)) {
			return $this->m_aTabs[$sTabContainer]->GetTab($sTab);
		}
		return null;
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 * @param string $sHtml
	 * @param string|null $sTabTitle
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function AddToTab(string $sTabContainer, string $sTabCode, string $sHtml, $sTabTitle = null): string
	{
		if (!$this->TabExists($sTabContainer, $sTabCode)) {
			$this->InitTab($sTabContainer, $sTabCode, static::ENUM_TAB_TYPE_HTML, $sTabTitle);
		}

		$oTab = $this->GetTab($sTabContainer, $sTabCode);

		// Append to the content of the tab
		$oTab->AddHtml($sHtml);

		return ''; // Nothing to add to the page for now
	}

	/**
	 * @param string $sTabContainer
	 *
	 * @return string
	 */
	public function SetCurrentTabContainer($sTabContainer = '')
	{
		$sPreviousTabContainer = $this->m_sCurrentTabContainer;
		$this->m_sCurrentTabContainer = $sTabContainer;

		return $sPreviousTabContainer;
	}

	/**
	 * @param string $sTabCode
	 *
	 * @param string|null $sTabTitle
	 * @param string|null $sTabDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return string
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 * @since 3.1.0 N°5920 Add $sTabDescription argument
	 */
	public function SetCurrentTab(string $sTabCode = '', ?string $sTabTitle = null, ?string $sTabDescription = null): ?string
	{
		$sPreviousTabCode = $this->m_sCurrentTab;
		$this->m_sCurrentTab = $sTabCode;

		if ($sTabCode != '') {
			// Init tab to HTML tab if not existing
			if (!$this->TabExists($this->GetCurrentTabContainer(), $sTabCode)) {
				$this->InitTab($this->GetCurrentTabContainer(), $sTabCode, static::ENUM_TAB_TYPE_HTML, $sTabTitle, null, $sTabDescription);
			}
		}

		return $sPreviousTabCode;
	}

	/**
	 * Add a tab which content will be loaded asynchronously via the supplied URL
	 *
	 * Limitations:
	 * Cross site scripting is not allowed for security reasons. Use a normal tab with an IFRAME if you want to
	 * pull content from another server. Static content cannot be added inside such tabs.
	 *
	 * @param string $sTabCode The (localised) label of the tab
	 * @param string $sUrl The URL to load (on the same server)
	 * @param boolean $bCache Whether or not to cache the content of the tab once it has been loaded. false will cause
	 *     the tab to be reloaded upon each activation.
	 *
	 * @param string|null $sTabTitle
	 * @param string|null $sPlaceholder
	 * @param string|null $sTabDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return string
	 *
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 * @since 2.0.3
	 * @since 3.1.0 N°5920 Add $sTabDescription argument
	 */
	public function AddAjaxTab(string $sTabCode, string $sUrl, bool $bCache = true, ?string $sTabTitle = null, ?string $sPlaceholder = null, ?string $sTabDescription = null): string
	{
		// Set the content of the tab
		/** @var \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\AjaxTab $oTab */
		$oTab = $this->InitTab($this->m_sCurrentTabContainer, $sTabCode, static::ENUM_TAB_TYPE_AJAX, $sTabTitle, $sPlaceholder, $sTabDescription);
		$oTab->SetUrl($sUrl)
			->SetCache($bCache);

		return ''; // Nothing to add to the page for now
	}

	/**
	 * @return string
	 */
	public function GetCurrentTabContainer()
	{
		return $this->m_sCurrentTabContainer;
	}

	/**
	 * @return string
	 */
	public function GetCurrentTab()
	{
		return $this->m_sCurrentTab;
	}

	/**
	 * @param string $sTabCode
	 * @param string|null $sTabContainer
	 */
	public function RemoveTab(string $sTabCode, string $sTabContainer = null)
	{
		if ($sTabContainer == null) {
			$sTabContainer = $this->m_sCurrentTabContainer;
		}
		if (isset($this->m_aTabs[$sTabContainer]) && $this->m_aTabs[$sTabContainer]->TabExists($sTabCode)) {
			// Delete the content of the tab
			$this->m_aTabs[$sTabContainer]->RemoveTab($sTabCode);

			// If we just removed the active tab, let's reset the active tab
			if (($this->m_sCurrentTabContainer == $sTabContainer) && ($this->m_sCurrentTab == $sTabCode)) {
				$this->m_sCurrentTab = '';
			}
		}
	}

	/**
	 * Finds the tab whose title matches a given pattern
	 *
	 * @param string $sPattern
	 * @param string|null $sTabContainer
	 *
	 * @return mixed The actual name of the tab (as a string) or false if not found
	 */
	public function FindTab(string $sPattern, string $sTabContainer = null)
	{
		$result = false;
		if ($sTabContainer == null) {
			$sTabContainer = $this->m_sCurrentTabContainer;
		}
		if (isset($this->m_aTabs[$sTabContainer])) {
			foreach ($this->m_aTabs[$sTabContainer]->GetSubBlocks() as $sTabCode => $void) {
				if (preg_match($sPattern, $sTabCode)) {
					$result = $sTabCode;
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Make the given tab the active one, as if it were clicked
	 * DOES NOT WORK: apparently in the *old* version of jquery
	 * that we are using this is not supported... TO DO upgrade
	 * the whole jquery bundle...
	 *
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 *
	 * @return string
	 * @deprecated 3.0.0
	 */
	public function SelectTab(string $sTabContainer, string $sTabCode)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		return '';
	}

	/**
	 * @param string $sContent
	 * @param WebPage $oPage
	 *
	 * @return mixed
	 * @deprecated 3.0.0
	 */
	public function RenderIntoContent(string $sContent, WebPage $oPage)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		return '';
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 * @param string $sTabType
	 * @param string|null $sTabTitle
	 * @param string|null $sPlaceholder
	 * @param string|null $sTabDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 * @since 2.7.0
	 * @since 3.1.0 N°5920 Add $sTabDescription argument
	 */
	protected function InitTab(string $sTabContainer, string $sTabCode, string $sTabType = self::DEFAULT_TAB_TYPE, ?string $sTabTitle = null, ?string $sPlaceholder = null, ?string $sTabDescription = null): Tab
	{
		$oTab = null;
		if (!$this->TabExists($sTabContainer, $sTabCode)) {
			if (!isset($this->m_aTabs[$sTabContainer])) {
				$oTabContainer = $this->AddTabContainer($sTabContainer);
			} else {
				$oTabContainer = $this->m_aTabs[$sTabContainer];
			}

			// For title, if none given, try to fallback on a dict entry by convention (dict entry code = tab code)
			$sTitle = ($sTabTitle !== null) ? Dict::S($sTabTitle) : Dict::S($sTabCode);

			// For description, if none given, try to fallback on a dict entry by convention (dict entry code = tab code followed by a "+" sign) but only if an entry exists, otherwise it would display a tooltip with a dict entry code which is not slick (eg. "Tab:Title:Foo+")
			$sDescription = null;
			if ($sTabDescription !== null) {
				$sDescription = Dict::S($sTabDescription);
			} else {
				$sFallbackDescriptionDictEntryCode = $sTabCode."+";
				$sFallbackDescription = Dict::S($sFallbackDescriptionDictEntryCode);
				if ($sFallbackDescriptionDictEntryCode !== $sFallbackDescription) {
					$sDescription = $sFallbackDescription;
				}
			}

			switch ($sTabType) {
				case static::ENUM_TAB_TYPE_AJAX:
					$oTab = $oTabContainer->AddAjaxTab($sTabCode, $sTitle, $sPlaceholder, $sDescription);
					break;

				case static::ENUM_TAB_TYPE_HTML:
				default:
					$oTab = $oTabContainer->AddTab($sTabCode, $sTitle, $sDescription);
					break;
			}
		} else {
			$oTab = $this->GetTab($sTabContainer, $sTabCode);
		}

		return $oTab;
	}
}
