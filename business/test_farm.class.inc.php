<?php


/**
 * test_farm.class.inc.php
 * User defined objects, for unit testing - SQL generation oriented (complex links) 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

///////////////////////////////////////////////////////////////////////////////
// Business implementation demo
///////////////////////////////////////////////////////////////////////////////

//todo MetaModel::RegisterRelation("Potes", array("description"=>"ceux dont l'email ressemble au mien", "verb_down"=>"est pote de", "verb_up"=>"est pote de"));


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

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("sex");
		MetaModel::Init_AddFilterFromAttribute("species");
		MetaModel::Init_AddFilterFromAttribute("speed");
		MetaModel::Init_AddFilterFromAttribute("mother");
		MetaModel::Init_AddFilterFromAttribute("father");
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

// ?		MetaModel::Init_AddAttribute(new AttributeLinkedSet("a2a", array("depends_on"=>array(), "linked_class"=>"Animal2animal", "ext_key_to_me"=>"animal1", "count_min"=>0, "count_max"=>10, "allowed_values"=>null)));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("height");
		MetaModel::Init_AddFilterFromAttribute("birth");
		MetaModel::Init_AddFilterFromAttribute("member");
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

		MetaModel::Init_InheritFilters();
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
		MetaModel::Init_InheritFilters();
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

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("flyingspeed");
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

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("animal1");
		MetaModel::Init_AddFilterFromAttribute("animal2");
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

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("DeadOrAlive");
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

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("leader");
		MetaModel::Init_AddFilterFromAttribute("leader_name");
		MetaModel::Init_AddFilterFromAttribute("leader_speed");
	}
}


?>
