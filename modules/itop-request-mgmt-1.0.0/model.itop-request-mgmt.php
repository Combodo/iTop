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

class UserRequest extends ResponseTicket
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,requestmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "ticket_request",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_AddAttribute(new AttributeEnum("request_type", array("allowed_values"=>new ValueSetEnum('service request,issue,information'), "sql"=>"request_type", "default_value"=>"service request", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("freeze_reason", array("allowed_values"=>null, "sql"=>"freeze_reason", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'org_id', 'description', 'request_type','ticket_log', 'start_date', 'tto_escalation_deadline', 'ttr_escalation_deadline', 'document_list', 'ci_list', 'contact_list','incident_list', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment', 'freeze_reason'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'request_type','start_date', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'org_id', 'start_date', 'status', 'service_id', 'priority', 'workgroup_id', 'agent_id'));

		MetaModel::Init_OverloadStateAttribute('frozen', 'freeze_reason', OPT_ATT_MANDATORY);

		// The freeze reason remains hidden in all other states
		MetaModel::Init_OverloadStateAttribute('new', 'request_type', OPT_ATT_MANDATORY);
		MetaModel::Init_OverloadStateAttribute('new', 'freeze_reason', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('assigned', 'freeze_reason', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('frozen', 'freeze_reason', OPT_ATT_MANDATORY | OPT_ATT_MUSTPROMPT);
		MetaModel::Init_OverloadStateAttribute('escalated_tto', 'freeze_reason', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('escalated_ttr', 'freeze_reason', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('resolved', 'freeze_reason', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('closed', 'request_type', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('closed', 'freeze_reason', OPT_ATT_HIDDEN);

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_freeze", array()));

		MetaModel::Init_DefineTransition("assigned", "ev_freeze", array("target_state"=>"frozen", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("frozen", "ev_timeout", array("target_state"=>"escalated_ttr", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("frozen", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("frozen", "ev_resolve", array("target_state"=>"resolved", "actions"=>array(), "user_restriction"=>null));
	}

	public function ComputeValues()
	{
		$iKey = $this->GetKey();
		if ($iKey < 0)
		{
			// Object not yet in the Database
			$iKey = MetaModel::GetNextKey(get_class($this));
		}
		$sName = sprintf('R-%06d', $iKey);
		$this->Set('ref', $sName);
		return parent::ComputeValues();
	}
}

$oMyMenuGroup = new MenuGroup('RequestManagement', 30 /* fRank */);

new TemplateMenuNode('UserRequest:Overview', '../modules/itop-request-mgmt-1.0.0/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewUserRequest', 'UserRequest', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchUserRequests', 'UserRequest', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('UserRequest:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
new OQLMenuNode('UserRequest:MyRequests', 'SELECT UserRequest WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('UserRequest:EscalatedRequests', 'SELECT UserRequest WHERE status IN ("escalated_tto", "escalated_ttr")', $oShortcutNode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('UserRequest:OpenRequests', 'SELECT UserRequest WHERE status IN ("new", "assigned", "escalated_tto", "escalated_ttr", "frozen", "resolved")', $oShortcutNode->GetIndex(), 3 /* fRank */);

?>
