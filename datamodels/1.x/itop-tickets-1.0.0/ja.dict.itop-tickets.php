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
// Class: Ticket
//

//
// Class: Ticket
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Ticket' => 'チケット', // 'Ticket',	# 'Ticket'
	'Class:Ticket+' => '',		# ''
	'Class:Ticket/Attribute:ref' => '参照', // 'Ref',	# 'Ref'
	'Class:Ticket/Attribute:ref+' => '',	# ''
	'Class:Ticket/Attribute:title' => 'タイトル', // 'Title',	# 'Title'
	'Class:Ticket/Attribute:title+' => '',		# ''
	'Class:Ticket/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Ticket/Attribute:description+' => '',	# ''
	'Class:Ticket/Attribute:ticket_log' => 'ログ', // 'Log',	# 'Log'
	'Class:Ticket/Attribute:ticket_log+' => '',	# ''
	'Class:Ticket/Attribute:start_date' => '開始済み', // 'Started', # 'Started'
	'Class:Ticket/Attribute:start_date+' => '',	  # ''
	'Class:Ticket/Attribute:document_list' => 'ドキュメント', // 'Documents',	# 'Documents'
	'Class:Ticket/Attribute:document_list+' => '本チケットに関連するドキュメント', // 'Documents related to the ticket',	# 'Documents related to the ticket'
	'Class:Ticket/Attribute:ci_list' => 'CI', // 'CIs', # 'CIs'
	'Class:Ticket/Attribute:ci_list+' => '本インシデントに関連するCI', // 'CIs concerned by the incident',	# 'CIs concerned by the incident'
	'Class:Ticket/Attribute:contact_list' => 'コンタクト', // 'Contacts',   	   		# 'Contacts'
	'Class:Ticket/Attribute:contact_list+' => '担当チーム、担当者', // 'Team and persons involved',	# 'Team and persons involved'
	'Class:Ticket/Attribute:incident_list' => '関連インシデント', // 'Related Incidents',		# 'Related Incidents'
	'Class:Ticket/Attribute:incident_list+' => '',	   # ''
	'Class:Ticket/Attribute:finalclass' => 'タイプ', // 'Type',	   # 'Type'
	'Class:Ticket/Attribute:finalclass+' => '',	   # ''
));


//
// Class: lnkTicketToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToDoc' => 'チケット/ドキュメント', // 'Ticket/Document',	# 'Ticket/Document'
	'Class:lnkTicketToDoc+' => '',			# ''
	'Class:lnkTicketToDoc/Attribute:ticket_id' => 'チケット', // 'Ticket',	# 'Ticket'
	'Class:lnkTicketToDoc/Attribute:ticket_id+' => '',	# ''
	'Class:lnkTicketToDoc/Attribute:ticket_ref' => 'チケット', // 'Ticket #',	# 'Ticket #'
	'Class:lnkTicketToDoc/Attribute:ticket_ref+' => '',    # ''
	'Class:lnkTicketToDoc/Attribute:document_id' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkTicketToDoc/Attribute:document_id+' => '',		# ''
	'Class:lnkTicketToDoc/Attribute:document_name' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkTicketToDoc/Attribute:document_name+' => '',		# ''
));

//
// Class: lnkTicketToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToContact' => 'チケット/コンタクト', // 'Ticket/Contact',	# 'Ticket/Contact'
	'Class:lnkTicketToContact+' => '',		# ''
	'Class:lnkTicketToContact/Attribute:ticket_id' => 'チケット', //  'Ticket',	# 'Ticket'
	'Class:lnkTicketToContact/Attribute:ticket_id+' => '',		# ''
	'Class:lnkTicketToContact/Attribute:ticket_ref' => 'チケット', // 'Ticket #',	# 'Ticket #'
	'Class:lnkTicketToContact/Attribute:ticket_ref+' => '',	   # ''
	'Class:lnkTicketToContact/Attribute:contact_id' => 'コンタクト', // 'Contact',	# 'Contact'
	'Class:lnkTicketToContact/Attribute:contact_id+' => '',		# ''
	'Class:lnkTicketToContact/Attribute:contact_name' => 'コンタクト', // 'Contact',	# 'Contact'
	'Class:lnkTicketToContact/Attribute:contact_name+' => '',	# ''
	'Class:lnkTicketToContact/Attribute:contact_email' => 'Eメール', // 'Email',	# 'Email'
	'Class:lnkTicketToContact/Attribute:contact_email+' => '',	# ''
	'Class:lnkTicketToContact/Attribute:role' => '役割', // 'Role',   # 'Role'
	'Class:lnkTicketToContact/Attribute:role+' => '',      # ''
));

