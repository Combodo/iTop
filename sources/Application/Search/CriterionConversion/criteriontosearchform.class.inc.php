<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Convert structures from OQL expressions into structure for the search form
 */

namespace Combodo\iTop\Application\Search\CriterionConversion;


use AttributeDate;
use AttributeDateTime;
use AttributeDefinition;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;
use DateInterval;
use DateTime;
use Dict;
use Exception;
use MetaModel;

class CriterionToSearchForm extends CriterionConversionAbstract
{

	/**
	 * @param array $aAndCriterionRaw
	 * @param array $aFieldsByCategory
	 *
	 * @param array $aClasses all the classes of the filter
	 *
	 * @param bool $bIsRemovable
	 *
	 * @return array
	 */
	public static function Convert($aAndCriterionRaw, $aFieldsByCategory, $aClasses, $bIsRemovable = true)
	{
		$aAllFields = array();
		foreach($aFieldsByCategory as $aFields)
		{
			if (!is_array($aFields))
			{
				continue;
			}
			foreach($aFields as $aField)
			{
				$sAlias = $aField['class_alias'];
				$sCode = $aField['code'];
				$aAllFields["$sAlias.$sCode"] = $aField;
			}
		}
		$aAndCriterion = array();
		$aMappingOperatorToFunction = array(
			AttributeDefinition::SEARCH_WIDGET_TYPE_STRING => 'TextToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD => 'ExternalFieldToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE => 'DateTimeToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME => 'DateTimeToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC => 'NumericToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_KEY => 'ExternalKeyToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY => 'ExternalKeyToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_ENUM => 'EnumToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_SET => 'SetToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_TAG_SET => 'TagSetToSearchForm',
		);

		foreach($aAndCriterionRaw as $aCriteria)
		{
			if (isset($aCriteria['label']))
			{
				$aCriteria['label'] = preg_replace("@\)$@", '', $aCriteria['label']);
				$aCriteria['label'] = preg_replace("@^\(@", '', $aCriteria['label']);
			}
			$aCriteria['is_removable'] = $bIsRemovable;

			$sClass = '';
			if (isset($aCriteria['ref']))
			{
				$aRef = explode('.', $aCriteria['ref']);
				if (isset($aClasses[$aRef[0]]))
				{
					$sClass = $aClasses[$aRef[0]];
					$aCriteria['class'] = $sClass;
				}
			}

			// Check criteria validity
			if (!isset($aCriteria['ref']) || !isset($aAllFields[$aCriteria['ref']]))
			{
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
				$aCriteria['label'] = Dict::S('UI:Search:Criteria:Raw:Filtered');
				if (isset($aCriteria['ref']))
				{
					try
					{
						$aCriteria['label'] = Dict::Format('UI:Search:Criteria:Raw:FilteredOn', MetaModel::GetName($sClass));
					}
					catch (Exception $e)
					{
					}
				}
			}
			if (array_key_exists('widget', $aCriteria))
			{
				if (array_key_exists($aCriteria['widget'], $aMappingOperatorToFunction))
				{
					$sFct = $aMappingOperatorToFunction[$aCriteria['widget']];
					$aAndCriterion = array_merge($aAndCriterion, self::$sFct($aCriteria, $aAllFields));
				}
				else
				{
					$aAndCriterion[] = $aCriteria;
				}
			}
		}

		// Regroup criterion by variable name (no ref first)
		usort($aAndCriterion, function ($a, $b)
		{
			if (array_key_exists('ref', $a) || array_key_exists('ref', $b))
			{
				if (array_key_exists('ref', $a) && array_key_exists('ref', $b))
				{
					$iRefCmp = strcmp($a['ref'], $b['ref']);
					if ($iRefCmp != 0)
					{
						return $iRefCmp;
					}

					return strcmp($a['operator'], $b['operator']);
				}
				if (array_key_exists('ref', $a))
				{
					return 1;
				}

				return -1;
			}
			if (array_key_exists('oql', $a) && array_key_exists('oql', $b))
			{
				return strcmp($a['oql'], $b['oql']);
			}

			return 0;
		});

		$aMergeFctByWidget = array(
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE => 'MergeDate',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME => 'MergeDateTime',
			AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC => 'MergeNumeric',
			AttributeDefinition::SEARCH_WIDGET_TYPE_ENUM => 'MergeEnumExtKeys',
			AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_KEY => 'MergeEnumExtKeys',
		);

		$aPrevCriterion = null;
		$aMergedCriterion = array();
		foreach($aAndCriterion as $aCurrCriterion)
		{
			if (!is_null($aPrevCriterion))
			{
				if (array_key_exists('ref', $aPrevCriterion) && array_key_exists('widget', $aPrevCriterion))
				{
					// If previous has ref, the current has ref as the array is sorted with all without ref first
					if (($aPrevCriterion['ref'] == $aCurrCriterion['ref']) && ($aPrevCriterion['widget'] == $aCurrCriterion['widget']))
					{
						// Same attribute, try to merge
						if (array_key_exists('widget', $aCurrCriterion))
						{
							if (array_key_exists($aCurrCriterion['widget'], $aMergeFctByWidget))
							{
								$sFct = $aMergeFctByWidget[$aCurrCriterion['widget']];
								$aPrevCriterion = self::$sFct($aPrevCriterion, $aCurrCriterion, $aMergedCriterion);
								continue;
							}
						}
					}
				}
				$aMergedCriterion[] = $aPrevCriterion;
			}

			$aPrevCriterion = $aCurrCriterion;
		}
		if (!is_null($aPrevCriterion))
		{
			$aMergedCriterion[] = $aPrevCriterion;
		}

		// Sort by label criterion by variable name (no ref first)
		usort($aMergedCriterion, function ($a, $b)
		{
			if (($a['widget'] === AttributeDefinition::SEARCH_WIDGET_TYPE_RAW) ||
				($b['widget'] === AttributeDefinition::SEARCH_WIDGET_TYPE_RAW))
			{
				if (($a['widget'] === AttributeDefinition::SEARCH_WIDGET_TYPE_RAW) &&
					($b['widget'] === AttributeDefinition::SEARCH_WIDGET_TYPE_RAW))
				{
					if (!isset($a['label']))
					{
						return -1;
					}
					if (!isset($b['label']))
					{
						return 1;
					}
					return strcmp($a['label'], $b['label']);
				}
				if ($a['widget'] === AttributeDefinition::SEARCH_WIDGET_TYPE_RAW)
				{
					return -1;
				}

				return 1;
			}

			if (!isset($a['label']))
			{
				return -1;
			}
			if (!isset($b['label']))
			{
				return 1;
			}
			return strcmp($a['label'], $b['label']);
		});

		return $aMergedCriterion;
	}

