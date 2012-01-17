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
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
