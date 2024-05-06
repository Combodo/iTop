<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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

use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Symfony\Component\DependencyInjection\Container;

require_once(APPROOT.'application/newsroomprovider.class.inc.php');

/**
 * Management of application plugins
 *
 * Definition of interfaces that can be implemented to customize iTop.
 * You may implement such interfaces in a module file (e.g. main.mymodule.php)
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @since       2.7.0
 */

/**
 * @api
 * @package     LoginExtensibilityAPI
 * @since       2.7.0
 */
interface iLoginExtension
{
	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @api
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes();
}

/**
 * @api
 * @package     LoginExtensibilityAPI
 * @since 2.7.0
 */
interface iLoginFSMExtension extends iLoginExtension
{
	/**
	 * Execute action for this login state
	 * If a page is displayed, the action must exit at this point
	 * if LoginWebPage::LOGIN_FSM_RETURN_ERROR is returned $iErrorCode must be set
	 * if LoginWebPage::LOGIN_FSM_RETURN_OK is returned then the login is OK and terminated
	 * if LoginWebPage::LOGIN_FSM_RETURN_IGNORE is returned then the FSM will proceed to next plugin or state
	 *
	 * @api
	 * @param string $sLoginState (see LoginWebPage::LOGIN_STATE_...)
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	public function LoginAction($sLoginState, &$iErrorCode);
}

/**
 * Login finite state machine
 *
 * Execute the action corresponding to the current login state.
 *
 *  * If a page is displayed, the action must exit at this point
 *  * if LoginWebPage::LOGIN_FSM_RETURN_ERROR is returned $iErrorCode must be set
 *  * if LoginWebPage::LOGIN_FSM_RETURN_OK is returned then the login is OK and terminated
 *  * if LoginWebPage::LOGIN_FSM_RETURN_IGNORE is returned then the FSM will proceed to next plugin or to next state
 *
 * @api
 * @package LoginExtensibilityAPI
 * @since 2.7.0
 */
abstract class AbstractLoginFSMExtension implements iLoginFSMExtension
{
	/**
	 * @inheritDoc
	 */
	abstract public function ListSupportedLoginModes();

