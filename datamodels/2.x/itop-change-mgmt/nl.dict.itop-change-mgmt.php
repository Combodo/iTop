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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
'Menu:ChangeManagement' => 'Change management',
	'Menu:Change:Overview' => 'Overzicht',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nieuwe change',
	'Menu:NewChange+' => 'Maak een nieuwe change ticket aan',
	'Menu:SearchChanges' => 'Zoek voor changes',
	'Menu:SearchChanges+' => 'Zoek voor change tickets',
	'Menu:Change:Shortcuts' => 'Snelkoppelingen',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Changes die nog acceptatie vereisen',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Changes die nog goedkeuring vereisen',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Open changes',
	'Menu:Changes+' => 'Alle open changes',
	'Menu:MyChanges' => 'Changes toegewezen aan mij',
	'Menu:MyChanges+' => 'Changes toegewezen door mij (als Agent)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Changes per categorie van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-Last-7-days' => 'Aantal changes van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Changes per domein van de afgelopen 7 dagen',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Changes per status van de afgelopen 7 dagen',
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

Dict::Add('NL NL', "Dutch", "Nederlands", array(
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
	'Class:Change/Attribute:status/Value:rejected' => 'Rejected',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Goedgekeurd',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Gesloten',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Categorie',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'applicatie',
	'Class:Change/Attribute:category/Value:application+' => 'applicatie',
	'Class:Change/Attribute:category/Value:hardware' => 'hardware',
	'Class:Change/Attribute:category/Value:hardware+' => 'hardware',
	'Class:Change/Attribute:category/Value:network' => 'netwerk',
	'Class:Change/Attribute:category/Value:network+' => 'netwerk',
	'Class:Change/Attribute:category/Value:other' => 'anders',
	'Class:Change/Attribute:category/Value:other+' => 'anders',
	'Class:Change/Attribute:category/Value:software' => 'software',
	'Class:Change/Attribute:category/Value:software+' => 'software',
	'Class:Change/Attribute:category/Value:system' => 'systeem',
	'Class:Change/Attribute:category/Value:system+' => 'systeem',
	'Class:Change/Attribute:reject_reason' => 'Reden van afwijzing',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change manager',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'Change manager email',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Hoofd change',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Hoofd change ref',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Creatie datum',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Goedkeuring datum',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Backup plan',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Gerelateerde verzoeken',
	'Class:Change/Attribute:related_request_list+' => 'Alle gebruikersverzoeken gelinkt aan deze change',
	'Class:Change/Attribute:related_incident_list' => 'Gerelateerde incidenten',
	'Class:Change/Attribute:related_incident_list+' => 'Alle incidenten die gelinkt zijn aan deze change',
	'Class:Change/Attribute:related_problems_list' => 'Gerelateerde problemen',
	'Class:Change/Attribute:related_problems_list+' => 'Alle problemen gelinkt aan deze change',
	'Class:Change/Attribute:child_changes_list' => 'Sub changes',
	'Class:Change/Attribute:child_changes_list+' => 'Alle sub changes gelinkt aan deze change',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Hoofd change friendly name',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Wijs toe',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Plan',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Wijs af',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Heropen',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Keur goed',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Sluit',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Storing',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Nee',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Yes',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));

?>
