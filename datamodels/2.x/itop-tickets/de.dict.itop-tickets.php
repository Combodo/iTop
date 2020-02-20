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
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2017 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*		
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referenz',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organisation',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Organisationsname',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Melder',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Meldername',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Team',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Teamname',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Bearbeiter',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Bearbeitername',
	'Class:Ticket/Attribute:agent_name+' => '',
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
	'Class:Ticket/Attribute:operational_status' => 'Betriebsstatus',
	'Class:Ticket/Attribute:operational_status+' => 'Berechnet nach detailliertem Status',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'In Bearbeitung',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'In Bearbeitung',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Gelöst',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Geschlossen',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
	'Ticket:ImpactAnalysis' => 'Auswirkungsanalyse',
));


//
// Class: lnkContactToTicket
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContactToTicket' => 'Verknüpfung Kontakt/Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Referenz',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Kontakt-Email',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rolle (Text)',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Rolle',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Manuell hinzugefügt',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Berechnet',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Nicht ändern',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkFunctionalCIToTicket' => 'Verknüpfung FunctionalCI/Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referenz',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Tickettitel',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI-Name',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Auswirkung (Text)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Auswirkung',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Manuell hinzugefügt',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Berechnet',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Nicht betroffen',
));


//
// Class: WorkOrder
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
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
	'Class:WorkOrder/Attribute:ticket_ref' => 'Referenziertes Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Team',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Team-Name',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Bearbeiter',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Melder-Email',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Startdatum',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Enddatum',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Schließen',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('DE DE', 'German', 'Deutsch', array(

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
	'Tickets:ResolvedFrom' => 'Automatisch durch %1$s gelöst',

	'Class:cmdbAbstractObject/Method:Set' => 'Set',
	'Class:cmdbAbstractObject/Method:Set+' => 'Ein Attribut (Feld) mit einem statischen Wert beschreiben',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'Das Feld, das im aktuellen Objekt gesetzt werden soll',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Wert',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'Der Wert, der geschrieben werden soll',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'SetCurrentDate',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Ein Attribut (Feld) mit der aktuellen Zeit und Datum beschreiben',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'Das Feld, das im aktuellen Objekt geschrieben werden soll',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'SetCurrentUser',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Ein Attribut (Feld) mit dem dezeit eingeloggten User beschreiben',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'Das Feld, das im aktuellen Objekt geschrieben werden soll. Falls das Feld vom Typ String ist, wird der FriendlyName des Users verwendet, ansonsten der Identifikator. Der FriendlyName ist der Name der mit dem User-Account verknüpften Person (falls vorhanden), ansonsten der Accountname (Login).',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'SetCurrentPerson',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Beschreibe ein Attribut (Feld) mit der gerade eingeloggten Person (die \\"Person\\", die mit dem geade eingeloggten User verknüpft ist)',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'Das Feld, das im aktuellen Objekt geschrieben werden soll. Falls das Feld vom Typ String ist, wird der FriendlyName des Users verwendet, ansonsten der Identifikator.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'SetElapsedTime',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Ein Attribut (Feld) mit der Zeit (in Sekunden) beschreiben, die seit einem Datumswert aus einem anderen Feld vergangen ist. ',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'Das Feld, das im aktuellen Objekt geschrieben werden soll',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Referenzfeld',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'Das Feld, aus dem die Refernzzeit/datum gelesen werden soll',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Arbeitszeiten',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Leer lassen um das Standard-Arbeitzeiten-Schema zu verwenden, oder auf  \\"DefaultWorkingTimeComputer\\" setzen um ein 24x7-Schema zu erzwingen',
	'Class:cmdbAbstractObject/Method:Reset' => 'Reset',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Ein Attribut (Feld) auf seinen Default-Wert zurücksetzen',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'Das Feld, das im aktuellen Objekt zurückgesetzt werden soll',
	'Class:cmdbAbstractObject/Method:Copy' => 'Copy',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Kopiere den Wert eines Attributs (Felds) in ein anderes Feld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Zielfeld',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'Das Feld, das im aktuellen Objekt geschrieben werden soll',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Quellfeld',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'Das Feld des aktuellen Objekts, aus dem der Wert entnommen werden soll',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'Stimulus anwenden',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Dem ausgewählten Objekt den ausgewählten Stimulus zuweisen',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimulus-Code',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'Ein valider Stimulus-Code für die aktuelle Klasse',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Time To Own (Erstzuweisungszeit)',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Zielvorgabe (SLT) vom Typ TTO',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Time To Resolve (Erstlösungszeit)',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Zielvorgabe (SLT) vom Typ TTR',

	'portal:itop-portal' => 'Standard Portal', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => '%1$s - Benutzer Portal',
	'Brick:Portal:UserProfile:Title' => 'Mein Profile',
	'Brick:Portal:NewRequest:Title' => 'Neue Störung/Anfrage',
	'Brick:Portal:NewRequest:Title+' => '<p>Hilfe?</p><p>Wählen Sie einen Service aus und senden Sie Ihre Anfrage zum Service Desk.</p>',
	'Brick:Portal:OngoingRequests:Title' => 'Offene Störungen/Anfragen',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Hier können Sie Ihre laufenden Anfragen und Störungsmeldungen ansehen,</p><p>den Verlauf verfolgen, Kommentare und Anhänge hinzufügen und gelöste Anfragen schließen.</p>',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'Offen',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Gelöst',
	'Brick:Portal:ClosedRequests:Title' => 'Geschlossene Störungen/Anfragen',
));
