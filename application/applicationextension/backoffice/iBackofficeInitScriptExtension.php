<?php

/**
 * Implement this interface to add inline script (JS) to the backoffice pages that will be executed right when the DOM is ready.
 *
 * @see \iTopWebPage::$a_init_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeInitScriptExtension
{
    /**
     * @return string
     * @see \iTopWebPage::$a_init_scripts
     * @api
     */
    public function GetInitScript(): string;
}