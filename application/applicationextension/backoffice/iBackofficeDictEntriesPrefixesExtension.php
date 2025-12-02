<?php

/**
 * Implement this interface to add Dict entries prefixes
 *
 * @see \iTopWebPage::$a_dict_entries_prefixes
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeDictEntriesPrefixesExtension
{
	/**
	 * @return array
	 * @see \iTopWebPage::a_dict_entries_prefixes
	 * @api
	 */
	public function GetDictEntriesPrefixes(): array;
}
