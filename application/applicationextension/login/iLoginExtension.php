<?php

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
	 * @return array of supported login modes
	 * @api
	 */
	public function ListSupportedLoginModes();
}
