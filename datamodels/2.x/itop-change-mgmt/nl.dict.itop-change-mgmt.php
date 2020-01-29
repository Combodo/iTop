<?php
// Copyright (C) 2010-2019 Combodo SARL
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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ChangeManagement' => 'Change Management',
	'Menu:Change:Overview' => 'Overzicht',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nieuwe change',
	'Menu:NewChange+' => 'Maak een nieuwe change aan',
	'Menu:SearchChanges' => 'Zoek naar changes',
	'Menu:SearchChanges+' => 'Zoek naar changes',
	'Menu:Change:Shortcuts' => 'Snelkoppelingen',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes die acceptatie vereisen',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes die goedkeuring vereisen',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Open changes',
	'Menu:Changes+' => 'Alle open changes',
	'Menu:MyChanges' => 'Changes toegewezen aan mij',
	'Menu:MyChanges+' => 'Changes toegewezen aan mij (als agent)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes per categorie van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-Last-7-days' => 'Aantal changes van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes per domein van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes per status van de afgelopen 7 dagen',
	'Tickets:Related:OpenChanges' => 'Open changes',
	'Tickets:Related:RecentChanges' => 'Recente changes (72u)',
));

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+


//
// Class: Change
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Change' => 'Change',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nieuw',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Toegewezen',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Gepland',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Afgekeurd',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Goedgekeurd',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Gesloten',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Categorie',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Applicatie',
	'Class:Change/Attribute:category/Value:application+' => 'Applicatie',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardware',
	'Class:Change/Attribute:category/Value:hardware+' => 'Hardware',
	'Class:Change/Attribute:category/Value:network' => 'Netwerk',
	'Class:Change/Attribute:category/Value:network+' => 'Netwerk',
	'Class:Change/Attribute:category/Value:other' => 'Anders',
	'Class:Change/Attribute:category/Value:other+' => 'Anders',
	'Class:Change/Attribute:category/Value:software' => 'Software',
	'Class:Change/Attribute:category/Value:software+' => 'Software',
	'Class:Change/Attribute:category/Value:system' => 'Systeem',
	'Class:Change/Attribute:category/Value:system+' => 'Systeem',
	'Class:Change/Attribute:reject_reason' => 'Reden van afwijzing',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change manager',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'E-mailadres change manager',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Hoofdchange',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Ref. hoofdchange',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Aangemaakt op',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Goedgekeurd op',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Backupplan',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Gerelateerde verzoeken',
	'Class:Change/Attribute:related_request_list+' => 'Alle gebruikersverzoeken gerelateerd aan deze change',
	'Class:Change/Attribute:related_incident_list' => 'Gerelateerde incidenten',
	'Class:Change/Attribute:related_incident_list+' => 'Alle incidenten gerelateerd	aan deze change',
	'Class:Change/Attribute:related_problems_list' => 'Gerelateerde problemen',
	'Class:Change/Attribute:related_problems_list+' => 'Alle problemen gerelateerd aan deze change',
	'Class:Change/Attribute:child_changes_list' => 'Subchanges',
	'Class:Change/Attribute:child_changes_list+' => 'Alle subchanges gerelateerd aan deze change',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Hoofdchange herkenbare naam',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Wijs toe',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Plan in',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Weiger',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Heropen',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Keur goed',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Sluit af',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Onderbreking',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Nee',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Ja',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));
