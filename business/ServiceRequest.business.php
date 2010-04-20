<?php



////////////////////////////////////////////////////////////////////////////////////
/**
* Description of a service request
* 
*/
////////////////////////////////////////////////////////////////////////////////////
class bizServiceRequest extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ServiceRequest",
			"description" => "Service request",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_id", "name"), // inherited attributes
			"db_table" => "serviceRequests",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/serviceRequest.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum("Open,approved,rejected,assigned,pending,closed"), "sql"=>"status", "default_value"=>"Open", "is_null_allowed"=>false, "depends_on"=>array())));
 	  MetaModel::Init_AddAttribute(new AttributeExternalKey("requester_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>null, "sql"=>"requester_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requester_mail", array("allowed_values"=>null, "extkey_attcode"=> 'requester_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"priority", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source", array("allowed_values"=>new ValueSetEnum("phone,E-mail,Fax"), "sql"=>"source", "default_value"=>"phone", "is_null_allowed"=>false, "depends_on"=>array())));

  	MetaModel::Init_AddAttribute(new AttributeExternalKey("coordinator_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"coordinator_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("coordinator_name", array("allowed_values"=>null, "extkey_attcode"=> 'coordinator_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("coordinator_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("org_id");
		MetaModel::Init_AddFilterFromAttribute("requester_id");
		MetaModel::Init_AddFilterFromAttribute("priority");
		MetaModel::Init_AddFilterFromAttribute("coordinator_id");
		MetaModel::Init_AddFilterFromAttribute("agent_id");
		MetaModel::Init_AddFilterFromAttribute("status");
		MetaModel::Init_AddFilterFromAttribute("source");


/*
		// Life cycle
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Implementation", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
	
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_implement", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_move2prod", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_obsololete", array()));

		MetaModel::Init_DefineTransition("New", "ev_implement", array("target_state"=>"Implementation", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implementation", "ev_move2prod", array("target_state"=>"Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_obsolete", array("target_state"=>"Obsolete", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
*/	

		MetaModel::Init_SetZListItems('details', array('name', 'title','status', 'org_id','priority','requester_id','description','source','coordinator_id','agent_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'title','status', 'org_id','priority')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status','org_id','priority','requester_id','source','coordinator_id','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status','org_id','priority','requester_id','source','coordinator_id','agent_id')); // Criteria of the advanced search form

	}
	
	// State machine actions
	public function IncrementVersion($sStimulusCode)
	{
		$this->Set('version_number', $this->Get('version_number') + 1);
		return true;
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* Description of a service item
* 
*/
////////////////////////////////////////////////////////////////////////////////////
class bizServiceItem extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ServiceItem",
			"description" => "Service Item",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("request_id", "name"), // inherited attributes
			"db_table" => "serviceItems",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeExternalKey("request_id", array("targetclass"=>"bizServiceRequest", "allowed_values"=>null, "sql"=>"request_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("request_name", array("allowed_values"=>null, "extkey_attcode"=> 'request_id', "target_attcode"=>"name")));
	  MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
  	MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum("Open,approved,rejected,assigned,pending,closed"), "sql"=>"status", "default_value"=>"Open", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("priority", array("allowed_values"=>null, "extkey_attcode"=> 'request_id', "target_attcode"=>"priority")));
		MetaModel::Init_AddAttribute(new AttributeText("comment", array("allowed_values"=>null, "sql"=>"comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
  	MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("workgroup_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("request_id");
		MetaModel::Init_AddFilterFromAttribute("contract_id");
		MetaModel::Init_AddFilterFromAttribute("priority");
		MetaModel::Init_AddFilterFromAttribute("workgroup_id");
		MetaModel::Init_AddFilterFromAttribute("agent_id");
		MetaModel::Init_AddFilterFromAttribute("status");
		MetaModel::Init_AddFilterFromAttribute("priority");


/*
		// Life cycle
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Implementation", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
	
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_implement", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_move2prod", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_obsololete", array()));

		MetaModel::Init_DefineTransition("New", "ev_implement", array("target_state"=>"Implementation", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implementation", "ev_move2prod", array("target_state"=>"Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_obsolete", array("target_state"=>"Obsolete", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
*/	

		MetaModel::Init_SetZListItems('details', array('name', 'title','request_id','contract_id','status','priority','description','comment','workgroup_id','agent_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'title','status', 'contract_id','priority')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name','request_id','contract_id','status','priority','workgroup_id','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name','request_id','contract_id','status','priority','workgroup_id','agent_id')); // Criteria of the advanced search form

	}
	

}





?>
