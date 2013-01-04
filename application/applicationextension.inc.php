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
 * Management of application plugins
 * 
 * Definition of interfaces that can be implemented to customize iTop.
 * You may implement such interfaces in a module file (e.g. main.mymodule.php) 
 *
 * @package     Extensibility
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @api
 */

/**
 * Implement this interface to change the behavior of the GUI for some objects.
 *
 * All methods are invoked by iTop for a given object. There are basically two usages:
 * 
 * 1) To tweak the form of an object, you will have to implement a specific behavior within:
 *  
 * * OnDisplayProperties (bEditMode = true)
 * * OnFormSubmit
 * * OnFormCancel
 * 
 * 2) To tune the display of the object details, you can use:
 *  
 * * OnDisplayProperties
 * * OnDisplayRelations
 * * GetIcon
 * * GetHilightClass
 *   
 * Please note that some of the APIs can be called several times for a single page displayed.
 * Therefore it is not recommended to perform too many operations, such as querying the database.
 * A recommended pattern is to cache data by the mean of static members. 
 *
 * @package     Extensibility
 * @api
 */
interface iApplicationUIExtension
{
	/**
	 *	Invoked when an object is being displayed (wiew or edit)
	 *	
	 * The method is called right after the main tab has been displayed.
	 * You can add output to the page, either to change the display, or to add a form input
	 * 
	 * Example:
	 * <code>
	 * if ($bEditMode)
	 * {
	 * 	$oPage->p('Age of the captain: &lt;input type="text" name="captain_age"/&gt;');
	 * }
	 * else
	 * {
	 * 	$oPage->p('Age of the captain: '.$iCaptainAge);
	 * }	 
	 * </code>
	 * 
	 * @param DBObject $oObject The object being displayed
	 * @param WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 * @return void
	 */	
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false);

	/**
	 *	Invoked when an object is being displayed (wiew or edit)
	 *	
	 * The method is called rigth after all the tabs have been displayed 
	 * 
	 * @param DBObject $oObject The object being displayed
	 * @param WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 * @return void
	 */	
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false);

	/**
	 *	Invoked when the end-user clicks on Modify from the object edition form
	 *	
	 * The method is called after the changes from the standard form have been
	 * taken into account, and before saving the changes into the database.	 
	 * 
	 * @param DBObject $oObject The object being edited
	 * @param string $sFormPrefix Prefix given to the HTML form inputs
	 * @return void
	 */	
	public function OnFormSubmit($oObject, $sFormPrefix = '');

	/**
	 *	Invoked when the end-user clicks on Cancel from the object edition form
	 *	
	 * Implement here any cleanup. This is necessary when you have injected some
	 * javascript into the edition form, and if that code requires to store temporary data
	 * (this is the case when a file must be uploaded).
	 * 
	 * @param string $sTempId Unique temporary identifier made of session_id and transaction_id. It identifies the object in a unique way.
	 * @return void
	 */	
	public function OnFormCancel($sTempId);

	/**
	 *	Not yet called by the framework!
	 *	
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 * 
	 * @param DBObject $oObject The object being displayed
	 * @return type desc
	 */	
	public function EnumUsedAttributes($oObject); // Not yet implemented

	/**
	 *	Not yet called by the framework!
	 *	
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 * 
	 * @param DBObject $oObject The object being displayed
	 * @return string Path of the icon, relative to the modules directory.
	 */	
	public function GetIcon($oObject); // Not yet implemented

	/**
	 *	Invoked when the object is displayed alone or within a list
	 *	
	 * Returns a value influencing the appearance of the object depending on its
	 * state.
	 * 
	 * Possible values are:
	 *
	 * * HILIGHT_CLASS_CRITICAL
 	 * * HILIGHT_CLASS_WARNING
	 * * HILIGHT_CLASS_OK
	 * * HILIGHT_CLASS_NONE	
	 * 
	 * @param DBObject $oObject The object being displayed
	 * @return integer The value representing the mood of the object
	 */	
	public function GetHilightClass($oObject);

	/**
	 *	Called when building the Actions menu for a single object or a list of objects
	 *
	 * Use this to add items to the Actions menu. You will have to specify a label and an URL.
	 *
	 * Example:
	 * <code>
	 * $oObject = $oSet->fetch();	 
	 * if ($oObject instanceof Sheep)
	 * {	 
	 * 	return array('View in my app' => 'http://myserver/view_sheeps?id='.$oObject->Get('name'));
	 * }
	 * else
	 * {
	 * 	return array();
	 * }
	 * </code>
	 *
	 * See also iPopupMenuExtension for greater flexibility
	 * 
	 * @param DBObjectSet $oSet A set of persistent objects (DBObject)
	 * @return string[string]
	 */	
	public function EnumAllowedActions(DBObjectSet $oSet);
}

