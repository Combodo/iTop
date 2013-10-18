<?php
// Copyright (C) 2010-2013 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Construction and display of the application's main menu
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
	static $bAdditionalMenusLoaded = false;
	static $aRootMenus = array();
	static $aMenusIndex = array();
	static $sFavoriteSiloQuery = 'SELECT Organization';
	
	static public function LoadAdditionalMenus()
	{
		if (!self::$bAdditionalMenusLoaded)
		{
			// Build menus from module handlers
			//
			foreach(get_declared_classes() as $sPHPClass)
			{
				if (is_subclass_of($sPHPClass, 'ModuleHandlerAPI'))
				{
					$aCallSpec = array($sPHPClass, 'OnMenuCreation');
					call_user_func($aCallSpec);
				}
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
	 * @return none
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
	 * Main function to add a menu entry into the application, can be called during the definition
	 * of the data model objects
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
				$sParentId = self::$aMenusIndex[$iParentIndex]['node']->GetMenuId();
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
			self::$aMenusIndex[$index]['node']->AddCondition($oMenuNode);
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
	 */
	static public function DisplayMenu($oPage, $aExtraParams)
	{
		self::LoadAdditionalMenus();
		// Sort the root menu based on the rank
		usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
		$iAccordion = 0;
		$iActiveMenu = self::GetMenuIndexById(self::GetActiveNodeId());
		foreach(self::$aRootMenus as $aMenu)
		{
			$oMenuNode = self::GetMenuNode($aMenu['index']);
			if (!$oMenuNode->IsEnabled()) continue; // Don't display a non-enabled menu
			$oPage->AddToMenu('<h3>'.$oMenuNode->GetTitle().'</h3>');
			$oPage->AddToMenu('<div>');
			$aChildren = self::GetChildren($aMenu['index']);
			if (count($aChildren) > 0)
			{
				$oPage->AddToMenu('<ul>');
				$bActive = self::DisplaySubMenu($oPage, $aChildren, $aExtraParams, $iActiveMenu);
				$oPage->AddToMenu('</ul>');
				if ($bActive)
				{
					//$oPage->add_ready_script("$('#accordion').accordion('activate', $iAccordion);");
					// $oPage->add_ready_script("$('#accordion').accordion('option', {collapsible: true});"); // Make it auto-collapsible once it has been opened properly
					$oPage->add_ready_script("$('#accordion').accordion('option', {collapsible: true, active: $iAccordion});"); // Make it auto-collapsible once it has been opened properly
				}
			}
			$oPage->AddToMenu('</div>');
			$iAccordion++;
		}
	}
	
	/**
	 * Handles the display of the sub-menus (called recursively if necessary)
	 * @return true if the currently selected menu is one of the submenus
	 */
	static protected function DisplaySubMenu($oPage, $aMenus, $aExtraParams, $iActiveMenu = -1)
	{
		// Sort the menu based on the rank
		$bActive = false;
		usort($aMenus, array('ApplicationMenu', 'CompareOnRank'));
		foreach($aMenus as $aMenu)
		{
			$index = $aMenu['index'];
			$oMenu = self::GetMenuNode($index);
			if ($oMenu->IsEnabled())
			{
				$aChildren = self::GetChildren($index);
				$sCSSClass = (count($aChildren) > 0) ? ' class="submenu"' : '';
				$sHyperlink = $oMenu->GetHyperlink($aExtraParams);
				if ($sHyperlink != '')
				{
					$oPage->AddToMenu('<li'.$sCSSClass.'><a href="'.$oMenu->GetHyperlink($aExtraParams).'">'.$oMenu->GetTitle().'</a></li>');
				}
				else
				{
					$oPage->AddToMenu('<li'.$sCSSClass.'>'.$oMenu->GetTitle().'</li>');
				}
				$aCurrentMenu = self::$aMenusIndex[$index];
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
	 * Helper function to retrieve the MenuNodeObject based on its ID
	 */
	static public function GetMenuNode($index)
	{
		return isset(self::$aMenusIndex[$index]) ? self::$aMenusIndex[$index]['node'] : null;
	}
	
	/**
	 * Helper function to get the list of child(ren) of a menu
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
			// Make sure the root menu is sorted on 'rank'
			usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
			$oFirstGroup = self::GetMenuNode(self::$aRootMenus[0]['index']);
			$aChildren = self::$aMenusIndex[$oFirstGroup->GetIndex()]['children'];
			usort($aChildren, array('ApplicationMenu', 'CompareOnRank'));
			$oMenuNode = self::GetMenuNode($aChildren[0]['index']);
			$sMenuId = $oMenuNode->GetMenuId();
		}
		return $sMenuId;
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
	protected $sMenuId;
	protected $index;

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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $iParentIndex = -1, $fRank = 0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		$this->sMenuId = $sMenuId;
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

	public function ReflectionProperties()
	{
		return $this->aReflectionProperties;
	}
	
	public function GetMenuId()
	{
		return $this->sMenuId;
	}
	
	public function GetTitle()
	{
		return Dict::S("Menu:$this->sMenuId", str_replace('_', ' ', $this->sMenuId));
	}
	
	public function GetLabel()
	{
		return Dict::S("Menu:$this->sMenuId+", "");
	}
	
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

	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams(utils::GetAbsoluteUrlAppRoot().'pages/UI.php', $aExtraParams);
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
						$iResult = UserRights::IsActionAllowed($sClass, $this->m_aEnableActions[$index]);
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
	
	public abstract function RenderContent(WebPage $oPage, $aExtraParams = array());
	
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
	 * @return MenuGroup
	 */
	public function __construct($sMenuId, $fRank, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, -1 /* no parent, groups are at root level */, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
	}
	
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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sTemplateFile, $iParentIndex, $fRank = 0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sTemplateFile = $sTemplateFile;
		$this->aReflectionProperties['template_file'] = $sTemplateFile;
	}
	
	public function GetHyperlink($aExtraParams)
	{
		if ($this->sTemplateFile == '') return '';
		return parent::GetHyperlink($aExtraParams);
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
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
	protected $sPageTitle;
	protected $sOQL;
	protected $bSearch;
	
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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sOQL, $iParentIndex, $fRank = 0, $bSearch = false, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sPageTitle = "Menu:$sMenuId+";
		$this->sOQL = $sOQL;
		$this->bSearch = $bSearch;
		$this->m_aParams = array();
		$this->aReflectionProperties['oql'] = $sOQL;
		$this->aReflectionProperties['do_search'] = $bSearch;
		// Enhancement: we could set as the "enable" condition that the user has enough rights to "read" the objects
		// of the class specified by the OQL...
	}
	
	/**
	 * Set some extra parameters to be passed to the display block to fine tune its appearence
	 * @param Hash $aParams paramCode => value. See DisplayBlock::GetDisplay for the meaning of the parameters
	 */
	public function SetParameters($aParams)
	{
		$this->m_aParams = $aParams;
		foreach($aParams as $sKey => $value)
		{
			$this->aReflectionProperties[$sKey] = $value;
		}
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		OQLMenuNode::RenderOQLSearch
		(
			$this->sOQL,
			Dict::S($this->sPageTitle),
			'Menu_'.$this->GetMenuId(),
			$this->bSearch, // Search pane
			true, // Search open
			$oPage, 
			array_merge($this->m_aParams, $aExtraParams)
		);
	}

	public static function RenderOQLSearch($sOql, $sTitle, $sUsageId, $bSearchPane, $bSearchOpen, WebPage $oPage, $aExtraParams = array())
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
		
		$oPage->add("<p class=\"page-header\">$sIcon ".Dict::S($sTitle)."</p>");
		
		$aParams = array_merge(array('table_id' => $sUsageId), $aExtraParams);
		$oBlock = new DisplayBlock($oSearch, 'list', false /* Asynchronous */, $aParams);
		$oBlock->Display($oPage, $sUsageId);
	}
}

/**
 * This class defines a menu item that displays a search form for the given class of objects
 */
class SearchMenuNode extends MenuNode
{
	protected $sPageTitle;
	protected $sClass;
	
	/**
	 * Create a menu item based on an OQL query and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sClass The class of objects to search for
	 * @param string $sPageTitle Title displayed into the page's content (will be looked-up in the dictionnary for translation)
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sClass, $iParentIndex, $fRank = 0, $bSearch = false, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sPageTitle = "Menu:$sMenuId+";
		$this->sClass = $sClass;
		$this->aReflectionProperties['class'] = $sClass;
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$oSearch = new DBObjectSearch($this->sClass);
		$aParams = array_merge(array('open' => true, 'table_id' => 'Menu_'.utils::GetSafeId($this->GetMenuId())), $aExtraParams);
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
	protected $sHyperlink;
	
	/**
	 * Create a menu item that points to any web page (not only UI.php)
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sHyperlink URL to the page to load. Use relative URL if you want to keep the application portable !
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sHyperlink, $iParentIndex, $fRank = 0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sHyperlink = $sHyperlink;
		$this->aReflectionProperties['url'] = $sHyperlink;
	}

	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams( $this->sHyperlink, $aExtraParams);
	}
	
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
	protected $sClass;
	
	/**
	 * Create a menu item that points to the URL for creating a new object, the menu will be added only if the current user has enough
	 * rights to create such an object (or an object of a child class)
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sClass URL to the page to load. Use relative URL if you want to keep the application portable !
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sClass, $iParentIndex, $fRank = 0)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank);
		$this->sClass = $sClass;
		$this->aReflectionProperties['class'] = $sClass;
	}

	public function GetHyperlink($aExtraParams)
	{
		$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class='.$this->sClass;
		$aExtraParams['c[menu]'] = $this->GetMenuId();
		return $this->AddParams($sHyperlink, $aExtraParams);
	}

	/**
	 * Overload the check of the "enable" state of this menu to take into account
	 * derived classes of objects
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
	protected $sDashboardFile;
	
	/**
	 * Create a menu item based on a custom template and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sTemplateFile Path (or URL) to the file that will be used as a template for displaying the page's content
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @param string $sEnableClass Name of class of object
	 * @param integer $iActionCode Either UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_DELETE, UR_ACTION_BULKREAD, UR_ACTION_BULKMODIFY or UR_ACTION_BULKDELETE
	 * @param integer $iAllowedResults Expected "rights" for the action: either UR_ALLOWED_YES, UR_ALLOWED_NO, UR_ALLOWED_DEPENDS or a mix of them...
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sDashboardFile, $iParentIndex, $fRank = 0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->sDashboardFile = $sDashboardFile;
		$this->aReflectionProperties['definition_file'] = $sDashboardFile;
	}
	
	public function GetHyperlink($aExtraParams)
	{
		if ($this->sDashboardFile == '') return '';
		return parent::GetHyperlink($aExtraParams);
	}

	public function GetDashboard()
	{
		$sDashboardDefinition = @file_get_contents($this->sDashboardFile);		
		if ($sDashboardDefinition !== false)
		{
			$bCustomized = false;
			
			// Search for an eventual user defined dashboard, overloading the existing one
			$oUDSearch = new DBObjectSearch('UserDashboard');
			$oUDSearch->AddCondition('user_id', UserRights::GetUserId(), '=');
			$oUDSearch->AddCondition('menu_code', $this->sMenuId, '=');
			$oUDSet = new DBObjectSet($oUDSearch);
			if ($oUDSet->Count() > 0)
			{
				// Assuming there is at most one couple {user, menu}!
				$oUserDashboard = $oUDSet->Fetch();
				$sDashboardDefinition = $oUserDashboard->Get('contents');
				$bCustomized = true;
				
			}
			$oDashboard = new RuntimeDashboard($this->sMenuId);
			$oDashboard->FromXml($sDashboardDefinition);
			$oDashboard->SetCustomFlag($bCustomized);
		}
		else
		{
			$oDashboard = null;
		}
		return $oDashboard;
	}

	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$oDashboard = $this->GetDashboard();
		if ($oDashboard != null)
		{
			$sDivId = preg_replace('/[^a-zA-Z0-9_]/', '', $this->sMenuId);
			$oPage->add('<div class="dashboard_contents" id="'.$sDivId.'">');
			$oDashboard->Render($oPage, false, $aExtraParams);
			$oPage->add('</div>');
			$oDashboard->RenderEditionTools($oPage);

			if ($oDashboard->GetAutoReload())
			{
				$sId = $this->sMenuId;
				$sExtraParams = json_encode($aExtraParams);
				$iReloadInterval = 1000 * $oDashboard->GetAutoReloadInterval();
				$oPage->add_script(
<<<EOF
					setInterval("ReloadDashboard('$sDivId');", $iReloadInterval);

					function ReloadDashboard(sDivId)
					{
						var oExtraParams = $sExtraParams;
						// Do not reload when a dialog box is active
						if (!($('.ui-dialog:visible').length > 0))
						{
							$('.dashboard_contents#'+sDivId).block();
							$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
							   { operation: 'reload_dashboard', dashboard_id: '$sId', extra_params: oExtraParams},
							   function(data){
								 $('.dashboard_contents#'+sDivId).html(data);
								 $('.dashboard_contents#'+sDivId).unblock();
								}
							 );
						}
					}
EOF
				);
			}

			$bEdit = utils::ReadParam('edit', false);
			if ($bEdit)
			{
				$sId = addslashes($this->sMenuId);
				$oPage->add_ready_script("EditDashboard('$sId');");
			}
		}
		else
		{
			$oPage->p("Error: failed to load dashboard file: '{$this->sDashboardFile}'");
		}
	}
	
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
			$oPage->p("Error: failed to load dashboard file: '{$this->sDashboardFile}'");
		}
	}
	
}

/**
 * A shortcut container is the preferred destination of newly created shortcuts
 */
class ShortcutContainerMenuNode extends MenuNode
{
	public function GetHyperlink($aExtraParams)
	{
		return '';
	}

	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
	}

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
			$oShortcutMenu = new ShortcutMenuNode($sName, $oShortcut, $this->GetIndex(), $fRank++);
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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $oShortcut, $iParentIndex, $fRank = 0, $sEnableClass = null, $iActionCode = null, $iAllowedResults = UR_ALLOWED_YES, $sEnableStimulus = null)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank, $sEnableClass, $iActionCode, $iAllowedResults, $sEnableStimulus);
		$this->oShortcut = $oShortcut;
		$this->aReflectionProperties['shortcut'] = $oShortcut->GetKey();
	}
	
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

	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		$this->oShortcut->RenderContent($oPage, $aExtraParams);
	}

	public function GetTitle()
	{
		return $this->oShortcut->Get('name');
	}
	
	public function GetLabel()
	{
		return $this->oShortcut->Get('name');
	}
}

