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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Change' => '変更',
	'Class:Change+' => '',
	'Class:Change/Attribute:status' => '状態',
	'Class:Change/Attribute:status+' => '',
	'Class:Change/Attribute:status/Value:new' => '新規',
	'Class:Change/Attribute:status/Value:new+' => '',
	'Class:Change/Attribute:status/Value:assigned' => '割り当て済み',
	'Class:Change/Attribute:status/Value:assigned+' => '',
	'Class:Change/Attribute:status/Value:planned' => '計画済み',
	'Class:Change/Attribute:status/Value:planned+' => '',
	'Class:Change/Attribute:status/Value:rejected' => '却下',
	'Class:Change/Attribute:status/Value:rejected+' => '',
	'Class:Change/Attribute:status/Value:approved' => '承認済み',
	'Class:Change/Attribute:status/Value:approved+' => '',
	'Class:Change/Attribute:status/Value:closed' => 'クローズ',
	'Class:Change/Attribute:status/Value:closed+' => '',
	'Class:Change/Attribute:category' => 'カテゴリ',
	'Class:Change/Attribute:category+' => '',
	'Class:Change/Attribute:category/Value:application' => 'アプリケーション',
	'Class:Change/Attribute:category/Value:application+' => '',
	'Class:Change/Attribute:category/Value:hardware' => 'ハードウエア',
	'Class:Change/Attribute:category/Value:hardware+' => '',
	'Class:Change/Attribute:category/Value:network' => 'ネットワーク',
	'Class:Change/Attribute:category/Value:network+' => '',
	'Class:Change/Attribute:category/Value:other' => 'その他',
	'Class:Change/Attribute:category/Value:other+' => '',
	'Class:Change/Attribute:category/Value:software' => 'ソフトウエア',
	'Class:Change/Attribute:category/Value:software+' => '',
	'Class:Change/Attribute:category/Value:system' => 'システム',
	'Class:Change/Attribute:category/Value:system+' => '',
	'Class:Change/Attribute:reject_reason' => '却下理由',
	'Class:Change/Attribute:reject_reason+' => '',
	'Class:Change/Attribute:changemanager_id' => '変更管理者',
	'Class:Change/Attribute:changemanager_id+' => '',
	'Class:Change/Attribute:parent_id' => '親変更',
	'Class:Change/Attribute:parent_id+' => '',
	'Class:Change/Attribute:creation_date' => '作成日',
	'Class:Change/Attribute:creation_date+' => '',
	'Class:Change/Attribute:approval_date' => '承認日',
	'Class:Change/Attribute:approval_date+' => '',
	'Class:Change/Attribute:fallback_plan' => 'フォールバック計画',
	'Class:Change/Attribute:fallback_plan+' => '',
	'Class:Change/Attribute:related_request_list' => '関連要求',
	'Class:Change/Attribute:related_request_list+' => '',
	'Class:Change/Attribute:child_changes_list' => '子変更',
	'Class:Change/Attribute:child_changes_list+' => '',
	'Class:Change/Stimulus:ev_assign' => '割り当て',
	'Class:Change/Stimulus:ev_assign+' => '',
	'Class:Change/Stimulus:ev_plan' => '計画',
	'Class:Change/Stimulus:ev_plan+' => '',
	'Class:Change/Stimulus:ev_reject' => '却下',
	'Class:Change/Stimulus:ev_reject+' => '',
	'Class:Change/Stimulus:ev_reopen' => '再オープン',
	'Class:Change/Stimulus:ev_reopen+' => '',
	'Class:Change/Stimulus:ev_approve' => '承認',
	'Class:Change/Stimulus:ev_approve+' => '',
	'Class:Change/Stimulus:ev_finish' => 'クローズ',
	'Class:Change/Stimulus:ev_finish+' => '',
	'Menu:ChangeManagement' => '変更管理',
	'Menu:Change:Overview' => '概要',
	'Menu:Change:Overview+' => '',
	'Menu:NewChange' => '新規変更',
	'Menu:NewChange+' => '',
	'Menu:SearchChanges' => '変更検索',
	'Menu:SearchChanges+' => '',
	'Menu:Change:Shortcuts' => 'ショートカット',
	'Menu:Change:Shortcuts+' => '',
	'Menu:WaitingAcceptance' => '受け付け待ちの変更',
	'Menu:WaitingAcceptance+' => '',
	'Menu:WaitingApproval' => '承認待ちの変更',
	'Menu:WaitingApproval+' => '',
	'Menu:Changes' => 'オープンな変更',
	'Menu:Changes+' => '',
	'Menu:MyChanges' => '私に割り当てられた変更',
	'Menu:MyChanges+' => '',
	'UI-ChangeManagementOverview-ChangeByCategory-last-7-days' => '最近7日間のカテゴリ別の変更',
	'UI-ChangeManagementOverview-Last-7-days' => '最近7日間の変更数',
	'UI-ChangeManagementOverview-ChangeByDomain-last-7-days' => '最近7日間のドメイン別変更',
	'UI-ChangeManagementOverview-ChangeByStatus-last-7-days' => '最近7日間の状態別変更',
	'Class:Change/Attribute:changemanager_email' => '変更管理者電子メール',
	'Class:Change/Attribute:changemanager_email+' => '',
	'Class:Change/Attribute:parent_name' => '親変更参照',
	'Class:Change/Attribute:parent_name+' => '',
	'Class:Change/Attribute:related_incident_list' => '関連インシデント',
	'Class:Change/Attribute:related_incident_list+' => '',
	'Class:Change/Attribute:parent_id_friendlyname' => '親変更フレンドリー名',
	'Class:Change/Attribute:parent_id_friendlyname+' => '',
	'Tickets:Related:OpenChanges' => 'Open changes~~',
	'Tickets:Related:RecentChanges' => 'Recent changes (72h)~~',
	'Class:Change/Attribute:related_problems_list' => '関連問題',
	'Class:Change/Attribute:related_problems_list+' => '',
	'Class:Change/Attribute:outage' => '停止',
	'Class:Change/Attribute:outage/Value:no' => 'いいえ',
	'Class:Change/Attribute:outage/Value:yes' => 'はい',
));
?>