	/**
	 * @param $aPrevCriterion
	 * @param $aCurrCriterion
	 * @param $aMergedCriterion
	 *
	 * @return array Current criteria or null if merged
	 * @throws \Exception
	 */
	protected static function MergeDate($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$sPrevOperator = $aPrevCriterion['operator'];
		$sCurrOperator = $aCurrCriterion['operator'];
		if (($sPrevOperator != '<=') || ($sCurrOperator != '>='))
		{
			$aMergedCriterion[] = $aPrevCriterion;

			return $aCurrCriterion;
		}

		// Merge into 'between' operation.
		// The ends of the interval are included
		$aCurrCriterion['operator'] = 'between_dates';
		$oFormat = AttributeDate::GetFormat();
		$sLastDate = $aPrevCriterion['values'][0]['value'];
		$oDate = new DateTime($sLastDate);
		$sLastDateValue = $oDate->format(AttributeDate::GetSQLFormat());
		$sLastDateLabel = $oFormat->format($oDate);

		$sFirstDate = $aCurrCriterion['values'][0]['value'];
		$oDate = new DateTime($sFirstDate);
		$sFirstDateValue = $oDate->format(AttributeDate::GetSQLFormat());
		$sFirstDateLabel = $oFormat->format($oDate);

		$aCurrCriterion['values'] = array();
		$aCurrCriterion['values'][] = array('value' => $sFirstDateValue, 'label' => $sFirstDateLabel);
		$aCurrCriterion['values'][] = array('value' => $sLastDateValue, 'label' => $sLastDateLabel);

		$aCurrCriterion['oql'] = "({$aPrevCriterion['oql']} AND {$aCurrCriterion['oql']})";
		$aCurrCriterion['label'] = $aPrevCriterion['label'].' '.Dict::S('Expression:Operator:AND', 'AND').' '.$aCurrCriterion['label'];

		$aMergedCriterion[] = $aCurrCriterion;

		return null;
	}

