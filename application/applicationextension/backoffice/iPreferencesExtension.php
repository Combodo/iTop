<?php

/**
 * @api
 * @package     PreferencesExtensibilityAPI
 * @since 2.7.0
 */
interface iPreferencesExtension
{
	/**
	 * @api
	 *
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 *
	 */
	public function DisplayPreferences(\Combodo\iTop\Application\WebPage\WebPage $oPage);

	/**
	 * @api
	 *
	 * @param string $sOperation
	 *
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 *
	 * @return bool true if the operation has been used
	 */
	public function ApplyPreferences(\Combodo\iTop\Application\WebPage\WebPage $oPage, $sOperation);
}
