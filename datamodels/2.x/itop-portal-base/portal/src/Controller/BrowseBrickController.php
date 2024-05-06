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

use AttributeExternalKey;
use AttributeLinkedSetIndirect;
use BinaryExpression;
use Combodo\iTop\Portal\Brick\AbstractBrick;
use Combodo\iTop\Portal\Brick\BrickCollection;
use Combodo\iTop\Portal\Brick\BrowseBrick;
use Combodo\iTop\Portal\Helper\BrickControllerHelper;
use Combodo\iTop\Portal\Helper\BrowseBrickHelper;
use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use FieldExpression;
use IssueLog;
use LogChannels;
use MetaModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use VariableExpression;

/**
 * Class BrowseBrickController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
class BrowseBrickController extends BrickController
{

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\Portal\Helper\BrowseBrickHelper $oBrowseBrickHelper
	 * @param \Combodo\iTop\Portal\Helper\RequestManipulatorHelper $oRequestManipulatorHelper
	 * @param \Combodo\iTop\Portal\Helper\BrickControllerHelper $oBrickControllerHelper
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection $oBrickCollection
	 *
	 * @since 3.2.0 N°6933
	 */
	public function __construct(
		protected BrowseBrickHelper $oBrowseBrickHelper,
		protected RequestManipulatorHelper $oRequestManipulatorHelper,
		protected BrickControllerHelper $oBrickControllerHelper,
		protected BrickCollection $oBrickCollection
	)
	{

	}


	/**
	 * @param \Symfony\Component\HttpFoundation\Request $oRequest
	 * @param string                                    $sBrickId
	 * @param string                                    $sBrowseMode
	 * @param string                                    $sDataLoading
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayAction(Request $oRequest, $sBrickId, $sBrowseMode = null, $sDataLoading = null)
	{
		$sPortalId = $this->getParameter('combodo.portal.instance.id');

		/** @var \Combodo\iTop\Portal\Brick\BrowseBrick $oBrick */
		$oBrick = $this->oBrickCollection->getBrickById($sBrickId);

		// Getting available browse modes
		$aBrowseModes = $oBrick->GetAvailablesBrowseModes();
		$aBrowseButtons = array_keys($aBrowseModes);
		// Getting current browse mode (First from router parameter, then default brick value)
		$sBrowseMode = (!empty($sBrowseMode)) ? $sBrowseMode : $oBrick->GetDefaultBrowseMode();
		// Getting current dataloading mode (First from router parameter, then query parameter, then default brick value)
		$sDataLoading = ($sDataLoading !== null) ? $sDataLoading : $this->oRequestManipulatorHelper->ReadParam('sDataLoading',
			$oBrick->GetDataLoading());
		// Getting search value
		$sRawSearchValue = $this->oRequestManipulatorHelper->ReadParam('sSearchValue', '');
		$sSearchValue = html_entity_decode($sRawSearchValue);
		if (strlen($sSearchValue) > 0)
		{
			$sDataLoading = AbstractBrick::ENUM_DATA_LOADING_LAZY;
		}

		$aData = array();
		$aLevelsProperties = array();
		$aLevelsClasses = array();
		$this->oBrowseBrickHelper->TreeToFlatLevelsProperties($oBrick->GetLevels(), $aLevelsProperties);

		// Consistency checks
		if (!in_array($sBrowseMode, array_keys($aBrowseModes)))
		{
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
				'Browse brick "'.$sBrickId.'" : Unknown browse mode "'.$sBrowseMode.'", availables are '.implode(' / ',
					array_keys($aBrowseModes)));
		}
		if (empty($aLevelsProperties))
		{
			throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Browse brick "'.$sBrickId.'" : No levels to display.');
		}

		// Building DBObjectSearch
		$oQuery = null;
		// ... In this case only we have to build a specific query for the current level only
		if (in_array($sBrowseMode, array(
				BrowseBrick::ENUM_BROWSE_MODE_TREE,
				BrowseBrick::ENUM_BROWSE_MODE_MOSAIC,
			)) && ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY))
		{
			// Will be handled later in the pagination part
		}
		// .. Otherwise
		else
		{
			// We iterate (in reverse mode /!\) over the levels to build the whole query, starting from the bottom
			$aLevelsPropertiesKeys = array_keys($aLevelsProperties);
			$iLoopMax = count($aLevelsPropertiesKeys) - 1;
			$oFullBinExpr = null;
			for ($i = $iLoopMax; $i >= 0; $i--)
			{
				// Retrieving class alias for all depth
				array_unshift($aLevelsClasses, $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->GetClassAlias());

				// Joining queries from bottom-up
				if ($i < $iLoopMax)
				{
					$aRealiasingMap = array();
					$oParentAtt = MetaModel::GetAttributeDef($aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['search']->GetClass(), $aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['parent_att']);
					// If we work on a n:n link
					if($oParentAtt instanceof AttributeLinkedSetIndirect)
					{
						// Create a DBSearch from Link class
						$oSubSearch = new DBObjectSearch($oParentAtt->GetLinkedClass());
						// Join it to the bottom query
						$oSubSearch = $oSubSearch->Join($aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['search'],
							DBSearch::JOIN_POINTING_TO, $oParentAtt->GetExtKeyToMe(), TREE_OPERATOR_EQUALS, $aRealiasingMap);
						// Join our Link class + bottom query to the up query
						$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search'] = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search'] ->Join($oSubSearch, DBSearch::JOIN_REFERENCED_BY,
							$oParentAtt->GetExtKeyToRemote(), TREE_OPERATOR_EQUALS, $aRealiasingMap);
					}
					else
					{
						$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search'] = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->Join($aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['search'],
							DBSearch::JOIN_REFERENCED_BY, $aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['parent_att'],
							TREE_OPERATOR_EQUALS, $aRealiasingMap);
					}
					foreach ($aLevelsPropertiesKeys as $sLevelAlias)
					{
						if (array_key_exists($sLevelAlias, $aRealiasingMap))
						{
							/** @since 2.7.2 */
							foreach ($aRealiasingMap[$sLevelAlias] as $sAliasToChange)
							{
								$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->RenameAlias($sAliasToChange, $sLevelAlias);
							}
						}
					}
				}

				// Adding search clause
				// Note : For know the search is naive and looks only for the exact match. It doesn't search for words separately
				if (strlen($sSearchValue) > 0)
				{
					// - Cleaning the search value by exploding and trimming spaces
					$aExplodedSearchValues = explode(' ', $sSearchValue);
					$aSearchValues = [];
					foreach ($aExplodedSearchValues as $sValue) {
						if (strlen($sValue) > 0) {
							$aSearchValues[] = $sValue;
						}
					}

					// - Retrieving fields to search
					$aSearchFields = array($aLevelsProperties[$aLevelsPropertiesKeys[$i]]['name_att']);
					if (!empty($aLevelsProperties[$aLevelsPropertiesKeys[$i]]['fields']))
					{
						$sTmpFieldClass = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->GetClass();
						foreach ($aLevelsProperties[$aLevelsPropertiesKeys[$i]]['fields'] as $aTmpField)
						{
							$sTmpFieldAttCode = $aTmpField['code'];

							// Skip invalid attcodes
							if(!MetaModel::IsValidAttCode($sTmpFieldClass, $sTmpFieldAttCode))
							{
								continue;
							}

							// For external key, force search on the friendlyname instead of the ID.
							// This should be addressed more globally with the bigger issue, see N°1970
							$oTmpFieldAttDef = MetaModel::GetAttributeDef($sTmpFieldClass, $sTmpFieldAttCode);
							if($oTmpFieldAttDef instanceof AttributeExternalKey)
							{
								$sTmpFieldAttCode .= '_friendlyname';
							}

							$aSearchFields[] = $sTmpFieldAttCode;
						}
					}
					// - Building query for the search values parts
					$oLevelBinExpr = null;
					$iFieldLoopMax = count($aSearchFields) - 1;
					$iSearchLoopMax = count($aSearchValues) - 1;
					for ($j = 0; $j <= $iFieldLoopMax; $j++)
					{
						$sTmpFieldAttCode = $aSearchFields[$j];
						$oFieldBinExpr = null;
						//$oFieldBinExpr = new BinaryExpression(new FieldExpression($aSearchFields[$j], $aLevelsPropertiesKeys[$i]), )

						for ($k = 0; $k <= $iSearchLoopMax; $k++)
						{
							$oSearchBinExpr = new BinaryExpression(new FieldExpression($sTmpFieldAttCode, $aLevelsPropertiesKeys[$i]),
								'LIKE', new VariableExpression('search_value_'.$k));
							if ($k === 0)
							{
								$oFieldBinExpr = $oSearchBinExpr;
							}
							else
							{
								$oFieldBinExpr = new BinaryExpression($oFieldBinExpr, 'AND', $oSearchBinExpr);
							}
						}

						if ($j === 0)
						{
							$oLevelBinExpr = $oFieldBinExpr;
						}
						else
						{
							$oLevelBinExpr = new BinaryExpression($oLevelBinExpr, 'OR', $oFieldBinExpr);
						}
					}

					// - Building query for the level
					if ($i === $iLoopMax)
					{
						$oFullBinExpr = $oLevelBinExpr;
					}
					else
					{
						$oFullBinExpr = new BinaryExpression($oFullBinExpr, 'OR', $oLevelBinExpr);
					}

					// - Adding it to the query when complete
					if ($i === 0)
					{
						$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->AddConditionExpression($oFullBinExpr);
					}
				}

				// Setting selected classes and binding parameters
				if ($i === 0)
				{
					$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->SetSelectedClasses($aLevelsClasses);

					if (strlen($sSearchValue) > 0)
					{
						// Note : This could be way more simpler if we had a SetInternalParam($sParam, $value) verb
						$aQueryParams = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->GetInternalParams();
						// Note : $iSearchloopMax was initialized on the previous loop
						for ($j = 0; $j <= $iSearchLoopMax; $j++) {
							$aQueryParams['search_value_'.$j] = '%'.$aSearchValues[$j].'%';
						}
						$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->SetInternalParams($aQueryParams);
					}
				}
			}
			$oQuery = $aLevelsProperties[$aLevelsPropertiesKeys[0]]['search'];

			// Testing appropriate data loading mode if we are in auto
			if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_AUTO) {
				// - Check how many records there is.
				// - Update $sDataLoading with its new value regarding the number of record and the threshold
				$oCountSet = new DBObjectSet($oQuery);
				$fThreshold = (float)MetaModel::GetModuleSetting($sPortalId,
					'lazy_loading_threshold');
				$sDataLoading = ($oCountSet->Count() > $fThreshold) ? AbstractBrick::ENUM_DATA_LOADING_LAZY : AbstractBrick::ENUM_DATA_LOADING_FULL;
				unset($oCountSet);
			}
		}

		// Setting query pagination if needed
		if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY)
		{
			switch ($sBrowseMode)
			{
				case BrowseBrick::ENUM_BROWSE_MODE_LIST:
					// Retrieving parameters
					$iPageNumber = (int)$this->oRequestManipulatorHelper->ReadParam('iPageNumber', 1, FILTER_SANITIZE_NUMBER_INT);
					$iListLength = (int)$this->oRequestManipulatorHelper->ReadParam('iListLength', BrowseBrick::DEFAULT_LIST_LENGTH,
						FILTER_SANITIZE_NUMBER_INT);

					// Getting total records number
					$oCountSet = new DBObjectSet($oQuery);
					$aData['recordsTotal'] = $oCountSet->Count();
					$aData['recordsFiltered'] = $oCountSet->Count();
					unset($oCountSet);

					$oSet = new DBObjectSet($oQuery);
					$oSet->SetLimit($iListLength, $iListLength * ($iPageNumber - 1));

					break;
				case BrowseBrick::ENUM_BROWSE_MODE_TREE:
				case BrowseBrick::ENUM_BROWSE_MODE_MOSAIC:
					// Retrieving parameters
					$sLevelAlias = $this->oRequestManipulatorHelper->ReadParam('sLevelAlias', '');
					$sNodeId = $this->oRequestManipulatorHelper->ReadParam('sNodeId', '');

					// If no values for those parameters, we might be loading page in lazy mode for the first time, therefore the URL doesn't have those information.
					if (empty($sLevelAlias))
					{
						reset($aLevelsProperties);
						$oQuery = $aLevelsProperties[key($aLevelsProperties)]['search'];
						if (!empty($sNodeId))
						{
							$oQuery->AddCondition('id', $sNodeId);
						}
					}
					// Else we need to find the OQL for that particular level
					else
					{
						$bFoundLevel = false;
						foreach ($aLevelsProperties as $aLevelProperties)
						{
							if ($aLevelProperties['alias'] === $sLevelAlias)
							{
								if (isset($aLevelProperties['levels']) && !empty($aLevelProperties['levels']) && isset($aLevelsProperties[$aLevelProperties['levels'][0]]))
								{
									$oQuery = $aLevelsProperties[$aLevelProperties['levels'][0]]['search'];
									if (!empty($sNodeId))
									{
										$sParentAttCode = $aLevelsProperties[$aLevelProperties['levels'][0]]['parent_att'];
										$oParentAtt = MetaModel::GetAttributeDef($oQuery->GetClass(), $sParentAttCode);
										if($oParentAtt instanceof AttributeLinkedSetIndirect)
										{
											$oQuery->AddConditionAdvanced($sParentAttCode.'->'.$oParentAtt->GetExtKeyToRemote(), $sNodeId);
										}
										else
										{
											$oQuery->AddCondition($sParentAttCode, $sNodeId);
										}
									}
									$bFoundLevel = true;
									break;
								}
							}
						}

						if (!$bFoundLevel)
						{
							throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR,
								'Browse brick "'.$sBrickId.'" : Level alias "'.$sLevelAlias.'" is not defined for that brick.');
						}
					}

					$oSet = new DBObjectSet($oQuery);
					break;

				default:
					// We should never be there. If there is an other browse mode for that brick :
					// - If it's from a custom brick extension, it should be handle by the extension router/controller
					// - If it's from a base brick, it should be handle in a case above this one
					// - If none of the previous statements was done, this fail safe will load all data as it's not able to know how to handle the pagination
					$oSet = new DBObjectSet($oQuery);
					break;
			}
		}
		else
		{
			$oSet = new DBObjectSet($oQuery);
		}

		// Optimizing the ObjectSet to retrieve only necessary columns
		$aColumnAttrs = array();
		foreach ($oSet->GetFilter()->GetSelectedClasses() as $sTmpClassAlias => $sTmpClassName)
		{
			if (isset($aLevelsProperties[$sTmpClassAlias]))
			{
				$aTmpLevelProperties = $aLevelsProperties[$sTmpClassAlias];
				// Mandatory main attribute
				$aTmpColumnAttrs = array($aTmpLevelProperties['name_att']);
				// Optional attributes, only if in list mode
				if ($sBrowseMode === BrowseBrick::ENUM_BROWSE_MODE_LIST)
				{
					foreach ($aTmpLevelProperties['fields'] as $aTmpField)
					{
						$aTmpColumnAttrs[] = $aTmpField['code'];
					}
				}
				// Optional attributes
				foreach (BrowseBrickHelper::OPTIONAL_ATTRIBUTES as $sOptionalAttribute)
				{
					if ($aTmpLevelProperties[$sOptionalAttribute] !== null)
					{
						$aTmpColumnAttrs[] = $aTmpLevelProperties[$sOptionalAttribute];
					}
				}

				$aColumnAttrs[$sTmpClassAlias] = $aTmpColumnAttrs;
			}
		}
		// Note: $aColumnAttrs already contains array of aliases => attcodes
		$oSet->OptimizeColumnLoad($aColumnAttrs);

		// Setting specified column sort, setting default datamodel one otherwise
		$aSortedParams = $this->oBrickControllerHelper->ExtractSortParams();
		if (!empty($aSortedParams))
		{
			$oSet->SetOrderBy($aSortedParams);
		}
		else
		{
			$oSet->SetOrderByClasses();
		}
		// Retrieving results and organizing them for templating
		$aItems = array();
		while ($aCurrentRow = $oSet->FetchAssoc())
		{
			switch ($sBrowseMode)
			{
				case BrowseBrick::ENUM_BROWSE_MODE_TREE:
				case BrowseBrick::ENUM_BROWSE_MODE_MOSAIC:
					$this->oBrowseBrickHelper->AddToTreeItems($aItems, $aCurrentRow, $aLevelsProperties, null);
					break;

				case BrowseBrick::ENUM_BROWSE_MODE_LIST:
				default:
					$aItems[] = $this->oBrowseBrickHelper->AddToFlatItems($aCurrentRow, $aLevelsProperties);
					break;
			}
		}

		IssueLog::Debug('Portal BrowseBrick query', LogChannels::PORTAL, array(
			'sPortalId' => $sPortalId,
			'sBrickId' => $sBrickId,
			'oql' => $oSet->GetFilter()->ToOQL(),
		));


		// Preparing response
		if ($oRequest->isXmlHttpRequest()) {
			$aData = $aData + array(
					'data' => $aItems,
					'levelsProperties' => $aLevelsProperties,
				);
			$oResponse = new JsonResponse($aData);
		} else {
			$aData = $aData + array(
					'oBrick' => $oBrick,
					'sBrickId' => $sBrickId,
					'sBrowseMode' => $sBrowseMode,
					'aBrowseButtons' => $aBrowseButtons,
					'sSearchValue' => $sRawSearchValue,
					'sDataLoading' => $sDataLoading,
					'aItems' => json_encode($aItems),
					'iItemsCount' => count($aItems),
					'aLevelsProperties' => json_encode($aLevelsProperties),
					'iDefaultLengthList' => $oBrick->GetDefaultListLength(),
				);

			// Note : To extend this brick's template, depending on what you want to do :
			// a) Modify the whole template :
			//	 - Create a template and specify it in the brick configuration
			// b) Add a new browse mode :
			//	 - Create a template for that browse mode,
			//	 - Add the mode to those available in the brick configuration,
			//	 - Create a router and add a route for the new browse mode
			if ($oBrick->GetPageTemplatePath() !== null)
			{
				$sTemplatePath = $oBrick->GetPageTemplatePath();
			}
			else
			{
				$sTemplatePath = $aBrowseModes[$sBrowseMode]['template'];
			}
			$oResponse = $this->render($sTemplatePath, $aData);
		}

		return $oResponse;
	}
}
