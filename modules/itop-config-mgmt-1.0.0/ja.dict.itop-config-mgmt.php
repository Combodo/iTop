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
 * @author 	Tadashi Kaneda <kaneda@rworks.jp>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('JA JP', 'Japanese', '日語', array (
	'Relation:impacts/Description' => 'インパクトを受ける要素',
	'Relation:impacts/VerbUp' => 'インパクト...',
	'Relation:impacts/VerbDown' => 'インパクトを受ける要素',
	'Relation:depends on/Description' => 'この要素が依存している要素',
	'Relation:depends on/VerbUp' => '依存...',
	'Relation:depends on/VerbDown' => 'インパクト...',
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

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

//
// Class: Organization
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Organization' => '組織',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => '名前',
	'Class:Organization/Attribute:name+' => '共通名',
	'Class:Organization/Attribute:code' => 'コード', 
	'Class:Organization/Attribute:code+' => '組織コード(Siret, DUNS, ...)',
	'Class:Organization/Attribute:status' => '状態',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'アクティブ',
	'Class:Organization/Attribute:status/Value:active+' => 'アクティブ',
	'Class:Organization/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:Organization/Attribute:status/Value:inactive+' => '非アクティブ',
	'Class:Organization/Attribute:parent_id' => '親',
	'Class:Organization/Attribute:parent_id+' => '親組織',
	'Class:Organization/Attribute:parent_name' => '親名称',
	'Class:Organization/Attribute:parent_name+' => '親組織の名称', // 'Name of the parent organization',
));


//
// Class: Location
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Location' => '場所',
	'Class:Location+' => '任意の場所のタイプ: リージョン、国、都市、サイト、ビル、フロア、部屋、ラック、...',
	'Class:Location/Attribute:name' => '名称',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => '状態',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'アクティブ',
	'Class:Location/Attribute:status/Value:active+' => 'アクティブ',
	'Class:Location/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:Location/Attribute:status/Value:inactive+' => '非アクティブ',
	'Class:Location/Attribute:org_id' => 'オーナー組織',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'オーナー組織名称',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => '住所',
	'Class:Location/Attribute:address+' => '住所',
	'Class:Location/Attribute:postal_code' => '郵便番号',
	'Class:Location/Attribute:postal_code+' => 'ZIP/郵便番号',
	'Class:Location/Attribute:city' => '都市',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => '国',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => '親場所',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => '親名称',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => '連絡先',
	'Class:Location/Attribute:contact_list+' => 'このサイトにある連絡先',
	'Class:Location/Attribute:infra_list' => 'インフラ',
	'Class:Location/Attribute:infra_list+' => 'このサイトにあるCI',
));
//
// Class: Group
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Group' => 'グループ',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => '名称',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => '状態',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => '実装中',
	'Class:Group/Attribute:status/Value:implementation+' => '実装中',
	'Class:Group/Attribute:status/Value:obsolete' => '廃止済',
	'Class:Group/Attribute:status/Value:obsolete+' => '廃止済',
	'Class:Group/Attribute:status/Value:production' => '稼働中',
	'Class:Group/Attribute:status/Value:production+' => '稼働中',
	'Class:Group/Attribute:org_id' => '組織',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => '名前',
	'Class:Group/Attribute:owner_name+' => '共通名',
	'Class:Group/Attribute:description' => '詳細情報',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'タイプ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => '親グループ',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => '名前',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'リンクされたCI',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkGroupToCI' => 'グループ / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'グループ',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => '名前',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => '名前',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => '理由',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Class: Contact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Contact' => '連絡先',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => '名前',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => '状態',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'アクティブ',
	'Class:Contact/Attribute:status/Value:active+' => 'アクティブ',
	'Class:Contact/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:Contact/Attribute:status/Value:inactive+' => '非アクティブ',
	'Class:Contact/Attribute:org_id' => '組織',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => '組織',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Eメール',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => '電話',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => '場所',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => '場所',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CIs',
	'Class:Contact/Attribute:ci_list+' => 'この連絡先に関連するCI',
	'Class:Contact/Attribute:contract_list' => '契約',
	'Class:Contact/Attribute:contract_list+' => 'この連絡先に関連する契約',
	'Class:Contact/Attribute:service_list' => 'サービス',
	'Class:Contact/Attribute:service_list+' => 'この連絡先に関連するサービス',
	'Class:Contact/Attribute:ticket_list' => 'チケット',
	'Class:Contact/Attribute:ticket_list+' => 'この連絡先に関連するチケット',
	'Class:Contact/Attribute:team_list' => 'チーム',
	'Class:Contact/Attribute:team_list+' => 'この連絡先が所属するチーム',
	'Class:Contact/Attribute:finalclass' => 'タイプ',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Person' => '人物',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'ファーストネーム',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => '社員番号',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Team' => 'チーム',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'メンバー',
	'Class:Team/Attribute:member_list+' => 'このチーム所属の連絡先', // 'Contacts that are part of the team',
));

