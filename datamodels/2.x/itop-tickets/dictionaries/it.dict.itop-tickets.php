<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('IT IT', 'Italian', 'Italiano', array(
'Class:Ticket' => 'Ticket',
'Class:Ticket/Attribute:ref' => 'Rif',
'Class:Ticket/Attribute:org_id' => 'Cliente',
'Class:Ticket/Attribute:org_name' => 'Organizzazione',
'Class:Ticket/Attribute:caller_id' => 'Chiamante~~',
'Class:Ticket/Attribute:caller_name' => 'Nome del Richiedente~~',
'Class:Ticket/Attribute:team_id' => 'Team~~',
'Class:Ticket/Attribute:team_name' => 'Nome del Team~~',
'Class:Ticket/Attribute:agent_id' => 'Operatore',
'Class:Ticket/Attribute:agent_name' => 'Nome dell\'operatore',
'Class:Ticket/Attribute:title' => 'Titolo',
'Class:Ticket/Attribute:description' => 'Descrizione',
'Class:Ticket/Attribute:start_date' => 'Data di inizio',
'Class:Ticket/Attribute:end_date' => 'Data di fine',
'Class:Ticket/Attribute:last_update' => 'Ultimo aggiornamento',
'Class:Ticket/Attribute:close_date' => 'Data di Chiusura',
'Class:Ticket/Attribute:private_log' => 'Registro privato~~',
'Class:Ticket/Attribute:contacts_list' => 'Contatti~~',
'Class:Ticket/Attribute:contacts_list+' => 'Tutti i contatti collegati a questo ticket~~',
'Class:Ticket/Attribute:functionalcis_list' => 'CI~~',
'Class:Ticket/Attribute:functionalcis_list+' => 'Tutti gli elementi di configurazione impattati per questo ticket~~',
'Class:Ticket/Attribute:workorders_list' => 'Ordini di lavoro~~',
'Class:Ticket/Attribute:workorders_list+' => 'Tutti gli ordini di lavoro per questo ticket~~',
'Class:Ticket/Attribute:finalclass' => 'Tipo',
'Class:Ticket/Attribute:operational_status' => 'Stato operativo~~',
'Class:Ticket/Attribute:operational_status+' => 'Calcolato dopo lo stato dettagliato~~',
'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'In corso~~',
'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Lavoro in corso~~',
'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Risolto~~',
'Class:Ticket/Attribute:operational_status/Value:closed' => 'Chiuso~~',
'Ticket:ImpactAnalysis' => 'Analisi dell\'impatto~~',
));


//
// Class: lnkContactToTicket
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkContactToTicket' => 'Link Contact / Ticket~~',
	'Class:lnkContactToTicket/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket~~',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Rif~~',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contatto~~',
	'Class:lnkContactToTicket/Attribute:contact_name' => 'Nome del Contatto~~',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Email del Contatto~~',
	'Class:lnkContactToTicket/Attribute:role' => 'Ruolo (testo)~~',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Ruolo~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Aggiunto manualmente~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Calcolato~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Non notificare~~',
));

//
// Class: WorkOrder
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:WorkOrder' => 'Work Order~~',
	'Class:WorkOrder+' => '~~',
	'Class:WorkOrder/Attribute:name' => 'Nome~~',
	'Class:WorkOrder/Attribute:status' => 'Stato~~',
	'Class:WorkOrder/Attribute:status/Value:open' => 'aperto~~',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'chiuso~~',
	'Class:WorkOrder/Attribute:description' => 'Descrizione~~',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket~~',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Riferimento Ticket~~',
	'Class:WorkOrder/Attribute:team_id' => 'Team~~',
	'Class:WorkOrder/Attribute:team_name' => 'Nome del Team~~',
	'Class:WorkOrder/Attribute:agent_id' => 'Operatore~~',
	'Class:WorkOrder/Attribute:agent_email' => 'Email dell\'operatore~~',
	'Class:WorkOrder/Attribute:start_date' => 'Data di inizio~~',
	'Class:WorkOrder/Attribute:end_date' => 'Data di fine~~',
	'Class:WorkOrder/Attribute:log' => 'Log~~',
	'Class:WorkOrder/Stimulus:ev_close' => 'Chiudi~~',
));


// Fieldset translation
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Ticket:baseinfo' => 'Infomazioni Generali',
	'Ticket:date' => 'Data',
	'Ticket:contact' => 'Contatti',
	'Ticket:moreinfo' => 'Più informazioni',
	'Ticket:relation' => 'Relazioni',
	'Ticket:log' => 'Centro Messaggi',
	'Ticket:Type' => 'Qualificazione~~',
	'Ticket:support' => 'Supporto~~',
	'Ticket:resolution' => 'Risoluzione~~',
	'Ticket:SLA' => 'Rapporto SLA~~',
	'WorkOrder:Details' => 'Dettagli~~',
	'WorkOrder:Moreinfo' => 'Ulteriori informazioni~~',
	'Tickets:ResolvedFrom' => 'Risolti automaticamente da %1$s~~',

	// Class: cmdbAbstractObject

	'Class:cmdbAbstractObject/Method:Set' => 'Imposta~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Valore~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'ImpostaDataCorrente~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull' => 'ImpostaDataCorrenteSeNulla~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'ImpostaUtenteCorrente~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'ImpostaPersonaCorrente~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'ImpostaTempoTrascorso~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Campo di Riferimento~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Ore Lavorative~~',
	'Class:cmdbAbstractObject/Method:SetIfNull' => 'ImpostaSeNulla~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2' => 'Valore~~',
	'Class:cmdbAbstractObject/Method:AddValue' => 'AggiungiValore~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2' => 'Valore~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate' => 'ImpostaDataCalcolata~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2' => 'Modificatore~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3' => 'Campo Sorgente~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull' => 'ImpostaDataCalcolataSeNulla~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2' => 'Modificatore~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3' => 'Campo Sorgente~~',
	'Class:cmdbAbstractObject/Method:Reset' => 'Reimposta~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:Copy' => 'Copia~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Campo di Destinazione~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Campo Sorgente~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'Applica Stimolo~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Codice Stimolo~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Tempo Per Prendere in Carico~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Tempo Per Risolvere~~',
));

