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
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+




Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
        'Menu:ProblemManagement' => 'Problem Yönetimi',
        'Menu:ProblemManagement+' => 'Problem Yönetimi',
    	'Menu:Problem:Overview' => 'Özet',
    	'Menu:Problem:Overview+' => 'Özet',
    	'Menu:NewProblem' => 'Yeni Problem',
    	'Menu:NewProblem+' => 'Yeni Problem',
    	'Menu:SearchProblems' => 'Problem Ara',
    	'Menu:SearchProblems+' => 'Problem Ara',
    	'Menu:Problem:Shortcuts' => 'Kısayollar',
        'Menu:Problem:MyProblems' => 'Problemlerim',
        'Menu:Problem:MyProblems+' => 'Problemlerim',
        'Menu:Problem:OpenProblems' => 'Tüm açık problemler',
        'Menu:Problem:OpenProblems+' => 'Tüm açık problemler',
	'UI-ProblemManagementOverview-ProblemByService' => 'Servis problemleri',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Servis problemleri',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Önceliklere göre problemler',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Önceliklere göre problemler',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Atanmamış Problemler',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Atanmamış Problemler',
	'UI:ProblemMgmtMenuOverview:Title' => 'Problem Yönetimi Gösterge Tablosu',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Problem Yönetimi Gösterge Tablosu',

));
//
// Class: Problem
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Problem' => 'Problem',
	'Class:Problem+' => '',
	'Class:Problem/Attribute:status' => 'Durum',
	'Class:Problem/Attribute:status+' => '',
	'Class:Problem/Attribute:status/Value:new' => 'Yeni',
	'Class:Problem/Attribute:status/Value:new+' => '',
	'Class:Problem/Attribute:status/Value:assigned' => 'Atanmış',
	'Class:Problem/Attribute:status/Value:assigned+' => '',
	'Class:Problem/Attribute:status/Value:resolved' => 'Çözülmüş',
	'Class:Problem/Attribute:status/Value:resolved+' => '',
	'Class:Problem/Attribute:status/Value:closed' => 'Kapanmış',
	'Class:Problem/Attribute:status/Value:closed+' => '',
	'Class:Problem/Attribute:org_id' => 'Müşteri',
	'Class:Problem/Attribute:org_id+' => '',
	'Class:Problem/Attribute:org_name' => 'Adı',
	'Class:Problem/Attribute:org_name+' => 'Yaygın adı',
	'Class:Problem/Attribute:service_id' => 'Servis',
	'Class:Problem/Attribute:service_id+' => '',
	'Class:Problem/Attribute:service_name' => 'Adı',
	'Class:Problem/Attribute:service_name+' => '',
	'Class:Problem/Attribute:servicesubcategory_id' => 'Servis Kategorisi',
	'Class:Problem/Attribute:servicesubcategory_id+' => '',
	'Class:Problem/Attribute:servicesubcategory_name' => 'Adı',
	'Class:Problem/Attribute:servicesubcategory_name+' => '',
	'Class:Problem/Attribute:product' => 'Ürün',
	'Class:Problem/Attribute:product+' => '',
	'Class:Problem/Attribute:impact' => 'Etkisi',
	'Class:Problem/Attribute:impact+' => '',
	'Class:Problem/Attribute:impact/Value:1' => 'Kişi',
	'Class:Problem/Attribute:impact/Value:1+' => '',
	'Class:Problem/Attribute:impact/Value:2' => 'Servis',
	'Class:Problem/Attribute:impact/Value:2+' => '',
	'Class:Problem/Attribute:impact/Value:3' => 'Bölüm',
	'Class:Problem/Attribute:impact/Value:3+' => '',
	'Class:Problem/Attribute:urgency' => 'Aciliyeti',
	'Class:Problem/Attribute:urgency+' => '',
	'Class:Problem/Attribute:urgency/Value:1' => 'Düşük',
	'Class:Problem/Attribute:urgency/Value:1+' => 'Düşük',
	'Class:Problem/Attribute:urgency/Value:2' => 'Orta',
	'Class:Problem/Attribute:urgency/Value:2+' => 'Orta',
	'Class:Problem/Attribute:urgency/Value:3' => 'Yüksek',
	'Class:Problem/Attribute:urgency/Value:3+' => 'Yüksek',
	'Class:Problem/Attribute:priority' => 'Öncelik',
	'Class:Problem/Attribute:priority+' => '',
	'Class:Problem/Attribute:priority/Value:1' => 'Düşük',
	'Class:Problem/Attribute:priority/Value:1+' => '',
	'Class:Problem/Attribute:priority/Value:2' => 'Orta',
	'Class:Problem/Attribute:priority/Value:2+' => '',
	'Class:Problem/Attribute:priority/Value:3' => 'Yüksek',
	'Class:Problem/Attribute:priority/Value:3+' => '',
	'Class:Problem/Attribute:workgroup_id' => 'Çalışma Grubu',
	'Class:Problem/Attribute:workgroup_id+' => '',
	'Class:Problem/Attribute:workgroup_name' => 'Adı',
	'Class:Problem/Attribute:workgroup_name+' => '',
	'Class:Problem/Attribute:agent_id' => 'Sorumlu',
	'Class:Problem/Attribute:agent_id+' => '',
	'Class:Problem/Attribute:agent_name' => 'Sorumlu Adı',
	'Class:Problem/Attribute:agent_name+' => '',
	'Class:Problem/Attribute:agent_email' => 'Sorumlu e-posta',
	'Class:Problem/Attribute:agent_email+' => '',
	'Class:Problem/Attribute:related_change_id' => 'İlgili değişiklik',
	'Class:Problem/Attribute:related_change_id+' => '',
	'Class:Problem/Attribute:related_change_ref' => 'Referans',
	'Class:Problem/Attribute:related_change_ref+' => '',
	'Class:Problem/Attribute:close_date' => 'Kapanış Tarihi',
	'Class:Problem/Attribute:close_date+' => '',
	'Class:Problem/Attribute:last_update' => 'Son güncelleme tarihi',
	'Class:Problem/Attribute:last_update+' => '',
	'Class:Problem/Attribute:assignment_date' => 'Atanma tarihi',
	'Class:Problem/Attribute:assignment_date+' => '',
	'Class:Problem/Attribute:resolution_date' => 'Çözülme tarihi',
	'Class:Problem/Attribute:resolution_date+' => '',
	'Class:Problem/Attribute:knownerrors_list' => 'Bilinen Hatalar',
	'Class:Problem/Attribute:knownerrors_list+' => '',
	'Class:Problem/Stimulus:ev_assign' => 'Ata',
	'Class:Problem/Stimulus:ev_assign+' => '',
	'Class:Problem/Stimulus:ev_reassign' => 'Yeniden ata',
	'Class:Problem/Stimulus:ev_reassign+' => '',
	'Class:Problem/Stimulus:ev_resolve' => 'Çöz',
	'Class:Problem/Stimulus:ev_resolve+' => '',
	'Class:Problem/Stimulus:ev_close' => 'Kapat',
	'Class:Problem/Stimulus:ev_close+' => '',
	'Class:Problem/Attribute:urgency/Value:4' => 'low~~',
	'Class:Problem/Attribute:urgency/Value:4+' => 'low~~',
	'Class:Problem/Attribute:priority/Value:4' => 'Low~~',
	'Class:Problem/Attribute:priority/Value:4+' => 'Low~~',
	'Class:Problem/Attribute:related_request_list' => 'Related requests~~',
	'Class:Problem/Attribute:related_request_list+' => 'All the requests that are related to this problem~~',
	'Class:Problem/Attribute:related_incident_list' => 'Related incidents~~',
	'Class:Problem/Attribute:related_incident_list+' => 'All the incidents that are related to this problem~~',
));

?>
