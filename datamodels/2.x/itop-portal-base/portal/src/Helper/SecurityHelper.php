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

use LogChannels;
use UserRights;
use IssueLog;
use MetaModel;
use DBSearch;
use DBObjectSearch;
use DBObjectSet;
use FieldExpression;
use VariableExpression;
use BinaryExpression;

/**
 * SecurityHelper class
 *
 * Handle security checks through the different layers (portal scopes, iTop silos, user rights)
 *
 * @package Combodo\iTop\Portal\Helper
 * @since   2.3.0
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class SecurityHelper
{
	/** @var array $aAllowedScopeObjectsCache */
	public static $aAllowedScopeObjectsCache = array(
		UR_ACTION_READ => array(),
		UR_ACTION_MODIFY => array(),
	);

	/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeValidator */
	private $oScopeValidator;
	/** @var \Combodo\iTop\Portal\Helper\LifecycleValidatorHelper $oLifecycleValidator */
	private $oLifecycleValidator;
	/** @var bool $bDebug */
	private $bDebug;

	/**
	 * SecurityHelper constructor.
	 *
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper     $oScopeValidator
	 * @param \Combodo\iTop\Portal\Helper\LifecycleValidatorHelper $oLifecycleValidator
	 * @param                                                      $bDebug
	 */
	public function __construct(ScopeValidatorHelper $oScopeValidator, LifecycleValidatorHelper $oLifecycleValidator, $bDebug)
	{
		$this->oScopeValidator = $oScopeValidator;
		$this->oLifecycleValidator = $oLifecycleValidator;
		$this->bDebug = $bDebug;
	}


	/**
	 * Returns true if the current user is allowed to do the $sAction on an $sObjectClass object (with optional $sObjectId id)
	 * Checks are:
	 * - Has a scope query for the $sObjectClass / $sAction
	 * - Optionally, if $sObjectId provided: Is object within scope for $sObjectClass / $sObjectId / $sAction
	 * - Is allowed by datamodel for $sObjectClass / $sAction
	 *
	 * @param string $sAction Must be in UR_ACTION_READ|UR_ACTION_MODIFY|UR_ACTION_CREATE
	 * @param string $sObjectClass
	 * @param string $sObjectId
	 *
	 * @return boolean
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function IsActionAllowed($sAction, $sObjectClass, $sObjectId = null)
	{
		$sDebugTracePrefix = __CLASS__.' / '.__METHOD__.' : Returned false for action '.$sAction.' on '.$sObjectClass.'::'.$sObjectId;

		// Checking action type
		if (!in_array($sAction, array(UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_CREATE)))
		{
			IssueLog::Debug($sDebugTracePrefix.' as the action value could not be understood ('.UR_ACTION_READ.'/'.UR_ACTION_MODIFY.'/'.UR_ACTION_CREATE.' expected', LogChannels::PORTAL);
			return false;
		}

		// Forcing allowed writing on the object if necessary. This is used in some particular cases.
		$bObjectIsCurrentUser = ($sObjectClass === 'Person' && $sObjectId == UserRights::GetContactId());
		if(in_array($sAction , array(UR_ACTION_MODIFY, UR_ACTION_READ)) && $bObjectIsCurrentUser){
			return true;
	    }

		// Checking the scopes layer
		// - Transforming scope action as there is only 2 values
		$sScopeAction = ($sAction === UR_ACTION_READ) ? UR_ACTION_READ : UR_ACTION_MODIFY;
		// - Retrieving the query. If user has no scope, it can't access that kind of objects
		$oScopeQuery = $this->oScopeValidator->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sObjectClass, $sScopeAction);
		if ($oScopeQuery === null)
		{
			IssueLog::Debug($sDebugTracePrefix.' as there was no scope defined for action '.$sScopeAction.' and profiles '.implode('/', UserRights::ListProfiles()), LogChannels::PORTAL);
			return false;
		}
		// - If action != create we do some additionnal checks
		if ($sAction !== UR_ACTION_CREATE)
		{
			// - Checking specific object if id is specified
			if ($sObjectId !== null)
			{
				// Checking if object status is in cache (to avoid unnecessary query)
				if (isset(static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass][$sObjectId]))
				{
					if (static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass][$sObjectId] === false)
					{
						IssueLog::Debug($sDebugTracePrefix.' as it was denied in the scope objects cache', LogChannels::PORTAL);
						return false;
					}
				}
				else
				{
					// Modifying query to filter on the ID
					// - Adding expression
					$sObjectKeyAtt = MetaModel::DBGetKey($sObjectClass);
					$oFieldExp = new FieldExpression($sObjectKeyAtt, $oScopeQuery->GetClassAlias());
					$oBinExp = new BinaryExpression($oFieldExp, '=', new VariableExpression('object_id'));
					$oScopeQuery->AddConditionExpression($oBinExp);
					// - Setting value
					$aQueryParams = $oScopeQuery->GetInternalParams();
					$aQueryParams['object_id'] = $sObjectId;
					$oScopeQuery->SetInternalParams($aQueryParams);
					unset($aQueryParams);

					// - Checking if query result is null (which means that the user has no right to view this specific object)
					$oSet = new DBObjectSet($oScopeQuery);
					if ($oSet->Count() === 0)
					{
						// Updating cache
						static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass][$sObjectId] = false;

						IssueLog::Debug($sDebugTracePrefix.' as there was no result for the following scope query : '.$oScopeQuery->ToOQL(true), LogChannels::PORTAL);
						return false;
					}

					// Updating cache
					static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass][$sObjectId] = true;
				}
			}
		}

		// Checking reading security layer. The object could be listed, check if it is actually allowed to view it
		if (UserRights::IsActionAllowed($sObjectClass, $sAction) == UR_ALLOWED_NO)
		{
			// For security reasons, we don't want to give the user too many information on why he cannot access the object.
			//throw new SecurityException('User not allowed to view this object', array('class' => $sObjectClass, 'id' => $sObjectId));
			IssueLog::Debug($sDebugTracePrefix.' as the user is not allowed to access this object according to the datamodel security (cf. Console settings)', LogChannels::PORTAL);
			return false;
		}

		return true;
	}

	/**
	 * @param string            $sStimulusCode
	 * @param string            $sObjectClass
	 * @param \DBObjectSet|null $oInstanceSet
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function IsStimulusAllowed($sStimulusCode, $sObjectClass, $oInstanceSet = null)
	{
		// Checking DataModel layer
		$aStimuliFromDatamodel = Metamodel::EnumStimuli($sObjectClass);
		$iActionAllowed = (get_class($aStimuliFromDatamodel[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sObjectClass, $sStimulusCode, $oInstanceSet) : UR_ALLOWED_NO;
		if (($iActionAllowed === false) || ($iActionAllowed === UR_ALLOWED_NO))
		{
			return false;
		}

		// Checking portal security layer
		$aStimuliFromPortal = $this->oLifecycleValidator->GetStimuliForProfiles(UserRights::ListProfiles(), $sObjectClass);
		if (!in_array($sStimulusCode, $aStimuliFromPortal))
		{
			return false;
		}

		return true;
	}

	/**
	 * Preloads scope objects cache with objects from $oQuery
	 *
	 * @param \DBSearch $oSearch
	 * @param array     $aExtKeysToPreload
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function PreloadForCache(DBSearch $oSearch, $aExtKeysToPreload = null)
	{
		$sObjectClass = $oSearch->GetClass();
		$aObjectIds = array();
		$aExtKeysIds = array();
		$aColumnsToLoad = array();

		if ($aExtKeysToPreload !== null)
		{
			foreach ($aExtKeysToPreload as $sAttCode)
			{
				/** @var \AttributeDefinition $oAttDef */
				$oAttDef = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
				if ($oAttDef->IsExternalKey())
				{
					/** @var \AttributeExternalKey $oAttDef */
					$aExtKeysIds[$oAttDef->GetTargetClass()] = array();
					$aColumnsToLoad[] = $sAttCode;
				}
			}
		}

		// Retrieving IDs of all objects
		// Note: We have to clone $oSet otherwise the source object will be modified
		$oSet = new DBObjectSet($oSearch);
		$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => $aColumnsToLoad));
		while ($oCurrentRow = $oSet->Fetch())
		{
			// Note: By presetting value to false, it is quicker to find which objects where not returned by the scope query later
			$aObjectIds[$oCurrentRow->GetKey()] = false;

			// Preparing ExtKeys to preload
			foreach ($aColumnsToLoad as $sAttCode)
			{
				$iExtKey = $oCurrentRow->Get($sAttCode);
				if ($iExtKey > 0)
				{
					/** @var \AttributeExternalKey $oAttDef */
					$oAttDef = MetaModel::GetAttributeDef($sObjectClass, $sAttCode);
					if (!in_array($iExtKey, $aExtKeysIds[$oAttDef->GetTargetClass()]))
					{
						$aExtKeysIds[$oAttDef->GetTargetClass()][] = $iExtKey;
					}
				}
			}
		}

		foreach (array(UR_ACTION_READ, UR_ACTION_MODIFY) as $sScopeAction)
		{
			// Retrieving scope query
			/** @var DBSearch $oScopeQuery */
			$oScopeQuery = $this->oScopeValidator->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sObjectClass, $sScopeAction);
			if ($oScopeQuery !== null)
			{
				// Restricting scope if specified
				if (!empty($aObjectIds))
				{
					$oScopeQuery->AddCondition('id', array_keys($aObjectIds), 'IN');
				}

				// Preparing object set
				$oScopeSet = new DBObjectSet($oScopeQuery);
				$oScopeSet->OptimizeColumnLoad(array($oScopeQuery->GetClassAlias() => array()));

				// Checking objects status
				$aScopeObjectIds = $aObjectIds;
				while ($oCurrentRow = $oScopeSet->Fetch())
				{
					$aScopeObjectIds[$oCurrentRow->GetKey()] = true;
				}

				// Updating cache
				if (!isset(static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass]))
				{
					static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass] = $aScopeObjectIds;
				}
				else
				{
					static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass] = array_merge_recursive(static::$aAllowedScopeObjectsCache[$sScopeAction][$sObjectClass], $aScopeObjectIds);
				}
			}
		}

		// Preloading ExtKeys
		foreach ($aExtKeysIds as $sTargetClass => $aTargetIds)
		{
			if (!empty($aTargetIds))
			{
				$oTargetSearch = new DBObjectSearch($sTargetClass);
				$oTargetSearch->AddCondition('id', $aTargetIds, 'IN');

				static::PreloadForCache($oTargetSearch);
			}
		}
	}
}
