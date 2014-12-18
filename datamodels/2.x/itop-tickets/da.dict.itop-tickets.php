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
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Reference',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organisation',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Bruger',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:team_id' => 'Team',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Tildelt til',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:title' => 'Titel',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Beskrivelse',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Start dato',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Slut dato',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Sidste opdatering',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Lukket dato',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Privat Log',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Kontakt',
	'Class:Ticket/Attribute:contacts_list+' => '',
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => '',
	'Class:Ticket/Attribute:workorders_list' => 'Arbejdsordre',
	'Class:Ticket/Attribute:workorders_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Type',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:lnkContactToTicket' => 'Sammenhæng Kontakt/Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rolle',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkFunctionalCIToTicket' => 'Sammenhæng FunctionalCI/Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Påvirkning',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:WorkOrder' => 'Arbejdsordre',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Navn',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Status',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Åben',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Lukket',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Beskrivelse',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Team',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Tildelt til',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Start dato',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Slut dato',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Luk',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
	'Class:Ticket/Attribute:org_name' => 'Organisations navn',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Bruger navn',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_name' => 'Team navn',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Tildelt til',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Reference',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Kontakt Email',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Reference',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI Navn',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Refererede Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Team navn',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Bruger Email',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Ticket:baseinfo' => 'Almindelig information',
	'Ticket:date' => 'Dato',
	'Ticket:contact' => 'Kontakt',
	'Ticket:moreinfo' => 'Yderligere information',
	'Ticket:relation' => 'Betegnelse',
	'Ticket:log' => 'Kommunikation',
	'Ticket:Type' => 'Qualifikation',
	'Ticket:support' => 'Support',
	'Ticket:resolution' => 'Løsning',
	'Ticket:SLA' => 'SLA Report',
	'WorkOrder:Details' => 'Detaljer',
	'WorkOrder:Moreinfo' => 'Yderligere information',
));
?>