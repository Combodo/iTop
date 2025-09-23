<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CoreUnexpectedValue;
use Dict;
use Exception;
use MetaModel;
use ormSet;
use utils;

class AttributeClassAttCodeSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	const DEFAULT_PARAM_INCLUDE_CHILD_CLASSES_ATTRIBUTES = false;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-class-attcode-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('class_field', 'attribute_definition_list', 'attribute_definition_exclusion_list'));
	}

	public function GetMaxSize()
	{
		return max(255, 15 * $this->GetMaxItems());
	}

	/**
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 */
	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		if (!isset($aArgs['this'])) {
			return null;
		}

		$oHostObj = $aArgs['this'];
		$sTargetClass = $this->Get('class_field');
		$sRootClass = $oHostObj->Get($sTargetClass);
		$bIncludeChildClasses = $this->GetOptional('include_child_classes_attributes', static::DEFAULT_PARAM_INCLUDE_CHILD_CLASSES_ATTRIBUTES);

		$aExcludeDefs = array();
		$sAttDefExclusionList = $this->Get('attribute_definition_exclusion_list');
		if (!empty($sAttDefExclusionList)) {
			foreach (explode(',', $sAttDefExclusionList) as $sAttDefName) {
				$sAttDefName = trim($sAttDefName);
				$aExcludeDefs[$sAttDefName] = $sAttDefName;
			}
		}

		$aAllowedDefs = array();
		$sAttDefList = $this->Get('attribute_definition_list');
		if (!empty($sAttDefList)) {
			foreach (explode(',', $sAttDefList) as $sAttDefName) {
				$sAttDefName = trim($sAttDefName);
				$aAllowedDefs[$sAttDefName] = $sAttDefName;
			}
		}

		$aAllAttributes = array();
		if (!empty($sRootClass)) {
			$aClasses = array($sRootClass);
			if ($bIncludeChildClasses === true) {
				$aClasses = $aClasses + MetaModel::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_EXCLUDETOP);
			}

			foreach ($aClasses as $sClass) {
				foreach (MetaModel::GetAttributesList($sClass) as $sAttCode) {
					// Add attribute only if not already there (can be in leaf classes but not the root)
					if (!array_key_exists($sAttCode, $aAllAttributes)) {
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						$sAttDefClass = get_class($oAttDef);

						// Skip excluded attdefs
						if (isset($aExcludeDefs[$sAttDefClass])) {
							continue;
						}
						// Skip not allowed attdefs only if list specified
						if (!empty($aAllowedDefs) && !isset($aAllowedDefs[$sAttDefClass])) {
							continue;
						}

						$aAllAttributes[$sAttCode] = array(
							'classes' => array($sClass),
						);
					} else {
						$aAllAttributes[$sAttCode]['classes'][] = $sClass;
					}
				}
			}
		}

		$aAllowedAttributes = array();
		foreach ($aAllAttributes as $sAttCode => $aAttData) {
			$iAttClassesCount = count($aAttData['classes']);
			$sAttFirstClass = $aAttData['classes'][0];
			$sAttLabel = MetaModel::GetLabel($sAttFirstClass, $sAttCode);

			if ($sAttFirstClass === $sRootClass) {
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass', $sAttCode, $sAttLabel);
			} elseif ($iAttClassesCount === 1) {
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass', $sAttCode, $sAttLabel, MetaModel::GetName($sAttFirstClass));
			} else {
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses', $sAttCode, $sAttLabel);
			}
			$aAllowedAttributes[$sAttCode] = $sLabel;
		}
		// N°6460 Always sort on the labels, not on the datamodel definition order
		natcasesort($aAllowedAttributes);

		return $aAllowedAttributes;
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
	 * @throws Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aArgs = array();
		if (!empty($oHostObj)) {
			$aArgs['this'] = $oHostObj;
		}
		$aAllowedAttributes = $this->GetAllowedValues($aArgs);
		$aInvalidAttCodes = array();
		if (is_string($proposedValue) && !empty($proposedValue)) {
			$aJsonFromWidget = json_decode($proposedValue, true);
			if (is_null($aJsonFromWidget)) {
				$proposedValue = trim($proposedValue);
				$aProposedValues = $this->FromStringToArray($proposedValue);
				$aValues = array();
				foreach ($aProposedValues as $sValue) {
					$sAttCode = trim($sValue);
					if (empty($aAllowedAttributes) || isset($aAllowedAttributes[$sAttCode])) {
						$aValues[$sAttCode] = $sAttCode;
					} else {
						$aInvalidAttCodes[] = $sAttCode;
					}
				}
				$oSet->SetValues($aValues);
			}
		} elseif ($proposedValue instanceof ormSet) {
			$oSet = $proposedValue;
		}
		if (!empty($aInvalidAttCodes) && !$bIgnoreErrors) {
			$sTargetClass = $this->Get('class_field');
			$sClass = $oHostObj->Get($sTargetClass);
			throw new CoreUnexpectedValue("The attribute(s) ".implode(', ', $aInvalidAttCodes)." are invalid for class {$sClass}");
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
				$sTargetClass = $this->Get('class_field');
				$sClass = $oHostObject->Get($sTargetClass);

				$aLocalizedValues = array();
				foreach ($value as $sAttCode) {
					try {
						$sAttClass = $sClass;

						// Look for the first class (current or children) that have this attcode
						foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass) {
							if (MetaModel::IsValidAttCode($sChildClass, $sAttCode)) {
								$sAttClass = $sChildClass;
								break;
							}
						}

						$sLabelForHtmlAttribute = utils::HtmlEntities(MetaModel::GetLabel($sAttClass, $sAttCode)." ($sAttCode)");
						$aLocalizedValues[] = '<span class="attribute-set-item" data-code="'.$sAttCode.'" data-label="'.$sLabelForHtmlAttribute.'" data-description="" data-tooltip-content="'.$sLabelForHtmlAttribute.'">'.$sAttCode.'</span>';
					}
					catch (Exception $e) {
						// Ignore bad values
					}
				}
				$value = $aLocalizedValues;
			}
			$value = implode('', $value);
		}

		return '<span class="'.implode(' ', $this->aCSSClasses).'">'.$value.'</span>';
	}

	public function IsNull($proposedValue)
	{
		return (empty($proposedValue));
	}
}