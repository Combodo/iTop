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

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:IncidentManagement' => 'インシデント管理',	# 'Incident Management'
	'Menu:IncidentManagement+' => 'インシデント管理',	# 'Incident Management'
	'Menu:Incident:Overview' => '概要',	# 'Overview'
	'Menu:Incident:Overview+' => '概要',  # 'Overview'
	'Menu:NewIncident' => '新規インシデント',	  # 'New Incident'
	'Menu:NewIncident+' => 'インシデントチケット作成',	# 'Create a new Incident ticket'
	'Menu:SearchIncidents' => 'インシデント検索',	# 'Search for Incidents'
	'Menu:SearchIncidents+' => 'インシデントチケット検索', # 'Search for Incident tickets'
	'Menu:Incident:Shortcuts' => 'ショートカット',	# 'Shortcuts'
	'Menu:Incident:Shortcuts+' => '',		# ''
	'Menu:Incident:MyIncidents' => '担当しているインシデント',	# 'Incidents assigned to me'
	'Menu:Incident:MyIncidents+' => '担当しているインシデント(エージェント)',	     # 'Incidents assigned to me (as Agent)'
	'Menu:Incident:EscalatedIncidents' => 'エスカレーションされたインシデント',  # 'Escalated Incidents'
	'Menu:Incident:EscalatedIncidents+' => 'エスカレーションされたインシデント', # 'Escalated Incidents'
	'Menu:Incident:OpenIncidents' => '担当のいないインシデント',	      # 'All Open Incidents'
	'Menu:Incident:OpenIncidents+' => '担当のいないインシデント',	      # 'All Open Incidents'
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
	'Class:Incident' => 'インシデント',	# 'Incident'
	'Class:Incident+' => '',	# ''
	'Class:Incident/Stimulus:ev_assign' => '割当',	# 'Assign'
	'Class:Incident/Stimulus:ev_assign+' => '',		# ''
	'Class:Incident/Stimulus:ev_reassign' => '再割当',	# 'Reassign'
	'Class:Incident/Stimulus:ev_reassign+' => '',		# ''
	'Class:Incident/Stimulus:ev_timeout' => '中断(エスカレーション)',	# 'ev_timeout'
	'Class:Incident/Stimulus:ev_timeout+' => '',		# ''
	'Class:Incident/Stimulus:ev_resolve' => '解決済みとする',	# 'Mark as resolved'
	'Class:Incident/Stimulus:ev_resolve+' => '',  # ''
	'Class:Incident/Stimulus:ev_close' => '完了',	# 'Close'
	'Class:Incident/Stimulus:ev_close+' => '',	# ''
));

?>
