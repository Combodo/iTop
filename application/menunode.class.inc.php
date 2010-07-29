<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Construction and display of the application's main menu
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/utils.inc.php');
require_once('../application/template.class.inc.php');


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
	static $aRootMenus = array();
	static $aMenusIndex = array();
	
	/**
	 * Main function to add a menu entry into the application, can be called during the definition
	 * of the data model objects
	 */
	static public function InsertMenu(MenuNode $oMenuNode, $iParentIndex = -1, $fRank)
	{
		$index = self::GetMenuIndexById($oMenuNode->GetMenuId());
		if ($index == -1)
		{
			// The menu does not already exist, insert it
			$index = count(self::$aMenusIndex);
			self::$aMenusIndex[$index] = array( 'node' => $oMenuNode, 'children' => array());
			if ($iParentIndex == -1)
			{
				self::$aRootMenus[] = array ('rank' => $fRank, 'index' => $index);
			}
			else
			{
				self::$aMenusIndex[$iParentIndex]['children'][] = array ('rank' => $fRank, 'index' => $index);
			}
		}
		return $index;
	}
	
	/**
	 * Entry point to display the whole menu into the web page, used by iTopWebPage
	 */
	static public function DisplayMenu(iTopWebPage $oPage, $aExtraParams)
	{
		// Sort the root menu based on the rank
		usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
		$iAccordion = 0;
		$iActiveMenu = ApplicationMenu::GetActiveNodeId();
		foreach(self::$aRootMenus as $aMenu)
		{
			$oMenuNode = self::GetMenuNode($aMenu['index']);
			if (($oMenuNode->GetMenuId() == 'AdminTools') && (!UserRights::IsAdministrator())) continue; // Don't display the admin menu for non admin users
			$oPage->AddToMenu('<h3><a href="'.$oMenuNode->GetHyperlink($aExtraParams).'">'.$oMenuNode->GetTitle().'</a></h3>');
			$oPage->AddToMenu('<div>');
			$aChildren = self::GetChildren($aMenu['index']);
			if (count($aChildren) > 0)
			{
				$oPage->AddToMenu('<ul>');
				$bActive = self::DisplaySubMenu($oPage, $aChildren, $aExtraParams, $iActiveMenu);
				$oPage->AddToMenu('</ul>');
				if ($bActive)
				{
					$oPage->add_ready_script("$('#accordion').accordion('activate', $iAccordion);");
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
	static protected function GetChildren($index)
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
	 * @return MenuNode or null if there is no menu at all !
	 */
	static public function GetActiveNodeId()
	{
		$iMenuIndex = utils::ReadParam('menu', -1);
		
		if ($iMenuIndex  == -1)
		{
			// Make sure the root menu is sorted on 'rank'
			usort(self::$aRootMenus, array('ApplicationMenu', 'CompareOnRank'));
			$oFirstGroup = self::GetMenuNode(self::$aRootMenus[0]['index']);
			$oMenuNode = self::GetMenuNode(self::$aMenusIndex[$oFirstGroup->GetIndex()]['children'][0]['index']);
			$iMenuIndex = $oMenuNode->GetIndex();
		}
		return $iMenuIndex;
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
	protected $index = null;
	
	/**
	 * Create a menu item and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param integer $iParentIndex ID of the parent menu, pass -1 for top level (group) items
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $iParentIndex = -1, $fRank = 0)
	{
		$this->sMenuId = $sMenuId;
		$this->index = ApplicationMenu::InsertMenu($this, $iParentIndex, $fRank);
	}
	
	public function GetMenuId()
	{
		return $this->sMenuId;
	}
	
	public function GetTitle()
	{
		return Dict::S("Menu:$this->sMenuId");
	}
	
	public function GetLabel()
	{
		return Dict::S("Menu:$this->sMenuId+");
	}
	
	public function GetIndex()
	{
		return $this->index;
	}
	
	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['menu'] = $this->GetIndex();
		return $this->AddParams('../pages/UI.php', $aExtraParams);
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
	 * @return MenuGroup
	 */
	public function __construct($sMenuId, $fRank)
	{
		parent::__construct($sMenuId, -1 /* no parent, groups are at root level */, $fRank);
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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sTemplateFile, $iParentIndex, $fRank = 0)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank);
		$this->sTemplateFile = $sTemplateFile;
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
	
	/**
	 * Create a menu item based on an OQL query and inserts it into the application's main menu
	 * @param string $sMenuId Unique identifier of the menu (used to identify the menu for bookmarking, and for getting the labels from the dictionary)
	 * @param string $sPageTitle Title displayed into the page's content (will be looked-up in the dictionnary for translation)
	 * @param integer $iParentIndex ID of the parent menu
	 * @param float $fRank Number used to order the list, any number will do, but for a given level (i.e same parent) all menus are sorted based on this value
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sOQL, $iParentIndex, $fRank = 0)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank);
		$this->sPageTitle = "Menu:$sMenuId+";
		$this->sOQL = $sOQL;
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		try
		{
			$oSearch = DBObjectSearch::FromOQL($this->sOQL);
			$sIcon = MetaModel::GetClassIcon($oSearch->GetClass());
		}
		catch(Exception $e)
		{
			$sIcon = '';
		}
		// The standard template used for all such pages: a (closed) search form at the top and a list of results at the bottom
		$sTemplate = <<<EOF
<itopblock BlockClass="DisplayBlock" type="search" asynchronous="false" encoding="text/oql">$this->sOQL</itopblock>
<p class="page-header"><img src="$sIcon"><itopstring>$this->sPageTitle</itopstring></p>
<itopblock BlockClass="DisplayBlock" type="list" asynchronous="false" encoding="text/oql">$this->sOQL</itopblock>
EOF;
		$oTemplate = new DisplayTemplate($sTemplate);
		$oTemplate->Render($oPage, $aExtraParams);
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
	 * @return MenuNode
	 */
	public function __construct($sMenuId, $sHyperlink, $iParentIndex, $fRank = 0)
	{
		parent::__construct($sMenuId, $iParentIndex, $fRank);
		$this->sHyperlink = $sHyperlink;
	}

	public function GetHyperlink($aExtraParams)
	{
		$aExtraParams['menu'] = $this->GetIndex();
		return $this->AddParams( $this->sHyperlink, $aExtraParams);
	}
	
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		assert(false); // Shall never be called, the external web page will handle the display by itself
	}
}
?>
