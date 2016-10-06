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

namespace Combodo\iTop\Portal\Helper;

use \Exception;
use \Silex\Application;
use \utils;
use \UserRights;
use \Dict;
use \IssueLog;
use \MetaModel;
use \DBObjectSet;
use \FieldExpression;
use \VariableExpression;
use \BinaryExpression;
use \Combodo\iTop\Portal\Helper\ScopeValidatorHelper;

/**
 * SecurityHelper class
 *
 * Handle security checks through the different layers (portal scopes, iTop silos, user rights)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class SecurityHelper
{

	/**
	 * Returns true if the current user is allowed to do the $sAction on an $sObjectClass object (with optionnal $sObjectId id)
	 *
	 * @param Silex\Application $oApp
	 * @param string $sAction Must be in UR_ACTION_READ|UR_ACTION_MODIFY|UR_ACTION_CREATE
	 * @param string $sObjectClass
	 * @param string $sObjectId
	 * @return boolean
	 */
	public static function IsActionAllowed(Application $oApp, $sAction, $sObjectClass, $sObjectId = null)
	{
		$sDebugTracePrefix = __CLASS__ . ' / ' . __METHOD__ . ' : Returned false for action ' . $sAction . ' on ' . $sObjectClass . '::' . $sObjectId;

		// Checking action type
		if (!in_array($sAction, array(UR_ACTION_READ, UR_ACTION_MODIFY, UR_ACTION_CREATE)))
		{
			if ($oApp['debug'])
			{
				IssueLog::Info($sDebugTracePrefix . ' as the action value could not be understood (' . UR_ACTION_READ . '/' . UR_ACTION_MODIFY . '/' . UR_ACTION_CREATE . ' expected');
			}
			return false;
		}

		// Checking the scopes layer
		// - Transforming scope action as there is only 2 values
		$sScopeAction = ($sAction === UR_ACTION_READ) ? UR_ACTION_READ : UR_ACTION_MODIFY;
		// - Retrieving the query
		$oScopeQuery = $oApp['scope_validator']->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sObjectClass, $sScopeAction);
		if ($oScopeQuery === null)
		{
			if ($oApp['debug'])
			{
				IssueLog::Info($sDebugTracePrefix . ' as there was no scope defined for action ' . $sScopeAction . ' and profiles ' . implode('/', UserRights::ListProfiles()));
			}
			return false;
		}
		// - If action != create we do some additionnal checks
		if ($sAction !== UR_ACTION_CREATE)
		{
			// - Adding object id to the query if specified
			if ($sObjectId !== null)
			{
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
			}

			// - Checking if query result is null
			$oSet = new DBObjectSet($oScopeQuery);
			// Note : This is to address a bug (#R-011452). We creating an object that is the first of its class, this would failed as the scope query always return an empty set
			//if ($oSet->Count() === 0)
			if (($oSet->Count() === 0) && ($sObjectId !== null))
			{
				if ($oApp['debug'])
				{
					IssueLog::Info($sDebugTracePrefix . ' as there was no result for the following scope query : ' . $oScopeQuery->ToOQL(true));
				}
				return false;
			}

			// Checking if the cmdbAbstractObject exists if id is specified
			if ($sObjectId !== null)
			{
				$oObject = MetaModel::GetObject($sObjectClass, $sObjectId, false /* MustBeFound */, $oApp['scope_validator']->IsAllDataAllowedForScope(UserRights::ListProfiles(), $sObjectClass));
				if ($oObject === null)
				{
					if ($oApp['debug'])
					{
						IssueLog::Info($sDebugTracePrefix . ' as object doesn\'t exists');
					}
					return false;
				}
				unset($oObject);
			}
		}

		// Checking reading security layer. The object could be listed, check if it is actually allowed to view it
		if (UserRights::IsActionAllowed($sObjectClass, $sAction) == UR_ALLOWED_NO)
		{
			// For security reasons, we don't want to give the user too many informations on why he cannot access the object.
			//throw new SecurityException('User not allowed to view this object', array('class' => $sObjectClass, 'id' => $sObjectId));
			if ($oApp['debug'])
			{
				IssueLog::Info($sDebugTracePrefix . ' as the user is not allowed to access this object according to the datamodel security (cf. Console settings)');
			}
			return false;
		}

		return true;
	}

	public static function IsStimulusAllowed(Application $oApp, $sStimulusCode, $sObjectClass, $oInstanceSet = null)
	{
		$aStimuli = Metamodel::EnumStimuli($sObjectClass);
		$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sObjectClass, $sStimulusCode, $oInstanceSet) : UR_ALLOWED_NO;
	}

}

?>