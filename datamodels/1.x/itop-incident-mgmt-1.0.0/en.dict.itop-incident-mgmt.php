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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(
	'Menu:IncidentManagement' => 'Incident Management',
	'Menu:IncidentManagement+' => 'Incident Management',
	'Menu:Incident:Overview' => 'Overview',
	'Menu:Incident:Overview+' => 'Overview',
	'Menu:NewIncident' => 'New Incident',
	'Menu:NewIncident+' => 'Create a new Incident ticket',
	'Menu:SearchIncidents' => 'Search for Incidents',
	'Menu:SearchIncidents+' => 'Search for Incident tickets',
	'Menu:Incident:Shortcuts' => 'Shortcuts',
	'Menu:Incident:Shortcuts+' => '',
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
	'Class:lnkTicketToIncident' => 'Ticket to Incident',
	'Class:lnkTicketToIncident/Attribute:ticket_id' => 'Ticket',
	'Class:lnkTicketToIncident/Attribute:incident_id' => 'Incident',
	'Class:lnkTicketToIncident/Attribute:reason' => 'Reason',
));

?>
