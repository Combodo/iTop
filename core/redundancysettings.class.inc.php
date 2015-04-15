<?php
// Copyright (C) 2015 Combodo SARL
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

/**
 * Persistent classes (internal): user settings for the redundancy
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Redundancy settings
 *
 * @package     iTopORM
 */
class RedundancySettings extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => array('relation_code','from_class','neighbour','objkey'),
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_redundancy_settings",
			"db_key_field" => "id",
			"db_finalclass_field" => "finalclass",
			"display_template" => "",
			'indexes' => array(
				array('relation_code', 'from_class', 'neighbour', 'objclass', 'objkey'),
			)
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("relation_code", array("allowed_values"=>null, "sql"=>"relation_code", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("from_class", array("allowed_values"=>null, "sql"=>"from_class", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("neighbour", array("allowed_values"=>null, "sql"=>"neighbour", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("objclass", array("allowed_values"=>null, "sql"=>"objclass", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeObjectKey("objkey", array("allowed_values"=>null, "class_attcode"=>"objclass", "sql"=>"objkey", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeBoolean("enabled", array("allowed_values"=>null, "sql"=>"enabled", "default_value"=>false, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeEnum("min_up_type", array("allowed_values"=>new ValueSetEnum('count,percent'), "sql"=>"min_up_type", "default_value"=>"count", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("min_up_count", array("allowed_values"=>null, "sql"=>"min_up_count", "default_value"=>1, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("min_up_percent", array("allowed_values"=>null, "sql"=>"min_up_percent", "default_value"=>50, "is_null_allowed"=>true, "depends_on"=>array())));
	}

	public static function MakeDefault($sRelCode, $aQueryInfo, $oToObject)
	{
		$oRet = MetaModel::NewObject('RedundancySettings');
		$oRet->Set('relation_code', $sRelCode);
		$oRet->Set('from_class', $aQueryInfo['sFromClass']);
		$oRet->Set('neighbour', $aQueryInfo['sNeighbour']);
		$oRet->Set('objclass', get_class($oToObject));
		$oRet->Set('objkey', $oToObject->GetKey());
		$oRet->Set('enabled', $aQueryInfo['bRedundancyEnabledValue']);
		$oRet->Set('min_up_type', $aQueryInfo['sRedundancyMinUpType']);
		$oRet->Set('min_up_count', ($aQueryInfo['sRedundancyMinUpType'] == 'count') ? $aQueryInfo['iRedundancyMinUpValue'] : 1);
		$oRet->Set('min_up_percent', ($aQueryInfo['sRedundancyMinUpType'] == 'percent') ? $aQueryInfo['iRedundancyMinUpValue'] : 50);
		return $oRet;
	}

	public static function GetSettings($sRelCode, $aQueryInfo, $oToObject)
	{
		$oSearch = new DBObjectSearch('RedundancySettings');
		$oSearch->AddCondition('relation_code', $sRelCode, '=');
		$oSearch->AddCondition('from_class', $aQueryInfo['sFromClass'], '=');
		$oSearch->AddCondition('neighbour', $aQueryInfo['sNeighbour'], '=');
		$oSearch->AddCondition('objclass', get_class($oToObject), '=');
		$oSearch->AddCondition('objkey', $oToObject->GetKey(), '=');

		$oSet = new DBObjectSet($oSearch);
		$oRet = $oSet->Fetch();
		if (!$oRet)
		{
			$oRet = self::MakeDefault($sRelCode, $aQueryInfo, $oToObject);
		}
		return $oRet;
	}
}