	/**
	 * @param $aPrevCriterion
	 * @param $aCurrCriterion
	 * @param $aMergedCriterion
	 *
	 * @return array Current criteria or null if merged
	 * @throws \Exception
	 */
	protected static function MergeDateTime($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$sPrevOperator = $aPrevCriterion['operator'];
		$sCurrOperator = $aCurrCriterion['operator'];
		if (($sPrevOperator != '<=') || ($sCurrOperator != '>='))
		{
			$aMergedCriterion[] = $aPrevCriterion;

			return $aCurrCriterion;
		}

		// Merge into 'between' operation.
		// The ends of the interval are included
		$sLastDate = $aPrevCriterion['values'][0]['value'];
		$sFirstDate = $aCurrCriterion['values'][0]['value'];
		$oDate = new DateTime($sLastDate);
		$aCurrCriterion['operator'] = 'between_dates';
		$sLastDateValue = $oDate->format(AttributeDateTime::GetSQLFormat());
		$sLastDateLabel = AttributeDateTime::GetFormat()->Format($sLastDateValue);

		$oDate = new DateTime($sFirstDate);
		$sFirstDateValue = $oDate->format(AttributeDateTime::GetSQLFormat());
		$sFirstDateLabel = AttributeDateTime::GetFormat()->Format($sFirstDateValue);

		$aCurrCriterion['values'] = array();
		$aCurrCriterion['values'][] = array('value' => $sFirstDateValue, 'label' => $sFirstDateLabel);
		$aCurrCriterion['values'][] = array('value' => $sLastDateValue, 'label' => $sLastDateLabel);

		$aCurrCriterion['oql'] = "({$aPrevCriterion['oql']} AND {$aCurrCriterion['oql']})";
		$aCurrCriterion['label'] = $aPrevCriterion['label'].' '.Dict::S('Expression:Operator:AND',
				'AND').' '.$aCurrCriterion['label'];

		$aMergedCriterion[] = $aCurrCriterion;

		return null;
	}

	/**
	 * @param $aPrevCriterion
	 * @param $aCurrCriterion
	 * @param $aMergedCriterion
	 *
	 * @return array Current criteria or null if merged
	 * @throws \Exception
	 */
	protected static function MergeNumeric($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$sPrevOperator = $aPrevCriterion['operator'];
		$sCurrOperator = $aCurrCriterion['operator'];
		if (($sPrevOperator != '<=') || ($sCurrOperator != '>='))
		{
			$aMergedCriterion[] = $aPrevCriterion;

			return $aCurrCriterion;
		}

		// Merge into 'between' operation.
		$sLastNum = $aPrevCriterion['values'][0]['value'];
		$sFirstNum = $aCurrCriterion['values'][0]['value'];
		$aCurrCriterion['values'] = array();
		$aCurrCriterion['values'][] = array('value' => $sFirstNum, 'label' => "$sFirstNum");
		$aCurrCriterion['values'][] = array('value' => $sLastNum, 'label' => "$sLastNum");

		$aCurrCriterion['oql'] = "({$aPrevCriterion['oql']} AND {$aCurrCriterion['oql']})";
		$aCurrCriterion['label'] = $aPrevCriterion['label'].' '.Dict::S('Expression:Operator:AND', 'AND').' '.$aCurrCriterion['label'];
		$aCurrCriterion['operator'] = 'between';

		$aMergedCriterion[] = $aCurrCriterion;

		return null;
	}

	private static function SerializeValues($aValues)
	{
		$aSerializedValues = array();
		foreach($aValues as $aValue)
		{
			$aSerializedValues[] = serialize($aValue);
		}

		return $aSerializedValues;
	}