//
// Class: lnkTicketToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToCI' => 'チケット/CI', // 'Ticket/CI', # 'Ticket/CI'
	'Class:lnkTicketToCI+' => '',	      # ''
	'Class:lnkTicketToCI/Attribute:ticket_id' => 'チケット', // 'Ticket',	# 'Ticket'
	'Class:lnkTicketToCI/Attribute:ticket_id+' => '',	# ''
	'Class:lnkTicketToCI/Attribute:ticket_ref' => 'チケット', // 'Ticket #', # 'Ticket #'
	'Class:lnkTicketToCI/Attribute:ticket_ref+' => '',    # ''
	'Class:lnkTicketToCI/Attribute:ci_id' => 'CI', # 'CI'
	'Class:lnkTicketToCI/Attribute:ci_id+' => '',  # ''
	'Class:lnkTicketToCI/Attribute:ci_name' => 'CI', # 'CI'
	'Class:lnkTicketToCI/Attribute:ci_name+' => '',	 # ''
	'Class:lnkTicketToCI/Attribute:ci_status' => 'CIステータス', // 'CI status',	# 'CI status'
	'Class:lnkTicketToCI/Attribute:ci_status+' => '',		# ''
	'Class:lnkTicketToCI/Attribute:impact' => '影響', // 'Impact',		# 'Impact'
	'Class:lnkTicketToCI/Attribute:impact+' => '',			# ''
));