//
// Class: lnkTeamToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTeamToContact' => 'チームメンバー',
	'Class:lnkTeamToContact+' => 'チームのメンバー',
	'Class:lnkTeamToContact/Attribute:team_id' => 'チーム',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'メンバー',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => '場所',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Eメール',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => '電話',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => '役割',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Document' => '文書',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => '名称',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => '組織',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => '組織名',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => '説明',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'タイプ',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => '契約',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'ネットワークマップ',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'プレゼンテーション',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'トレーニング',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'ホワイトペーパー',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => '作業指示',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => '状態',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => '下書き',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => '廃止',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => '公開済み',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CIs',
	'Class:Document/Attribute:ci_list+' => 'この文書に関連するCI',
	'Class:Document/Attribute:contract_list' => '契約',
	'Class:Document/Attribute:contract_list+' => 'この文書に関連する契約',
	'Class:Document/Attribute:service_list' => 'サービス',
	'Class:Document/Attribute:service_list+' => 'サービス',
	'Class:Document/Attribute:ticket_list' => 'チケット',
	'Class:Document/Attribute:ticket_list+' => 'この文書に関連するチケット',
	'Class:Document:PreviewTab' => 'プレビュー',
));

//
// Class: WebDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:WebDoc' => 'ウェブ文書',
	'Class:WebDoc+' => '他のウェブサーバで参照可能な文書',
	'Class:WebDoc/Attribute:url' => 'URL',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Note' => 'ノート',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'テキスト',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:FileDoc' => '文書(ファイル)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'コンテンツ',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Licence' => 'ライセンス',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'プロバイダ',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'オーナー',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => '名前',
	'Class:Licence/Attribute:org_name+' => '共通名',
	'Class:Licence/Attribute:product' => '製品',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => '名称',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => '開始日',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => '終了日',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'キー',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'スコープ',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => '利用制限',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => '利用リスト',
	'Class:Licence/Attribute:usage_list+' => 'このライセンスを利用するアプリケーションインスタンス',
));


//
// Class: Subnet
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Subnet' => 'サブネット',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => '名前',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'オーナー組織',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => '説明',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IPマスク',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Patch' => 'パッチ',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => '名前',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => '説明',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'アプリケーションスコープ',
	'Class:Patch/Attribute:target_sw+' => '対象ソフトウェア(OS or アプリケーション)',
	'Class:Patch/Attribute:version' => 'バージョン',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'タイプ',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'アプリケーション',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'セキュリティ',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'サービスパック',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'デバイス',
	'Class:Patch/Attribute:ci_list+' => 'このパッチがインストールされているデバイス',
));

//
// Class: Software
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Software' => 'ソフトウェア',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => '名前',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => '説明',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'インストールリスト',
	'Class:Software/Attribute:instance_list+' => 'このソフトウェアのインスタンス',
	'Class:Software/Attribute:finalclass' => 'タイプ',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Application' => 'アプリケーション',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => '名前',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => '説明',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'インストールリスト',
	'Class:Application/Attribute:instance_list+' => 'このアプリケーションのインスタンス',
));

//
// Class: DBServer
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DBServer' => 'データベース',
	'Class:DBServer+' => 'データベースサーバソフトウェア',
	'Class:DBServer/Attribute:instance_list' => 'インストールリスト',
	'Class:DBServer/Attribute:instance_list+' => 'このデータベースサーバのインスタンス',
));

//
// Class: lnkPatchToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkPatchToCI' => 'パッチ方法',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'パッチ',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'パッチ',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '', 
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:FunctionalCI' => '機能CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => '名称',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => '状態',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => '実装中',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => '廃止済',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => '稼働中',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'オーナー組織',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'オーナー組織',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => '事業上の重要度',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => '高',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => '低',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => '中',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => '連絡先',
	'Class:FunctionalCI/Attribute:contact_list+' => 'このCIの連絡先',
	'Class:FunctionalCI/Attribute:document_list' => '文書',
	'Class:FunctionalCI/Attribute:document_list+' => 'このCIに関する文書',
	'Class:FunctionalCI/Attribute:solution_list' => 'アプリケーションソリューション',
	'Class:FunctionalCI/Attribute:solution_list+' => 'このCIを用いたアプリケーションソリューション',
	'Class:FunctionalCI/Attribute:contract_list' => '連絡先',
	'Class:FunctionalCI/Attribute:contract_list+' => 'このCIをサポートする連絡先',
	'Class:FunctionalCI/Attribute:ticket_list' => 'チケット',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'このCIに関連するチケット',
	'Class:FunctionalCI/Attribute:finalclass' => 'タイプ',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SoftwareInstance' => 'ソフトウェアインスタンス',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'デバイス',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'デバイス',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'ライセンス',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'ライセンス',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'ソフトウェア',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'バージョン',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => '説明',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApplicationInstance' => 'アプリケーションインスタンス',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => 'ソフトウェア',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => '名前', 
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DBServerInstance' => 'DBサーバインスタンス',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => 'ソフトウェア',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'ソフトウェア名',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'データベース',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'データベースソース',
));


