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
			"icon" => "../modules/itop-incident-mgmt-1.0.0/images/incident.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'org_id', 'description', 'ticket_log', 'start_date', 'tto_escalation_deadline', 'ttr_escalation_deadline', 'document_list', 'ci_list', 'contact_list','incident_list', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'org_id', 'start_date', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'org_id', 'start_date', 'status', 'caller_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'org_id', 'start_date', 'status', 'service_id', 'priority', 'workgroup_id', 'agent_id'));
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
				$oNewLink->Set('impact', 'potentially impacted (automatically computed)');
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
		parent::OnInsert();
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

			case 'escalated_tto':
			case 'escalated_ttr':
			$sIconName = self::MakeIconFromName('incident-escalated.png');
			break;
			
			case 'resolved':
			case 'closed':
			$sIcon = self::MakeIconFromName('incident-closed.png');
			break;

			case 'new':
			$sIcon = self::MakeIconFromName('incident.png');
			$oEscalationDeadline = $this->Get('tto_escalation_deadline');
			if ($oEscalationDeadline != null)
			{
				// A SLA is running
				$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));
				$iEscalationDeadline = AttributeDateTime::GetAsUnixSeconds($oEscalationDeadline);
				$ratio = ($iEscalationDeadline - time())/($iEscalationDeadline - $iStartDate);
				if ($ratio <= 0)
				{
					$sIcon = self::MakeIconFromName('incident-escalated.png');
				}
				else if ($ratio <= 0.25)
				{
					$sIcon = self::MakeIconFromName('incident-deadline.png');
				}
			}
			break;
			
			case 'assigned':
			$sIcon = self::MakeIconFromName('incident.png');
			$oEscalationDeadline = $this->Get('ttr_escalation_deadline');
			if ($oEscalationDeadline != null)
			{
				// A SLA is running
				$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));
				$iEscalationDeadline = AttributeDateTime::GetAsUnixSeconds($oEscalationDeadline);
				$ratio = ($iEscalationDeadline - time())/($iEscalationDeadline - $iStartDate);
				if ($ratio <= 0)
				{
					$sIcon = self::MakeIconFromName('incident-escalated.png');
				}
				else if ($ratio <= 0.25)
				{
					$sIcon = self::MakeIconFromName('incident-deadline.png');
				}
			}
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
			$sPath = '../modules/itop-incident-mgmt-1.0.0/images/'.$sIconName;
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

class lnkTicketToIncident extends cmdbAbstractObject
{

        public static function Init()
        {
                $aParams = array
                (
                        "category" => "bizmodel,searchable,incidentmgmt,requestmgmt",
                        "key_type" => "autoincrement",
                        "name_attcode" => "ticket_id",
                        "state_attcode" => "",
                        "reconc_keys" => array("ticket_id","incident_id"),
                        "db_table" => "lnktickettoincident",
                        "db_key_field" => "id",
                        "db_finalclass_field" => "",
                        "display_template" => "",
                );
                MetaModel::Init_Params($aParams);
                MetaModel::Init_InheritAttributes();
                MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"Ticket", "jointype"=>null, "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_ref", array("allowed_values"=>null, "extkey_attcode"=>"ticket_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalKey("incident_id", array("targetclass"=>"Incident", "jointype"=>null, "allowed_values"=>null, "sql"=>"incident_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeExternalField("incident_ref", array("allowed_values"=>null, "extkey_attcode"=>"incident_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
                MetaModel::Init_SetZListItems('details', array('ticket_id', 'incident_id','reason'));
                MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'incident_id'));
                MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'incident_id'));
                MetaModel::Init_SetZListItems('list', array('ticket_id', 'incident_id','reason'));
  
      }
}


$oMyMenuGroup = new MenuGroup('IncidentManagement', 40 /* fRank */);
new TemplateMenuNode('Incident:Overview', '../modules/itop-incident-mgmt-1.0.0/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewIncident', 'Incident', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchIncidents', 'Incident', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Incident:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
$oNode = new OQLMenuNode('Incident:MyIncidents', 'SELECT Incident WHERE agent_id = :current_contact_id', $oShortcutNode->GetIndex(), 1 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Incident:EscalatedIncidents', 'SELECT Incident WHERE status IN ("escalated_tto", "escalated_ttr")', $oShortcutNode->GetIndex(), 2 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Incident:OpenIncidents', 'SELECT Incident WHERE status IN ("new", "assigned", "escalated_tto", "escalated_ttr", "resolved")', $oShortcutNode->GetIndex(), 3 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));

?>
