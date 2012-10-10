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
	'Menu:RequestManagement' => '帮助中心',
	'Menu:RequestManagement+' => '帮助中心',
	'Menu:UserRequest:Overview' => '概览',
	'Menu:UserRequest:Overview+' => '概览',
	'Menu:NewUserRequest' => '新的用户请求',
	'Menu:NewUserRequest+' => '创建新的用户请求单据',
	'Menu:SearchUserRequests' => '搜索用户请求',
	'Menu:SearchUserRequests+' => '搜索用户请求单据',
	'Menu:UserRequest:Shortcuts' => '快捷方式',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => '指派给我的请求',
	'Menu:UserRequest:MyRequests+' => '指派给我的请求 (作为办理人)',
	'Menu:UserRequest:EscalatedRequests' => '升级的请求',
	'Menu:UserRequest:EscalatedRequests+' => '升级的请求',
	'Menu:UserRequest:OpenRequests' => '所有待处理的请求',
	'Menu:UserRequest:OpenRequests+' => '所有待处理的请求',
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

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:UserRequest' => '用户请求',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:request_type' => '请求类别',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:information' => '信息',
	'Class:UserRequest/Attribute:request_type/Value:information+' => '信息',
	'Class:UserRequest/Attribute:request_type/Value:issue' => '议题',
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '议题',
	'Class:UserRequest/Attribute:request_type/Value:service request' => '服务请求',
	'Class:UserRequest/Attribute:request_type/Value:service request+' => '服务请求',
	'Class:UserRequest/Attribute:freeze_reason' => '未决原因',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => '指派',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => '重新指派',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'ev_timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => '标记为已解决',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => '关闭',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => '标记为未决',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
));

?>