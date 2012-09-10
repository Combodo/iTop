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
	'Menu:RequestManagement' => 'ヘルプデスク',
	'Menu:RequestManagement+' => 'ヘルプデスク',
	'Menu:UserRequest:Overview' => '概要',
	'Menu:UserRequest:Overview+' => '概要',
	'Menu:NewUserRequest' => '新規要求',
	'Menu:NewUserRequest+' => '要求チケットを作成',
	'Menu:SearchUserRequests' => '要求を検索',
	'Menu:SearchUserRequests+' => '要求チケットを検索',
	'Menu:UserRequest:Shortcuts' => 'ショートカット',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => '担当の要求',
	'Menu:UserRequest:MyRequests+' => '担当の要求(エージェント)',
	'Menu:UserRequest:EscalatedRequests' => 'エスカレーションされた要求',
	'Menu:UserRequest:EscalatedRequests+' => 'エスカレーションされた要求',
	'Menu:UserRequest:OpenRequests' => 'オープン中の要求',
	'Menu:UserRequest:OpenRequests+' => 'オープン中の要求',
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

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:UserRequest' => 'ユーザー要求',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => '要求のタイプ',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => '情報',
	'Class:UserRequest/Attribute:request_type/Value:information+' => '情報',
	'Class:UserRequest/Attribute:request_type/Value:issue' => '課題',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '課題',
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'サービス要求',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'サービス要求',
	'Class:UserRequest/Attribute:freeze_reason' => '保留の理由',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => '割り当て',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => '再割り当て',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_タイムアウト',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => '解決済み',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'クローズ',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => '保留',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));

?>
