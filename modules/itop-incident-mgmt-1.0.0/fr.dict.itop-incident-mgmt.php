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

Dict::Add('FR FR', 'French', 'Français', array(
	'Menu:IncidentManagement' => 'Gestion des incidents',
	'Menu:IncidentManagement+' => 'Gestion des incidents',
	'Menu:Incident:Overview' => 'Vue d\'ensemble',
	'Menu:Incident:Overview+' => 'Vue d\'ensemble',
	'Menu:Incident:MyIncidents' => 'Mes tickets',
	'Menu:Incident:MyIncidents+' => 'Mes tickets d\'incident',
	'Menu:Incident:EscalatedIncidents' => 'Ticket en cours d\'escalade',
	'Menu:Incident:EscalatedIncidents+' => 'Ticket d\'incident en cours d\'escalade',
	'Menu:Incident:OpenIncidents' => 'Ticket ouverts',
	'Menu:Incident:OpenIncidents+' => 'Tous les tickets d\'incident ouverts',

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

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Incident' => 'Ticket d\'Incident',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Assigner',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Reassigner',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Marquer comme résolu',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Fermer',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