/**
 * Implement this interface to perform specific things when objects are manipulated
 *
 * Note that those methods will be called when objects are manipulated, either in a programmatic way
 * or through the GUI. 
 *  
 * @package     Extensibility
 * @api
 */ 
interface iApplicationObjectExtension
{
	/**
	 *	Invoked to determine wether an object has been modified in memory
	 *
	 *	The GUI calls this verb to determine the message that will be displayed to the end-user.
	 *	Anyhow, this API can be called in other contexts such as the CSV import tool.
	 *	
	 * If the extension returns false, then the framework will perform the usual evaluation.
	 * Otherwise, the answer is definitively "yes, the object has changed".	 	 	 
	 *	 
	 * @param DBObject $oObject The target object
	 * @return boolean True if something has changed for the target object
	 */	
	public function OnIsModified($oObject);

	/**
	 *	Invoked to determine wether an object can be written to the database 
	 *	
	 *	The GUI calls this verb and reports any issue.
	 *	Anyhow, this API can be called in other contexts such as the CSV import tool.
	 * 
	 * @param DBObject $oObject The target object
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */	
	public function OnCheckToWrite($oObject);

	/**
	 *	Invoked to determine wether an object can be deleted from the database
	 *	
	 * The GUI calls this verb and stops the deletion process if any issue is reported.
	 * 	 
	 * Please not that it is not possible to cascade deletion by this mean: only stopper issues can be handled. 	 
	 * 
	 * @param DBObject $oObject The target object
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */	
	public function OnCheckToDelete($oObject);

	/**
	 *	Invoked when an object is updated into the database
	 *	
	 * The method is called right <b>after</b> the object has been written to the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBUpdate($oObject, $oChange = null);

	/**
	 *	Invoked when an object is created into the database
	 *	
	 * The method is called right <b>after</b> the object has been written to the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBInsert($oObject, $oChange = null);

	/**
	 *	Invoked when an object is deleted from the database
	 *	
	 * The method is called right <b>before</b> the object will be deleted from the database.
	 * 
	 * @param DBObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information once for all the changes made within the current page
	 * @return void
	 */	
	public function OnDBDelete($oObject, $oChange = null);
}

/**
 * New extension to add menu items in the "popup" menus inside iTop. Provides a greater flexibility than
 * iApplicationUIExtension::EnumAllowedActions.
 * 
 * To add some menus into iTop, declare a class that implements this interface, it will be called automatically
 * by the application, as long as the class definition is included somewhere in the code
 * 
 * @package     Extensibility
 * @api
 * @since 2.0  
 */
interface iPopupMenuExtension
{
	/**
	 * Insert an item into the Actions menu of a list
	 *
	 * $param is a DBObjectSet containing the list of objects	
	 */	
	const MENU_OBJLIST_ACTIONS = 1;
	/**
	 * Insert an item into the Toolkit menu of a list
	 *
	 * $param is a DBObjectSet containing the list of objects
	 */	
	const MENU_OBJLIST_TOOLKIT = 2;
	/**
	 * Insert an item into the Actions menu on an object details page
	 *
	 * $param is a DBObject instance: the object currently displayed
	 */	
	const MENU_OBJDETAILS_ACTIONS = 3;
	/**
	 * Insert an item into the Dashboard menu
	 *
	 * The dashboad menu is shown on the top right corner when a dashboard
	 * is being displayed.
	 * 
	 * $param is a Dashboard instance: the dashboard currently displayed
	 */	
	const MENU_DASHBOARD_ACTIONS = 4;
	/**
	 * Insert an item into the User menu (upper right corner)
	 *
	 * $param is null
	 */
	const MENU_USER_ACTIONS = 5;