	/**
	 * @inheritDoc
	 */
	public function LoginAction($sLoginState, &$iErrorCode)
	{
		switch ($sLoginState) {
			case LoginWebPage::LOGIN_STATE_START:
				return $this->OnStart($iErrorCode);

			case LoginWebPage::LOGIN_STATE_MODE_DETECTION:
				return $this->OnModeDetection($iErrorCode);

			case LoginWebPage::LOGIN_STATE_READ_CREDENTIALS:
				return $this->OnReadCredentials($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CHECK_CREDENTIALS:
				return $this->OnCheckCredentials($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CREDENTIALS_OK:
				return $this->OnCredentialsOK($iErrorCode);

			case LoginWebPage::LOGIN_STATE_USER_OK:
				return $this->OnUsersOK($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				return $this->OnConnected($iErrorCode);

			case LoginWebPage::LOGIN_STATE_ERROR:
				return $this->OnError($iErrorCode);
		}

		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Initialization
	 *
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnStart(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Detect login mode explicitly without respecting configured order (legacy mode)
	 * In most case do nothing here
	 *
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnModeDetection(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Obtain the credentials either if login mode is empty or set to yours.
	 * This step can be called multiple times by the FSM:
	 * for example:
	 * 1 - display login form
	 * 2 - read the values posted by the user (store that in session)
	 *
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnReadCredentials(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Control the validity of the data from the session
	 * Automatic user provisioning can be done here
	 *
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnCheckCredentials(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnCredentialsOK(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnUsersOK(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnConnected(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @api
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	protected function OnError(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}
}

/**
 * @api
 * @package LoginExtensibilityAPI
 * @since 2.7.0
 */
interface iLogoutExtension extends iLoginExtension
{
	/**
	 * Execute all actions to log out properly
	 * @api
	 */
	public function LogoutAction();
}

/**
 * Login page extensibility
 *
 * @api
 * @package UIExtensibilityAPI
 * @since 2.7.0
 */
interface iLoginUIExtension extends iLoginExtension
{
	/**
	 * @api
	 * @return LoginTwigContext
	 */
	public function GetTwigContext();
}

/**
 * @api
 * @package     PreferencesExtensibilityAPI
 * @since 2.7.0
 */
interface iPreferencesExtension
{
	/**
	 * @api
	 * @param WebPage $oPage
	 *
	 */
	public function DisplayPreferences(WebPage $oPage);

	/**
	 * @api
	 * @param WebPage $oPage
	 * @param string $sOperation
	 *
	 * @return bool true if the operation has been used
	 */
	public function ApplyPreferences(WebPage $oPage, $sOperation);
}

/**
 * Extend this class instead of implementing iPreferencesExtension if you don't need to overload all methods
 *
 * @api
 * @package     PreferencesExtensibilityAPI
 * @since       2.7.0
 */
abstract class AbstractPreferencesExtension implements iPreferencesExtension
{
	/**
	 * @inheritDoc
	 */
	public function DisplayPreferences(WebPage $oPage)
	{
		// Do nothing
	}

	/**
	 * @inheritDoc
	 */
	public function ApplyPreferences(WebPage $oPage, $sOperation)
	{
		// Do nothing
	}

}

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
 * @api
 * @package     UIExtensibilityAPI
 */
interface iApplicationUIExtension
{
	/**
	 *    Invoked when an object is being displayed (wiew or edit)
	 *
	 * The method is called right after the main tab has been displayed.
	 * You can add output to the page, either to change the display, or to add a form input
	 *
	 * Example:
	 * <code>
	 * if ($bEditMode)
	 * {
	 *    $oPage->p('Age of the captain: &lt;input type="text" name="captain_age"/&gt;');
	 * }
	 * else
	 * {
	 *    $oPage->p('Age of the captain: '.$iCaptainAge);
	 * }
	 * </code>
	 *
	 * @api
	 * @param DBObject $oObject The object being displayed
	 * @param WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 *
	 * @return void
	 */
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false);

	/**
	 * Invoked when an object is being displayed (wiew or edit)
	 *
	 * The method is called rigth after all the tabs have been displayed
	 *
	 * @api
	 * @param DBObject $oObject The object being displayed
	 * @param WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 *
	 * @return void
	 */
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false);

	/**
	 * Invoked when the end-user clicks on Modify from the object edition form
	 *
	 * The method is called after the changes from the standard form have been
	 * taken into account, and before saving the changes into the database.
	 *
	 * @api
	 * @param DBObject $oObject The object being edited
	 * @param string $sFormPrefix Prefix given to the HTML form inputs
	 *
	 * @return void
	 */
	public function OnFormSubmit($oObject, $sFormPrefix = '');

	/**
	 * Invoked when the end-user clicks on Cancel from the object edition form
	 *
	 * Implement here any cleanup. This is necessary when you have injected some
	 * javascript into the edition form, and if that code requires to store temporary data
	 * (this is the case when a file must be uploaded).
	 *
	 * @api
	 * @param string $sTempId Unique temporary identifier made of session_id and transaction_id. It identifies the object in a unique way.
	 *
	 * @return void
	 */
	public function OnFormCancel($sTempId);

	/**
	 * Not yet called by the framework!
	 *
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 *
	 * @api
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return string[] desc
	 */
	public function EnumUsedAttributes($oObject); // Not yet implemented

	/**
	 * Not yet called by the framework!
	 *
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 *
	 * @api
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return string Path of the icon, relative to the modules directory.
	 */
	public function GetIcon($oObject); // Not yet implemented

	/**
	 * Invoked when the object is displayed alone or within a list
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
	 * @api
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return integer The value representing the mood of the object
	 */
	public function GetHilightClass($oObject);

	/**
	 * Called when building the Actions menu for a single object or a list of objects
	 *
	 * Use this to add items to the Actions menu. You will have to specify a label and an URL.
	 *
	 * Example:
	 * <code>
	 * $oObject = $oSet->fetch();
	 * if ($oObject instanceof Sheep)
	 * {
	 *    return array('View in my app' => 'http://myserver/view_sheeps?id='.$oObject->Get('name'));
	 * }
	 * else
	 * {
	 *    return array();
	 * }
	 * </code>
	 *
	 * See also iPopupMenuExtension for greater flexibility
	 *
	 * @api
	 * @param DBObjectSet $oSet A set of persistent objects (DBObject)
	 *
	 * @return array
	 */
	public function EnumAllowedActions(DBObjectSet $oSet);
}

/**
 * Extend this class instead of implementing iApplicationUIExtension if you don't need to overload
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since       2.7.0
 */
abstract class AbstractApplicationUIExtension implements iApplicationUIExtension
{
	/**
	 * @inheritDoc
	 */
	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function OnFormSubmit($oObject, $sFormPrefix = '')
	{
	}

	/**
	 * @inheritDoc
	 */
	public function OnFormCancel($sTempId)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function EnumUsedAttributes($oObject)
	{
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function GetIcon($oObject)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetHilightClass($oObject)
	{
		return HILIGHT_CLASS_NONE;
	}

	/**
	 * @inheritDoc
	 */
	public function EnumAllowedActions(DBObjectSet $oSet)
	{
		return array();
	}

}

/**
 * Implement this interface to perform specific operations when objects are manipulated
 *
 * Note that those methods will be called when objects are manipulated, either in a programmatic way
 * or through the GUI.
 *
 * @api
 * @deprecated  3.1.0 N°4756 use the new event service instead, see {@see DBObject::FireEvent()} method. More details on each method PHPDoc.
 * @package     ORMExtensibilityAPI
 */
interface iApplicationObjectExtension
{
	/**
	 * Invoked to determine whether an object has been modified in memory
	 *
	 * The GUI calls this verb to determine the message that will be displayed to the end-user.
	 * Anyhow, this API can be called in other contexts such as the CSV import tool.
	 *
	 * If the extension returns false, then the framework will perform the usual evaluation.
	 * Otherwise, the answer is definitively "yes, the object has changed".
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 No alternative available, this API was unstable and is abandoned
	 * @param \cmdbAbstractObject $oObject The target object
	 *
	 * @return boolean True if something has changed for the target object
	 */
	public function OnIsModified($oObject);

	/**
	 * Invoked to determine whether an object can be written to the database
	 *
	 * The GUI calls this verb and reports any issue.
	 * Anyhow, this API can be called in other contexts such as the CSV import tool.
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 Use EVENT_DB_CHECK_TO_WRITE event instead
	 * @param \cmdbAbstractObject $oObject The target object
	 *
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */
	public function OnCheckToWrite($oObject);

	/**
	 * Invoked to determine wether an object can be deleted from the database
	 *
	 * The GUI calls this verb and stops the deletion process if any issue is reported.
	 *
	 * Please not that it is not possible to cascade deletion by this mean: only stopper issues can be handled.
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 Use EVENT_DB_CHECK_TO_DELETE event instead
	 * @param \cmdbAbstractObject $oObject The target object
	 *
	 * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
	 */
	public function OnCheckToDelete($oObject);

	/**
	 * Invoked when an object is updated into the database. The method is called right <b>after</b> the object has been written to the
	 * database.
	 *
	 * Useful methods you can call on $oObject :
	 *
	 * * {@see DBObject::ListPreviousValuesForUpdatedAttributes()} : list of changed attributes and their values before the change
	 * * {@see DBObject::Get()} : for a given attribute the new value that was persisted
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_WRITE event instead
	 * @param \cmdbAbstractObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
	 *     once for all the changes made within the current page
	 *
	 * @return void
	 *
	 * @since 2.7.0 N°2293 can access object changes by calling {@see DBObject::ListPreviousValuesForUpdatedAttributes()} on $oObject
	 */
	public function OnDBUpdate($oObject, $oChange = null);

	/**
	 * Invoked when an object is created into the database
	 *
	 * The method is called right <b>after</b> the object has been written to the database.
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_WRITE event instead
	 * @param \cmdbAbstractObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
	 *     once for all the changes made within the current page
	 *
	 * @return void
	 */
	public function OnDBInsert($oObject, $oChange = null);

	/**
	 * Invoked when an object is deleted from the database
	 *
	 * The method is called right <b>before</b> the object will be deleted from the database.
	 *
	 * @api
	 * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_DELETE event instead
	 * @param \cmdbAbstractObject $oObject The target object
	 * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
	 *     once for all the changes made within the current page
	 *
	 * @return void
	 */
	public function OnDBDelete($oObject, $oChange = null);
}

/**
 * Extend this class instead of iApplicationObjectExtension if you don't need to overload all methods
 *
 * @api
 * @deprecated  3.1.0 N°4756 use the new event service instead, see {@see DBObject::FireEvent()} method
 * @package     ORMExtensibilityAPI
 * @since       2.7.0
 */
abstract class AbstractApplicationObjectExtension implements iApplicationObjectExtension
{
	/**
	 * @inheritDoc
	 */
	public function OnIsModified($oObject)
	{
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function OnCheckToWrite($oObject)
	{
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function OnCheckToDelete($oObject)
	{
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function OnDBUpdate($oObject, $oChange = null)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function OnDBInsert($oObject, $oChange = null)
	{
	}

	/**
	 * @inheritDoc
	 */
	public function OnDBDelete($oObject, $oChange = null)
	{
	}

}

/**
 * New extension to add menu items in the "popup" menus inside iTop. Provides a greater flexibility than
 * iApplicationUIExtension::EnumAllowedActions.
 *
 * To add some menus into iTop, declare a class that implements this interface, it will be called automatically
 * by the application, as long as the class definition is included somewhere in the code
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
interface iPopupMenuExtension
{
	/**
	 * Insert an item into the Actions menu of a list
	 *
	 * $param is a DBObjectSet containing the list of objects
	 * @api
	 */
	const MENU_OBJLIST_ACTIONS = 1;
	/**
	 * Insert an item into the Toolkit menu of a list
	 *
	 * $param is a DBObjectSet containing the list of objects
	 * @api
	 */
	const MENU_OBJLIST_TOOLKIT = 2;
	/**
	 * Insert an item into the Actions menu on an object details page
	 *
	 * $param is a DBObject instance: the object currently displayed
	 * @api
	 */
	const MENU_OBJDETAILS_ACTIONS = 3;
	/**
	 * Insert an item into the Dashboard menu
	 *
	 * The dashboad menu is shown on the top right corner when a dashboard
	 * is being displayed.
	 *
	 * $param is a Dashboard instance: the dashboard currently displayed
	 * @api
	 */
	const MENU_DASHBOARD_ACTIONS = 4;
	/**
	 * Insert an item into the User menu (upper right corner)
	 *
	 * $param is null
	 * @api
	 */
	const MENU_USER_ACTIONS = 5;
	/**
	 * Insert an item into the Action menu on an object item in an objects list in the portal
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object' => $oObject) containing the portal id and a DBObject instance (the object on
	 * the current line)
	 * @api
	 */
	const PORTAL_OBJLISTITEM_ACTIONS = 7;
	/**
	 * Insert an item into the Action menu on an object details page in the portal
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object' => $oObject) containing the portal id and a DBObject instance (the object
	 * currently displayed)
	 * @api
	 */
	const PORTAL_OBJDETAILS_ACTIONS = 8;

	/**
	 * Insert an item into the Actions menu of a list in the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object_set' => $oSet) containing DBObjectSet containing the list of objects
	 *
	 * @todo
	 */
	const PORTAL_OBJLIST_ACTIONS = 6;
	/**
	 * Insert an item into the user menu of the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is the portal id
	 *
	 * @todo
	 */
	const PORTAL_USER_ACTIONS = 9;
	/**
	 * Insert an item into the navigation menu of the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is the portal id
	 *
	 * @todo
	 */
	const PORTAL_MENU_ACTIONS = 10;

	/**
	 * Get the list of items to be added to a menu.
	 *
	 * This method is called by the framework for each menu.
	 * The items will be inserted in the menu in the order of the returned array.
	 *
	 * @api
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 *
	 * @return object[] An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 */
	public static function EnumItems($iMenuId, $param);
}

/**
 * Base class for the various types of custom menus
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
abstract class ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sUID;
	/** @ignore */
	protected $sLabel;
	/** @ignore */
	protected $sTooltip;
	/** @ignore */
	protected $sIconClass;
	/** @ignore */
	protected $aCssClasses;

	/**
	 * Constructor
	 *
	 * @api
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 */
	public function __construct($sUID, $sLabel)
	{
		$this->sUID = $sUID;
		$this->sLabel = $sLabel;
		$this->sTooltip = '';
		$this->sIconClass = '';
		$this->aCssClasses = array();
	}

	/**
	 * Get the UID
	 *
	 * @return string The unique identifier
	 * @ignore
	 */
	public function GetUID()
	{
		return $this->sUID;
	}

	/**
	 * Get the label
	 *
	 * @return string The label
	 * @ignore
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * Get the CSS classes
	 *
	 * @return array
	 * @ignore
	 */
	public function GetCssClasses()
	{
		return $this->aCssClasses;
	}

	/**
	 * @api
	 * @param $aCssClasses
	 */
	public function SetCssClasses($aCssClasses)
	{
		$this->aCssClasses = $aCssClasses;
	}

	/**
	 * Adds a CSS class to the CSS classes that will be put on the menu item
	 *
	 * @api
	 * @param $sCssClass
	 */
	public function AddCssClass($sCssClass)
	{
		$this->aCssClasses[] = $sCssClass;
	}


	/**
	 * @param $sTooltip
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function SetTooltip($sTooltip)
	{
		$this->sTooltip = $sTooltip;
	}

	/**
	 * @return string
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function GetTooltip()
	{
		return $this->sTooltip;
	}

	/**
	 * @param $sIconClass
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function SetIconClass($sIconClass)
	{
		$this->sIconClass = $sIconClass;
	}

	/**
	 * @return string
	 *
	 * @api
	 * @since 3.0.0
	 */
	public function GetIconClass()
	{
		return $this->sIconClass;
	}

	/**
	 * Returns the components to create a popup menu item in HTML
	 *
	 * @return array A hash array: array('label' => , 'url' => , 'target' => , 'onclick' => )
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
 * Note: This works only in the backoffice, {@see \URLButtonItem} for the end-user portal
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class URLPopupMenuItem extends ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sUrl;
	/** @ignore */
	protected $sTarget;

	/**
	 * Constructor
	 *
	 * @api
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sUrl If the menu is an hyperlink, provide the absolute hyperlink here
	 * @param string $sTarget In case the menu is an hyperlink and a specific target is needed (_blank for example), pass it here
	 */
	public function __construct($sUID, $sLabel, $sUrl, $sTarget = '_top')
	{
		parent::__construct($sUID, $sLabel);
		$this->sUrl = $sUrl;
		$this->sTarget = $sTarget;
	}

	/** @ignore */
	public function GetMenuItem()
	{
		return array('label' => $this->GetLabel(),
			'url' => $this->GetUrl(),
			'target' => $this-> GetTarget(),
			'css_classes' => $this->aCssClasses,
			'icon_class' => $this->sIconClass,
			'tooltip' => $this->sTooltip
		);
	}

	/** @ignore */
	public function GetUrl()
	{
		return $this->sUrl;
	}

	/** @ignore */
	public function GetTarget()
	{
		return $this->sTarget;
	}
}

/**
 * Class for adding an item into a popup menu that triggers some Javascript code
 *
 * Note: This works only in the backoffice, {@see \JSButtonItem} for the end-user portal
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class JSPopupMenuItem extends ApplicationPopupMenuItem
{
	/** @ignore */
	protected $sJsCode;
	/** @ignore */
	protected $sUrl;
	/** @ignore */
	protected $aIncludeJSFiles;

	/**
	 * Class for adding an item that triggers some Javascript code
	 *
	 * @api
	 * @param string $sUID The unique identifier of this menu in iTop... make sure you pass something unique enough
	 * @param string $sLabel The display label of the menu (must be localized)
	 * @param string $sJSCode In case the menu consists in executing some havascript code inside the page, pass it here. If supplied $sURL
	 *     ans $sTarget will be ignored
	 * @param array $aIncludeJSFiles An array of file URLs to be included (once) to provide some JS libraries for the page.
	 */
	public function __construct($sUID, $sLabel, $sJSCode, $aIncludeJSFiles = array())
	{
		parent::__construct($sUID, $sLabel);
		$this->sJsCode = $sJSCode;
		$this->sUrl = '#';
		$this->aIncludeJSFiles = $aIncludeJSFiles;
	}

	/** @ignore */
	public function GetMenuItem()
	{
		// Note: the semicolumn is a must here!
		return array(
			'label' => $this->GetLabel(),
			'onclick' => $this->GetJsCode().'; return false;',
			'url' => $this->GetUrl(),
			'css_classes' => $this->GetCssClasses(),
			'icon_class' => $this->sIconClass,
			'tooltip' => $this->sTooltip
		);
	}

	/** @ignore */
	public function GetLinkedScripts()
	{
		return $this->aIncludeJSFiles;
	}

	/** @ignore */
	public function GetJsCode()
	{
		return $this->sJsCode;
	}

	/** @ignore */
	public function GetUrl()
	{
		return $this->sUrl;
	}
}

/**
 * Class for adding a separator (horizontal line, not selectable) the output
 * will automatically reduce several consecutive separators to just one
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class SeparatorPopupMenuItem extends ApplicationPopupMenuItem
{
	static $idx = 0;

	/**
	 * Constructor
	 * @api
	 */
	public function __construct()
	{
		parent::__construct('_separator_'.(self::$idx++), '');
	}

	/** @ignore */
	public function GetMenuItem()
	{
		return array('label' => '<hr class="menu-separator">', 'url' => '', 'css_classes' => $this->aCssClasses);
	}
}

/**
 * Class for adding an item as a button that browses to the given URL
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class URLButtonItem extends URLPopupMenuItem
{

}

/**
 * Class for adding an item as a button that runs some JS code
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 */
class JSButtonItem extends JSPopupMenuItem
{

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
 * @api
 * @package     UIExtensibilityAPI
 * @since 2.0
 * @deprecated 3.0.0 If you need to include:
 *   * JS/CSS files/snippets, use {@see \iBackofficeLinkedScriptsExtension}, {@see \iBackofficeLinkedStylesheetsExtension}, etc instead
 *   * HTML (and optionally JS/CSS), use {@see \iPageUIBlockExtension} to manipulate {@see \Combodo\iTop\Application\UI\Base\UIBlock} instead
 */
interface iPageUIExtension
{
	/**
	 * Add content to the header of the page
	 *
	 * @api
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 *
	 * @return string The HTML content to add into the page
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage);

	/**
	 * Add content to the footer of the page
	 *
	 * @api
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 *
	 * @return string The HTML content to add into the page
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage);

	/**
	 * Add content to the "admin banner"
	 *
	 * @api
	 * @param iTopWebPage $oPage The page to insert stuff into.
	 *
	 * @return string The HTML content to add into the page
	 */
	public function GetBannerHtml(iTopWebPage $oPage);
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
 * @api
 * @package     BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iPageUIBlockExtension
{
	/**
	 * Add content to the "admin banner"
	 *
	 * @api
	 * @return iUIBlock|null The Block to add into the page
	 */
	public function GetBannerBlock();

	/**
	 * Add content to the header of the page
	 *
	 * @api
	 * @return iUIBlock|null The Block to add into the page
	 */
	public function GetHeaderBlock();

	/**
	 * Add content to the footer of the page
	 *
	 * @api
	 * @return iUIBlock|null The Block to add into the page
	 */
	public function GetFooterBlock();
}

/**
 * Extend this class instead of iPageUIExtension if you don't need to overload all methods
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since       2.7.0
 * @deprecated 3.0.0 use AbstractPageUIBlockExtension instead
 */
abstract class AbstractPageUIExtension implements iPageUIExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetNorthPaneHtml(iTopWebPage $oPage)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetSouthPaneHtml(iTopWebPage $oPage)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function GetBannerHtml(iTopWebPage $oPage)
	{
		return '';
	}

}

/**
 * Extend this class instead of iPageUIExtension if you don't need to overload all methods
 *
 * @api
 * @package     UIBlockExtensibilityAPI
 * @since       3.0.0
 */
abstract class AbstractPageUIBlockExtension implements iPageUIBlockExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetBannerBlock()
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetHeaderBlock()
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetFooterBlock()
	{
		return null;
	}
}

/**
 * Implement this interface to add script (JS) files to the backoffice pages
 *
 * @see \iTopWebPage::$a_linked_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeLinkedScriptsExtension
{
	/**
	 * Each script will be included using this property
	 * @api
	 * @see \iTopWebPage::$a_linked_scripts
	 * @return array An array of absolute URLs to the files to include
	 */
	public function GetLinkedScriptsAbsUrls(): array;
}

/**
 * Implement this interface to add inline script (JS) to the backoffice pages' head.
 * Will be executed first, BEFORE the DOM interpretation.
 *
 * @see \iTopWebPage::$a_early_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeEarlyScriptExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_early_scripts
	 * @return string
	 */
	public function GetEarlyScript(): string;
}

/**
 * Implement this interface to add inline script (JS) to the backoffice pages that will be executed immediately, without waiting for the DOM to be ready.
 *
 * @see \iTopWebPage::$a_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeScriptExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_scripts
	 * @return string
	 */
	public function GetScript(): string;
}

/**
 * Implement this interface to add inline script (JS) to the backoffice pages that will be executed right when the DOM is ready.
 *
 * @see \iTopWebPage::$a_init_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeInitScriptExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_init_scripts
	 * @return string
	 */
	public function GetInitScript(): string;
}

/**
 * Implement this interface to add inline script (JS) to the backoffice pages that will be executed slightly AFTER the DOM is ready (just after the init. scripts).
 *
 * @see \iTopWebPage::$a_ready_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeReadyScriptExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_ready_scripts
	 * @return string
	 */
	public function GetReadyScript(): string;
}

/**
 * Implement this interface to add stylesheets (CSS) to the backoffice pages
 *
 * @see \iTopWebPage::$a_linked_stylesheets
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeLinkedStylesheetsExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_linked_stylesheets
	 * @return array An array of absolute URLs to the files to include
	 */
	public function GetLinkedStylesheetsAbsUrls(): array;
}

/**
 * Implement this interface to add inline style (CSS) to the backoffice pages' head.
 *
 * @see \iTopWebPage::$a_styles
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeStyleExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::$a_styles
	 * @return string
	 */
	public function GetStyle(): string;
}

/**
 * Implement this interface to add Dict entries
 *
 * @see \iTopWebPage::$a_dict_entries
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeDictEntriesExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::a_dict_entries
	 * @return array
	 */
	public function GetDictEntries(): array;
}

/**
 * Implement this interface to add Dict entries prefixes
 *
 * @see \iTopWebPage::$a_dict_entries_prefixes
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeDictEntriesPrefixesExtension
{
	/**
	 * @api
	 * @see \iTopWebPage::a_dict_entries_prefixes
	 * @return array
	 */
	public function GetDictEntriesPrefixes(): array;
}

/**
 * Implement this interface to add content to any enhanced portal page
 *
 * @api
 * @package     PortalExtensibilityAPI
 *
 * @since 2.4.0 interface creation
 * @since 2.7.0 change method signatures due to Silex to Symfony migration
 */
interface iPortalUIExtension
{
	const ENUM_PORTAL_EXT_UI_BODY = 'Body';
	const ENUM_PORTAL_EXT_UI_NAVIGATION_MENU = 'NavigationMenu';
	const ENUM_PORTAL_EXT_UI_MAIN_CONTENT = 'MainContent';

