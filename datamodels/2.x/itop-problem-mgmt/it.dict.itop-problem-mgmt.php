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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Problem' => 'Problema',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Stato',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Nuovo',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Assegnato',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Risolto',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Chiuso',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Cliente',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:service_id' => 'Servizio',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Categoria di servizio',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:product' => 'Prodotto',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Impatto',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Una persona',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Un servizio',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Un dipartimento',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Urgenza',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Bassa',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Bassa',
	'Class:Problem/Attribute:urgency/Value:2' => 'Media',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Media',
	'Class:Problem/Attribute:urgency/Value:3' => 'Alta',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Alta',
	'Class:Problem/Attribute:priority' => 'Priorità',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Bassa',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Media',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Alta',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'Gruppo di lavoro',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:agent_id' => 'Agente',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:related_change_id' => 'Cambi Correlati',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:close_date' => 'Data di Chiusura',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Ultimo Aggiornamento',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Data di asseganzione',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Data di risoluzione',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Errori Conosciuti',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Assegnare',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Riassegnare',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Risolvere',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Chiudere',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Menu:ProblemManagement' => 'Gestione dei Problemi',
	'Menu:ProblemManagement+' => 'Gestione dei Problemi',
	'Menu:Problem:Overview' => 'Panoramica',
	'Menu:Problem:Overview+' => 'Panoramica',
	'Menu:NewProblem' => 'Nuovo Problema',
	'Menu:NewProblem+' => 'Nuovo Problema',
	'Menu:SearchProblems' => 'Ricerca per Problema',
	'Menu:SearchProblems+' => 'Ricerca per Problema',
	'Menu:Problem:Shortcuts' => 'Scorciatoia',
	'Menu:Problem:MyProblems' => 'I Miei Problemi',
	'Menu:Problem:MyProblems+' => 'I Miei Problemi',
	'Menu:Problem:OpenProblems' => 'Tutti i Problemi Aperti',
	'Menu:Problem:OpenProblems+' => 'Tutti i Problemi Aperti',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemi per Servizio',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemi per Servizio',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemi per Priorità',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemi per Priorità',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Problemi non assegnati',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Problemi non assegnati',
	'UI:ProblemMgmtMenuOverview:Title' => 'Dashboard per la gestione dei problemi',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Dashboard per la gestione dei problemi',
	'Class:Problem/Attribute:org_name' => 'Nome',
	'Class:Problem/Attribute:org_name+' => 'Nome Comune',
	'Class:Problem/Attribute:service_name' => 'Nome',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Nome',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Nome',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_name' => 'Nome dell\Agente',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'Email dell\'Agente',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Ref',
	'Class:Problem/Attribute:related_change_ref+' => '',
));
?>
