<?php

require_once('../core/MyHelpers.class.inc.php');
require_once('../core/cmdbobject.class.inc.php');

/**
 * business_test.class.inc.php
 * User defined objects, for unit testing 
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

MetaModel::RegisterRelation("Potes", array("description"=>"ceux dont l'email ressemble au mien", "verb_down"=>"est pote de", "verb_up"=>"est pote de"));


MetaModel::RegisterZList("list1", array("description"=>"une premiere list, just for fun", "type"=>"attributes"));
MetaModel::RegisterZList("list2", array("description"=>"la secunda e meliora", "type"=>"attributes"));
MetaModel::RegisterZList("list3", array("description"=>"la variante qui tue", "type"=>"filters"));


/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbObjectHomeMade extends cmdbObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "anyObject",
			"description" => "std object",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(""),
			"db_table" => "",
			"db_key_field" => "",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
	}

	public static function GetRelationQueries($sRelCode)
	{
		//trigger_error("GetRelationQueries: cmdbObjectHomeMade");
		switch ($sRelCode)
		{
		case "Potes":
			$aRels = array("xxxx" => array("sQuery"=>"cmdbContact: pkey = 40", "bPropagate"=>true, "iDistance"=>3));
			return $aRels;
		}
	}
}


/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbContact extends cmdbObjectHomeMade
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "Contact",
			"description" => "Un object que l'on peut communiquer avec",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "etat",
			"reconc_keys" => array("name"),
			"db_table" => "contact",
			"db_key_field" => "contactid",
			"db_finalclass_field" => "actualclass",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("etat", array("label"=>"l'etat", "description"=>"les etats d'ame d'eric", "allowed_values"=>new ValueSetEnum('justborn, 15, 21'), "sql"=>"etat", "default_value"=>"justborn", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"nom", "description"=>"ze equipe", "allowed_values"=>null, "sql"=>"name", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("email", array("label"=>"iMaile", "description"=>"imelle", "allowed_values"=>null, "sql"=>"email", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("owner", array("label"=>"owned by", "description"=>"organization owning the team", "allowed_values"=>null, "sql"=>"ownerorg", "targetclass"=>"cmdbOrga", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ownername", array("label"=>"owned by", "description"=>"name of organization owning the team", "allowed_values"=>null, "extkey_attcode"=>"owner", "target_attcode"=>"_name_")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ownertnut", array("label"=>"owntnut", "description"=>"blah tnut blah", "allowed_values"=>null, "extkey_attcode"=>"owner", "target_attcode"=>"_dunsnumber_")));

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("myworkshops", array("label"=>"held workshops", "description"=>"blah tnut blah", "depends_on"=>array(), "linked_class"=>"cmdbLiens", "ext_key_to_me"=>"tocontact", "count_min"=>1, "count_max"=>10, "allowed_values"=>null)));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("owner");
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("ownername");

		MetaModel::Init_SetZListItems("list1", array("name", "email"));
		MetaModel::Init_SetZListItems("list2", array());
		MetaModel::Init_SetZListItems("list3", array("ownername"));

		MetaModel::Init_DefineState("justborn", array("label"=>"just born", "description"=>"too young to die", "attribute_inherit"=>null, "attribute_list"=>array("owner"=>OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("15", array("label"=>"student", "description"=>"stupid age", "attribute_inherit"=>"justborn", "attribute_list"=>array("owner"=>OPT_ATT_MUSTPROMPT, "email"=>OPT_ATT_MUSTPROMPT)));
		MetaModel::Init_DefineState("21", array("label"=>"old", "description"=>"one foot in the grave", "attribute_inherit"=>"15", "attribute_list"=>array("email"=>OPT_ATT_READONLY|OPT_ATT_MUSTCHANGE)));

		MetaModel::Init_DefineStimulus("toschool", new StimulusUserAction(array("label"=>"go to school", "description"=>"start learning stupid things")));
		MetaModel::Init_DefineStimulus("raise", new StimulusUserAction(array("label"=>"grow!", "description"=>"eat tons of BigMACs")));

		MetaModel::Init_DefineTransition("justborn", "toschool", array("target_state"=>"15", "actions"=>array('MyLifecycleHandler', 'MyLifecycleHandler2'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("15", "raise", array("target_state"=>"21", "actions"=>null, "user_restriction"=>null));
	}

	public static function GetRelationQueries($sRelCode)
	{
		//trigger_error("GetRelationQueries: cmdbContact");
		switch ($sRelCode)
		{
		case "Potes":
			$aRels = array(
				"zz1" => array("sQuery"=>"cmdbContact: name Begins with '\$[this.name::]' AND pkey != \$[this.pkey::]", "bPropagate"=>false, "iDistance"=>3),
				"zz2" => array("sQuery"=>"cmdbContact: owner = \$[this.owner::] AND owner != 2", "bPropagate"=>false, "iDistance"=>3),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
		}
	}

	public function MyLifecycleHandler($sStimulusCode)
	{
		echo "<p>youhou!</p>";
		return true;
	}
	public function MyLifecycleHandler2($sStimulusCode)
	{
		echo "<p>... les papous...</p>";
		return true;
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbTeam extends cmdbContact
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "Equipado",
			"description" => "Un regroupement de gens",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "email",
			"state_attcode" => "",
			"reconc_keys" => array("email"),
			"db_table" => "team",
			"db_key_field" => "teamid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_OverloadAttributeParams("email", array("label"=>"email2", "description"=>"emailleu22"));
		MetaModel::Init_AddAttribute(new AttributeInteger("headcount", array("label"=>"nombre", "description"=>"combien ils sont", "allowed_values"=>null, "sql"=>"headcount", "default_value"=>654321, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("headcount");

		MetaModel::Init_SetZListItems("noneditable", array("name"));
	}

	public function ComputeValues()
	{
		//echo "Set(), function ComputeValues has been found for ".get_class($this)."<br/>\n";
		$this->Set("name", $this->Get("email")." and ".$this->Get("headcount"));
	}

	public static function GetRelationQueries($sRelCode)
	{
		//trigger_error("GetRelationQueries: cmdbTeam");
		switch ($sRelCode)
		{
		case "Potes":
			//$aRels = array("Relies on" => array("sQuery"=>"cmdbContact: name Begins with 'Louis'", "bPropagate"=>false, "iDistance"=>3));
			return array_merge(array(), parent::GetRelationQueries($sRelCode));
		}
	}
}


/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbOrga extends cmdbObjectHomeMade
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "Organization",
			"description" => "Une entite qui possede des choses",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "_name_",
			"state_attcode" => "",
			"reconc_keys" => array("_name_"),
			"db_table" => "organization",
			"db_key_field" => "orgid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("_name_", array("label"=>"namo", "description"=>"official company name", "allowed_values"=>null, "sql"=>"name", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("_status_", array("label"=>"step", "description"=>"step or status, etc.", "allowed_values"=>null, "sql"=>"status", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("_dunsnumber_", array("label"=>"duns debile number", "description"=>"une bonne idee a OVSD", "allowed_values"=>null, "sql"=>"dunsnumber", "default_value"=>99007, "is_null_allowed"=>false, "depends_on"=>array())));
// not yet allowed		MetaModel::Init_AddAttribute(new AttributeInteger("_dunsnumberBY2_", array("label"=>"dummy duns", "description"=>"deux fois plus debile", "allowed_values"=>null, "sql"=>"dunsnumber * 3.141592654")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("_name_");

		MetaModel::Init_SetZListItems("list1", array("_status_"));
		MetaModel::Init_SetZListItems("list2", array());
		MetaModel::Init_SetZListItems("list3", array("_name_"));
	}

}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbLiens extends cmdbObjectHomeMade
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "Liens_entre_contacts_et_workshop",
			"description" => "Une entite qui lie des contacts et workshops",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "function",
			"state_attcode" => "",
			"reconc_keys" => array("function"),
			"db_table" => "role_ws",
			"db_key_field" => "linkid",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("function", array("label"=>"fonction", "description"=>"la fonction...", "allowed_values"=>null, "sql"=>"function", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("a1", array("label"=>"a1", "description"=>"a1", "allowed_values"=>null, "sql"=>"a1", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("a2", array("label"=>"a1", "description"=>"a2", "allowed_values"=>null, "sql"=>"a2", "default_value"=>"XXXX", "is_null_allowed"=>true, "depends_on"=>array())));

		// What makes it being a link...
		MetaModel::Init_AddAttribute(new AttributeExternalKey("toworkshop", array("label"=>"participates in", "description"=>"workshop in wich the person is participating", "allowed_values"=>null, "sql"=>"ws_id", "targetclass"=>"cmdbWorkshop", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ws_info", array("label"=>"name", "description"=>"namedescription", "allowed_values"=>null, "extkey_attcode"=>"toworkshop", "target_attcode"=>"namitus")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("tocontact", array("label"=>"is held by", "description"=>"people involved in that mess", "allowed_values"=>null, "sql"=>"contactid", "targetclass"=>"cmdbContact", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_info", array("label"=>"name", "description"=>"namedescription", "allowed_values"=>null, "extkey_attcode"=>"tocontact", "target_attcode"=>"name")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("function");

		MetaModel::Init_SetZListItems("list1", array("toworkshop", "contact_info"));
		MetaModel::Init_SetZListItems("list2", array("function"));
		MetaModel::Init_SetZListItems("list3", array("function"));
	}

	public static function GetRelationQueries($sRelCode)
	{
		throw new CoreException("GetRelationQueries: cmdbLiens");
		return array("Relies on" => array("sQuery"=>"", "bPropagate"=>true, "iDistance"=>3));
	}
}

/**
 * blah blah 
 *
 * @package     iTopUnitTests
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class cmdbWorkshop extends cmdbObjectHomeMade
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "blah",
			"name" => "Workshop",
			"description" => "Une entite qui pond des theories insensees",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "namitus",
			"state_attcode" => "",
			"reconc_keys" => array("namitus"),
			"db_table" => "workshop",
			"db_key_field" => "ws_id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("namitus", array("label"=>"namo", "description"=>"nom imbitique pour pondeurs de debilites", "allowed_values"=>null, "sql"=>"name", "default_value"=>"XXXX", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("namitus");

		MetaModel::Init_SetZListItems("list1", array("namitus"));
		MetaModel::Init_SetZListItems("list2", array());
		MetaModel::Init_SetZListItems("list3", array("namitus"));
	}

	public static function GetRelationQueries($sRelCode)
	{
		throw new CoreException("GetRelationQueries: cmdbWorkshop");
		return array("Relies on" => array("sQuery"=>"", "bPropagate"=>true, "iDistance"=>3));
	}
}


?>
