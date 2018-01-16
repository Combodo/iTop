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

namespace Combodo\iTop\Portal\Helper;

use Exception;
use Silex\Application;
use DOMNodeList;
use DOMFormatException;
use UserRights;
use DBObject;
use DBSearch;
use DBObjectSet;
use BinaryExpression;
use FieldExpression;
use ScalarExpression;

class ContextManipulatorHelper
{
	const ENUM_RULE_CALLBACK_BACK = 'back';
	const ENUM_RULE_CALLBACK_GOTO = 'goto';
	const ENUM_RULE_CALLBACK_OPEN = 'open';
	const ENUM_RULE_CALLBACK_OPEN_VIEW = 'view';
	const ENUM_RULE_CALLBACK_OPEN_EDIT = 'edit';
	const DEFAULT_RULE_CALLBACK_OPEN = self::ENUM_RULE_CALLBACK_OPEN_VIEW;

	protected $oApp;
	protected $aRules;

	public function __construct()
	{
		$this->aRules = array();
	}

	/**
	 * Initializes the ScopeValidator by generating and caching the scopes compilation in the $this->sCachePath.$this->sFilename file.
	 *
	 * @param DOMNodeList $oNodes
	 * @throws DOMFormatException
	 * @throws Exception
	 */
	public function Init(DOMNodeList $oNodes)
	{
		$this->aRules = array();

		// Iterating over the scope nodes
		foreach ($oNodes as $oRuleNode)
		{
			// Retrieving mandatory id attribute
			$sRuleId = $oRuleNode->getAttribute('id');
			if ($sRuleId === '')
			{
				throw new DOMFormatException('Rule tag must have an id attribute.', null, null, $oRuleNode);
			}

			// Setting if the rule needs a source object
			$bNeedsSource = false;
			// Note : preset and retrofit are no longer plurals as it should match as much as possible iTopObjectCopier specs. We use plurals only in the xml for the collection tags
			$aRule = array(
				'source_oql' => null,
				'dest_class' => null,
				'preset' => array(),
				'retrofit' => array(),
				'submit' => null,
				'cancel' => null
			);

			// Iterating over the rule's nodes
			foreach ($oRuleNode->childNodes as $oSubNode)
			{
				$sSubNodeName = $oSubNode->nodeName;
				switch ($sSubNodeName)
				{
					case 'source_class':
						$aRule['source_oql'] = 'SELECT ' . $oSubNode->GetText();
						break;

					case 'source_oql':
					case 'dest_class':
						$aRule[$sSubNodeName] = $oSubNode->GetText();
						break;

					case 'presets':
					case 'retrofits':
						foreach ($oSubNode->childNodes as $oActionNode)
						{
							// Note : Caution, the index of $aRule is now $oActionNode->nodeName instead of $sSubNodeName, as we want to match iTopObjectCopier specs like told previously
							if (in_array($oActionNode->nodeName, array('preset', 'retrofit')))
							{
								$sActionText = $oActionNode->GetText();
								$aRule[$oActionNode->nodeName][] = $sActionText;

								// Checking if the rule needs a source object
								if (substr($sActionText, 0, 4) === 'copy')
								{
									$bNeedsSource = true;
								}
							}
						}
						break;

					case 'submit':
					case 'cancel':
						// Retrieving callback type and checking that it is allowed
						$sType = $oSubNode->getAttribute('xsi:type');
						if ($sType === '')
						{
							throw new DOMFormatException($sSubNodeName . ' must have an xsi:type attribute.', null, null, $oSubNode);
						}
						if (($sType === static::ENUM_RULE_CALLBACK_OPEN) && ($sSubNodeName === 'cancel'))
						{
							throw new DOMFormatException('Cancel tag cannot be of type ' . $sType . '.', null, null, $oSubNode);
						}

						$aRule[$sSubNodeName] = array('type' => $sType);
						switch ($sType)
						{
							case static::ENUM_RULE_CALLBACK_BACK:
								// Default value
								$sRefresh = false;
								// Retrieving value
								$oRefreshNode = $oSubNode->GetOptionalElement('refresh');
								if (($oRefreshNode !== null) && ($oRefreshNode->GetText() !== null))
								{
									$sRefresh = $oRefreshNode->GetText();
								}

								$aRule[$sSubNodeName]['refresh'] = $sRefresh;
								break;
							case static::ENUM_RULE_CALLBACK_GOTO:
								// Retrieving value
								$sBrickId = $oSubNode->GetUniqueElement('brick')->GetText();
								if ($sBrickId === null)
								{
									throw new DOMFormatException('Brick tag value must not be empty.', null, null, $oSubNode);
								}

								$aRule[$sSubNodeName]['brick_id'] = $sBrickId;
								break;
							case static::ENUM_RULE_CALLBACK_OPEN:
								// Default value
								$sMode = static::ENUM_RULE_CALLBACK_OPEN_VIEW;
								// Retrieving value
								$oModeNode = $oSubNode->GetOptionalElement('mode');
								if (($oModeNode !== null) && ($oModeNode->GetText() !== null))
								{
									$sMode = $oModeNode->GetText();
								}

								$aRule[$sSubNodeName]['mode'] = $sMode;
								break;
						}
						break;
				}
			}

			// If there is no source information we check if there is a preset that requires a copy in order to throw an exception
			if (($aRule['source_oql'] === null) && ($bNeedsSource === true))
			{
				throw new DOMFormatException('Rule tag must have either a "source_oql" or a "source_class" child node.', null, null, $oRuleNode);
			}

			$this->aRules[$sRuleId] = $aRule;
		}
	}

