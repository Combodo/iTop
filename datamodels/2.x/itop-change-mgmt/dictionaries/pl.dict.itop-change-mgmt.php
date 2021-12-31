<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:ChangeManagement' => 'Zarządzanie zmianami',
	'Menu:Change:Overview' => 'Przegląd',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => 'Nowa zmiana',
	'Menu:NewChange+' => 'Utwórz nowe zgłoszenie zmiany',
	'Menu:SearchChanges' => 'Szukaj zmian',
	'Menu:SearchChanges+' => 'Szukaj zgłoszeń zmian',
	'Menu:Change:Shortcuts' => 'Skróty',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => 'Zmiany do akceptacji',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => 'Zmiany do zatwierdzenia',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'Otwarte zmiany',
	'Menu:Changes+' => 'Wszystkie otwarte zmiany',
	'Menu:MyChanges' => 'Zmiany przypisane do mnie',
	'Menu:MyChanges+' => 'Zmiany przypisane do mnie (jako Agent)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Zmiany według kategorii w ciągu ostatnich 7 dni',
	'UI-ChangeManagementOverview-Last-7-days' => 'Liczba zmian w ciągu ostatnich 7 dni',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Zmiany według domeny w ciągu ostatnich 7 dni',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Zmiany według statusu z ostatnich 7 dni',
	'Tickets:Related:OpenChanges' => 'Otwarte zmiany',
	'Tickets:Related:RecentChanges' => 'Ostatnie zmiany (72h)',
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Change' => 'Zmiana',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => 'Status',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => 'Nowa',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Przydzielona',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Planowana',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Odrzucona',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Zatwierdzona',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Zamknięta',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Category',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'aplikacja',
	'Class:Change/Attribute:category/Value:application+' => 'aplikacja',
	'Class:Change/Attribute:category/Value:hardware' => 'sprzęt komputerowy',
	'Class:Change/Attribute:category/Value:hardware+' => 'sprzęt komputerowy',
	'Class:Change/Attribute:category/Value:network' => 'sieć',
	'Class:Change/Attribute:category/Value:network+' => 'sieć',
	'Class:Change/Attribute:category/Value:other' => 'inne',
	'Class:Change/Attribute:category/Value:other+' => 'inne',
	'Class:Change/Attribute:category/Value:software' => 'oprogramowanie',
	'Class:Change/Attribute:category/Value:software+' => 'oprogramowanie',
	'Class:Change/Attribute:category/Value:system' => 'system',
	'Class:Change/Attribute:category/Value:system+' => 'system',
	'Class:Change/Attribute:reject_reason' => 'Powód odrzucenia',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => 'Menedżer zmiany',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:changemanager_email' => 'E-mail menedżera zmiany',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_id' => 'Zmiana źródłowa',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:parent_name' => 'Zmiana źródłowa',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:creation_date' => 'Data utworzenia',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => 'Data zatwierdzenia',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'Plan awaryjny',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => 'Powiązane wnioski',
	'Class:Change/Attribute:related_request_list+' => 'Wszystkie prośby użytkowników powiązane z tą zmianą',
	'Class:Change/Attribute:related_incident_list' => 'Powiązane incydenty',
	'Class:Change/Attribute:related_incident_list+' => 'Wszystkie incydenty związane z tą zmianą',
	'Class:Change/Attribute:related_problems_list' => 'Powiązane problemy',
	'Class:Change/Attribute:related_problems_list+' => 'Wszystkie problemy związane z tą zmianą',
	'Class:Change/Attribute:child_changes_list' => 'Zmiany zależne',
	'Class:Change/Attribute:child_changes_list+' => 'Wszystkie zmiany podrzędne powiązane z tą zmianą',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Przyjazna nazwa zmiany źródłowej',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Class:Change/Stimulus:ev_assign' => 'Przydzielona',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => 'Zaplanowana',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => 'Odrzuona',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => 'Wznowiona',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => 'Zatwierdona',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'Zamknięta',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Class:Change/Attribute:outage' => 'Awaria',
	'Class:Change/Attribute:outage+' => '',
	'Class:Change/Attribute:outage/Value:no' => 'Nie',
	'Class:Change/Attribute:outage/Value:no+' => '',
	'Class:Change/Attribute:outage/Value:yes' => 'Tak',
	'Class:Change/Attribute:outage/Value:yes+' => '',
));
