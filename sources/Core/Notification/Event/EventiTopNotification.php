<?php

/*
 *  Can't use namespaces in iTop objects (yet)
namespace  Combodo\iTop\Core\Notification\Event;

use AttributeDateTime;
use AttributeEnum;
use AttributeExternalKey;
use AttributeImage;
use AttributeTemplateString;
use AttributeURL;
use EventNotification;
use MetaModel;
use ValueSetEnum;
**/

class EventiTopNotification extends EventNotification
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb,view_in_gui",
			"key_type"            => "autoincrement",
			"name_attcode"        => "",
			"state_attcode"       => "",
			"reconc_keys"         => array(''),
			"db_table"            => "priv_event_itop_notif",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeTemplateString("title", array("allowed_values" => null, "sql" => "title", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeImage("icon", array("sql" => 'icon', "is_null_allowed" => true, "default_value" => '', "allowed_values" => null, "depends_on" => array(), "always_load_in_tables" => false)));
		MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("allowed_values" => new ValueSetEnum('1,2,3,4'), "sql" => "priority", "default_value" => '1', "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeURL("url", array("allowed_values" => null, "sql" => "url", "default_value" => null, "is_null_allowed" => false, "depends_on" => array(), "target" => "_blank")));
		MetaModel::Init_AddAttribute(new AttributeEnum("read", array("allowed_values" => new ValueSetEnum('yes,no'), "sql" => "read", "default_value" => 'no', "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("read_date", array("allowed_values"=>null, "sql"=>"read_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id",array("targetclass" => 'Contact', "allowed_values" => null, "sql" => "contact_id", "default_value" => null, "is_null_allowed" => false, "depends_on" => array(), "always_load_in_tables" => false, "on_target_delete" => DEL_SILENT, "tracking_level" => ATTRIBUTE_TRACKING_NONE,)));

	}
}