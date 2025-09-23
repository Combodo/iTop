<?php

/**
 * Implement this interface to add inline script (JS) to the backoffice pages' head.
 * Will be executed first, BEFORE the DOM interpretation.
 *
 * @see \iTopWebPage::$a_early_scripts
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeEarlyScriptExtension
{
    /**
     * @return string
     * @see \iTopWebPage::$a_early_scripts
     * @api
     */
    public function GetEarlyScript(): string;
}