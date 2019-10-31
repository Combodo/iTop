<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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


/**
 * Class ItopCounter
 *
 */
final class ItopCounter
{
	/**
	 * Key based counter.
	 * The counter is protected against concurrency script.
	 *
	 * @param $sCounterName
	 * @param null|callable $oNewObjectValueProvider optional callable that must return an integer. Used when no key is found
	 *
	 * @return int the counter starting at
	 *  * `0` when no $oNewObjectValueProvider is given (or null)
	 *  * `$oNewObjectValueProvider() + 1` otherwise
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreOqlMultipleResultsForbiddenException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function Inc($sCounterName, $oNewObjectValueProvider = null)
	{
		$sSelfClassName = self::class;
		$sMutexKeyName = "{$sSelfClassName}-{$sCounterName}";
		$oiTopMutex = new iTopMutex($sMutexKeyName);
		$oiTopMutex->Lock();

		$oFilter = DBObjectSearch::FromOQL('SELECT KeyValueStore WHERE key_name=:key_name AND namespace=:namespace', array(
			'key_name'  => $sCounterName,
			'namespace' => $sSelfClassName,
		));
		$oCounter = $oFilter->GetFirstResult();
		if (is_null($oCounter))
		{
			if (null != $oNewObjectValueProvider)
			{
				$iComputedValue = $oNewObjectValueProvider();
			}
			else
			{
				$iComputedValue = 0;
			}
			$oCounter = MetaModel::NewObject('KeyValueStore', array(
				'key_name'  => $sCounterName,
				'value'     => $iComputedValue,
				'namespace' => $sSelfClassName,
			));
		}

		$iCurrentValue = (int) $oCounter->Get('value');
		$iCurrentValue++;

		$oCounter->Set('value', $iCurrentValue);
		$oCounter->DBWrite();

		$oiTopMutex->Unlock();

		return $iCurrentValue;
	}

	/**
	 * handle a counter for the root class of given $sLeafClass.
	 * If no counter exist initialize it with the `max(id) + 1`
	 *
	 *
	 *
	 * @param $sLeafClass
	 *
	 * @return int
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreOqlMultipleResultsForbiddenException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function IncClass($sLeafClass)
	{
		$sRootClass = MetaModel::GetRootClass($sLeafClass);

		$oNewObjectCallback = function() use ($sRootClass)
		{
			$sRootTable = MetaModel::DBGetTable($sRootClass);
			$sIdField = MetaModel::DBGetKey($sRootClass);

			return CMDBSource::QueryToScalar("SELECT max(`$sIdField`) FROM `$sRootTable`");
		};

		return self::Inc($sRootClass, $oNewObjectCallback);
	}
}



/**
 * Persistent classes for a CMDB
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class KeyValueStore extends DBObject
{
	public static function Init()
	{
		$aParams = array(
			'category' => 'bizmodel',
			'key_type' => 'autoincrement',
			'name_attcode' => array('key_name'),
			'state_attcode' => '',
			'reconc_keys' => array(''),
			'db_table' => 'key_value_store',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
			'indexes' => array (
				array (
					0 => 'key_name',
					1 => 'namespace',
				),
			),);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("namespace", array("allowed_values"=>null, "sql"=>'namespace', "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("key_name", array("allowed_values"=>null, "sql"=>'key_name', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("value", array("allowed_values"=>null, "sql"=>'value', "default_value"=>'0', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));

		MetaModel::Init_SetZListItems('details', array (
			0 => 'key_name',
			1 => 'value',
			2 => 'namespace',
		));
		MetaModel::Init_SetZListItems('standard_search', array (
			0 => 'key_name',
			1 => 'value',
			2 => 'namespace',
		));
		MetaModel::Init_SetZListItems('list', array (
			0 => 'key_name',
			1 => 'value',
			2 => 'namespace',
		));
		;
	}


}