	/**
	 * Returns an array of CSS file urls
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return array
	 */
	public function GetCSSFiles(Container $oContainer);

	/**
	 * Returns inline (raw) CSS
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return string
	 */
	public function GetCSSInline(Container $oContainer);

	/**
	 * Returns an array of JS file urls
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return array
	 */
	public function GetJSFiles(Container $oContainer);

	/**
	 * Returns raw JS code
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return string
	 */
	public function GetJSInline(Container $oContainer);

	/**
	 * Returns raw HTML code to put at the end of the <body> tag
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return string
	 */
	public function GetBodyHTML(Container $oContainer);

	/**
	 * Returns raw HTML code to put at the end of the #main-wrapper element
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return string
	 */
	public function GetMainContentHTML(Container $oContainer);

	/**
	 * Returns raw HTML code to put at the end of the #topbar and #sidebar elements
	 *
	 * @api
	 * @param \Symfony\Component\DependencyInjection\Container $oContainer
	 *
	 * @return string
	 */
	public function GetNavigationMenuHTML(Container $oContainer);
}

/**
 * Extend this class instead of iPortalUIExtension if you don't need to overload all methods
 *
 * @api
 * @package     PortalExtensibilityAPI
 * @since       2.4.0
 */
abstract class AbstractPortalUIExtension implements iPortalUIExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetCSSFiles(Container $oContainer)
	{
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function GetCSSInline(Container $oContainer)
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetJSFiles(Container $oContainer)
	{
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function GetJSInline(Container $oContainer)
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetBodyHTML(Container $oContainer)
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetMainContentHTML(Container $oContainer)
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetNavigationMenuHTML(Container $oContainer)
	{
		return null;
	}
}

/**
 * Implement this interface to register a new field renderer mapping to either:
 * - Add the rendering of a new attribute type
 * - Overload the default rendering of an attribute type
 *
 * @since 3.1.0 N°6041
 *
 * @experimental Form / Field / Renderer should be used in more places in next iTop releases, which may introduce major API changes
 */
interface iFieldRendererMappingsExtension
{
	/**
	 * @return array {
	 *              array: {
	 *                  field: string,
	 *                  form_renderer: string,
	 *                  field_renderer: string
	 *              }
	 *          }  List of field renderer mapping: FQCN field class, FQCN Form Renderer class, FQCN Field Renderer class
	 *
	 * Example:
	 *
	 * ```php
	 * [
	 *  ['field' => 'FQCN\FieldA', 'form_renderer' => 'Combodo\iTop\Renderer\Console\ConsoleFormRenderer', 'field_renderer' => 'FQCN\FieldRendererA'],
	 *  ['field' => 'FQCN\FieldB', 'form_renderer' => 'Combodo\iTop\Renderer\Console\ConsoleFormRenderer', 'field_renderer' => 'FQCN\FieldRendererB'],
	 *  ['field' => 'FQCN\FieldA', 'form_renderer' => 'Combodo\iTop\Renderer\Bootstrap\BsFormRenderer', 'field_renderer' => 'FQCN\FieldRendererA'],
	 *  ['field' => 'FQCN\FieldB', 'form_renderer' => 'Combodo\iTop\Renderer\Bootstrap\BsFormRenderer', 'field_renderer' => 'FQCN\FieldRendererB'],
	 * ]
	 * ```
	 */
	public static function RegisterSupportedFields(): array;
}

/**
 * Implement this interface to add new operations to the REST/JSON web service
 *
 * @api
 * @package     RESTExtensibilityAPI
 * @since 2.0.1
 */
interface iRestServiceProvider
{
	/**
	 * Enumerate services delivered by this class
	 *
	 * @api
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 *
	 * @return array An array of hash 'verb' => verb, 'description' => description
	 */
	public function ListOperations($sVersion);

