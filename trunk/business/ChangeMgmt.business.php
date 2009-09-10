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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Ticket Ref", "description"=>"Refence number ofr this change", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("label"=>"Title", "description"=>"Overview of the Change", "allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeString("type", array("label"=>"Change Type", "description"=>"Type of the Change", "allowed_values"=>new ValueSetEnum("Routine, Normal, Emergency"), "sql"=>"type", "default_value"=>"Routine", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeString("domain", array("label"=>"Domain", "description"=>"Domain for the Change", "allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"domain", "default_value"=>"Desktop", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeString("reason", array("label"=>"Reason For Change", "description"=>"Reason for the Change", "allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("requestor_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Requestor", "description"=>"who is requesting this change", "allowed_values"=>null, "sql"=>"requestor_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requestor_mail", array("label"=>"Requestor", "description"=>"mail of user requesting this change", "allowed_values"=>null, "extkey_attcode"=> 'requestor_id', "target_attcode"=>"email")));

    
    MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "label"=>"Customer", "description"=>"who is impacted by the ticket", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("label"=>"Customer", "description"=>"Name of the customer impacted by this ticket", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("ticket_status", array("label"=>"Status", "description"=>"Status of the ticket", "allowed_values"=>new ValueSetEnum("New, Validated,Rejected,PlannedScheduled,Approved,NotApproved,Implemented,Monitored, Closed"), "sql"=>"change_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));

		MetaModel::Init_AddAttribute(new AttributeDate("creation_date", array("label"=>"Creation Date", "description"=>"Change creation date", "allowed_values"=>null, "sql"=>"creation_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    	// définir une date de défaut à maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("label"=>"Last Update", "description"=>"last time the Ticket was modified", "allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeDate("start_date", array("label"=>"Start Date", "description"=>"Time the change is expected to start", "allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end_date", array("label"=>"End Date", "description"=>"Date when the change is supposed to end", "allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("close_date", array("label"=>"Closure Date", "description"=>"Date when the Ticket was closed", "allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Risk Assessment", "description"=>"Impact of the change", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Workgroup", "description"=>"which workgroup is owning ticket", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("label"=>"Workgroup", "description"=>"name of workgroup managing the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Agent", "description"=>"who is managing the ticket", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "depends_on"=>array('workgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("label"=>"Agent", "description"=>"name of agent managing the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisorgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Supervisor Group", "description"=>"which workgroup is supervising ticket", "allowed_values"=>null, "sql"=>"supervisorgroup_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisorgroup_name", array("label"=>"Supervisor Group", "description"=>"name of the group supervising the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'supervisorgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Supervisor", "description"=>"who is managing the ticket", "allowed_values"=>null, "sql"=>"supervisor_id", "is_null_allowed"=>true, "depends_on"=>array('supervisorgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_mail", array("label"=>"Supervisor", "description"=>"name of agent supervising the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'supervisor_id', "target_attcode"=>"email")));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("managergroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Manager Group", "description"=>"which workgroup is approving ticket", "allowed_values"=>null, "sql"=>"managergroup_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("managergroup_name", array("label"=>"Manager Group", "description"=>"name of workgroup approving the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'managergroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Manager", "description"=>"who is approving the ticket", "allowed_values"=>null, "sql"=>"manager_id", "is_null_allowed"=>true, "depends_on"=>array('managergroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_mail", array("label"=>"Manager", "description"=>"name of agent approving the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'manager_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeEnum("outage", array("label"=>"Planned Outage", "description"=>"Flag to define if there is a planned outage", "allowed_values"=>new ValueSetEnum("Yes,No"), "sql"=>"outage", "default_value"=>"No", "is_null_allowed"=>false, "depends_on"=>array())));


		MetaModel::Init_AddAttribute(new AttributeText("change_request", array("label"=>"Change Request", "description"=>"Description of Change required", "allowed_values"=>null, "sql"=>"change_req", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("change_log", array("label"=>"Implementation Log", "description"=>"List all action performed during the change", "allowed_values"=>null, "sql"=>"change_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeText("fallback", array("label"=>"Fallback Plan", "description"=>"Instruction to come back to former situation", "allowed_values"=>null, "sql"=>"fallback", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
    MetaModel::Init_AddAttribute(new AttributeInteger("assignment_count", array("label"=>"Assignment Count", "description"=>"Number of times this ticket was assigned or reassigned", "allowed_values"=>null, "sql"=>"assignment_count", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("label"=>"Impacted Infrastructure", "description"=>"CIs that are impacted by this change", "linked_class"=>"lnkInfraChangeTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

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
		MetaModel::Init_DefineState("New", array("label"=>"New (Unassigned)", "description"=>"Newly created ticket", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_MANDATORY, 'title' => OPT_ATT_MANDATORY, 'reason' => OPT_ATT_MANDATORY, 'impacted_infra_manual' => OPT_ATT_MANDATORY,
												 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Validated", array("label"=>"Validated", "description"=>"Ticket is approved", "attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_READONLY,'managergroup_id' => OPT_ATT_MANDATORY, 'supervisorgroup_id' => OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Rejected", array("label"=>"Rejected", "description"=>"This ticket is not approved", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("PlannedScheduled", array("label"=>"Planned&Scheduled", "description"=>"Evaluation is done for this change", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_MANDATORY, 'impact' => OPT_ATT_MANDATORY, 'workgroup_id' => OPT_ATT_MANDATORY, 'change_log' => OPT_ATT_MUSTCHANGE,'fallback' => OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Approved", array("label"=>"Approved", "description"=>"Ticket is approved by CAB", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("NotApproved", array("label"=>"Not Approved", "description"=>"Ticket has not been approved by CAB", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Implemented", array("label"=>"Implementation", "description"=>"Work is in progress for this ticket", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Monitored", array("label"=>"Monitored", "description"=>"Change performed is now monitored", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY,'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN)));
	  	MetaModel::Init_DefineState("Closed", array("label"=>"Closed", "description"=>"Ticket is closed", "attribute_inherit"=>null, "attribute_list"=>array('org_id' => OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY)));

		MetaModel::Init_DefineStimulus("ev_validate", new StimulusUserAction(array("label"=>"Validate this change", "description"=>"Make sure it is a valid change request")));
		MetaModel::Init_DefineStimulus("ev_reject", new StimulusUserAction(array("label"=>"Reject this change", "description"=>"This change request is rejected because it is a non valid one")));
		MetaModel::Init_DefineStimulus("ev_reopen", new StimulusUserAction(array("label"=>"Modify this change", "description"=>"Update change request to make it valid")));
		MetaModel::Init_DefineStimulus("ev_plan", new StimulusUserAction(array("label"=>"Plan this change", "description"=>"Plan and Schedule this change for validation")));
	  	MetaModel::Init_DefineStimulus("ev_approve", new StimulusUserAction(array("label"=>"Approve this change", "description"=>"This change is approved by CAB")));
		MetaModel::Init_DefineStimulus("ev_replan", new StimulusUserAction(array("label"=>"Update planning and schedule", "description"=>"Modify Plan and Schedule in order to have this change re-validated")));
		MetaModel::Init_DefineStimulus("ev_notapprove", new StimulusUserAction(array("label"=>"Not approve this change", "description"=>"This change is not approved by CAB")));
		MetaModel::Init_DefineStimulus("ev_implement", new StimulusUserAction(array("label"=>"Implement this change", "description"=>"Implementation pahse for current change")));
		MetaModel::Init_DefineStimulus("ev_monitor", new StimulusUserAction(array("label"=>"Monitor this change", "description"=>"Starting monitoring period for this change")));
		MetaModel::Init_DefineStimulus("ev_finish", new StimulusUserAction(array("label"=>"Close change", "description"=>"Change is done, and can be closed")));

		MetaModel::Init_DefineTransition("New", "ev_validate", array("target_state"=>"Validated", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("New", "ev_reject", array("target_state"=>"Rejected", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Rejected", "ev_reopen", array("target_state"=>"New", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Validated", "ev_plan", array("target_state"=>"PlannedScheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("PlannedScheduled", "ev_approve", array("target_state"=>"Approved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("PlannedScheduled", "ev_notapprove", array("target_state"=>"NotApproved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("NotApproved", "ev_replan", array("target_state"=>"PlannedScheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Approved", "ev_implement", array("target_state"=>"Implemented", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implemented", "ev_monitor", array("target_state"=>"Monitored", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implemented", "ev_finish", array("target_state"=>"Closed", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Monitored", "ev_finish", array("target_state"=>"Closed", "actions"=>array(), "user_restriction"=>null));		
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

	public function ComputeFields()
	{
		if ($this->GetKey() > 0)
		{
			$sName = sprintf('C-%06d', $this->GetKey());
		}
		else
		{
			$sName = "Id not set";
		}
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "label"=>"Infrastructure", "description"=>"The infrastructure impacted", "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("label"=>"Infrastructure Name", "description"=>"Name of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizChangeTicket", "jointype"=> '', "label"=>"Ticket", "description"=>"Ticket number", "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("label"=>"Ticket Name", "description"=>"Name of the ticket", "allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Level of impact of the infra by the related ticket", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "label"=>"Contact", "description"=>"The contact linked to contract", "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_mail", array("label"=>"Contact E-mail", "description"=>"Mail for the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("change_id", array("targetclass"=>"bizChangeTicket", "jointype"=> '', "label"=>"Change Ticket", "description"=>"Change ticket ID", "allowed_values"=>null, "sql"=>"change_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("change_number", array("label"=>"Change Ticket", "description"=>"Ticket number for this change", "allowed_values"=>null, "extkey_attcode"=> 'change_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("label"=>"Role", "description"=>"Role of this contact for this change", "allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
