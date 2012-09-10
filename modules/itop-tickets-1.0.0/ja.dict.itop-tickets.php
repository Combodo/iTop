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
	'Class:Ticket' => 'チケット',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => '参照',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:title' => '題名',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => '説明',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:ticket_log' => 'ログ', 
	'Class:Ticket/Attribute:ticket_log+' => '',	# ''
	'Class:Ticket/Attribute:start_date' => '開始日',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:document_list' => '文書',
	'Class:Ticket/Attribute:document_list+' => 'このチケットに関連する文書',
	'Class:Ticket/Attribute:ci_list' => 'CI',
	'Class:Ticket/Attribute:ci_list+' => 'このインシデントに関連するCI',
	'Class:Ticket/Attribute:contact_list' => '連絡先',
	'Class:Ticket/Attribute:contact_list+' => '担当チーム、担当者',
	'Class:Ticket/Attribute:incident_list' => '関連するインシデント',
	'Class:Ticket/Attribute:incident_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'タイプ',
	'Class:Ticket/Attribute:finalclass+' => '',
));

// Fieldset translation
Dict::Add('JA JP', 'Japanese', '日本語', array(

	'Ticket:baseinfo' => '基本情報',
	'Ticket:date' => '日付',
	'Ticket:contact' => '連絡先',
	'Ticket:moreinfo' => '追加情報',
	'Ticket:relation' => '関連',
	'Ticket:log' => 'コミュニケーション',

));
//
// Class: lnkTicketToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToDoc' => 'チケット/文書',
	'Class:lnkTicketToDoc+' => '',
	'Class:lnkTicketToDoc/Attribute:ticket_id' => 'チケット',
	'Class:lnkTicketToDoc/Attribute:ticket_id+' => '',
	'Class:lnkTicketToDoc/Attribute:ticket_ref' => 'チケット',
	'Class:lnkTicketToDoc/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToDoc/Attribute:document_id' => '文書',
	'Class:lnkTicketToDoc/Attribute:document_id+' => '',
	'Class:lnkTicketToDoc/Attribute:document_name' => '文書',
	'Class:lnkTicketToDoc/Attribute:document_name+' => '',
));

//
// Class: lnkTicketToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToContact' => 'チケット/連絡先',
	'Class:lnkTicketToContact+' => '',
	'Class:lnkTicketToContact/Attribute:ticket_id' => 'チケット',
	'Class:lnkTicketToContact/Attribute:ticket_id+' => '',
	'Class:lnkTicketToContact/Attribute:ticket_ref' => 'チケット',
	'Class:lnkTicketToContact/Attribute:ticket_ref+' => '',	
	'Class:lnkTicketToContact/Attribute:contact_id' => '連絡先',
	'Class:lnkTicketToContact/Attribute:contact_id+' => '',
	'Class:lnkTicketToContact/Attribute:contact_name' => '連絡先',
	'Class:lnkTicketToContact/Attribute:contact_name+' => '',
	'Class:lnkTicketToContact/Attribute:contact_email' => 'Eメール',
	'Class:lnkTicketToContact/Attribute:contact_email+' => '',
	'Class:lnkTicketToContact/Attribute:role' => '役割',
	'Class:lnkTicketToContact/Attribute:role+' => '',
));

//
// Class: lnkTicketToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTicketToCI' => 'チケット/CI',
	'Class:lnkTicketToCI+' => '',
	'Class:lnkTicketToCI/Attribute:ticket_id' => 'チケット',
	'Class:lnkTicketToCI/Attribute:ticket_id+' => '',
	'Class:lnkTicketToCI/Attribute:ticket_ref' => 'チケット',
	'Class:lnkTicketToCI/Attribute:ticket_ref+' => '',
	'Class:lnkTicketToCI/Attribute:ci_id' => 'CI',
	'Class:lnkTicketToCI/Attribute:ci_id+' => '',
	'Class:lnkTicketToCI/Attribute:ci_name' => 'CI',
	'Class:lnkTicketToCI/Attribute:ci_name+' => '',
	'Class:lnkTicketToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkTicketToCI/Attribute:ci_status+' => '',
	'Class:lnkTicketToCI/Attribute:impact' => 'インパクト',
	'Class:lnkTicketToCI/Attribute:impact+' => '',
));


