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

class Incident extends ResponseTicket
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,incidentmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "ticket_incident",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'description', 'ticket_log', 'start_date', 'escalation_deadline', 'closure_deadline', 'document_list', 'ci_list', 'contact_list', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'start_date', 'status', 'org_id', 'service_id', 'priority', 'workgroup_id', 'agent_id'));
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
		$this->Set('ref', $sName);
		return parent::ComputeValues();
	}
}

$oMyMenuGroup = new MenuGroup('IncidentManagement', 1 /* fRank */);

// By default, one entry per class
//new TemplateMenuNode('MyIncidents', 'SELECT Incident', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
// incident dont je suis caller
// incident dont je suis requester
new OQLMenuNode('OpenedIncidents', 'SELECT Incident WHERE status IN ("new", "assigned", "escalation")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
//new OQLMenuNode('EscalatedIncidents', 'SELECT Incident WHERE status IN ("escalation")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
//new TemplateMenuNode('IncidentOverview', 'SELECT Incident', $oMyMenuGroup->GetIndex(), 0 /* fRank */);


//new TemplateMenuNode('WelcomeMenuPage', '../business/templates/welcome_menu.html', $oWelcomeMenu->GetIndex() /* oParent */, 1 /* fRank */);


?>
