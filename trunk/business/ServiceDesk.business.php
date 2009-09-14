<?php


/**
 * ServiceDesk.businnes.php
 * Define business model for Service Desk module
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
class bizServiceCall extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ServiceCall",
			"description" => "Service Call from customer",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",  
			"state_attcode" => "call_status",
			"reconc_keys" => array("title"),
			"db_table" => "servicecall",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/serviceCall.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Service Call Ref", "description"=>"Refence identifier for this service call", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("label"=>"Title", "description"=>"Overview of the service call", "allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
   	
    MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of the Incident", "allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"type", "default_value"=>"Server", "is_null_allowed"=>false, "depends_on"=>array())));
     MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "label"=>"Customer", "description"=>"Customer concerned by this service call", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("label"=>"Customer", "description"=>"Name of the customer raising this service call", "allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("call_status", array("label"=>"Status", "description"=>"Status of the ticket", "allowed_values"=>new ValueSetEnum("New, Assigned, WorkInProgress,Resolved,Closed"), "sql"=>"call_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));
		MetaModel::Init_AddAttribute(new AttributeText("call_description", array("label"=>"Description", "description"=>"Description of the call as describe by caller", "allowed_values"=>null, "sql"=>"call_description", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("creation_date", array("label"=>"Creation date", "description"=>"Call creation date", "allowed_values"=>null, "sql"=>"creation_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
    // définir une date de défaut à maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("label"=>"Last update", "description"=>"last time the call was modified", "allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeDate("next_update", array("label"=>"Next update", "description"=>"next time the Ticket is expected to be  modified", "allowed_values"=>null, "sql"=>"next_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDate("end_date", array("label"=>"Closure Date", "description"=>"Date when the call was closed", "allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	  MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Caller", "description"=>"person that trigger this call", "allowed_values"=>null, "sql"=>"caller_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("caller_mail", array("label"=>"Caller", "description"=>"Person that trigger this call", "allowed_values"=>null, "extkey_attcode"=> 'caller_id', "target_attcode"=>"email")));
	
  	MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Impact for this call", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
  	MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "label"=>"Workgroup", "description"=>"which workgroup is owning call", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("label"=>"Workgroup", "description"=>"name of workgroup managing the call", "allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
    MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "label"=>"Agent", "description"=>"who is managing the call", "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "depends_on"=>array("workgroup_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("label"=>"Agent", "description"=>"mail of agent managing the call", "allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));
		// Comment afficher le first + last name de l'agent ? Est-ce utile d'ajouter ce champ?
		MetaModel::Init_AddAttribute(new AttributeText("action_log", array("label"=>"Action Logs", "description"=>"List all action performed during the call", "allowed_values"=>null, "sql"=>"action_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("severity", array("label"=>"Severity", "description"=>"Field defining the criticity for the call", "allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"criticity", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("resolution", array("label"=>"Resolution", "description"=>"Description of the resolution", "allowed_values"=>null, "sql"=>"resolution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source", array("label"=>"Source", "description"=>"source type for this call", "allowed_values"=>new ValueSetEnum("phone,E-mail,Fax"), "sql"=>"source", "default_value"=>"phone", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("label"=>"Impacted Infrastructure", "description"=>"CIs that are not meeting the SLA", "linked_class"=>"lnkInfraCall", "ext_key_to_me"=>"call_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("related_tickets", array("label"=>"Related Incident", "description"=>"Other incident tickets related to this call", "linked_class"=>"lnkCallTicket", "ext_key_to_me"=>"call_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(/*'impacted_infra_computed',*/ 'impacted_infra_manual'))));


		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("title");
		MetaModel::Init_AddFilterFromAttribute("type");
		MetaModel::Init_AddFilterFromAttribute("org_id");
		MetaModel::Init_AddFilterFromAttribute("caller_id");
		MetaModel::Init_AddFilterFromAttribute("call_status");
		MetaModel::Init_AddFilterFromAttribute("creation_date");
		MetaModel::Init_AddFilterFromAttribute("last_update");
		MetaModel::Init_AddFilterFromAttribute("end_date");
		MetaModel::Init_AddFilterFromAttribute("workgroup_id");
		MetaModel::Init_AddFilterFromAttribute("agent_id");
		MetaModel::Init_AddFilterFromAttribute("severity");
    MetaModel::Init_AddFilterFromAttribute("source");

		// doit-on aussi ajouter un filtre sur les extfields lié à une extkey ? ici le name de l'agent?
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','title', 'org_id', 'type','call_status','source', 'severity','creation_date', 'call_description', 'caller_id', 'impact', 'last_update', 'end_date', 'workgroup_id','agent_id','action_log','resolution')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'title', 'org_id', 'type','call_status','severity','creation_date')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'title', 'org_id','source', 'caller_id','type', 'call_status', 'severity','creation_date', 'last_update','end_date','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'title', 'org_id', 'caller_id','type', 'call_status', 'severity','creation_date', 'last_update','end_date','agent_id')); // Criteria of the advanced search form

		// State machine
		MetaModel::Init_DefineState("New", array("label"=>"New (Unassigned)", "description"=>"Newly created call", "attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_HIDDEN,
												 "title"=>OPT_ATT_MANDATORY, "org_id"=>OPT_ATT_MANDATORY, "caller_id"=>OPT_ATT_MANDATORY, "call_description"=>OPT_ATT_MANDATORY, "creation_date"=>OPT_ATT_MANDATORY, "workgroup_id"=>OPT_ATT_MANDATORY,
												 "severity"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_HIDDEN,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT)));
		MetaModel::Init_DefineState("Assigned", array("label"=>"Assigned", "description"=>"Call is assigned to somebody", "attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY, "creation_date"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "workgroup_id"=>OPT_ATT_MUSTCHANGE, "agent_id"=>OPT_ATT_MUSTCHANGE)));
		MetaModel::Init_DefineState("WorkInProgress", array("label"=>"Work In Progress", "description"=>"Work is in progress", "attribute_inherit"=>null, "attribute_list"=>array("title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "creation_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Resolved", array("label"=>"Resolved", "description"=>"Call is resolved", "attribute_inherit"=>null, "attribute_list"=>array("workgroup_id"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY, "resolution"=>OPT_ATT_MANDATORY, "end_date"=>OPT_ATT_MANDATORY,"resolution"=>OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Closed", array("label"=>"Closed", "description"=>"Call is closed", "attribute_inherit"=>null, "attribute_list"=>array("workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY, "resolution"=>OPT_ATT_READONLY, "end_date"=>OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus("ev_assign", new StimulusUserAction(array("label"=>"Assign this call", "description"=>"Assign this call to a group and an agent")));
		MetaModel::Init_DefineStimulus("ev_reassign", new StimulusUserAction(array("label"=>"Reassign this call", "description"=>"Reassign this call to a different group and agent")));
		MetaModel::Init_DefineStimulus("ev_start_working", new StimulusUserAction(array("label"=>"Work on this call", "description"=>"Start working on this call")));
		MetaModel::Init_DefineStimulus("ev_resolve", new StimulusUserAction(array("label"=>"Resolve this call", "description"=>"Resolve this call")));
		MetaModel::Init_DefineStimulus("ev_close", new StimulusUserAction(array("label"=>"Close this call", "description"=>"Close this call")));

		MetaModel::Init_DefineTransition("New", "ev_assign", array("target_state"=>"Assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_start_working", array("target_state"=>"WorkInProgress", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_resolve", array("target_state"=>"Resolved", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Resolved", "ev_close", array("target_state"=>"Closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
		
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('org_id', $oGenerator->GetOrganizationId());
		$this->Set('title', $oGenerator->GenerateString("enum(Site,Server,Line)| |enum(is down,is flip-flopping,is not responding)"));
		$this->Set('agent_id', $oGenerator->GenerateKey("bizPerson", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('call_status', $oGenerator->GenerateString("enum(Open,Closed,Closed,Monitored)"));
		$this->Set('creation_date', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
		$this->Set('last_update', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
		$this->Set('end_date', $oGenerator->GenerateString("2007-|number(07-12)|-|number(01-30)| |number(07-12)|:|number(00-59)|:|number(00-59)"));
	}
	
	public static function GetUIPage()
	{
		return './UI.php';
	}
	
	// State machine actions

	
	public function SetClosureDate($sStimulusCode)
	{
		$this->Set('end_date', time());
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
		$sName = sprintf('S-%06d', $iKey);
		$this->Set('name', $sName);
	}
}


////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any ticket and a Call
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkCallTicket extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Call Ticket",
			"description" => "Ticket related to a call",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("impact"),  // ????
			"db_table" => "call_ticket",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "label"=>"Related Ticket", "description"=>"The related ticket", "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("label"=>"Related ticket", "description"=>"Name of the related ticket", "allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("call_id", array("targetclass"=>"bizServiceCall", "jointype"=> '', "label"=>"Call", "description"=>"Ticket number", "allowed_values"=>null, "sql"=>"call_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("call_name", array("label"=>"Call name", "description"=>"Name of the call", "allowed_values"=>null, "extkey_attcode"=> 'call_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Impact on the call", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("ticket_id");
		MetaModel::Init_AddFilterFromAttribute("call_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('ticket_id', 'call_id', 'impact')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('ticket_id', 'call_id', 'impact')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'call_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'call_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('call_id', $oGenerator->GenerateKey("bizServiceCall", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('ticket_id', $oGenerator->GenerateKey("bizIncidentTicket", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('impact', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Infra and a Call
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraCall extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Infra Call",
			"description" => "Infra concerned by a call",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "impact",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("impact"),  // ????
			"db_table" => "infra_call",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "label"=>"Infrastructure", "description"=>"The infrastructure impacted", "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("label"=>"Infrastructure Name", "description"=>"Name of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("call_id", array("targetclass"=>"bizServiceCall", "jointype"=> '', "label"=>"Call", "description"=>"Call number", "allowed_values"=>null, "sql"=>"call_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("call_name", array("label"=>"Call name", "description"=>"Name of the call", "allowed_values"=>null, "extkey_attcode"=> 'call_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("label"=>"Impact", "description"=>"Level of impact of the infra by the related ticket", "allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("infra_id");
		MetaModel::Init_AddFilterFromAttribute("call_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'call_id', 'impact')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'call_id', 'impact')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('infra_id', 'call_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('infra_id', 'call_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('infra_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('call_id', $oGenerator->GenerateKey("bizServiceCall", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('impact', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}




?>
