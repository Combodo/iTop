<?php

/**
 * Implement this interface to add sass file (SCSS) to the backoffice pages.
 * example: return "css/setup.scss"
 *
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.3.0
 */
interface iBackofficeSassExtension
{
	/**
	 * @return string
	 * @see \iTopWebPage::$a_styles
	 * @api
	 */
	public function GetSass(): string;
}
