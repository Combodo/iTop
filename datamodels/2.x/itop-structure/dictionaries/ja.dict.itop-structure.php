<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
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
Dict::Add('JA JP', 'Japanese', '日本語', array(
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
	'Class:Organization/Attribute:parent_name' => '親名前',
	'Class:Organization/Attribute:parent_name+' => '親組織の名前',
	'Class:Organization/Attribute:deliverymodel_id' => '提供モデル',
	'Class:Organization/Attribute:deliverymodel_id+' => '',
	'Class:Organization/Attribute:deliverymodel_name' => '提供モデル名',
	'Class:Organization/Attribute:deliverymodel_name+' => '',
	'Class:Organization/Attribute:parent_id_friendlyname' => '親',
	'Class:Organization/Attribute:parent_id_friendlyname+' => '親組織',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => 'iTop Users within this organization~~',
));

//
// Class: Location
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Location' => '場所',
	'Class:Location+' => '任意の場所のタイプ: リージョン、国、都市、サイト、ビル、フロア、部屋、ラック、...',
	'Class:Location/Attribute:name' => '名前',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => '状態',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'アクティブ',
	'Class:Location/Attribute:status/Value:active+' => 'アクティブ',
	'Class:Location/Attribute:status/Value:inactive' => '非アクティブ',
	'Class:Location/Attribute:status/Value:inactive+' => '非アクティブ',
	'Class:Location/Attribute:org_id' => 'オーナー組織',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'オーナー組織名前',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => '住所',
	'Class:Location/Attribute:address+' => '住所',
	'Class:Location/Attribute:postal_code' => '郵便番号',
	'Class:Location/Attribute:postal_code+' => 'ZIP/郵便番号',
	'Class:Location/Attribute:city' => '都市',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => '国',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:physicaldevice_list' => 'デバイス',
	'Class:Location/Attribute:physicaldevice_list+' => '',
	'Class:Location/Attribute:person_list' => '連絡先',
	'Class:Location/Attribute:person_list+' => '',
));

//
// Class: Contact
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
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
	'Class:Contact/Attribute:notify' => '通知',
	'Class:Contact/Attribute:notify+' => '',
	'Class:Contact/Attribute:notify/Value:no' => 'いいえ',
	'Class:Contact/Attribute:notify/Value:no+' => 'いいえ',
	'Class:Contact/Attribute:notify/Value:yes' => 'はい',
	'Class:Contact/Attribute:notify/Value:yes+' => 'はい',
	'Class:Contact/Attribute:function' => '機能',
	'Class:Contact/Attribute:function+' => '',
	'Class:Contact/Attribute:cis_list' => 'CI',
	'Class:Contact/Attribute:cis_list+' => '',
	'Class:Contact/Attribute:finalclass' => '連絡先タイプ',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Person' => '人物',
	'Class:Person+' => '',
	'Class:Person/Attribute:name' => '姓',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => 'ファーストネーム',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => '社員番号',
	'Class:Person/Attribute:employee_number+' => '',
	'Class:Person/Attribute:mobile_phone' => '携帯電話',
	'Class:Person/Attribute:mobile_phone+' => '',
	'Class:Person/Attribute:location_id' => '場所',
	'Class:Person/Attribute:location_id+' => '',
	'Class:Person/Attribute:location_name' => '場所名',
	'Class:Person/Attribute:location_name+' => '',
	'Class:Person/Attribute:manager_id' => 'マネージャ',
	'Class:Person/Attribute:manager_id+' => '',
	'Class:Person/Attribute:manager_name' => 'マネージャ名',
	'Class:Person/Attribute:manager_name+' => '',
	'Class:Person/Attribute:team_list' => 'チーム',
	'Class:Person/Attribute:team_list+' => '',
	'Class:Person/Attribute:tickets_list' => 'チケット',
	'Class:Person/Attribute:tickets_list+' => '',
	'Class:Person/Attribute:manager_id_friendlyname' => 'マネージャーフレンドリ名',
	'Class:Person/Attribute:manager_id_friendlyname+' => '',
	'Class:Person/Attribute:picture' => 'Picture~~',
	'Class:Person/Attribute:picture+' => '~~',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization~~',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number~~',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization~~',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name~~',
));

//
// Class: Team
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Team' => 'チーム',
	'Class:Team+' => '',
	'Class:Team/Attribute:persons_list' => 'メンバー',
	'Class:Team/Attribute:persons_list+' => '',
	'Class:Team/Attribute:tickets_list' => 'チケット',
	'Class:Team/Attribute:tickets_list+' => '',
));

