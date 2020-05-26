<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/application/template.class.inc.php');
require_once(APPROOT."/application/user.dashboard.class.inc.php");


/**
 * This class manipulates, stores and displays the navigation menu used in the application
 * In order to improve the modularity of the data model and to ease the update/migration
 * between evolving data models, the menus are no longer stored in the database, but are instead
 * built on the fly each time a page is loaded.
 * The application's menu is organized into top-level groups with, inside each group, a tree of menu items.
 * Top level groups do not display any content, they just expand/collapse.
 * Sub-items drive the actual content of the page, they are based either on templates, OQL queries or full (external?) web pages.
 *
 * Example:
 * Here is how to insert the following items in the application's menu:
 *   +----------------------------------------+
 *   | Configuration Management Group         | >> Top level group
 *   +----------------------------------------+
 *		+ Configuration Management Overview     >> Template based menu item
 *		+ Contacts								>> Template based menu item
 *			+ Persons							>> Plain list (OQL based)
 *			+ Teams								>> Plain list (OQL based)
 *
 * // Create the top-level group. fRank = 1, means it will be inserted after the group '0', which is usually 'Welcome'
 * $oConfigMgmtMenu = new MenuGroup('ConfigurationManagementMenu', 1);
 * // Create an entry, based on a custom template, for the Configuration management overview, under the top-level group
 * new TemplateMenuNode('ConfigurationManagementMenu', '../somedirectory/configuration_management_menu.html', $oConfigMgmtMenu->GetIndex(), 0);
 * // Create an entry (template based) for the overview of contacts
 * $oContactsMenu = new TemplateMenuNode('ContactsMenu', '../somedirectory/configuration_management_menu.html',$oConfigMgmtMenu->GetIndex(), 1);
 * // Plain list of persons
 * new OQLMenuNode('PersonsMenu', 'SELECT bizPerson', $oContactsMenu->GetIndex(), 0);
 *
 */
 
class ApplicationMenu
{
	/**
	 * @var bool
	 */
	static $bAdditionalMenusLoaded = false;
	/**
	 * @var array
	 */
	static $aRootMenus = array();
	/**
	 * @var array
	 */
	static $aMenusIndex = array();
	/**
	 * @var string
	 */
	static $sFavoriteSiloQuery = 'SELECT Organization';

	static public function LoadAdditionalMenus()
	{
		if (!self::$bAdditionalMenusLoaded)
		{
			// Build menus from module handlers
			//
			/** @var \ModuleHandlerApiInterface $oPHPClass */
			foreach(MetaModel::EnumPlugins('ModuleHandlerApiInterface') as $oPHPClass)
            {
                $oPHPClass::OnMenuCreation();
            }

			// Build menus from the menus themselves (e.g. the ShortcutContainerMenuNode will do that)
			//
			foreach(self::$aRootMenus as $aMenu)
			{
				$oMenuNode = self::GetMenuNode($aMenu['index']);
				$oMenuNode->PopulateChildMenus();
			}

			self::$bAdditionalMenusLoaded = true;
		}
	}

	/**
	 * Set the query used to limit the list of displayed organizations in the drop-down menu
	 * @param $sOQL string The OQL query returning a list of Organization objects
	 * @return void
	 */
	static public function SetFavoriteSiloQuery($sOQL)
	{
		self::$sFavoriteSiloQuery = $sOQL;
	}
	
	/**
	 * Get the query used to limit the list of displayed organizations in the drop-down menu
	 * @return string The OQL query returning a list of Organization objects
	 */
	static public function GetFavoriteSiloQuery()
	{
		return self::$sFavoriteSiloQuery;
	}

	/**
	 * Check whether a menu Id is enabled or not
	 *
	 * @param $sMenuId
	 *
	 * @throws \Exception
	 */
	static public function CheckMenuIdEnabled($sMenuId)
	{
		self::LoadAdditionalMenus();
		$oMenuNode = self::GetMenuNode(self::GetMenuIndexById($sMenuId));
		if (is_null($oMenuNode) || !$oMenuNode->IsEnabled())
		{
			require_once(APPROOT.'/setup/setuppage.class.inc.php');
			$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
			$oP->add("<h1>".Dict::S('UI:Login:Error:AccessRestricted')."</h1>\n");
			$oP->p("<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/logoff.php\">".Dict::S('UI:LogOffMenu')."</a>");
			$oP->output();
			exit;
		}
	}

