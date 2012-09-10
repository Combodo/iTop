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

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:IncidentManagement' => 'インシデント管理',
	'Menu:IncidentManagement+' => 'インシデント管理',
	'Menu:Incident:Overview' => '概要',
	'Menu:Incident:Overview+' => '概要',
	'Menu:NewIncident' => '新規インシデント',
	'Menu:NewIncident+' => 'インシデントチケット作成',
	'Menu:SearchIncidents' => 'インシデント検索',
	'Menu:SearchIncidents+' => 'インシデントチケット検索',
	'Menu:Incident:Shortcuts' => 'ショートカット',
	'Menu:Incident:Shortcuts+' => '', # ''
	'Menu:Incident:MyIncidents' => '担当しているインシデント',
	'Menu:Incident:MyIncidents+' => '担当しているインシデント(エージェント)',
	'Menu:Incident:EscalatedIncidents' => 'エスカレーションされたインシデント',
	'Menu:Incident:EscalatedIncidents+' => 'エスカレーションされたインシデント',
	'Menu:Incident:OpenIncidents' => '全オープンインシデント',
	'Menu:Incident:OpenIncidents+' => '全オープンインシデント',
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

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Incident' => 'インシデント',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => '割り当て',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => '再割り当て',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'EV_タイムアウト',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => '解決',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'クローズ',
	'Class:Incident/Stimulus:ev_close+' => '',
	'Class:lnkTicketToIncident' => 'インシデントへのチケット',
	'Class:lnkTicketToIncident/Attribute:ticket_id' => 'チケット',
	'Class:lnkTicketToIncident/Attribute:incident_id' => 'インシデント',
	'Class:lnkTicketToIncident/Attribute:reason' => '理由',
));

?>
