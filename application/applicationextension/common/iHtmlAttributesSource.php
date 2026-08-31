<?php

/**
 * Implemented by metadata value objects that flatten to HTML attributes.
 * Ease the implementation of classes that need to provide HTML attributes to the UI components
 *
 * @api
 * @since 3.3.0
 */
interface iHtmlAttributesSource
{
	/** @return array<string, string> Attribute name => value, ready to be escaped and printed */
	public function GetHtmlAttributes(): array;
}