	/**
	 * Get the list of items to be added to a menu.
	 *
	 * This method is called by the framework for each menu.
	 * The items will be inserted in the menu in the order of the returned array.
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 * @return object[] An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param);
}

/**
 * Base class for the various types of custom menus
 * 
 * @package     Extensibility
 * @internal
 * @since 2.0
 */
abstract class ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sUID;
	/** @ignore */
	protected $sLabel;
	
	/**
	 *	Constructor
	 *	
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 */	
	public function __construct($sUID, $sLabel)
	{
		$this->sUID = $sUID;
		$this->sLabel = $sLabel;
	}
	
	/**
	 *	Get the UID
	 *	
	 * @return string The unique identifier	 
	 * @ignore	 	 
	 */	
	public function GetUID()
	{
		return $this->sUID;
	}
	
	/**
	 *	Get the label
	 *	
	 * @return string The label
	 * @ignore	 	 
	 */	
	public function GetLabel()
	{
		return $this->sLabel;
	}
	
	/**
	 * Returns the components to create a popup menu item in HTML
	 * @return Hash A hash array: array('label' => , 'url' => , 'target' => , 'onclick' => )
	 * @ignore	 	 
	 */
	abstract public function GetMenuItem();

	/** @ignore */
	public function GetLinkedScripts()
	{
		return array();
	}
}

/**
 * Class for adding an item into a popup menu that browses to the given URL
 *  
 * @package     Extensibility
 * @api
 * @since 2.0  
 */
class URLPopupMenuItem extends ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sURL;
	/** @ignore */
	protected $sTarget;
	
	/**
	 * Constructor
	 * 	 
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
	
	/** @ignore */
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'url' => $this->sURL, 'target' => $this->sTarget);	
	}
}

/**
 * Class for adding an item into a popup menu that triggers some Javascript code
 * 
 * @package     Extensibility
 * @api
 * @since 2.0  
 */
class JSPopupMenuItem extends ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sJSCode;
	/** @ignore */
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
	
	/** @ignore */
	public function GetMenuItem()
	{
		return array ('label' => $this->GetLabel(), 'onclick' => $this->sJSCode, 'url' => '#');
	}
	
	/** @ignore */
	public function GetLinkedScripts()
	{
		return $this->aIncludeJSFiles;
	}
}

/**
 * Class for adding a separator (horizontal line, not selectable) the output
 * will automatically reduce several consecutive separators to just one
 * 
 * @package     Extensibility
 * @api
 * @since 2.0  
 */
class SeparatorPopupMenuItem extends ApplicationPopupMenuItem
{
	/**
	 * Constructor	
	 */
	public function __construct()
	{
		parent::__construct('', '');
	}
	
	/** @ignore */
	public function GetMenuItem()
	{
		return array ('label' => '<hr class="menu-separator">', 'url' => '');
	}
}

/**
 * Implement this interface to add content to any iTopWebPage
 * 
 * There are 3 places where content can be added:
 * 
 * * The north pane: (normaly empty/hidden) at the top of the page, spanning the whole
 *   width of the page
 * * The south pane: (normaly empty/hidden) at the bottom of the page, spanning the whole
 *   width of the page
 * * The admin banner (two tones gray background) at the left of the global search.
 *   Limited space, use it for short messages
 * 
 * Each of the methods of this interface is supposed to return the HTML to be inserted at
 * the specified place and can use the passed iTopWebPage object to add javascript or CSS definitions
 *
 * @package     Extensibility
 * @api
 * @since 2.0  
 */
interface iPageUIExtension
{
	/**
	 * Add content to the North pane
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the South pane
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage);
	/**
	 * Add content to the "admin banner"
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 * @return string The HTML content to add into the page
	 */
	public function GetBannerHtml(iTopWebPage $oPage);
}

