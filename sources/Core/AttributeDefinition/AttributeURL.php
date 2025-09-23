<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOpSetAttributeURL;
use CMDBSource;
use Combodo\iTop\Form\Field\UrlField;
use CoreException;
use DBObject;
use Exception;
use Str;
use utils;

/**
 * Map a varchar column to an URL (formats the ouput in HMTL)
 *
 * @package     iTopORM
 */
class AttributeURL extends AttributeString
{
	/**
	 * @var string
	 * SCHEME....... USER....................... PASSWORD.......................... HOST/IP........... PORT.......... PATH......................... GET............................................ ANCHOR..........................
	 * Example: http://User:passWord@127.0.0.1:8888/patH/Page.php?arrayArgument[2]=something:blah20#myAnchor
	 * @link http://www.php.net/manual/fr/function.preg-match.php#93824 regexp source
	 * @since 3.0.1 N°4515 handle Alfresco and Sharepoint URLs
	 * @since 3.0.3 moved from Config to AttributeURL constant
	 */
	public const DEFAULT_VALIDATION_PATTERN = /** @lang RegExp */
		'(https?|ftp)\://([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?([a-zA-Z0-9-.]{3,})(\:[0-9]{2,5})?(/([a-zA-Z0-9:%@+\$_-]\.?)+)*/?(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:[\]@&%=+/\$_.,-]*)?(#[a-zA-Z0-9_.-][a-zA-Z0-9+\$_.-]*)?';

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
		//return parent::ListExpectedParams();
		return array_merge(parent::ListExpectedParams(), array("target"));
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(2048)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetMaxSize()
	{
		return 2048;
	}

	public function GetEditClass()
	{
		return "String";
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sTarget = $this->Get("target");
		if (empty($sTarget)) {
			$sTarget = "_blank";
		}
		$sLabel = Str::pure2html($sValue);
		if (strlen($sLabel) > 128) {
			// Truncate the length to 128 characters, by removing the middle
			$sLabel = substr($sLabel, 0, 100).'.....'.substr($sLabel, -20);
		}

		return "<a target=\"$sTarget\" href=\"$sValue\">$sLabel</a>";
	}

	public function GetValidationPattern()
	{
		return $this->GetOptional('validation_pattern', '^'.utils::GetConfig()->Get('url_validation_pattern').'$');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\UrlField';
	}

	/**
	 * @param DBObject $oObject
	 * @param UrlField $oFormField
	 *
	 * @return null
	 * @throws CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		$oFormField->SetTarget($this->Get('target'));

		return $oFormField;
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeURL::class;
	}
}