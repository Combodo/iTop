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
	'Menu:RequestManagement' => 'Yardım masası',
	'Menu:RequestManagement+' => 'Yardım masası',
	'Menu:UserRequest:Overview' => 'Özet',
	'Menu:UserRequest:Overview+' => 'Özet',
	'Menu:NewUserRequest' => 'Yeni çağrı',
	'Menu:NewUserRequest+' => 'Yeni çağrı yarat',
	'Menu:SearchUserRequests' => 'Kullanıcı çağrılarını ara',
	'Menu:SearchUserRequests+' => 'Kullanıcı çağrılarını ara',
	'Menu:UserRequest:Shortcuts' => 'Kısayollar',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Bana atanan çağrılar',
	'Menu:UserRequest:MyRequests+' => 'Bana atanan çağrılar',
	'Menu:UserRequest:EscalatedRequests' => 'Yönetime aktarılan çağrılar',
	'Menu:UserRequest:EscalatedRequests+' => 'Yönetime aktarılan çağrılar',
	'Menu:UserRequest:OpenRequests' => 'Tüm açık çağrılar',
	'Menu:UserRequest:OpenRequests+' => 'Tüm açık çağrılar',
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
// Class: UserRequest
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:UserRequest' => 'Kullanıcı isteği',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => 'İstek Tipi',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => 'Bilgi',
	'Class:UserRequest/Attribute:request_type/Value:information+' => 'Bilgi',
	'Class:UserRequest/Attribute:request_type/Value:issue' => 'Konu',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => 'Konu',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'Servis isteği',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'Servis isteği',
	'Class:UserRequest/Attribute:freeze_reason' => 'Tanımlanmamış istek',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Ata',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Tekrar ata',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'zaman aşımı',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Çözümlendi',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Kapatıldı',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Beklemede',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));

?>