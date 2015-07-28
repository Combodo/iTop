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
 * @author	Erik Bøg <erik@boegmoeller.dk>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Change' => 'Change',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => 'Status for emnet',
	'Class:Change/Attribute:status/Value:new' => 'Ny',
	'Class:Change/Attribute:status/Value:new+' => 'Opret ny',
	'Class:Change/Attribute:status/Value:assigned' => 'Tildelt',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Planlagt',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Afslået',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Godkendt',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Lukket',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Kategori',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Applikation',
	'Class:Change/Attribute:category/Value:application+' => '',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
	'Class:Change/Attribute:category/Value:hardware+' => '',
	'Class:Change/Attribute:category/Value:network' => 'Netværk',
	'Class:Change/Attribute:category/Value:network+' => '',
	'Class:Change/Attribute:category/Value:other' => 'Andet',
	'Class:Change/Attribute:category/Value:other+' => '',
	'Class:Change/Attribute:category/Value:software' => 'Software',
	'Class:Change/Attribute:category/Value:software+' => '',
	'Class:Change/Attribute:category/Value:system' => 'System',
	'Class:Change/Attribute:category/Value:system+' => '',
	'Class:Change/Attribute:reject_reason' => 'Årsag til afslag',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change Manager',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:parent_id' => 'Parent Change',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:creation_date' => 'Oprettelsesdato',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Godkendelsesdato',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Fallback-Plan',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Relaterede Requests',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Afledte Changes',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Tildel',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Planlæg',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Afslåp',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Genåben',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Godkend',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Luk',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Menu:ChangeManagement' => 'Change Management',
	'Menu:Change:Overview' => 'Oversigt',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Ny Change',
	'Menu:NewChange+' => '',
	'Menu:SearchChanges' => 'Søg efter Changes',
	'Menu:SearchChanges+' => '',
	'Menu:Change:Shortcuts' => 'Genveje',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes der afventer accept',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes der afventer godkendelse',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Åbne Changes',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Changes tildelt til mig',
	'Menu:MyChanges+' => '',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes de sidste 7 dage efter kategori',
	'UI-ChangeManagementOverview-Last-7-days' => 'Antal Changes i de sidste 7 dage',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes de sidste 7 dage efter type',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes de sidste 7 dage efter status',
	'Class:Change/Attribute:changemanager_email' => 'Change Manager Email',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_name' => 'Parent Change ref',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Relaterede Incidents',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Parent Change Friendly Name',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Tickets:Related:OpenChanges' => 'Open changes~~',
	'Tickets:Related:RecentChanges' => 'Recent changes (72h)~~',
	'Class:Change/Attribute:related_problems_list' => 'Relaterede problemer',
	'Class:Change/Attribute:related_problems_list+' => '',
	'Class:Change/Attribute:outage' => 'Nedetid',
	'Class:Change/Attribute:outage/Value:no' => 'Nej',
	'Class:Change/Attribute:outage/Value:yes' => 'Ja',
));
?>