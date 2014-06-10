<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * @author 	David M. Gümbel <david.guembel@itomig.de>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Change' => 'Change',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Neu',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Zugewiesen',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Geplant',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Abgelehnt',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Genehmigt',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Geschlossen',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Kategorie',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Applikation',
	'Class:Change/Attribute:category/Value:application+' => '',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
	'Class:Change/Attribute:category/Value:hardware+' => '',
	'Class:Change/Attribute:category/Value:network' => 'Netzwerk',
	'Class:Change/Attribute:category/Value:network+' => '',
	'Class:Change/Attribute:category/Value:other' => 'Andere',
	'Class:Change/Attribute:category/Value:other+' => '',
	'Class:Change/Attribute:category/Value:software' => 'Software',
	'Class:Change/Attribute:category/Value:software+' => '',
	'Class:Change/Attribute:category/Value:system' => 'System',
	'Class:Change/Attribute:category/Value:system+' => '',
	'Class:Change/Attribute:reject_reason' => 'Ablehnungsgrund',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change Manager',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:parent_id' => 'Parent Change',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:creation_date' => 'Erstellungsdatum',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Genehmigungsdatum',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Fallback-Plan',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Verwandte Requests',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Abgeleitete Changes',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Zuweisen',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planen',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Zurückweisen',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Wiedereröffnen',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Genehmigen',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Schließen',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Menu:ChangeManagement' => 'Change Management',
	'Menu:Change:Overview' => 'Übersicht',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Neuer Change',
	'Menu:NewChange+' => '',
	'Menu:SearchChanges' => 'Suche nach Changes',
	'Menu:SearchChanges+' => '',
	'Menu:Change:Shortcuts' => 'Shortcuts',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes die auf Annahme warten',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes die auf Genehmigung warten',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Offene Changes',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Changes die mit zugewiesen sind',
	'Menu:MyChanges+' => '',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes der letzten sieben Tage nach Kategorie',
	'UI-ChangeManagementOverview-Last-7-days' => 'Zahl der Changes in den letzten sieben Tagen',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes der letzten sieben Tage nach Typ',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes der letzten sieben Tage nach Status',
	'Class:Change/Attribute:changemanager_email' => 'Change Manager Email',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_name' => 'Parent Change ref',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Verwandte Incidents',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Parent Change Friendly Name',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Attribute:outage' => 'Ausfall',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Nein',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Ja',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));
?>