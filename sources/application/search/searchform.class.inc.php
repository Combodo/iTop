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
use AttributeDefinition;
use CMDBObjectSet;
use CoreException;
use DBObjectSearch;
use Dict;
use Expression;
use IssueLog;
use MetaModel;
use TrueExpression;
use utils;
use WebPage;

class SearchForm
{
	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public static function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sClassName = $oSet->GetFilter()->GetClass();

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
			$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass', '$sContext')\">\n".implode('',
					$aOptions)."</select>\n";
		}
		else
		{
			$sClassesCombo = MetaModel::GetName($sClassName);
		}
		$sAction = (isset($aExtraParams['action'])) ? $aExtraParams['action'] : utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		$sHtml .= "<form id=\"fs_{$sSearchFormId}\" action=\"{$sAction}\">\n"; // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
		$sHtml .= "<h2>".Dict::Format('UI:SearchFor_Class_Objects', $sClassesCombo)."</h2>\n";
		$sHtml .= "<div id=\"fs_{$sSearchFormId}_criterion_outer\">\n";
		$sHtml .= "</div>\n";


		$sPrimaryClassName = $oSet->GetClass();
		$sPrimaryClassAlias = $oSet->GetClassAlias();


		$aFields = self::GetFields($sPrimaryClassName, $sPrimaryClassAlias);
		$oSearch = $oSet->GetFilter();
		$aCriterion = self::GetCriterion($oSearch);

		$oBaseSearch = $oSearch->DeepClone();
		$oBaseSearch->ResetCondition();

		$aSearchParams = array(
			'criterion_outer_selector' => "#fs_{$sSearchFormId}_criterion_outer",
			'search' => array(
				'fields' => $aFields,
				'criterion' => $aCriterion,
				'base_oql' => $oBaseSearch->ToOQL(),
			),
		);

		$oPage->add_ready_script('$("#fs_'.$sSearchFormId.'").search_form_handler('.json_encode($aSearchParams).');');

		return $sHtml;
	}

	/**
	 * @param $sClassName
	 *
	 * @param $sClassAlias
	 *
	 * @return array
	 */
	public static function GetFields($sClassName, $sClassAlias)
	{
		$aFields = array();
		try
		{
			$aList = MetaModel::GetZListItems($sClassName, 'standard_search');
			$aAttrDefs = MetaModel::ListAttributeDefs($sClassName);
			foreach($aList as $sFilterCode)
			{
				$aField = array();
				$aField['code'] = $sFilterCode;
				$aField['class'] = $sClassName;
				$aField['class_alias'] = $sClassAlias;
				if (array_key_exists($sFilterCode, $aAttrDefs))
				{
					$oAttrDef = $aAttrDefs[$sFilterCode];
					$aField['label'] = $oAttrDef->GetLabel();
					$aField['widget'] = $oAttrDef->GetSearchType();
					$aField['allowed_values'] = self::GetFieldAllowedValues($oAttrDef);
				}
				else
				{
					$aField['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE;
				}
				$aFields[$sClassAlias.'.'.$sFilterCode] = $aField;
			}
		} catch (CoreException $e)
		{
			IssueLog::Error($e->getMessage());
		}

		return $aFields;
	}

	/**
	 * @param $oAttrDef
	 *
	 * @return array
	 */
	public static function GetFieldAllowedValues($oAttrDef)
	{
		if (method_exists($oAttrDef, 'GetAllowedValuesAsObjectSet'))
		{
			$oSet = $oAttrDef->GetAllowedValuesAsObjectSet();
			if ($oSet->Count() > MetaModel::GetConfig()->Get('max_combo_length'))
			{
				return array('autocomplete' => true);
			}
		}

		return array('values' => $oAttrDef->GetAllowedValues());
	}

	/**
	 * @param DBObjectSearch $oSearch
	 */
	public static function GetCriterion($oSearch)
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
			$aOrCriterion[] = array('and' => $aAndCriterion);
		}

		return array('or' => $aOrCriterion);
	}
}