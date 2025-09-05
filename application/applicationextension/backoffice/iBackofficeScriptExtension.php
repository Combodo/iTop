<?php

/**
 * Implement this interface to add inline script (JS) to the backoffice pages that will be executed immediately, without waiting for the DOM to be ready.
 *
 * @see \iTopWebPage::$a_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeScriptExtension
{
    /**
     * @return string
     * @see \iTopWebPage::$a_scripts
     * @api
     */
    public function GetScript(): string;
}