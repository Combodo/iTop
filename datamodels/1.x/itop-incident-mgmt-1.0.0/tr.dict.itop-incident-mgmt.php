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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:IncidentManagement' => 'Arıza Yönetimi',
	'Menu:IncidentManagement+' => 'Arıza Yönetimi',
	'Menu:Incident:Overview' => 'Özet',
	'Menu:Incident:Overview+' => 'Özet',
	'Menu:NewIncident' => 'Yeni arıza',
	'Menu:NewIncident+' => 'Yeni arıza kaydı yarat',
	'Menu:SearchIncidents' => 'Arıza kayıt arama',
	'Menu:SearchIncidents+' => 'Arıza kayıt arama',
	'Menu:Incident:Shortcuts' => 'Kısayollar',
	'Menu:Incident:Shortcuts+' => '',
	'Menu:Incident:MyIncidents' => 'Bana atanan arıza kayıtları',
	'Menu:Incident:MyIncidents+' => 'Bana atanan arıza kayıtları',
	'Menu:Incident:EscalatedIncidents' => 'Yönetime aktarılan arıza kayıtları',
	'Menu:Incident:EscalatedIncidents+' => 'Yönetime aktarılan arıza kayıtları',
	'Menu:Incident:OpenIncidents' => 'Tüm açık arıza kayıtları',
	'Menu:Incident:OpenIncidents+' => 'Tüm açık arıza kayıtları',

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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Incident' => 'Arıza',
	'Class:Incident+' => '',
	'Class:Incident/Stimulus:ev_assign' => 'Ata',
	'Class:Incident/Stimulus:ev_assign+' => '',
	'Class:Incident/Stimulus:ev_reassign' => 'Tekrar ata',
	'Class:Incident/Stimulus:ev_reassign+' => '',
	'Class:Incident/Stimulus:ev_timeout' => 'Değerlendirme süre aşımı',
	'Class:Incident/Stimulus:ev_timeout+' => '',
	'Class:Incident/Stimulus:ev_resolve' => 'Çözümlendi',
	'Class:Incident/Stimulus:ev_resolve+' => '',
	'Class:Incident/Stimulus:ev_close' => 'Kapatıldı',
	'Class:Incident/Stimulus:ev_close+' => '',
));

?>
