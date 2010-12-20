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
 * @author      Vladimir Shilov <shilow@ukr.net>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Menu:IncidentManagement' => 'Управление инцидентами',
	'Menu:IncidentManagement+' => 'Управление инцидентами',
	'Menu:Incident:Overview' => 'Обзор',
	'Menu:Incident:Overview+' => 'Обзор',
	'Menu:NewIncident' => 'Новый инцидент',
	'Menu:NewIncident+' => 'Создать новый инцидент-тикет',
	'Menu:SearchIncidents' => 'Поиск инцидентов',
	'Menu:SearchIncidents+' => 'Поиск инцидент-тикетов',
	'Menu:Incident:Shortcuts' => 'Ярлыки',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Инциденты назначенные на меня',
	'Menu:Incident:MyIncidents+' => 'Управление инцидентами (как Агент)',
	'Menu:Incident:EscalatedIncidents' => 'Эскалированные инциденты',
	'Menu:Incident:EscalatedIncidents+' => 'Эскалированные инциденты',
	'Menu:Incident:OpenIncidents' => 'Все открытые инциденты',
	'Menu:Incident:OpenIncidents+' => 'Все открытые инциденты',

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

Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:Incident' => 'Инцидент',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Назначить',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Переназначить',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Пометить как решённое',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Закрыть',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