	public function SetApp($oApp)
	{
		$this->oApp = $oApp;
	}

	/**
	 * Returns a hash array of rules
	 *
	 * @return array
	 */
	public function GetRules()
	{
		return $this->aRules;
	}

	/**
	 * Return the rule identified by its ID, as a hash array
	 *
	 * @param string $sId
	 * @return array
	 */
	public function GetRule($sId)
	{
		if (!array_key_exists($sId, $this->aRules))
		{
			throw new Exception('Context creator : Could not find "' . $sId . '" in the rules list');
		}
		return $this->aRules[$sId];
	}

	/**
	 * Prepare the $oObject passed as a reference with the $aData
	 *
	 * $aData must be of the form :
	 * array(
	 *   'rules' => array(
	 *     'rule-id-1',
	 *     'rule-id-2',
	 *     ...
	 *   ),
	 *   'sources' => array(
	 *     <DBObject1 class> => <DBObject1 id>,
	 *     <DBObject2 class> => <DBObject2 id>,
	 *     ...
	 *   )
	 * )
	 *
	 * @param array $aData
	 * @param DBObject $oObject
	 */
	public function PrepareObject(array $aData, DBObject &$oObject)
	{
		if (isset($aData['rules']) && isset($aData['sources']))
		{
			$aRules = $aData['rules'];
			$aSources = $aData['sources'];

			foreach ($aData['rules'] as $sId)
			{
				// Retrieveing current rule
				$aRule = $this->GetRule($sId);

				// Retrieving source object if needed
				if ($aRule['source_oql'] !== null)
				{
					// Preparing query to retrieve source object(s)
					$oSearch = DBSearch::FromOQL($aRule['source_oql']);
					$sSearchClass = $oSearch->GetClass();
					$aSearchParams = $oSearch->GetInternalParams();

					if (array_key_exists($sSearchClass, $aSources))
					{
						$sourceId = $aSources[$sSearchClass];

						if (array_key_exists('id', $oSearch->GetQueryParams()))
						{
							if (is_array($sourceId))
							{
								throw new Exception('Context creator : ":id" parameter in rule "' . $sId . '" cannot be an array (This is a limitation of DBSearch)');
							}

							$aSearchParams['id'] = $sourceId;
						}
						else
						{
							if (!is_array($sourceId))
							{
								$sourceId = array($sourceId);
							}

							$iLoopMax = count($sourceId);
							$oFullBinExpr = null;
							for ($i = 0; $i < $iLoopMax; $i++)
							{
								// - Building full search expression
								$oBinExpr = new BinaryExpression(new FieldExpression('id', $oSearch->GetClassAlias()), '=', new ScalarExpression($sourceId[$i]));
								if ($i === 0)
								{
									$oFullBinExpr = $oBinExpr;
								}
								else
								{
									$oFullBinExpr = new BinaryExpression($oFullBinExpr, 'OR', $oBinExpr);
								}

								// - Adding it to the query when complete
								if ($i === ($iLoopMax - 1))
								{
									$oSearch->AddConditionExpression($oFullBinExpr);
								}
							}
						}
					}

					// Checking for silos
					$oScopeSearch = $this->oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sSearchClass, UR_ACTION_READ);
					if ($oScopeSearch->IsAllDataAllowed())
					{
						$oSearch->AllowAllData();
					}

					// Retrieving source object(s) and applying rules
					$oSet = new DBObjectSet($oSearch, array(), $aSearchParams);
					while ($oSourceObject = $oSet->Fetch())
					{
						// Changing behaviour to remove usage of ObjectCopier as its now being integrated in the core
						// Old code : iTopObjectCopier::PrepareObject($aRule, $oObject, $oSourceObject);
						// Presets
						if (isset($aRule['preset']) && !empty($aRule['preset']))
						{
							$oObject->ExecActions($aRule['preset'], array('source' => $oSourceObject));
						}
						// Retrofits
						if (isset($aRule['retrofit']) && !empty($aRule['retrofit']))
						{
							$oSourceObject->ExecActions($aRule['retrofit'], array('source' => $oObject));
						}
					}
				}
				else
				{
					// Changing behaviour to remove usage of ObjectCopier as its now being integrated in the core
					// Old code : iTopObjectCopier::PrepareObject($aRule, $oObject, $oObject);
					// Presets
					if (isset($aRule['preset']) && !empty($aRule['preset']))
					{
						$oObject->ExecActions($aRule['preset'], array('source' => $oObject));
					}
				}
			}
		}
	}

	/**
	 * Returns a hash array of urls for each type of callback
	 *
	 * eg :
	 * array(
	 * 	 'submit' => 'http://localhost/',
	 * 	 'cancel' => null
	 * );
	 *
	 * @param \Silex\Application $oApp
	 * @param array $aData
	 * @param \DBObject $oObject
	 * @param boolean $bModal
	 * @return array
	 */
	public function GetCallbackUrls(Application $oApp, array $aData, DBObject $oObject, $bModal = false)
	{
		$aResults = array(
			'submit' => null,
			'cancel' => null
		);

		if (isset($aData['rules']))
		{
			foreach ($aData['rules'] as $sId)
			{
				// Retrieveing current rule
				$aRule = $this->GetRule($sId);

				// For each type of callbacks, we check if there is a rule to apply
				foreach (array('submit', 'cancel') as $sCallbackName)
				{
					if (is_array($aRule[$sCallbackName]))
					{
						// Previously declared rule on a callback is overwritten by the last
						$sCallbackUrl = null;
						switch ($aRule[$sCallbackName]['type'])
						{
							case static::ENUM_RULE_CALLBACK_BACK:
								if (!$bModal)
								{
									$sCallbackUrl = ($_SERVER['HTTP_REFERER'] !== '') ? $_SERVER['HTTP_REFERER'] : null;
								}
								break;

							case static::ENUM_RULE_CALLBACK_GOTO:
								$oBrick = ApplicationHelper::GetLoadedBrickFromId($oApp, $aRule[$sCallbackName]['brick_id']);
								$sCallbackUrl = $oApp['url_generator']->generate($oBrick->GetRouteName(), array('sBrickId' => $oBrick->GetId()));
								break;

							case static::ENUM_RULE_CALLBACK_OPEN:
								$sCallbackUrl = ($oObject->IsNew()) ? null : $oApp['url_generator']->generate('p_object_' . $aRule[$sCallbackName]['mode'], array('sObjectClass' => get_class($oObject), 'sObjectId' => $oObject->GetKey()));
								break;
						}

						$aResults[$sCallbackName] = $sCallbackUrl;
					}
				}
			}
		}

		return $aResults;
	}

    /**
     * Prepares the rules as an array of rules and source objects so it can be tokenised
     *
     * @param array $aRules
     * @param array $aObjects
     * @return array
     */
	public static function PrepareRulesForToken($aRules, $aObjects = array())
    {
        // Getting necessary information from objects
        $aSources = array();
        foreach ($aObjects as $oObject)
        {
            $aSources[get_class($oObject)] = $oObject->GetKey();
        }

        // Preparing data
        $aTokenRules = array(
            'rules' => $aRules,
            'sources' => $aSources
        );

        return $aTokenRules;
    }

	/**
	 * Encodes a token made out of the rules.
	 *
	 * Token = base64_encode( json_encode( array( 'rules' => array(), 'sources' => array() ) ) )
	 *
	 * To retrieve it has
	 *
	 * @param array $aRules
	 * @param array $aObjects
	 * @return string
	 */
	public static function EncodeRulesToken($aTokenRules)
	{
	    // Returning tokenised data
		return base64_encode(json_encode($aTokenRules));
	}

    /**
     * @param array $aRules
     * @param array $aObjects
     * @return string
     */
	public static function PrepareAndEncodeRulesToken($aRules, $aObjects = array())
    {
        // Preparing rules before making a token
        $aTokenRules = static::PrepareRulesForToken($aRules, $aObjects);

        // Returning tokenised data
        return static::EncodeRulesToken($aTokenRules);
    }

	/**
	 * Decodes a token made out of the rules
	 *
	 * @param string $sToken
	 * @return array
	 */
	public static function DecodeRulesToken($sToken)
	{
		return json_decode(base64_decode($sToken), true);
	}

}

?>