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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
   	
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"type", "default_value"=>"Server", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("call_status", array("allowed_values"=>new ValueSetEnum("New, Assigned, WorkInProgress,Resolved,Closed"), "sql"=>"call_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));
		MetaModel::Init_AddAttribute(new AttributeText("call_description", array("allowed_values"=>null, "sql"=>"call_description", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("creation_date", array("allowed_values"=>null, "sql"=>"creation_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		// d�finir une date de d�faut � maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("next_update", array("allowed_values"=>null, "sql"=>"next_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p WHERE p.org_id = :this->org_id'), "sql"=>"caller_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('org_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("caller_mail", array("allowed_values"=>null, "extkey_attcode"=> 'caller_id', "target_attcode"=>"email")));
		
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
		MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("workgroup_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));
		// Comment afficher le first + last name de l'agent ? Est-ce utile d'ajouter ce champ?
		MetaModel::Init_AddAttribute(new AttributeText("action_log", array("allowed_values"=>null, "sql"=>"action_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("severity", array("allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"criticity", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("resolution", array("allowed_values"=>null, "sql"=>"resolution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source", array("allowed_values"=>new ValueSetEnum("phone,E-mail,Fax"), "sql"=>"source", "default_value"=>"phone", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("linked_class"=>"lnkInfraCall", "ext_key_to_me"=>"call_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("related_tickets", array("linked_class"=>"lnkCallTicket", "ext_key_to_me"=>"call_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(/*'impacted_infra_computed',*/ 'impacted_infra_manual'))));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','title', 'org_id', 'type','call_status','source', 'severity','creation_date', 'call_description', 'caller_id', 'impact', 'last_update', 'end_date', 'workgroup_id','agent_id','action_log','resolution')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'title', 'org_id', 'type','call_status','severity','creation_date')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'title', 'org_id','source', 'caller_id','type', 'call_status', 'severity','creation_date', 'last_update','end_date','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'title', 'org_id', 'caller_id','type', 'call_status', 'severity','creation_date', 'last_update','end_date','agent_id')); // Criteria of the advanced search form

		// State machine
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_READONLY,
												 "title"=>OPT_ATT_MANDATORY, "org_id"=>OPT_ATT_MANDATORY, "caller_id"=>OPT_ATT_MANDATORY, "call_description"=>OPT_ATT_MANDATORY, "creation_date"=>OPT_ATT_MANDATORY, "workgroup_id"=>OPT_ATT_MANDATORY,
												 "severity"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_HIDDEN,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT)));
		MetaModel::Init_DefineState("Assigned", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY,"source"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY, "creation_date"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_MUSTCHANGE)));
		MetaModel::Init_DefineState("WorkInProgress", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_READONLY,
												 "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY,"source"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY, "creation_date"=>OPT_ATT_READONLY, "workgroup_id"=>OPT_ATT_READONLY,
												 "severity"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY,"action_log"=>OPT_ATT_MUSTPROMPT,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT)));
		MetaModel::Init_DefineState("Resolved", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_READONLY,
												 "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "source"=>OPT_ATT_READONLY,"caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY, "creation_date"=>OPT_ATT_READONLY, "workgroup_id"=>OPT_ATT_READONLY,
												 "severity"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT, "resolution"=>OPT_ATT_MUSTCHANGE)));
		MetaModel::Init_DefineState("Closed", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY, 'end_date' => OPT_ATT_READONLY, 'last_update' =>  OPT_ATT_READONLY,"next_update"=>OPT_ATT_READONLY,
												 "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY,"source"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "call_description"=>OPT_ATT_READONLY, "creation_date"=>OPT_ATT_READONLY,"impact"=>OPT_ATT_READONLY,"type"=>OPT_ATT_READONLY, "workgroup_id"=>OPT_ATT_READONLY,
												 "severity"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impacted_infra_manual"=>OPT_ATT_READONLY, "related_tickets"=>OPT_ATT_READONLY, "resolution"=>OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reassign", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_start_working", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_resolve", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_close", array()));

		MetaModel::Init_DefineTransition("New", "ev_assign", array("target_state"=>"Assigned", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Assigned", "ev_start_working", array("target_state"=>"WorkInProgress", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_reassign", array("target_state"=>"Assigned", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("WorkInProgress", "ev_resolve", array("target_state"=>"Resolved", "actions"=>array('SetLastUpdate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Resolved", "ev_close", array("target_state"=>"Closed", "actions"=>array('SetLastUpdate','SetClosureDate'), "user_restriction"=>null));
		
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
	
	// State machine actions

	
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("call_id", array("targetclass"=>"bizServiceCall", "jointype"=> '', "allowed_values"=>null, "sql"=>"call_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("call_name", array("allowed_values"=>null, "extkey_attcode"=> 'call_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("call_id", array("targetclass"=>"bizServiceCall", "jointype"=> '', "allowed_values"=>null, "sql"=>"call_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("call_name", array("allowed_values"=>null, "extkey_attcode"=> 'call_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
