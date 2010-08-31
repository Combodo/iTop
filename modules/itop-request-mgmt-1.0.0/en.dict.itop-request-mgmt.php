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

Dict::Add('EN US', 'English', 'English', array(
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => 'Helpdesk',
	'Menu:UserRequest:Overview' => 'Overview',
	'Menu:UserRequest:Overview+' => 'Overview',
	'Menu:NewUserRequest' => 'New User Request',
	'Menu:NewUserRequest+' => 'Create a new User Request ticket',
	'Menu:SearchUserRequests' => 'Search for User Requests',
	'Menu:SearchUserRequests+' => 'Search for User Request tickets',
	'Menu:UserRequest:Shortcuts' => 'Shortcuts',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Requests assigned to me',
	'Menu:UserRequest:MyRequests+' => 'Requests assigned to me (as Agent)',
	'Menu:UserRequest:EscalatedRequests' => 'Escalated Requests',
	'Menu:UserRequest:EscalatedRequests+' => 'Escalated Requests',
	'Menu:UserRequest:OpenRequests' => 'All Open Requests',
	'Menu:UserRequest:OpenRequests+' => 'All Open Requests',
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:UserRequest' => 'User Request',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Request Type',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Information',
	'Class:UserRequest/Attribute:request_type/Value:information+' => 'Information',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Issue',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => 'Issue',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Service Request',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'Service Request',
	'Class:UserRequest/Attribute:freeze_reason' => 'Pending reason',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Assign',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Reassign',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Mark as resolved',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Close',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Mark as pending',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));


?>
