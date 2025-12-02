<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use MetaModel;
use ValueSetEnumClasses;

/**
 * An attribute that matches an object class
 *
 * @package     iTopORM
 */
class AttributeClass extends AttributeString
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_ENUM;

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), ['class_category', 'more_values']);
	}

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aParams["allowed_values"] = new ValueSetEnumClasses($aParams['class_category'], $aParams['more_values']);
		parent::__construct($sCode, $aParams);
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$sDefault = parent::GetDefaultValue($oHostObject);
		if (!$this->IsNullAllowed() && $this->IsNull($sDefault)) {
			// For this kind of attribute specifying null as default value
			// is authorized even if null is not allowed

			// Pick the first one...
			$aClasses = $this->GetAllowedValues();
			$sDefault = key($aClasses);
		}

		return $sDefault;
	}

	/**
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 */
	public function GetAllowedValues($aArgs = [], $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef) {
			return null;
		}

		$aListClass = $oValSetDef->GetValues($aArgs, $sContains);
		/* @since 3.3.0 remove elements in class_exclusion_list */
		$sClassExclusionList = $this->GetOptional('class_exclusion_list', null);
		if (!empty($sClassExclusionList)) {
			foreach (explode(',', $sClassExclusionList) as $sClassName) {
				unset($aListClass[trim($sClassName)]);
			}
		}

		return $aListClass;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue)) {
			return '';
		}

		return MetaModel::GetName($sValue);
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
