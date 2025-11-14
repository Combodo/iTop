<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

/**
 * Map a text column (size > ?), containing HTML code, to an attribute
 *
 * @package     iTopORM
 */
class AttributeHTML extends AttributeLongText
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = [];
		$aColumns[$this->Get('sql')] = $this->GetSQLCol();
		if ($this->GetOptional('format', null) != null) {
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('text','html')";
			if ($bFullSpec) {
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'html'"; // default 'html' is for migrating old records
			}
		}

		return $aColumns;
	}

	/**
	 * The actual formatting of the text: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'html'); // Defaults to HTML
	}
}
