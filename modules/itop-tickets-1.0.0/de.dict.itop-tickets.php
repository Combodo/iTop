<?php
// Copyright (C) 2010 Combodo SARL
//
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

/**
 * Localized data
 *
 * @author   Erwan Taloc <erwan.taloc@combodo.com>
 * @author   Romain Quetiez <romain.quetiez@combodo.com>
 * @author   Denis Flaven <denis.flaven@combodo.com>
 * @license   http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referenz',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:title' => 'Titel',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Beschreibung',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:ticket_log' => 'Protokoll',
	'Class:Ticket/Attribute:ticket_log+' => '',
	'Class:Ticket/Attribute:start_date' => 'Gestartet',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:document_list' => 'Dokumente',
	'Class:Ticket/Attribute:document_list+' => 'Dokumente zu diesem Ticket',
	'Class:Ticket/Attribute:ci_list' => 'CIs',
	'Class:Ticket/Attribute:ci_list+' => 'CIs, die diesen Incident betreffen',
	'Class:Ticket/Attribute:contact_list' => 'Kontakte',
	'Class:Ticket/Attribute:contact_list+' => 'Beteiligtes Team und beteiligte Personen',
	'Class:Ticket/Attribute:incident_list' => 'Dazugehörige Incidents',
	'Class:Ticket/Attribute:incident_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Typ',
	'Class:Ticket/Attribute:finalclass+' => '',
));

//
// Class: lnkTicketToDoc
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTicketToDoc' => 'Ticket/Document',
	'Class:lnkTicketToDoc+' => '',
	'Class:lnkTicketToDoc/Attribute:ticket_id' => 'Ticket',
	'Class:lnkTicketToDoc/Attribute:ticket_id+' => '',
	'Class:lnkTicketToDoc/Attribute:ticket_ref' => 'Ticket #',
	'Class:lnkTicketToDoc/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToDoc/Attribute:document_id' => 'Dokument',
	'Class:lnkTicketToDoc/Attribute:document_id+' => '',
	'Class:lnkTicketToDoc/Attribute:document_name' => 'Dokument',
	'Class:lnkTicketToDoc/Attribute:document_name+' => '',
));

//
// Class: lnkTicketToContact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTicketToContact' => 'Ticket/Kontakt',
	'Class:lnkTicketToContact+' => '',
	'Class:lnkTicketToContact/Attribute:ticket_id' => 'Ticket',
	'Class:lnkTicketToContact/Attribute:ticket_id+' => '',
	'Class:lnkTicketToContact/Attribute:ticket_ref' => 'Ticket #',
	'Class:lnkTicketToContact/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToContact/Attribute:contact_id' => 'Kontakt',
	'Class:lnkTicketToContact/Attribute:contact_id+' => '',
	'Class:lnkTicketToContact/Attribute:contact_name' => 'Kontakt',
	'Class:lnkTicketToContact/Attribute:contact_name+' => '',
	'Class:lnkTicketToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTicketToContact/Attribute:contact_email+' => '',
	'Class:lnkTicketToContact/Attribute:role' => 'Rolle',
	'Class:lnkTicketToContact/Attribute:role+' => '',
));

//
// Class: lnkTicketToCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTicketToCI' => 'Ticket/CI',
	'Class:lnkTicketToCI+' => '',
	'Class:lnkTicketToCI/Attribute:ticket_id' => 'Ticket',
	'Class:lnkTicketToCI/Attribute:ticket_id+' => '',
	'Class:lnkTicketToCI/Attribute:ticket_ref' => 'Ticket #',
	'Class:lnkTicketToCI/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToCI/Attribute:ci_id' => 'CI',
	'Class:lnkTicketToCI/Attribute:ci_id+' => '',
	'Class:lnkTicketToCI/Attribute:ci_name' => 'CI',
	'Class:lnkTicketToCI/Attribute:ci_name+' => '',
	'Class:lnkTicketToCI/Attribute:ci_status' => 'CI-Status',
	'Class:lnkTicketToCI/Attribute:ci_status+' => '',
	'Class:lnkTicketToCI/Attribute:impact' => 'Auswirkung',
	'Class:lnkTicketToCI/Attribute:impact+' => '',
));

//
// Class: ResponseTicket
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ResponseTicket' => 'ResponseTicket',
	'Class:ResponseTicket+' => '',
	'Class:ResponseTicket/Attribute:status' => 'Status',
	'Class:ResponseTicket/Attribute:status+' => '',
	'Class:ResponseTicket/Attribute:status/Value:new' => 'Neu',
	'Class:ResponseTicket/Attribute:status/Value:new+' => 'Neu eröffnet',
	'Class:ResponseTicket/Attribute:status/Value:frozen' => 'Unerledigt',
	'Class:ResponseTicket/Attribute:status/Value:frozen+' => '',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto' => 'Eskalation/TTO',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto+' => '',
	'Class:ResponseTicket/Attribute:status/Value:assigned' => 'Zugeteilt',
	'Class:ResponseTicket/Attribute:status/Value:assigned+' => '',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr' => 'Eskalation/TTR',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr+' => '',
	'Class:ResponseTicket/Attribute:status/Value:resolved' => 'Gelöst',
	'Class:ResponseTicket/Attribute:status/Value:resolved+' => '',
	'Class:ResponseTicket/Attribute:status/Value:closed' => 'Geschlossen',
	'Class:ResponseTicket/Attribute:status/Value:closed+' => '',
	'Class:ResponseTicket/Attribute:caller_id' => 'Caller',
	'Class:ResponseTicket/Attribute:caller_id+' => '',
	'Class:ResponseTicket/Attribute:workgroup_name' => 'Arbeitsgruppe',
	'Class:ResponseTicket/Attribute:workgroup_name+' => '',
	'Class:ResponseTicket/Attribute:org_id' => 'Kunde',
	'Class:ResponseTicket/Attribute:org_id+' => '',
	'Class:ResponseTicket/Attribute:org_name' => 'Kunde',
	'Class:ResponseTicket/Attribute:org_name+' => '',
	'Class:ResponseTicket/Attribute:service_id' => 'Service',
	'Class:ResponseTicket/Attribute:service_id+' => '',
	'Class:ResponseTicket/Attribute:servicesubcategory_id' => 'Service-Element',
	'Class:ResponseTicket/Attribute:servicesubcategory_id+' => '',
	'Class:ResponseTicket/Attribute:product' => 'Produkt',
	'Class:ResponseTicket/Attribute:product+' => '',
	'Class:ResponseTicket/Attribute:impact' => 'Auswirkung',
	'Class:ResponseTicket/Attribute:impact+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:1' => 'Eine Person',
	'Class:ResponseTicket/Attribute:impact/Value:1+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:2' => 'Ein Service',
	'Class:ResponseTicket/Attribute:impact/Value:2+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:3' => 'Eine Abteilung',
	'Class:ResponseTicket/Attribute:impact/Value:3+' => '',
	'Class:ResponseTicket/Attribute:urgency' => 'Dringlichkeit',
	'Class:ResponseTicket/Attribute:urgency+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:1' => 'Niedrig',
	'Class:ResponseTicket/Attribute:urgency/Value:1+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:2' => 'Medium',
	'Class:ResponseTicket/Attribute:urgency/Value:2+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:3' => 'Hoch',
	'Class:ResponseTicket/Attribute:urgency/Value:3+' => '',
	'Class:ResponseTicket/Attribute:priority' => 'Priorität',
	'Class:ResponseTicket/Attribute:priority+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:1' => 'Niedrig',
	'Class:ResponseTicket/Attribute:priority/Value:1+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:2' => 'Medium',
	'Class:ResponseTicket/Attribute:priority/Value:2+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:3' => 'Hoch',
	'Class:ResponseTicket/Attribute:priority/Value:3+' => '',
	'Class:ResponseTicket/Attribute:workgroup_id' => 'Arbeitsgruppe',
	'Class:ResponseTicket/Attribute:workgroup_id+' => '',
	'Class:ResponseTicket/Attribute:agent_id' => 'Bearbeiter',
	'Class:ResponseTicket/Attribute:agent_id+' => '',
	'Class:ResponseTicket/Attribute:agent_name' => 'Bearbeiter',
	'Class:ResponseTicket/Attribute:agent_name+' => '',
	'Class:ResponseTicket/Attribute:agent_email' => 'Agent Email',
	'Class:ResponseTicket/Attribute:agent_email+' => '',
	'Class:ResponseTicket/Attribute:related_change_id' => 'Verbundene Änderungen',
	'Class:ResponseTicket/Attribute:related_change_id+' => '',
	'Class:ResponseTicket/Attribute:related_change_ref' => 'Verbundene Änderungen',
	'Class:ResponseTicket/Attribute:related_change_ref+' => '',
	'Class:ResponseTicket/Attribute:close_date' => 'Geschlossen',
	'Class:ResponseTicket/Attribute:close_date+' => '',
	'Class:ResponseTicket/Attribute:last_update' => 'Letzte Aktualisierung',
	'Class:ResponseTicket/Attribute:last_update+' => '',
	'Class:ResponseTicket/Attribute:assignment_date' => 'Zugeteilt',
	'Class:ResponseTicket/Attribute:assignment_date+' => '',
	'Class:ResponseTicket/Attribute:escalation_deadline' => 'Eskalationsfrist',
	'Class:ResponseTicket/Attribute:escalation_deadline+' => '',
	'Class:ResponseTicket/Attribute:closure_deadline' => 'Abschlussfrist',
	'Class:ResponseTicket/Attribute:closure_deadline+' => '',
	'Class:ResponseTicket/Attribute:resolution_code' => 'Code für Lösung',
	'Class:ResponseTicket/Attribute:resolution_code+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce' => 'Konnte nicht reproduziert werden',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate' => 'Duplikat eines bestehenden Tickets',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed' => 'Repariert',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant' => 'Irrelevant',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant+' => '',
	'Class:ResponseTicket/Attribute:solution' => 'Lösung',
	'Class:ResponseTicket/Attribute:solution+' => '',
	'Class:ResponseTicket/Attribute:user_satisfaction' => 'Benutzerzufriedenheit',
	'Class:ResponseTicket/Attribute:user_satisfaction+' => '',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1' => '1',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1+' => '1',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2' => '2',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2+' => '2',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3' => '3',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3+' => '3',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4' => '4',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4+' => '4',
	'Class:ResponseTicket/Attribute:user_commment' => 'Benutzerkommentar',
	'Class:ResponseTicket/Attribute:user_commment+' => '',
	'Class:ResponseTicket/Stimulus:ev_assign' => 'Verteilen',
	'Class:ResponseTicket/Stimulus:ev_assign+' => '',
	'Class:ResponseTicket/Stimulus:ev_reassign' => 'Neu verteilen',
	'Class:ResponseTicket/Stimulus:ev_reassign+' => '',
	'Class:ResponseTicket/Stimulus:ev_timeout' => 'ev-Timeout',
	'Class:ResponseTicket/Stimulus:ev_timeout+' => '',
	'Class:ResponseTicket/Stimulus:ev_resolve' => 'Als gelöst markieren',
	'Class:ResponseTicket/Stimulus:ev_resolve+' => '',
	'Class:ResponseTicket/Stimulus:ev_close' => 'Schließen',
	'Class:ResponseTicket/Stimulus:ev_close+' => '',
));


?>