	/**
	 * Enumerate services delivered by this class
	 *
	 * @api
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 * @param string $sVerb
	 * @param array $aParams
	 *
	 * @return RestResult The standardized result structure (at least a message)
	 */
	public function ExecOperation($sVersion, $sVerb, $aParams);
}

/**
 * Minimal REST response structure. Derive this structure to add response data and error codes.
 *
 * @api
 * @package     RESTAPI
 * @since 2.0.1
 */
class RestResult
{
	/**
	 * Result: no issue has been encountered
	 * @api
	 */
	const OK = 0;
	/**
	 * Result: missing/wrong credentials or the user does not have enough rights to perform the requested operation
	 * @api
	 */
	const UNAUTHORIZED = 1;
	/**
	 * Result: the parameter 'version' is missing
	 * @api
	 */
	const MISSING_VERSION = 2;
	/**
	 * Result: the parameter 'json_data' is missing
	 * @api
	 */
	const MISSING_JSON = 3;
	/**
	 * Result: the input structure is not a valid JSON string
	 * @api
	 */
	const INVALID_JSON = 4;
	/**
	 * Result: the parameter 'auth_user' is missing, authentication aborted
	 * @api
	 */
	const MISSING_AUTH_USER = 5;
	/**
	 * Result: the parameter 'auth_pwd' is missing, authentication aborted
	 * @api
	 */
	const MISSING_AUTH_PWD = 6;
	/**
	 * Result: no operation is available for the specified version
	 * @api
	 */
	const UNSUPPORTED_VERSION = 10;
	/**
	 * Result: the requested operation is not valid for the specified version
	 * @api
	 */
	const UNKNOWN_OPERATION = 11;
	/**
	 * Result: the requested operation cannot be performed because it can cause data (integrity) loss
	 * @api
	 */
	const UNSAFE = 12;
	/**
	 * Result: the request page number is not valid. It must be an integer greater than 0
	 * @api
	 */
	const INVALID_PAGE = 13;
	/**
	 * Result: the operation could not be performed, see the message for troubleshooting
	 * @api
	 */
	const INTERNAL_ERROR = 100;

