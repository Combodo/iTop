<?php

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
	 * @return string
	 * @see \iTopWebPage::$a_ready_scripts
	 * @api
	 */
	public function GetReadyScript(): string;
}
