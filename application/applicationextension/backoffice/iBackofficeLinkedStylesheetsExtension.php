<?php

/**
 * Implement this interface to add stylesheets (CSS) to the backoffice pages
 *
 * @see \iTopWebPage::$a_linked_stylesheets
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeLinkedStylesheetsExtension
{
    /**
     * @return array An array of absolute URLs to the files to include
     * @see \iTopWebPage::$a_linked_stylesheets
     * @api
     */
    public function GetLinkedStylesheetsAbsUrls(): array;
}