	/**
	 * Default constructor - ok!
	 * @api
	 */
	public function __construct()
	{
		$this->code = RestResult::OK;
	}

	/**
	 * Result code
	 * @var int
	 * @api
	 */
	public $code;
	/**
	 * Result message
	 * @var string
	 * @api
	 */
	public $message;
}

/**
 * Helpers for implementing REST services
 *
 * @api
 * @package     RESTAPI
 */
class RestUtils
{
	/**
	 * Registering tracking information. Any further object modification be associated with the given comment, when the modification gets
	 * recorded into the DB
	 *
	 * @api
	 *
	 * @param StdClass $oData Structured input data. Must contain 'comment'.
	 *
	 * @return void
	 * @throws Exception
	 */
	public static function InitTrackingComment($oData)
	{
		$sComment = self::GetMandatoryParam($oData, 'comment');
		CMDBObject::SetTrackInfo($sComment);
	}

	/**
	 * Read a mandatory parameter from  from a Rest/Json structure.
	 *
	 * @api
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 *
	 * @return mixed parameter value if present
	 * @throws Exception If the parameter is missing
	 */
	public static function GetMandatoryParam($oData, $sParamName)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			throw new Exception("Missing parameter '$sParamName'");
		}
	}


	/**
	 * Read an optional parameter from a Rest/Json structure.
	 *
	 * @api
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param mixed $default Default value if the parameter is not found in the input data
	 *
	 * @param StdClass $oData Structured input data.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public static function GetOptionalParam($oData, $sParamName, $default)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			return $default;
		}
	}


	/**
	 * Read a class from a Rest/Json structure.
	 *
	 * @api
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 *
	 * @return string
	 * @throws Exception If the parameter is missing or the class is unknown
	 */
	public static function GetClass($oData, $sParamName)
	{
		$sClass = self::GetMandatoryParam($oData, $sParamName);
		if (!MetaModel::IsValidClass($sClass))
		{
			throw new Exception("$sParamName: '$sClass' is not a valid class'");
		}

		return $sClass;
	}


