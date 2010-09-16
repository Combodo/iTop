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
	'Class:Incident/Stimulus:ev_assign' => 'Zuteilen',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Umverteilen',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev-Timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Als gelöst markieren',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Schließen',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
