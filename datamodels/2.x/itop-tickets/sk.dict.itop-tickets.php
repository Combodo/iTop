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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referencia',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organizácia',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Názov organizácie',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Zadávateľ',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Meno volajúceho',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Tím',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Názov tímu',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Agent',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Meno agenta',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Nadpis',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Popis',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Dátum začiatku',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Dátum ukončenia',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Posledná aktualizácia',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Dátum uzavretia',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Skrytý záznam',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Kontakty',
	'Class:Ticket/Attribute:contacts_list+' => '',
	'Class:Ticket/Attribute:functionalcis_list' => 'Komponenty',
	'Class:Ticket/Attribute:functionalcis_list+' => '',
	'Class:Ticket/Attribute:workorders_list' => 'Pracovné príkazy',
	'Class:Ticket/Attribute:workorders_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Typ',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Operational status~~',
	'Class:Ticket/Attribute:operational_status+' => 'Computed after the detailed status~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Ongoing~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Work in progress~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Resolved~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '~~',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Closed~~',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '~~',
	'Ticket:ImpactAnalysis' => 'Impact Analysis~~',
));


//
// Class: lnkContactToTicket
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContactToTicket' => 'väzba - Kontakt / Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Referencia na Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Email kontaktu',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rola',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Role~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Added manually~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Computed~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Do not notify~~',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkFunctionalCIToTicket' => 'väzba - Komponent / Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referencia na Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Dopad',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impact~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Added manually~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Computed~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Not impacted~~',
));


//
// Class: WorkOrder
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:WorkOrder' => 'Pracovný príkaz',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Názov',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Stav',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Otvorený',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Zatvorený',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Popis',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Referencia na Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Tím',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Názov tímu',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Agent',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Email agenta',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Dátum začiatku',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Dátum ukončenia',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Záznam',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Zatvoriť',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(

	'Ticket:baseinfo' => 'Všeobecné informácia',
	'Ticket:date' => 'Dátumy',
	'Ticket:contact' => 'Kontakt',
	'Ticket:moreinfo' => 'Viac informácií',
	'Ticket:relation' => 'Vzťahy',
	'Ticket:log' => 'Komunikácie',
	'Ticket:Type' => 'Kvalifikácia',
	'Ticket:support' => 'Podpora',
	'Ticket:resolution' => 'Riešenie',
	'Ticket:SLA' => 'SLA report',
	'WorkOrder:Details' => 'Detaily',
	'WorkOrder:Moreinfo' => 'Viac informácií',
	'Tickets:ResolvedFrom' => 'Automatically resolved from %1$s~~',

	'Class:cmdbAbstractObject/Method:Set' => 'Set~~',
	'Class:cmdbAbstractObject/Method:Set+' => 'Set a field with a static value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'SetCurrentDate~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Set a field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'SetCurrentUser~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Set a field with the currently logged in user~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'SetCurrentPerson~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Set a field with the currently logged in person (the "person" attached to the logged in "user").~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used.~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'SetElapsedTime~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Set a field with the time (seconds) elapsed since a date given by another field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Reference Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'The field from which to get the reference date~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Working Hours~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Leave empty to rely on the standard working hours scheme, or set to "DefaultWorkingTimeComputer" to force a 24x7 scheme~~',
	'Class:cmdbAbstractObject/Method:Reset' => 'Reset~~',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Reset a field to its default value~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'The field to reset, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy' => 'Copy~~',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Copy the value of a field to another field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Source Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'The field to get the value from, in the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'ApplyStimulus~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Apply the specified stimulus to the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimulus code~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'A valid stimulus code for the current class~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Time To Own~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTO~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Time To Resolve~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTR~~',

	'portal:itop-portal' => 'Standard portal~~', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => '%1$s - User portal~~',
	'Brick:Portal:UserProfile:Title' => 'My profile~~',
	'Brick:Portal:NewRequest:Title' => 'New request~~',
	'Brick:Portal:NewRequest:Title+' => '<p>Need help?</p><p>Pick from the services catalog and submit your request to our support teams.</p>~~',
	'Brick:Portal:OngoingRequests:Title' => 'Ongoing requests~~',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Follow up with your ongoing requests.</p><p>Check the progress, add comments, attach documents, acknowledge the solution.</p>~~',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'Open~~',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Resolved~~',
	'Brick:Portal:ClosedRequests:Title' => 'Closed requests~~',
));
