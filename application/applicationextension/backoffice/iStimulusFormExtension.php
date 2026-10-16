<?php

/**
 * Implement this interface to change the behavior of stimulus forms.
 *
 * @api
 * @package     UIExtensibilityAPI
 */
interface iStimulusFormExtension
{
	/**
	 *    Invoked when a dialog before applying a stimulus is being displayed.
	 *
	 * The method is called right after the dialog for fields like solution of UserRequest is displayed.
	 * You can add output to the page, either to change the display, or to add a form input
	 *
	 * @api
	 * @param DBObject $oObject The object being displayed
	 * @param WebPage $oPage The output context
	 *
	 * @return void
	 */
	public function OnDisplayStimulusForm($oObject, WebPage $oPage);
}