	protected static function MergeEnumExtKeys($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$aFirstValues = self::SerializeValues($aPrevCriterion['values']);
		$aNextValues = self::SerializeValues($aCurrCriterion['values']);

		// Keep only the common values
		$aCurrCriterion['values'] = array_map("unserialize", array_intersect($aFirstValues, $aNextValues));

		$aMergedCriterion[] = $aCurrCriterion;
		return null;
	}

	protected static function TextToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		$sValue = $aCriteria['values'][0]['value'];

		$bStartWithPercent = substr($sValue, 0, 1) == '%' ? true : false;
		$bEndWithPercent = substr($sValue, -1) == '%' ? true : false;

		switch (true)
		{
			case ('' == $sValue and ($sOperator == '=' or $sOperator == 'LIKE')):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
				break;
			case ('' == $sValue and $sOperator == '!='):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
				break;
			case ($sOperator == 'LIKE' && $bStartWithPercent && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_CONTAINS;
				$sValue = substr($sValue, 1, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
			case ($sOperator == 'LIKE' && $bStartWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_ENDS_WITH;
				$sValue = substr($sValue, 1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
			case ($sOperator == 'LIKE' && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_STARTS_WITH;
				$sValue = substr($sValue, 0, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
		}

		return array($aCriteria);
	}

	protected static function ExternalFieldToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		$sValue = $aCriteria['values'][0]['value'];

		$bStartWithPercent = substr($sValue, 0, 1) == '%' ? true : false;
		$bEndWithPercent = substr($sValue, -1) == '%' ? true : false;

		switch (true)
		{
			case ($sOperator == 'ISNULL'):
			case ('' == $sValue and ($sOperator == 'LIKE')):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
				break;
			case (($sOperator == '=') && isset($aCriteria['has_undefined']) && ($sValue == '0')):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
				break;
			case (($sOperator == 'LIKE') && $bStartWithPercent && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_CONTAINS;
				$sValue = substr($sValue, 1, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
			case (($sOperator == 'LIKE') && $bStartWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_ENDS_WITH;
				$sValue = substr($sValue, 1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
			case (($sOperator == 'LIKE') && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_STARTS_WITH;
				$sValue = substr($sValue, 0, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = "$sValue";
				break;
		}

		return array($aCriteria);
	}

	protected static function DateTimeToSearchForm($aCriterion, $aFields)
	{
		if ((!array_key_exists('is_relative', $aCriterion) || !$aCriterion['is_relative'])
			&& (!isset($aCriterion['unit']) || ($aCriterion['unit'] == 'DAY')))
		{
			// Convert '=' in 'between'
			if (isset($aCriterion['operator']))
			{
				switch ($aCriterion['operator'])
				{
					case '=':
						if (isset($aCriterion['has_undefined']) && (isset($aCriterion['values'][0]['value']) && ($aCriterion['values'][0]['value'] == 0)))
						{
							// Special case for NOT EMPTY
							$aCriterion['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
						}
						else
						{
							$aCriterion['operator'] = CriterionConversionAbstract::OP_BETWEEN_DATES;
							$sWidget = $aCriterion['widget'];
							if ($sWidget == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE)
							{
								$aCriterion['values'][1] = $aCriterion['values'][0];
							}
							else
							{
								$sDate = $aCriterion['values'][0]['value'];
								$oDate = new DateTime($sDate);

								$sFirstDateValue = $oDate->format(AttributeDateTime::GetSQLFormat());
								try
								{
									$sFirstDateLabel = AttributeDateTime::GetFormat()->Format($sFirstDateValue);
									$aCriterion['values'][0] = array('value' => $sFirstDateValue, 'label' => "$sFirstDateLabel");
								}
								catch (Exception $e)
								{
								}

								$oDate->add(DateInterval::createFromDateString('1 day'));
								$oDate->sub(DateInterval::createFromDateString('1 second'));

								$sLastDateValue = $oDate->format(AttributeDateTime::GetSQLFormat());
								try
								{
									$sLastDateLabel = AttributeDateTime::GetFormat()->Format($sLastDateValue);
									$aCriterion['values'][1] = array('value' => $sLastDateValue, 'label' => "$sLastDateLabel");
								}
								catch (Exception $e)
								{
								}
							}
						}
						break;

					case 'ISNULL':
						$aCriterion['operator'] = CriterionConversionAbstract::OP_EMPTY;
						break;

					case '>':
						$aCriterion['operator'] = '>=';
						$sWidget = $aCriterion['widget'];
						if ($sWidget == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE)
						{
							$sDelta = '1 day';
							$sAttributeClass = AttributeDate::class;
						}
						else
						{
							$sDelta = '1 second';
							$sAttributeClass = AttributeDateTime::class;
						}
						/** @var \AttributeDateTime $sAttributeClass */
						/** @var \DateTimeFormat $oFormat */
						$oFormat = $sAttributeClass::GetFormat();
						$sFirstDate = $aCriterion['values'][0]['value'];
						$oDate = new DateTime($sFirstDate);
						$oDate->add(DateInterval::createFromDateString($sDelta));
						$sFirstDateValue = $oDate->format($sAttributeClass::GetSQLFormat());
						try
						{
							$sFirstDateLabel = $oFormat->format($oDate);
							$aCriterion['values'][0] = array('value' => $sFirstDateValue, 'label' => $sFirstDateLabel);
						} catch (Exception $e) {}
						break;

					case '<':
						$aCriterion['operator'] = '<=';
						$sWidget = $aCriterion['widget'];
						if ($sWidget == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE)
						{
							$sDelta = '1 day';
							$sAttributeClass = AttributeDate::class;
						}
						else
						{
							$sDelta = '1 second';
							$sAttributeClass = AttributeDateTime::class;
						}
						/** @var \AttributeDateTime $sAttributeClass */
						/** @var \DateTimeFormat $oFormat */
						$oFormat = $sAttributeClass::GetFormat();
						$sFirstDate = $aCriterion['values'][0]['value'];
						$oDate = new DateTime($sFirstDate);
						$oDate->sub(DateInterval::createFromDateString($sDelta));
						$sFirstDateValue = $oDate->format($sAttributeClass::GetSQLFormat());
						try
						{
							$sFirstDateLabel = $oFormat->format($oDate);
							$aCriterion['values'][0] = array('value' => $sFirstDateValue, 'label' => $sFirstDateLabel);
						} catch (Exception $e) {}
						break;

				}
			}

			return array($aCriterion);
		}

		if (isset($aCriterion['values'][0]['value']))
		{
			$sLabel = $aCriterion['values'][0]['value'];
			if (isset($aCriterion['verb']))
			{
				switch ($aCriterion['verb'])
				{
					case 'DATE_SUB':
						$sLabel = '-'.$sLabel;
						break;
					case 'DATE_ADD':
						$sLabel = '+'.$sLabel;
						break;
				}
			}
			if (isset($aCriterion['unit']))
			{
				$sLabel .= Dict::S('Expression:Unit:Short:'.$aCriterion['unit'], $aCriterion['unit']);
			}
			$aCriterion['values'][0]['label'] = "$sLabel";
		}

		// Temporary until the JS widget support relative dates
		$aCriterion['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;

		return array($aCriterion);
	}

	protected static function NumericToSearchForm($aCriteria, $aFields)
	{
		switch ($aCriteria['operator'])
		{
			case  'ISNULL':
				$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
				break;

			case '=':
				if (isset($aCriteria['has_undefined']) && (isset($aCriteria['values'][0]['value']) && ($aCriteria['values'][0]['value'] == 0)))
				{
					// Special case for NOT EMPTY
					$aCriteria['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
				}
				break;
		}

		return array($aCriteria);
	}

	protected static function EnumToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		switch ($sOperator)
		{
			case '=':
				// Same as IN
				$aCriteria['operator'] = CriterionConversionAbstract::OP_IN;
				break;
			case 'NOT IN':
			case 'NOTIN':
			case '!=':
				// Same as NOT IN
				$aCriteria = self::RevertValues($aCriteria, $aFields);
				break;
			case 'IN':
				// Nothing special to do
				break;
			case 'OR':
			case 'ISNULL':
				// Special case when undefined and/or other values are selected
				$aCriteria['operator'] = CriterionConversionAbstract::OP_IN;
				if (isset($aCriteria['has_undefined']) && $aCriteria['has_undefined'])
				{
					if (!isset($aCriteria['values']))
					{
						$aCriteria['values'] = array();
					}
					// Convention for 'undefined' enums
					$aCriteria['values'][] = array('value' => 'null', 'label' => Dict::S('Enum:Undefined'));
				}
				break;
			default:
				// Unknown operator
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
				break;
		}

		return array($aCriteria);
	}

	protected static function TagSetToSearchForm($aCriteria, $aFields)
	{
		$aCriterion = array($aCriteria);
		$sOperator = $aCriteria['operator'];
		switch ($sOperator)
		{
			case 'MATCHES':
				// Nothing special to do
				if (isset($aCriteria['has_undefined']) && $aCriteria['has_undefined'])
				{
					if (!isset($aCriteria['values']))
					{
						$aCriteria['values'] = array();
					}
					// Convention for 'undefined' tag set
					$aCriteria['values'][] = array('value' => '', 'label' => Dict::S('Enum:Undefined'));
				}
				break;

			case 'OR':
			case 'ISNULL':
				$aCriteria['operator'] = CriterionConversionAbstract::OP_EQUALS;
				if (isset($aCriteria['has_undefined']) && $aCriteria['has_undefined'])
				{
					if (!isset($aCriteria['values']))
					{
						$aCriteria['values'] = array();
					}
					// Convention for 'undefined' tag set
					$aCriteria['values'][] = array('value' => '', 'label' => Dict::S('Enum:Undefined'));
				}
				break;

			case '=':
				$aCriteria['operator'] = CriterionConversionAbstract::OP_MATCHES;
				if (isset($aCriteria['has_undefined']) && $aCriteria['has_undefined'])
				{
					$aCriteria['values'] = array();
					// Convention for 'undefined' tag set
					$aCriteria['values'][] = array('value' => '', 'label' => Dict::S('Enum:Undefined'));
				}
				else
				{
					// Split values into a list of Matches
					$aCriterion = array();
					$aValues = $aCriteria['values'];
					foreach($aValues as $aValue)
					{
						$aCriteria['values'] = array($aValue);
						$aCriterion[] = $aCriteria;
					}
				}
				break;

			default:
				// Unknown operator
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
				break;
		}
		return $aCriterion;
	}

	protected static function SetToSearchForm($aCriteria, $aFields)
	{
		$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
		return array($aCriteria);
	}

	protected static function ExternalKeyToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		switch ($sOperator)
		{
			case '=':
				// Same as IN
				$aCriteria['operator'] = CriterionConversionAbstract::OP_IN;
				unset($aCriteria['oql']);
				break;
			case 'IN':
				unset($aCriteria['oql']);
				break;
			default:
				// Unknown operator
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
				break;
		}

		return array($aCriteria);
	}

	/**
	 * @param $aCriteria
	 * @param $aFields
	 *
	 * @return mixed
	 */
	protected static function RevertValues($aCriteria, $aFields)
	{
		$sRef = $aCriteria['ref'];
		$aValues = $aCriteria['values'];
		if (array_key_exists($sRef, $aFields))
		{
			$aField = $aFields[$sRef];
			if (array_key_exists('allowed_values', $aField) && array_key_exists('values', $aField['allowed_values']))
			{
				$aAllowedValues = $aField['allowed_values']['values'];
			}
			else
			{
				// Can't obtain the list of allowed values, just set as unknown
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
			}
		}

		if (isset($aAllowedValues))
		{
			foreach($aValues as $aValue)
			{
				$sValue = $aValue['value'];
				unset($aAllowedValues[$sValue]);
			}
			$aCriteria['values'] = array();

			foreach($aAllowedValues as $sValue => $sLabel)
			{
				$aValue = array('value' => $sValue, 'label' => "$sLabel");
				$aCriteria['values'][] = $aValue;
			}
			$aCriteria['operator'] = 'IN';
		}

		return $aCriteria;
	}

}
