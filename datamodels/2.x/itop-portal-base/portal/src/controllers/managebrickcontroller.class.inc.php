<?php

// Copyright (C) 2010-2015 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Portal\Controller;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \UserRights;
use \CMDBSource;
use \IssueLog;
use \MetaModel;
use \AttributeDefinition;
use \AttributeDate;
use \AttributeDateTime;
use \AttributeDuration;
use \AttributeSubItem;
use \DBSearch;
use \DBObjectSearch;
use \DBObjectSet;
use \FieldExpression;
use \BinaryExpression;
use \VariableExpression;
use \SQLExpression;
use \UnaryExpression;
use \Dict;
use \Combodo\iTop\Portal\Helper\ApplicationHelper;
use \Combodo\iTop\Portal\Helper\SecurityHelper;
use \Combodo\iTop\Portal\Brick\AbstractBrick;
use \Combodo\iTop\Portal\Brick\ManageBrick;

class ManageBrickController extends BrickController
{

	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId, $sGroupingTab, $sDataLoading = null)
	{
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		$aData = array();
		$aGroupingTabsValues = array();
		$aGroupingAreasValues = array();
		$aQueries = array();

		// Getting current dataloading mode (First from router parameter, then query parameter, then default brick value)
		$sDataLoading = ($sDataLoading !== null) ? $sDataLoading : ( ($oRequest->query->get('sDataLoading') !== null) ? $oRequest->query->get('sDataLoading') : $oBrick->GetDataLoading() );
		// Getting search value
		$sSearchValue = $oRequest->get('sSearchValue', null);

		// Getting area columns properties
		$aColumnsAttrs = $oBrick->GetFields();
		// Adding friendlyname attribute to the list is not already in it
		$sTitleAttrCode = 'friendlyname';
		if (($sTitleAttrCode !== null) && !in_array($sTitleAttrCode, $aColumnsAttrs))
		{
			$aColumnsAttrs = array_merge(array($sTitleAttrCode), $aColumnsAttrs);
		}

		// Starting to build query
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());

		// - Adding search clause if necessary
		// Note : This is a very naive search at the moment
		if ($sSearchValue !== null)
		{
			$aSearchListItems = MetaModel::GetZListItems($oQuery->GetClass(), 'standard_search');
			$oFullBinExpr = null;
			for ($i = 0; $i < count($aSearchListItems); $i++)
			{
				$sSearchItemAttr = $aSearchListItems[$i];
				$oBinExpr = new BinaryExpression(new FieldExpression($sSearchItemAttr, $oQuery->GetClassAlias()), 'LIKE', new VariableExpression('search_value'));

				// At each iteration we build the complete expression for the search like ( (field1 LIKE %search%) OR (field2 LIKE %search%) OR (field3 LIKE %search%) ...)
				if ($i === 0)
				{
					$oFullBinExpr = $oBinExpr;
				}
				else
				{
					$oFullBinExpr = new BinaryExpression($oFullBinExpr, 'OR', $oBinExpr);
				}

				// Then on the last iteration we add the complete expression to the query
				// Note : We don't do it after the loop as there could be an empty search ZList
				if ($i === (count($aSearchListItems) - 1))
				{
					// - Adding expression to the query
					$oQuery->AddConditionExpression($oFullBinExpr);
					// - Setting expression parameters
					// Note : This could be way more simpler if we had a SetInternalParam($sParam, $value) verb
					$aQueryParams = $oQuery->GetInternalParams();
					$aQueryParams['search_value'] = '%' . $sSearchValue . '%';
					$oQuery->SetInternalParams($aQueryParams);
				}
			}
		}

		// Preparing tabs
		// - We need to retrieve distinct values for the grouping attribute
		if ($oBrick->HasGroupingTabs())
		{
			$aGroupingTabs = $oBrick->GetGroupingTabs();

			// If tabs are made of the distinct values of an attribute, we have a find them via a query
			if ($oBrick->IsGroupingTabsByDistinctValues())
			{
				$sGroupingTabAttCode = $aGroupingTabs['attribute'];

				$oDistinctQuery = DBSearch::FromOQL($oBrick->GetOql());
				$oFieldExp = new FieldExpression($sGroupingTabAttCode, $oDistinctQuery->GetClassAlias());
				$sDistinctSql = $oDistinctQuery->MakeGroupByQuery(array(), array('grouped_by_1' => $oFieldExp), true);
				$aDistinctResults = CMDBSource::QueryToArray($sDistinctSql);

				if (!empty($aDistinctResults))
				{
					foreach ($aDistinctResults as $aDistinctResult)
					{
						$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GTAB');
						$oExpression = new BinaryExpression(new FieldExpression($sGroupingTabAttCode, $oDistinctQuery->GetClassAlias()), '=', new UnaryExpression($aDistinctResult['grouped_by_1']));
						$oConditionQuery->AddConditionExpression($oExpression);

						$aGroupingTabsValues[$aDistinctResult['grouped_by_1']] = array(
							'value' => $aDistinctResult['grouped_by_1'],
							'label' => strip_tags($oFieldExp->MakeValueLabel($oDistinctQuery, $aDistinctResult['grouped_by_1'], '')),
							'condition' => $oConditionQuery,
							'count' => $aDistinctResult['_itop_count_']
						);
						unset($oConditionQuery);
					}
					unset($aDistinctResults);
				}
				else
				{
					$aGroupingTabsValues['undefined'] = array(
						'value' => 'undefined',
						'label' => '',
						'condition' => null,
						'count' => null
					);
				}
			}
			// Otherwise we create the tabs from the SQL expressions
			else
			{
				foreach ($aGroupingTabs['groups'] as $aGroup)
				{
					$aGroupingTabsValues[$aGroup['id']] = array(
						'value' => $aGroup['id'],
						'label' => Dict::S($aGroup['title']),
						'condition' => DBSearch::FromOQL($aGroup['condition']),
						'count' => null
					);
				}
			}
		}
		// - Retrieving the current grouping tab to display and altering the query to do so
		if ($sGroupingTab === null)
		{
			if ($oBrick->HasGroupingTabs())
			{
				reset($aGroupingTabsValues);
				$sGroupingTab = key($aGroupingTabsValues);
				if ($aGroupingTabsValues[$sGroupingTab]['condition'] !== null)
				{
					$oQuery = $oQuery->Intersect($aGroupingTabsValues[$sGroupingTab]['condition']);
				}
			}
			else
			{
				// Do not group by tabs, display all in the same page
			}
		}
		else
		{
			if ($aGroupingTabsValues[$sGroupingTab]['condition'] !== null)
			{
				$oQuery = $oQuery->Intersect($aGroupingTabsValues[$sGroupingTab]['condition']);
			}
		}

		// Preparing areas
		// - We need to retrieve distinct values for the grouping attribute
		// Note : Will have to be changed when we consider grouping on something else than the finalclass
		$sParentAlias = $oQuery->GetClassAlias();
		if (true)
		{
			$sGroupingAreaAttCode = 'finalclass';

			// For root classes
			if (MetaModel::IsValidAttCode($oQuery->GetClass(), $sGroupingAreaAttCode))
			{
				$oDistinctQuery = DBSearch::FromOQL($oBrick->GetOql());
				// Checking if there is a scope to apply
				$oDistinctScopeQuery = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $oQuery->GetClass(), UR_ACTION_READ);
				if ($oDistinctScopeQuery != null)
				{
					$oDistinctQuery = $oDistinctQuery->Intersect($oDistinctScopeQuery);
					// - Allowing all data if necessary
					if ($oDistinctScopeQuery->IsAllDataAllowed())
					{
						$oDistinctQuery->AllowAllData();
					}
				}
				// Adding grouping conditions
				$oFieldExp = new FieldExpression($sGroupingAreaAttCode, $sParentAlias);
				$sDistinctSql = $oDistinctQuery->MakeGroupByQuery(array(), array('grouped_by_1' => $oFieldExp), true);
				$aDistinctResults = CMDBSource::QueryToArray($sDistinctSql);

				foreach ($aDistinctResults as $aDistinctResult)
				{
					$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GARE');
					$oExpression = new BinaryExpression(new FieldExpression($sGroupingAreaAttCode, 'GARE'), '=', new UnaryExpression($aDistinctResult['grouped_by_1']));
					$oConditionQuery->AddConditionExpression($oExpression);

					$aGroupingAreasValues[$aDistinctResult['grouped_by_1']] = array(
						'value' => $aDistinctResult['grouped_by_1'],
						'label' => MetaModel::GetName($aDistinctResult['grouped_by_1']), // Caution : This works only because we froze the grouping areas on the finalclass attribute.
						'condition' => $oConditionQuery,
						'count' => $aDistinctResult['_itop_count_']
					);
					unset($oConditionQuery);
				}
				unset($aDistinctResults);
			}
			// For leaf classes
			else
			{
				$aGroupingAreasValues[$oQuery->GetClass()] = array(
					'value' => $oQuery->GetClass(),
					'label' => MetaModel::GetName($oQuery->GetClass()), // Caution : This works only because we froze the grouping areas on the finalclass attribute.
					'condition' => null,
					'count' => 0
				);
			}
		}
		// - Retrieving the grouping areas to display
		$sGroupingArea = $oRequest->get('sGroupingArea');
		//   - If specified or lazy loading, we trunc the $aGroupingAreasValues to keep only this one
		if ($sGroupingArea !== null)
		{
			$aGroupingAreasValues = array($sGroupingArea => $aGroupingAreasValues[$sGroupingArea]);
		}
		//   - Preapring the queries
		foreach ($aGroupingAreasValues as $sKey => $aGroupingAreasValue)
		{
			$oAreaQuery = DBSearch::CloneWithAlias($oQuery, $sParentAlias);
			if ($aGroupingAreasValue['condition'] !== null)
			{
				//$oAreaQuery->AddConditionExpression($aGroupingAreasValue['condition']);
				$oAreaQuery = $oAreaQuery->Intersect($aGroupingAreasValue['condition']);
			}

			// Restricting query to allowed scope on each classes
			// Note : Will need to moved the scope restriction on queries elsewhere when we consider grouping on something else than finalclass
			// Note : We now get view scope instead of edit scope as we allowed users to view/edit objects in the brick regarding their rights
			$oScopeQuery = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $aGroupingAreasValue['value'], UR_ACTION_READ);
			if ($oScopeQuery !== null)
			{
				$oAreaQuery = $oAreaQuery->Intersect($oScopeQuery);
				// - Allowing all data if necessary
				if ($oScopeQuery->IsAllDataAllowed())
				{
					$oAreaQuery->AllowAllData();
				}
			}
			else
			{
				$oAreaQuery = null;
			}

			$aQueries[$sKey] = $oAreaQuery;
		}

		// Testing appropriate data loading mode if we are in auto
		// - For all (html) tables, this doesn't care for the grouping ares (finalclass)
		if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_AUTO)
		{
			// - Check how many records there is.
			// - Update $sDataLoading with its new value regarding the number of record and the threshold
			$oCountSet = new DBObjectSet($oQuery);
			$oCountSet->OptimizeColumnLoad(array());
			$fThreshold = (float) MetaModel::GetModuleSetting($oApp['combodo.portal.instance.id'], 'lazy_loading_threshold');
			$sDataLoading = ($oCountSet->Count() > $fThreshold) ? AbstractBrick::ENUM_DATA_LOADING_LAZY : AbstractBrick::ENUM_DATA_LOADING_FULL;
			unset($oCountSet);
		}

		// Preparing data sets
		$aSets = array();
		foreach ($aQueries as $sKey => $oQuery)
		{
			// Checking if we have a valid query
			if ($oQuery !== null)
			{
				// Setting query pagination if needed
				if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY)
				{
					// Retrieving parameters
					$iPageNumber = (int) $oRequest->get('iPageNumber', 1);
					$iCountPerPage = (int) $oRequest->get('iCountPerPage', ManageBrick::DEFAULT_COUNT_PER_PAGE_LIST);

					// Getting total records number
					$oCountSet = new DBObjectSet($oQuery);
					$oCountSet->OptimizeColumnLoad(array($oQuery->GetClassAlias() => $aColumnsAttrs));
					$aData['recordsTotal'] = $oCountSet->Count();
					$aData['recordsFiltered'] = $oCountSet->Count();
					unset($oCountSet);

					$oSet = new DBObjectSet($oQuery);
					$oSet->SetLimit($iCountPerPage, $iCountPerPage * ($iPageNumber - 1));
				}
				else
				{
					$oSet = new DBObjectSet($oQuery);
				}
				$oSet->OptimizeColumnLoad(array($oQuery->GetClassAlias() => $aColumnsAttrs));
				$oSet->SetOrderByClasses();
				$aSets[$sKey] = $oSet;
			}
		}

		// Retrieving and preparing datas for rendering
		$aGroupingAreasData = array();
		foreach ($aSets as $sKey => $oSet)
		{
			// Set properties
			$sCurrentClass = $sKey;
			
			// Defining which attribute will open the edition form)
			$sMainActionAttrCode = $aColumnsAttrs[0];

			// Loading columns definition
			$aColumnsDefinition = array();
			foreach ($aColumnsAttrs as $sColumnAttr)
			{
				$oAttDef = MetaModel::GetAttributeDef($sKey, $sColumnAttr);
				$aColumnsDefinition[$sColumnAttr] = array(
					'title' => $oAttDef->GetLabel(),
					'type' => ($oAttDef instanceof AttributeDateTime) ? 'moment-'.$oAttDef->GetFormat()->ToMomentJS() : 'html', // Special sorting for Date & Time
				);
			}

			// Getting items
			$aItems = array();
			// ... For each item
			while ($oCurrentRow = $oSet->Fetch())
			{
				// ... Retrieving item's attributes values
				$aItemAttrs = array();
				foreach ($aColumnsAttrs as $sItemAttr)
				{
					$aActions = array();
					// Set the edit action to the main (first) attribute only
					//if ($sItemAttr === $sTitleAttrCode)
					if ($sItemAttr === $sMainActionAttrCode)
					{
						// Checking if we can edit the object
						if (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_MODIFY, $sCurrentClass, $oCurrentRow->GetKey()) && ($oBrick->GetOpeningMode() === ManageBrick::ENUM_ACTION_EDIT))
						{
							$sActionType = ManageBrick::ENUM_ACTION_EDIT;
						}
						// - Otherwise, check if view is allowed
						elseif (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sCurrentClass, $oCurrentRow->GetKey()))
						{
							$sActionType = ManageBrick::ENUM_ACTION_VIEW;
						}
						else
						{
							$sActionType = null;
						}
						// - Then set allowed action
						if ($sActionType !== null)
						{
							$aActions[] = array(
								'type' => $sActionType,
								'class' => $sCurrentClass,
								'id' => $oCurrentRow->GetKey()
							);
						}
					}
					
					$oAttDef = MetaModel::GetAttributeDef($sCurrentClass, $sItemAttr);
					if ($oAttDef->IsExternalKey())
					{
						$sValue = $oCurrentRow->Get($sItemAttr . '_friendlyname');

						// Adding a view action on the external keys
						if ($oCurrentRow->Get($sItemAttr) !== $oAttDef->GetNullValue())
						{
							// Checking if we can view the object
							if ((SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $oAttDef->GetTargetClass(), $oCurrentRow->Get($sItemAttr))))
							{
								$aActions[] = array(
									'type' => ManageBrick::ENUM_ACTION_VIEW,
									'class' => $oAttDef->GetTargetClass(),
									'id' => $oCurrentRow->Get($sItemAttr)
								);
							}
						}
					}
					elseif ($oAttDef instanceof AttributeSubItem || $oAttDef instanceof AttributeDuration)
					{
						$sValue = $oAttDef->GetAsHTML($oCurrentRow->Get($sItemAttr));
					}
					else
					{
						$sValue = $oAttDef->GetValueLabel($oCurrentRow->Get($sItemAttr));
					}
					unset($oAttDef);

					$aItemAttrs[$sItemAttr] = array(
						'att_code' => $sItemAttr,
						'value' => $sValue,
						'actions' => $aActions
					);
				}
				
				// ... And item's properties
				$aItems[] = array(
					'id' => $oCurrentRow->GetKey(),
					'class' => $sCurrentClass,
					'attributes' => $aItemAttrs,
					'highlight_class' => $oCurrentRow->GetHilightClass()
				);
			}

			$aGroupingAreasData[$sKey] = array(
				'sId' => $sKey,
				'sTitle' => $aGroupingAreasValues[$sKey]['label'],
				'aItems' => $aItems,
				'iItemsCount' => $oSet->Count(),
				'aColumnsDefinition' => $aColumnsDefinition
			);
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$aData = $aData + array(
				'data' => $aGroupingAreasData[$sGroupingArea]['aItems']
			);
			$oResponse = $oApp->json($aData);
		}
		else
		{
			$aData = $aData + array(
				'oBrick' => $oBrick,
				'sBrickId' => $sBrickId,
				'sGroupingTab' => $sGroupingTab,
				'aGroupingTabsValues' => $aGroupingTabsValues,
				'sDataLoading' => $sDataLoading,
				'aGroupingAreasData' => $aGroupingAreasData,
				'sDateFormat' => AttributeDate::GetFormat()->ToMomentJS(),
				'sDateTimeFormat' => AttributeDateTime::GetFormat()->ToMomentJS(),
			);

			$oResponse = $oApp['twig']->render($oBrick->GetPageTemplatePath(), $aData);
		}

		return $oResponse;
	}

}
