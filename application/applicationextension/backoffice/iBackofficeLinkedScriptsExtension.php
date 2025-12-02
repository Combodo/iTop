<?php

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
	 * @return array An array of absolute URLs to the files to include
	 * @see \iTopWebPage::$a_linked_scripts
	 * @api
	 */
	public function GetLinkedScriptsAbsUrls(): array;
}
