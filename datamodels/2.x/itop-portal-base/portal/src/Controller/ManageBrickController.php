<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\Controller;

use AttributeDate;
use AttributeDateTime;
use AttributeDefinition;
use AttributeEnum;
use AttributeExternalKey;
use AttributeImage;
use AttributeSet;
use AttributeTagSet;
use BinaryExpression;
use BulkExport;
use CMDBSource;
use Combodo\iTop\Portal\Brick\AbstractBrick;
use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\ManageBrick;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Portal\Helper\BrickControllerHelper;
use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use Combodo\iTop\Portal\Helper\ScopeValidatorHelper;
use Combodo\iTop\Portal\Helper\SecurityHelper;
use Combodo\iTop\Portal\Routing\UrlGenerator;
use DBObject;
use DBObjectSet;
use DBSearch;
use DBUnionSearch;
use Dict;
use Exception;
use FieldExpression;
use iPopupMenuExtension;
use IssueLog;
use JSButtonItem;
use LogChannels;
use MetaModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UnaryExpression;
use URLButtonItem;
use utils;

/**
 * Class ManageBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 * @author  Eric Espie <eric.espie@combodo.com>
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author  Pierre Goiffon <pierre.goiffon@combodo.com>
 * @since   2.3.0
 */
class ManageBrickController extends BrickController
{
	/** @var string EXCEL_EXPORT_TEMPLATE_PATH */
	const EXCEL_EXPORT_TEMPLATE_PATH = 'itop-portal-base/portal/templates/bricks/manage/popup-export-excel.html.twig';

	/**
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeValidatorHelper
	 * @param \Combodo\iTop\Portal\Routing\UrlGenerator $oUrlGenerator
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulatorHelper
	 * @param \Combodo\iTop\Portal\Helper\SecurityHelper $oSecurityHelper
	 * @param \Combodo\iTop\Portal\Helper\BrickControllerHelper $oBrickControllerHelper
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected BrickCollection $oBrickCollection,
		protected ScopeValidatorHelper $oScopeValidatorHelper,
		protected UrlGenerator $oUrlGenerator,
		protected RequestManipulatorHelper $oRequestManipulatorHelper,
		protected SecurityHelper $oSecurityHelper,
		protected BrickControllerHelper $oBrickControllerHelper
	)
	{

	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 * @param string                                    $sGroupingTab
	 * @param string                                    $sDisplayMode
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function DisplayAction(Request $oRequest, $sBrickId, $sGroupingTab, $sDisplayMode = null)
	{
		/** @var \Combodo\iTop\Portal\Brick\ManageBrick $oBrick */
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		if (is_null($sDisplayMode)) {
			$sDisplayMode = $oBrick->GetDefaultDisplayMode();
		}

		$aData = $this->GetData($oRequest, $sBrickId, $sGroupingTab, $oBrick::AreDetailsNeededForDisplayMode($sDisplayMode));

		$aExportFields = $oBrick->GetExportFields();
		$aData = $aData + array(
				'sDisplayMode' => $sDisplayMode,
				'bCanExport' => !empty($aExportFields),
				'iDefaultListLength' => $oBrick->GetDefaultListLength(),
			);
		// Preparing response
		if ($oRequest->isXmlHttpRequest()) {
			$oResponse = new JsonResponse($aData);
		}
		else
		{
			$sLayoutTemplate = $oBrick::GetPageTemplateFromDisplayMode($sDisplayMode);
			$oResponse = $this->render($sLayoutTemplate, $aData);
		}