//
// Class: Document
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Document' => '文書',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => '名前',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => '組織',
	'Class:Document/Attribute:org_id+' => '',
	'Class:Document/Attribute:org_name' => '組織名',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => '文書タイプ',
	'Class:Document/Attribute:documenttype_id+' => '',
	'Class:Document/Attribute:documenttype_name' => '文書タイプ名',
	'Class:Document/Attribute:documenttype_name+' => '',
	'Class:Document/Attribute:version' => 'Version~~',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => '説明',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => '状態',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => '下書き',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => '廃止',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => '公開済み',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CI',
	'Class:Document/Attribute:cis_list+' => '',
	'Class:Document/Attribute:finalclass' => '文書タイプ',
	'Class:Document/Attribute:finalclass+' => '',
));

//
// Class: DocumentFile
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DocumentFile' => '文書ファイル',
	'Class:DocumentFile+' => '',
	'Class:DocumentFile/Attribute:file' => 'ファイル',
	'Class:DocumentFile/Attribute:file+' => '',
));

//
// Class: DocumentNote
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DocumentNote' => '文書ノート',
	'Class:DocumentNote+' => '',
	'Class:DocumentNote/Attribute:text' => 'テキスト',
	'Class:DocumentNote/Attribute:text+' => '',
));

//
// Class: DocumentWeb
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DocumentWeb' => '文書Web',
	'Class:DocumentWeb+' => '',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '',
));

//
// Class: Typology
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Typology' => '分類',
	'Class:Typology+' => '',
	'Class:Typology/Attribute:name' => '名前',
	'Class:Typology/Attribute:name+' => '',
	'Class:Typology/Attribute:finalclass' => 'タイプ',
	'Class:Typology/Attribute:finalclass+' => '',
));

//
// Class: DocumentType
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DocumentType' => '文書タイプ',
	'Class:DocumentType+' => '',
));

//
// Class: ContactType
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ContactType' => '問い合せ先タイプ',
	'Class:ContactType+' => '',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkPersonToTeam' => 'リンク 人物/チーム',
	'Class:lnkPersonToTeam+' => '',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'チーム',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'チーム名',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '',
	'Class:lnkPersonToTeam/Attribute:person_id' => '人物',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '',
	'Class:lnkPersonToTeam/Attribute:person_name' => '名前',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '',
	'Class:lnkPersonToTeam/Attribute:role_id' => '役割',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '',
	'Class:lnkPersonToTeam/Attribute:role_name' => '役割名',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '',
));

//
// Application Menu
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Menu:DataAdministration' => 'データ管理',
	'Menu:DataAdministration+' => 'データ管理',
	'Menu:Catalogs' => 'カタログ',
	'Menu:Catalogs+' => 'データタイプ',
	'Menu:Audit' => '監査',
	'Menu:Audit+' => '監査',
	'Menu:CSVImport' => 'CSV インポート',
	'Menu:CSVImport+' => '一括作成/一括更新',
	'Menu:Organization' => '組織',
	'Menu:Organization+' => '全組織',
	'Menu:ConfigManagement' => '構成管理',
	'Menu:ConfigManagement+' => '構成管理',
	'Menu:ConfigManagementCI' => '構成管理項目',
	'Menu:ConfigManagementCI+' => '構成管理項目',
	'Menu:ConfigManagementOverview' => '概要',
	'Menu:ConfigManagementOverview+' => '概要',
	'Menu:Contact' => '連絡先',
	'Menu:Contact+' => '連絡先',
	'Menu:Contact:Count' => '%1$d',
	'Menu:Person' => '人物',
	'Menu:Person+' => '全人物',
	'Menu:Team' => 'チーム',
	'Menu:Team+' => '全チーム',
	'Menu:Document' => '文書',
	'Menu:Document+' => '全文書',
	'Menu:Location' => '場所',
	'Menu:Location+' => '全ての場所',
	'Menu:NewContact' => '新規連絡先',
	'Menu:NewContact+' => '新規連絡先',
	'Menu:SearchContacts' => '連絡先検索',
	'Menu:SearchContacts+' => '連絡先検索',
	'Menu:ConfigManagement:Shortcuts' => 'ショートカット',
	'Menu:ConfigManagement:AllContacts' => '全連絡先: %1$d',
	'Menu:Typology' => 'トポロジー構成',
	'Menu:Typology+' => 'トポロジー構成',
	'UI_WelcomeMenu_AllConfigItems' => 'サマリー',
	'Menu:ConfigManagement:Typology' => '分類構成',
));

// Add translation for Fieldsets

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Person:info' => '情報',
	'UserLocal:info' => 'General information~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => '通知',
));

// Themes
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'theme:fullmoon' => 'Full moon~~',
	'theme:test-red' => 'Test instance (Red)~~',
));
