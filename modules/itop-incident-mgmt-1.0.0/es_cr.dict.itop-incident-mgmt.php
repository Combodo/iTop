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

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:IncidentManagement' => 'Gestión de Incidentes',
	'Menu:IncidentManagement+' => 'Gestión de Incidentes',
	'Menu:Incident:Overview' => 'Visión General',
	'Menu:Incident:Overview+' => 'Visión General',
	'Menu:Incident:MyIncidents' => 'Incidentes asignados a mí',
	'Menu:Incident:MyIncidents+' => 'Incidentes asignados a mí (como Agente)',
	'Menu:Incident:EscalatedIncidents' => 'Incidentes Escalados',
	'Menu:Incident:EscalatedIncidents+' => 'Incidentes Escalados',
	'Menu:Incident:OpenIncidents' => 'Todos los Incidentes Abiertos',
	'Menu:Incident:OpenIncidents+' => 'Todos los Incidentes Abiertos',

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

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Incident' => 'Incidente',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Asignar',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Re-asignar',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'Tiempo Fuera del incidente',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Marcar como resuelto',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Cerrar',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
