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
use \Exception;
use \UserRights;
use \Dict;
use \MetaModel;
use \DBSearch;
use \DBObjectSearch;
use \DBObjectSet;
use \DBObject;
use \BinaryExpression;
use \FieldExpression;
use \VariableExpression;
use \Combodo\iTop\Portal\Helper\ApplicationHelper;
use \Combodo\iTop\Portal\Helper\SecurityHelper;
use \Combodo\iTop\Portal\Helper\ContextManipulatorHelper;
use \Combodo\iTop\Portal\Brick\AbstractBrick;
use \Combodo\iTop\Portal\Brick\BrowseBrick;

class BrowseBrickController extends BrickController
{
	const LEVEL_SEPARATOR = '-';
    public static $aOptionalAttributes = array('tooltip_att');

	public function DisplayAction(Request $oRequest, Application $oApp, $sBrickId, $sBrowseMode = null, $sDataLoading = null)
	{
		$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $sBrickId);

		// Getting availables browse modes
		$aBrowseModes = $oBrick->GetAvailablesBrowseModes();
		$aBrowseButtons = array_keys($aBrowseModes);
		// Getting current browse mode (First from router pamater, then default brick value)
		$sBrowseMode = (!empty($sBrowseMode)) ? $sBrowseMode : $oBrick->GetDefaultBrowseMode();
		// Getting current dataloading mode (First from router parameter, then query parameter, then default brick value)
		$sDataLoading = ($sDataLoading !== null) ? $sDataLoading : ( ($oRequest->query->get('sDataLoading') !== null) ? $oRequest->query->get('sDataLoading') : $oBrick->GetDataLoading() );
		// Getting search value
		$sSearchValue = $oRequest->get('sSearchValue', null);
		if ($sSearchValue !== null)
		{
			$sDataLoading = AbstractBrick::ENUM_DATA_LOADING_LAZY;
		}

		$aData = array();
		$aLevelsProperties = array();
		$aLevelsClasses = array();
		static::TreeToFlatLevelsProperties($oApp, $oBrick->GetLevels(), $aLevelsProperties);

		// Concistency checks
		if (!in_array($sBrowseMode, array_keys($aBrowseModes)))
		{
			$oApp->abort(500, 'Browse brick "' . $sBrickId . '" : Unknown browse mode "' . $sBrowseMode . '", availables are ' . implode(' / ', array_keys($aBrowseModes)));
		}
		if (empty($aLevelsProperties))
		{
			$oApp->abort(500, 'Browse brick "' . $sBrickId . '" : No levels to display.');
		}

