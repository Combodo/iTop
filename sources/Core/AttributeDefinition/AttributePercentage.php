<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Exception;

/**
 * Display an integer between 0 and 100 as a percentage / horizontal bar graph
 *
 * @package     iTopORM
 */
class AttributePercentage extends AttributeInteger
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_NUMERIC;

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

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$iWidth = 5; // Total width of the percentage bar graph, in em...
		$iValue = (int)$sValue;
		if ($iValue > 100) {
			$iValue = 100;
		} else {
			if ($iValue < 0) {
				$iValue = 0;
			}
		}
		if ($iValue > 90) {
			$sColor = "#cc3300";
		} else {
			if ($iValue > 50) {
				$sColor = "#cccc00";
			} else {
				$sColor = "#33cc00";
			}
		}
		$iPercentWidth = ($iWidth * $iValue) / 100;

		return "<div style=\"width:{$iWidth}em;-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;display:inline-block;border: 1px #ccc solid;\"><div style=\"width:{$iPercentWidth}em; display:inline-block;background-color:$sColor;\">&nbsp;</div></div>&nbsp;$sValue %";
	}
}