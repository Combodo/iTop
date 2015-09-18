<?php

// Copyright (C) 2010-2014 Combodo SARL
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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:Ticket' => 'Tiket',
    'Class:Ticket+' => '',
    'Class:Ticket/Attribute:ref' => 'ID',
    'Class:Ticket/Attribute:ref+' => '',
    'Class:Ticket/Attribute:org_id' => 'Organizace',
    'Class:Ticket/Attribute:org_id+' => '',
    'Class:Ticket/Attribute:org_name' => 'Název organizace',
    'Class:Ticket/Attribute:org_name+' => '',
    'Class:Ticket/Attribute:caller_id' => 'Zadavatel',
    'Class:Ticket/Attribute:caller_id+' => '',
    'Class:Ticket/Attribute:caller_name' => 'Název zadavatele',
    'Class:Ticket/Attribute:caller_name+' => '',
    'Class:Ticket/Attribute:team_id' => 'Tým',
    'Class:Ticket/Attribute:team_id+' => '',
    'Class:Ticket/Attribute:team_name' => 'Název týmu',
    'Class:Ticket/Attribute:team_name+' => '',
    'Class:Ticket/Attribute:agent_id' => 'Řešitel',
    'Class:Ticket/Attribute:agent_id+' => '',
    'Class:Ticket/Attribute:agent_name' => 'Název řešitele',
    'Class:Ticket/Attribute:agent_name+' => '',
    'Class:Ticket/Attribute:title' => 'Název',
    'Class:Ticket/Attribute:title+' => '',
    'Class:Ticket/Attribute:description' => 'Popis',
    'Class:Ticket/Attribute:description+' => '',
    'Class:Ticket/Attribute:start_date' => 'Datum vytvoření',
    'Class:Ticket/Attribute:start_date+' => '',
    'Class:Ticket/Attribute:end_date' => 'Datum ukončení',
    'Class:Ticket/Attribute:end_date+' => '',
    'Class:Ticket/Attribute:last_update' => 'Poslední aktualizace',
    'Class:Ticket/Attribute:last_update+' => '',
    'Class:Ticket/Attribute:close_date' => 'Datum uzavření',
    'Class:Ticket/Attribute:close_date+' => '',
    'Class:Ticket/Attribute:private_log' => 'Interní záznam',
    'Class:Ticket/Attribute:private_log+' => '',
    'Class:Ticket/Attribute:contacts_list' => 'Kontakty',
    'Class:Ticket/Attribute:contacts_list+' => 'Všechny kontakty spojené s tímto tiketem',
    'Class:Ticket/Attribute:functionalcis_list' => 'Konfigurační položky',
    'Class:Ticket/Attribute:functionalcis_list+' => 'Všechny konfigurační položky ovlivněné tímto tiketem',
    'Class:Ticket/Attribute:workorders_list' => 'Pracovní příkazy',
    'Class:Ticket/Attribute:workorders_list+' => 'Všechny pracovní příkazy pro tento tiket',
    'Class:Ticket/Attribute:finalclass' => 'Typ',
    'Class:Ticket/Attribute:finalclass+' => '',
    'Ticket:ImpactAnalysis' => 'Analýza dopadů',
));

//
// Class: lnkContactToTicket
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkContactToTicket' => 'Spojení (Kontakt / Tiket)',
    'Class:lnkContactToTicket+' => '',
    'Class:lnkContactToTicket/Attribute:ticket_id' => 'Tiket',
    'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
    'Class:lnkContactToTicket/Attribute:ticket_ref' => 'ID',
    'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
    'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
    'Class:lnkContactToTicket/Attribute:contact_id+' => '',
    'Class:lnkContactToTicket/Attribute:contact_email' => 'Email kontaktu',
    'Class:lnkContactToTicket/Attribute:contact_email+' => '',
    'Class:lnkContactToTicket/Attribute:role' => 'Role (text)',
    'Class:lnkContactToTicket/Attribute:role+' => '',
    'Class:lnkContactToTicket/Attribute:role_code' => 'Role',
    'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Přidán manuálně',
    'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Automaticky',
    'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Neupozorňovat',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkFunctionalCIToTicket' => 'Spojení (Funkční konfigurační položka / Tiket)',
    'Class:lnkFunctionalCIToTicket+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Tiket',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'ID',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'Konfigurační položka',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Název konfigurační položky',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Dopad (text)',
    'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Dopad',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Přidán manuálně',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Automaticky',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Není zasažen',
));