//
// Class: ResponseTicket
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ResponseTicket' => 'レスポンスチケット',
	'Class:ResponseTicket+' => '',
	'Class:ResponseTicket/Attribute:status' => '状態',
	'Class:ResponseTicket/Attribute:status+' => '',
	'Class:ResponseTicket/Attribute:status/Value:new' => '新規',
	'Class:ResponseTicket/Attribute:status/Value:new+' => '新規にオープン',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto' => 'エスカレーション/TTO',
	'Class:ResponseTicket/Attribute:status/Value:escalated_tto+' => '',
	'Class:ResponseTicket/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:ResponseTicket/Attribute:status/Value:assigned+' => '',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr' => 'エスカレーション/TTR',
	'Class:ResponseTicket/Attribute:status/Value:escalated_ttr+' => '',
	'Class:ResponseTicket/Attribute:status/Value:frozen' => '保留', // 'Pending',
	'Class:ResponseTicket/Attribute:status/Value:frozen+' => '',
	'Class:ResponseTicket/Attribute:status/Value:resolved' => '解決済み',
	'Class:ResponseTicket/Attribute:status/Value:resolved+' => '',
	'Class:ResponseTicket/Attribute:status/Value:closed' => 'クローズ',
	'Class:ResponseTicket/Attribute:status/Value:closed+' => '',
	'Class:ResponseTicket/Attribute:caller_id' => '連絡者', // 'caller',
	'Class:ResponseTicket/Attribute:caller_id+' => '',
	'Class:ResponseTicket/Attribute:caller_email' => 'Eメール',
	'Class:ResponseTicket/Attribute:caller_email+' => '',
	'Class:ResponseTicket/Attribute:org_id' => '顧客',
	'Class:ResponseTicket/Attribute:org_id+' => '',
	'Class:ResponseTicket/Attribute:org_name' => '顧客',
	'Class:ResponseTicket/Attribute:org_name+' => '',
	'Class:ResponseTicket/Attribute:service_id' => 'サービス',
	'Class:ResponseTicket/Attribute:service_id+' => '',
	'Class:ResponseTicket/Attribute:service_name' => '名前',
	'Class:ResponseTicket/Attribute:service_name+' => '',
	'Class:ResponseTicket/Attribute:servicesubcategory_id' => 'サービス要素', // 'Service element',
	'Class:ResponseTicket/Attribute:servicesubcategory_id+' => '',
	'Class:ResponseTicket/Attribute:servicesubcategory_name' => '名前',
	'Class:ResponseTicket/Attribute:servicesubcategory_name+' => '',
	'Class:ResponseTicket/Attribute:product' => '製品',
	'Class:ResponseTicket/Attribute:product+' => '',
	'Class:ResponseTicket/Attribute:impact' => 'インパクト',
	'Class:ResponseTicket/Attribute:impact+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:1' => '部署',
	'Class:ResponseTicket/Attribute:impact/Value:1+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:2' => 'サービス',
	'Class:ResponseTicket/Attribute:impact/Value:2+' => '',
	'Class:ResponseTicket/Attribute:impact/Value:3' => '人',
	'Class:ResponseTicket/Attribute:impact/Value:3+' => '',
	'Class:ResponseTicket/Attribute:urgency' => '緊急度',
	'Class:ResponseTicket/Attribute:urgency+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:1' => '高',
	'Class:ResponseTicket/Attribute:urgency/Value:1+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:2' => '中',
	'Class:ResponseTicket/Attribute:urgency/Value:2+' => '',
	'Class:ResponseTicket/Attribute:urgency/Value:3' => '低',
	'Class:ResponseTicket/Attribute:urgency/Value:3+' => '',
	'Class:ResponseTicket/Attribute:priority' => '優先度',
	'Class:ResponseTicket/Attribute:priority+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:1' => '高',
	'Class:ResponseTicket/Attribute:priority/Value:1+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:2' => '中',
	'Class:ResponseTicket/Attribute:priority/Value:2+' => '',
	'Class:ResponseTicket/Attribute:priority/Value:3' => '低',
	'Class:ResponseTicket/Attribute:priority/Value:3+' => '',
	'Class:ResponseTicket/Attribute:workgroup_id' => 'ワークグループ',
	'Class:ResponseTicket/Attribute:workgroup_id+' => '',
	'Class:ResponseTicket/Attribute:workgroup_name' => 'ワークグループ',
	'Class:ResponseTicket/Attribute:workgroup_name+' => '',
	'Class:ResponseTicket/Attribute:agent_id' => 'エージェント',
	'Class:ResponseTicket/Attribute:agent_id+' => '',
	'Class:ResponseTicket/Attribute:agent_name' => 'エージェント',
	'Class:ResponseTicket/Attribute:agent_name+' => '',
	'Class:ResponseTicket/Attribute:agent_email' => 'エージェントEメール',
	'Class:ResponseTicket/Attribute:agent_email+' => '',
	'Class:ResponseTicket/Attribute:related_problem_id' => '関連する問題',
	'Class:ResponseTicket/Attribute:related_problem_id+' => '',
	'Class:ResponseTicket/Attribute:related_problem_ref' => '参照',
	'Class:ResponseTicket/Attribute:related_problem_ref+' => '',
	'Class:ResponseTicket/Attribute:related_change_id' => '関連する変更',
	'Class:ResponseTicket/Attribute:related_change_id+' => '',
	'Class:ResponseTicket/Attribute:related_change_ref' => '関連する変更', // 'Related change',
	'Class:ResponseTicket/Attribute:related_change_ref+' => '',
	'Class:ResponseTicket/Attribute:close_date' => 'クローズ',
	'Class:ResponseTicket/Attribute:close_date+' => '',
	'Class:ResponseTicket/Attribute:last_update' => '最終更新日',
	'Class:ResponseTicket/Attribute:last_update+' => '',
	'Class:ResponseTicket/Attribute:assignment_date' => '割り当て日',
	'Class:ResponseTicket/Attribute:assignment_date+' => '',
	'Class:ResponseTicket/Attribute:resolution_date' => '解決日',
	'Class:ResponseTicket/Attribute:resolution_date+' => '',
	'Class:ResponseTicket/Attribute:tto_escalation_deadline' => 'TTOエスカレーション期限', // 'TTO Escalation deadline',
	'Class:ResponseTicket/Attribute:tto_escalation_deadline+' => '',
	'Class:ResponseTicket/Attribute:ttr_escalation_deadline' => 'TTRエスカレーション期限', // 'TTR Escalation deadline',
	'Class:ResponseTicket/Attribute:ttr_escalation_deadline+' => '',
	'Class:ResponseTicket/Attribute:closure_deadline' => 'クローズ期限', // 'Closure deadline',
	'Class:ResponseTicket/Attribute:closure_deadline+' => '',
	'Class:ResponseTicket/Attribute:resolution_code' => '解決コード',
	'Class:ResponseTicket/Attribute:resolution_code+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce' => '再現出来ない',
	'Class:ResponseTicket/Attribute:resolution_code/Value:couldnotreproduce+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate' => 'チケットを複製',
	'Class:ResponseTicket/Attribute:resolution_code/Value:duplicate+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed' => '改修済み',
	'Class:ResponseTicket/Attribute:resolution_code/Value:fixed+' => '',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant' => '見当違い', // 'Irrelevant',
	'Class:ResponseTicket/Attribute:resolution_code/Value:irrelevant+' => '',
	'Class:ResponseTicket/Attribute:solution' => '解決策',
	'Class:ResponseTicket/Attribute:solution+' => '',		   # ''
	'Class:ResponseTicket/Attribute:user_satisfaction' => 'ユーザ満足度',
	'Class:ResponseTicket/Attribute:user_satisfaction+' => '',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1' => '大変満足', // 'Very satisfied',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:1+' => '大変満足', // 'Very satisfied',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2' => '満足', // 'Fairly statisfied',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:2+' => '満足', // 'Fairly statisfied',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3' => '不満', // 'Rather Dissatified',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:3+' => '不満', // 'Rather Dissatified',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4' => '大変不満', // 'Very Dissatisfied',
	'Class:ResponseTicket/Attribute:user_satisfaction/Value:4+' => '大変不満', // 'Very Dissatisfied',
	'Class:ResponseTicket/Attribute:user_commment' => 'ユーザコメント',
	'Class:ResponseTicket/Attribute:user_commment+' => '',
	'Class:ResponseTicket/Stimulus:ev_assign' => '割り当て',
	'Class:ResponseTicket/Stimulus:ev_assign+' => '',
	'Class:ResponseTicket/Stimulus:ev_reassign' => '再割り当て',
	'Class:ResponseTicket/Stimulus:ev_reassign+' => '',
	'Class:ResponseTicket/Stimulus:ev_timeout' => 'ev_タイムアウト', // 'Escalation', 'ev_timeout',
	'Class:ResponseTicket/Stimulus:ev_timeout+' => '',
	'Class:ResponseTicket/Stimulus:ev_resolve' => '解決済み',
	'Class:ResponseTicket/Stimulus:ev_resolve+' => '',
	'Class:ResponseTicket/Stimulus:ev_close' => 'クローズ',
	'Class:ResponseTicket/Stimulus:ev_close+' => '',
));




?>
