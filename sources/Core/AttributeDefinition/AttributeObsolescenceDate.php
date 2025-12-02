<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Dict;
use Exception;

class AttributeObsolescenceDate extends AttributeDate
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

	public function GetLabel($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceDate/Label', $sDefault);

		return parent::GetLabel($sDefault);
	}

	public function GetDescription($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceDate/Label+', $sDefault);

		return parent::GetDescription($sDefault);
	}
}