//
// Class: DatabaseInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DatabaseInstance' => 'データベースインスタンス',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'データベースサーバー',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'データベースバージョン',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => '説明',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApplicationSolution' => 'アプリケーションソリューション',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => '説明',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CI',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'このソリューションを構成するCI',
	'Class:ApplicationSolution/Attribute:process_list' => 'ビジネスプロセス',
	'Class:ApplicationSolution/Attribute:process_list+' => 'このソリューションに依存するビジネスプロセス',
));

//
// Class: BusinessProcess
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:BusinessProcess' => 'ビジネスプロセス',
	'Class:BusinessProcess+' => '',	     # ''
	'Class:BusinessProcess/Attribute:description' => '説明',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'アプリケーションソリューション',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'このプロセスが依存するアプリケーションソリューション',
));

//
// Class: ConnectableCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ConnectableCI' => '接続可能なCI',
	'Class:ConnectableCI+' => '物理的なCI', 
	'Class:ConnectableCI/Attribute:brand' => 'ブランド',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'モデル',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'シリアル番号',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => '資産のリファレンス', 
	'Class:ConnectableCI/Attribute:asset_ref+' => '',   # ''
));

//
// Class: NetworkInterface
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NetworkInterface' => 'ネットワークインタフェース',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'デバイス',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'デバイス',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => '論理タイプ',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'バックアップ',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => '論理',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'ポート',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'プライマリ',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'セカンダリ',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => '物理タイプ',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'イーサネット',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'フレームリレー',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'IPアドレス',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'IPマスク',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'MACアドレス',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => '速度',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => '二重',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => '自動',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => '自動',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => '全',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => '半',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => '不明',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => '接続済み',
	'Class:NetworkInterface/Attribute:connected_if+' => '接続済みインタフェース',
	'Class:NetworkInterface/Attribute:connected_name' => '接続済み',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => '接続デバイス',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'デバイス',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'リンクタイプ',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'ダウンリンク',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'アップリンク',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Device' => 'デバイス',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'ネットワークインタフェース',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'ハードディスク',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OSファミリ',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OSバージョン',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'アプリケーション',
	'Class:PC/Attribute:application_list+' => 'このPCにインストール済みアプリケーション',
	'Class:PC/Attribute:patch_list' => 'パッチ',
	'Class:PC/Attribute:patch_list+' => 'このPCにインストール済みのパッチ',
));

//
// Class: MobileCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:MobileCI' => 'モバイルCI',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:MobilePhone' => '携帯電話',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => '電話番号',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'ハードウェアPIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:InfrastructureCI' => 'インフラCI',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => '説明',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => '場所',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => '場所',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => '場所の詳細',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => '管理IP',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'デフォルトゲートウェイ',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NetworkDevice' => 'ネットワークデバイス',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'タイプ',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WANアクセラレータ',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '', 
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'ファイアウォール',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'ハブ',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'ロードバランサ',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'ルータ',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'スイッチ',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'IOSバージョン',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP 読み取り',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP 書き込み',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Server' => 'サーバ',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'ハードディスク',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'OSファミリ',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'OSバージョン',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'アプリケーション',
	'Class:Server/Attribute:application_list+' => 'このサーバにインストールされたアプリケーション',
	'Class:Server/Attribute:patch_list' => 'パッチ',
	'Class:Server/Attribute:patch_list+' => 'このサーバにインストールされたパッチ', // 'Patches installed on this server',
));

//
// Class: Printer
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Printer' => 'プリンタ',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'タイプ', 
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'プリンタ',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'テクノロジ',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'インクジェット',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'レーザー',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'トレーサー',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCIToDoc' => 'CI/文書',
	'Class:lnkCIToDoc+' => '',	# ''
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CIの状態',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => '文書',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => '文書',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => '文書のタイプ',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => '文書の状態',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCIToContact' => 'CI/連絡先',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CIの状態',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => '連絡先', 
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => '連絡先',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => '連絡先Eメール',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => '役割',
	'Class:lnkCIToContact/Attribute:role+' => 'このCIに関連する連絡先の役割', // 'Role of the contact regarding the CI',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkSolutionToCI' => 'ソリューション/CI',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'アプリケーションソリューション',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'アプリケーションソリューション',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => '効用',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'ソリューション中でのこのCIの効用',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkProcessToSolution' => 'ビジネスプロセス/ソリューション',
	'Class:lnkProcessToSolution+' => '',	  # ''
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'アプリケーションソリューション',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'アプリケーションソリューション',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'プロセス',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'プロセス',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => '理由',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'プロセスとソリューション間のリンクに関する詳細情報',
));



