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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Menu:IncidentManagement' => 'Incident Management',
	'Menu:IncidentManagement+' => 'Incident Management',
	'Menu:Incident:Overview' => 'Übersicht',
	'Menu:Incident:Overview+' => 'Übersicht',
	'Menu:NewIncident' => 'Neuer Incident',
	'Menu:NewIncident+' => 'Ein neues Incident-Ticket erstellen',
	'Menu:SearchIncidents' => 'Nach Incidents suchen',
	'Menu:SearchIncidents+' => 'Nach Incidents suchen',
	'Menu:Incident:Shortcuts' => 'Shortcuts',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Incidents, die mich betreffen',
	'Menu:Incident:MyIncidents+' => 'Incidents, die mich betreffen (als Bearbeiter)',
	'Menu:Incident:EscalatedIncidents' => 'Eskalierte Incidents',
	'Menu:Incident:EscalatedIncidents+' => 'Eskalierte Incidents',
	'Menu:Incident:OpenIncidents' => 'Alle offenen Incidents',
	'Menu:Incident:OpenIncidents+' => 'Alle offenen Incidents',

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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Incident' => 'Incident',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => '"Zuweisen"',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Neu zuweisen',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev-Timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Als gelöst markieren',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Schließen',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
