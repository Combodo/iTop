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
 * @author	Stephan Rosenke <stephan.rosenke@itomig.de>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referenz',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organisation',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Melder',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:team_id' => 'Team',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Bearbeiter',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:title' => 'Titel',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Beschreibung',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Gestartet',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Enddatum',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Letztes Update',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Schließdatum',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Privates Log',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Kontakte',
	'Class:Ticket/Attribute:contacts_list+' => '',
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => '',
	'Class:Ticket/Attribute:workorders_list' => 'Arbeitsaufträge',
	'Class:Ticket/Attribute:workorders_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Typ',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:lnkContactToTicket' => 'Verknüpfung Kontakt/Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rolle',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkFunctionalCIToTicket' => 'Verknüpfung FunctionalCI/Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Auswirkung',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:WorkOrder' => 'Arbeitsauftrag',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Name',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Status',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Offen',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Geschlossen',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Beschreibung',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Team',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Bearbeiter',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Startdatum',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Enddatum',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Schließen',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
	'Class:Ticket/Attribute:org_name' => 'Organisationsname',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Meldername',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_name' => 'Teamname',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Bearbeitername',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Referenz',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Kontakt-Email',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referenz',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI-Name',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Referenziertes Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Team-Name',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Melder-Email',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Ticket:baseinfo' => 'Allgemeine Informationen',
	'Ticket:date' => 'Daten',
	'Ticket:contact' => 'Kontakte',
	'Ticket:moreinfo' => 'weitergehende Informationen',
	'Ticket:relation' => 'Beziehungen',
	'Ticket:log' => 'Kommunikation',
	'Ticket:Type' => 'Qualifikation',
	'Ticket:support' => 'Support',
	'Ticket:resolution' => 'Lösung',
	'Ticket:SLA' => 'SLA-Report',
	'WorkOrder:Details' => 'Details',
	'WorkOrder:Moreinfo' => 'Weitere Informationen',
));
?>