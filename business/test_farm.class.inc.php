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
 * Persistent classes for core automated tests
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

///////////////////////////////////////////////////////////////////////////////
// Business implementation demo
///////////////////////////////////////////////////////////////////////////////

//todo MetaModel::RegisterRelation("Potes");


//todo MetaModel::RegisterZList("list1", array("description"=>"une premiere list, just for fun", "type"=>"attributes"));
//todo MetaModel::RegisterZList("list2", array("description"=>"la secunda e meliora", "type"=>"attributes"));
//todo MetaModel::RegisterZList("list3", array("description"=>"la variante qui tue", "type"=>"filters"));


class Animal extends cmdbObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(""),
			"db_table" => "animals",
			"db_key_field" => "animalid",
			"db_finalclass_field" => "actualclass",
		);
		MetaModel::Init_Params($aParams);

		MetaModel::Init_AddAttribute(new AttributeEnum("sex", array("allowed_values"=>new ValueSetEnum('male, female'), "sql"=>"sex", "default_value"=>"male", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("species", array("allowed_values"=>null, "sql"=>"species", "default_value"=>"xxx", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("speed", array("allowed_values"=>null, "sql"=>"speed", "default_value"=>4, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("mother", array("allowed_values"=>null, "sql"=>"mother", "targetclass"=>"Animal", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("father", array("allowed_values"=>null, "sql"=>"father", "targetclass"=>"Animal", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
	}
}


class Mammal extends Animal
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "mammals",
			"db_key_field" => "mammalid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"xxx", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("height", array("allowed_values"=>null, "sql"=>"height", "default_value"=>1, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("birth", array("allowed_values"=>null, "sql"=>"birth", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("member", array("allowed_values"=>null, "sql"=>"member", "targetclass"=>"Group", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
	}
}

class Bird extends Animal
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "birds",
			"db_key_field" => "birdid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_OverloadAttributeParams("species", array("allowed_values"=>array('geese', 'rooster', 'chicken', 'turckey', 'pie', 'corbeau')));
	}
}

class WalkingBird extends Bird
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "walkingbirds",
			"db_key_field" => "walkingbirdid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_OverloadAttributeParams("species", array("allowed_values"=>array('geese', 'rooster', 'chicken', 'turckey')));
	}
}

class FlyingBird extends Bird
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "flyingbirds",
			"db_key_field" => "flyingbirdid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_OverloadAttributeParams("species", array("allowed_values"=>array('pie', 'corbeau')));
		MetaModel::Init_AddAttribute(new AttributeInteger("flyingspeed", array("allowed_values"=>null, "sql"=>"headcount", "default_value"=>10, "is_null_allowed"=>false, "depends_on"=>array())));
	}
}

class AnimalRelation extends cmdbObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "a2a",
			"db_key_field" => "linkid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// What makes it being a link...
		MetaModel::Init_AddAttribute(new AttributeExternalKey("animal1", array("allowed_values"=>null, "sql"=>"a1", "targetclass"=>"Animal", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("animal2", array("allowed_values"=>null, "sql"=>"a2", "targetclass"=>"Animal", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
	}
}


class EaterToEaten extends AnimalRelation
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "eatertoeaten",
			"db_key_field" => "eatertoeatonid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("DeadOrAlive", array("allowed_values"=>new ValueSetEnum('dead, fresh, cooked'), "sql"=>"deadoralive", "default_value"=>"fresh", "is_null_allowed"=>false, "depends_on"=>array())));
	}
}

class Group extends cmdbObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "groups",
			"db_key_field" => "groupid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"xxx", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("leader", array("allowed_values"=>null, "sql"=>"leader", "targetclass"=>"Mammal", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("leader_name", array("allowed_values"=>null, "extkey_attcode"=> 'leader', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("leader_speed", array("allowed_values"=>null, "extkey_attcode"=> 'leader', "target_attcode"=>"speed")));
	}
}


?>
