<?php

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
	public function DisplayPreferences(\Combodo\iTop\Application\WebPage\WebPage $oPage)
	{
		// Do nothing
	}

	/**
	 * @inheritDoc
	 */
	public function ApplyPreferences(\Combodo\iTop\Application\WebPage\WebPage $oPage, $sOperation)
	{
		// Do nothing
	}

}