	/**
	 * Main function to add a menu entry into the application, can be called during the definition
	 * of the data model objects
	 * @param MenuNode $oMenuNode
	 * @param $iParentIndex
	 * @param $fRank
	 * @return int
	 */
	static public function InsertMenu(MenuNode $oMenuNode, $iParentIndex, $fRank)
	{
		$index = self::GetMenuIndexById($oMenuNode->GetMenuId());
		if ($index == -1)
		{
			// The menu does not already exist, insert it
			$index = count(self::$aMenusIndex);

			if ($iParentIndex == -1)
			{
				$sParentId = '';
				self::$aRootMenus[] = array ('rank' => $fRank, 'index' => $index);
			}
			else
			{
				/** @var \MenuNode $oNode */
				$oNode = self::$aMenusIndex[$iParentIndex]['node'];
				$sParentId = $oNode->GetMenuId();
				self::$aMenusIndex[$iParentIndex]['children'][] = array ('rank' => $fRank, 'index' => $index);
			}

			// Note: At the time when 'parent', 'rank' and 'source_file' have been added for the reflection API,
			//       they were not used to display the menus (redundant or unused)
			//
			$aBacktrace = debug_backtrace();
			$sFile = isset($aBacktrace[2]["file"]) ? $aBacktrace[2]["file"] : $aBacktrace[1]["file"];
			self::$aMenusIndex[$index] = array('node' => $oMenuNode, 'children' => array(), 'parent' => $sParentId, 'rank' => $fRank, 'source_file' => $sFile);
		}
		else
		{
			// the menu already exists, let's combine the conditions that make it visible
			/** @var \MenuNode $oNode */
			$oNode = self::$aMenusIndex[$index]['node'];
			$oNode->AddCondition($oMenuNode);
		}

		return $index;
	}

	/**
	 * Reflection API - Get menu entries
	 */
	static public function ReflectionMenuNodes()
	{
		self::LoadAdditionalMenus();
		return self::$aMenusIndex;
	}

	/**
	 * Entry point to display the whole menu into the web page, used by iTopWebPage
	 * @param \WebPage $oPage
	 * @param $aExtraParams
	 * @throws DictExceptionMissingString
	 */
	static public function DisplayMenu($oPage, $aExtraParams)
	{
		self::LoadAdditionalMenus();
		// Sort the root menu based on the rank
		usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
		$iAccordion = 0;
		$iActiveAccordion = $iAccordion;
		$iActiveMenu = self::GetMenuIndexById(self::GetActiveNodeId());
		foreach(self::$aRootMenus as $aMenu)
		{
			if (!self::CanDisplayMenu($aMenu)) { continue; }
			$oMenuNode = self::GetMenuNode($aMenu['index']);
			$oPage->AddToMenu('<h3 id="'.utils::GetSafeId('AccordionMenu_'.$oMenuNode->GetMenuID()).'" class="navigation-menu-group" data-menu-id="'.$oMenuNode->GetMenuId().'">'.$oMenuNode->GetTitle().'</h3>');
			$oPage->AddToMenu('<div>');
			$oPage->AddToMenu('<ul>');
			$aChildren = self::GetChildren($aMenu['index']);
			$bActive = self::DisplaySubMenu($oPage, $aChildren, $aExtraParams, $iActiveMenu);
			$oPage->AddToMenu('</ul>');
			if ($bActive)
			{
				$iActiveAccordion = $iAccordion;
			}
			$oPage->AddToMenu('</div>');
			$iAccordion++;
		}

		$oPage->add_ready_script(
<<<EOF
	// Accordion Menu
	$("#accordion").css({display:'block'}).accordion({ header: "h3", heightStyle: "content", collapsible: true,  active: $iActiveAccordion, icons: false, animate: true }); // collapsible will be enabled once the item will be selected
EOF
		);
	}

