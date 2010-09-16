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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => 'Helpdesk',
	'Menu:UserRequest:Overview' => 'Übersicht',
	'Menu:UserRequest:Overview+' => 'Übersicht',
	'Menu:NewUserRequest' => 'Neue Benutzeranfrage erstellen',
	'Menu:NewUserRequest+' => 'Neue Benutzeranfrage erstellen',
	'Menu:SearchUserRequests' => 'Nach einem Benutzer-Request suchen',
	'Menu:SearchUserRequests+' => 'Rechercher parmi les demandes utilisateur',
	'Menu:UserRequest:Shortcuts' => 'Shortcuts',
	'Menu:UserRequest:Shortcuts+' => 'Shortcuts',
	'Menu:UserRequest:MyRequests' => 'Anfragen, die mich betreffen',
	'Menu:UserRequest:MyRequests+' => 'Anfragen, die mich betreffen (als Bearbeiter)',
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
	'Class:UserRequest/Attribute:freeze_reason' => 'Grund für nicht erledigen',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Zuteilen',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Als unerledigt markieren',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Umverteilen',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev-Timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Als gelöst markieren',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Schließen',
	'Class:UserRequest/Stimulus:ev_close+' => '',
));

?>
