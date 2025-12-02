<?php

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
	 *
	 *@param \Combodo\iTop\Application\WebPage\WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 *
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return void
	 */
	public function OnDisplayProperties($oObject, \Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false);

	/**
	 * Invoked when an object is being displayed (wiew or edit)
	 *
	 * The method is called rigth after all the tabs have been displayed
	 *
	 * @api
	 *
	 *@param \Combodo\iTop\Application\WebPage\WebPage $oPage The output context
	 * @param boolean $bEditMode True if the edition form is being displayed
	 *
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return void
	 */
	public function OnDisplayRelations($oObject, \Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false);

	/**
	 * Invoked when the end-user clicks on Modify from the object edition form
	 *
	 * The method is called after the changes from the standard form have been
	 * taken into account, and before saving the changes into the database.
	 *
	 * @param DBObject $oObject The object being edited
	 * @param string $sFormPrefix Prefix given to the HTML form inputs
	 *
	 * @return void
	 * @api
	 */
	public function OnFormSubmit($oObject, $sFormPrefix = '');

	/**
	 * Invoked when the end-user clicks on Cancel from the object edition form
	 *
	 * Implement here any cleanup. This is necessary when you have injected some
	 * javascript into the edition form, and if that code requires to store temporary data
	 * (this is the case when a file must be uploaded).
	 *
	 * @param string $sTempId Unique temporary identifier made of session_id and transaction_id. It identifies the object in a unique way.
	 *
	 * @return void
	 * @api
	 */
	public function OnFormCancel($sTempId);

	/**
	 * Not yet called by the framework!
	 *
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 *
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return string[] desc
	 * @api
	 */
	public function EnumUsedAttributes($oObject); // Not yet implemented

	/**
	 * Not yet called by the framework!
	 *
	 * Sorry, the verb has been reserved. You must implement it, but it is not called as of now.
	 *
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return string Path of the icon, relative to the modules directory.
	 * @api
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
	 * @param DBObject $oObject The object being displayed
	 *
	 * @return integer The value representing the mood of the object
	 * @api
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
	 * @param DBObjectSet $oSet A set of persistent objects (DBObject)
	 *
	 * @return array
	 * @api
	 */
	public function EnumAllowedActions(DBObjectSet $oSet);
}
