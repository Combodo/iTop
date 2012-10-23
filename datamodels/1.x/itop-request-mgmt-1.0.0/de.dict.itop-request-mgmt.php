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
 * Localized data
 *
 * @author   Stephan Rosenke <stephan.rosenke@itomig.de>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => 'Helpdesk',
	'Menu:UserRequest:Overview' => 'Übersicht',
	'Menu:UserRequest:Overview+' => 'Übersicht',
	'Menu:NewUserRequest' => 'Neue Benutzeranfrage erstellen',
	'Menu:NewUserRequest+' => 'Neue Benutzeranfrage erstellen',
	'Menu:SearchUserRequests' => 'Nach einem Benutzer-Request suchen',
	'Menu:SearchUserRequests+' => 'Nach einem Benutzer-Request suchen',
	'Menu:UserRequest:Shortcuts' => 'Shortcuts',
	'Menu:UserRequest:Shortcuts+' => 'Shortcuts',
	'Menu:UserRequest:MyRequests' => 'Anfragen, die mich betreffen',
	'Menu:UserRequest:MyRequests+' => 'Anfragen, die mich als Bearbeiter betreffen',
	'Menu:UserRequest:EscalatedRequests' => 'Eskalierte Anfragen',
	'Menu:UserRequest:EscalatedRequests+' => 'Eskalierte Anfragen',
	'Menu:UserRequest:OpenRequests' => 'Alle offenen Anfragen',
	'Menu:UserRequest:OpenRequests+' => 'Alle offenen Anfragen',
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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:UserRequest' => 'Benutzeranfrage',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Art der Anfrage',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Information',
	'Class:UserRequest/Attribute:request_type/Value:information+' => 'Information',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Problem',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => 'Problem',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Service-Anfrage',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'Service-Anfrage',
	'Class:UserRequest/Attribute:freeze_reason' => 'Grund für Nicht-Erledigung',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Zuweisen',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Als unerledigt markieren',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Neu zuweisen',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev-Timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Als gelöst markieren',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Schließen',
	'Class:UserRequest/Stimulus:ev_close+' => '',
));

?>
