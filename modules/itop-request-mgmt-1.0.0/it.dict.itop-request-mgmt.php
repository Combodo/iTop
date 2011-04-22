<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('IT IT', 'Italian', 'Italiano', array(
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
// Class: UserRequest
//

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
	'Class:UserRequest/Stimulus:ev_assign' => 'Assegnare',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Riassegnare',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Segna come risolto',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Chiuso',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Segna come in attesa',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));

?>
