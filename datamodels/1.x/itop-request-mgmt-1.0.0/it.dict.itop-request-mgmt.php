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
	'Class:UserRequest' => 'Richiesta dell\'utente',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Tipo di Richiesta',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Informazione',
	'Class:UserRequest/Attribute:request_type/Value:information+' => 'Informazione',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Problema',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => 'Problema',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Richiesta di assistenza',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'Richiesta di assistenza',
	'Class:UserRequest/Attribute:freeze_reason' => 'Motivo dell\'attesa',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Assegna',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Riassegna',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Segna come risolto',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Chiuso',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Segna come in attesa',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Menu:RequestManagement' => 'Servizio di assistenza',
	'Menu:RequestManagement+' => 'Servizio di assistenza',
	'Menu:UserRequest:Overview' => 'Panoramica',
	'Menu:UserRequest:Overview+' => 'Panoramica',
	'Menu:NewUserRequest' => 'Nuova Richiesta dall\'Utente',
	'Menu:NewUserRequest+' => 'Crea un ticket per una Nuova Richiesta dall\'Utente',
	'Menu:SearchUserRequests' => 'Cerca per Richieste dall\'Utente',
	'Menu:SearchUserRequests+' => 'Cerca per tickets delle Richieste dall\'Utente',
	'Menu:UserRequest:Shortcuts' => 'Scorciatoia',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Richiesta assegnata a me',
	'Menu:UserRequest:MyRequests+' => 'Richiesta assegnata a me (come Agente)',
	'Menu:UserRequest:EscalatedRequests' => 'Richieste in escalation',
	'Menu:UserRequest:EscalatedRequests+' => 'Richieste in escalation',
	'Menu:UserRequest:OpenRequests' => 'Tutte le richieste aperte',
	'Menu:UserRequest:OpenRequests+' => 'Tutte le richieste aperte',
));
?>
