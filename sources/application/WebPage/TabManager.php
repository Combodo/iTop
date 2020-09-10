<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Helper class to implement JQueryUI tabs inside a page
 */
class TabManager
{
	const ENUM_TAB_TYPE_HTML = 'html';
	const ENUM_TAB_TYPE_AJAX = 'ajax';

	const DEFAULT_TAB_TYPE = self::ENUM_TAB_TYPE_HTML;

	protected $m_aTabs;
	protected $m_sCurrentTabContainer;
	protected $m_sCurrentTab;

	public function __construct()
	{
		$this->m_aTabs = array();
		$this->m_sCurrentTabContainer = '';
		$this->m_sCurrentTab = '';
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sPrefix
	 *
	 * @return string
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '')
	{
		$this->m_aTabs[$sTabContainer] = array('prefix' => $sPrefix, 'tabs' => array());

		return "\$Tabs:$sTabContainer\$";
	}

	/**
	 * @param string $sHtml
	 *
	 * @throws \Exception
	 */
	public function AddToCurrentTab($sHtml)
	{
		$this->AddToTab($this->m_sCurrentTabContainer, $this->m_sCurrentTab, $sHtml);
	}

	/**
	 * @return int
	 */
	public function GetCurrentTabLength()
	{
		$iLength = isset($this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$this->m_sCurrentTab]['html']) ? strlen($this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$this->m_sCurrentTab]['html']) : 0;

		return $iLength;
	}

	/**
	 * Truncates the given tab to the specifed length and returns the truncated part
	 *
	 * @param string $sTabContainer The tab container in which to truncate the tab
	 * @param string $sTab The name/identifier of the tab to truncate
	 * @param integer $iLength The length/offset at which to truncate the tab
	 *
	 * @return string The truncated part
	 */
	public function TruncateTab($sTabContainer, $sTab, $iLength)
	{
		$sResult = substr($this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$this->m_sCurrentTab]['html'],
			$iLength);
		$this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$this->m_sCurrentTab]['html'] = substr($this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$this->m_sCurrentTab]['html'],
			0, $iLength);

		return $sResult;
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sTab
	 *
	 * @return bool
	 */
	public function TabExists($sTabContainer, $sTab)
	{
		return isset($this->m_aTabs[$sTabContainer]['tabs'][$sTab]);
	}

	/**
	 * @return int
	 */
	public function TabsContainerCount()
	{
		return count($this->m_aTabs);
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
	public function AddToTab($sTabContainer, $sTabCode, $sHtml, $sTabTitle = null)
	{
		if (!$this->TabExists($sTabContainer, $sTabCode)) {
			$this->InitTab($sTabContainer, $sTabCode, static::ENUM_TAB_TYPE_HTML, $sTabTitle);
		}

		// If target tab is not of type 'html', throw an exception
		if ($this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['type'] != static::ENUM_TAB_TYPE_HTML) {
			throw new Exception("Cannot add HTML content to the tab '$sTabCode' of type '{$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['type']}'");
		}

		// Append to the content of the tab
		$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['html'] .= $sHtml;

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
	 * @return string
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null)
	{
		$sPreviousTabCode = $this->m_sCurrentTab;
		$this->m_sCurrentTab = $sTabCode;

		// Init tab to HTML tab if not existing
		if (!$this->TabExists($this->GetCurrentTabContainer(), $sTabCode)) {
			$this->InitTab($this->GetCurrentTabContainer(), $sTabCode, static::ENUM_TAB_TYPE_HTML, $sTabTitle);
		}

		return $sPreviousTabCode;
	}

	/**
	 * Add a tab which content will be loaded asynchronously via the supplied URL
	 *
	 * Limitations:
	 * Cross site scripting is not not allowed for security reasons. Use a normal tab with an IFRAME if you want to
	 * pull content from another server. Static content cannot be added inside such tabs.
	 *
	 * @param string $sTabCode The (localised) label of the tab
	 * @param string $sUrl The URL to load (on the same server)
	 * @param boolean $bCache Whether or not to cache the content of the tab once it has been loaded. false will cause
	 *     the tab to be reloaded upon each activation.
	 *
	 * @return string
	 *
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null)
	{
		// Set the content of the tab
		$this->InitTab($this->m_sCurrentTabContainer, $sTabCode, static::ENUM_TAB_TYPE_AJAX, $sTabTitle);
		$this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$sTabCode]['url'] = $sUrl;
		$this->m_aTabs[$this->m_sCurrentTabContainer]['tabs'][$sTabCode]['cache'] = $bCache;

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
	public function RemoveTab($sTabCode, $sTabContainer = null)
	{
		if ($sTabContainer == null) {
			$sTabContainer = $this->m_sCurrentTabContainer;
		}
		if (isset($this->m_aTabs[$sTabContainer]['tabs'][$sTabCode])) {
			// Delete the content of the tab
			unset($this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]);

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
	public function FindTab($sPattern, $sTabContainer = null)
	{
		$result = false;
		if ($sTabContainer == null) {
			$sTabContainer = $this->m_sCurrentTabContainer;
		}
		foreach ($this->m_aTabs[$sTabContainer]['tabs'] as $sTabCode => $void) {
			if (preg_match($sPattern, $sTabCode)) {
				$result = $sTabCode;
				break;
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
	 */
	public function SelectTab($sTabContainer, $sTabCode)
	{
		$container_index = 0;
		$tab_index = 0;
		foreach ($this->m_aTabs as $sCurrentTabContainerName => $aTabs) {
			if ($sTabContainer == $sCurrentTabContainerName) {
				foreach ($aTabs['tabs'] as $sCurrentTabLabel => $void) {
					if ($sCurrentTabLabel == $sTabCode) {
						break;
					}
					$tab_index++;
				}
				break;
			}
			$container_index++;
		}
		$sSelector = '#tabbedContent_'.$container_index.' > ul';

		return "window.setTimeout(\"$('$sSelector').tabs('select', $tab_index);\", 100);"; // Let the time to the tabs widget to initialize
	}

	/**
	 * @param string $sContent
	 * @param \WebPage $oPage
	 *
	 * @return mixed
	 */
	public function RenderIntoContent($sContent, WebPage $oPage)
	{
		// Render the tabs in the page (if any)
		foreach ($this->m_aTabs as $sTabContainerName => $aTabs) {
			$sTabs = '';
			$sPrefix = $aTabs['prefix'];
			$container_index = 0;
			if (count($aTabs['tabs']) > 0) {
				// Clean tabs
				foreach ($aTabs['tabs'] as $sTabCode => $aTabData) {
					// Sometimes people set an empty tab to force content NOT to be rendered in the previous one. We need to remove them.
					// Note: Look for "->SetCurrentTab('');" for examples.
					if ($sTabCode === '') {
						unset($aTabs['tabs'][$sTabCode]);
					}
				}

				// Render tabs
				if ($oPage->IsPrintableVersion()) {
					$oPage->add_ready_script(
						<<< EOF
oHiddeableChapters = {};
EOF
					);
					$sTabs = "<!-- tabs -->\n<div id=\"tabbedContent_{$sPrefix}{$container_index}\" class=\"light\">\n";
					$i = 0;
					foreach ($aTabs['tabs'] as $sTabCode => $aTabData) {
						$sTabCodeForJs = addslashes($sTabCode);
						$sTabTitleForHtml = utils::HtmlEntities($aTabData['title']);
						$sTabId = "tab_{$sPrefix}{$container_index}$i";
						switch ($aTabData['type']) {
							case static::ENUM_TAB_TYPE_AJAX:
								$sTabHtml = '';
								$sUrl = $aTabData['url'];
								$oPage->add_ready_script(
									<<< EOF
$.post('$sUrl', {printable: '1'}, function(data){
	$('#$sTabId > .printable-tab-content').append(data);
});
EOF
								);
								break;

							case static::ENUM_TAB_TYPE_HTML:
							default:
								$sTabHtml = $aTabData['html'];
						}
						$sTabs .= "<div class=\"printable-tab\" id=\"$sTabId\"><h2 class=\"printable-tab-title\">$sTabTitleForHtml</h2><div class=\"printable-tab-content\">".$sTabHtml."</div></div>\n";
						$oPage->add_ready_script(
							<<< EOF
oHiddeableChapters['$sTabId'] = '$sTabTitleForHtml';
EOF
						);
						$i++;
					}
					$sTabs .= "</div>\n<!-- end of tabs-->\n";
				} else {
					$sTabs = "<!-- tabs -->\n<div id=\"tabbedContent_{$sPrefix}{$container_index}\" class=\"light\">\n";
					$sTabs .= "<ul>\n";
					// Display the unordered list that will be rendered as the tabs
					$i = 0;
					foreach ($aTabs['tabs'] as $sTabCode => $aTabData) {
						$sTabCodeForHtml = utils::HtmlEntities($sTabCode);
						$sTabTitleForHtml = utils::HtmlEntities($aTabData['title']);
						switch ($aTabData['type']) {
							case static::ENUM_TAB_TYPE_AJAX:
								$sTabs .= "<li data-cache=\"".($aTabData['cache'] ? 'true' : 'false')."\"><a href=\"{$aTabData['url']}\" class=\"tab\" data-tab-id=\"$sTabCodeForHtml\"><span>$sTabTitleForHtml</span></a></li>\n";
								break;

							case static::ENUM_TAB_TYPE_HTML:
							default:
								$sTabs .= "<li><a href=\"#tab_{$sPrefix}{$container_index}$i\" class=\"tab\" data-tab-id=\"$sTabCodeForHtml\"><span>$sTabTitleForHtml</span></a></li>\n";
						}
						$i++;
					}
					$sTabs .= "</ul>\n";
					// Now add the content of the tabs themselves
					$i = 0;
					foreach ($aTabs['tabs'] as $sTabCode => $aTabData) {
						switch ($aTabData['type']) {
							case static::ENUM_TAB_TYPE_AJAX:
								// Nothing to add
								break;

							case static::ENUM_TAB_TYPE_HTML:
							default:
								$sTabs .= "<div id=\"tab_{$sPrefix}{$container_index}$i\">".$aTabData['html']."</div>\n";
						}
						$i++;
					}
					$sTabs .= "</div>\n<!-- end of tabs-->\n";
				}
			}
			$sContent = str_replace("\$Tabs:$sTabContainerName\$", $sTabs, $sContent);
			$container_index++;
		}

		return $sContent;
	}

	/**
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 * @param string $sTabType
	 * @param string|null $sTabTitle
	 *
	 * @since 2.7.0
	 */
	protected function InitTab($sTabContainer, $sTabCode, $sTabType = self::DEFAULT_TAB_TYPE, $sTabTitle = null)
	{
		if (!$this->TabExists($sTabContainer, $sTabCode)) {
			// Container
			if (!array_key_exists($sTabContainer, $this->m_aTabs)) {
				$this->m_aTabs[$sTabContainer] = array(
					'prefix' => '',
					'tabs' => array(),
				);
			}

			// Common properties
			$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode] = array(
				'type' => $sTabType,
				'title' => ($sTabTitle !== null) ? Dict::S($sTabTitle) : Dict::S($sTabCode),
			);

			// Specific properties
			switch ($sTabType) {
				case static::ENUM_TAB_TYPE_AJAX:
					$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['url'] = null;
					$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['cache'] = null;
					break;

				case static::ENUM_TAB_TYPE_HTML:
				default:
					$this->m_aTabs[$sTabContainer]['tabs'][$sTabCode]['html'] = null;
					break;
			}
		}
	}
}