	/**
	 * Recursively check if the menu and at least one of his sub-menu is enabled
	 * @param array $aMenu menu entry
	 * @return bool true if at least one menu is enabled
	 */
	static private function CanDisplayMenu($aMenu)
	{
		$oMenuNode = self::GetMenuNode($aMenu['index']);
		if ($oMenuNode->IsEnabled())
		{
			$aChildren = self::GetChildren($aMenu['index']);
			if (count($aChildren) > 0)
			{
				foreach($aChildren as $aSubMenu)
				{
					if (self::CanDisplayMenu($aSubMenu))
					{
						return true;
					}
				}
			}
			else
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Handles the display of the sub-menus (called recursively if necessary)
	 *
	 * @param \WebPage $oPage
	 * @param array $aMenus
	 * @param array $aExtraParams
	 * @param int $iActiveMenu
	 *
	 * @return true if the currently selected menu is one of the submenus
	 * @throws DictExceptionMissingString
	 * @throws \Exception
	 */
	static protected function DisplaySubMenu($oPage, $aMenus, $aExtraParams, $iActiveMenu = -1)
	{
		// Sort the menu based on the rank
		$bActive = false;
		usort($aMenus, array('ApplicationMenu', 'CompareOnRank'));
		foreach($aMenus as $aMenu)
		{
			if (!self::CanDisplayMenu($aMenu))
			{
				continue;
			}
			$index = $aMenu['index'];
			$oMenu = self::GetMenuNode($index);
			if ($oMenu->IsEnabled())
			{
				$aChildren = self::GetChildren($index);
				$aCSSClasses = array('navigation-menu-item');
				if (count($aChildren) > 0)
				{
					$aCSSClasses[] = 'submenu';
				}
				$sHyperlink = $oMenu->GetHyperlink($aExtraParams);
				$sItemHtml = '<li id="'.utils::GetSafeId('AccordionMenu_'.$oMenu->GetMenuID()).'" class="'.implode(' ', $aCSSClasses).'" data-menu-id="'.$oMenu->GetMenuID().'">';
				if ($sHyperlink != '')
				{
					$sLinkTarget = '';
					if ($oMenu->IsHyperLinkInNewWindow())
					{
						$sLinkTarget .= ' target="_blank"';
					}
					$sURL = '"'.$oMenu->GetHyperlink($aExtraParams).'"'.$sLinkTarget;
					$sTitle = utils::HtmlEntities($oMenu->GetTitle());
					$sItemHtml .= "<a href={$sURL}>{$sTitle}</a>";
				}
				else
				{
					$sItemHtml .= $oMenu->GetTitle();
				}
				$sItemHtml .= '</li>';
				$oPage->AddToMenu($sItemHtml);
				if ($iActiveMenu == $index)
				{
					$bActive = true;
				}
				if (count($aChildren) > 0)
				{
					$oPage->AddToMenu('<ul>');
					$bActive |= self::DisplaySubMenu($oPage, $aChildren, $aExtraParams, $iActiveMenu);
					$oPage->AddToMenu('</ul>');
				}
			}
		}
		return $bActive;
	}

	/**
	 * Helper function to sort the menus based on their rank
	 * @param $a
	 * @param $b
	 * @return int
	 */
	static public function CompareOnRank($a, $b)
	{
		$result = 1;
		if ($a['rank'] == $b['rank'])
		{
			$result = 0;
		}
		if ($a['rank'] < $b['rank'])
		{
			$result = -1;
		}
		return $result;
	}

	/**
	 * Helper function to retrieve the MenuNode Object based on its ID
	 * @param int $index
	 * @return MenuNode|null
	 */
	static public function GetMenuNode($index)
	{
		return isset(self::$aMenusIndex[$index]) ? self::$aMenusIndex[$index]['node'] : null;
	}

	/**
	 * Helper function to get the list of child(ren) of a menu
	 * @param int $index
	 * @return array
	 */
	static public function GetChildren($index)
	{
		return self::$aMenusIndex[$index]['children'];
	}

	/**
	 * Helper function to get the ID of a menu based on its name
	 * @param string $sTitle Title of the menu (as passed when creating the menu)
	 * @return integer ID of the menu, or -1 if not found
	 */
	static public function GetMenuIndexById($sTitle)
	{
		$index = -1;
		/** @var MenuNode[] $aMenu */
		foreach(self::$aMenusIndex as $aMenu)
		{
			if ($aMenu['node']->GetMenuId() == $sTitle)
			{
				$index = $aMenu['node']->GetIndex();
				break;
			}
		}
		return $index;
	}
	
	/**
	 * Retrieves the currently active menu (if any, otherwise the first menu is the default)
	 * @return string The Id of the currently active menu
	 */
	static public function GetActiveNodeId()
	{
		$oAppContext = new ApplicationContext();
		$sMenuId = $oAppContext->GetCurrentValue('menu', null);		
		if ($sMenuId  === null)
		{
			$sMenuId = self::GetDefaultMenuId();
		}
		return $sMenuId;
	}

	/**
	 * @return null|string
	 */
	static public function GetDefaultMenuId()
	{
		static $sDefaultMenuId = null;
		if (is_null($sDefaultMenuId))
		{
			// Make sure the root menu is sorted on 'rank'
			usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
			$oFirstGroup = self::GetMenuNode(self::$aRootMenus[0]['index']);
			$aChildren = self::$aMenusIndex[$oFirstGroup->GetIndex()]['children'];
			usort($aChildren, array('ApplicationMenu', 'CompareOnRank'));
			$oMenuNode = self::GetMenuNode($aChildren[0]['index']);
			$sDefaultMenuId = $oMenuNode->GetMenuId();
		}
		return $sDefaultMenuId;
	}

	/**
	 * @param $sMenuId
	 * @return string
	 */
	static public function GetRootMenuId($sMenuId)
	{
		$iMenuIndex = self::GetMenuIndexById($sMenuId);
		if ($iMenuIndex == -1)
		{
			return '';
		}
		$oMenu = ApplicationMenu::GetMenuNode($iMenuIndex);
		while ($oMenu->GetParentIndex() != -1)
		{
			$oMenu = ApplicationMenu::GetMenuNode($oMenu->GetParentIndex());
		}
		return $oMenu->GetMenuId();
	}
}

/**
 * Root class for all the kind of node in the menu tree, data model providers are responsible for instantiating
 * MenuNodes (i.e instances from derived classes) in order to populate the application's menu. Creating an objet
 * derived from MenuNode is enough to have it inserted in the application's main menu.
 * The class iTopWebPage, takes care of 3 items:
 * +--------------------+
 * | Welcome            |
 * +--------------------+
 * 		Welcome To iTop
 * +--------------------+
 * | Tools              |
 * +--------------------+
 * 		CSV Import
 * +--------------------+
 * | Admin Tools        |
 * +--------------------+
 *		User Accounts
 *		Profiles
 *		Notifications
 *		Run Queries
 *		Export
 *		Data Model
 *		Universal Search
 *
 * All the other menu items must constructed along with the various data model modules
 */
abstract class MenuNode
{
	/**
	 * @var string
	 */
	protected $sMenuId;
	/**
	 * @var int
	 */
	protected $index;
	/**
	 * @var int
	 */
	protected $iParentIndex;

	/**
	 * Properties reflecting how the node has been declared
	 */
	protected $aReflectionProperties;

	/**
	 * Class of objects to check if the menu is enabled, null if none
	 */
	protected $m_aEnableClasses;

	/**
	 * User Rights Action code to check if the menu is enabled, null if none
	 */
	protected $m_aEnableActions;

	/**
	 * User Rights allowed results (actually a bitmask) to check if the menu is enabled, null if none
	 */
	protected $m_aEnableActionResults;

	/**
	 * Stimulus to check: if the user can 'apply' this stimulus, then she/he can see this menu
	 */	
	protected $m_aEnableStimuli;

	/**
	 * Create a menu item, sets the condition to have it displayed and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param integer $iParentIndex ID of the parent menu, pass -1 for top level (group) items
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param mixed $iActionCode UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus The user can see this menu if she/he has enough rights to apply this stimulus
	 */
	public function __construct($sMenuId, $iParentIndex = -1, $fRank = 0.0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		$this->sMenuId = $sMenuId;
		$this->iParentIndex = $iParentIndex;
		$this->aReflectionProperties = array();
		if (strlen($sEnableClass) > 0)
		{
			$this->aReflectionProperties['enable_class'] = $sEnableClass;
			$this->aReflectionProperties['enable_action'] = $iActionCode;
			$this->aReflectionProperties['enable_permission'] = $iAllowedResults;
			$this->aReflectionProperties['enable_stimulus'] = $sEnableStimulus;
		}
		$this->m_aEnableClasses = array($sEnableClass);
		$this->m_aEnableActions = array($iActionCode);
		$this->m_aEnableActionResults = array($iAllowedResults);
		$this->m_aEnableStimuli = array($sEnableStimulus);
		$this->index = ApplicationMenu::InsertMenu($this, $iParentIndex, $fRank);
	}

	/**
	 * @return array
	 */
	public function ReflectionProperties()
	{
		return $this->aReflectionProperties;
	}

	/**
	 * @return string
	 */
	public function GetMenuId()
	{
		return $this->sMenuId;
	}

	/**
	 * @return int
	 */
	public function GetParentIndex()
	{
		return $this->iParentIndex;
	}

	/**
	 * @return string
	 * @throws DictExceptionMissingString
	 */
	public function GetTitle()
	{
		return Dict::S("Menu:$this->sMenuId", str_replace('_', ' ', $this->sMenuId));
	}

	/**
	 * @return string
	 * @throws DictExceptionMissingString
	 */
	public function GetLabel()
	{
		$sRet = Dict::S("Menu:$this->sMenuId+", "");
		if ($sRet === '')
		{
			if ($this->iParentIndex != -1)
			{
				$oParentMenu = ApplicationMenu::GetMenuNode($this->iParentIndex);
				$sRet = $oParentMenu->GetTitle().' / '.$this->GetTitle();
			}
			else
			{
				$sRet = $this->GetTitle();
			}
			//$sRet = $this->GetTitle();
		}
		return $sRet;
	}

	/**
	 * @return int
	 */
	public function GetIndex()
	{
		return $this->index;
	}
	
	public function PopulateChildMenus()
	{
		foreach (ApplicationMenu::GetChildren($this->GetIndex()) as $aMenu)
		{
			$index = $aMenu['index'];
			$oMenu = ApplicationMenu::GetMenuNode($index);
			$oMenu->PopulateChildMenus();
		}
	}

	/**
	 * @param $aExtraParams
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams(utils::GetAbsoluteUrlAppRoot().'pages/UI.php', $aExtraParams);
	}

	/**
	 * @return bool true if the link should be opened in a new window
	 * @since 2.7.0 N°1283
	 */
	public function IsHyperLinkInNewWindow()
	{
		return false;
	}
	
	/**
	 * Add a limiting display condition for the same menu node. The conditions will be combined with a AND
	 * @param $oMenuNode MenuNode Another definition of the same menu node, with potentially different access restriction
	 * @return void
	 */
	public function AddCondition(MenuNode $oMenuNode)
	{
		foreach($oMenuNode->m_aEnableClasses as $index => $sClass )
		{
			$this->m_aEnableClasses[] = $sClass;
			$this->m_aEnableActions[] = $oMenuNode->m_aEnableActions[$index];
			$this->m_aEnableActionResults[] = $oMenuNode->m_aEnableActionResults[$index];
			$this->m_aEnableStimuli[] = $oMenuNode->m_aEnableStimuli[$index];
		}
	}
	/**
	 * Tells whether the menu is enabled (i.e. displayed) for the current user
	 * @return bool True if enabled, false otherwise
	 */
	public function IsEnabled()
	{
		foreach($this->m_aEnableClasses as $index => $sClass)
		{
			if ($sClass != null)
			{
				if (MetaModel::IsValidClass($sClass))
				{
					if ($this->m_aEnableStimuli[$index] != null)
					{
						if (!UserRights::IsStimulusAllowed($sClass, $this->m_aEnableStimuli[$index]))
						{
							return false;
						}
					}
					if ($this->m_aEnableActions[$index] != null)
					{
						// Menus access rights ignore the archive mode
						utils::PushArchiveMode(false);
						$iResult = UserRights::IsActionAllowed($sClass, $this->m_aEnableActions[$index]);
						utils::PopArchiveMode();
						if (!($iResult & $this->m_aEnableActionResults[$index]))
						{
							return false;
						}
					}
				}
				else
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 * @return mixed
	 */
	public abstract function RenderContent(WebPage $oPage, $aExtraParams = array());

	/**
	 * @param string $sHyperlink
	 * @param array $aExtraParams
	 * @return string
	 */
	protected function AddParams($sHyperlink, $aExtraParams)
	{
		if (count($aExtraParams) > 0)
		{
			$aQuery = array();
			$sSeparator = '?';
			if (strpos($sHyperlink, '?') !== false)
			{
				$sSeparator = '&';
			}
			foreach($aExtraParams as $sName => $sValue)
			{
				$aQuery[] = urlencode($sName).'='.urlencode($sValue);
			}
			$sHyperlink .= $sSeparator.implode('&', $aQuery);
		}
		return $sHyperlink;
	}
}

/**
 * This class implements a top-level menu group. A group is just a container for sub-items
 * it does not display a page by itself
 */
class MenuGroup extends MenuNode
{
	/**
	 * Create a top-level menu group and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param float $fRank Number used to order the list, the groups are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $fRank, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, -1 /* no parent, groups are at root level */, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
	}

	/**
	 * @inheritDoc
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		assert(false); // Shall never be called, groups do not display any content
	}
}

/**
 * This class defines a menu item which content is based on a custom template.
 * Note the template can be either a local file or an URL !
 */
class TemplateMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sTemplateFile;
	
	/**
	 * Create a menu item based on a custom template and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sTemplateFile Path (or URL) to the file that will be used as a template for displaying the page's content
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $sTemplateFile, $iParentIndex, $fRank = 0.0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sTemplateFile = $sTemplateFile;
		$this->aReflectionProperties['template_file'] = $sTemplateFile;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		if ($this->sTemplateFile == '') return '';
		return parent::GetHyperlink($aExtraParams);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		ApplicationMenu::CheckMenuIdEnabled($this->GetMenuId());
		$sTemplate = @file_get_contents($this->sTemplateFile);
		if ($sTemplate !== false)
		{
			$aExtraParams['table_id'] = 'Menu_'.$this->GetMenuId();
			$oTemplate = new DisplayTemplate($sTemplate);
			$oTemplate->Render($oPage, $aExtraParams);
		}
		else
		{
			$oPage->p("Error: failed to load template file: '{$this->sTemplateFile}'"); // No need to translate ?
		}
	}
}

/**
 * This class defines a menu item that uses a standard template to display a list of items therefore it allows
 * only two parameters: the page's title and the OQL expression defining the list of items to be displayed
 */
class OQLMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sPageTitle;
	/**
	 * @var string
	 */
	protected $sOQL;
	/**
	 * @var bool
	 */
	protected $bSearch;
	/**
	 * @var bool|null
	 */
	protected $bSearchFormOpen;
	
	/**
	 * Extra parameters to be passed to the display block to fine tune its appearence
	 */
	protected $m_aParams;


	/**
	 * Create a menu item based on an OQL query and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sOQL OQL query defining the set of objects to be displayed
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param bool $bSearch Whether or not to display a (collapsed) search frame at the top of the page
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 * @param bool $bSearchFormOpen
	 */
	public function __construct($sMenuId, $sOQL, $iParentIndex, $fRank = 0.0, $bSearch = false, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null, $bSearchFormOpen = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sPageTitle = "Menu:$sMenuId+";
		$this->sOQL = $sOQL;
		$this->bSearch = $bSearch;
		$this->bSearchFormOpen = $bSearchFormOpen;
		$this->m_aParams = array();
		$this->aReflectionProperties['oql'] = $sOQL;
		$this->aReflectionProperties['do_search'] = $bSearch;
		// Enhancement: we could set as the "enable" condition that the user has enough rights to "read" the objects
		// of the class specified by the OQL...
	}
	
	/**
	 * Set some extra parameters to be passed to the display block to fine tune its appearence
	 * @param array $aParams paramCode => value. See DisplayBlock::GetDisplay for the meaning of the parameters
	 */
	public function SetParameters($aParams)
	{
		$this->m_aParams = $aParams;
		foreach($aParams as $sKey => $value)
		{
			$this->aReflectionProperties[$sKey] = $value;
		}
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		ApplicationMenu::CheckMenuIdEnabled($this->GetMenuId());
		OQLMenuNode::RenderOQLSearch
		(
			$this->sOQL,
			Dict::S($this->sPageTitle),
			'Menu_'.$this->GetMenuId(),
			$this->bSearch, // Search pane
			$this->bSearchFormOpen, // Search open
			$oPage, 
			array_merge($this->m_aParams, $aExtraParams),
			true
		);
	}

	/**
	 * @param string $sOql
	 * @param string $sTitle
	 * @param string $sUsageId
	 * @param bool $bSearchPane
	 * @param bool $bSearchOpen
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 * @param bool $bEnableBreadcrumb
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws OQLException
	 */
	public static function RenderOQLSearch($sOql, $sTitle, $sUsageId, $bSearchPane, $bSearchOpen, WebPage $oPage, $aExtraParams = array(), $bEnableBreadcrumb = false)
	{
		$sUsageId = utils::GetSafeId($sUsageId);
		$oSearch = DBObjectSearch::FromOQL($sOql);
		$sIcon = MetaModel::GetClassIcon($oSearch->GetClass());

		if ($bSearchPane)
		{
			$aParams = array_merge(array('open' => $bSearchOpen, 'table_id' => $sUsageId), $aExtraParams);
			$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, $aParams);
			$oBlock->Display($oPage, 0);
		}
		
		$oPage->add("<p class=\"page-header\">$sIcon ".utils::HtmlEntities(Dict::S($sTitle))."</p>");
		
		$aParams = array_merge(array('table_id' => $sUsageId), $aExtraParams);
		$oBlock = new DisplayBlock($oSearch, 'list', false /* Asynchronous */, $aParams);
		$oBlock->Display($oPage, $sUsageId);

		if ($bEnableBreadcrumb && ($oPage instanceof iTopWebPage))
		{
			// Breadcrumb
			//$iCount = $oBlock->GetDisplayedCount();
			$sPageId = "ui-search-".$oSearch->GetClass();
			$sLabel = MetaModel::GetName($oSearch->GetClass());
			$oPage->SetBreadCrumbEntry($sPageId, $sLabel, $sTitle, '', '../images/breadcrumb-search.png');
		}
	}
}

/**
 * This class defines a menu item that displays a search form for the given class of objects
 */
class SearchMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sPageTitle;
	/**
	 * @var string
	 */
	protected $sClass;

	/**
	 * Create a menu item based on an OQL query and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sClass The class of objects to search for
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param bool $bSearch (not used)
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $sClass, $iParentIndex, $fRank = 0.0, $bSearch = false, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sPageTitle = "Menu:$sMenuId+";
		$this->sClass = $sClass;
		$this->aReflectionProperties['class'] = $sClass;
	}

	/**
	 * @inheritDoc
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		ApplicationMenu::CheckMenuIdEnabled($this->GetMenuId());
		$oPage->SetBreadCrumbEntry("menu-".$this->sMenuId, $this->GetTitle(), '', '', utils::GetAbsoluteUrlAppRoot().'images/breadcrumb-search.png');

		$oSearch = new DBObjectSearch($this->sClass);
		$aParams = array_merge(array('table_id' => 'Menu_'.utils::GetSafeId($this->GetMenuId())), $aExtraParams);
		$oBlock = new DisplayBlock($oSearch, 'search', false /* Asynchronous */, $aParams);
		$oBlock->Display($oPage, 0);
	}
}

