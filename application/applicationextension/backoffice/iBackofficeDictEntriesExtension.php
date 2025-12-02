<?php

/**
 * Implement this interface to add Dict entries
 *
 * @see \iTopWebPage::$a_dict_entries
 * @api
 * @package BackofficeUIExtensibilityAPI
 * @since 3.0.0
 */
interface iBackofficeDictEntriesExtension
{
	/**
	 * @return array
	 * @see \iTopWebPage::a_dict_entries
	 * @api
	 */
	public function GetDictEntries(): array;
}
