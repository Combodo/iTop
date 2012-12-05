<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Class iPlugin
 * Management of application plugin 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

interface iApplicationUIExtension
{
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false);
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false);
	public function OnFormSubmit($oObject, $sFormPrefix = '');
	public function OnFormCancel($sTempId); // temp id is made of session_id and transaction_id, it identifies the object in a unique way

	public function EnumUsedAttributes($oObject); // Not yet implemented
	public function GetIcon($oObject); // Not yet implemented
	public function GetHilightClass($oObject);

	public function EnumAllowedActions(DBObjectSet $oSet);
}

interface iApplicationObjectExtension
{
	public function OnIsModified($oObject);
	public function OnCheckToWrite($oObject);
	public function OnCheckToDelete($oObject);
	public function OnDBUpdate($oObject, $oChange = null);
	public function OnDBInsert($oObject, $oChange = null);
	public function OnDBDelete($oObject, $oChange = null);
}

/**
 * New extension to add menu items in the "popup" menus inside iTop. Provides a greater flexibility than
 * iApplicationUIExtension::EnumAllowedActions.
 * 
 * To add some menus into iTop, declare a class that implements this interface, it will be called automatically
 * by the application, as long as the class definition is included somewhere in the code
 */
interface iPopupMenuExtension
{
	// Possible types of menu into which new items can be added
	const MENU_OBJLIST_ACTIONS = 1; 	// $param is a DBObjectSet containing the list of objects
	const MENU_OBJLIST_TOOLKIT = 2;		// $param is a DBObjectSet containing the list of objects
	const MENU_OBJDETAILS_ACTIONS = 3;	// $param is a DBObject instance: the object currently displayed
	const MENU_DASHBOARD_ACTIONS = 4;	// $param is a Dashboard instance: the dashboard currently displayed
	const MENU_USER_ACTIONS = 5;		// $param is a null ??

	/**
	 * Get the list of items to be added to a menu. The items will be inserted in the menu in the order of the returned array
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx above
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 * @return Array An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param);
}

/**
 * Each menu items is defined by an instance of an object derived from the class
 * ApplicationPopupMenu below
 *
 */
abstract class ApplicationPopupMenuItem
{
	protected $sUID;
	protected $sLabel;
	
	public function __construct($sUID, $sLabel)
	{
		$this->sUID = $sUID;
		$this->sLabel = $sLabel;
	}
	
	public function GetUID()
	{
		return $this->sUID;
	}
	
	public function GetLabel()
	{
		return $this->sLabel;
	}
	
	/**
	 * Returns the components to create a popup menu item in HTML
	 * @return Hash A hash array: array('label' => , 'url' => , 'target' => , 'onclick' => )
	 */
	abstract public function GetMenuItem();

	public function GetLinkedScripts()
	{
		return array();
	}
}

/**
 * Class for adding an item into a popup menu that browses to the given URL
 */
class URLPopupMenuItem extends ApplicationPopupMenuItem
{
	protected $sURL;
	protected $sTarget;
	
	/**
	 * Class for adding an item that browses to the given URL
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sURL If the menu is an hyperlink, provide the absolute hyperlink here
	 * @param string $sTarget In case the menu is an hyperlink and a specific target is needed (_blank for example), pass it here
	 */
	public function __construct($sUID, $sLabel, $sURL, $sTarget = '_top')
	{
		parent::__construct($sUID, $sLabel);
		$this->sURL = $sURL;
		$this->sTarget = $sTarget;
	}
	
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'url' => $this->sURL, 'target' => $this->sTarget);	
	}
}

/**
 * Class for adding an item into a popup menu that triggers some Javascript code
 */
class JSPopupMenuItem extends ApplicationPopupMenuItem
{
	protected $sJSCode;
	protected $aIncludeJSFiles;
	
	/**
	 * Class for adding an item that triggers some Javascript code
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sJSCode In case the menu consists in executing some havascript code inside the page, pass it here. If supplied $sURL ans $sTarget will be ignored
	 * @param array $aIncludeJSFiles An array of file URLs to be included (once) to provide some JS libraries for the page.
	 */
	public function __construct($sUID, $sLabel, $sJSCode, $aIncludeJSFiles = array())
	{
		parent::__construct($sUID, $sLabel);
		$this->sJSCode = $sJSCode;
		$this->aIncludeJSFiles = $aIncludeJSFiles;
	}
	
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'onclick' => $this->sJSCode, 'url' => '#');
	}
	
	public function GetLinkedScripts()
	{
		return $this->aIncludeJSFiles;
	}
}

/**
 * Class for adding a separator (horizontal line, not selectable) the output
 * will automatically reduce several consecutive separators to just one
 */
class SeparatorPopupMenuItem extends ApplicationPopupMenuItem
{
	/**
	 * Class for inserting a separator into a popup menu
	 */
	public function __construct()
	{
		parent::__construct('', '');
	}
	
	public function GetMenuItem()
	{
		return array ('label' => '<hr class="menu-separator">', 'url' => '');
	}
}

/**
 * Implement this interface to add content to any iTopWebPage
 * There are 3 places where content can be added:
 * - The north pane: (normaly empty/hidden) at the top of the page, spanning the whole
 *   width of the page
 * - The south pane: (normaly empty/hidden) at the bottom of the page, spanning the whole
 *   width of the page
 * - The admin banner (two tones gray background) at the left of the global search.
 *   Limited space, use it for short messages
 * Each of the methods of this interface is supposed to return the HTML to be inserted at
 * the specified place and can use the passed iTopWebPage object to add javascript or CSS definitions
 *
 */
interface iPageUIExtension
{
	/**
	 * Add content to the North pane
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the South pane
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the "admin banner"
	 * @param WebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetBannerHtml(iTopWebPage $oPage);
}

