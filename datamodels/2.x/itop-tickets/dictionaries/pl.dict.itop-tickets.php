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
// Class: Ticket
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Ticket' => 'Zgłoszenie',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Powiązanie',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organizacja',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Nazwa organizacji',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Zgłaszający',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Nazwa zgłaszającego',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Zespół',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Nazwa zespołu',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Agent',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Nazwa agenta',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Tytuł',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Opis',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Data rozpoczęcia',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Data zakończenia',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Ostatnia data',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Data zamknięcia',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Dziennik prywatny',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Kontakty',
	'Class:Ticket/Attribute:contacts_list+' => 'Wszystkie kontakty powiązane z tym zgłoszeniem',
	'Class:Ticket/Attribute:functionalcis_list' => 'Konfiguracje',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Wszystkie elementy konfiguracji, na które ma wpływ to zgłoszenie. Elementy oznaczone jako "Obliczone" zostały automatycznie oznaczone jako objęte zmianą. Elementy oznaczone jako "Nie ma wpływu" są wyłączone z wpływu.',
	'Class:Ticket/Attribute:workorders_list' => 'Zlecenia pracy',
	'Class:Ticket/Attribute:workorders_list+' => 'Wszystkie zlecenia pracy dla tego zgłoszenia',
	'Class:Ticket/Attribute:finalclass' => 'Podklasa zgłoszenia',
	'Class:Ticket/Attribute:finalclass+' => 'Nazwa ostatniej klasy',
	'Class:Ticket/Attribute:operational_status' => 'Status operacyjny',
	'Class:Ticket/Attribute:operational_status+' => 'Obliczane po statusie szczegółowym',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Trwający',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Praca w toku',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Rozwiązany',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Zamknięty',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => 'Analiza wpływu',
));


//
// Class: lnkContactToTicket
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkContactToTicket' => 'Połączenie Kontakt / Zgłoszenie',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Zgłoszenie',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Powiązanie',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'E-mail kontaktu',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rola (tekst)',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Rola',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Dodane ręcznie',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Obliczone',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Nie powiadamiaj',
));

//
// Class: WorkOrder
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:WorkOrder' => 'Porządek pracy',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Nazwa',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Status',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'otwarty',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'zamknięty',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Opis',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Zgłoszenie',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Powiązanie zgłoszenia',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Zespół',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Nazwa zespołu',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Agent',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'E-mail Agenta',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Data rozpoczęcia',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Data zakończenia',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Dziennik',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Zamknij',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Ticket:baseinfo' => 'Informacje ogólne',
	'Ticket:date' => 'Daty',
	'Ticket:contact' => 'Kontakty',
	'Ticket:moreinfo' => 'Więcej informacji',
	'Ticket:relation' => 'Relacje',
	'Ticket:log' => 'Komunikacja',
	'Ticket:Type' => 'Kwalifikacja',
	'Ticket:support' => 'Wsparcie',
	'Ticket:resolution' => 'Analiza',
	'Ticket:SLA' => 'Raport SLA',
	'WorkOrder:Details' => 'Szczegóły',
	'WorkOrder:Moreinfo' => 'Więcej informacji',
	'Tickets:ResolvedFrom' => 'Automatycznie rozwiązane z %1$s',
	'Class:cmdbAbstractObject/Method:Set' => 'Ustawienia',
	'Class:cmdbAbstractObject/Method:Set+' => 'Ustaw pole z wartością statyczną',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Wartość',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'Wartość do ustawienia',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'SetCurrentDate',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Ustaw pole z aktualną datą i godziną',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'SetCurrentUser',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Ustaw pole z aktualnie zalogowanym użytkownikiem',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie. Jeśli pole jest ciągiem, zostanie użyta przyjazna nazwa, w przeciwnym razie zostanie użyty identyfikator. Ta przyjazna nazwa to nazwisko osoby, jeśli jakakolwiek jest dołączona do użytkownika, w przeciwnym razie jest to login.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'SetCurrentPerson',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Ustaw pole z aktualnie zalogowaną osobą ("osobą" dołączoną do zalogowanego "użytkownika").',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie. Jeśli pole jest ciągiem, zostanie użyta przyjazna nazwa, w przeciwnym razie zostanie użyty identyfikator.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'SetElapsedTime',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Ustaw pole z czasem (w sekundach), jaki upłynął od daty podanej w innym polu',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Pole odniesienia',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'Pole, z którego ma zostać pobrana data odniesienia',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Godziny pracy',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Pozostaw puste, aby polegać na standardowym schemacie godzin pracy, lub ustaw na „DefaultWorkingTimeComputer”, aby wymusić schemat 24x7',
	'Class:cmdbAbstractObject/Method:Reset' => 'Resetuj',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Zresetuj pole do wartości domyślnej',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'Pole do zresetowania w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:Copy' => 'Kopiuj',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Skopiuj wartość pola do innego pola',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Pole docelowe',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'Pole do ustawienia w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Source Field',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'Pole, z którego ma zostać pobrana wartość, w bieżącym obiekcie',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'ApplyStimulus',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Zastosuj określony impuls do bieżącego obiektu',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Kod impulsu',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'Prawidłowy kod impulsu dla bieżącej klasy',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Czas do podjęcia TTO',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Cel oparty na SLT typu TTO',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Czas na rozwiązanie TTR',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Cel oparty na SLT typu TTR',
));

//
// Class: Document
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:Document/Attribute:contracts_list' => 'Umowy',
	'Class:Document/Attribute:contracts_list+' => 'Wszystkie umowy powiązane z tym dokumentem',
	'Class:Document/Attribute:services_list' => 'Usługi',
	'Class:Document/Attribute:services_list+' => 'Wszystkie usługi powiązane z tym dokumentem',
));