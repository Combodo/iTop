<?php

// Copyright (C) 2010-2018 Combodo SARL
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

use Exception;
use AttributeDate;
use AttributeDateTime;
use AttributeDefinition;
use AttributeDuration;
use AttributeSubItem;
use BinaryExpression;
use CMDBSource;
use Combodo\iTop\Portal\Brick\AbstractBrick;
use Combodo\iTop\Portal\Brick\ManageBrick;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\SecurityHelper;
use DBObject;
use DBObjectSet;
use DBSearch;
use Dict;
use FieldExpression;
use iPopupMenuExtension;
use JSButtonItem;
use MetaModel;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use UnaryExpression;
use URLButtonItem;
use UserRights;
use VariableExpression;

class ManageBrickController extends BrickController
{
	const EXCEL_EXPORT_TEMPLATE_PATH = 'itop-portal-base/portal/src/views/bricks/manage/popup-export-excel.html.twig';

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param \Silex\Application $oApp
	 * @param string $sBrickId
	 * @param string $sDisplayMode
	 * @param string $sGroupingTab
	 * @param string $sDataLoading
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId, $sGroupingTab, $sDisplayMode = null, $sDataLoading = null)
    {
		/** @var ManageBrick $oBrick */
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		if (is_null($sDisplayMode))
		{
			$sDisplayMode = $oBrick->GetDefaultDisplayMode();
		}
		$aDisplayParams = $oBrick->GetPresentationDataForTileMode($sDisplayMode);
		$aData = $this->GetData($oRequest, $oApp, $sBrickId, $sGroupingTab, $oBrick::AreDetailsNeededForDisplayMode($sDisplayMode));

		$aExportFields = $oBrick->GetExportFields();
		$aData = $aData + array(
				'sDisplayMode' => $sDisplayMode,
				'bCanExport' => !empty($aExportFields),
			);
		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$oResponse = $oApp->json($aData);
		}
		else
		{
			$sLayoutTemplate = $oBrick::GetPageTemplateFromDisplayMode($sDisplayMode);
			$oResponse = $oApp['twig']->render($sLayoutTemplate, $aData);
		}

		return $oResponse;
	}

	/**
	 * Method for the brick's tile on home page
	 *
	 * @param Request $oRequest
	 * @param Application $oApp
	 * @param string $sBrickId
	 *
	 * @return Response
	 */
	public function TileAction(Request $oRequest, Application $oApp, $sBrickId)
	{
		/** @var ManageBrick $oBrick */
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		try
		{
			$aData = $this->GetData($oRequest, $oApp, $sBrickId, null);
		}
		catch (Exception $e)
		{
			// TODO Default values
			$aData = array();
		}

		return $oApp['twig']->render($oBrick->GetTileTemplatePath(), $aData);
	}

	/**
	 * @param Request $oRequest
	 * @param Application $oApp
	 * @param string $sBrickId
	 * @param string $sGroupingTab
	 * @param string $sGroupingArea
	 *
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function ExcelExportStartAction(
		Request $oRequest, Application $oApp, $sBrickId, $sGroupingTab, $sGroupingArea
	) {
		/** @var ManageBrick $oBrick */
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());
		$sClass = $oQuery->GetClass();
		$aData = $this->GetData($oRequest, $oApp, $sBrickId, $sGroupingTab, true);

		if (isset($aData['aQueries']) && count($aData['aQueries']) === 1)
		{
			$aQueries = $aData['aQueries'];
			reset($aQueries);
			$sKey = key($aQueries);
			$oSearch = $aData['aQueries'][$sKey];
		}
		else
		{
			$oQuery = DBSearch::FromOQL($oBrick->GetOql());
			$sClass = $oQuery->GetClass();
			/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeHelper */
			$oScopeHelper = $oApp['scope_validator'];
			$oScopeHelper->AddScopeToQuery($oQuery, $sClass);
			$aData = array();
			$this->ManageSearchValue($oRequest, $aData, $oQuery, $sClass);

			// Grouping tab
			if ($oBrick->HasGroupingTabs())
			{
				$aGroupingTabs = $oBrick->GetGroupingTabs();

				// If tabs are made of the distinct values of an attribute, we have a find them via a query
				if ($oBrick->IsGroupingTabsByDistinctValues())
				{
					$sGroupingTabAttCode = $aGroupingTabs['attribute'];
					$aGroupingTabsValues = $this->GroupByAttribute($oQuery, $sGroupingTabAttCode, $oApp, $oBrick);
					$oQuery = $oQuery->Intersect($aGroupingTabsValues[$sGroupingTab]['condition']);
				}
				else
				{
					foreach ($aGroupingTabs['groups'] as $aGroup)
					{
						if ($aGroup['id'] === $sGroupingTab)
						{
							$oConditionQuery = $oQuery->Intersect(DBSearch::FromOQL($aGroup['condition']));
							$oQuery = $oQuery->Intersect($oConditionQuery);
							break;
						}
					}
				}
			}

			// Finalclass
			$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GARE');
			$oExpression = new BinaryExpression(new FieldExpression('finalclass', 'GARE'), '=',
				new UnaryExpression($sGroupingArea));
			$oConditionQuery->AddConditionExpression($oExpression);
			/** @var DBSearch $oSearch */
			$oSearch = $oQuery->Intersect($oConditionQuery);
		}

		$aColumnsAttrs = $oBrick->GetExportFields();
		$aFields = array();
		$sTitleAttrCode = 'friendlyname';
		if (!in_array($sTitleAttrCode, $aColumnsAttrs))
		{
			$aFields[] = $sTitleAttrCode;
		}
		foreach ($aColumnsAttrs as $sAttCode)
		{
			$oAttributeDef = MetaModel::GetAttributeDef($sGroupingArea, $sAttCode);
			if ($oAttributeDef->IsExternalKey(EXTKEY_ABSOLUTE))
			{
				$aFields[] = $sAttCode.'_friendlyname';
			}
			else
			{
				$aFields[] = $sAttCode;
			}
		}

		$sFields = implode(',', $aFields);
		$aData = array(
			'oBrick' => $oBrick,
			'sBrickId' => $sBrickId,
			'sFields' => $sFields,
			'sOQL' => $oSearch->ToOQL(),
		);

		return $oApp['twig']->render(static::EXCEL_EXPORT_TEMPLATE_PATH, $aData);
	}


	/**
	 * @param Request $oRequest
	 * @param Application $oApp
	 * @param string $sBrickId
	 * @param string $sGroupingTab
	 * @param bool $bNeedDetails
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function GetData(Request $oRequest, Application $oApp, $sBrickId, $sGroupingTab, $bNeedDetails = false)
	{
		/** @var ManageBrick $oBrick */
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		$aData = array();
		$aGroupingTabsValues = array();
		$aGroupingAreasValues = array();
		$aQueries = array();
		$bHasScope = true;

		// Getting current dataloading mode (First from router parameter, then query parameter, then default brick value)
		$sDataLoading = ($oRequest->get('sDataLoading') !== null) ? $oRequest->get('sDataLoading') : $oBrick->GetDataLoading();

		// - Retrieving the grouping areas to display
		$sGroupingArea = $oRequest->get('sGroupingArea');
		if (!is_null($sGroupingArea))
		{
			$bNeedDetails = true;
		}

		// Getting area columns properties
		$aColumnsAttrs = $oBrick->GetFields();
		// Adding friendlyname attribute to the list if not already in it
		$sTitleAttrCode = 'friendlyname';
		if (($sTitleAttrCode !== null) && !in_array($sTitleAttrCode, $aColumnsAttrs))
		{
			$aColumnsAttrs = array_merge(array($sTitleAttrCode), $aColumnsAttrs);
		}

		// Starting to build query
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());
		$sClass = $oQuery->GetClass();
		$sIconURL = MetaModel::GetClassIcon($sClass, false);

		// Preparing tabs
		// - We need to retrieve distinct values for the grouping attribute
		$iCount = 0;
		if ($oBrick->HasGroupingTabs())
		{
			$aGroupingTabs = $oBrick->GetGroupingTabs();

			// If tabs are made of the distinct values of an attribute, we have a find them via a query
			if ($oBrick->IsGroupingTabsByDistinctValues())
			{
				$sGroupingTabAttCode = $aGroupingTabs['attribute'];
				$aGroupingTabsValues = $this->GroupByAttribute($oQuery, $sGroupingTabAttCode, $oApp, $oBrick);
				foreach ($aGroupingTabsValues as $aResult)
				{
					$iCount += $aResult['count'];
				}
			}
			// Otherwise we create the tabs from the SQL expressions
			else
			{
				foreach ($aGroupingTabs['groups'] as $aGroup)
				{
					$oConditionQuery = $oQuery->Intersect(DBSearch::FromOQL($aGroup['condition']));
					// - Restricting query to scope

					/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeHelper */
					$oScopeHelper = $oApp['scope_validator'];
					$bHasScope = $oScopeHelper->AddScopeToQuery($oConditionQuery, $oConditionQuery->GetClass());
					if ($bHasScope)
					{
						// - Building ObjectSet
						$oConditionSet = new DBObjectSet($oConditionQuery);
						$iGroupCount = $oConditionSet->Count();
					}
					else
					{
						$oConditionSet = null;
						$iGroupCount = 0;
					}
					$aGroupingTabsValues[$aGroup['id']] = array(
						'value' => $aGroup['id'],
						'label' => Dict::S($aGroup['title']),
						'label_html' => Dict::S($aGroup['title']),
						'condition' => $oConditionQuery,
						'count' => $iGroupCount,
					);
					$iCount += $iGroupCount;
				}
			}
		}
		else
		{
			$oConditionQuery = $this->GetScopedQuery($oApp, $oBrick, $sClass);
			if (!is_null($oConditionQuery))
			{
				$oSet = new DBObjectSet($oConditionQuery);
				$iCount = $oSet->Count();
			}
		}

		// - Retrieving the current grouping tab to display if necessary and altering the query to do so
		if ($sGroupingTab === null)
		{
			if ($oBrick->HasGroupingTabs())
			{
				reset($aGroupingTabsValues);
				$sGroupingTab = key($aGroupingTabsValues);
				if ($aGroupingTabsValues[$sGroupingTab]['condition'] !== null)
				{
					$oQuery = $aGroupingTabsValues[$sGroupingTab]['condition']->DeepClone();
				}
			}
		}
		else
		{
			if ($aGroupingTabsValues[$sGroupingTab]['condition'] !== null)
			{
				$oQuery = $aGroupingTabsValues[$sGroupingTab]['condition']->DeepClone();
			}
		}

        // - Adding search clause if necessary
        $this->ManageSearchValue($oRequest, $aData, $oQuery, $sClass, $aColumnsAttrs);

		// Preparing areas
		// - We need to retrieve distinct values for the grouping attribute
		// Note : Will have to be changed when we consider grouping on something else than the finalclass
		$sParentAlias = $oQuery->GetClassAlias();
		if ($bNeedDetails)
		{
			$sGroupingAreaAttCode = 'finalclass';

			// For root classes
			if (MetaModel::IsValidAttCode($sClass, $sGroupingAreaAttCode))
			{
				$oDistinctQuery = $this->GetScopedQuery($oApp, $oBrick, $sClass);
				// Adding grouping conditions
				$oFieldExp = new FieldExpression($sGroupingAreaAttCode, $oDistinctQuery->GetClassAlias());
				$sDistinctSql = $oDistinctQuery->MakeGroupByQuery(array(), array('grouped_by_1' => $oFieldExp), true);
				$aDistinctResults = CMDBSource::QueryToArray($sDistinctSql);

				foreach ($aDistinctResults as $aDistinctResult)
				{
					$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GARE');
					$oExpression = new BinaryExpression(new FieldExpression($sGroupingAreaAttCode, 'GARE'), '=',
						new UnaryExpression($aDistinctResult['grouped_by_1']));
					$oConditionQuery->AddConditionExpression($oExpression);

					$aGroupingAreasValues[$aDistinctResult['grouped_by_1']] = array(
						'value' => $aDistinctResult['grouped_by_1'],
						'label' => MetaModel::GetName($aDistinctResult['grouped_by_1']),
						// Caution : This works only because we froze the grouping areas on the finalclass attribute.
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
				$aGroupingAreasValues[$sClass] = array(
					'value' => $sClass,
					'label' => MetaModel::GetName($sClass),
					// Caution : This works only because we froze the grouping areas on the finalclass attribute.
					'condition' => null,
					'count' => 0
				);
			}

			//   - If specified or lazy loading, we truncate the $aGroupingAreasValues to keep only this one
			if ($sGroupingArea !== null)
			{
				$aGroupingAreasValues = array($sGroupingArea => $aGroupingAreasValues[$sGroupingArea]);
			}
			//   - Preparing the queries
			foreach ($aGroupingAreasValues as $sKey => $aGroupingAreasValue)
			{
				$oAreaQuery = DBSearch::CloneWithAlias($oQuery, $sParentAlias);
				if ($aGroupingAreasValue['condition'] !== null)
				{
					$oAreaQuery = $aGroupingAreasValue['condition']->DeepClone();
				}

				// Restricting query to allowed scope on each classes
				// Note: Will need to moved the scope restriction on queries elsewhere when we consider grouping on something else than finalclass
				// Note: We now get view scope instead of edit scope as we allowed users to view/edit objects in the brick regarding their rights
				/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeHelper */
				$oScopeHelper = $oApp['scope_validator'];
				$bHasScope = $oScopeHelper->AddScopeToQuery($oAreaQuery, $aGroupingAreasValue['value']);
				if (!$bHasScope)
				{
					// if no scope apply does not allow any data
					$oAreaQuery = null;
				}

				$aQueries[$sKey] = $oAreaQuery;
			}

			$aData['aQueries'] = $aQueries;

			// Testing appropriate data loading mode if we are in auto
			// - For all (html) tables, this doesn't care for the grouping ares (finalclass)
			if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_AUTO)
			{
				// - Check how many records there is.
				// - Update $sDataLoading with its new value regarding the number of record and the threshold
				$oCountSet = new DBObjectSet($oQuery);
				$oCountSet->OptimizeColumnLoad(array());
				$fThreshold = (float)MetaModel::GetModuleSetting($oApp['combodo.portal.instance.id'],
					'lazy_loading_threshold');
				$sDataLoading = ($oCountSet->Count() > $fThreshold) ? AbstractBrick::ENUM_DATA_LOADING_LAZY : AbstractBrick::ENUM_DATA_LOADING_FULL;
				unset($oCountSet);
			}

			// Preparing data sets
			$aSets = array();
			/** @var DBSearch $oQuery */
			foreach ($aQueries as $sKey => $oQuery)
			{
				// Checking if we have a valid query
				if ($oQuery !== null)
				{
					// Setting query pagination if needed
					if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY)
					{
						// Retrieving parameters
						$iPageNumber = (int)$oRequest->get('iPageNumber', 1);
						$iListLength = (int)$oRequest->get('iListLength', ManageBrick::DEFAULT_LIST_LENGTH);

						// Getting total records number
						$oCountSet = new DBObjectSet($oQuery);
						$oCountSet->OptimizeColumnLoad(array($oQuery->GetClassAlias() => $aColumnsAttrs));
						$aData['recordsTotal'] = $oCountSet->Count();
						$aData['recordsFiltered'] = $oCountSet->Count();
						unset($oCountSet);

						$oSet = new DBObjectSet($oQuery);
						$oSet->SetLimit($iListLength, $iListLength * ($iPageNumber - 1));
					}
					else
					{
						$oSet = new DBObjectSet($oQuery);
					}

					// Adding always_in_tables attributes
					$aColumnsToLoad = array($oQuery->GetClassAlias() => $aColumnsAttrs);
					foreach ($oQuery->GetSelectedClasses() as $sAlias => $sClassSelected)
					{
						/** @var AttributeDefinition $oAttDef */
						foreach (MetaModel::ListAttributeDefs($sClassSelected) as $sAttCode => $oAttDef)
						{
							if ($oAttDef->AlwaysLoadInTables())
							{
								$aColumnsToLoad[$sAlias][] = $sAttCode;
							}
						}
					}

					$oSet->OptimizeColumnLoad($aColumnsToLoad);
					$oSet->SetOrderByClasses();
					SecurityHelper::PreloadForCache($oApp, $oSet->GetFilter(),
						$aColumnsToLoad[$oQuery->GetClassAlias()] /* preloading only extkeys from the main class */);
					$aSets[$sKey] = $oSet;
				}
			}

			// Retrieving and preparing data for rendering
			$aGroupingAreasData = array();
			$bHasObjectListItemExtension = false;
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
						'type' => ($oAttDef instanceof AttributeDateTime) ? 'moment-'.$oAttDef->GetFormat()->ToMomentJS() : 'html',
						// Special sorting for Date & Time
					);
				}

				// Getting items
				$aItems = array();
				// ... For each item
				/** @var DBObject $oCurrentRow */
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
							if (($oBrick->GetOpeningMode() === ManageBrick::ENUM_ACTION_EDIT) && SecurityHelper::IsActionAllowed($oApp,
									UR_ACTION_MODIFY, $sCurrentClass, $oCurrentRow->GetKey()))
							{
								$sActionType = ManageBrick::ENUM_ACTION_EDIT;
							}
							// - Otherwise, check if view is allowed
							elseif (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $sCurrentClass,
								$oCurrentRow->GetKey()))
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
									'id' => $oCurrentRow->GetKey(),
									'opening_target' => $oBrick->GetOpeningTarget(),
								);
							}
						}

						/** @var AttributeDefinition $oAttDef */
						$oAttDef = MetaModel::GetAttributeDef($sCurrentClass, $sItemAttr);
						if ($oAttDef->IsExternalKey())
						{
							$sValue = $oCurrentRow->Get($sItemAttr.'_friendlyname');

							// Adding a view action on the external keys
							if ($oCurrentRow->Get($sItemAttr) !== $oAttDef->GetNullValue())
							{
								// Checking if we can view the object
								if ((SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $oAttDef->GetTargetClass(),
									$oCurrentRow->Get($sItemAttr))))
								{
									$aActions[] = array(
										'type' => ManageBrick::ENUM_ACTION_VIEW,
										'class' => $oAttDef->GetTargetClass(),
										'id' => $oCurrentRow->Get($sItemAttr),
										'opening_target' => $oBrick->GetOpeningTarget(),
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

					// ... Checking menu extensions
					$aItemButtons = array();
					foreach (MetaModel::EnumPlugins('iPopupMenuExtension') as $oExtensionInstance)
					{
						foreach ($oExtensionInstance->EnumItems(iPopupMenuExtension::PORTAL_OBJLISTITEM_ACTIONS, array(
							'portal_id' => $oApp['combodo.portal.instance.id'],
							'object' => $oCurrentRow
						)) as $oMenuItem)
						{
							if (is_object($oMenuItem))
							{
								if ($oMenuItem instanceof JSButtonItem)
								{
									$aItemButtons[] = $oMenuItem->GetMenuItem() + array(
											'js_files' => $oMenuItem->GetLinkedScripts(),
											'type' => 'button'
										);
								}
								elseif ($oMenuItem instanceof URLButtonItem)
								{
									$aItemButtons[] = $oMenuItem->GetMenuItem() + array('type' => 'link');
								}
							}
						}
					}

					// ... And item's properties
					$aItems[] = array(
						'id' => $oCurrentRow->GetKey(),
						'class' => $sCurrentClass,
						'attributes' => $aItemAttrs,
						'highlight_class' => $oCurrentRow->GetHilightClass(),
						'actions' => $aItemButtons,
					);

					if (!empty($aItemButtons))
					{
						$bHasObjectListItemExtension = true;
					}
				}

				// Adding an extra column for object list item extensions
				if ($bHasObjectListItemExtension === true)
				{
					$aColumnsDefinition['_ui_extensions'] = array(
						'title' => Dict::S('Brick:Portal:Manage:Table:ItemActions'),
						'type' => 'html',
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
		}
		else
		{
			$aGroupingAreasData = array();
			$sGroupingArea = null;
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$aData = $aData + array(
					'data' => $aGroupingAreasData[$sGroupingArea]['aItems']
				);
		}
		else
		{
			$aDisplayValues = array();
			$aUrls = array();
			$aColumns = array();
			$aNames = array();
			if ($bHasScope)
			{
				foreach ($aGroupingTabsValues as $aValues)
				{
					$aDisplayValues[] = array(
						'value' => $aValues['count'],
						'label' => $aValues['label'],
						'label_html' => $aValues['label_html'],
					);
					$aUrls[] = $oApp['url_generator']->generate('p_manage_brick', array(
						'sBrickId' => $sBrickId,
						'sDisplayMode' => 'default',
						'sGroupingTab' => $aValues['value']
					));
				}

				foreach ($aDisplayValues as $idx => $aValue)
				{
					$aColumns[] = array('series_'.$idx, (int)$aValue['value']);
					$aNames['series_'.$idx] = $aValue['label'];
				}
			}

			// Preparing data to pass to the templating service
			$aData = $aData + array(
					'sFct' => 'count',
					'sIconURL' => $sIconURL,
					'aColumns' => $aColumns,
					'aNames' => $aNames,
					'aDisplayValues' => $aDisplayValues,
					'aUrls' => $aUrls,
					'oBrick' => $oBrick,
					'sBrickId' => $sBrickId,
					'sGroupingTab' => $sGroupingTab,
					'aGroupingTabsValues' => $aGroupingTabsValues,
					'sDataLoading' => $sDataLoading,
					'aGroupingAreasData' => $aGroupingAreasData,
					'sDateFormat' => AttributeDate::GetFormat()->ToMomentJS(),
					'sDateTimeFormat' => AttributeDateTime::GetFormat()->ToMomentJS(),
					'iCount' => $iCount,
				);
		}

		return $aData;
	}

	/**
	 * @param Request $oRequest
	 * @param array $aData
	 * @param DBSearch $oQuery
	 * @param string $sClass
	 */
	protected function ManageSearchValue(Request $oRequest, &$aData, DBSearch &$oQuery, $sClass, $aColumnsAttrs)
	{
		// Getting search value
		$sSearchValue = $oRequest->get('sSearchValue', null);

		// - Adding search clause if necessary
		// Note : This is a very naive search at the moment
		if ($sSearchValue !== null)
		{
		    // Putting only valid attributes as one can define attributes of leaf classes in the brick definition (<fields>), but at this stage we are working on the abstract class.
            // Note: This won't fix everything as the search will not be looking in all fields.
		    $aSearchListItems = array();
		    foreach($aColumnsAttrs as $sColumnAttr)
            {
                if(MetaModel::IsValidAttCode($sClass, $sColumnAttr))
                {
                    $aSearchListItems[] = $sColumnAttr;
                }
            }

			$oFullBinExpr = null;
			foreach ($aSearchListItems as $sSearchItemAttr)
			{
				$oBinExpr = new BinaryExpression(new FieldExpression($sSearchItemAttr, $oQuery->GetClassAlias()),
					'LIKE', new VariableExpression('search_value'));
				// At each iteration we build the complete expression for the search like ( (field1 LIKE %search%) OR (field2 LIKE %search%) OR (field3 LIKE %search%) ...)
				if (is_null($oFullBinExpr))
				{
					$oFullBinExpr = $oBinExpr;
				}
				else
				{
					$oFullBinExpr = new BinaryExpression($oFullBinExpr, 'OR', $oBinExpr);
				}
			}

			// Then add the complete expression to the query
			if (!is_null($oFullBinExpr))
			{
				// - Adding expression to the query
				$oQuery->AddConditionExpression($oFullBinExpr);
				// - Setting expression parameters
				// Note : This could be way more simpler if we had a SetInternalParam($sParam, $value) verb
				$aQueryParams = $oQuery->GetInternalParams();
				$aQueryParams['search_value'] = '%'.$sSearchValue.'%';
				$oQuery->SetInternalParams($aQueryParams);
			}
		}

		$aData['sSearchValue'] = $sSearchValue;
	}

	/**
	 * Get the groups using a given attribute code.
	 * If a limit is given, the remaining groups are aggregated (groupby result and search request).
	 *
	 * @param DBSearch $oQuery Initial query
	 * @param string $sGroupingTabAttCode Attribute code to group by
	 * @param Application $oApp
	 * @param ManageBrick $oBrick
	 *
	 * @return array of results from the groupby request and the corrsponding search.
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function GroupByAttribute(
		DBSearch $oQuery, $sGroupingTabAttCode, Application $oApp, ManageBrick $oBrick
	) {
		$aGroupingTabsValues = array();
		$aDistinctResults = array();
		$oDistinctQuery = DBSearch::FromOQL($oBrick->GetOql());
		/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeHelper */
		$oScopeHelper = $oApp['scope_validator'];
		$bHasScope = $oScopeHelper->AddScopeToQuery($oDistinctQuery, $oDistinctQuery->GetClass());
		if ($bHasScope)
		{
			// - Adding field condition
			$oFieldExp = new FieldExpression($sGroupingTabAttCode, $oDistinctQuery->GetClassAlias());
			$sDistinctSql = $oDistinctQuery->MakeGroupByQuery(array(), array('grouped_by_1' => $oFieldExp), true);
			$aDistinctResults = CMDBSource::QueryToArray($sDistinctSql);
			if (!empty($aDistinctResults))
			{
				$iLimit = $oBrick->GetGroupLimit();
				$aOthers = array();
				if ($iLimit > 0)
				{
					uasort($aDistinctResults, function ($a, $b) {
						$v1 = $a['_itop_count_'];
						$v2 = $b['_itop_count_'];

						return ($v1 == $v2) ? 0 : (($v1 > $v2) ? -1 : 1);
					});

					if (count($aDistinctResults) > $iLimit)
					{
						if ($oBrick->ShowGroupOthers())
						{
							$aOthers = array_slice($aDistinctResults, $iLimit);
						}
						$aDistinctResults = array_slice($aDistinctResults, 0, $iLimit);
					}
				}

				foreach ($aDistinctResults as $aDistinctResult)
				{
					$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GTAB');
					$oExpression = new BinaryExpression(new FieldExpression($sGroupingTabAttCode,
                        $oConditionQuery->GetClassAlias()), '=', new UnaryExpression($aDistinctResult['grouped_by_1']));
					$oConditionQuery->AddConditionExpression($oExpression);

					$sHtmlLabel = $oFieldExp->MakeValueLabel($oDistinctQuery, $aDistinctResult['grouped_by_1'], '');
					$aGroupingTabsValues[$aDistinctResult['grouped_by_1']] = array(
						'value' => $aDistinctResult['grouped_by_1'],
						'label_html' => $sHtmlLabel,
						'label' => strip_tags($sHtmlLabel),
						'condition' => $oConditionQuery,
						'count' => $aDistinctResult['_itop_count_'],
					);
					unset($oConditionQuery);
				}
				if (!empty($aOthers))
				{
					// Aggregate others
					$oConditionQuery = DBSearch::CloneWithAlias($oQuery, 'GTAB');
					$oExpression = null;
					$iOtherCount = 0;
					foreach ($aOthers as $aResult)
					{
						$iOtherCount += $aResult['_itop_count_'];
						$oExpr = new BinaryExpression(new FieldExpression($sGroupingTabAttCode,
                            $oConditionQuery->GetClassAlias()), '=', new UnaryExpression($aResult['grouped_by_1']));
						if (is_null($oExpression))
						{
							$oExpression = $oExpr;
						}
						else
						{
							$oExpression = new BinaryExpression($oExpression, 'OR', $oExpr);
						}
					}
					$oConditionQuery->AddConditionExpression($oExpression);

					$sLabel = Dict::S('Brick:Portal:Manage:Others');
					$aGroupingTabsValues['Others'] = array(
						'value' => 'Others',
						'label_html' => $sLabel,
						'label' => $sLabel,
						'condition' => $oConditionQuery,
						'count' => $iOtherCount,
					);
					unset($oConditionQuery);
				}
			}
		}
		if (empty($aDistinctResults))
		{
			$sLabel = Dict::S('Brick:Portal:Manage:All');
			$aGroupingTabsValues['undefined'] = array(
				'value' => 'All',
				'label_html' => $sLabel,
				'label' => $sLabel,
				'condition' => null,
				'count' => 0,
			);
		}

		return $aGroupingTabsValues;
	}

	/**
	 * @param Application $oApp
	 * @param ManageBrick $oBrick
	 * @param string $sClass
	 *
	 * @return DBSearch
	 * @throws \OQLException
	 */
	protected function GetScopedQuery(Application $oApp, ManageBrick $oBrick, $sClass)
	{
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());
		/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeHelper */
		$oScopeHelper = $oApp['scope_validator'];
		$oScopeHelper->AddScopeToQuery($oQuery, $sClass);

		return $oQuery;
	}
}
