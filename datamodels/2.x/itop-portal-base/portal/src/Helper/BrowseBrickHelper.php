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


namespace Combodo\iTop\Portal\Helper;


use AttributeImage;
use AttributeSet;
use AttributeTagSet;
use Combodo\iTop\Portal\Brick\BrowseBrick;
use DBSearch;
use Dict;
use MetaModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserRights;

/**
 * Class BrowseBrickHelper
 *
 * @package Combodo\iTop\Portal\Helper
 * @since   2.7.0
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BrowseBrickHelper
{
	/** @var string LEVEL_SEPARATOR */
	const LEVEL_SEPARATOR = '-';
	/** @var array OPTIONAL_ATTRIBUTES */
	const OPTIONAL_ATTRIBUTES = array('tooltip_att', 'description_att', 'image_att');

	/** @var \Combodo\iTop\Portal\Helper\SecurityHelper */
	private $oSecurityHelper;
	/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper */
	private $oScopeValidator;
	/** @var \Combodo\iTop\Portal\Routing\UrlGenerator */
	private $oUrlGenerator;

	/**
	 * BrowseBrickHelper constructor.
	 *
	 * @param \Combodo\iTop\Portal\Helper\SecurityHelper                 $oSecurityHelper
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper           $oScopeValidator
	 * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $oUrlGenerator
	 */
	public function __construct(SecurityHelper $oSecurityHelper, ScopeValidatorHelper $oScopeValidator, UrlGeneratorInterface $oUrlGenerator
	) {
		$this->oSecurityHelper = $oSecurityHelper;
		$this->oScopeValidator = $oScopeValidator;
		$this->oUrlGenerator = $oUrlGenerator;
	}

	/**
	 * Flattens the $aLevels into $aLevelsProperties in order to be able to build an OQL query from multiple single queries related to each
	 * others. As of now it only keeps search / parent_att / name_att properties.
	 *
	 * Note : This is not in the BrowseBrick class because the classes should not rely on DBObjectSearch.
	 *
	 * @param array  $aLevels           Levels from a BrowseBrick class
	 * @param array  $aLevelsProperties Reference to an array that will contain the flattened levels
	 * @param string $sLevelAliasPrefix String that will be prefixed to the level ID as an unique path identifier
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function TreeToFlatLevelsProperties(array $aLevels, array &$aLevelsProperties, $sLevelAliasPrefix = 'L')
	{
		foreach ($aLevels as $aLevel)
		{
			$sCurrentLevelAlias = $sLevelAliasPrefix.static::LEVEL_SEPARATOR.$aLevel['id'];
			$oSearch = DBSearch::CloneWithAlias(DBSearch::FromOQL($aLevel['oql']), $sCurrentLevelAlias);

			// Restricting to the allowed scope
			$oScopeSearch = $this->oScopeValidator->GetScopeFilterForProfiles(UserRights::ListProfiles(), $oSearch->GetClass(),
				UR_ACTION_READ);
			$oSearch = ($oScopeSearch !== null) ? $oSearch->Intersect($oScopeSearch) : null;
			// - Allowing all data if necessary
			if ($oScopeSearch !== null && $oScopeSearch->IsAllDataAllowed())
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
					'description_att' => $aLevel['description_att'],
					'image_att' => $aLevel['image_att'],
					'search' => $oSearch,
					'fields' => array(),
					'actions' => array(),
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
							'hidden' => $aFieldProperties['hidden'],
						);
					}
				}

				// Flattening and adding sub levels
				if (isset($aLevel['levels']))
				{
					foreach ($aLevel['levels'] as $aChildLevel)
					{
						// Checking if the sub level if allowed
						$oChildSearch = DBSearch::FromOQL($aChildLevel['oql']);
						if ($this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $oChildSearch->GetClass()))
						{
							// Adding the sub level to this one
							$aLevelsProperties[$sCurrentLevelAlias]['levels'][] = $sCurrentLevelAlias.static::LEVEL_SEPARATOR.$aChildLevel['id'];

							// Adding drill down action if necessary
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
					$this->TreeToFlatLevelsProperties($aLevel['levels'], $aLevelsProperties, $sCurrentLevelAlias);
				}

				// Adding actions to the level
				foreach ($aLevel['actions'] as $sId => $aAction)
				{
					// ... Only if it's not already there (eg. the drilldown added with the sublevels)
					if (!array_key_exists($sId, $aLevelsProperties[$sCurrentLevelAlias]['actions']))
					{
						// Adding action only if allowed
						if (($aAction['type'] === BrowseBrick::ENUM_ACTION_VIEW) && !$this->oSecurityHelper->IsActionAllowed(UR_ACTION_READ,
								$oSearch->GetClass()))
						{
							continue;
						}
						elseif (($aAction['type'] === BrowseBrick::ENUM_ACTION_EDIT) && !$this->oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY,
								$oSearch->GetClass()))
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
									// We can only make translate a dictionary entry with a class placeholder when the action has a class tag. if it has a factory method, we don't know yet what class is going to be created
									if ($aAction['factory']['type'] === BrowseBrick::ENUM_FACTORY_TYPE_CLASS)
									{
										$aAction['title'] = Dict::Format('Brick:Portal:Browse:Action:CreateObjectFromThis',
											MetaModel::GetName($aAction['factory']['value']));
										$aAction['url'] = $this->oUrlGenerator->generate('p_object_create',
											array('sObjectClass' => $aAction['factory']['value']));
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
									$aAction['url'] = $this->oUrlGenerator->generate('p_object_create',
										array('sObjectClass' => $aAction['factory']['value']));
								}
								else
								{
									$aAction['url'] = $this->oUrlGenerator->generate('p_object_create_from_factory', array(
										'sEncodedMethodName' => base64_encode($aAction['factory']['value']),
										'sObjectClass' => '-objectClass-',
										'sObjectId' => '-objectId-',
									));
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
	 * Prepares the action rules for an array of DBObject items.
	 *
	 * @param array  $aItems
	 * @param string $sLevelsAlias
	 * @param array  $aLevelsProperties
	 *
	 * @return array
	 */
	public function PrepareActionRulesForItems(array $aItems, $sLevelsAlias, array &$aLevelsProperties)
	{
		$aActionRules = array();

		foreach ($aLevelsProperties[$sLevelsAlias]['actions'] as $sId => $aAction)
		{
			$aActionRules[$sId] = ContextManipulatorHelper::PrepareAndEncodeRulesToken($aAction['rules'], $aItems);
		}

		return $aActionRules;
	}

	/**
	 * Takes $aCurrentRow as a flat array and transform it in another flat array (not objects) with only the necessary information
	 *
	 * eg:
	 * - $aCurrentRow : array('L-1' => ObjectClass1, 'L-1-1' => ObjectClass2, 'L-1-1-1' => ObjectClass3)
	 * - $aRow will be : array(
	 *      'L1' => array(
	 *          'name' => 'Object class 1 name'
	 *      ),
	 *      'L1-1' => array(
	 *          'name' => 'Object class 2 name',
	 *      ),
	 *      'L1-1-1' => array(
	 *          'name' => 'Object class 3 name',
	 *      ),
	 *      ...
	 *  )
	 *
	 * @param array $aCurrentRow
	 * @param array $aLevelsProperties
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function AddToFlatItems(array $aCurrentRow, array &$aLevelsProperties)
	{
		$aRow = array();

		/** @var \DBObject $value */
		foreach ($aCurrentRow as $key => $value)
		{
			// Retrieving objects from all levels
			$aItems = array_values($aCurrentRow);

			$sCurrentObjectClass = get_class($value);
			$sCurrentObjectId = $value->GetKey();

			$sNameAttCode = $aLevelsProperties[$key]['name_att'];
			$sNameAttDef = MetaModel::GetAttributeDef($sCurrentObjectClass, $sNameAttCode);
			$sNameAttDefClass = get_class($sNameAttDef);

			$aRow[$key] = array(
				'level_alias' => $key,
				'id' => $sCurrentObjectId,
				'name' => $value->Get($sNameAttCode),
				'class' => $sCurrentObjectClass,
				'action_rules_token' => $this->PrepareActionRulesForItems($aItems, $key, $aLevelsProperties),
				'metadata' => array(
					'object_class' => $sCurrentObjectClass,
					'object_id' => $sCurrentObjectId,
					'attribute_code' => $sNameAttCode,
					'attribute_type' => $sNameAttDefClass,
					'value_raw' => $value->Get($sNameAttCode),
				),
			);

			// Adding optional attributes if necessary
			foreach (static::OPTIONAL_ATTRIBUTES as $sOptionalAttribute)
			{
				if ($aLevelsProperties[$key][$sOptionalAttribute] !== null)
				{
					$sPropertyName = substr($sOptionalAttribute, 0, -4);
					$oAttDef = MetaModel::GetAttributeDef($sCurrentObjectClass, $aLevelsProperties[$key][$sOptionalAttribute]);

					if ($oAttDef instanceof AttributeImage)
					{
						$tmpAttValue = $value->Get($aLevelsProperties[$key][$sOptionalAttribute]);
						if ($sOptionalAttribute === 'image_att')
						{
							if (is_object($tmpAttValue) && !$tmpAttValue->IsEmpty())
							{
								$oOrmDoc = $tmpAttValue;
								$tmpAttValue = $this->oUrlGenerator->generate('p_object_document_display', [
									'sObjectClass' => $sCurrentObjectClass,
									'sObjectId' => $sCurrentObjectId,
									'sObjectField' => $aLevelsProperties[$key][$sOptionalAttribute],
									'cache' => 86400,
									's' => $oOrmDoc->GetSignature(),
								]);
							}
							else
							{
								$tmpAttValue = $oAttDef->Get('default_image');
							}
						}
					}
					else
					{
						$tmpAttValue = $value->GetAsHTML($aLevelsProperties[$key][$sOptionalAttribute]);
					}

					$aRow[$key][$sPropertyName] = $tmpAttValue;
				}
			}
			// Adding fields attributes if necessary
			if (!empty($aLevelsProperties[$key]['fields']))
			{
				$aRow[$key]['fields'] = array();
				foreach ($aLevelsProperties[$key]['fields'] as $aField)
				{
					$oAttDef = MetaModel::GetAttributeDef($sCurrentObjectClass, $aField['code']);
					$sAttDefClass = get_class($oAttDef);

					switch (true)
					{
						case $oAttDef instanceof AttributeTagSet:
							/** @var \ormTagSet $oSetValues */
							$oSetValues = $value->Get($aField['code']);
							$aCodes = $oSetValues->GetTags();
							/** @var \AttributeTagSet $oAttDef */
							$sHtmlForFieldValue = $oAttDef->GenerateViewHtmlForValues($aCodes, '', false);
							break;

						case $oAttDef instanceof AttributeSet:
							$oAttDef->SetDisplayLink(false);
							$sHtmlForFieldValue = $value->Get($aField['code']);
							break;

						case $oAttDef instanceof AttributeImage:
							// Todo: This should be refactored, it has been seen multiple times in the portal
							$oOrmDoc = $value->Get($aField['code']);
							if (is_object($oOrmDoc) && !$oOrmDoc->IsEmpty())
							{
								$sUrl = $this->oUrlGenerator->generate('p_object_document_display', [
									'sObjectClass' => $sCurrentObjectClass,
									'sObjectId' => $sCurrentObjectId,
									'sObjectField' => $aField['code'],
									'cache' => 86400,
									's' => $oOrmDoc->GetSignature(),
								]);
							}
							else
							{
								$sUrl = $oAttDef->Get('default_image');
							}
							$sHtmlForFieldValue = '<img src="'.$sUrl.'" />';
							break;

						default:
							$sHtmlForFieldValue = $oAttDef->GetAsHTML($value->Get($aField['code']), $value);
							break;
					}

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
					$attValueRaw = ($bExcludeRawValue === false) ? $value->Get($aField['code']) : null;

					$aRow[$key]['fields'][$aField['code']] = array(
						'object_class' => $sCurrentObjectClass,
						'object_id' => $sCurrentObjectId,
						'attribute_code' => $aField['code'],
						'attribute_type' => $sAttDefClass,
						'value_raw' => $attValueRaw,
						'value_html' => $sHtmlForFieldValue,
					);
				}
			}
		}

		return $aRow;
	}

	/**
	 * Takes $aCurrentRow as a flat array to recursively convert and insert it into a tree array $aItems.
	 * This is used to build a tree array from a DBObjectSet retrieved with FetchAssoc().
	 *
	 * eg:
	 * - $aCurrentRow : array('L-1' => ObjectClass1, 'L-1-1' => ObjectClass2, 'L-1-1-1' => ObjectClass3)
	 * - $aItems will be : array(
	 *      'L1' =>
	 *          'name' => 'Object class 1 name',
	 *          'subitems' => array(
	 *              'L1-1' => array(
	 *                  'name' => 'Object class 2 name',
	 *                  'subitems' => array(
	 *                      'L1-1-1' => array(
	 *                          'name' => 'Object class 3 name',
	 *                          'subitems' => array()
	 *                      ),
	 *                      ...
	 *                  )
	 *              ),
	 *              ...
	 *          )
	 *      ),
	 *      ...
	 *  )
	 *
	 * @param array &    $aItems Reference to the array to be built
	 * @param array      $aCurrentRow
	 * @param array      $aLevelsProperties
	 * @param array|null $aCurrentRowObjects
	 *
	 * @throws \Exception
	 */
	public function AddToTreeItems(array &$aItems, array $aCurrentRow, array &$aLevelsProperties, $aCurrentRowObjects = null)
	{
		$aCurrentRowKeys = array_keys($aCurrentRow);
		$aCurrentRowValues = array_values($aCurrentRow);
		/** @var \DBObject[] $aCurrentRowValues */
		$sCurrentIndex = $aCurrentRowKeys[0].'::'.$aCurrentRowValues[0]->GetKey();

		// We make sure to keep all row objects through levels by copying them when processing the first level.
		// Otherwise they will be sliced through levels, one by one.
		if ($aCurrentRowObjects === null)
		{
			$aCurrentRowObjects = $aCurrentRowValues;
		}

		if (!isset($aItems[$sCurrentIndex]))
		{
			$aItems[$sCurrentIndex] = array(
				'level_alias' => $aCurrentRowKeys[0],
				'id' => $aCurrentRowValues[0]->GetKey(),
				'name' => $aCurrentRowValues[0]->Get($aLevelsProperties[$aCurrentRowKeys[0]]['name_att']),
				'class' => get_class($aCurrentRowValues[0]),
				'subitems' => array(),
				'action_rules_token' => $this->PrepareActionRulesForItems($aCurrentRowObjects, $aCurrentRowKeys[0], $aLevelsProperties),
			);

			// Adding optional attributes if necessary
			foreach (static::OPTIONAL_ATTRIBUTES as $sOptionalAttribute)
			{
				if ($aLevelsProperties[$aCurrentRowKeys[0]][$sOptionalAttribute] !== null)
				{
					$sPropertyName = substr($sOptionalAttribute, 0, -4);
					$oAttDef = MetaModel::GetAttributeDef(get_class($aCurrentRowValues[0]),
						$aLevelsProperties[$aCurrentRowKeys[0]][$sOptionalAttribute]);

					if ($oAttDef instanceof AttributeImage)
					{
						$tmpAttValue = $aCurrentRowValues[0]->Get($aLevelsProperties[$aCurrentRowKeys[0]][$sOptionalAttribute]);
						if ($sOptionalAttribute === 'image_att')
						{
							if (is_object($tmpAttValue) && !$tmpAttValue->IsEmpty())
							{
								$oOrmDoc = $tmpAttValue;
								$tmpAttValue = $this->oUrlGenerator->generate('p_object_document_display', [
									'sObjectClass' => get_class($aCurrentRowValues[0]),
									'sObjectId' => $aCurrentRowValues[0]->GetKey(),
									'sObjectField' => $aLevelsProperties[$aCurrentRowKeys[0]][$sOptionalAttribute],
									'cache' => 86400,
									's' => $oOrmDoc->GetSignature(),
								]);
							}
							else
							{
								$tmpAttValue = $oAttDef->Get('default_image');
							}
						}
					}
					else
					{
						$tmpAttValue = $aCurrentRowValues[0]->GetAsHTML($aLevelsProperties[$aCurrentRowKeys[0]][$sOptionalAttribute]);
					}

					$aItems[$sCurrentIndex][$sPropertyName] = $tmpAttValue;
				}
			}
		}

		$aCurrentRowSliced = array_slice($aCurrentRow, 1);
		if (!empty($aCurrentRowSliced))
		{
			$this->AddToTreeItems($aItems[$sCurrentIndex]['subitems'], $aCurrentRowSliced, $aLevelsProperties, $aCurrentRowObjects);
		}
	}
}
