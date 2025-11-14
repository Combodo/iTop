<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use Exception;
use utils;

/**
 * Map a varchar column (size < ?) to an attribute that must never be shown to the user
 *
 * @package     iTopORM
 */
class AttributePassword extends AttributeString implements iAttributeNoGroupBy
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

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

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Password";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(64)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetMaxSize()
	{
		return 64;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (utils::IsNullOrEmptyString($sValue)) {
			return '';
		} else {
			return '******';
		}
	}

	public function IsPartOfFingerprint()
	{
		return false;
	} // Cannot reliably compare two encrypted passwords since the same password will be encrypted in diffferent manners depending on the random 'salt'
}
