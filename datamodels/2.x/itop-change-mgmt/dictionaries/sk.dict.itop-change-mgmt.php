<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Menu:ChangeManagement' => 'Manažment zmien',
	'Menu:Change:Overview' => 'Prehľad',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nová zmena',
	'Menu:NewChange+' => '',
	'Menu:SearchChanges' => 'Vyhľadať zmeny',
	'Menu:SearchChanges+' => '',
	'Menu:Change:Shortcuts' => 'Skratky',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Zmeny očakávajúce prijatie',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Zmeny očakávajúce schválenie',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Otvorené zmeny',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => 'Zmeny pridelené mne',
	'Menu:MyChanges+' => '',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Zmeny podľa kategórie za posledných 7 dní',
	'UI-ChangeManagementOverview-Last-7-days' => 'Počet zmien za posledných 7 dní',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Zmeny podľa domény za posledných 7 dní',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Zmeny podľa stavu za posledných 7 dní',
	'Tickets:Related:OpenChanges' => 'Open changes~~',
	'Tickets:Related:RecentChanges' => 'Recent changes (72h)~~',
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

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Change' => 'Zmena',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Stav',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nová',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Pridelená',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Plánovaná',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Zamietnutá',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Schválená',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Zatvorená',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Kategória',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'Aplikácia',
	'Class:Change/Attribute:category/Value:application+' => '',
	'Class:Change/Attribute:category/Value:hardware' => 'Hardvér',
	'Class:Change/Attribute:category/Value:hardware+' => '',
	'Class:Change/Attribute:category/Value:network' => 'Sieť',
	'Class:Change/Attribute:category/Value:network+' => '',
	'Class:Change/Attribute:category/Value:other' => 'Iné',
	'Class:Change/Attribute:category/Value:other+' => '',
	'Class:Change/Attribute:category/Value:software' => 'Softvér',
	'Class:Change/Attribute:category/Value:software+' => '',
	'Class:Change/Attribute:category/Value:system' => 'Systém',
	'Class:Change/Attribute:category/Value:system+' => '',
	'Class:Change/Attribute:reject_reason' => 'Dôvod zamietnutia',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Change manager~~',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'Email manažéra zmien',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Nadradená zmena',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Referencia na rodičovskú zmenu',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Dátum vytvorenia',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Dátum schválenia',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Návratový plán',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Súvisiace požiadavky',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:related_incident_list' => 'Súvisiace incidenty',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:related_problems_list' => 'Súvisiace problémy',
	'Class:Change/Attribute:related_problems_list+' => '',
	'Class:Change/Attribute:child_changes_list' => 'Podriadené zmeny',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Priateľské meno rodičovskej zmeny',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Prideiť',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Naplánuj',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Zamietnúť',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Znova otvoriť',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Schváliť',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Zatvoriť',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Outage~~',
	'Class:Change/Attribute:outage+' => '~~',
	'Class:Change/Attribute:outage/Value:no' => 'No~~',
	'Class:Change/Attribute:outage/Value:no+' => '~~',
	'Class:Change/Attribute:outage/Value:yes' => 'Yes~~',
	'Class:Change/Attribute:outage/Value:yes+' => '~~',
));
