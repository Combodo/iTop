<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Nummer',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organisatie',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Naam organisatie',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Aanvrager',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Naam aanvrager',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Team',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Naam team',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Agent',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Naam agent',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Titel',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Omschrijving',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Startdatum',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Einddatum',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Laatste update',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Afgesloten sinds',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Privélog',
	'Class:Ticket/Attribute:private_log+' => 'Interne commentaar',
	'Class:Ticket/Attribute:contacts_list' => 'Contacten',
	'Class:Ticket/Attribute:contacts_list+' => 'Alle contacten gerelateerd aan dit ticket',
	'Class:Ticket/Attribute:functionalcis_list' => 'CI\'s',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Alle configuratie-items die impact hebben op dit ticket',
	'Class:Ticket/Attribute:workorders_list' => 'Werkopdrachten',
	'Class:Ticket/Attribute:workorders_list+' => 'Alle werkopdrachten voor dit ticket',
	'Class:Ticket/Attribute:finalclass' => 'Soort',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Operationele status',
	'Class:Ticket/Attribute:operational_status+' => '',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Bezig',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => '',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Afgerond',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Afgesloten',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => 'Impactanalyse',
));


//
// Class: lnkContactToTicket
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkContactToTicket' => 'Link Contact / Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'E-mailadres contact',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rol',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Rol',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Manueel toegevoegd',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Automatisch afgeleid',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Niet verwittigen',
));

//
// Class: WorkOrder
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:WorkOrder' => 'Werkopdracht',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Naam',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Status',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Open',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Gesloten',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Omschrijving',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Ref. ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Team',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Naam team',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Agent',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'E-mailadres agent',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Startdatum',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Einddatum',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Sluiten',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Ticket:baseinfo'                                                => 'Globale informatie',
	'Ticket:date'                                                    => 'Data',
	'Ticket:contact'                                                 => 'Contacten',
	'Ticket:moreinfo'                                               => 'Meer informatie',
	'Ticket:relation'                                               => 'Relaties',
	'Ticket:log'                                                    => 'Communicatie',
	'Ticket:Type'                                                   => 'Kwalificaties',
	'Ticket:support'                                                => 'Support',
	'Ticket:resolution'                                             => 'Oplossing',
	'Ticket:SLA'                                                    => 'Rapportage SLA',
	'WorkOrder:Details'                                             => 'Details',
	'WorkOrder:Moreinfo'                                            => 'Meer informatie',
	'Tickets:ResolvedFrom'                                          => 'Automatisch afgerond door %1$s',
	'Class:cmdbAbstractObject/Method:Set'                           => 'Stel in op waarde',
	'Class:cmdbAbstractObject/Method:Set+'                          => 'Stel in veldwaarde in op een statische waarde',
	'Class:cmdbAbstractObject/Method:Set/Param:1'                   => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Set/Param:1+'                  => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Set/Param:2'                   => 'Waarde',
	'Class:cmdbAbstractObject/Method:Set/Param:2+'                  => 'De waarde die moet ingesteld worden',
	'Class:cmdbAbstractObject/Method:SetCurrentDate'                => 'Stel in op huidige datum',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+'               => 'Stel de veldwaarde in op de huidige datum',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1'        => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+'       => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull'          => 'SetCurrentDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+'         => 'Set an empty field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser'                => 'Stel in op huidige gebruiker',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+'               => 'Stel de veldwaarde in op de huidige gebruiker',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1'        => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+'       => 'Het veld dat voor het huidige object ingesteld moet worden. Als het veldtype tekst is, wordt de friendly name gebruikt, anders de ID. De friendly name is de naam van de persoon indien gekend, anders wordt dit de login.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson'              => 'Stel in op huidige persoon',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+'             => 'Stel de veldwaarde in op de huidige persoon (= de persoon gelinkt aan de gebruikersaccount).',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1'      => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+'     => 'Het veld dat voor het huidige object ingesteld moet worden. Als het veldtype tekst is, wordt de friendly name gebruikt, anders de ID.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime'                => 'Stel in op verlopen tijd',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+'               => 'Stel een veld in op de tijd (in seconden) die voorbijgegaan is sinds een tijdstip gedefinieerd in een ander veld.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1'        => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+'       => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2'        => 'Referentieveld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+'       => 'Het veld waarin de referentiedatum opgegeven is',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3'        => 'Werkuren',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+'       => 'Laat leeg om te berekenen op basis van het standaard werkschema, of stel in op "DefaultWorkingTimeComputer" om een 24x7-tijdschema af te dwingen.',
	'Class:cmdbAbstractObject/Method:SetIfNull'                     => 'SetIfNull~~',
	'Class:cmdbAbstractObject/Method:SetIfNull+'                    => 'Set a field only if it is empty, with a static value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1'             => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+'            => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2'              => 'Value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+'             => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:AddValue'                       => 'AddValue~~',
	'Class:cmdbAbstractObject/Method:AddValue+'                      => 'Add a fixed value to a field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1'               => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+'              => 'The field to modify, in the current object~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2'               => 'Value~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+'              => 'Decimal value which will be added, can be negative~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate'                => 'SetComputedDate~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate+'               => 'Set a field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+'       => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2'        => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+'       => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3'        => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+'       => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull'          => 'SetComputedDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+'         => 'Set non empty field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2'  => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3'  => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:Reset'                          => 'Reset',
	'Class:cmdbAbstractObject/Method:Reset+'                         => 'Reset een veld naar de standaardwaarde.',
	'Class:cmdbAbstractObject/Method:Reset/Param:1'                  => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+'                 => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Copy'                           => 'Kopieer',
	'Class:cmdbAbstractObject/Method:Copy+'                          => 'Kopieer de waarde van een veld naar een ander veld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1'                   => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+'                  => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Copy/Param:2'                   => 'Bronveld',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+'                  => 'Het veld van het huidige object dat overgenomen moet worden',
	'Class:cmdbAbstractObject/Method:ApplyStimulus'                  => 'Stimulus uitvoeren',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+'                 => 'Voert een stimulus uit op het huidige object',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1'          => 'Stimuluscode',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+'         => 'Een geldige stimuluscode voor de huidige klasse',
	'Class:ResponseTicketTTO/Interface:iMetricComputer'              => 'Time To Own',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+'             => 'Doel gebaseerd op een SLT (TTO)',
	'Class:ResponseTicketTTR/Interface:iMetricComputer'              => 'Time To Resolve',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+'             => 'Doel gebaseerd op een SLT (TTR)',
));

//
// Class: Document
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Document/Attribute:contracts_list' => 'Contracten',
	'Class:Document/Attribute:contracts_list+' => 'Alle contracten gerelateerd aan dit document',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => 'Alle services gerelateerd aan dit document.',
));