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
 * Database properties - manage database instances in a complex installation
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


/**
 * A database property 
 *
 * @package     iTopORM
 */
class DBProperty extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "cloud",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_db_properties",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("change_date", array("allowed_values"=>null, "sql"=>"change_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("change_comment", array("allowed_values"=>null, "sql"=>"change_comment", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
	}

	/**
	 * Helper to check wether the table has been created into the DB 
	 * (this table did not exist in 1.0.1 and older versions)
	 */
	public static function IsInstalled()
	{
		$sTable = MetaModel::DBGetTable(__CLASS__);
		if (CMDBSource::IsTable($sTable))
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}

	public static function SetProperty($sName, $sValue, $sComment = '', $sDescription = null)
	{
		try
		{
			$oSearch = DBObjectSearch::FromOQL('SELECT DBProperty WHERE name = :name');
			$oSet = new DBObjectSet($oSearch, array(), array('name' => $sName));
			if ($oSet->Count() == 0)
			{
				$oProp = new DBProperty();
				$oProp->Set('name', $sName);
				$oProp->Set('description', $sDescription);
				$oProp->Set('value', $sValue);
				$oProp->Set('change_date', time());
				$oProp->Set('change_comment', $sComment);
				$oProp->DBInsert();
			}
			elseif ($oSet->Count() == 1)
			{
				$oProp = $oSet->fetch();
				if (!is_null($sDescription))
				{
					$oProp->Set('description', $sDescription);
				}
				$oProp->Set('value', $sValue);
				$oProp->Set('change_date', time());
				$oProp->Set('change_comment', $sComment);
				$oProp->DBUpdate();
			}
			else
			{
				// Houston...
				throw new CoreException('duplicate db property');
			}
		}
		catch (MySQLException $e)
		{
			// This might be because the table could not be found,
			// let's check it and discard silently if this is really the case
			if (self::IsInstalled())
			{
				throw $e;
			}
			IssueLog::Error('Attempting to write a DBProperty while the module has not been installed');
		}
	}

	public static function GetProperty($sName, $default = null)
	{
		try
		{
			$oSearch = DBObjectSearch::FromOQL('SELECT DBProperty WHERE name = :name');
			$oSet = new DBObjectSet($oSearch, array(), array('name' => $sName));
			$iCount = $oSet->Count();
			if ($iCount == 0)
			{
				//throw new CoreException('unknown db property', array('name' => $sName));
				$sValue = $default;
			}
			elseif ($iCount == 1)
			{
				$oProp = $oSet->fetch();
				$sValue = $oProp->Get('value');
			}
			else
			{
				// $iCount > 1
				// Houston...
				throw new CoreException('duplicate db property', array('name' => $sName, 'count' => $iCount));
			}
		}
		catch (MySQLException $e)
		{
			// This might be because the table could not be found,
			// let's check it and discard silently if this is really the case
			if (self::IsInstalled())
			{
				throw $e;
			}
			$sValue = $default;
		}
		return $sValue;
	}
}

?>
