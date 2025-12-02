<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Dict;
use ValueSetEnum;

/**
 * An attibute that matches one of the language codes availables in the dictionnary
 *
 * @package     iTopORM
 */
class AttributeApplicationLanguage extends AttributeString
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
	}

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aAvailableLanguages = Dict::GetLanguages();
		$aLanguageCodes = [];
		foreach ($aAvailableLanguages as $sLangCode => $aInfo) {
			$aLanguageCodes[$sLangCode] = $aInfo['description'].' ('.$aInfo['localized_description'].')';
		}

		// N°6462 This should be sorted directly in \Dict during the compilation but we can't for 2 reasons:
		// - Additional languages can be added on the fly even though it is not recommended
		// - Formatting is done at run time (just above)
		natcasesort($aLanguageCodes);

		$aParams["allowed_values"] = new ValueSetEnum($aLanguageCodes);
		parent::__construct($sCode, $aParams);
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}
}
