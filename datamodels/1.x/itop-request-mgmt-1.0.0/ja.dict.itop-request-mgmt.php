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
	'Menu:RequestManagement' => 'ヘルプデスク',	# 'Helpdesk'
	'Menu:RequestManagement+' => 'ヘルプデスク',  # 'Helpdesk'
	'Menu:UserRequest:Overview' => '概要',  # 'Overview'
	'Menu:UserRequest:Overview+' => '概要', # 'Overview'
	'Menu:NewUserRequest' => '新規リクエスト',  # 'New User Request'
	'Menu:NewUserRequest+' => 'リクエストチケットを作成',	# 'Create a new User Request ticket'
	'Menu:SearchUserRequests' => 'リクエストを検索',	# 'Search for User Requests'
	'Menu:SearchUserRequests+' => 'リクエストチケットを検索',  # 'Search for User Request tickets'
	'Menu:UserRequest:Shortcuts' => 'ショートカット',   # 'Shortcuts'
	'Menu:UserRequest:Shortcuts+' => '',	       	    # ''
	'Menu:UserRequest:MyRequests' => '担当しているリクエスト',	# 'Requests assigned to me'
	'Menu:UserRequest:MyRequests+' => '担当しているリクエスト(エージェント)',   # 'Requests assigned to me (as Agent)'
	'Menu:UserRequest:EscalatedRequests' => 'エスカレーションされた Requests',  # 'Escalated Requests'
	'Menu:UserRequest:EscalatedRequests+' => 'エスカレーションされた Requests', # 'Escalated Requests'
	'Menu:UserRequest:OpenRequests' => '担当のいないリクエスト',	    # 'All Open Requests'
	'Menu:UserRequest:OpenRequests+' => '担当のいないリクエスト',       # 'All Open Requests'
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
	'Class:UserRequest' => 'ユーザーリクエスト',	# 'User Request'
	'Class:UserRequest+' => '',			# ''
	'Class:UserRequest/Attribute:request_type' => 'リクエストの種別',	 # 'Request Type'
	'Class:UserRequest/Attribute:request_type+' => '', 			 # ''
	'Class:UserRequest/Attribute:request_type/Value:information' => '情報',	 # 'Information'
	'Class:UserRequest/Attribute:request_type/Value:information+' => '情報', # 'Information'
	'Class:UserRequest/Attribute:request_type/Value:issue' => '問題点',	 # 'Issue'
	'Class:UserRequest/Attribute:request_type/Value:issue+' => '問題点',	 # 'Issue'
	'Class:UserRequest/Attribute:request_type/Value:service request' => 'サービスの依頼',	# 'Service Request'
	'Class:UserRequest/Attribute:request_type/Value:service request+' => 'サービスの依頼',	# 'Service Request'
	'Class:UserRequest/Attribute:freeze_reason' => '保留の理由',	 # 'Pending reason'
	'Class:UserRequest/Attribute:freeze_reason+' => '',	# ''
	'Class:UserRequest/Stimulus:ev_assign' => '割当',	# 'Assign'
	'Class:UserRequest/Stimulus:ev_assign+' => '',		# ''
	'Class:UserRequest/Stimulus:ev_reassign' => '再割当',	# 'Reassign'
	'Class:UserRequest/Stimulus:ev_reassign+' => '',	# ''
	'Class:UserRequest/Stimulus:ev_timeout' => '中断(エスカレーション)',  # 'ev_timeout'
	'Class:UserRequest/Stimulus:ev_timeout+' => '',		  # ''
	'Class:UserRequest/Stimulus:ev_resolve' => '解決済みとする',	# 'Mark as resolved'
	'Class:UserRequest/Stimulus:ev_resolve+' => '',	    		# ''
	'Class:UserRequest/Stimulus:ev_close' => '完了',  # 'Close'
	'Class:UserRequest/Stimulus:ev_close+' => '',	   # ''
	'Class:UserRequest/Stimulus:ev_freeze' => '保留とする',	# 'Mark as pending'
	'Class:UserRequest/Stimulus:ev_freeze+' => '',	   	# ''
));

?>
