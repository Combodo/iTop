<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use CoreException;
use CoreUnexpectedValue;
use DBSearch;
use Exception;
use IssueLog;
use MetaModel;
use OQLException;
use ormSet;
use utils;

class AttributeQueryAttCodeSet extends AttributeSet
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-query-attcode-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), ['query_field']);
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetMaxSize()
	{
		return 65535;
	}

	/**
	 * Get a class array indexed by alias
	 *
	 * @param $oHostObj
	 *
	 * @return array
	 */
	private function GetClassList($oHostObj)
	{
		try {
			$sQueryField = $this->Get('query_field');
			$sQuery = $oHostObj->Get($sQueryField);
			if (empty($sQuery)) {
				return [];
			}
			$oFilter = DBSearch::FromOQL($sQuery);

			return $oFilter->GetSelectedClasses();

		} catch (OQLException $e) {
			IssueLog::Warning($e->getMessage());
		}

		return [];
	}

	public function GetAllowedValues($aArgs = [], $sContains = '')
	{
		if (isset($aArgs['this'])) {
			$oHostObj = $aArgs['this'];
			$aClasses = $this->GetClassList($oHostObj);

			$aAllowedAttributes = [];
			$aAllAttributes = [];

			if ((count($aClasses) == 1) && (array_keys($aClasses)[0] == array_values($aClasses)[0])) {
				$sClass = reset($aClasses);
				$aAttributes = MetaModel::GetAttributesList($sClass);
				foreach ($aAttributes as $sAttCode) {
					$aAllowedAttributes[$sAttCode] = "$sAttCode (".MetaModel::GetLabel($sClass, $sAttCode).')';
				}
			} else {
				if (!empty($aClasses)) {
					ksort($aClasses);
					foreach ($aClasses as $sAlias => $sClass) {
						$aAttributes = MetaModel::GetAttributesList($sClass);
						foreach ($aAttributes as $sAttCode) {
							$aAllAttributes[] = ['alias' => $sAlias, 'class' => $sClass, 'att_code' => $sAttCode];
						}
					}
				}
				foreach ($aAllAttributes as $aFullAttCode) {
					$sAttCode = $aFullAttCode['alias'].'.'.$aFullAttCode['att_code'];
					$sClass = $aFullAttCode['class'];
					$sLabel = "$sAttCode (".MetaModel::GetLabel($sClass, $aFullAttCode['att_code']).')';
					$aAllowedAttributes[$sAttCode] = $sLabel;
				}
			}

			return $aAllowedAttributes;
		}

		return null;
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param DBObject $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws OQLException
	 * @throws Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aArgs = [];
		if (!empty($oHostObj)) {
			$aArgs['this'] = $oHostObj;
		}
		$aAllowedAttributes = $this->GetAllowedValues($aArgs);
		$aInvalidAttCodes = [];
		if (is_string($proposedValue) && !empty($proposedValue)) {
			$proposedValue = trim($proposedValue);
			$aProposedValues = $this->FromStringToArray($proposedValue);
			$aValues = [];
			foreach ($aProposedValues as $sValue) {
				$sAttCode = trim($sValue);
				if (empty($aAllowedAttributes) || isset($aAllowedAttributes[$sAttCode])) {
					$aValues[$sAttCode] = $sAttCode;
				} else {
					$aInvalidAttCodes[] = $sAttCode;
				}
			}
			$oSet->SetValues($aValues);
		} elseif ($proposedValue instanceof ormSet) {
			$oSet = $proposedValue;
		}
		if (!empty($aInvalidAttCodes) && !$bIgnoreErrors) {
			throw new CoreUnexpectedValue("The attribute(s) ".implode(', ', $aInvalidAttCodes)." are invalid");
		}

		return $oSet;
	}

	/**
	 * @param $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{

		if ($value instanceof ormSet) {
			$value = $value->GetValues();
		}
		if (is_array($value)) {
			if (!empty($oHostObject) && $bLocalize) {
				$aArgs['this'] = $oHostObject;
				$aAllowedAttributes = $this->GetAllowedValues($aArgs);

				$aLocalizedValues = [];
				foreach ($value as $sAttCode) {
					if (isset($aAllowedAttributes[$sAttCode])) {
						$sLabelForHtmlAttribute = utils::HtmlEntities($aAllowedAttributes[$sAttCode]);
						$aLocalizedValues[] = '<span class="attribute-set-item" data-code="'.$sAttCode.'" data-label="'.$sLabelForHtmlAttribute.'" data-description="" data-tooltip-content="'.$sLabelForHtmlAttribute.'">'.$sAttCode.'</span>';
					}
				}
				$value = $aLocalizedValues;
			}
			$value = implode('', $value);
		}

		return '<span class="'.implode(' ', $this->aCSSClasses).'">'.$value.'</span>';
	}
}