//
// Class: ResponseTicket
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ResponseTicket' => 'レスポンスチケット', // 'ResponseTicket',	# 'ResponseTicket'
	'Class:ResponseTicket+' => '',			# ''
	'Class:ResponseTicket/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:ResponseTicket/Attribute:status+' => '',		# ''
	'Class:ResponseTicket/Attribute:status/Value:new' => '新規',	# 'New'
	'Class:ResponseTicket/Attribute:status/Value:new+' => '新規にオープン', // 'newly opened',	# 'newly opened'
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto' => 'エスカレーション/TTO', // 'Escalation/TTO', # 'Escalation/TTO'
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto+' => '',		 # ''
	'Class:ResponseTicket/Attribute:status/Value:assigned' => '割当済',		 # 'Assigned'
	'Class:ResponseTicket/Attribute:status/Value:assigned+' => '',			 # ''
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr' => 'エスカレーション/TTR', // 'Escalation/TTR', # 'Escalation/TTR'
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr+' => '',		 # ''
	'Class:ResponseTicket/Attribute:status/Value:frozen' => 'ペンディング', // 'Pending',		 # 'Pending'
	'Class:ResponseTicket/Attribute:status/Value:frozen+' => '',			 # ''
	'Class:ResponseTicket/Attribute:status/Value:resolved' => '解決済み', // 'Resolved',		 # 'Resolved'
	'Class:ResponseTicket/Attribute:status/Value:resolved+' => '',			 # ''
	'Class:ResponseTicket/Attribute:status/Value:closed' => '完了',		 # 'Closed'
	'Class:ResponseTicket/Attribute:status/Value:closed+' => '',			 # ''
	'Class:ResponseTicket/Attribute:caller_id' => '呼び出し', // 'Caller',	 # 'Caller'
	'Class:ResponseTicket/Attribute:caller_id+' => '',	 # ''
	'Class:ResponseTicket/Attribute:caller_email' => 'Eメール', // 'Email',  # 'Email'
	'Class:ResponseTicket/Attribute:caller_email+' => '',	   # ''
	'Class:ResponseTicket/Attribute:org_id' => 'カスタマ', // 'Customer',	   # 'Customer'
	'Class:ResponseTicket/Attribute:org_id+' => '',		   # ''
	'Class:ResponseTicket/Attribute:org_name' => 'カスタマ', // 'Customer',   # 'Customer'
	'Class:ResponseTicket/Attribute:org_name+' => '',	   # ''
	'Class:ResponseTicket/Attribute:service_id' => 'サービス', // 'Service',  # 'Service'
	'Class:ResponseTicket/Attribute:service_id+' => '',	   # ''
	'Class:ResponseTicket/Attribute:service_name' => '名前', // 'Name',   # 'Name'
	'Class:ResponseTicket/Attribute:service_name+' => '',	   # ''
	'Class:ResponseTicket/Attribute:servicesubcategory_id' => 'サービス要素', // 'Service element',	# 'Service element'
	'Class:ResponseTicket/Attribute:servicesubcategory_id+' => '',	   # ''
	'Class:ResponseTicket/Attribute:servicesubcategory_name' => '名前', // 'Name',  # 'Name'
	'Class:ResponseTicket/Attribute:servicesubcategory_name+' => '',     # ''
	'Class:ResponseTicket/Attribute:product' => 'プロダクト', // 'Product',	  # 'Product'
	'Class:ResponseTicket/Attribute:product+' => '',	  # ''
	'Class:ResponseTicket/Attribute:impact' => '影響', // 'Impact',	  # 'Impact'
	'Class:ResponseTicket/Attribute:impact+' => '',		  # ''
	'Class:ResponseTicket/Attribute:impact/Value:1' => '部署', // 'A department',	# 'A department'
	'Class:ResponseTicket/Attribute:impact/Value:1+' => '',			# ''
	'Class:ResponseTicket/Attribute:impact/Value:2' => 'サービス', // 'A service',		# 'A service'
	'Class:ResponseTicket/Attribute:impact/Value:2+' => '',			# ''
	'Class:ResponseTicket/Attribute:impact/Value:3' => 'パーソン', // 'A person',		# 'A person'
	'Class:ResponseTicket/Attribute:impact/Value:3+' => '',			# ''
	'Class:ResponseTicket/Attribute:urgency' => '緊急', // 'Urgency',			# 'Urgency'
	'Class:ResponseTicket/Attribute:urgency+' => '',			# ''
	'Class:ResponseTicket/Attribute:urgency/Value:1' => '高', // 'High',		# 'High'
	'Class:ResponseTicket/Attribute:urgency/Value:1+' => '',		# ''
	'Class:ResponseTicket/Attribute:urgency/Value:2' => '中', // 'Medium',		# 'Medium'
	'Class:ResponseTicket/Attribute:urgency/Value:2+' => '',		# ''
	'Class:ResponseTicket/Attribute:urgency/Value:3' => '低', // 'Low',		# 'Low'
	'Class:ResponseTicket/Attribute:urgency/Value:3+' => '',		# ''
	'Class:ResponseTicket/Attribute:priority' => 'プライオリティ', // 'Priority',		# 'Priority'
	'Class:ResponseTicket/Attribute:priority+' => '',			# ''
	'Class:ResponseTicket/Attribute:priority/Value:1' => '高', // 'High',		# 'High'
	'Class:ResponseTicket/Attribute:priority/Value:1+' => '',		# ''
	'Class:ResponseTicket/Attribute:priority/Value:2' => '中', // 'Medium',		# 'Medium'
	'Class:ResponseTicket/Attribute:priority/Value:2+' => '',		# ''
	'Class:ResponseTicket/Attribute:priority/Value:3' => '低', // 'Low',		# 'Low'
	'Class:ResponseTicket/Attribute:priority/Value:3+' => '',		# ''
	'Class:ResponseTicket/Attribute:workgroup_id' => 'ワークグループ', // 'Workgroup',		# 'Workgroup'
	'Class:ResponseTicket/Attribute:workgroup_id+' => '',			# ''
	'Class:ResponseTicket/Attribute:workgroup_name' => 'ワークグループ', // 'Workgroup',		# 'Workgroup'
	'Class:ResponseTicket/Attribute:workgroup_name+' => '',			# ''
	'Class:ResponseTicket/Attribute:agent_id' => 'エージェント', // 'Agent',			# 'Agent'
	'Class:ResponseTicket/Attribute:agent_id+' => '',			# ''
	'Class:ResponseTicket/Attribute:agent_name' => 'エージェント', // 'Agent',			# 'Agent'
	'Class:ResponseTicket/Attribute:agent_name+' => '',			# ''
	'Class:ResponseTicket/Attribute:agent_email' => 'エージェントEメール', // 'Agent email',		# 'Agent email'
	'Class:ResponseTicket/Attribute:agent_email+' => '',   # ''
	'Class:ResponseTicket/Attribute:related_problem_id' => '関連プロブレム', // 'Related Problem',	# 'Related Problem'
	'Class:ResponseTicket/Attribute:related_problem_id+' => '',	# ''
	'Class:ResponseTicket/Attribute:related_problem_ref' => '参照', // 'Ref',	# 'Ref'
	'Class:ResponseTicket/Attribute:related_problem_ref+' => '',	# ''
	'Class:ResponseTicket/Attribute:related_change_id' => '関連する変更', // 'Related change',	# 'Related change'
	'Class:ResponseTicket/Attribute:related_change_id+' => '',     # ''
	'Class:ResponseTicket/Attribute:related_change_ref' => '関連する変更', // 'Related change',	# 'Related change'
	'Class:ResponseTicket/Attribute:related_change_ref+' => '',	# ''
	'Class:ResponseTicket/Attribute:close_date' => '完了',	# 'Closed'
	'Class:ResponseTicket/Attribute:close_date+' => '',		# ''
	'Class:ResponseTicket/Attribute:last_update' => '最終更新日', // 'Last update',	# 'Last update'
	'Class:ResponseTicket/Attribute:last_update+' => '',  # ''
	'Class:ResponseTicket/Attribute:assignment_date' => 'アサイン日付', // 'Assignment Date ',	# 'Assignment Date '
	'Class:ResponseTicket/Attribute:assignment_date+' => '',	# ''
	'Class:ResponseTicket/Attribute:resolution_date' => '解決日付', // 'Resolution Date',	# 'Resolution Date'
	'Class:ResponseTicket/Attribute:resolution_date+' => '',	# ''
	'Class:ResponseTicket/Attribute:tto_escalation_deadline' => 'TTOエスカレーション締切り', // 'TTO Escalation deadline',	# 'TTO Escalation deadline'
	'Class:ResponseTicket/Attribute:tto_escalation_deadline+' => '', # ''
	'Class:ResponseTicket/Attribute:ttr_escalation_deadline' => 'TTRエスカレーション締切り', // 'TTR Escalation deadline',	# 'TTR Escalation deadline'
	'Class:ResponseTicket/Attribute:ttr_escalation_deadline+' => '', # ''
	'Class:ResponseTicket/Attribute:closure_deadline' => 'クローズ締切り', // 'Closure deadline',	# 'Closure deadline'
	'Class:ResponseTicket/Attribute:closure_deadline+' => '',     # ''
	'Class:ResponseTicket/Attribute:resolution_code' => '解決コード', // 'Resolution code',	# 'Resolution code'
	'Class:ResponseTicket/Attribute:resolution_code+' => '',	# ''
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce' => '再現できず', // 'Could not be reproduced',	# 'Could not be reproduced'
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce+' => '',   # ''
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate' => 'チケットを複製', // 'Duplicate ticket',	# 'Duplicate ticket'
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate+' => '',       # ''
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed' => '改修済み', // 'Fixed',       # 'Fixed'
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed+' => '',	       # ''
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant' => '見当違い', // 'Irrelevant',	# 'Irrelevant'
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant+' => '',		# ''
	'Class:ResponseTicket/Attribute:solution' => '解決策', // 'Solution',	   # 'Solution'
	'Class:ResponseTicket/Attribute:solution+' => '',		   # ''
	'Class:ResponseTicket/Attribute:user_satisfaction' => 'ユーザ満足度', // 'User satisfaction',	# 'User satisfaction'
	'Class:ResponseTicket/Attribute:user_satisfaction+' => '',  # ''
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1' => '大変満足である', // 'Very satisfied',	# 'Very satisfied'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1+' => '大変満足である', // 'Very satisfied',  # 'Very satisfied'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2' => '概ね満足である', // 'Fairly statisfied',  # 'Fairly statisfied'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2+' => '概ね満足である', // 'Fairly statisfied', # 'Fairly statisfied'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3' => 'やや不満である', // 'Rather Dissatified', # 'Rather Dissatified'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3+' => 'やや不満である', // 'Rather Dissatified',  # 'Rather Dissatified'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4' => '大変不満である', // 'Very Dissatisfied',    # 'Very Dissatisfied'
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4+' => '大変不満である', // 'Very Dissatisfied',   # 'Very Dissatisfied'
	'Class:ResponseTicket/Attribute:user_commment' => 'ユーザコメント', // 'User comment',    # 'User comment'
	'Class:ResponseTicket/Attribute:user_commment+' => '',	# ''
	'Class:ResponseTicket/Stimulus:ev_assign' => '割当',	# 'Assign'
	'Class:ResponseTicket/Stimulus:ev_assign+' => '',	# ''
	'Class:ResponseTicket/Stimulus:ev_reassign' => '再割当',	# 'Reassign'
	'Class:ResponseTicket/Stimulus:ev_reassign+' => '',		# ''
	'Class:ResponseTicket/Stimulus:ev_timeout' => 'エスカレーション', // 'Escalation',	# 'Escalation'
	'Class:ResponseTicket/Stimulus:ev_timeout+' => '',		# ''
	'Class:ResponseTicket/Stimulus:ev_resolve' => '解決済みとする', # 'Mark as resolved'
	'Class:ResponseTicket/Stimulus:ev_resolve+' => '',    # ''
	'Class:ResponseTicket/Stimulus:ev_close' => '完了',  # 'Close'
	'Class:ResponseTicket/Stimulus:ev_close+' => '',      # ''
));

?>
