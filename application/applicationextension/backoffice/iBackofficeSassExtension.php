<?php

/**
 * Implement this interface to add sass files (SCSS) to the backoffice pages.
 * example: return "css/setup.scss"
 *
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.3.0
 */
interface iBackofficeSassExtension
{
	/**
	 * @return array An array of relative paths (from loaded import paths) to the files to compile and include
	 * @see \iTopWebPage::$a_linked_stylesheets
	 * @api
	 */
	public function GetSassRelPaths(): array;
}
