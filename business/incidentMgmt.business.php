<?php


/**
 * incident<business<php
 * Define business model for incident mgmt module
 *
 * @package     iTopBizModelSamples
 * @author      Erwan Taloc <erwan.taloc@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

////////////////////////////////////////////////////////////////////////////////////
/**
* An Incident Ticket
*/
////////////////////////////////////////////////////////////////////////////////////
class bizIncidentTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Incident",
			"description" => "Incident ticket",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",  
			"state_attcode" => "ticket_status",
			"reconc_keys" => array("title"),
			"db_table" => "incident",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/ticket.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Ticket Ref", "description"=>"Refence number ofr this incident", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("label"=>"Title", "description"=>"Overview of the Incident", "allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
   	
    MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of the Incident", "allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"type", "default_value"=>"Server", "is_null_allowed"=>false, "depends_on"=>array())));
     MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "label"=>"Customer", "description"=>"who is impacted by the ticket", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("label"=>"Customer", "description"=>"Name of the customer impacted by this ticket", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("ticket_status", array("label"=>"Status", "description"=>"Status of the ticket", "allowed_values"=>new ValueSetEnum("New, Assigned, WorkInProgress, Resolved, Closed"), "sql"=>"ticket_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));
		MetaModel::Init_AddAttribute(new AttributeText("initial_situation", array("label"=>"Initial Situation", "description"=>"Initial situation of the Incident", "allowed_values"=>null, "sql"=>"initial_situation", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("current_situation", array("label"=>"Current Situation", "description"=>"Current situation of the Incident", "allowed_values"=>null, "sql"=>"current_situation", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("start_date", array("label"=>"Starting date", "description"=>"Incident starting date", "allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    // définir une date de défaut à maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("label"=>"Last update", "description"=>"last time the Ticket was modified", "allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
	      MetaModel::Init_AddAttribute(new AttributeDate("next_update", array("label"=>"Next update", "description"=>"next time the Ticket is expected to be  modified", "allowed_values"=>null, "sql"=>"next_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDate("end_date", array("label"=>"Closure Date", "description"=>"Date when the Ticket was closed", "allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Caller", "description"=>"person that trigger incident", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p WHERE p.org_id = :this->org_id'), "sql"=>"caller_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("caller_mail", array("label"=>"Caller", "description"=>"Person that trigger this incident", "allowed_values"=>null, "extkey_attcode"=> 'caller_id', "target_attcode"=>"email")));
	
  	MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Impact of the Incident", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
  	MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Workgroup", "description"=>"which workgroup is owning ticket", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("label"=>"Workgroup", "description"=>"name of workgroup managing the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Agent", "description"=>"who is managing the ticket", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('workgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("label"=>"Agent", "description"=>"mail of agent managing the Ticket", "allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));
		// Comment afficher le first + last name de l'agent ? Est-ce utile d'ajouter ce champ?
		MetaModel::Init_AddAttribute(new AttributeText("action_log", array("label"=>"Action Logs", "description"=>"List all action performed during the incident", "allowed_values"=>null, "sql"=>"action_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("severity", array("label"=>"Severity", "description"=>"Field defining the criticity if the incident", "allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"criticity", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("assignment_count", array("label"=>"Assignment Count", "description"=>"Number of times this ticket was assigned or reassigned", "allowed_values"=>null, "sql"=>"assignment_count", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("resolution", array("label"=>"Resolution", "description"=>"Description of the resolution", "allowed_values"=>null, "sql"=>"resolution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("label"=>"Impacted Infrastructure", "description"=>"CIs that are not meeting the SLA", "linked_class"=>"lnkInfraTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("related_tickets", array("label"=>"Related Tickets", "description"=>"Other incident tickets related to this one", "linked_class"=>"lnkRelatedTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"rel_ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(/*'impacted_infra_computed',*/ 'impacted_infra_manual'))));


		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("title");
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("org_id");
		MetaModel::Init_AddFilterFromAttribute("caller_id");
		MetaModel::Init_AddFilterFromAttribute("ticket_status");
		MetaModel::Init_AddFilterFromAttribute("start_date");
		MetaModel::Init_AddFilterFromAttribute("last_update");
		MetaModel::Init_AddFilterFromAttribute("end_date");
		MetaModel::Init_AddFilterFromAttribute("workgroup_id");
		MetaModel::Init_AddFilterFromAttribute("agent_id");
		MetaModel::Init_AddFilterFromAttribute("severity");
		MetaModel::Init_AddFilterFromAttribute("assignment_count");

		// doit-on aussi ajouter un filtre sur les extfields lié à une extkey ? ici le name de l'agent?
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','title', 'org_id', 'type','ticket_status', 'severity','start_date', 'initial_situation', 'current_situation','caller_id', 'impact', 'last_update', 'next_update','end_date', 'assignment_count', 'workgroup_id','agent_id','action_log','resolution')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'title', 'org_id', 'type','ticket_status','severity','start_date', 'initial_situation')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'title', 'org_id', 'caller_id','type', 'ticket_status', 'severity','start_date', 'last_update','end_date','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'title', 'org_id','caller_id','type','ticket_status', 'severity','start_date', 'last_update', 'end_date','agent_id')); // Criteria of the advanced search form

		// State machine
		MetaModel::Init_DefineState("New", array("label"=>"New (Unassigned)", "description"=>"Newly created ticket", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN, 'next_update' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_HIDDEN,
												 "title"=>OPT_ATT_MANDATORY, "org_id"=>OPT_ATT_MANDATORY, "caller_id"=>OPT_ATT_MANDATORY, "initial_situation"=>OPT_ATT_MANDATORY, "start_date"=>OPT_ATT_MANDATORY, "workgroup_id"=>OPT_ATT_MANDATORY,
												 "severity"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_HIDDEN,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT,"resolution"=>OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Assigned", array("label"=>"Assigned", "description"=>"Ticket is assigned to somebody", "attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,'assignment_count' => OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_MUSTCHANGE,"resolution"=>OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("WorkInProgress", array("label"=>"Work In Progress", "description"=>"Work is in progress", "attribute_inherit"=>null, "attribute_list"=>array("title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY,"action_log"=>OPT_ATT_MANDATORY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Resolved", array("label"=>"Resolved", "description"=>"Ticket is resolved", "attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,"current_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_READONLY,'last_update' => OPT_ATT_READONLY,'next_update' => OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY,"resolution"=>OPT_ATT_MUSTCHANGE)));
		MetaModel::Init_DefineState("Closed", array("label"=>"Closed", "description"=>"Ticket is closed", "attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,"current_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_READONLY,'last_update' => OPT_ATT_READONLY,'next_update' => OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY,"resolution"=>OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus("ev_assign", new StimulusUserAction(array("label"=>"Assign this ticket", "description"=>"Assign this ticket to a group and an agent")));
		MetaModel::Init_DefineStimulus("ev_reassign", new StimulusUserAction(array("label"=>"Reassign this ticket", "description"=>"Reassign this ticket to a different group and agent")));
		MetaModel::Init_DefineStimulus("ev_start_working", new StimulusUserAction(array("label"=>"Work on this ticket", "description"=>"Start working on this ticket")));
		MetaModel::Init_DefineStimulus("ev_resolve", new StimulusUserAction(array("label"=>"Resolve this ticket", "description"=>"Resolve this ticket")));
		MetaModel::Init_DefineStimulus("ev_close", new StimulusUserAction(array("label"=>"Close this ticket", "description"=>"Close this ticket")));

		MetaModel::Init_DefineTransition("New", "ev_assign", array("target_state"=>"Assigned", "actions"=>array('IncrementAssignmentCount'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array('IncrementAssignmentCount','SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_start_working", array("target_state"=>"WorkInProgress", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array('IncrementAssignmentCount','SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_resolve", array("target_state"=>"Resolved", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Resolved", "ev_close", array("target_state"=>"Closed", "actions"=>array('SetClosureDate','SetLastUpdate'), "user_restriction"=>null));
		
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
	
		public function SetLastUpdate($sStimulusCode)
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
		$sName = sprintf('I-%06d', $iKey);
		$this->Set('name', $sName);
	}
}


////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Infra and a Incident
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkRelatedTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Related Ticket",
			"description" => "Ticket related to a ticket",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("impact"),  // ????
			"db_table" => "related_ticket",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("rel_ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "label"=>"Related Ticket", "description"=>"The related ticket", "allowed_values"=>null, "sql"=>"rel_ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("rel_ticket_name", array("label"=>"Related ticket", "description"=>"Name of the related ticket", "allowed_values"=>null, "extkey_attcode"=> 'rel_ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "label"=>"Ticket", "description"=>"Ticket number", "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("label"=>"Ticket Name", "description"=>"Name of the ticket", "allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Impact on the related ticket", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("rel_ticket_id");
		MetaModel::Init_AddFilterFromAttribute("ticket_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('rel_ticket_id', 'ticket_id', 'impact')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('rel_ticket_id', 'ticket_id', 'impact')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('rel_ticket_id', 'ticket_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('rel_ticket_id', 'ticket_id')); // Criteria of the advanced search form
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
* n-n link between any Infra and a Incident
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra Ticket",
			"description" => "Infra impacted by a ticket",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("impact"),  // ????
			"db_table" => "infra_ticket",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "label"=>"Infrastructure", "description"=>"The infrastructure impacted", "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("label"=>"Infrastructure Name", "description"=>"Name of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "label"=>"Ticket #", "description"=>"Ticket number", "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
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
* n-n link between any Contqct and a Incident
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkContactTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Contact Ticket",
			"description" => "Contacts to be notify for a ticket",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "role",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("role"),  // ????
			"db_table" => "contact_ticket",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "label"=>"Contact", "description"=>"Contact to Notify", "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("label"=>"Contact email", "description"=>"Mail for the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "label"=>"Ticket #", "description"=>"Ticket number", "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("label"=>"Ticket Name", "description"=>"Name of the ticket", "allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("label"=>"Role", "description"=>"Role of the contact", "allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("contact_id");
		MetaModel::Init_AddFilterFromAttribute("ticket_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('contact_id', 'ticket_id', 'role')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('contact_id', 'ticket_id', 'role')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('contact_id', 'ticket_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('contact_id', 'ticket_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('infra_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('ticket_id', $oGenerator->GenerateKey("bizIncidentTicket", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('impact', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}
////////////////////////////////////////////////////////////////////////////////////
//**
//* A workgroup is a queue in a given call tracking system
//* It belongs to a team and a given organization
////////////////////////////////////////////////////////////////////////////////////
class bizWorkgroup extends logRealObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Workgroup",
			"description" => "Call tracking workgroup",
			"key_type" => "",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_name", "name"), // inherited attributes
			"db_table" => "workgroups",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Lifecycle status", "allowed_values"=>new ValueSetEnum('production,implementation,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("label"=>"Organization", "description"=>"Company / Department owning this object", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("team_id", array("targetclass"=>"bizTeam", "label"=>"Team", "description"=>"Team owning the workgroup", "allowed_values"=>null, "sql"=>"team_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("team_name", array("label"=>"Team Name", "description"=>"name of the team", "allowed_values"=>null, "extkey_attcode"=> 'team_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("label"=>"Role", "description"=>"Role of this work group", "allowed_values"=>new ValueSetEnum("1st level support,2nd level support,3rd level support"), "sql"=>"role", "default_value"=>"1st level support", "is_null_allowed"=>true, "depends_on"=>array())));
		
		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("org_name");
		MetaModel::Init_AddFilterFromAttribute("team_id");
		MetaModel::Init_AddFilterFromAttribute("role");
		
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'team_id', 'role')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'team_id','role')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'team_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'team_id','role')); // Criteria of the advanced search form

	}
}



?>
