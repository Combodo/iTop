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
	'Menu:IncidentManagement' => 'Incident Management',
	'Menu:IncidentManagement+' => 'Incident Management',
	'Menu:Incident:Overview' => 'Overview',
	'Menu:Incident:Overview+' => 'Overview',
	'Menu:Incident:MyIncidents' => 'Incidents assigned to me',
	'Menu:Incident:MyIncidents+' => 'Incidents assigned to me (as Agent)',
	'Menu:Incident:EscalatedIncidents' => 'Escalated Incidents',
	'Menu:Incident:EscalatedIncidents+' => 'Escalated Incidents',
	'Menu:Incident:OpenIncidents' => 'All Open Incidents',
	'Menu:Incident:OpenIncidents+' => 'All Open Incidents',

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
// Class: Incident
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:Incident' => 'Incident',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Assign',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Reassign',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Mark as resolved',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Close',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
