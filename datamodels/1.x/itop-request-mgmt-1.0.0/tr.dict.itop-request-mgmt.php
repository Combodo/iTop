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