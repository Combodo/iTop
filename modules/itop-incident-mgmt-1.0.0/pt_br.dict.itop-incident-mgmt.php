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

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Menu:IncidentManagement' => 'Gerenciamento Incidentes',
	'Menu:IncidentManagement+' => 'Gerenciamento Incidentes',
	'Menu:Incident:Overview' => 'Vis&atilde;o Geral',
	'Menu:Incident:Overview+' => 'Vis&atilde;o Geral',
	'Menu:NewIncident' => 'Novo Incidente',
	'Menu:NewIncident+' => 'Novo Incidente',
	'Menu:SearchIncidents' => 'Pesquisa para Incidentes',
	'Menu:SearchIncidents+' => 'Pesquisa para Incidentes',
	'Menu:Incident:Shortcuts' => 'Atalhos',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Incidentes atribu&iacute;do a mim',
	'Menu:Incident:MyIncidents+' => 'Incidentes atribu&iacute; para mim (como agente)',
	'Menu:Incident:EscalatedIncidents' => 'Incidentes encaminhados',
	'Menu:Incident:EscalatedIncidents+' => 'Incidentes encaminhados',
	'Menu:Incident:OpenIncidents' => 'Todos Incidentes abertos',
	'Menu:Incident:OpenIncidents+' => 'Todos Incidentes abertos',

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

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:Incident' => 'Incidentes',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Atribu&iacute;r',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Re-atribu&iacute;r',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Marque como resolvido',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Fechado',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