/**
 * This class defines a menu that points to any web page. It takes only two parameters:
 * - The hyperlink to point to
 * - The name of the menu
 * Note: the parameter menu=xxx (where xxx is the id of the menu itself) will be added to the hyperlink
 * in order to make it the active one, if the target page is based on iTopWebPage and therefore displays the menu
 */
class WebPageMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sHyperlink;

	/** @var bool */
	protected $bIsLinkInNewWindow;

	/**
	 * Create a menu item that points to any web page (not only UI.php)
	 *
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sHyperlink URL to the page to load. Use relative URL if you want to keep the application portable !
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 * @param bool $bIsLinkInNewWindow for the {@link WebPageMenuNode::IsHyperLinkInNewWindow} method
	 */
	public function __construct(
		$sMenuId, $sHyperlink, $iParentIndex, $fRank = 0.0, $sEnableClass = null, $iActionCode = null,
		$iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null, $bIsLinkInNewWindow = false
	)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sHyperlink = $sHyperlink;
		$this->aReflectionProperties['url'] = $sHyperlink;
		$this->bIsLinkInNewWindow = $bIsLinkInNewWindow;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams( $this->sHyperlink, $aExtraParams);
	}

	/**
	 * @inheritDoc
	 */
	public function IsHyperLinkInNewWindow()
	{
		return $this->bIsLinkInNewWindow;
	}

	/**
	 * @inheritDoc
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		assert(false); // Shall never be called, the external web page will handle the display by itself
	}
}

/**
 * This class defines a menu that points to the page for creating a new object of the specified class.
 * It take only one parameter: the name of the class
 * Note: the parameter menu=xxx (where xxx is the id of the menu itself) will be added to the hyperlink
 * in order to make it the active one
 */
class NewObjectMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sClass;

	/**
	 * Create a menu item that points to the URL for creating a new object, the menu will be added only if the current user has enough
	 * rights to create such an object (or an object of a child class)
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sClass URL to the page to load. Use relative URL if you want to keep the application portable !
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass
	 * @param int|null $iActionCode
	 * @param int $iAllowedResults
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $sClass, $iParentIndex, $fRank = 0.0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sClass = $sClass;
		$this->aReflectionProperties['class'] = $sClass;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class='.$this->sClass;
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams($sHyperlink, $aExtraParams);
	}

	/**
	 * Overload the check of the "enable" state of this menu to take into account
	 * derived classes of objects
	 * @throws CoreException
	 */
	public function IsEnabled()
	{
		// Enable this menu, only if the current user has enough rights to create such an object, or an object of
		// any child class
	
		$aSubClasses = MetaModel::EnumChildClasses($this->sClass, ENUM_CHILD_CLASSES_ALL); // Including the specified class itself
		$bActionIsAllowed = false;
	
		foreach($aSubClasses as $sCandidateClass)
		{
			if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
			{
				$bActionIsAllowed = true;
				break; // Enough for now
			}
		}
		return $bActionIsAllowed;		
	}

	/**
	 * @inheritDoc
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		assert(false); // Shall never be called, the external web page will handle the display by itself
	}
}

require_once(APPROOT.'application/dashboard.class.inc.php');
/**
 * This class defines a menu item which content is based on XML dashboard.
 */
