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
 * @author      Robert Deng <denglx@gmail.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Menu:IncidentManagement' => '事件管理',
	'Menu:IncidentManagement+' => '时间管理',
	'Menu:Incident:Overview' => '概览',
	'Menu:Incident:Overview+' => '概览',
	'Menu:NewIncident' => '新事件',
	'Menu:NewIncident+' => '创建新的事件单据',
	'Menu:SearchIncidents' => '搜索事件',
	'Menu:SearchIncidents+' => '搜索事件单据',
	'Menu:Incident:Shortcuts' => '快捷方式',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => '指派给我的事件',
	'Menu:Incident:MyIncidents+' => '指派给我的事件 (作为办理人)',
	'Menu:Incident:EscalatedIncidents' => '升级的事件',
	'Menu:Incident:EscalatedIncidents+' => '升级的事件',
	'Menu:Incident:OpenIncidents' => '所有待处理的事件',
	'Menu:Incident:OpenIncidents+' => '所有待处理的事件',

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

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:Incident' => '事件',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => '指派',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => '重新指派',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => '标记为已解决',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => '关闭',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
