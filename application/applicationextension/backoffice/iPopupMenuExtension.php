<?php

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
	public const MENU_OBJLIST_ACTIONS = 1;
	/**
	 * Insert an item into the Toolkit menu of a list
	 *
	 * $param is a DBObjectSet containing the list of objects
	 * @api
	 */
	public const MENU_OBJLIST_TOOLKIT = 2;
	/**
	 * Insert an item into the Actions menu on an object details page
	 *
	 * $param is a DBObject instance: the object currently displayed
	 * @api
	 */
	public const MENU_OBJDETAILS_ACTIONS = 3;
	/**
	 * Insert an item into the Dashboard menu
	 *
	 * The dashboad menu is shown on the top right corner when a dashboard
	 * is being displayed.
	 *
	 * $param is a Dashboard instance: the dashboard currently displayed
	 * @api
	 */
	public const MENU_DASHBOARD_ACTIONS = 4;
	/**
	 * Insert an item into the User menu (upper right corner)
	 *
	 * $param is null
	 * @api
	 */
	public const MENU_USER_ACTIONS = 5;
	/**
	 * Insert an item into the Action menu on an object item in an objects list in the portal
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object' => $oObject) containing the portal id and a DBObject instance (the object on
	 * the current line)
	 * @api
	 */
	public const PORTAL_OBJLISTITEM_ACTIONS = 7;
	/**
	 * Insert an item into the Action menu on an object details page in the portal
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object' => $oObject) containing the portal id and a DBObject instance (the object
	 * currently displayed)
	 * @api
	 */
	public const PORTAL_OBJDETAILS_ACTIONS = 8;

	/**
	 * Insert an item into the Actions menu of a list in the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is an array('portal_id' => $sPortalId, 'object_set' => $oSet) containing DBObjectSet containing the list of objects
	 *
	 * @todo
	 */
	public const PORTAL_OBJLIST_ACTIONS = 6;
	/**
	 * Insert an item into the user menu of the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is the portal id
	 *
	 * @todo
	 */
	public const PORTAL_USER_ACTIONS = 9;
	/**
	 * Insert an item into the navigation menu of the portal
	 * Note: This is not implemented yet !
	 *
	 * $param is the portal id
	 *
	 * @todo
	 */
	public const PORTAL_MENU_ACTIONS = 10;

	/**
	 * Get the list of items to be added to a menu.
	 *
	 * This method is called by the framework for each menu.
	 * The items will be inserted in the menu in the order of the returned array.
	 *
	 * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx
	 * @param mixed $param Depends on $iMenuId, see the constants defined above
	 *
	 * @return object[] An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
	 * @api
	 */
	public static function EnumItems($iMenuId, $param);
}
