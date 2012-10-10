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
 * @licence	http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:UserRequest' => 'Felhasználói kérés',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Kérés típus',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Információ',
	'Class:UserRequest/Attribute:request_type/Value:information+' => '',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Kérdés',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Szolgáltatás kérés',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => '',
	'Class:UserRequest/Attribute:freeze_reason' => 'Felfüggesztés oka',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Hozzárendelés',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Átrendelés',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'Timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Megjelölés megoldottként',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Lezárás',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Megjelölés felfüggesztettként',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => '',
	'Menu:UserRequest:Overview' => 'Áttekintő',
	'Menu:UserRequest:Overview+' => '',
	'Menu:NewUserRequest' => 'Új felhasználói kérés',
	'Menu:NewUserRequest+' => '',
	'Menu:SearchUserRequests' => 'Felhasználói kérés keresés',
	'Menu:SearchUserRequests+' => '',
	'Menu:UserRequest:Shortcuts' => 'Gyorsmenü',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Hozzám rendelt felhasználói kérések',
	'Menu:UserRequest:MyRequests+' => '',
	'Menu:UserRequest:EscalatedRequests' => 'Eszkalált felhasználói kérések',
	'Menu:UserRequest:EscalatedRequests+' => '',
	'Menu:UserRequest:OpenRequests' => 'Összes nyitott felhasználó kérés',
	'Menu:UserRequest:OpenRequests+' => '',
));
?>