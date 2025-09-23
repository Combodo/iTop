<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use DateTimeFormat;
use DBObject;

/**
 * Map a date+time column to an attribute
 *
 * @package     iTopORM
 */
class AttributeDate extends AttributeDateTime
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_DATE;

	public static $oDateFormat = null;

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

	public static function GetFormat()
	{
		if (self::$oDateFormat == null) {
			AttributeDateTime::LoadFormatFromConfig();
		}

		return self::$oDateFormat;
	}

	public static function SetFormat(DateTimeFormat $oDateFormat)
	{
		self::$oDateFormat = $oDateFormat;
	}

	/**
	 * Returns the format string used for the date & time stored in memory
	 *
	 * @return string
	 */
	public static function GetInternalFormat()
	{
		return 'Y-m-d';
	}

	/**
	 * Returns the format string used for the date & time written to MySQL
	 *
	 * @return string
	 */
	public static function GetSQLFormat()
	{
		return 'Y-m-d';
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Date";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "DATE";
	}

	public function GetImportColumns()
	{
		// Allow an empty string to be a valid value (synonym for "reset")
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'VARCHAR(10)'.CMDBSource::GetSqlStringColumnDefinition();

		return $aColumns;
	}


	/**
	 * Override to specify Field class
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behave more like a Prepare.
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		$oFormField = parent::MakeFormField($oObject, $oFormField);
		$oFormField->SetDateOnly(true);

		return $oFormField;
	}

}