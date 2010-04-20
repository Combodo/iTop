<?php
////////////////////////////////////////////////////////////////////////////////////
/**
* A Change Ticket
*/
////////////////////////////////////////////////////////////////////////////////////
class bizChangeTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Change",
			"description" => "Change ticket",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",  
			"state_attcode" => "ticket_status",
			"reconc_keys" => array("title"),
			"db_table" => "change_ticket",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/change.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeString("type", array("allowed_values"=>new ValueSetEnum("Routine, Normal, Emergency"), "sql"=>"type", "default_value"=>"Routine", "is_null_allowed"=>true, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeString("domain", array("allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"domain", "default_value"=>"Desktop", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("requestor_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p WHERE p.org_id = :this->org_id'), "sql"=>"requestor_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requestor_mail", array("allowed_values"=>null, "extkey_attcode"=> 'requestor_id', "target_attcode"=>"email")));

    
    MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("ticket_status", array("allowed_values"=>new ValueSetEnum("New, Validated,Rejected,Assigned,PlannedScheduled,Approved,NotApproved,Implemented,Monitored, Closed"), "sql"=>"change_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));

		MetaModel::Init_AddAttribute(new AttributeDate("creation_date", array("allowed_values"=>null, "sql"=>"creation_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    	// définir une date de défaut à maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeDate("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("close_date", array("allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('workgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisorgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"supervisorgroup_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisorgroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'supervisorgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->supervisorgroup_id'), "sql"=>"supervisor_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('supervisorgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_mail", array("allowed_values"=>null, "extkey_attcode"=> 'supervisor_id', "target_attcode"=>"email")));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("managergroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"managergroup_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("managergroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'managergroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->managergroup_id'), "sql"=>"manager_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('managergroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_mail", array("allowed_values"=>null, "extkey_attcode"=> 'manager_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeEnum("outage", array("allowed_values"=>new ValueSetEnum("Yes,No"), "sql"=>"outage", "default_value"=>"No", "is_null_allowed"=>true, "depends_on"=>array())));


		MetaModel::Init_AddAttribute(new AttributeText("change_request", array("allowed_values"=>null, "sql"=>"change_req", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("change_log", array("allowed_values"=>null, "sql"=>"change_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeText("fallback", array("allowed_values"=>null, "sql"=>"fallback", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeInteger("assignment_count", array("allowed_values"=>null, "sql"=>"assignment_count", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("linked_class"=>"lnkInfraChangeTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("title");
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("domain");
		MetaModel::Init_AddFilterFromAttribute("org_id");
		MetaModel::Init_AddFilterFromAttribute("requestor_id");
		MetaModel::Init_AddFilterFromAttribute("ticket_status");
		MetaModel::Init_AddFilterFromAttribute("creation_date");
		MetaModel::Init_AddFilterFromAttribute("start_date");
		MetaModel::Init_AddFilterFromAttribute("last_update");
		MetaModel::Init_AddFilterFromAttribute("end_date");
		MetaModel::Init_AddFilterFromAttribute("close_date");
		MetaModel::Init_AddFilterFromAttribute("workgroup_id");
		MetaModel::Init_AddFilterFromAttribute("workgroup_name");
		MetaModel::Init_AddFilterFromAttribute("supervisorgroup_id");
		MetaModel::Init_AddFilterFromAttribute("managergroup_id");
		MetaModel::Init_AddFilterFromAttribute("supervisor_id");
		MetaModel::Init_AddFilterFromAttribute("manager_id");
		MetaModel::Init_AddFilterFromAttribute("agent_id");
		MetaModel::Init_AddFilterFromAttribute("impact");
		MetaModel::Init_AddFilterFromAttribute("assignment_count");
		MetaModel::Init_AddFilterFromAttribute("outage");

		// doit-on aussi ajouter un filtre sur les extfields lié à une extkey ? ici le name de l'agent?
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','title', 'org_id','type','domain','requestor_id','change_request','ticket_status', 'outage','impact', 'last_update', 'start_date','end_date', 'assignment_count', 'workgroup_id','agent_id','supervisorgroup_id','supervisor_id','managergroup_id','manager_id','change_log','fallback')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'title', 'org_id', 'ticket_status','outage','start_date','type')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'title', 'org_id', 'ticket_status','type', 'outage','requestor_id','workgroup_id','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'title', 'org_id', 'ticket_status','type', 'outage','workgroup_id','agent_id')); // Criteria of the advanced search form

		// State machine
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_MANDATORY, 'title' => OPT_ATT_MANDATORY, 'reason' => OPT_ATT_MANDATORY, 'impacted_infra_manual' => OPT_ATT_MANDATORY,
												 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN, 'workgroup_id' => OPT_ATT_HIDDEN, 'supervisorgroup_id' => OPT_ATT_HIDDEN, 'managergroup_id' => OPT_ATT_HIDDEN, 'supervisor_id' => OPT_ATT_HIDDEN, 'manager_id' => OPT_ATT_HIDDEN, 'agent_id' => OPT_ATT_HIDDEN )));
		MetaModel::Init_DefineState("Validated", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY, 'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY, 'impacted_infra_manual' => OPT_ATT_MANDATORY,
												 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN, 'workgroup_id' => OPT_ATT_MUSTCHANGE, 'supervisorgroup_id' => OPT_ATT_MUSTCHANGE, 'managergroup_id' => OPT_ATT_MUSTCHANGE, 'supervisor_id' => OPT_ATT_HIDDEN, 'manager_id' => OPT_ATT_HIDDEN, 'agent_id' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Rejected", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Assigned", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY, 'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY, 'impacted_infra_manual' => OPT_ATT_MANDATORY,
												 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN, 'workgroup_id' => OPT_ATT_MANDATORY, 'supervisorgroup_id' => OPT_ATT_MANDATORY, 'managergroup_id' => OPT_ATT_MANDATORY, 'supervisor_id' => OPT_ATT_MUSTCHANGE, 'manager_id' => OPT_ATT_MUSTCHANGE, 'agent_id' => OPT_ATT_MUSTCHANGE)));
		
    MetaModel::Init_DefineState("PlannedScheduled", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_MANDATORY, 'end_date' => OPT_ATT_MANDATORY, 'impact' => OPT_ATT_MANDATORY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_MANDATORY, 'manager_id' => OPT_ATT_MANDATORY, 'agent_id' => OPT_ATT_MANDATORY, 'change_log' => OPT_ATT_MANDATORY,'fallback' => OPT_ATT_MANDATORY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Approved", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'outage' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_MANDATORY, 'impact' => OPT_ATT_READONLY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_READONLY, 'manager_id' => OPT_ATT_READONLY, 'agent_id' => OPT_ATT_MANDATORY, 'change_log' => OPT_ATT_MANDATORY,'fallback' => OPT_ATT_MANDATORY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("NotApproved", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'outage' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_MANDATORY, 'impact' => OPT_ATT_READONLY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_READONLY, 'manager_id' => OPT_ATT_READONLY, 'agent_id' => OPT_ATT_MANDATORY, 'change_log' => OPT_ATT_MANDATORY,'fallback' => OPT_ATT_MANDATORY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Implemented", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_READONLY,'outage' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_MANDATORY, 'impact' => OPT_ATT_READONLY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_READONLY, 'manager_id' => OPT_ATT_READONLY, 'agent_id' => OPT_ATT_MANDATORY, 'change_log' => OPT_ATT_MANDATORY,'fallback' => OPT_ATT_MANDATORY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Monitored", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_READONLY, 'impact' => OPT_ATT_READONLY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_READONLY, 'manager_id' => OPT_ATT_READONLY, 'agent_id' => OPT_ATT_READONLY, 'change_log' => OPT_ATT_READONLY,'fallback' => OPT_ATT_READONLY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));
	  	MetaModel::Init_DefineState("Closed", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY,'outage' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN,'start_date' => OPT_ATT_READONLY,'end_date' => OPT_ATT_READONLY,'last_update' => OPT_ATT_READONLY,'close_date' => OPT_ATT_READONLY, 'impact' => OPT_ATT_READONLY, 'workgroup_id' => OPT_ATT_READONLY,'supervisorgroup_id' => OPT_ATT_READONLY, 'managergroup_id' => OPT_ATT_READONLY, 'supervisor_id' => OPT_ATT_READONLY, 'manager_id' => OPT_ATT_READONLY, 'agent_id' => OPT_ATT_READONLY, 'change_log' => OPT_ATT_READONLY,'fallback' => OPT_ATT_READONLY,'title' => OPT_ATT_READONLY, 'reason' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY,'requestor_id' => OPT_ATT_READONLY,'domain' => OPT_ATT_READONLY,'change_request' => OPT_ATT_READONLY,'creation_date' => OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_validate", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reject", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reopen", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_plan", array()));
	  	MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_approve", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_replan", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_notapprove", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_implement", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_monitor", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_finish", array()));

		MetaModel::Init_DefineTransition("New", "ev_validate", array("target_state"=>"Validated", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("New", "ev_reject", array("target_state"=>"Rejected", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Rejected", "ev_reopen", array("target_state"=>"New", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Validated", "ev_assign", array("target_state"=>"Assigned", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_plan", array("target_state"=>"PlannedScheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));

		MetaModel::Init_DefineTransition("PlannedScheduled", "ev_approve", array("target_state"=>"Approved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("PlannedScheduled", "ev_notapprove", array("target_state"=>"NotApproved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("NotApproved", "ev_replan", array("target_state"=>"PlannedScheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Approved", "ev_implement", array("target_state"=>"Implemented", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implemented", "ev_monitor", array("target_state"=>"Monitored", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implemented", "ev_finish", array("target_state"=>"Closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Monitored", "ev_finish", array("target_state"=>"Closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));		
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('title', $oGenerator->GenerateString("enum(Site,Server,Line)| |enum(is down,is flip-flopping,is not responding)"));
		$this->Set('agent_id', $oGenerator->GenerateKey("bizPerson", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('ticket_status', $oGenerator->GenerateString("enum(Open,Closed,Closed,Monitored)"));
		$this->Set('start_date', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
		$this->Set('last_update', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
		$this->Set('end_date', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
	}
	
	
	
	// State machine actions
	public function IncrementAssignmentCount($sStimulusCode)
	{
		$this->Set('assignment_count', $this->Get('assignment_count') + 1);
		return true;
	}
	
	public function SetClosureDate($sStimulusCode)
	{
		$this->Set('end_date', time());
		return true;
	}
	public function SetLastUpDate($sStimulusCode)
	{
		$this->Set('last_update', time());
		return true;
	}

	public function ComputeValues()
	{
		$iKey = $this->GetKey();
		if ($iKey < 0)
		{
			// Object not yet in the Database
			$iKey = MetaModel::GetNextKey(get_class($this));
		}
		$sName = sprintf('C-%06d', $iKey);
		$this->Set('name', $sName);
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Infra and a Change Ticket
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraChangeTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra Change Ticket",
			"description" => "Infra impacted by a Change ticket",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("impact"),  // ????
			"db_table" => "infra_changeticket",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizChangeTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("infra_id");
		MetaModel::Init_AddFilterFromAttribute("ticket_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'ticket_id', 'impact')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'ticket_id', 'impact')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('infra_id', 'ticket_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('infra_id', 'ticket_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('infra_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('ticket_id', $oGenerator->GenerateKey("bizIncidentTicket", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('impact', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}
}
////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any contact and a Contract
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkContactChange extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ContactChangeLink",
			"description" => "Contact associated to a change",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "role",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("role"),  // ????
			"db_table" => "contact_change",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_mail", array("allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("change_id", array("targetclass"=>"bizChangeTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"change_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("change_number", array("allowed_values"=>null, "extkey_attcode"=> 'change_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("change_id");
		MetaModel::Init_AddFilterFromAttribute("contact_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('change_id', 'contact_id', 'role')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('change_id', 'contact_id', 'role')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('change_id', 'contact_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('change_id', 'contact_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('contract_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('contact_id', $oGenerator->GenerateKey("bizIncidentTicket", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('role', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}

?>