		return $oResponse;
	}

	/**
	 * Method for the brick's tile on home page
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 */
	public function TileAction(Request $oRequest, $sBrickId)
	{
		/** @var \Combodo\iTop\Portal\Brick\ManageBrick $oBrick */
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		try
		{
			$aData = $this->GetData($oRequest, $sBrickId, null);
		}
		catch (Exception $e)
		{
			// TODO Default values
			$aData = array();
		}

		return $this->render($oBrick->GetTileTemplatePath(), $aData);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 * @param string                                    $sGroupingTab
	 * @param string                                    $sGroupingArea
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function ExcelExportStartAction(Request $oRequest, $sBrickId, $sGroupingTab, $sGroupingArea)
	{
		/** @var \Combodo\iTop\Portal\Brick\ManageBrick $oBrick */
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());
		$sClass = $oQuery->GetClass();
		$aData = $this->GetData($oRequest, $sBrickId, $sGroupingTab, true);

		if (isset($aData['aQueries']) && count($aData['aQueries']) === 1)
		{
			$aQueries = $aData['aQueries'];
			reset($aQueries);
			$sKey = key($aQueries);
			$oSearch = $aData['aQueries'][$sKey];
		}
		else
		{
			$this->oScopeValidatorHelper->AddScopeToQuery($oQuery, $sClass);
			$aData = array();
			$this->ManageSearchValue($aData, $oQuery, $sClass);

			// Grouping tab
			if ($oBrick->HasGroupingTabs())
			{
				$aGroupingTabs = $oBrick->GetGroupingTabs();

				// If tabs are made of the distinct values of an attribute, we have a find them via a query
				if ($oBrick->IsGroupingTabsByDistinctValues())
				{
					$sGroupingTabAttCode = $aGroupingTabs['attribute'];
					$aGroupingTabsValues = $this->GroupByAttribute($oQuery, $sGroupingTabAttCode, $oBrick);
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
		$sFormat = 'xlsx';
		$oSearch->UpdateContextFromUser();
		$oExporter = BulkExport::FindExporter($sFormat, $oSearch);
		$oExporter->SetObjectList($oSearch);
		$oExporter->SetFormat($sFormat);
		$oExporter->SetChunkSize(EXPORTER_DEFAULT_CHUNK_SIZE);
		$oExporter->SetLocalizeOutput(true);
		$oExporter->SetFields($sFields);

		$aData = array(
			'oBrick' => $oBrick,
			'sBrickId' => $sBrickId,
			'sToken' => $oExporter->SaveState(),
            'sWikiUrl' => 'https://www.itophub.io/wiki/page?id='.utils::GetItopVersionWikiSyntax().'%3Auser%3Alists#excel_export',
		);

		return $this->render(static::EXCEL_EXPORT_TEMPLATE_PATH, $aData);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 * @param string                                    $sGroupingTab
	 * @param bool                                      $bNeedDetails
	 *
	 * @return array
	 *
	 * @throws \Combodo\iTop\Portal\Brick\BrickNotFoundException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function GetData(Request $oRequest, $sBrickId, $sGroupingTab, $bNeedDetails = false)
	{
		/** @var string $sPortalId */
		$sPortalId = $this->getParameter('combodo.portal.instance.id');
		/** @var \Combodo\iTop\Portal\Brick\ManageBrick $oBrick */
		$oBrick = $this->oBrickCollection->GetBrickById($sBrickId);

		$aData = array();
		$aGroupingTabsValues = array();
		$aGroupingAreasValues = array();
		$aQueries = array();
		$bHasScope = true;

		// Getting current data loading mode (First from router parameter, then query parameter, then default brick value)
		$sDataLoading = $this->oRequestManipulatorHelper->ReadParam('sDataLoading', $oBrick->GetDataLoading());

		// - Retrieving the grouping areas to display
		$sGroupingArea = $this->oRequestManipulatorHelper->ReadParam('sGroupingArea', '');
		if (!empty($sGroupingArea))
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
				$aGroupingTabsValues = $this->GroupByAttribute($oQuery, $sGroupingTabAttCode, $oBrick);
				foreach ($aGroupingTabsValues as $aResult)
				{
					$iCount += $aResult['count'];
				}
			}
			// Otherwise we create the tabs from the SQL expressions
			else
			{
				$aConditionQueryGrouping = array();
				foreach ($aGroupingTabs['groups'] as $aGroup)
				{
					$oDBSearch = DBSearch::FromOQL($aGroup['condition']);
					$oConditionQuery = $oQuery->Intersect($oDBSearch);
					// - Restricting query to scope
					array_push($aConditionQueryGrouping,$oDBSearch);
					$bHasScope = $this->oScopeValidatorHelper->AddScopeToQuery($oConditionQuery, $oConditionQuery->GetClass());
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
						'description' => array_key_exists('description',$aGroup) ? Dict::S($aGroup['description']) : null,
						'condition' => $oConditionQuery,
						'count' => $iGroupCount,
					);
				}
				try
				{
					$oConditionQuery = $oQuery->Intersect(new DBUnionSearch($aConditionQueryGrouping));
					$bHasScope = $this->oScopeValidatorHelper->AddScopeToQuery($oConditionQuery, $oConditionQuery->GetClass());
					if ($bHasScope)
					{
						// - Building ObjectSet
						$oConditionSet = new DBObjectSet($oConditionQuery);
						$iCount = $oConditionSet->Count();
					}
					else
					{
						$oConditionSet = null;
						$iCount = 0;
					}
				}
				catch (Exception $e){
					$oConditionSet = null;
					$iCount = -1;
				}
			}
		}
		else
		{
			$oConditionQuery = $this->GetScopedQuery($oBrick, $sClass);
			if (!is_null($oConditionQuery))
			{
				$oSet = new DBObjectSet($oConditionQuery);
				$iCount = $oSet->Count();
			}
		}

		// - Retrieving the current grouping tab to display if necessary and altering the query to do so
		if (empty($sGroupingTab))
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

		// Retrieve the current tab description to set the page subtitle (if it exists)
		$aData['sBrickSubtitle'] = !empty($aGroupingTabsValues[$sGroupingTab]['description']) ? $aGroupingTabsValues[$sGroupingTab]['description'] : null;

		// - Transforming search sort params to OQL format
		$aSortedParams = $this->oBrickControllerHelper->ExtractSortParams();

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
				$oDistinctQuery = $this->GetScopedQuery($oBrick, $sClass);
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
						'count' => $aDistinctResult['_itop_count_'],
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
					'count' => 0,
				);
			}

			//   - If specified or lazy loading, we truncate the $aGroupingAreasValues to keep only this one
			if (!empty($sGroupingArea))
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
				$bHasScope = $this->oScopeValidatorHelper->AddScopeToQuery($oAreaQuery, $aGroupingAreasValue['value']);
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
				$oCountSet->OptimizeColumnLoad(array($oQuery->GetClassAlias() => array()));
				$fThreshold = (float)MetaModel::GetModuleSetting($sPortalId,
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
					// - Adding search clause if necessary
					$this->ManageSearchValue($aData, $oQuery, $sKey, $aColumnsAttrs);
					
					// Setting query pagination if needed
					if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY)
					{
						// Retrieving parameters
						$iPageNumber = (int)$this->oRequestManipulatorHelper->ReadParam('iPageNumber', 1, FILTER_SANITIZE_NUMBER_INT);
						$iListLength = (int)$this->oRequestManipulatorHelper->ReadParam('iListLength', ManageBrick::DEFAULT_LIST_LENGTH,
							FILTER_SANITIZE_NUMBER_INT);

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

					// Setting specified column sort, setting default datamodel one otherwise
					if (!empty($aSortedParams))
					{
						$oSet->SetOrderBy($aSortedParams);
					}
					else
					{
						$oSet->SetOrderByClasses();
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
					// Note: $aColumnToLoad already contains array of aliases => attcodes
					$oSet->OptimizeColumnLoad($aColumnsToLoad);

					$this->oSecurityHelper->PreloadForCache($oSet->GetFilter(),
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
					$sCurrentObjectClass = get_class($oCurrentRow);
					$sCurrentObjectId = $oCurrentRow->GetKey();

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
							if (($oBrick->GetOpeningMode() === ManageBrick::ENUM_ACTION_EDIT) && $this->oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY,
									$sCurrentClass, $oCurrentRow->GetKey()))
							{
								$sActionType = ManageBrick::ENUM_ACTION_EDIT;
							}
							// - Otherwise, check if view is allowed
							elseif ($this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sCurrentClass,
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

						/** @var \AttributeDefinition $oAttDef */
						$oAttDef = MetaModel::GetAttributeDef($sCurrentClass, $sItemAttr);
						$sAttDefClass = get_class($oAttDef);
						if ($oAttDef->IsExternalKey())
						{
							/** @var \AttributeExternalKey $oAttDef */
							$sValue = $oCurrentRow->GetAsHTML($sItemAttr.'_friendlyname');
							$sSortValue = $oCurrentRow->Get($sItemAttr.'_friendlyname');

							// Adding a view action on the external keys
							if ($oCurrentRow->Get($sItemAttr) !== $oAttDef->GetNullValue())
							{
								// Checking if we can view the object
								if (($this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $oAttDef->GetTargetClass(),
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
						elseif ($oAttDef instanceof AttributeImage)
						{
							/** @var \ormDocument $oOrmDoc */
							$oOrmDoc = $oCurrentRow->Get($sItemAttr);
							if (is_object($oOrmDoc) && !$oOrmDoc->IsEmpty())
							{
								$sUrl = $this->oUrlGenerator->generate('p_object_document_display', [
									'sObjectClass' => get_class($oCurrentRow),
									'sObjectId' => $oCurrentRow->GetKey(),
									'sObjectField' => $sItemAttr,
									'cache' => 86400,
									's' => $oOrmDoc->GetSignature(),
								]);
							}
							else
							{
								$sUrl = $oAttDef->Get('default_image');
							}
							$sValue = '<img src="'.$sUrl.'" />';
							$sSortValue = null;
						}
						elseif ($oAttDef instanceof AttributeTagSet)
						{
							/** @var \ormTagSet $oSetValues */
							$oSetValues = $oCurrentRow->Get($sItemAttr);
							$aCodes = $oSetValues->GetTags();
							/** @var \AttributeTagSet $oAttDef */
							$sValue = $oAttDef->GenerateViewHtmlForValues($aCodes, '', false);
							$sSortValue = implode(' ', $aCodes);
						} elseif ($oAttDef instanceof AttributeSet) {
							$oAttDef->SetDisplayLink(false);
							$sValue = $oAttDef->GetAsHTML($oCurrentRow->Get($sItemAttr));
							$sSortValue = "".$oCurrentRow->Get($sItemAttr);
						} elseif ($oAttDef instanceof AttributeEnum) {
							$sValue = $oAttDef->GetAsPlainText($oCurrentRow->Get($sItemAttr));
							$sSortValue = $oCurrentRow->Get($sItemAttr);
						} else {
							$sValue = $oAttDef->GetAsHTML($oCurrentRow->Get($sItemAttr));
							$sSortValue = $oCurrentRow->Get($sItemAttr);
						}
						unset($oAttDef);

						// For simple fields, we get the raw (stored) value as well
						$bExcludeRawValue = false;
						foreach (ApplicationHelper::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude)
						{
							if (is_a($sAttDefClass, $sAttDefClassToExclude, true))
							{
								$bExcludeRawValue = true;
								break;
							}
						}
						$attValueRaw = ($bExcludeRawValue === false) ? $oCurrentRow->Get($sItemAttr) : null;

						$aItemAttrs[$sItemAttr] = array(
							'object_class' => $sCurrentObjectClass,
							'object_id' => $sCurrentObjectId,
							'attribute_code' => $sItemAttr,
							'attribute_type' => $sAttDefClass,
							'value_raw' => $attValueRaw,
							'value_html' => $sValue,
							'sort_value' => $sSortValue,
							'actions' => $aActions,
						);
					}

					// ... Checking menu extensions
					$aItemButtons = array();
					/** @var iPopupMenuExtension $oExtensionInstance */
					foreach (MetaModel::EnumPlugins('iPopupMenuExtension') as $oExtensionInstance)
					{
						foreach ($oExtensionInstance->EnumItems(iPopupMenuExtension::PORTAL_OBJLISTITEM_ACTIONS, array(
							'portal_id' => $sPortalId,
							'object' => $oCurrentRow,
						)) as $oMenuItem)
						{
							if (is_object($oMenuItem))
							{
								if ($oMenuItem instanceof JSButtonItem)
								{
									$aItemButtons[] = $oMenuItem->GetMenuItem() + array(
											'js_files' => $oMenuItem->GetLinkedScripts(),
											'type' => 'button',
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
					'aColumnsDefinition' => $aColumnsDefinition,
				);

				IssueLog::Debug('Portal ManageBrick query', LogChannels::PORTAL, array(
					'sPortalId' => $sPortalId,
					'sBrickId' => $sBrickId,
					'sGroupingTab' => $sGroupingTab,
					'oql' => $oSet->GetFilter()->ToOQL(),
					'aGroupingTabs' => $aGroupingTabs,
				));
			}
		} else {
			$aGroupingAreasData = array();
			$sGroupingArea = null;
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest()) {
			$aData = $aData + array(
					'data' => $aGroupingAreasData[$sGroupingArea]['aItems'],
				);
		} else {
			$aDisplayValues = array();
			$aUrls = array();
			$aColumns = array();
			$aNames = array();
			if ($bHasScope) {
				foreach ($aGroupingTabsValues as $aValues) {
					$aDisplayValues[] = array(
						'value' => $aValues['count'],
						'label' => $aValues['label'],
						'label_html' => $aValues['label_html'],
					);
					$aUrls[] = $this->oUrlGenerator->generate('p_manage_brick_display_as', array(
						'sBrickId' => $sBrickId,
						'sDisplayMode' => 'list',
						'sGroupingTab' => $aValues['value'],
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
	 * @param array     $aData
	 * @param \DBSearch $oQuery
	 * @param string    $sClass
	 * @param array     $aColumnsAttrs
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function ManageSearchValue(&$aData, DBSearch &$oQuery, $sClass, $aColumnsAttrs = array())
	{
		// Getting search value
		$sRawSearchValue = trim($this->oRequestManipulatorHelper->ReadParam('sSearchValue', ''));
		$sSearchValue = html_entity_decode($sRawSearchValue);

		// - Adding search clause if necessary
		// Note : This is a very naive search at the moment
		if (strlen($sSearchValue) > 0) {
			// Putting only valid attributes as one can define attributes of leaf classes in the brick definition (<fields>), but at this stage we are working on the abstract class.
			// Note: This won't fix everything as the search will not be looking in all fields.
			$aSearchListItems = [];
			foreach ($aColumnsAttrs as $sColumnAttr) {
				// Skip invalid attCodes
				if (!MetaModel::IsValidAttCode($sClass, $sColumnAttr)) {
					continue;
				}

				// For external key, force search on the friendlyname instead of the ID.
				// This should be addressed more globally with the bigger issue, see N°1970
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sColumnAttr);
				if ($oAttDef instanceof AttributeExternalKey) {
					$sColumnAttr .= '_friendlyname';
				}

				$aSearchListItems[] = $sColumnAttr;
			}

			if (preg_match('/^"(.*)"$/', $sSearchValue, $aMatches)) {
				// The text is surrounded by double-quotes, remove the quotes and treat it as one single expression
				$aSearchNeedles = [$aMatches[1]];
			} else {
				// Split the text on the blanks and treat this as a search for <word1> AND <word2> AND <word3>
				$aExplodedSearchNeedles = explode(' ', $sSearchValue);
				$aSearchNeedles = [];
				foreach ($aExplodedSearchNeedles as $sValue) {
					if (strlen($sValue) > 0) {
						$aSearchNeedles[] = $sValue;
					}
				}
			}
			foreach ($aSearchNeedles as $sSearchWord) {
				$oQuery->AddCondition_FullTextOnAttributes($aSearchListItems, $sSearchWord);
			}
		}

		$aData['sSearchValue'] = $sRawSearchValue;
	}

	/**
	 * Get the groups using a given attribute code.
	 * If a limit is given, the remaining groups are aggregated (group by result and search request).
	 *
	 * @param \DBSearch                              $oQuery              Initial query
	 * @param string                                 $sGroupingTabAttCode Attribute code to group by
	 * @param \Combodo\iTop\Portal\Brick\ManageBrick $oBrick
	 *
	 * @return array of results from the group by request and the corresponding search.
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	protected function GroupByAttribute(DBSearch $oQuery, $sGroupingTabAttCode, ManageBrick $oBrick) {

		$aGroupingTabsValues = array();
		$aDistinctResults = array();
		$oDistinctQuery = DBSearch::FromOQL($oBrick->GetOql());
		$bHasScope = $this->oScopeValidatorHelper->AddScopeToQuery($oDistinctQuery, $oDistinctQuery->GetClass());
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
						'label' => strip_tags(html_entity_decode($sHtmlLabel, ENT_QUOTES, 'UTF-8')),
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
	 * @param \Combodo\iTop\Portal\Brick\ManageBrick $oBrick
	 * @param string                                 $sClass
	 *
	 * @return \DBSearch
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	protected function GetScopedQuery(ManageBrick $oBrick, $sClass)
	{
		$oQuery = DBSearch::FromOQL($oBrick->GetOql());
		$this->oScopeValidatorHelper->AddScopeToQuery($oQuery, $sClass);

		return $oQuery;
	}
}
