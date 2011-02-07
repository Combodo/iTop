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
 * Persistent classes for a CMDB
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */



abstract class Change extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "change",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"icon" => "../modules/itop-change-mgmt-1.0.0/images/change.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('approved,assigned,closed,implemented,monitored,new,notapproved,plannedscheduled,rejected,validated'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("requestor_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Person AS p WHERE p.org_id = :this->org_id'), "sql"=>"requestor_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requestor_email", array("allowed_values"=>null, "extkey_attcode"=>"requestor_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"workgroup_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("creation_date", array("allowed_values"=>null, "sql"=>"creation_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("close_date", array("allowed_values"=>null, "sql"=>"close_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Person AS p JOIN lnkTeamToContact AS l ON l.contact_id=p.id JOIN Team AS t ON l.team_id=t.id WHERE t.id = :this->workgroup_id'), "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("workgroup_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_name", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_email", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_group_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"supervisor_group_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_group_name", array("allowed_values"=>null, "extkey_attcode"=>"supervisor_group_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Person AS p JOIN lnkTeamToContact AS l ON l.contact_id=p.id JOIN Team AS t ON l.team_id=t.id WHERE t.id = :this->supervisor_group_id'), "sql"=>"supervisor_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("supervisor_group_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_email", array("allowed_values"=>null, "extkey_attcode"=>"supervisor_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_group_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"manager_group_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_group_name", array("allowed_values"=>null, "extkey_attcode"=>"manager_group_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Person AS p JOIN lnkTeamToContact AS l ON l.contact_id=p.id JOIN Team AS t ON l.team_id=t.id WHERE t.id = :this->manager_group_id'), "sql"=>"manager_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("manager_group_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_email", array("allowed_values"=>null, "extkey_attcode"=>"manager_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("outage", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"outage", "default_value"=>"no", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("fallback", array("allowed_values"=>null, "sql"=>"fallback", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'org_id', 'description','ticket_log', 'start_date','end_date', 'document_list', 'ci_list', 'contact_list','incident_list', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'fallback'));
		MetaModel::Init_SetZListItems('advanced_search', array('finalclass', 'ref', 'title', 'org_id', 'start_date', 'end_date','status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('standard_search', array('finalclass', 'ref', 'title', 'org_id', 'start_date', 'end_date','status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'title', 'start_date', 'status'));


		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit" => null,
				"attribute_list" => array(
					'start_date' => OPT_ATT_HIDDEN,
					'ticket_log' => OPT_ATT_HIDDEN,
					'impact' => OPT_ATT_HIDDEN,
					'outage' => OPT_ATT_HIDDEN,
					'fallback' => OPT_ATT_HIDDEN,
					'ref' => OPT_ATT_READONLY,
					'requestor_id' => OPT_ATT_MANDATORY,
					'title' => OPT_ATT_MANDATORY,
					'reason' => OPT_ATT_MANDATORY,
					'workgroup_id' => OPT_ATT_HIDDEN,
					'creation_date' => OPT_ATT_READONLY,
					'last_update' => OPT_ATT_READONLY,
					'end_date' => OPT_ATT_HIDDEN,
					'close_date' => OPT_ATT_HIDDEN,
					'agent_id' => OPT_ATT_HIDDEN,
					'agent_email' => OPT_ATT_HIDDEN,
					'supervisor_group_id' => OPT_ATT_HIDDEN,
					'supervisor_id' => OPT_ATT_HIDDEN,
					'manager_group_id' => OPT_ATT_HIDDEN,
					'manager_id' => OPT_ATT_HIDDEN,
				),
			)
		);
		MetaModel::Init_DefineState(
			"validated",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
					'title' => OPT_ATT_READONLY,
					'reason' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_MANDATORY,
					'supervisor_group_id' => OPT_ATT_MANDATORY,
					'manager_group_id' => OPT_ATT_MANDATORY,
					'description' => OPT_ATT_READONLY,
					'requestor_id' => OPT_ATT_READONLY,
					'title' => OPT_ATT_MANDATORY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"rejected",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"assigned",
			array(
				"attribute_inherit" => 'validated',
				"attribute_list" => array(
					'workgroup_id' => OPT_ATT_MANDATORY,
					'agent_id' => OPT_ATT_MUSTCHANGE,
					'supervisor_id' => OPT_ATT_MUSTCHANGE,
					'manager_id' => OPT_ATT_MUSTCHANGE,
					'description' => OPT_ATT_READONLY,
					'requestor_id' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"plannedscheduled",
			array(
				"attribute_inherit" => 'assigned',
				"attribute_list" => array(
					'ticket_log' => OPT_ATT_MANDATORY,
					'requestor_id' => OPT_ATT_READONLY,
					'org_id' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_READONLY,
					'start_date' => OPT_ATT_MANDATORY,
					'end_date' => OPT_ATT_MANDATORY,
					'impact' => OPT_ATT_MANDATORY,
					'agent_id' => OPT_ATT_MANDATORY,
					'supervisor_group_id' => OPT_ATT_MANDATORY,
					'supervisor_id' => OPT_ATT_MANDATORY,
					'manager_group_id' => OPT_ATT_MANDATORY,
					'manager_id' => OPT_ATT_MANDATORY,
					'fallback' => OPT_ATT_MANDATORY,
					'outage' => OPT_ATT_MUSTPROMPT,
				),
			)
		);
		MetaModel::Init_DefineState(
			"approved",
			array(
				"attribute_inherit" => 'plannedscheduled',
				"attribute_list" => array(
					'start_date' => OPT_ATT_READONLY,
					'end_date' => OPT_ATT_MANDATORY,
					'impact' => OPT_ATT_READONLY,
					'supervisor_group_id' => OPT_ATT_READONLY,
					'supervisor_id' => OPT_ATT_READONLY,
					'manager_group_id' => OPT_ATT_READONLY,
					'manager_id' => OPT_ATT_READONLY,
					'outage' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"notapproved",
			array(
				"attribute_inherit" => 'plannedscheduled',
				"attribute_list" => array(
					'impact' => OPT_ATT_READONLY,
					'supervisor_group_id' => OPT_ATT_READONLY,
					'supervisor_id' => OPT_ATT_READONLY,
					'manager_group_id' => OPT_ATT_READONLY,
					'manager_id' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"implemented",
			array(
				"attribute_inherit" => 'approved',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"monitored",
			array(
				"attribute_inherit" => 'implemented',
				"attribute_list" => array(
					'end_date' => OPT_ATT_READONLY,
					'agent_id' => OPT_ATT_READONLY,
					'fallback' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"closed",
			array(
				"attribute_inherit" => 'monitored',
				"attribute_list" => array(
					'ticket_log' => OPT_ATT_READONLY,
					'close_date' => OPT_ATT_READONLY,
				),
			)
		);

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

	}

	public function SetClosureDate($sStimulusCode)
	{
		$this->Set('close_date', time());
		return true;
	}

	protected function OnInsert()
	{
		$oToNotify = $this->Get('contact_list');
		$oToImpact = $this->Get('ci_list');

		$oImpactedInfras = DBObjectSet::FromLinkSet($this, 'ci_list', 'ci_id');
	
		$aComputed = $oImpactedInfras->GetRelatedObjects('impacts', 10);
	
		if (isset($aComputed['FunctionalCI']) && is_array($aComputed['FunctionalCI']))
		{
			foreach($aComputed['FunctionalCI'] as $iKey => $oObject)
			{
				$oNewLink = new lnkTicketToCI();
				$oNewLink->Set('ci_id', $iKey);
				$oToImpact->AddObject($oNewLink);
			}
		}
		if (isset($aComputed['Contact']) && is_array($aComputed['Contact']))
		{
			foreach($aComputed['Contact'] as $iKey => $oObject)
			{
				$oNewLink = new lnkTicketToContact();
				$oNewLink->Set('contact_id', $iKey);
				$oNewLink->Set('role', 'contact automatically computed');
				$oToNotify->AddObject($oNewLink);
			}
		}

		$this->Set('creation_date', time());
		$this->Set('last_update', time());
	}

	protected function OnUpdate()
	{
		$this->Set('last_update', time());
	}

	public function ComputeValues()
	{
		$sCurrRef = $this->Get('ref');
		if (strlen($sCurrRef) == 0)
		{
			$iKey = $this->GetKey();
			if ($iKey < 0)
			{
				// Object not yet in the Database
				$iKey = MetaModel::GetNextKey(get_class($this));
			}
			$sName = sprintf('C-%06d', $iKey);
			$this->Set('ref', $sName);
		}
	}

	/**
	 * Get the icon representing this object
	 * @param boolean $bImgTag If true the result is a full IMG tag (or an emtpy string if no icon is defined)
	 * @return string Either the full IMG tag ($bImgTag == true) or just the path to the icon file
	 */
	public function GetIcon($bImgTag = true)
	{
		$sStatus = $this->Get('status');
		switch($this->GetState())
		{
			case 'approved':
			case 'implemented':
			case 'monitored':
			$sIcon = self::MakeIconFromName('change-approved.png');
			break;
			
			case 'rejected':
			case 'notapproved':
			$sIcon = self::MakeIconFromName('change-rejected.png');
			break;

			case 'closed':
			$sIcon = self::MakeIconFromName('change-closed.png');
			break;

			default:
			$sIcon = MetaModel::GetClassIcon(get_class($this), $bImgTag);
		}
		return $sIcon;
	}
	
	protected static function MakeIconFromName($sIconName, $bImgTag = true)
	{
		$sIcon = '';
		if ($sIconName != '')
		{
			$sPath = '../modules/itop-change-mgmt-1.0.0/images/'.$sIconName;
			if ($bImgTag)
			{
				$sIcon = "<img src=\"$sPath\" style=\"vertical-align:middle;\"/>";
			}
			else
			{
				$sIcon  = $sPath;
			}
		}
		return $sIcon;
	}
}

class RoutineChange extends Change
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_routine",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('title', 'org_id', 'description','ticket_log', 'start_date', 'end_date','document_list', 'ci_list', 'contact_list','incident_list', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'fallback'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date', 'end_date','status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date','end_date', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('list', array('title', 'org_id', 'start_date', 'status', 'requestor_id'));

		MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_implement", array("target_state"=>"implemented", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));		
	}
}

abstract class ApprovedChange extends Change
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_approved",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_AddAttribute(new AttributeDateTime("approval_date", array("allowed_values"=>null, "sql"=>"approval_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("approval_comment", array("allowed_values"=>null, "sql"=>"approval_comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'org_id', 'description','ticket_log', 'start_date', 'end_date','document_list', 'ci_list', 'contact_list','incident_list', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date','end_date', 'status', 'reason', 'requestor_id', 'workgroup_id', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage','approval_date'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date','end_date', 'status', 'reason', 'requestor_id', 'workgroup_id', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'approval_date'));
		MetaModel::Init_SetZListItems('list', array('title', 'org_id', 'start_date', 'status', 'requestor_id'));

		MetaModel::Init_OverloadStateAttribute('new', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('new', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('validated', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('validated', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('rejected', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('rejected', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('assigned', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('assigned', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('plannedscheduled', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('plannedscheduled', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('notapproved', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('notapproved', 'approval_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('approved', 'approval_date', OPT_ATT_MANDATORY);
		MetaModel::Init_OverloadStateAttribute('approved', 'approval_comment', OPT_ATT_MANDATORY);
		MetaModel::Init_OverloadStateAttribute('implemented', 'approval_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('implemented', 'approval_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('monitored', 'approval_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('monitored', 'approval_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('closed', 'approval_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('closed', 'approval_comment', OPT_ATT_READONLY);
	}
}

class NormalChange extends ApprovedChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_normal",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_AddAttribute(new AttributeDateTime("acceptance_date", array("allowed_values"=>null, "sql"=>"acceptance_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("acceptance_comment", array("allowed_values"=>null, "sql"=>"acceptance_comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'org_id', 'description','ticket_log', 'start_date','end_date', 'document_list', 'ci_list', 'contact_list','incident_list', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update','close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'fallback', 'approval_date', 'approval_comment', 'acceptance_date', 'acceptance_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date', 'end_date','status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date','end_date', 'status', 'reason', 'requestor_id', 'workgroup_id', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage'));
		MetaModel::Init_SetZListItems('list', array('title', 'org_id', 'start_date', 'status', 'requestor_id'));

		MetaModel::Init_OverloadStateAttribute('new', 'acceptance_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('new', 'acceptance_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('validated', 'acceptance_date', OPT_ATT_MANDATORY);
		MetaModel::Init_OverloadStateAttribute('validated', 'acceptance_comment', OPT_ATT_MANDATORY);
		MetaModel::Init_OverloadStateAttribute('rejected', 'acceptance_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('rejected', 'acceptance_comment', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('plannedscheduled', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('plannedscheduled', 'acceptance_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('approved', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('approved', 'acceptance_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('notapproved', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('notapproved', 'acceptance_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('implemented', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('implemented', 'acceptance_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('monitored', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('monitored', 'acceptance_comment', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('closed', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('closed', 'acceptance_comment', OPT_ATT_READONLY);

		MetaModel::Init_DefineTransition("new", "ev_validate", array("target_state"=>"validated", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("new", "ev_reject", array("target_state"=>"rejected", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("rejected", "ev_reopen", array("target_state"=>"new", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("validated", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_approve", array("target_state"=>"approved", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_notapprove", array("target_state"=>"notapproved", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("notapproved", "ev_replan", array("target_state"=>"plannedscheduled", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("approved", "ev_implement", array("target_state"=>"implemented", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));		
	}
}

class EmergencyChange extends ApprovedChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_emergency",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('title', 'org_id', 'description','ticket_log', 'start_date', 'end_date','document_list', 'ci_list', 'contact_list','incident_list', 'status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date','end_date', 'status', 'reason', 'requestor_id', 'workgroup_id', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'approval_date'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date', 'end_date','status', 'reason', 'requestor_id', 'workgroup_id', 'creation_date', 'last_update', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'approval_date'));
		MetaModel::Init_SetZListItems('list', array('title', 'org_id', 'start_date', 'status', 'requestor_id'));

		MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_approve", array("target_state"=>"approved", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_notapprove", array("target_state"=>"notapproved", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("notapproved", "ev_replan", array("target_state"=>"plannedscheduled", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("approved", "ev_implement", array("target_state"=>"implemented", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));		
	}
}


$oMyMenuGroup = new MenuGroup('ChangeManagement', 50 /* fRank */);
new TemplateMenuNode('Change:Overview', '../modules/itop-change-mgmt-1.0.0/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewChange', 'Change', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchChanges', 'Change', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Change:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
$oNode = new OQLMenuNode('MyChanges', 'SELECT Change WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Changes', 'SELECT Change WHERE status != "closed"', $oShortcutNode->GetIndex(), 2 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('WaitingApproval', 'SELECT ApprovedChange WHERE status IN ("plannedscheduled")', $oShortcutNode->GetIndex(), 3 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('WaitingAcceptance', 'SELECT NormalChange WHERE status IN ("new")', $oShortcutNode->GetIndex(), 4 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
?>
