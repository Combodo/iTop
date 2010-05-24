<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Persistent classes for incident management
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
   	
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum("Network,Server,Desktop,Application"), "sql"=>"type", "default_value"=>"Server", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("ticket_status", array("allowed_values"=>new ValueSetEnum("New, Assigned, WorkInProgress, Resolved, Closed"), "sql"=>"ticket_status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		// SetPossibleValues("status",array("Open","Monitored","Closed"));
		MetaModel::Init_AddAttribute(new AttributeText("initial_situation", array("allowed_values"=>null, "sql"=>"initial_situation", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("current_situation", array("allowed_values"=>null, "sql"=>"current_situation", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		// d�finir une date de d�faut � maintenant, alias creation ou modification du ticket
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
	    MetaModel::Init_AddAttribute(new AttributeDateTime("next_update", array("allowed_values"=>null, "sql"=>"next_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"closed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p WHERE p.org_id = :this->org_id'), "sql"=>"caller_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("caller_mail", array("allowed_values"=>null, "extkey_attcode"=> 'caller_id', "target_attcode"=>"email")));
	
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"bizWorkgroup", "jointype"=> "", "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=> 'workgroup_id', "target_attcode"=>"name")));  
		MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"bizPerson", "jointype"=> "", "allowed_values"=>new ValueSetObjects('SELECT bizPerson AS p JOIN lnkContactTeam AS l ON l.contact_id=p.id JOIN bizTeam AS t ON l.team_id=t.id JOIN bizWorkgroup AS w ON w.team_id=t.id WHERE w.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('workgroup_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_mail", array("allowed_values"=>null, "extkey_attcode"=> 'agent_id', "target_attcode"=>"email")));
		// Comment afficher le first + last name de l'agent ? Est-ce utile d'ajouter ce champ?
		MetaModel::Init_AddAttribute(new AttributeText("action_log", array("allowed_values"=>null, "sql"=>"action_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("severity", array("allowed_values"=>new ValueSetEnum("critical,medium,low"), "sql"=>"criticity", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("assignment_count", array("allowed_values"=>null, "sql"=>"assignment_count", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("resolution", array("allowed_values"=>null, "sql"=>"resolution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("impacted_infra_manual", array("linked_class"=>"lnkInfraTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("related_tickets", array("linked_class"=>"lnkRelatedTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"rel_ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(/*'impacted_infra_computed',*/ 'impacted_infra_manual'))));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contacts_a_notifier", array("linked_class"=>"lnkContactTicket", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "default_value"=>new ValueSetRelatedObjectsFromLinkSet('impacted_infra_manual'/*sLinkSetAttCode*/, 'infra_id'/*sExtKeyToRemote*/, 'impacts'/*sRelationCode*/, 3/*iMaxDepth*/, 'bizContact'/*sTargetClass*/, 'lnkContactTicket'/*sTargetLinkClass*/, 'contact_id'/*sTargetExtKey*/) /* ici plus tard... */, "count_min"=>0, "count_max"=>0, "depends_on"=>array('impacted_infra_manual'))));

		// doit-on aussi ajouter un filtre sur les extfields li� � une extkey ? ici le name de l'agent?
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name','title', 'org_id', 'type','ticket_status', 'severity','start_date', 'initial_situation', 'current_situation','caller_id', 'impact', 'last_update', 'next_update','end_date', 'assignment_count', 'workgroup_id','agent_id','action_log','resolution')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'title', 'org_id', 'type','ticket_status','severity','start_date', 'initial_situation')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'title', 'org_id', 'caller_id','type', 'ticket_status', 'severity','start_date', 'last_update','end_date','agent_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'title', 'org_id','caller_id','type','ticket_status', 'severity','start_date', 'last_update', 'end_date','agent_id')); // Criteria of the advanced search form

		// State machine
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_READONLY, 'assignment_count' => OPT_ATT_HIDDEN, 'end_date' => OPT_ATT_HIDDEN, 'next_update' => OPT_ATT_HIDDEN, 'last_update' =>  OPT_ATT_HIDDEN,
												 "title"=>OPT_ATT_MANDATORY, "org_id"=>OPT_ATT_MANDATORY, "caller_id"=>OPT_ATT_MANDATORY, "initial_situation"=>OPT_ATT_MANDATORY, "start_date"=>OPT_ATT_MANDATORY, "workgroup_id"=>OPT_ATT_MANDATORY,
												 "severity"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_HIDDEN,"impacted_infra_manual"=>OPT_ATT_MANDATORY, "related_tickets"=>OPT_ATT_MUSTPROMPT,"resolution"=>OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("Assigned", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,'assignment_count' => OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "workgroup_id"=>OPT_ATT_MUSTPROMPT, "agent_id"=>OPT_ATT_MUSTCHANGE,"resolution"=>OPT_ATT_HIDDEN)));
		MetaModel::Init_DefineState("WorkInProgress", array("attribute_inherit"=>null, "attribute_list"=>array("title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_HIDDEN, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_MANDATORY, "agent_id"=>OPT_ATT_MANDATORY,"action_log"=>OPT_ATT_MANDATORY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Resolved", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,"current_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_READONLY,'last_update' => OPT_ATT_READONLY,'next_update' => OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY,"resolution"=>OPT_ATT_MUSTCHANGE)));
		MetaModel::Init_DefineState("Closed", array("attribute_inherit"=>null, "attribute_list"=>array('name' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, "title"=>OPT_ATT_READONLY, "org_id"=>OPT_ATT_READONLY, "caller_id"=>OPT_ATT_READONLY, "initial_situation"=>OPT_ATT_READONLY,"current_situation"=>OPT_ATT_READONLY,'end_date' => OPT_ATT_READONLY,'last_update' => OPT_ATT_READONLY,'next_update' => OPT_ATT_READONLY, "start_date"=>OPT_ATT_READONLY,"workgroup_id"=>OPT_ATT_READONLY, "agent_id"=>OPT_ATT_READONLY,"action_log"=>OPT_ATT_READONLY,"impact"=>OPT_ATT_READONLY,"severity"=>OPT_ATT_READONLY,"resolution"=>OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reassign", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_start_working", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_resolve", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_close", array()));

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

/*
	EXAMPLE: OnInsert....

	public function OnInsert()
	{
		// Romain: ajouter cette ligne
		$oToNotify = $this->Get('contacts_a_notifier');

		// Romain: ca c'etait pour verifier que ca fonctionne bien
		// $oFirstContact = MetaModel::GetObject('bizPerson', 6);
		// $oNewLink = new lnkContactTicket();
		// $oNewLink->Set('contact_id', 6);
		// $oNewLink->Set('role', 'created before');
		// $oToNotify->AddObject($oNewLink);

		$oImpactedInfras = DBObjectSet::FromLinkSet($this, 'impacted_infra_manual', 'infra_id');

		$aComputed = $oImpactedInfras->GetRelatedObjects('impacts', 10);

		if (array_key_exists('logRealObject', $aComputed))
		{
			foreach($aComputed['logRealObject'] as $iKey => $oObject)
			{
				if (MetaModel::IsParentClass('bizContact', get_class($oObject)))
				{
					$oNewLink = new lnkContactTicket();
					$oNewLink->Set('contact_id', $iKey);
					//$oNewLink->Set('ticket_id', $this->GetKey()); // unkown at that time!
					$oNewLink->Set('role', 'contact automatically computed');

					// Romain: transformer cette ligne
					$oToNotify->AddObject($oNewLink);
				}
			}
			// Romain: supprimer cette ligne
			// $this->Set('contacts_a_notifier', $oToNotify);
		}
	}
*/
	
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("rel_ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"rel_ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("rel_ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'rel_ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"bizIncidentTicket", "jointype"=> '', "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_name", array("allowed_values"=>null, "extkey_attcode"=> 'ticket_id', "target_attcode"=>"title")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

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
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('production,implementation,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("team_id", array("targetclass"=>"bizTeam", "allowed_values"=>null, "sql"=>"team_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("team_name", array("allowed_values"=>null, "extkey_attcode"=> 'team_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>new ValueSetEnum("1st level support,2nd level support,3rd level support"), "sql"=>"role", "default_value"=>"1st level support", "is_null_allowed"=>true, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'team_id', 'role')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'team_id','role')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'team_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'team_id','role')); // Criteria of the advanced search form

	}
}



?>
