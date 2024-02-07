<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Menu:ChangeManagement' => 'Gestione dei cambi',
	'Menu:Change:Overview' => 'Panoramica',
	'Menu:Change:Overview+' => '~~',
	'Menu:NewChange' => 'Nuovo cambio',
	'Menu:NewChange+' => 'Crea un ticket per un nuovo cambio',
	'Menu:SearchChanges' => 'Cerca per cambi',
	'Menu:SearchChanges+' => 'Cerca i cambi per tickets',
	'Menu:Change:Shortcuts' => 'Scorciatoie',
	'Menu:Change:Shortcuts+' => '~~',
	'Menu:WaitingAcceptance' => 'Modifiche in attesa di accettazione',
	'Menu:WaitingAcceptance+' => '~~',
	'Menu:WaitingApproval' => 'Modifiche in attesa di approvazione',
	'Menu:WaitingApproval+' => '~~',
	'Menu:Changes' => 'Modifiche aperte',
	'Menu:Changes+' => 'Tutte le Modifiche aperte',
	'Menu:MyChanges' => 'Modifiche assegnate a me',
	'Menu:MyChanges+' => 'Modifiche assegnato a me (come operatore)',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => 'Cambiamenti per categoria negli ultimi 7 giorni',
	'UI-ChangeManagementOverview-Last-7-days' => 'Numero di cambiamenti negli ultimi 7 giorni',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => 'Cambiamenti per dominio negli ultimi 7 giorni',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => 'Cambiamenti per stato negli ultimi 7 giorni',
	'Tickets:Related:OpenChanges' => 'Cambiamenti aperti',
	'Tickets:Related:RecentChanges' => 'Cambiamenti recenti (72h)',
));

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
// Class: Change
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Change' => 'Cambio',
	'Class:Change+' => '~~',
	'Class:Change/Attribute:status' => 'Stato',
	'Class:Change/Attribute:status+' => '~~',
	'Class:Change/Attribute:status/Value:new' => 'Nuovo',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => 'Assegnato',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => 'Pianificato',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => 'Rifiutato',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => 'Approvato',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'Chiuso',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'Category~~',
	'Class:Change/Attribute:category+' => '~~',
	'Class:Change/Attribute:category/Value:application' => 'applicazione',
	'Class:Change/Attribute:category/Value:hardware' => 'hardware',
	'Class:Change/Attribute:category/Value:network' => 'rete',
	'Class:Change/Attribute:category/Value:other' => 'altro',
	'Class:Change/Attribute:category/Value:software' => 'software',
	'Class:Change/Attribute:category/Value:system' => 'sistema',
	'Class:Change/Attribute:reject_reason' => 'Motivo del rifiuto',
	'Class:Change/Attribute:changemanager_id' => 'Gestore del cambiamento',
	'Class:Change/Attribute:changemanager_email' => 'Email del gestore del cambiamento',
	'Class:Change/Attribute:parent_id' => 'Cambiamento genitore',
	'Class:Change/Attribute:parent_name' => 'Rif. del cambiamento genitore',
	'Class:Change/Attribute:creation_date' => 'Data di creazione',
	'Class:Change/Attribute:approval_date' => 'Data di approvazione',
	'Class:Change/Attribute:fallback_plan' => 'Piano di fallback',
	'Class:Change/Attribute:related_request_list' => 'Richieste correlate',
	'Class:Change/Attribute:related_incident_list' => 'Incidenti correlati',
	'Class:Change/Attribute:related_problems_list' => 'Problemi correlati',
	'Class:Change/Attribute:child_changes_list' => 'Cambiamenti figli',
	'Class:Change/Attribute:parent_id_friendlyname' => 'Nome del cambiamento genitore',
	'Class:Change/Attribute:parent_id_friendlyname+' => '~~',
	'Class:Change/Stimulus:ev_assign' => 'Assegna',
	'Class:Change/Stimulus:ev_assign+' => '~~',
	'Class:Change/Stimulus:ev_plan' => 'Pianifica',
	'Class:Change/Stimulus:ev_plan+' => '~~',
	'Class:Change/Stimulus:ev_reject' => 'Rifiuta',
	'Class:Change/Stimulus:ev_reject+' => '~~',
	'Class:Change/Stimulus:ev_reopen' => 'Riapre',
	'Class:Change/Stimulus:ev_reopen+' => '~~',
	'Class:Change/Stimulus:ev_approve' => 'Approva',
	'Class:Change/Stimulus:ev_approve+' => '~~',
	'Class:Change/Stimulus:ev_finish' => 'Fine',
	'Class:Change/Stimulus:ev_finish+' => '~~',
	'Class:Change/Attribute:outage' => 'Interruzione',
	'Class:Change/Attribute:outage+' => '~~',
	'Class:Change/Attribute:outage/Value:no' => 'No',
	'Class:Change/Attribute:outage/Value:no+' => '~~',
	'Class:Change/Attribute:outage/Value:yes' => 'Si',
	'Class:Change/Attribute:outage/Value:yes+' => '~~',
));