	/**
	 * Read a list of attribute codes from a Rest/Json structure.
	 *
	 * @api
	 * @param StdClass $oData Structured input data.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 *
	 * @param string $sClass Name of the class
	 *
	 * @return array of class => list of attributes (see RestResultWithObjects::AddObject that uses it)
	 * @throws Exception
	 */
	public static function GetFieldList($sClass, $oData, $sParamName)
	{
		$sFields = self::GetOptionalParam($oData, $sParamName, '*');
		$aShowFields = array();
		if ($sFields == '*')
		{
			foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				$aShowFields[$sClass][] = $sAttCode;
			}
		}
		elseif ($sFields == '*+')
		{
			foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sRefClass)
			{
				foreach (MetaModel::ListAttributeDefs($sRefClass) as $sAttCode => $oAttDef)
				{
					$aShowFields[$sRefClass][] = $sAttCode;
				}
			}
		}
		else
		{
			foreach (explode(',', $sFields) as $sAttCode)
			{
				$sAttCode = trim($sAttCode);
				if (($sAttCode != 'id') && (!MetaModel::IsValidAttCode($sClass, $sAttCode)))
				{
					throw new Exception("$sParamName: invalid attribute code '$sAttCode'");
				}
				$aShowFields[$sClass][] = $sAttCode;
			}
		}

		return $aShowFields;
	}

	/**
	 * Read and interpret object search criteria from a Rest/Json structure
	 *
	 * @api
	 * @param string $sClass Name of the class
	 * @param StdClass $oCriteria Hash of attribute code => value (can be a substructure or a scalar, depending on the nature of the
	 *     attriute)
	 *
	 * @return object The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 */
	protected static function FindObjectFromCriteria($sClass, $oCriteria)
	{
		$aCriteriaReport = array();
		if (isset($oCriteria->finalclass))
		{
			if (!MetaModel::IsValidClass($oCriteria->finalclass))
			{
				throw new Exception("finalclass: Unknown class '".$oCriteria->finalclass."'");
			}
			if (!MetaModel::IsParentClass($sClass, $oCriteria->finalclass))
			{
				throw new Exception("finalclass: '".$oCriteria->finalclass."' is not a child class of '$sClass'");
			}
			$sClass = $oCriteria->finalclass;
		}
		$oSearch = new DBObjectSearch($sClass);
		foreach ($oCriteria as $sAttCode => $value)
		{
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			$oSearch->AddCondition($sAttCode, $realValue, '=');
			if (is_object($value) || is_array($value))
			{
				$value = json_encode($value);
			}
			$aCriteriaReport[] = "$sAttCode: $value ($realValue)";
		}
		$oSet = new DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		if ($iCount == 0)
		{
			throw new Exception("No item found with criteria: ".implode(', ', $aCriteriaReport));
		}
		elseif ($iCount > 1)
		{
			throw new Exception("Several items found ($iCount) with criteria: ".implode(', ', $aCriteriaReport));
		}
		$res = $oSet->Fetch();

		return $res;
	}


	/**
	 * Find an object from a polymorph search specification (Rest/Json)
	 *
	 * @api
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @param bool $bAllowNullValue Allow the cases such as key = 0 or key = {null} and return null then
	 * @param string $sClass Name of the class
	 *
	 * @return DBObject The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 *
	 * @see DBObject::CheckChangedExtKeysValues() generic method to check that we can access the linked object isn't used in that use case because values can be literal, OQL, friendlyname
	 */
	public static function FindObjectFromKey($sClass, $key, $bAllowNullValue = false)
	{
		if (is_object($key))
		{
			$res = static::FindObjectFromCriteria($sClass, $key);
		}
		elseif (is_numeric($key))
		{
			if ($bAllowNullValue && ($key == 0))
			{
				$res = null;
			}
			else
			{
				$res = MetaModel::GetObject($sClass, $key, false);
				if (is_null($res))
				{
					throw new Exception("Invalid object $sClass::$key");
				}
			}
		}
		elseif (is_string($key))
		{
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			if ($iCount == 0)
			{
				throw new Exception("No item found for query: $key");
			}
			elseif ($iCount > 1)
			{
				throw new Exception("Several items found ($iCount) for query: $key");
			}
			$res = $oSet->Fetch();
		}
		else
		{
			throw new Exception("Wrong format for key");
		}

		return $res;
	}

	/**
	 * Search objects from a polymorph search specification (Rest/Json)
	 *
	 * @api
	 * @param string $sClass Name of the class
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @param int $iLimit The limit of results to return
	 * @param int $iOffset The offset of results to return
	 *
	 * @return DBObjectSet The search result set
	 * @throws Exception If the input structure is not valid
	 */
	public static function GetObjectSetFromKey($sClass, $key, $iLimit = 0, $iOffset = 0)
	{
		if (is_object($key))
		{
			if (isset($key->finalclass))
			{
				$sClass = $key->finalclass;
				if (!MetaModel::IsValidClass($sClass))
				{
					throw new Exception("finalclass: Unknown class '$sClass'");
				}
			}

			$oSearch = new DBObjectSearch($sClass);
			foreach ($key as $sAttCode => $value)
			{
				$realValue = static::MakeValue($sClass, $sAttCode, $value);
				$oSearch->AddCondition($sAttCode, $realValue, '=');
			}
		}
		elseif (is_numeric($key))
		{
			$oSearch = new DBObjectSearch($sClass);
			$oSearch->AddCondition('id', $key);
		}
		elseif (is_string($key))
		{
			// OQL
            try {
                $oSearch = DBObjectSearch::FromOQL($key);
            } catch (Exception $e) {
                throw new CoreOqlException('Query failed to execute', [
                        'query' => $key,
                        'exception_class' => get_class($e),
                        'exception_message' => $e->getMessage(),
                ]);
            }
        }
		else
		{
			throw new Exception("Wrong format for key");
		}
		$oObjectSet = new DBObjectSet($oSearch, array(), array(), null, $iLimit, $iOffset);

		return $oObjectSet;
	}

	/**
	 * Interpret the Rest/Json value and get a valid attribute value
	 *
	 * @api
	 * @param string $sAttCode Attribute code
	 * @param mixed $value Depending on the type of attribute (a scalar, or search criteria, or list of related objects...)
	 * @param string $sClass Name of the class
	 *
	 * @return mixed The value that can be used with DBObject::Set()
	 * @throws Exception If the specification of the value is not valid.
	 */
	public static function MakeValue($sClass, $sAttCode, $value)
	{
		try
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new Exception("Unknown attribute");
			}
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef instanceof AttributeExternalKey)
			{
				$oExtKeyObject = static::FindObjectFromKey($oAttDef->GetTargetClass(), $value, true /* allow null */);
				$value = ($oExtKeyObject != null) ? $oExtKeyObject->GetKey() : 0;
			}
			elseif ($oAttDef instanceof AttributeLinkedSet)
			{
				if (!is_array($value))
				{
					throw new Exception("A link set must be defined by an array of objects");
				}
				$sLnkClass = $oAttDef->GetLinkedClass();
				$aLinks = array();
				foreach ($value as $oValues)
				{
					$oLnk = static::MakeObjectFromFields($sLnkClass, $oValues);
					// Fix for N°1939
					if (($oAttDef instanceof AttributeLinkedSetIndirect) && ($oLnk->Get($oAttDef->GetExtKeyToRemote()) == 0))
					{
						continue;
					}
					$aLinks[] = $oLnk;
				}
				$value = DBObjectSet::FromArray($sLnkClass, $aLinks);
			}
			elseif ($oAttDef instanceof AttributeTagSet)
			{
				if (!is_array($value))
				{
					throw new Exception("A tag set must be defined by an array of tag codes");
				}
				$value = $oAttDef->FromJSONToValue($value);
			}
			else
			{
				$value = $oAttDef->FromJSONToValue($value);
			}
		}
		catch (Exception $e)
		{
			throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
		}

		return $value;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and build an object
	 *
	 * @api
	 * @param array $aFields A hash of attribute code => value specification.
	 * @param string $sClass Name of the class
	 *
	 * @return DBObject The newly created object
	 * @throws Exception If the specification of the values is not valid
	 */
	public static function MakeObjectFromFields($sClass, $aFields)
	{
		$oObject = MetaModel::NewObject($sClass);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			try
			{
				$oObject->Set($sAttCode, $realValue);
			}
			catch (Exception $e)
			{
				throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
			}
		}

		return $oObject;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and update the given object
	 *
	 * @api
	 * @param array $aFields A hash of attribute code => value specification.
	 * @param DBObject $oObject The object being modified
	 *
	 * @return DBObject The object modified
	 * @throws Exception If the specification of the values is not valid
	 */
	public static function UpdateObjectFromFields($oObject, $aFields)
	{
		$sClass = get_class($oObject);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			try
			{
				$oObject->Set($sAttCode, $realValue);
			}
			catch (Exception $e)
			{
				throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
			}
		}

		return $oObject;
	}
}