//
// Class extensions
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Subnet/Tab:IPUsage' => 'IPの用途',
	'Class:Subnet/Tab:IPUsage-explain' => '<em>%1$s</em>から<em>%2$s</em>の範囲のIPアドレスを保持するインタフェース',
	'Class:Subnet/Tab:FreeIPs' => '未割り当てIP',
	'Class:Subnet/Tab:FreeIPs-count' => '未割り当てIPアドレス：%1$s',
	'Class:Subnet/Tab:FreeIPs-explain' => 'これが未割り当てIPアドレスから10個を抽出したものです。',
));

//
// Application Menu
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:Catalogs' => 'カタログ',
	'Menu:Catalogs+' => 'データタイプ',
	'Menu:Audit' => '監査',
	'Menu:Audit+' => '監査',
	'Menu:Organization' => '組織',
	'Menu:Organization+' => '全組織',
	'Menu:Application' => 'アプリケーション',
	'Menu:Application+' => '全アプリケーション',
	'Menu:DBServer' => 'データベースサーバ',
	'Menu:DBServer+' => 'データベースサーバ',
	'Menu:Audit' => '監査',
	'Menu:ConfigManagement' => '構成管理',
	'Menu:ConfigManagement+' => '構成管理',
	'Menu:ConfigManagementOverview' => '概要',
	'Menu:ConfigManagementOverview+' => '概要',
	'Menu:Contact' => '連絡先',
	'Menu:Contact+' => '連絡先',
	'Menu:Person' => '人物',
	'Menu:Person+' => '全員',
	'Menu:Team' => 'チーム',
	'Menu:Team+' => '全チーム',
	'Menu:Document' => '文書',
	'Menu:Document+' => '全文書',
	'Menu:Location' => '場所',
	'Menu:Location+' => '全ての場所',
	'Menu:ConfigManagementCI' => '構成項目(CI)',
	'Menu:ConfigManagementCI+' => '構成項目 (Configuration Items)',
	'Menu:BusinessProcess' => 'ビジネスプロセス',
	'Menu:BusinessProcess+' => '全ビジネスプロセス',
	'Menu:ApplicationSolution' => 'アプリケーションソリューション',
	'Menu:ApplicationSolution+' => '全アプリケーションソリューション',
	'Menu:ConfigManagementSoftware' => 'アプリケーション管理',
	'Menu:Licence' => 'ライセンス',
	'Menu:Licence+' => '全ライセンス',
	'Menu:Patch' => 'パッチ',
	'Menu:Patch+' => '全パッチ',
	'Menu:ApplicationInstance' => 'インストールされたソフトウェア',
	'Menu:ApplicationInstance+' => 'アプリケーションとデータベースサーバ',
	'Menu:ConfigManagementHardware' => 'インフラ管理',
	'Menu:Subnet' => 'サブネット',
	'Menu:Subnet+' => '全サブネット',
	'Menu:NetworkDevice' => 'ネットワークデバイス',
	'Menu:NetworkDevice+' => '全ネットワークデバイス',
	'Menu:Server' => 'サーバ', 
	'Menu:Server+' => '全サーバ',
	'Menu:Printer' => 'プリンタ',
	'Menu:Printer+' => '全プリンタ',
	'Menu:MobilePhone' => '携帯電話',
	'Menu:MobilePhone+' => '全携帯電話',
	'Menu:PC' => 'パーソナルコンピュータ',
	'Menu:PC+' => '全パーソナルコンピュータ',
	'Menu:NewContact' => '新規連絡先',
	'Menu:NewContact+' => '新規連絡先',
	'Menu:SearchContacts' => '連絡先検索',
	'Menu:SearchContacts+' => '連絡先検索',
	'Menu:NewCI' => '新規登録',
	'Menu:NewCI+' => '新規登録',
	'Menu:SearchCIs' => 'CI検索',
	'Menu:SearchCIs+' => 'CI検索',
	'Menu:ConfigManagement:Devices' => 'デバイス',
	'Menu:ConfigManagement:AllDevices' => 'デバイス数: %1$d',
	'Menu:ConfigManagement:SWAndApps' => 'ソフトウェアとアプリケーション',
	'Menu:ConfigManagement:Misc' => 'その他',
	'Menu:Group' => 'CIグループ',
	'Menu:Group+' => 'CIグループ',
	'Menu:ConfigManagement:Shortcuts' => 'ショートカット',
	'Menu:ConfigManagement:AllContacts' => '全連絡先：%1$d',
));
?>
