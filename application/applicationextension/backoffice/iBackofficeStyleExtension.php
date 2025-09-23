<?php

/**
 * Implement this interface to add inline style (CSS) to the backoffice pages' head.
 *
 * @see \iTopWebPage::$a_styles
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeStyleExtension
{
    /**
     * @return string
     * @see \iTopWebPage::$a_styles
     * @api
     */
    public function GetStyle(): string;
}