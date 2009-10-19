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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Request Ref", "description"=>"Refence number ofr this service request", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("label"=>"Title", "description"=>"Overview of the Service Request", "allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "label"=>"Customer", "description"=>"Customer for this service request", "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("label"=>"Customer", "description"=>"name of the Customer", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("label"=>"Description", "description"=>"Description of this service request", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Status of the service request", "allowed_values"=>new ValueSetEnum("Open,approved,rejected,assigned,pending,closed"), "sql"=>"status", "default_value"=>"Open", "is_null_allowed"=>false, "depends_on"=>array())));
 	  MetaModel::Init_AddAttribute(new AttributeExternalKey("requester_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Requester", "description"=>"person that trigger service request", "allowed_values"=>null, "sql"=>"requester_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requester_mail", array("label"=>"Requester", "description"=>"Person that trigger this service request", "allowed_values"=>null, "extkey_attcode"=> 'requester_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("label"=>"Priority", "description"=>"Field defining the priority for this service request", "allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"priority", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source", array("label"=>"Source", "description"=>"source type for this call", "allowed_values"=>new ValueSetEnum("phone,E-mail,Fax"), "sql"=>"source", "default_value"=>"phone", "is_null_allowed"=>false, "depends_on"=>array())));

  	MetaModel::Init_AddAttribute(new AttributeExternalKey("coordinator_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Coordinator", "description"=>"which workgroup is controlling this request", "allowed_values"=>null, "sql"=>"coordinator_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("coordinator_name", array("label"=>"Coordinator", "description"=>"name of workgroup coordinating this service request", "allowed_values"=>null, "extkey_attcode"=> 'coordinator_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Coordinator Agent", "description"=>"who is managing the ticket", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("coordinator_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("label"=>"Coordinator Agent", "description"=>"mail of agent coordinating this service request", "allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

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
		MetaModel::Init_DefineState("New", array("label"=>"New", "description"=>"Newly created service", "attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Implementation", array("label"=>"Implementing", "description"=>"The service is being worked on", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("label"=>"Production", "description"=>"The service is effective in production", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("label"=>"Obsolete", "description"=>"The service is no more deleivered", "attribute_inherit"=>null,
												"attribute_list"=>array()));
	
		MetaModel::Init_DefineStimulus("ev_implement", new StimulusUserAction(array("label"=>"Implement this service", "description"=>"This service is under construction")));
		MetaModel::Init_DefineStimulus("ev_move2prod", new StimulusUserAction(array("label"=>"Move to production", "description"=>"This service is now on production")));
		MetaModel::Init_DefineStimulus("ev_obsololete", new StimulusUserAction(array("label"=>"Obsolete", "description"=>"Thi service is no more delivered")));

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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Request Ref", "description"=>"Refence number for this service item", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("label"=>"Title", "description"=>"Overview of the Service item", "allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeExternalKey("request_id", array("targetclass"=>"bizServiceRequest", "label"=>"Service Request", "description"=>"Corresponding service request", "allowed_values"=>null, "sql"=>"request_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("request_name", array("label"=>"Service Request", "description"=>"name of the request", "allowed_values"=>null, "extkey_attcode"=> 'request_id', "target_attcode"=>"name")));
	  MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "label"=>"Service", "description"=>"Corresponding service", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("label"=>"Service Name", "description"=>"name of the service", "allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
  	MetaModel::Init_AddAttribute(new AttributeText("description", array("label"=>"Description", "description"=>"Description of this service request", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Status of the service request", "allowed_values"=>new ValueSetEnum("Open,approved,rejected,assigned,pending,closed"), "sql"=>"status", "default_value"=>"Open", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("priority", array("label"=>"Priority", "description"=>"priority corresponding to service request", "allowed_values"=>null, "extkey_attcode"=> 'request_id', "target_attcode"=>"priority")));
		MetaModel::Init_AddAttribute(new AttributeText("comment", array("label"=>"Comment", "description"=>"Comment of this service item", "allowed_values"=>null, "sql"=>"comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
  	MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Workgroup", "description"=>"which workgroup working on this service item", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("label"=>"Workgroup", "description"=>"name of workgroup working on this service item", "allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Coordinator Agent", "description"=>"who is managing the service item", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("workgroup_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("label"=>"Agent", "description"=>"mail of agent coordinating this service item", "allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

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
		MetaModel::Init_DefineState("New", array("label"=>"New", "description"=>"Newly created service", "attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Implementation", array("label"=>"Implementing", "description"=>"The service is being worked on", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("label"=>"Production", "description"=>"The service is effective in production", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("label"=>"Obsolete", "description"=>"The service is no more deleivered", "attribute_inherit"=>null,
												"attribute_list"=>array()));
	
		MetaModel::Init_DefineStimulus("ev_implement", new StimulusUserAction(array("label"=>"Implement this service", "description"=>"This service is under construction")));
		MetaModel::Init_DefineStimulus("ev_move2prod", new StimulusUserAction(array("label"=>"Move to production", "description"=>"This service is now on production")));
		MetaModel::Init_DefineStimulus("ev_obsololete", new StimulusUserAction(array("label"=>"Obsolete", "description"=>"Thi service is no more delivered")));

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
