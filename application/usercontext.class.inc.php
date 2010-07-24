<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class UserContext... should be obsoleted later (see notes below)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../core/cmdbobject.class.inc.php');
/**
 * Helper class to capture a user's restrictions (access rights, profiles...) as a set of limiting conditions
 *
 * **** NOW OBSOLETE *** SHOULD BE REPLACED EVERYWHERE BY UserRights *****
 * 
 *
 *
 *     
 * Usage:
 * 1) Build the user's context (from her rights, a lookup in the database, a cookie, whatever)
 * 	$oContext = new UserContext();
 *  $oContext->AddCondition('SomeClass', 'someFilter', 'SomeValue', '=');
 *   ...
 *
 * 2) Use the restrictions contained in the context when retrieving objects either when:
 * getting directly an instance of an object
 * $oObj = $oContext->GetObject('myClass', 'someKey'); // Instead of $oObj = MetaModel::GetObject('Klass', 'someKey');
 * or when building a new search filter
 * $oFilter = $oContext->NewFilter('myClass'); // Instead of $oFilter = new CMDBSearchFilter('Klass');
 */
class UserContext
{
	/**
	 * Hash array to store the restricting conditions by myClass
	 */
	protected $m_aConditions;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->m_aConditions = array();
	}
	/**
	 * Create a new search filter for the given class of objects that already contains the context's restrictions
	 */
	public function NewFilter($sClass)
	{
		return UserRights::GetFilter($sClass);
		/*
		$oFilter = new CMDBSearchFilter($sClass);
		foreach($this->m_aConditions as $sConditionClass => $aConditionList)
		{
			// Add to the filter all the conditions of the parent classes of this class
			if ($this->IsSubclass($sConditionClass,$sClass))
			{
				foreach($aConditionList as $sFilterCode => $aCondition)
				{
					$oFilter->AddCondition($sFilterCode, $aCondition['value'], $aCondition['operator']);
				}
			}
		}
		return $oFilter;
		*/
	}
	/**
	 * Retrieve an instance of an object (if allowed by the context)
	 */
	public function GetObject($sClass, $sKey)
	{
		$oObject = null;
		$oFilter = $this->NewFilter($sClass);
		$oFilter->AddCondition('id', $sKey, '=');
		$oSet = new CMDBObjectSet($oFilter);
		if ($oSet->Count() > 0)
		{
			$oObject = $oSet->Fetch();
		}
		return $oObject;
	}
	
	/**
	 * Add a restriction to the context for a given class of objects (and all its persistent subclasses)
	 */
	public function AddCondition($sClass, $sFilterCode, $value, $sOperator)
	{
		if(!isset($this->m_aConditions[$sClass]))
		{
			$this->m_aConditions[$sClass] = array();
		}
		$this->m_aConditions[$sClass][$sFilterCode] = array('value'=>$value, 'operator'=>$sOperator);
	}
	
	/**
	 * Check if a given class is a subclass of (or same as) another one
	 */
	protected function IsSubclass($sParentClass, $sSubclass)
	{
		$bResult = false;
		if ($sParentClass == $sSubclass)
		{
			$bResult = true;
		}
		else
		{
			$aParentList = MetaModel::EnumParentClasses($sSubclass);
			$bResult = in_array($sParentClass, $aParentList);
		}
		return $bResult;
	}
}
?>