/**
 * Helpers for modules extensibility, with discover performed by the MetaModel.
 *
 *
 * @api
 * @package     Extensibility
 */
interface iModuleExtension
{
	/**
	 * @api
	 */
	public function __construct();
}

/**
 * KPI logging extensibility point
 *
 * KPI Logger extension
 */
interface iKPILoggerExtension
{
    /**
     * Init the statistics collected
     *
     * @return void
     */
    public function InitStats();

    /**
     * Add a new KPI to the stats
     *
     * @param \Combodo\iTop\Core\Kpi\KpiLogData $oKpiLogData
     *
     * @return mixed
     */
    public function LogOperation($oKpiLogData);
}

/**
 * Implement this interface to add files to the backup
 *
 * @api
 * @since 3.2.0
 */
interface iBackupExtraFilesExtension
{
	/**
	 * @api
	 * @return string[] Array of relative paths (from app root) for files and directories to be included in the backup
	 */
	public function GetExtraFilesRelPaths(): array;
}


/**
 * Interface to provide messages to be displayed in the "Welcome Popup"
 *
 * @api
 * @since 3.2.0
 */
interface iWelcomePopupExtension
{
	// Importance for ordering messages
	// Just two levels since less important messages have nothing to do in the welcome popup
	public const ENUM_IMPORTANCE_CRITICAL = 0;
	public const ENUM_IMPORTANCE_HIGH = 1;
	public const DEFAULT_IMPORTANCE = self::ENUM_IMPORTANCE_HIGH;