//
// Class: WorkOrder
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:WorkOrder' => 'Pracovní příkaz',
    'Class:WorkOrder+' => '',
    'Class:WorkOrder/Attribute:name' => 'Název',
    'Class:WorkOrder/Attribute:name+' => '',
    'Class:WorkOrder/Attribute:status' => 'Stav',
    'Class:WorkOrder/Attribute:status+' => '',
    'Class:WorkOrder/Attribute:status/Value:open' => 'otevřený',
    'Class:WorkOrder/Attribute:status/Value:open+' => '',
    'Class:WorkOrder/Attribute:status/Value:closed' => 'uzavřený',
    'Class:WorkOrder/Attribute:status/Value:closed+' => '',
    'Class:WorkOrder/Attribute:description' => 'Popis',
    'Class:WorkOrder/Attribute:description+' => '',
    'Class:WorkOrder/Attribute:ticket_id' => 'Tiket',
    'Class:WorkOrder/Attribute:ticket_id+' => '',
    'Class:WorkOrder/Attribute:ticket_ref' => 'ID tiketu',
    'Class:WorkOrder/Attribute:ticket_ref+' => '',
    'Class:WorkOrder/Attribute:team_id' => 'Tým',
    'Class:WorkOrder/Attribute:team_id+' => '',
    'Class:WorkOrder/Attribute:team_name' => 'Název týmu',
    'Class:WorkOrder/Attribute:team_name+' => '',
    'Class:WorkOrder/Attribute:agent_id' => 'Řešitel',
    'Class:WorkOrder/Attribute:agent_id+' => '',
    'Class:WorkOrder/Attribute:agent_email' => 'Email řešitele',
    'Class:WorkOrder/Attribute:agent_email+' => '',
    'Class:WorkOrder/Attribute:start_date' => 'Datum začátku',
    'Class:WorkOrder/Attribute:start_date+' => '',
    'Class:WorkOrder/Attribute:end_date' => 'Datum konce',
    'Class:WorkOrder/Attribute:end_date+' => '',
    'Class:WorkOrder/Attribute:log' => 'Log',
    'Class:WorkOrder/Attribute:log+' => '',
    'Class:WorkOrder/Stimulus:ev_close' => 'Uzavřít',
    'Class:WorkOrder/Stimulus:ev_close+' => '',
));

// Fieldset translation
Dict::Add('CS CZ', 'Czech', 'Čeština', array(

    'Ticket:baseinfo' => 'Obecné informace',
    'Ticket:date' => 'Data',
    'Ticket:contact' => 'Kontakty',
    'Ticket:moreinfo' => 'Více informací',
    'Ticket:relation' => 'Vztahy',
    'Ticket:log' => 'Komunikace',
    'Ticket:Type' => 'Kvalifikace',
    'Ticket:support' => 'Podpora',
    'Ticket:resolution' => 'Řešení',
    'Ticket:SLA' => 'SLA zpráva',
    'WorkOrder:Details' => 'Detaily',
    'WorkOrder:Moreinfo' => 'Více informací',
    'Tickets:ResolvedFrom' => 'Vyřešeno automaticky na základě %1$s',

    'Class:cmdbAbstractObject/Method:Set' => 'Set',
    'Class:cmdbAbstractObject/Method:Set+' => 'Set a field with a static value',
    'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'The field to set, in the current object',
    'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Value',
    'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'The value to set',
    'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'SetCurrentDate',
    'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Set a field with the current date and time',
    'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'The field to set, in the current object',
    'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'SetCurrentUser',
    'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Set a field with the currently logged in user',
    'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'SetCurrentPerson',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Set a field with the currently logged in person (the "person" attached to the logged in "user").',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used.',
    'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'SetElapsedTime',
    'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Set a field with the time (seconds) elapsed since a date given by another field',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'The field to set, in the current object',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Reference Field',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'The field from which to get the reference date',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Working Hours',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Leave empty to rely on the standard working hours scheme, or set to "DefaultWorkingTimeComputer" to force a 24x7 scheme',
    'Class:cmdbAbstractObject/Method:Reset' => 'Reset',
    'Class:cmdbAbstractObject/Method:Reset+' => 'Reset a field to its default value',
    'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'The field to reset, in the current object',
    'Class:cmdbAbstractObject/Method:Copy' => 'Copy',
    'Class:cmdbAbstractObject/Method:Copy+' => 'Copy the value of a field to another field',
    'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Target Field',
    'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'The field to set, in the current object',
    'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Source Field',
    'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'The field to get the value from, in the current object',
    'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Time To Own',
    'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTO',
    'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Time To Resolve',
    'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTR',
));
