<?php

/**
 * Various atomic change operations, to be tracked 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class CMDBChangeOp extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "change operation",
			"description" => "Change operations tracking",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop",
			"db_key_field" => "id",
			"db_finalclass_field" => "optype",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("label"=>"change", "description"=>"change", "allowed_values"=>null, "sql"=>"changeid", "targetclass"=>"CMDBChange", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("label"=>"date", "description"=>"date and time of the change", "allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"date")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userinfo", array("label"=>"user", "description"=>"who made this change", "allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"userinfo")));
		MetaModel::Init_AddAttribute(new AttributeString("objclass", array("label"=>"object class", "description"=>"object class", "allowed_values"=>null, "sql"=>"objclass", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("objkey", array("label"=>"object id", "description"=>"object id", "allowed_values"=>null, "sql"=>"objkey", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("objclass");
		MetaModel::Init_AddFilterFromAttribute("objkey");
		MetaModel::Init_AddFilterFromAttribute("date");
		MetaModel::Init_AddFilterFromAttribute("userinfo");
	}
}



/**
 * Record the creation of an object  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class CMDBChangeOpCreate extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "object creation",
			"description" => "Object creation tracking",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_create",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_InheritFilters();
	}
}


/**
 * Record the deletion of an object 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class CMDBChangeOpDelete extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "object deletion",
			"description" => "Object deletion tracking",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_delete",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_InheritFilters();
	}
}


/**
 * Record the modification of an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class CMDBChangeOpSetAttribute extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "object change",
			"description" => "Object properties change tracking",
			"key_type" => "",
			"key_label" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("label"=>"Attribute", "description"=>"code of the modified property", "allowed_values"=>null, "sql"=>"attcode", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("newvalue", array("label"=>"New value", "description"=>"new value of the attribute", "allowed_values"=>null, "sql"=>"newvalue", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("attcode");
		MetaModel::Init_AddFilterFromAttribute("newvalue");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode', 'newvalue')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode', 'newvalue')); // Attributes to be displayed for a list
	}
}

?>