	/**
	 * Overload this method if you need to display an icon representing the provider (eg. your own company logo, module icon, ...)
	 *
	 * @api
	 * @return string Relative path (from app. root) of the icon representing the provider
	 */
	public function GetIconRelPath(): string;

	/**
	 * @api
	 * @return \Combodo\iTop\Application\WelcomePopup\Message[]
	 */
	public function GetMessages(): array;

	/**
	 * Overload this method if the provider needs to do some additional processing after the message ($sMessageId) has been acknowledged by the current user
	 *
	 * @param string $sMessageId
	 * @api
	 */
	public function AcknowledgeMessage(string $sMessageId): void;
}

/**
 * Inherit from this class to provide messages to be displayed in the "Welcome Popup"
 *
 * @api
 * @since 3.2.0
 */
abstract class AbstractWelcomePopupExtension implements iWelcomePopupExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetIconRelPath(): string
	{
		return \Combodo\iTop\Application\Branding::$aLogoPaths[\Combodo\iTop\Application\Branding::ENUM_LOGO_TYPE_MAIN_LOGO_COMPACT]['default'];
	}

	/**
	 * @inheritDoc
	 */
	public function GetMessages(): array
	{
		return [];
	}
	
	/**
	 * @inheritDoc
	 */
	public function AcknowledgeMessage(string $sMessageId): void
	{
		// No need to process the acknowledgment notice by default
		return;
	}
}