class DashboardMenuNode extends MenuNode
{
	/**
	 * @var string
	 */
	protected $sDashboardFile;

	/**
	 * Create a menu item based on a custom template and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sDashboardFile
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $sDashboardFile, $iParentIndex, $fRank = 0.0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sDashboardFile = $sDashboardFile;
		$this->aReflectionProperties['definition_file'] = $sDashboardFile;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		if ($this->sDashboardFile == '') return '';
		return parent::GetHyperlink($aExtraParams);
	}

	/**
	 * @return null|RuntimeDashboard
	 * @throws CoreException
	 * @throws Exception
	 */
	public function GetDashboard()
	{
		return RuntimeDashboard::GetDashboard($this->sDashboardFile, $this->sMenuId);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		ApplicationMenu::CheckMenuIdEnabled($this->GetMenuId());
		$oDashboard = $this->GetDashboard();
		if ($oDashboard != null)
		{
			$sDivId = utils::Sanitize($this->sMenuId, '', 'element_identifier');
			$oPage->add('<div class="dashboard_contents" id="'.$sDivId.'">');
			$aExtraParams['dashboard_div_id'] = $sDivId;
			$oDashboard->SetReloadURL($this->GetHyperlink($aExtraParams));
			$oDashboard->Render($oPage, false, $aExtraParams);
			$oPage->add('</div>');

			$bEdit = utils::ReadParam('edit', false);
			if ($bEdit)
			{
				$sId = addslashes($this->sMenuId);
				$oPage->add_ready_script("EditDashboard('$sId');");
			}
			else
			{
				$oParentMenu = ApplicationMenu::GetMenuNode($this->iParentIndex);
				$sParentTitle = $oParentMenu->GetTitle();
				$sThisTitle = $this->GetTitle();
				if ($sParentTitle != $sThisTitle)
				{
					$sDescription = $sParentTitle.' / '.$sThisTitle;
				}
				else
				{
					$sDescription = $sThisTitle;
				}
				if ($this->sMenuId == ApplicationMenu::GetDefaultMenuId())
				{
					$sIcon = '../images/breadcrumb_home.png';
				}
				else
				{
					$sIcon = '../images/breadcrumb-dashboard.png';
				}
				$oPage->SetBreadCrumbEntry("ui-dashboard-".$this->sMenuId, $this->GetTitle(), $sDescription, '', $sIcon);
			}
		}
		else
		{
			$oPage->p("Error: failed to load dashboard file: '{$this->sDashboardFile}'");
		}
	}

