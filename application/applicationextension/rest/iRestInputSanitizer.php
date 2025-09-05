<?php

/**
 * A REST service provider implementing this interface will have its input JSON data sanitized for logging purposes
 *
 * @see \iRestServiceProvider
 * @since 2.7.13, 3.2.1-1
 */
interface iRestInputSanitizer
{
    public function SanitizeJsonInput(string $sJsonInput): string;
}