<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Exception;
use utils;

/**
 * Specialization of a string: phone number
 *
 * @package     iTopORM
 */
class AttributePhoneNumber extends AttributeString
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
		return $this->GetOptional(
			'validation_pattern',
			'^'.utils::GetConfig()->Get('phone_number_validation_pattern').'$'
		);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\PhoneField';
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue)) {
			return '';
		}

		$sUrlDecorationClass = utils::GetConfig()->Get('phone_number_decoration_class');
		$sUrlPattern = utils::GetConfig()->Get('phone_number_url_pattern');
		$sUrl = sprintf($sUrlPattern, $sValue);

		return '<a class="tel" href="'.$sUrl.'"><span class="text_decoration '.$sUrlDecorationClass.'"></span>'.parent::GetAsHTML($sValue).'</a>';
	}

}