	/**
	 * @param WebPage $oPage
	 * @throws CoreException
	 * @throws Exception
	 */
	public function RenderEditor(WebPage $oPage)
	{
		$oDashboard = $this->GetDashboard();
		if ($oDashboard != null)
		{
			$oDashboard->RenderEditor($oPage);
		}
		else
		{
			$oPage->p("Error: failed to load dashboard file: '{$this->sDashboardFile}'");
		}
	}

	/**
	 * @param $oDashlet
	 * @throws Exception
	 */
	public function AddDashlet($oDashlet)
	{
		$oDashboard = $this->GetDashboard();
		if ($oDashboard != null)
		{
			$oDashboard->AddDashlet($oDashlet);
			$oDashboard->Save();
		}
		else
		{
			throw new Exception("Error: failed to load dashboard file: '{$this->sDashboardFile}'");
		}
	}
	
}

/**
 * A shortcut container is the preferred destination of newly created shortcuts
 */
class ShortcutContainerMenuNode extends MenuNode
{
	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
	}

	/**
	 * @inheritDoc
	 * @throws CoreException
	 * @throws Exception
	 */
	public function PopulateChildMenus()
	{
		// Load user shortcuts in DB
		//
		$oBMSearch = new DBObjectSearch('Shortcut');
		$oBMSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
		$oBMSet = new DBObjectSet($oBMSearch, array('friendlyname' => true)); // ascending on friendlyname
		$fRank = 1;
		while ($oShortcut = $oBMSet->Fetch())
		{
			$sName = $this->GetMenuId().'_'.$oShortcut->GetKey();
			new ShortcutMenuNode($sName, $oShortcut, $this->GetIndex(), $fRank++);
		}
	
		// Complete the tree
		//
		parent::PopulateChildMenus();
	}
}


