<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
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


namespace Combodo\iTop\Application\Search;


use ApplicationContext;
use AttributeDateTime;
use AttributeDefinition;
use CMDBObjectSet;
use Combodo\iTop\Application\Search\CriterionConversion\CriterionToSearchForm;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use Expression;
use IssueLog;
use MetaModel;
use TrueExpression;
use utils;
use WebPage;

class SearchForm
{
	private $aLabels = array();

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$sHtml = '';
		$this->aLabels = array();
		$oAppContext = new ApplicationContext();
		$sClassName = $oSet->GetFilter()->GetClass();
		$aListParams = array();

		foreach($aExtraParams as $key => $value)
		{
			$aListParams[$key] = $value;
		}

		// Simple search form
		if (isset($aExtraParams['currentId']))
		{
			$sSearchFormId = $aExtraParams['currentId'];
		}
		else
		{
			$iSearchFormId = $oPage->GetUniqueId();
			$sSearchFormId = 'SimpleSearchForm'.$iSearchFormId;
			$sHtml .= "<div id=\"ds_$sSearchFormId\" class=\"mini_tab{$iSearchFormId}\">\n";
			$aListParams['currentId'] = "$iSearchFormId";
		}
		// Check if the current class has some sub-classes
		if (isset($aExtraParams['baseClass']))
		{
			$sRootClass = $aExtraParams['baseClass'];
		}
		else
		{
			$sRootClass = $sClassName;
		}

		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson))
		{
			$aListParams['json'] = json_decode($sJson, true);
		}


		$aSubClasses = MetaModel::GetSubclasses($sRootClass);
		if (count($aSubClasses) > 0)
		{
			$aOptions = array();
			$aOptions[MetaModel::GetName($sRootClass)] = "<option value=\"$sRootClass\">".MetaModel::GetName($sRootClass)."</options>\n";
			foreach($aSubClasses as $sSubclassName)
			{
				$aOptions[MetaModel::GetName($sSubclassName)] = "<option value=\"$sSubclassName\">".MetaModel::GetName($sSubclassName)."</options>\n";
			}
			$aOptions[MetaModel::GetName($sClassName)] = "<option selected value=\"$sClassName\">".MetaModel::GetName($sClassName)."</options>\n";
			ksort($aOptions);
			$sContext = $oAppContext->GetForLink();
			$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass', '$sContext', '{$aExtraParams['table_id']}')\">\n".implode('',
					$aOptions)."</select>\n";
		}
		else
		{
			$sClassesCombo = MetaModel::GetName($sClassName);
		}
		$sAction = (isset($aExtraParams['action'])) ? $aExtraParams['action'] : utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		$sStyle = (isset($aExtraParams['open']) && ($aExtraParams['open'] == 'true')) ? 'opened' : '';
		$sHtml .= "<form id=\"fs_{$sSearchFormId}\" action=\"{$sAction}\" class=\"{$sStyle}\">\n"; // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
		$sHtml .= "<h2 class=\"sf_title\"><span class=\"sft_picto fa fa-search\"></span>" . Dict::Format('UI:SearchFor_Class_Objects', $sClassesCombo) . "<a class=\"sft_toggler fa fa-caret-down pull-right\" href=\"#\" title=\"" . Dict::S('UI:Search:Toggle') . "\"></a><a class=\"sft_refresh fa fa-refresh pull-right\" href=\"#\" title=\"" . Dict::S('UI:Button:Refresh') . "\"></a></h2>\n";
		$sHtml .= "<div id=\"fs_{$sSearchFormId}_message\" class=\"sf_message header_message\"></div>\n";
		$sHtml .= "<div id=\"fs_{$sSearchFormId}_criterion_outer\">\n</div>\n";
		$sHtml .= "</form>\n";

		$aFields = $this->GetFields($oSet);
		$oSearch = $oSet->GetFilter();
		$aCriterion = $this->GetCriterion($oSearch, $aFields);

		$oBaseSearch = $oSearch->DeepClone();
		$oBaseSearch->ResetCondition();
		$sBaseOQL = str_replace(' WHERE 1', '', $oBaseSearch->ToOQL());

		if (isset($aExtraParams['table_inner_id']))
		{
			$sDataConfigListSelector = $aExtraParams['table_inner_id'];
		}
		else
		{
			$sDataConfigListSelector = $aExtraParams['table_id'];
		}

		if (!isset($aExtraParams['table_id']))
		{
			$aExtraParams['table_id'] = "search_form_result_{$sSearchFormId}";
		}
		if (!array_key_exists('table_inner_id', $aExtraParams))
		{
			$aListParams['table_inner_id'] = "table_inner_id_{$sSearchFormId}";
		}
		// When table_id is different of result_list_outer_selector
		if (array_key_exists('table_id2', $aExtraParams))
		{
			$aListParams['table_id'] = $aExtraParams['table_id2'];
		}
		$aSearchParams = array(
			'criterion_outer_selector' => "#fs_{$sSearchFormId}_criterion_outer",
            'result_list_outer_selector' => "#{$aExtraParams['table_id']}",
			'data_config_list_selector' => "#{$sDataConfigListSelector}",
			'endpoint' => utils::GetAbsoluteUrlAppRoot().'pages/ajax.searchform.php',
			'date_format' => AttributeDateTime::GetFormat()->ToMomentJS(),
			'list_params' => $aListParams,
			'search' => array(
				'has_hidden_criteria' => (array_key_exists('hidden_criteria', $aListParams) && !empty($aListParams['hidden_criteria'])),
				'fields' => $aFields,
				'criterion' => $aCriterion,
				'base_oql' => $sBaseOQL,
			),
		);

		$oPage->add_ready_script('$("#fs_'.$sSearchFormId.'").search_form_handler('.json_encode($aSearchParams).');');

		return $sHtml;
	}

	/**
	 * @param DBObjectSet $oSet
	 *
	 * @return array
	 */
	public function GetFields($oSet)
	{
		$oSearch = $oSet->GetFilter();
		$aSelectedClasses = $oSearch->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aSelectedClasses as $sAlias => $sClassName)
		{
			if (\UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAllFields = array();
		try
		{
			foreach($aAuthorizedClasses as $sAlias => $sClass)
			{
				$aAttributeDefs = MetaModel::ListAttributeDefs($sClass);
				$aList = MetaModel::GetZListItems($sClass, 'standard_search');
				$aZList = array();
				foreach($aList as $sAttCode)
				{
					if (array_key_exists($sAttCode, $aAttributeDefs))
					{
						$oAttDef = $aAttributeDefs[$sAttCode];
						$aZList = $this->AppendField($sClass, $sAlias, $sAttCode, $oAttDef, $aZList);
						unset($aAttributeDefs[$sAttCode]);
					}
				}
				uasort($aZList, function ($aItem1, $aItem2) {
					return strcmp($aItem1['label'], $aItem2['label']);
				});
				$aAllFields['zlist'] = $aZList;

				$aOthers = array();
				foreach($aAttributeDefs as $sAttCode => $oAttDef)
				{
					if ($this->IsSubAttribute($oAttDef)) continue;

					$aOthers = $this->AppendField($sClass, $sAlias, $sAttCode, $oAttDef, $aOthers);
				}
				uasort($aOthers, function ($aItem1, $aItem2) {
					return strcmp($aItem1['label'], $aItem2['label']);
				});

				$aAllFields['others'] = $aOthers;
			}
		} catch (CoreException $e)
		{
			IssueLog::Error($e->getMessage());
		}

		return $aAllFields;
	}

	protected function IsSubAttribute($oAttDef)
	{
		return (($oAttDef instanceof AttributeFriendlyName) || ($oAttDef instanceof AttributeExternalField) || ($oAttDef instanceof AttributeSubItem));
	}

	/**
	 * @param $oAttrDef
	 *
	 * @return array
	 */
	public static function GetFieldAllowedValues($oAttrDef)
	{
		if ($oAttrDef->IsExternalKey(EXTKEY_ABSOLUTE))
		{
			$sTargetClass = $oAttrDef->GetTargetClass();
			try
			{
				$oSearch = new DBObjectSearch($sTargetClass);
			} catch (Exception $e)
			{
				IssueLog::Error($e->getMessage());

				return array('values' => array());
			}
			$oSearch->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->Count() > MetaModel::GetConfig()->Get('max_combo_length'))
			{
				return array('autocomplete' => true);
			}
		}
		else
		{
			if (method_exists($oAttrDef, 'GetAllowedValuesAsObjectSet'))
			{
				$oSet = $oAttrDef->GetAllowedValuesAsObjectSet();
				if ($oSet->Count() > MetaModel::GetConfig()->Get('max_combo_length'))
				{
					return array('autocomplete' => true);
				}
			}
		}

		$aAllowedValues = $oAttrDef->GetAllowedValues();

		return array('values' => $aAllowedValues);
	}

	/**
	 * @param \DBSearch $oSearch
	 * @param array $aFields
	 *
	 * @return array
	 */
	public function GetCriterion($oSearch, $aFields)
	{
		$oExpression = $oSearch->GetCriteria();

		$aOrCriterion = array();
		$aORExpressions = Expression::Split($oExpression, 'OR');
		foreach($aORExpressions as $oORSubExpr)
		{
			$aAndCriterion = array();
			$aAndExpressions = Expression::Split($oORSubExpr, 'AND');
			foreach($aAndExpressions as $oAndSubExpr)
			{
				if ($oAndSubExpr instanceof TrueExpression)
				{
					continue;
				}
				$aAndCriterion[] = $oAndSubExpr->GetCriterion($oSearch);
			}
			$aAndCriterion = CriterionToSearchForm::Convert($aAndCriterion, $aFields);
			$aOrCriterion[] = array('and' => $aAndCriterion);
		}

		return array('or' => $aOrCriterion);
	}

	/**
	 * @param $sClass
	 * @param $sClassAlias
	 * @param $sFilterCode
	 * @param $oAttDef
	 * @param $aFields
	 *
	 * @return mixed
	 */
	private function AppendField($sClass, $sClassAlias, $sFilterCode, $oAttDef, $aFields)
	{
		if (!is_null($oAttDef) && ($oAttDef->GetSearchType() != AttributeDefinition::SEARCH_WIDGET_TYPE_RAW))
		{
			$sLabel = $oAttDef->GetLabel();
			if (!array_key_exists($sLabel, $this->aLabels))
			{
				$aField = array();
				$aField['code'] = $sFilterCode;
				$aField['class'] = $sClass;
				$aField['class_alias'] = $sClassAlias;
				$aField['label'] = $sLabel;
				$aField['widget'] = $oAttDef->GetSearchType();
				$aField['allowed_values'] = self::GetFieldAllowedValues($oAttDef);
				$aField['is_null_allowed'] = $oAttDef->IsNullAllowed();
				$aFields[$sClassAlias.'.'.$sFilterCode] = $aField;
				$this->aLabels[$sLabel] = true;
			}

			// Sub items
			//
			//			if ($oAttDef->IsSearchable())
			//			{
			//				$sShortLabel = $oAttDef->GetLabel();
			//				$sLabel = $sShortAlias.$oAttDef->GetLabel();
			//				$aSubAttr = $this->GetSubAttributes($sClass, $sFilterCode, $oAttDef);
			//				$aValidSubAttr = array();
			//				foreach($aSubAttr as $aSubAttDef)
			//				{
			//					$aValidSubAttr[] = array('attcodeex' => $aSubAttDef['code'], 'code' => $sShortAlias.$aSubAttDef['code'], 'label' => $aSubAttDef['label'], 'unique_label' => $sShortAlias.$aSubAttDef['unique_label']);
			//				}
			//				$aAllFields[] = array('attcodeex' => $sFilterCode, 'code' => $sShortAlias.$sFilterCode, 'label' => $sShortLabel, 'unique_label' => $sLabel, 'subattr' => $aValidSubAttr);
			//			}

		}

		return $aFields;
	}
}