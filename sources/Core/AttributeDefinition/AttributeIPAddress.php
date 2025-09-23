<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

/**
 * Specialization of a string: IP address
 *
 * @package     iTopORM
 */
class AttributeIPAddress extends AttributeString
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

	public function GetValidationPattern()
	{
		$sNum = '(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])';

		return "^($sNum\\.$sNum\\.$sNum\\.$sNum)$";
	}

	public function GetOrderBySQLExpressions($sClassAlias)
	{
		// Note: This is the responsibility of this function to place backticks around column aliases
		return array('INET_ATON(`'.$sClassAlias.$this->GetCode().'`)');
	}
}