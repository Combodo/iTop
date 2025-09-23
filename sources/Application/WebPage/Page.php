<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

/**
 * Generic interface common to CLI and Web pages
 */
interface Page
{
	/**
	 * Outputs (via some echo) the complete HTML page by assembling all its elements
	 *
	 * @return mixed
	 */
	public function output();

	/**
	 * Add any text or HTML fragment to the body of the page
	 *
	 * @param string $sText
	 *
	 * @return void
	 */
	public function add($sText);

	/**
	 * Add a paragraph to the body of the page
	 *
	 * @param string $sText
	 *
	 * @return void
	 */
	public function p($sText);

	/**
	 * Add a pre-formatted text to the body of the page
	 *
	 * @param string $sText
	 *
	 * @return void
	 */
	public function pre($sText);

	/**
	 * Add a comment
	 *
	 * @param string $sText
	 *
	 * @return void
	 */
	public function add_comment($sText);

	/**
	 * Adds a tabular content to the web page
	 *
	 * @param string[] $aConfig Configuration of the table: hash array of 'column_id' => 'Column Label'
	 * @param string[] $aData Hash array. Data to display in the table: each row is made of 'column_id' => Data. A
	 *     column 'pkey' is expected for each row
	 * @param array $aParams Hash array. Extra parameters for the table.
	 *
	 * @return void
	 */
	public function table($aConfig, $aData, $aParams = array());
}
