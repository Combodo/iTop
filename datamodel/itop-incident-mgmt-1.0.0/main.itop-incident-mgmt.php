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

$oMyMenuGroup = new MenuGroup('IncidentManagement', 40 /* fRank */);
new TemplateMenuNode('Incident:Overview', dirname(__FILE__).'/overview.html', $oMyMenuGroup->GetIndex() /* oParent */, 0 /* fRank */);
new NewObjectMenuNode('NewIncident', 'Incident', $oMyMenuGroup->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchIncidents', 'Incident', $oMyMenuGroup->GetIndex(), 2 /* fRank */);
$oShortcutNode = new TemplateMenuNode('Incident:Shortcuts', '', $oMyMenuGroup->GetIndex(), 3 /* fRank */);
$oNode = new OQLMenuNode('Incident:MyIncidents', 'SELECT Incident WHERE agent_id = :current_contact_id AND status NOT IN ("closed", "resolved")', $oShortcutNode->GetIndex(), 1 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Incident:EscalatedIncidents', 'SELECT Incident WHERE status IN ("escalated_tto", "escalated_ttr")', $oShortcutNode->GetIndex(), 2 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'fast'));
$oNode = new OQLMenuNode('Incident:OpenIncidents', 'SELECT Incident WHERE status IN ("new", "assigned", "escalated_tto", "escalated_ttr", "resolved")', $oShortcutNode->GetIndex(), 3 /* fRank */);
$oNode->SetParameters(array('auto_reload' => 'standard'));

?>
