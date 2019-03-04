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
 * @author jbostoen (2018)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Alle configuratie-items die impact hebben op dit ticket',
	'Class:Ticket/Attribute:workorders_list' => 'Werkopdrachten',
	'Class:Ticket/Attribute:workorders_list+' => 'Alle werkopdrachten voor dit ticket',
	'Class:Ticket/Attribute:finalclass' => 'Type',
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
// Class: lnkFunctionalCIToTicket
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToTicket' => 'Link FunctionalCI / Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referentie',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impact',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impact',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Manueel toegevoegd',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Automatisch afgeleid',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Niet geïmpacteerd',
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

	'Ticket:baseinfo' => 'Globale informatie',
	'Ticket:date' => 'Data',
	'Ticket:contact' => 'Contacten',
	'Ticket:moreinfo' => 'Meer informatie',
	'Ticket:relation' => 'Relaties',
	'Ticket:log' => 'Communicatie',
	'Ticket:Type' => 'Kwalificaties',
	'Ticket:support' => 'Support',
	'Ticket:resolution' => 'Oplossing',
	'Ticket:SLA' => 'Rapportage SLA',
	'WorkOrder:Details' => 'Details',
	'WorkOrder:Moreinfo' => 'Meer informatie',
	'Tickets:ResolvedFrom' => 'Automatisch afgerond door %1$s',

	'Class:cmdbAbstractObject/Method:Set' => 'Stel in op waarde',
	'Class:cmdbAbstractObject/Method:Set+' => 'Stel in veldwaarde in op een statische waarde',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Waarde',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'De waarde die moet ingesteld worden',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'Stel in op huidige datum',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Stel de veldwaarde in op de huidige datum',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'Stel in op huidige gebruiker',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Stel de veldwaarde in op de huidige gebruiker',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden. Als het veldtype tekst is, wordt de friendly name gebruikt, anders de ID. De friendly name is de naam van de persoon indien gekend, anders wordt dit de login.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'Stel in op huidige persoon',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Stel de veldwaarde in op de huidige persoon (= de persoon gelinkt aan de gebruikersaccount).',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden. Als het veldtype tekst is, wordt de friendly name gebruikt, anders de ID.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'Stel in op verlopen tijd',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Stel een veld in op de tijd (in seconden) die voorbijgegaan is sinds een tijdstip gedefinieerd in een ander veld.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Referentieveld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'Het veld waarin de referentiedatum opgegeven is',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Werkuren',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Laat leeg om te berekenen op basis van het standaard werkschema, of stel in op "DefaultWorkingTimeComputer" om een 24x7-tijdschema af te dwingen.',
	'Class:cmdbAbstractObject/Method:Reset' => 'Reset',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Reset een veld naar de standaardwaarde.',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Copy' => 'Kopieer',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Kopieer de waarde van een veld naar een ander veld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Doelveld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'Het veld dat voor het huidige object ingesteld moet worden',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Bronveld',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'Het veld van het huidige object dat overgenomen moet worden',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'Stimulus uitvoeren',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Voert een stimulus uit op het huidige object',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimuluscode',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'Een geldige stimuluscode voor de huidige klasse',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Time To Own',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Doel gebaseerd op een SLT van het type TTO',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Time To Resolve',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Doel gebaseerd op een SLT van het type TTR',

	'portal:itop-portal' => 'Standaard portaal', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => '%1$s - Gebruikersportaal',
	'Brick:Portal:UserProfile:Title' => 'Mijn profiel',
	'Brick:Portal:NewRequest:Title' => 'Nieuw verzoek',
	'Brick:Portal:NewRequest:Title+' => '<p>Hulp nodig?</p><p>Selecteer de categorie uit de servicecatalogus en verstuur jouw verzoek naar onze support teams.</p>',
	'Brick:Portal:OngoingRequests:Title' => 'Lopende verzoeken',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Verder gaan met jouw openstaande verzoeken.</p><p>Controleer de voortgang, voeg commentaar of documenten toe, bevestig de geboden oplossing.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'Openstaand',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Opgelost',
	'Brick:Portal:ClosedRequests:Title' => 'Gesloten verzoeken',
));