require_once(APPROOT.'application/shortcut.class.inc.php');
/**
 * This class defines a menu item which content is a shortcut.
 */
class ShortcutMenuNode extends MenuNode
{
	/**
	 * @var Shortcut
	 */
	protected $oShortcut;

	/**
	 * Create a menu item based on a custom template and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param object $oShortcut Shortcut object
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @param string $sEnableStimulus
	 */
	public function __construct($sMenuId, $oShortcut, $iParentIndex, $fRank = 0.0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->oShortcut = $oShortcut;
		$this->aReflectionProperties['shortcut'] = $oShortcut->GetKey();
	}

	/**
	 * @inheritDoc
	 */
	public function GetHyperlink($aExtraParams)
	{
		$sContext = $this->oShortcut->Get('context');
		$aContext = unserialize($sContext);
		if (isset($aContext['menu']))
		{
			unset($aContext['menu']);
		}
		foreach ($aContext as $sArgName => $sArgValue)
		{
			$aExtraParams[$sArgName] = $sArgValue;
		}
		return parent::GetHyperlink($aExtraParams);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		ApplicationMenu::CheckMenuIdEnabled($this->GetMenuId());
		$this->oShortcut->RenderContent($oPage, $aExtraParams);
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \Exception
	 */
	public function GetTitle()
	{
		return $this->oShortcut->Get('name');
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \Exception
	 */
	public function GetLabel()
	{
		return $this->oShortcut->Get('name');
	}
}