		// Building DBobjectSearch
		$oQuery = null;
		// ... In this case only we have to build a specific query for the current level only
		if (($sBrowseMode === BrowseBrick::ENUM_BROWSE_MODE_TREE) && ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_LAZY))
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
					$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search'] = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->Join($aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['search'], DBSearch::JOIN_REFERENCED_BY, $aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['parent_att'], TREE_OPERATOR_EQUALS, $aRealiasingMap);
					foreach ($aLevelsPropertiesKeys as $sLevelAlias)
					{
						if (array_key_exists($sLevelAlias, $aRealiasingMap))
						{
							$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->RenameAlias($aRealiasingMap[$sLevelAlias], $sLevelAlias);
						}
					}
				}

				// Adding search clause
				// Note : For know the search is naive and looks only for the exact match. It doesn't search for words separately
				if ($sSearchValue !== null)
				{
					// - Cleaning the search value by exploding and trimming spaces
					$aSearchValues = explode(' ', $sSearchValue);
					array_walk($aSearchValues, function(&$sSearchValue, $sKey)
					{
						trim($sSearchValue);
					});

					// - Building query for the search values parts
					$oLevelBinExpr = null;
					$iSearchLoopMax = count($aSearchValues) - 1;
					for ($j = 0; $j <= $iSearchLoopMax; $j++)
					{
						$oSearchBinExpr = new BinaryExpression(new FieldExpression($aLevelsProperties[$aLevelsPropertiesKeys[$i]]['name_att'], $aLevelsPropertiesKeys[$i]), 'LIKE', new VariableExpression('search_value_' . $j));
						if ($j === 0)
						{
							$oLevelBinExpr = $oSearchBinExpr;
						}
						else
						{
							$oLevelBinExpr = new BinaryExpression($oLevelBinExpr, 'AND', $oSearchBinExpr);
						}

						if ($j === $iSearchLoopMax)
						{

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

					if ($sSearchValue !== null)
					{
						// Note : This could be way more simpler if we had a SetInternalParam($sParam, $value) verb
						$aQueryParams = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->GetInternalParams();
						// Note : $iSearchloopMax was initialized on the previous loop
						for ($j = 0; $j <= $iSearchLoopMax; $j++)
						{
							$aQueryParams['search_value_' . $j] = '%' . $aSearchValues[$j] . '%';
						}
						$aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->SetInternalParams($aQueryParams);
					}
				}
			}
			$oQuery = $aLevelsProperties[$aLevelsPropertiesKeys[0]]['search'];

			// Testing appropriate data loading mode if we are in auto
			if ($sDataLoading === AbstractBrick::ENUM_DATA_LOADING_AUTO)
			{
				// - Check how many records there is.
				// - Update $sDataLoading with its new value regarding the number of record and the threshold
				$oCountSet = new DBObjectSet($oQuery);
				$fThreshold = (float) MetaModel::GetModuleSetting($oApp['combodo.portal.instance.id'], 'lazy_loading_threshold');
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
					$iPageNumber = (int) $oRequest->get('iPageNumber', 1);
					$iCountPerPage = (int) $oRequest->get('iCountPerPage', BrowseBrick::DEFAULT_COUNT_PER_PAGE_LIST);

					// Getting total records number
					$oCountSet = new DBObjectSet($oQuery);
					$aData['recordsTotal'] = $oCountSet->Count();
					$aData['recordsFiltered'] = $oCountSet->Count();
					unset($oCountSet);

					$oSet = new DBObjectSet($oQuery);
					$oSet->SetLimit($iCountPerPage, $iCountPerPage * ($iPageNumber - 1));

					break;
				case BrowseBrick::ENUM_BROWSE_MODE_TREE:
					// Retrieving parameters
					$sLevelAlias = $oRequest->get('sLevelAlias');
					$sNodeId = $oRequest->get('sNodeId');

					// If no values for those parameters, we might be loading page in lazy mode for the first time, therefore the URL doesn't have those informations.
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
										$oQuery->AddCondition($aLevelsProperties[$aLevelProperties['levels'][0]]['parent_att'], $sNodeId);
									}
									$bFoundLevel = true;
									break;
								}
							}
						}

						if (!$bFoundLevel)
						{
							$oApp->abort(500, 'Browse brick "' . $sBrickId . '" : Level alias "' . $sLevelAlias . '" is not defined for that brick.');
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
				// Optionnal attributes, only if in list mode
				if ($sBrowseMode === BrowseBrick::ENUM_BROWSE_MODE_LIST)
				{
					foreach ($aTmpLevelProperties['fields'] as $aTmpField)
					{
						$aTmpColumnAttrs[] = $aTmpField['code'];
					}
				}
                // Optional attributes
                foreach(static::$aOptionalAttributes as $sOptionalAttribute)
                {
                    if($aTmpLevelProperties[$sOptionalAttribute] !== null)
                    {
                        $aTmpColumnAttrs[] = $aTmpLevelProperties[$sOptionalAttribute];
                    }
                }

                $aColumnAttrs[$sTmpClassAlias] = $aTmpColumnAttrs;
			}
		}
		$oSet->OptimizeColumnLoad($aColumnAttrs);

		// Sorting objects through defined order (in DM)
		$oSet->SetOrderByClasses();

		// Retrieving results and organizing them for templating
		$aItems = array();
		while ($aCurrentRow = $oSet->FetchAssoc())
		{
			switch ($sBrowseMode)
			{
				case BrowseBrick::ENUM_BROWSE_MODE_TREE:
					static::AddToTreeItems($aItems, $aCurrentRow, $aLevelsProperties);
					break;

				case BrowseBrick::ENUM_BROWSE_MODE_LIST:
				default:
					$aItems[] = static::AddToFlatItems($aCurrentRow, $aLevelsProperties);
					break;
			}
		}

		// Preparing response
		if ($oRequest->isXmlHttpRequest())
		{
			$aData = $aData + array(
				'data' => $aItems,
				'levelsProperties' => $aLevelsProperties
			);
			$oResponse = $oApp->json($aData);
		}
		else
		{
			$aData = $aData + array(
				'oBrick' => $oBrick,
				'sBrickId' => $sBrickId,
				'sBrowseMode' => $sBrowseMode,
				'aBrowseButtons' => $aBrowseButtons,
				'sSearchValue' => $sSearchValue,
				'sDataLoading' => $sDataLoading,
				'aItems' => json_encode($aItems),
				'iItemsCount' => count($aItems),
				'aLevelsProperties' => json_encode($aLevelsProperties)
			);

			// Note : To extend this brick's template, depending on what you want to do :
			// a) Modify the whole template :
			//	 - Create a template and specify it in the brick configuration
			// b) Add a new browse mode :
			//	 - Create a template for that browse mode,
			//	 - Add the mode to those availables in the brick configuration,
			//	 - Create a router and add a route for the new browse mode
			if ($oBrick->GetPageTemplatePath() !== null)
			{
				$sTemplatePath = $oBrick->GetPageTemplatePath();
			}
			else
			{
				$sTemplatePath = $aBrowseModes[$sBrowseMode]['template'];
			}
			$oResponse = $oApp['twig']->render($sTemplatePath, $aData);
		}

		return $oResponse;
	}

	/**
	 * Flattens the $aLevels into $aLevelsProperties in order to be able to build an OQL query from multiple single queries related to each others.
	 * As of now it only keeps search / parent_att / name_att properties.
	 *
	 * Note : This is not in the BrowseBrick class because the classes should not rely on DBObjectSearch.
	 *
	 * @param Silex\Application $oApp
	 * @param array $aLevels Levels from a BrowseBrick class
	 * @param array $aLevelsProperties Reference to an array that will contain the flattened levels
	 * @param string $sLevelAliasPrefix String that will be prefixed to the level ID as an unique path identifier
	 */
	public static function TreeToFlatLevelsProperties(Application $oApp, array $aLevels, array &$aLevelsProperties, $sLevelAliasPrefix = 'L')
	{
		foreach ($aLevels as $aLevel)
		{
			$sCurrentLevelAlias = $sLevelAliasPrefix . static::LEVEL_SEPARATOR . $aLevel['id'];
			$oSearch = DBSearch::CloneWithAlias(DBSearch::FromOQL($aLevel['oql']), $sCurrentLevelAlias);

			// Restricting to the allowed scope
			$oScopeSearch = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $oSearch->GetClass(), UR_ACTION_READ);
			$oSearch = ($oScopeSearch !== null) ? $oSearch->Intersect($oScopeSearch) : null;
			// - Allowing all data if necessary
			if ($oScopeSearch->IsAllDataAllowed())
			{
				$oSearch->AllowAllData();
			}

			if ($oSearch !== null)
			{
				$aLevelsProperties[$sCurrentLevelAlias] = array(
					'alias' => $sCurrentLevelAlias,
					'title' => ($aLevel['title'] !== null) ? Dict::S($aLevel['title']) : MetaModel::GetName($oSearch->GetClass()),
					'parent_att' => $aLevel['parent_att'],
					'name_att' => $aLevel['name_att'],
					'tooltip_att' => $aLevel['tooltip_att'],
					'search' => $oSearch,
					'fields' => array(),
					'actions' => array()
				);

				// Adding current level's fields
				if (isset($aLevel['fields']))
				{
					$aLevelsProperties[$sCurrentLevelAlias]['fields'] = array();

					foreach ($aLevel['fields'] as $sFieldAttCode => $aFieldProperties)
					{
						$aLevelsProperties[$sCurrentLevelAlias]['fields'][] = array(
							'code' => $sFieldAttCode,
							'label' => MetaModel::GetAttributeDef($oSearch->GetClass(), $sFieldAttCode)->GetLabel(),
							'hidden' => $aFieldProperties['hidden']
						);
					}
				}

				// Flattening and adding sublevels
				if (isset($aLevel['levels']))
				{
					foreach ($aLevel['levels'] as $aChildLevel)
					{
						// Checking if the sublevel if allowed
						$oChildSearch = DBSearch::FromOQL($aChildLevel['oql']);
						if (SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $oChildSearch->GetClass()))
						{
							// Adding the sublevel to this one
							$aLevelsProperties[$sCurrentLevelAlias]['levels'][] = $sCurrentLevelAlias . static::LEVEL_SEPARATOR . $aChildLevel['id'];

							// Adding drilldown action if necessary
							foreach ($aLevel['actions'] as $sId => $aAction)
							{
								if ($aAction['type'] === BrowseBrick::ENUM_ACTION_DRILLDOWN)
								{
									$aLevelsProperties[$sCurrentLevelAlias]['actions'][$sId] = $aAction;
									break;
								}
							}
						}
						unset($oChildSearch);
					}
					static::TreeToFlatLevelsProperties($oApp, $aLevel['levels'], $aLevelsProperties, $sCurrentLevelAlias);
				}

				// Adding actions to the level
				foreach ($aLevel['actions'] as $sId => $aAction)
				{
					// ... Only if it's not already there (eg. the drilldown added with the sublevels)
					if (!array_key_exists($sId, $aLevelsProperties[$sCurrentLevelAlias]['actions']))
					{
						// Adding action only if allowed
						if (($aAction['type'] === BrowseBrick::ENUM_ACTION_VIEW) && !SecurityHelper::IsActionAllowed($oApp, UR_ACTION_READ, $oSearch->GetClass()))
						{
							continue;
						}
						elseif (($aAction['type'] === BrowseBrick::ENUM_ACTION_EDIT) && !SecurityHelper::IsActionAllowed($oApp, UR_ACTION_MODIFY, $oSearch->GetClass()))
						{
							continue;
						}
						elseif ($aAction['type'] === BrowseBrick::ENUM_ACTION_DRILLDOWN)
						{
							continue;
						}

						// Setting action title
						if (isset($aAction['title']))
						{
							// Note : There could be an enhancement here, by checking if the string code has the '%1' needle and use Dict::S or Dict::Format accordingly.
							// But it would require to benchmark a potential performance drop as it will be done for all items
							$aAction['title'] = Dict::S($aAction['title']);
						}
						else
						{
							switch ($aAction['type'])
							{
								case BrowseBrick::ENUM_ACTION_CREATE_FROM_THIS:
									// We can only make translate a dictionnary entry with a class placeholder when the action has a class tag. if it has a factory method, we don't know yet what class is going to be created
									if ($aAction['factory']['type'] === BrowseBrick::ENUM_FACTORY_TYPE_CLASS)
									{
										$aAction['title'] = Dict::Format('Brick:Portal:Browse:Action:CreateObjectFromThis', MetaModel::GetName($aAction['factory']['value']));
										$aAction['url'] = $oApp['url_generator']->generate('p_object_create', array('sObjectClass' => $aAction['factory']['value']));
									}
									else
									{
										$aAction['title'] = Dict::S('Brick:Portal:Browse:Action:Create');
									}
									break;
								case BrowseBrick::ENUM_ACTION_VIEW:
									$aAction['title'] = Dict::S('Brick:Portal:Browse:Action:View');
									break;
								case BrowseBrick::ENUM_ACTION_EDIT:
									$aAction['title'] = Dict::S('Brick:Portal:Browse:Action:Edit');
									break;
								case BrowseBrick::ENUM_ACTION_DRILLDOWN:
									$aAction['title'] = Dict::S('Brick:Portal:Browse:Action:Drilldown');
									break;
							}
						}

						// Setting action icon class
						if (!isset($aAction['icon_class']))
						{
							switch ($aAction['type'])
							{
								case BrowseBrick::ENUM_ACTION_CREATE_FROM_THIS:
									$aAction['icon_class'] = BrowseBrick::ENUM_ACTION_ICON_CLASS_CREATE_FROM_THIS;
									break;
								case BrowseBrick::ENUM_ACTION_VIEW:
									$aAction['icon_class'] = BrowseBrick::ENUM_ACTION_ICON_CLASS_VIEW;
									break;
								case BrowseBrick::ENUM_ACTION_EDIT:
									$aAction['icon_class'] = BrowseBrick::ENUM_ACTION_ICON_CLASS_EDIT;
									break;
								case BrowseBrick::ENUM_ACTION_DRILLDOWN:
									$aAction['icon_class'] = BrowseBrick::ENUM_ACTION_ICON_CLASS_DRILLDOWN;
									break;
							}
						}

						// Setting action url
						switch ($aAction['type'])
						{
							case BrowseBrick::ENUM_ACTION_CREATE_FROM_THIS:
								if ($aAction['factory']['type'] === BrowseBrick::ENUM_FACTORY_TYPE_CLASS)
								{
									$aAction['url'] = $oApp['url_generator']->generate('p_object_create', array('sObjectClass' => $aAction['factory']['value']));
								}
								else
								{
									$aAction['url'] = $oApp['url_generator']->generate('p_object_create_from_factory', array('sEncodedMethodName' => base64_encode($aAction['factory']['value']), 'sObjectClass' => '-objectClass-', 'sObjectId' => '-objectId-'));
								}
								break;
						}

						$aLevelsProperties[$sCurrentLevelAlias]['actions'][$sId] = $aAction;
					}
				}
			}
		}
	}

	/**
	 * Prepares the action rules for an $oItem. Action rules are used to defined some iTopObjectCopier rules that will be apply to an DBObject created from $oItem
	 *
	 * @param DBObject $oItem
	 * @param string $sLevelsAlias
	 * @param array $aLevelsProperties
	 * @return array
	 */
	public static function PrepareActionRulesForItem(DBObject $oItem, $sLevelsAlias, array &$aLevelsProperties)
	{
		$aActionRules = array();

		foreach ($aLevelsProperties[$sLevelsAlias]['actions'] as $sId => $aAction)
		{
			$aActionRules[$sId] = ContextManipulatorHelper::EncodeRulesToken($aAction['rules'], array($oItem));
		}

		return $aActionRules;
	}

	/**
	 * Takes $aCurrentRow as a flat array and transform it in another flat array (not objects) with only the necessary informations
	 *
	 * eg:
	 * - $aCurrentRow : array('L-1' => ObjectClass1, 'L-1-1' => ObjectClass2, 'L-1-1-1' => ObjectClass3)
	 * - $aRow will be : array(
	 * 	  'L1' => array(
	 * 		  'name' => 'Object class 1 name'
	 * 	  ),
	 * 	  'L1-1' => array(
	 * 		  'name' => 'Object class 2 name',
	 * 	  ),
	 * 	  'L1-1-1' => array(
	 * 		  'name' => 'Object class 3 name',
	 * 	  ),
	 * 	  ...
	 *  )
	 *
	 * @param array $aCurrentRow
	 * @param array $aLevelsProperties
	 * @return array
	 */
	public static function AddToFlatItems(array $aCurrentRow, array &$aLevelsProperties)
	{
		$aRow = array();

		foreach ($aCurrentRow as $key => $value)
		{
			$aRow[$key] = array(
				'level_alias' => $key,
				'id' => $value->GetKey(),
				'name' => $value->Get($aLevelsProperties[$key]['name_att']),
				'class' => get_class($value),
				'action_rules_token' => static::PrepareActionRulesForItem($value, $key, $aLevelsProperties)
			);

			// Adding tooltip attribute if necessary
			if ($aLevelsProperties[$key]['tooltip_att'] !== null)
			{
				$aRow[$key]['tooltip'] = $value->Get($aLevelsProperties[$key]['tooltip_att']);
			}
			// Adding fields attributes if necessary
			if (!empty($aLevelsProperties[$key]['fields']))
			{
				$aRow[$key]['fields'] = array();
				foreach ($aLevelsProperties[$key]['fields'] as $aField)
				{
					$oAttDef = MetaModel::GetAttributeDef(get_class($value), $aField['code']);
					if ($oAttDef->GetEditClass() === 'Duration')
					{
						$aRow[$key]['fields'][$aField['code']] = $oAttDef->GetAsHTML($value->Get($aField['code']));
					}
					else
					{
						$aRow[$key]['fields'][$aField['code']] = $oAttDef->GetValueLabel($value->Get($aField['code']));
					}
				}
			}
		}

		return $aRow;
	}

	/**
	 * Takes $aCurrentRow as a flat array to recursvily convert and insert it into a tree array $aItems.
	 * This is used to build a tree array from a DBObjectSet retrieved with FetchAssoc().
	 *
	 * eg:
	 * - $aCurrentRow : array('L-1' => ObjectClass1, 'L-1-1' => ObjectClass2, 'L-1-1-1' => ObjectClass3)
	 * - $aItems will be : array(
	 * 	  'L1' =>
	 * 		  'name' => 'Object class 1 name',
	 * 		  'subitems' => array(
	 * 			  'L1-1' => array(
	 * 				  'name' => 'Object class 2 name',
	 * 				  'subitems' => array(
	 * 					  'L1-1-1' => array(
	 * 						  'name' => 'Object class 3 name',
	 * 						  'subitems' => array()
	 * 					  ),
	 * 					  ...
	 * 				  )
	 * 			  ),
	 * 			  ...
	 * 		  )
	 * 	  ),
	 * 	  ...
	 *  )
	 *
	 * @param array &$aItems Reference to the array to be built
	 * @param array $aCurrentRow
	 * @param array $aLevelsProperties
	 */
	public static function AddToTreeItems(array &$aItems, array $aCurrentRow, array &$aLevelsProperties)
	{
		$aCurrentRowKeys = array_keys($aCurrentRow);
		$aCurrentRowValues = array_values($aCurrentRow);
		$sCurrentIndex = $aCurrentRowKeys[0] . '::' . $aCurrentRowValues[0]->GetKey();

		if (!isset($aItems[$sCurrentIndex]))
		{
			$aItems[$sCurrentIndex] = array(
				'level_alias' => $aCurrentRowKeys[0],
				'id' => $aCurrentRowValues[0]->GetKey(),
				'name' => $aCurrentRowValues[0]->Get($aLevelsProperties[$aCurrentRowKeys[0]]['name_att']),
				'class' => get_class($aCurrentRowValues[0]),
				'subitems' => array(),
				'action_rules_token' => static::PrepareActionRulesForItem($aCurrentRowValues[0], $aCurrentRowKeys[0], $aLevelsProperties)
			);

			if ($aLevelsProperties[$aCurrentRowKeys[0]]['tooltip_att'] !== null)
			{
				$aItems[$sCurrentIndex]['tooltip'] = $aCurrentRowValues[0]->Get($aLevelsProperties[$aCurrentRowKeys[0]]['tooltip_att']);
			}
		}

		$aCurrentRowSliced = array_slice($aCurrentRow, 1);
		if (!empty($aCurrentRowSliced))
		{
			static::AddToTreeItems($aItems[$sCurrentIndex]['subitems'], $aCurrentRowSliced, $aLevelsProperties);
		}
	}

}
