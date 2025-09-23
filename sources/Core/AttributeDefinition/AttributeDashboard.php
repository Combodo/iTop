<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use MetaModel;
use RuntimeDashboard;
use utils;

class AttributeDashboard extends AttributeDefinition
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

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(),
			array("definition_file", "is_user_editable"));
	}

	public function GetDashboard()
	{
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		$sFilePath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/'.$this->Get('definition_file');

		return RuntimeDashboard::GetDashboard($sFilePath, $sClass.'__'.$sAttCode);
	}

	public function IsUserEditable()
	{
		return $this->Get('is_user_editable');
	}

	public function IsWritable()
	{
		return false;
	}

	public function GetEditClass()
	{
		return "";
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return null;
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return '';
	}

	/**
	 * @inheritdoc
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		return null;
	}

	// if this verb returns false, then GetValue must be implemented
	public static function LoadInObject()
	{
		return false;
	}

	public function GetValue($oHostObject)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// Always return false for now, we don't consider a custom version of a dashboard
		return false;
	}
}