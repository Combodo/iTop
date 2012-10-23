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
 * @author 	Tadashi Kaneda <kaneda@rworks.jp>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Relation:impacts/Description' => '影響を受ける要素???', // Elements impacted by',	# 'Elements impacted by'
	'Relation:impacts/VerbUp' => '影響...???', // 'Impact...',   	     		# 'Impact...'
	'Relation:impacts/VerbDown' => '影響を受ける要素???', // Elements impacted by...',	# 'Elements impacted by...'
	'Relation:depends on/Description' => 'この要素に依存する要素群???', // Elements this element depends on',    # 'Elements this element depends on'
	'Relation:depends on/VerbUp' => '...に依存する???',    # 'Depends on...'
	'Relation:depends on/VerbDown' => '影響を受ける???', // 'Impacts...',	    # 'Impacts...'
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
	'Class:Organization' => '組織', // 'Organization',	# 'Organization'
	'Class:Organization+' => '',		# ''
	'Class:Organization/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Organization/Attribute:name+' => '共通名', // 'Common name',	# 'Common name'
	'Class:Organization/Attribute:code' => 'コード', // 'Code',	# 'Code'
	'Class:Organization/Attribute:code+' => '組織コード(Siret, DUNS, ...)', // 'Organization code (Siret, DUNS,...)',	# 'Organization code (Siret, DUNS,...)'
	'Class:Organization/Attribute:status' => 'ステータス', // 'Status',    # 'Status'
	'Class:Organization/Attribute:status+' => '',	      # ''
	'Class:Organization/Attribute:status/Value:active' => 'アクティブ', // 'Active',	# 'Active'
	'Class:Organization/Attribute:status/Value:active+' => 'アクティブ', // 'Active',  # 'Active'
	'Class:Organization/Attribute:status/Value:inactive' => '非アクティブ', // 'Inactive', # 'Inactive'
	'Class:Organization/Attribute:status/Value:inactive+' => '非アクティブ', // 'Inactive',  # 'Inactive'
	'Class:Organization/Attribute:parent_id' => '親', // 'Parent', # 'Parent'
	'Class:Organization/Attribute:parent_id+' => '親組織', // 'Parent organization',	# 'Parent organization'
	'Class:Organization/Attribute:parent_name' => '親名称', // 'Parent name',		# 'Parent name'
	'Class:Organization/Attribute:parent_name+' => '親組織の名称', // 'Name of the parent organization', # 'Name of the parent organization'
));


//
// Class: Location
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Location' => 'ロケーション', // 'Location',	# 'Location'
	'Class:Location+' => '任意のロケーションタイプ: リージョン、国、都市、サイト、ビル、フロア、部屋、ラック、...', // 'Any type of location: Region, Country, City, Site, Building, Floor, Room, Rack,...',	# 'Any type of location: Region, Country, City, Site, Building, Floor, Room, Rack,...'
	'Class:Location/Attribute:name' => '名称', // 'Name',  # 'Name'
	'Class:Location/Attribute:name+' => '',	    # ''
	'Class:Location/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:Location/Attribute:status+' => '',	# ''
	'Class:Location/Attribute:status/Value:active' => 'アクティブ', // 'Active',	# 'Active'
	'Class:Location/Attribute:status/Value:active+' => 'アクティブ', // 'Active',	# 'Active'
	'Class:Location/Attribute:status/Value:inactive' => '非アクティブ', // 'Inactive',	# 'Inactive'
	'Class:Location/Attribute:status/Value:inactive+' => '非アクティブ', // 'Inactive',  # 'Inactive'
	'Class:Location/Attribute:org_id' => 'オーナー組織', // 'Owner organization',	  # 'Owner organization'
	'Class:Location/Attribute:org_id+' => '',   			  # ''
	'Class:Location/Attribute:org_name' => 'オーナー組織名称', // 'Name of the owner organization',	# 'Name of the owner organization'
	'Class:Location/Attribute:org_name+' => '',  	    	  			# ''
	'Class:Location/Attribute:address' => 'アドレス', // 'Address',	# 'Address'
	'Class:Location/Attribute:address+' => '住所', // 'Postal address',  # 'Postal address'
	'Class:Location/Attribute:postal_code' => '郵便番号', // 'Postal code',  # 'Postal code'
	'Class:Location/Attribute:postal_code+' => '郵便番号', // 'ZIP/Postal code',	    # 'ZIP/Postal code'
	'Class:Location/Attribute:city' => '都市', // 'City', # 'City'
	'Class:Location/Attribute:city+' => '',	   # ''
	'Class:Location/Attribute:country' => '国', // 'Country',	# 'Country'
	'Class:Location/Attribute:country+' => '',		# ''
	'Class:Location/Attribute:parent_id' => '親ロケーション', // 'Parent location',	# 'Parent location'
	'Class:Location/Attribute:parent_id+' => '',			# ''
	'Class:Location/Attribute:parent_name' => '親名称???', // 'Parent name',	# 'Parent name'
	'Class:Location/Attribute:parent_name+' => '',	  		# ''
	'Class:Location/Attribute:contact_list' => 'コンタクト', // 'Contacts',	# 'Contacts'
	'Class:Location/Attribute:contact_list+' => 'このサイトにあるコンタクト', // 'Contacts located on this site',	# 'Contacts located on this site'
	'Class:Location/Attribute:infra_list' => 'インフラ', // 'Infrastructure',    	      		# 'Infrastructure'
	'Class:Location/Attribute:infra_list+' => 'このサイトにあるCI', // 'CIs located on this site',		# 'CIs located on this site'
));
//
// Class: Group
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Group' => 'グループ', // 'Group',	# 'Group'
	'Class:Group+' => '',		# ''
	'Class:Group/Attribute:name' => '名称', // 'Name',	# 'Name'
	'Class:Group/Attribute:name+' => '',	# ''
	'Class:Group/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:Group/Attribute:status+' => '',		# ''
	'Class:Group/Attribute:status/Value:implementation' => '実装???', // 'Implementation',	# 'Implementation'
	'Class:Group/Attribute:status/Value:implementation+' => '実装???', // 'Implementation',	# 'Implementation'
	'Class:Group/Attribute:status/Value:obsolete' => 'もう使われていない', // Obsolete',			# 'Obsolete'
	'Class:Group/Attribute:status/Value:obsolete+' => 'もう使われていない', // 'Obsolete',			# 'Obsolete'
	'Class:Group/Attribute:status/Value:production' => 'プロダクション', // 'Production',		# 'Production'
	'Class:Group/Attribute:status/Value:production+' => 'プロダクション', // 'Production',		# 'Production'
	'Class:Group/Attribute:org_id' => '組織', // 'Organization',   # 'Organization'
	'Class:Group/Attribute:org_id+' => '',		    # ''
	'Class:Group/Attribute:owner_name' => '名前', // 'Name',	    # 'Name'
	'Class:Group/Attribute:owner_name+' => '共通名', // 'Common name', # 'Common name'
	'Class:Group/Attribute:description' => '詳細情報', // 'Description', # 'Description'
	'Class:Group/Attribute:description+' => '',	      # ''
	'Class:Group/Attribute:type' => 'タイプ', // 'Type',	# 'Type'
	'Class:Group/Attribute:type+' => '',	# ''
	'Class:Group/Attribute:parent_id' => '親グループ', // 'Parent Group',	# 'Parent Group'
	'Class:Group/Attribute:parent_id+' => '',    # ''
	'Class:Group/Attribute:parent_name' => '名前', // 'Name', # 'Name'
	'Class:Group/Attribute:parent_name+' => '',    # ''
	'Class:Group/Attribute:ci_list' => 'リンクされたCI', // 'Linked CIs', # 'Linked CIs'
	'Class:Group/Attribute:ci_list+' => '',	   # ''
));

//
// Class: lnkGroupToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkGroupToCI' => 'グループ / CI', // 'Group / CI', # 'Group / CI'
	'Class:lnkGroupToCI+' => '',   # ''
	'Class:lnkGroupToCI/Attribute:group_id' => 'グループ', // 'Group',	# 'Group'
	'Class:lnkGroupToCI/Attribute:group_id+' => '',		# ''
	'Class:lnkGroupToCI/Attribute:group_name' => '名前', // 'Name',	# 'Name'
	'Class:lnkGroupToCI/Attribute:group_name+' => '',	# ''
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI', # 'CI'
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',  # ''
	'Class:lnkGroupToCI/Attribute:ci_name' => '名前', // 'Name',	# 'Name'
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',		# ''
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CIステータス', // 'CI Status',  # 'CI Status'
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',	  # ''
	'Class:lnkGroupToCI/Attribute:reason' => '理由', // 'Reason',	  # 'Reason'
	'Class:lnkGroupToCI/Attribute:reason+' => '',		  # ''
));


//
// Class: Contact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Contact' => 'コンタクト', //  'Contact',	# 'Contact'
	'Class:Contact+' => '',		# ''
	'Class:Contact/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Contact/Attribute:name+' => '',		# ''
	'Class:Contact/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:Contact/Attribute:status+' => '',	# ''
	'Class:Contact/Attribute:status/Value:active' => 'アクティブ', // 'Active',	# 'Active'
	'Class:Contact/Attribute:status/Value:active+' => 'アクティブ', // 'Active',	# 'Active'
	'Class:Contact/Attribute:status/Value:inactive' => '非アクティブ', // 'Inactive',	# 'Inactive'
	'Class:Contact/Attribute:status/Value:inactive+' => '非アクティブ', // 'Inactive',	# 'Inactive'
	'Class:Contact/Attribute:org_id' => '組織', // 'Organization', # 'Organization'
	'Class:Contact/Attribute:org_id+' => '',	    # ''
	'Class:Contact/Attribute:org_name' => '組織', // 'Organization', # 'Organization'
	'Class:Contact/Attribute:org_name+' => '',	      # ''
	'Class:Contact/Attribute:email' => 'Eメール', // 'Email',	      # 'Email'
	'Class:Contact/Attribute:email+' => '',		      # ''
	'Class:Contact/Attribute:phone' => '電話', // 'Phone',	      # 'Phone'
	'Class:Contact/Attribute:phone+' => '',		      # ''
	'Class:Contact/Attribute:location_id' => 'ロケーション', // 'Location',  # 'Location'
	'Class:Contact/Attribute:location_id+' => '',	      # ''
	'Class:Contact/Attribute:location_name' => 'ロケーション', // 'Location',	# 'Location'
	'Class:Contact/Attribute:location_name+' => '',		# ''
	'Class:Contact/Attribute:ci_list' => 'CIs', # 'CIs'
	'Class:Contact/Attribute:ci_list+' => 'このコンタクトに関連するCI', // 'CIs related to the contact',	# 'CIs related to the contact'
	'Class:Contact/Attribute:contract_list' => 'コンタクト', // 'Contracts',	  # 'Contracts'
	'Class:Contact/Attribute:contract_list+' => 'このコンタクトに関連するコンタクト', // 'Contracts related to the contact',	# 'Contracts related to the contact'
	'Class:Contact/Attribute:service_list' => 'サービス', // 'Services',  # 'Services'
	'Class:Contact/Attribute:service_list+' => 'このコンタクトに関連するサービス', // 'Services related to this contact',	# 'Services related to this contact'
	'Class:Contact/Attribute:ticket_list' => 'チケット', // 'Tickets',  # 'Tickets'
	'Class:Contact/Attribute:ticket_list+' => 'このコンタクトに関連するチケット', // 'Tickets related to the contact',	# 'Tickets related to the contact'
	'Class:Contact/Attribute:team_list' => 'チーム', // 'Teams',	   # 'Teams'
	'Class:Contact/Attribute:team_list+' => 'このコンタクトが所属するチーム', // 'Teams this contact belongs to',	# 'Teams this contact belongs to'
	'Class:Contact/Attribute:finalclass' => 'タイプ', // 'Type',	    # 'Type'
	'Class:Contact/Attribute:finalclass+' => '',	    # ''
));

//
// Class: Person
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Person' => 'パーソン', // 'Person',	# 'Person'
	'Class:Person+' => '',		# ''
	'Class:Person/Attribute:first_name' => '名字', // 'First Name',	# 'First Name'
	'Class:Person/Attribute:first_name+' => '',   		# ''
	'Class:Person/Attribute:employee_id' => '社員番号', // Employee ID',	# 'Employee ID'
	'Class:Person/Attribute:employee_id+' => '',	  	# ''
));

//
// Class: Team
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Team' => 'チーム', // 'Team',		# 'Team'
	'Class:Team+' => '',		# ''
	'Class:Team/Attribute:member_list' => 'メンバ', // 'Members',	# 'Members'
	'Class:Team/Attribute:member_list+' => '本チーム所属のコンタクト', // 'Contacts that are part of the team',	# 'Contacts that are part of the team'
));

//
// Class: lnkTeamToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkTeamToContact' => 'チームメンバ', // 'Team Members',	# 'Team Members'
	'Class:lnkTeamToContact+' => 'チームのメンバ', // 'Members of a team', # 'Members of a team'
	'Class:lnkTeamToContact/Attribute:team_id' => 'チーム', // 'Team',	     # 'Team'
	'Class:lnkTeamToContact/Attribute:team_id+' => '',	     # ''
	'Class:lnkTeamToContact/Attribute:contact_id' => 'メンバ', // 'Member',   # 'Member'
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',	     # ''
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'ロケーション', // 'Location',	# 'Location'
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',		# ''
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Eメール', // 'Email',		# 'Email'
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',		# ''
	'Class:lnkTeamToContact/Attribute:contact_phone' => '電話番号', // 'Phone',		# 'Phone'
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',		# ''
	'Class:lnkTeamToContact/Attribute:role' => '役割', // 'Role',   # 'Role'
	'Class:lnkTeamToContact/Attribute:role+' => '',	     # ''
));

//
// Class: Document
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Document' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:Document+' => '',	# ''
	'Class:Document/Attribute:name' => '名称', // 'Name',	# 'Name'
	'Class:Document/Attribute:name+' => '',		# ''
	'Class:Document/Attribute:org_id' => '組織', // 'Organization',	# 'Organization'
	'Class:Document/Attribute:description+' => '',		# ''
	'Class:Document/Attribute:org_name' => '組織名', // 'Organization Name',	# 'Organization Name'
	'Class:Document/Attribute:org_name+' => '',	     # ''
	'Class:Document/Attribute:description+' => '',	     # ''
	'Class:Document/Attribute:description' => '詳細情報', // 'Description',	# 'Description'
	'Class:Document/Attribute:description+' => '',			# ''
	'Class:Document/Attribute:type' => 'タイプ', // 'Type', # 'Type'
	'Class:Document/Attribute:type+' => '',	   # ''
	'Class:Document/Attribute:type/Value:contract' => 'コンタクト', // 'Contract',	# 'Contract'
	'Class:Document/Attribute:type/Value:contract+' => '',		# ''
	'Class:Document/Attribute:type/Value:networkmap' => 'ネットワークマップ', // 'Network Map',	# 'Network Map'
	'Class:Document/Attribute:type/Value:networkmap+' => '',     # ''
	'Class:Document/Attribute:type/Value:presentation' => 'プレゼンテーション', // 'Presentation',	# 'Presentation'
	'Class:Document/Attribute:type/Value:presentation+' => '',		# ''
	'Class:Document/Attribute:type/Value:training' => 'トレーニング', // 'Training',		# 'Training'
	'Class:Document/Attribute:type/Value:training+' => '',			# ''
	'Class:Document/Attribute:type/Value:whitePaper' => 'ホワイトペーパー', // 'White Paper',	# 'White Paper'
	'Class:Document/Attribute:type/Value:whitePaper+' => '',   # ''
	'Class:Document/Attribute:type/Value:workinginstructions' => '業務命令', // 'Working Instructions',	# 'Working Instructions'
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',     # ''
	'Class:Document/Attribute:status' => 'ステータス', // 'Status',		   # 'Status'
	'Class:Document/Attribute:status+' => '',		   # ''
	'Class:Document/Attribute:status/Value:draft' => 'ドラフト', // 'Draft',  # 'Draft'
	'Class:Document/Attribute:status/Value:draft+' => '',	   # ''
	'Class:Document/Attribute:status/Value:obsolete' => 'すでに使われていない', // 'Obsolete',	# 'Obsolete'
	'Class:Document/Attribute:status/Value:obsolete+' => '',	# ''
	'Class:Document/Attribute:status/Value:published' => 'パブリッシュ済み', // 'Published', # 'Published'
	'Class:Document/Attribute:status/Value:published+' => '',	  # ''
	'Class:Document/Attribute:ci_list' => 'CIs',	   # 'CIs'
	'Class:Document/Attribute:ci_list+' => '本ドキュメントを参照するCI', // 'CIs refering to this document',	# 'CIs refering to this document'
	'Class:Document/Attribute:contract_list' => 'コンタクト', // 'Contracts',     # 'Contracts'
	'Class:Document/Attribute:contract_list+' => '本ドキュメントを参照するコンタクト', // 'Contracts refering to this document',	# 'Contracts refering to this document'
	'Class:Document/Attribute:service_list' => 'サービス', //'Services',	# 'Services'
	'Class:Document/Attribute:service_list+' => 'サービス', // 'Services refering to this document',	# 'Services refering to this document'
	'Class:Document/Attribute:ticket_list' => 'チケット', // 'Tickets',  # 'Tickets'
	'Class:Document/Attribute:ticket_list+' => '本ドキュメントを参照するチケット', // 'Tickets refering to this document',	# 'Tickets refering to this document'
	'Class:Document:PreviewTab' => 'プレビュー', //  'Preview',  # 'Preview'
));

//
// Class: WebDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:WebDoc' => 'ウェブドキュメント', // 'Web Document',     # 'Web Document'
	'Class:WebDoc+' => '他のウェブサーバで参照可能なドキュメント', // 'Document available on another web server',	# 'Document available on another web server'
	'Class:WebDoc/Attribute:url' => 'URL', // 'Url', # 'Url'
	'Class:WebDoc/Attribute:url+' => '',   # ''
));

//
// Class: Note
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Note' => 'ノート', // 'Note',		# 'Note'
	'Class:Note+' => '',		# ''
	'Class:Note/Attribute:note' => 'テキスト', // 'Text',	# 'Text'
	'Class:Note/Attribute:note+' => '',	# ''
));

//
// Class: FileDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:FileDoc' => 'ドキュメント(ファイル)', // 'Document (file)', # 'Document (file)'
	'Class:FileDoc+' => '',	     	      # ''
	'Class:FileDoc/Attribute:contents' => 'コンテンツ', // 'Contents',	# 'Contents'
	'Class:FileDoc/Attribute:contents+' => '',		# ''
));

//
// Class: Licence
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Licence' => 'ライセンス', //'Licence',	# 'Licence'
	'Class:Licence+' => '',		# ''
	'Class:Licence/Attribute:provider' => 'プロバイダ', // 'Provider',	# 'Provider'
	'Class:Licence/Attribute:provider+' => '',		# ''
	'Class:Licence/Attribute:org_id' => 'オーナー', // 'Owner',		# 'Owner'
	'Class:Licence/Attribute:org_id+' => '',		# ''
	'Class:Licence/Attribute:org_name' => '名前', // 'Name',		# 'Name'
	'Class:Licence/Attribute:org_name+' => '共通名', // 'Common name',	# 'Common name'
	'Class:Licence/Attribute:product' => 'プロダクト', // 'Product',		# 'Product'
	'Class:Licence/Attribute:product+' => '',		# ''
	'Class:Licence/Attribute:name' => '名称', //  'Name',		# 'Name'
	'Class:Licence/Attribute:name+' => '',			# ''
	'Class:Licence/Attribute:start' => '開始日付', // 'Start date',	# 'Start date'
	'Class:Licence/Attribute:start+' => '',	  		# ''
	'Class:Licence/Attribute:end' => '終了日付', // 'End date',	# 'End date'
	'Class:Licence/Attribute:end+' => '', 		# ''
	'Class:Licence/Attribute:licence_key' => 'キー', // 'Key',	# 'Key'
	'Class:Licence/Attribute:licence_key+' => '',	# ''
	'Class:Licence/Attribute:scope' => 'スコープ', // 'Scope',	# 'Scope'
	'Class:Licence/Attribute:scope+' => '',		# ''
	'Class:Licence/Attribute:usage_limit' => '利用上限', // 'Usage limit',	# 'Usage limit'
	'Class:Licence/Attribute:usage_limit+' => '',		# ''
	'Class:Licence/Attribute:usage_list' => '利用方法', // 'Usage',  	# 'Usage'
	'Class:Licence/Attribute:usage_list+' => '本ライセンスを利用するアプリケーションインスタンス', // 'Application instances using this licence',	# 'Application instances using this licence'
));


//
// Class: Subnet
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Subnet' => 'サブネット', // 'Subnet',	# 'Subnet'
	'Class:Subnet+' => '',		# ''
	//'Class:Subnet/Attribute:name' => '名前', // 'Name',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'オーナー組織', // 'Owner organization',	# 'Owner organization'
	'Class:Subnet/Attribute:org_id+' => '',	  # ''
	'Class:Subnet/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Subnet/Attribute:description+' => '',		# ''
	'Class:Subnet/Attribute:ip' => 'IP',  # 'IP'
	'Class:Subnet/Attribute:ip+' => '',   # ''
	'Class:Subnet/Attribute:ip_mask' => 'IPマスク', // 'IP Mask',	# 'IP Mask'
	'Class:Subnet/Attribute:ip_mask+' => '',	# ''
));

//
// Class: Patch
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Patch' => 'パッチ', //'Patch',	# 'Patch'
	'Class:Patch+' => '',		# ''
	'Class:Patch/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Patch/Attribute:name+' => '',	# ''
	'Class:Patch/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Patch/Attribute:description+' => '',		# ''
	'Class:Patch/Attribute:target_sw' => 'アプリケーションスコープ', // 'Application scope', # 'Application scope'
	'Class:Patch/Attribute:target_sw+' => '対象ソフトウェア(OS or アプリケーション)', // 'Target software (OS or application)',	# 'Target software (OS or application)'
	'Class:Patch/Attribute:version' => 'バージョン', // 'Version', # 'Version'
	'Class:Patch/Attribute:version+' => '',	      # ''
	'Class:Patch/Attribute:type' => 'タイプ', // 'Type',	      # 'Type'
	'Class:Patch/Attribute:type+' => '',	      # ''
	'Class:Patch/Attribute:type/Value:application' => 'アプリケーション', // 'Application',	# 'Application'
	'Class:Patch/Attribute:type/Value:application+' => '',			# ''
	'Class:Patch/Attribute:type/Value:os' => 'OS',	# 'OS'
	'Class:Patch/Attribute:type/Value:os+' => '',	# ''
	'Class:Patch/Attribute:type/Value:security' => 'セキュリティ', // 'Security',	# 'Security'
	'Class:Patch/Attribute:type/Value:security+' => '',		# ''
	'Class:Patch/Attribute:type/Value:servicepack' => 'サービスパック', // 'Service Pack', # 'Service Pack'
	'Class:Patch/Attribute:type/Value:servicepack+' => '',	   # ''
	'Class:Patch/Attribute:ci_list' => 'デバイス', // 'Devices',	# 'Devices'
	'Class:Patch/Attribute:ci_list+' => '本パッチがインストールされているデバイス', // 'Devices where the patch is installed',	# 'Devices where the patch is installed'
));

//
// Class: Software
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Software' => 'ソフトウェア', // 'Software',	# 'Software'
	'Class:Software+' => '',	# ''
	'Class:Software/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Software/Attribute:name+' => '',		# ''
	'Class:Software/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Software/Attribute:description+' => '',			# ''
	'Class:Software/Attribute:instance_list' => 'インストール', // 'Installations',	# 'Installations'
	'Class:Software/Attribute:instance_list+' => '本ソフトウェアのインスタンス', // 'Instances of this software',	# 'Instances of this software'
	'Class:Software/Attribute:finalclass' => 'タイプ', // 'Type',	# 'Type'
	'Class:Software/Attribute:finalclass+' => '',		# ''
));

//
// Class: Application
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Application' => 'アプリケーション', // 'Application', # 'Application'
	'Class:Application+' => '',	      # ''
	'Class:Application/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Application/Attribute:name+' => '',	# ''
	'Class:Application/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Application/Attribute:description+' => '',		# ''
	'Class:Application/Attribute:instance_list' => 'インストール', // 'Installations',	# 'Installations'
	'Class:Application/Attribute:instance_list+' => '本アプリケーションのインスタンス', // 'Instances of this application',	# 'Instances of this application'
));

//
// Class: DBServer
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DBServer' => 'データベース', // 'Database',	# 'Database'
	'Class:DBServer+' => 'データベースサーバソフトウェア', // 'Database server SW',	# 'Database server SW'
	'Class:DBServer/Attribute:instance_list' => 'インストール', // 'Installations',	   # 'Installations'
	'Class:DBServer/Attribute:instance_list+' => '本データベースサーバのインスタンス', // 'Instances of this database server',	# 'Instances of this database server'
));

//
// Class: lnkPatchToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkPatchToCI' => 'パッチ方法', // 'Patch Usage',	# 'Patch Usage'
	'Class:lnkPatchToCI+' => '',   # ''
	'Class:lnkPatchToCI/Attribute:patch_id' => 'パッチ', // 'Patch',	# 'Patch'
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',		# ''
	'Class:lnkPatchToCI/Attribute:patch_name' => 'パッチ', // 'Patch',	# 'Patch'
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',	# ''
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI', # 'CI'
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',  # ''
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',	# 'CI'
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',	# ''
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CIステータス', // 'CI Status',	# 'CI Status'
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',		# ''
));

//
// Class: FunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:FunctionalCI' => '機能的CI？？？', // 'Functional CI',	# 'Functional CI'
	'Class:FunctionalCI+' => '',	    # ''
	'Class:FunctionalCI/Attribute:name' => '名称', // 'Name',	# 'Name'
	'Class:FunctionalCI/Attribute:name+' => '',	# ''
	'Class:FunctionalCI/Attribute:status' => 'ステータス', // 'Status',	# 'Status'
	'Class:FunctionalCI/Attribute:status+' => '',		# ''
	'Class:FunctionalCI/Attribute:status/Value:implementation' => '実装', // 'Implementation',	# 'Implementation'
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',		# ''
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'すでに使われていない', // 'Obsolete',		# 'Obsolete'
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',			# ''
	'Class:FunctionalCI/Attribute:status/Value:production' => 'プロダクション', // 'Production',		# 'Production'
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',			# ''
	'Class:FunctionalCI/Attribute:org_id' => 'オーナー組織', // 'Owner organization',			# 'Owner organization'
	'Class:FunctionalCI/Attribute:org_id+' => '',					# ''
	'Class:FunctionalCI/Attribute:owner_name' => 'オーナー組織', // 'Owner organization',	# 'Owner organization'
	'Class:FunctionalCI/Attribute:owner_name+' => '',   			# ''
	'Class:FunctionalCI/Attribute:importance' => 'Business criticality',	# 'Business criticity'
	'Class:FunctionalCI/Attribute:importance+' => '',      			# ''
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'High',	# 'High'
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',	# ''
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Low',	# 'Low'
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',	# ''
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Medium',	# 'Medium'
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',		# ''
	'Class:FunctionalCI/Attribute:contact_list' => 'コンタクト', // 'Contacts', 		# 'Contacts'
	'Class:FunctionalCI/Attribute:contact_list+' => 'このCIへのコンタクト', // 'Contacts for this CI',	# 'Contacts for this CI'
	'Class:FunctionalCI/Attribute:document_list' => 'ドキュメント', // 'Documents',  	   	# 'Documents'
	'Class:FunctionalCI/Attribute:document_list+' => 'このCIに関するドキュメンテーション', // 'Documentation for this CI',	# 'Documentation for this CI'
	'Class:FunctionalCI/Attribute:solution_list' => 'アプリケーションソリューション', // 'Application solutions', 	# 'Application solutions'
	'Class:FunctionalCI/Attribute:solution_list+' => '本CIを用いたアプリケーションソリューション', // 'Application solutions using this CI',	# 'Application solutions using this CI'
	'Class:FunctionalCI/Attribute:contract_list' => 'コンタクト', // 'Contracts',  		      	   	# 'Contracts'
	'Class:FunctionalCI/Attribute:contract_list+' => '本CIをサポートするコンタクト', // 'Contracts supporting this CI',	# 'Contracts supporting this CI'
	'Class:FunctionalCI/Attribute:ticket_list' => 'チケット', // 'Tickets',    	       	    		# 'Tickets'
	'Class:FunctionalCI/Attribute:ticket_list+' => 'このCIに関連するチケット', // 'Tickets related to the CI',	# 'Tickets related to the CI'
	'Class:FunctionalCI/Attribute:finalclass' => 'タイプ', // 'Type',	# 'Type'
	'Class:FunctionalCI/Attribute:finalclass+' => '',	# ''
));

//
// Class: SoftwareInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SoftwareInstance' => 'ソフトウェアインスタンス', // 'Software Instance',	# 'Software Instance'
	'Class:SoftwareInstance+' => '',      # ''
	'Class:SoftwareInstance/Attribute:device_id' => 'デバイス', // 'Device',	# 'Device'
	'Class:SoftwareInstance/Attribute:device_id+' => '',		# ''
	'Class:SoftwareInstance/Attribute:device_name' => 'デバイス', // 'Device',	# 'Device'
	'Class:SoftwareInstance/Attribute:device_name+' => '',		# ''
	'Class:SoftwareInstance/Attribute:licence_id' => 'ライセンス', // 'Licence',	# 'Licence'
	'Class:SoftwareInstance/Attribute:licence_id+' => '',		# ''
	'Class:SoftwareInstance/Attribute:licence_name' => 'ライセンス', // 'Licence',	# 'Licence'
	'Class:SoftwareInstance/Attribute:licence_name+' => '',		# ''
	'Class:SoftwareInstance/Attribute:software_name' => 'ソフトウェア', // 'Software',	# 'Software'
	'Class:SoftwareInstance/Attribute:software_name+' => '',	# ''
	'Class:SoftwareInstance/Attribute:version' => 'バージョン', // 'Version',	# 'Version'
	'Class:SoftwareInstance/Attribute:version+' => '',		# ''
	'Class:SoftwareInstance/Attribute:description' => '詳細記述', // 'Description',  # 'Description'
	'Class:SoftwareInstance/Attribute:description+' => '',		  # ''
));

//
// Class: ApplicationInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApplicationInstance' => 'アプリケーションインスタンス', // 'Application Instance',	# 'Application Instance'
	'Class:ApplicationInstance+' => '',	    # ''
	'Class:ApplicationInstance/Attribute:software_id' => 'ソフトウェア', // 'Software',	# 'Software'
	'Class:ApplicationInstance/Attribute:software_id+' => '',		# ''
	'Class:ApplicationInstance/Attribute:software_name' => '名前', // 'Name',		# 'Name'
	'Class:ApplicationInstance/Attribute:software_name+' => '',		# ''
));


//
// Class: DBServerInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DBServerInstance' => 'DBサーバインスタンス', // 'DB Server Instance',	# 'DB Server Instance'
	'Class:DBServerInstance+' => '',       # ''
	'Class:DBServerInstance/Attribute:software_id' => 'ソフトウェア', // 'Software',	# 'Software'
	'Class:DBServerInstance/Attribute:software_id+' => '',		# ''
	'Class:DBServerInstance/Attribute:software_name' => 'ソフトウェア名', // 'Software Name',	# 'Software Name'
	'Class:DBServerInstance/Attribute:software_name+' => '',      # ''
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'データベース', // 'Databases',	# 'Databases'
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'データベースソース', // 'Database sources',	# 'Database sources'
));


//
// Class: DatabaseInstance
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:DatabaseInstance' => 'データベースインスタンス', // 'Database Instance',	# 'Database Instance'
	'Class:DatabaseInstance+' => '',      # ''
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'データベースサーバ', // 'Database server',	# 'Database server'
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',      # ''
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'データベースバージョン', // 'Database version',	# 'Database version'
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',	   # ''
	'Class:DatabaseInstance/Attribute:description' => '詳細記述', // 'Description',  # 'Description'
	'Class:DatabaseInstance/Attribute:description+' => '',		  # ''
));

//
// Class: ApplicationSolution
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ApplicationSolution' => 'アプリケーションソリューション', // 'Application Solution',	# 'Application Solution'
	'Class:ApplicationSolution+' => '',	    # ''
	'Class:ApplicationSolution/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:ApplicationSolution/Attribute:description+' => '',		# ''
	'Class:ApplicationSolution/Attribute:ci_list' => 'CIs',			# 'CIs'
	'Class:ApplicationSolution/Attribute:ci_list+' => 'このソリューションを構成するCI', // 'CIs composing the solution',	# 'CIs composing the solution'
	'Class:ApplicationSolution/Attribute:process_list' => 'ビジネスプロセス', // 'Business processes',	# 'Business processes'
	'Class:ApplicationSolution/Attribute:process_list+' => 'このソリューションに依存するビジネスプロセス', // 'Business processes relying on the solution',	# 'Business processes relying on the solution'
));

//
// Class: BusinessProcess
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:BusinessProcess' => 'ビジネスプロセス', // 'Business Process',	# 'Business Process'
	'Class:BusinessProcess+' => '',	     # ''
	'Class:BusinessProcess/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:BusinessProcess/Attribute:description+' => '',		# ''
	'Class:BusinessProcess/Attribute:used_solution_list' => 'アプリケーションソリューション', // 'Application	solutions',	# 'Application	solutions'
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'このプロセスが依存するアプリケーションソリューション', // 'Application solutions the process is relying on',	# 'Application solutions the process is relying on'
));

//
// Class: ConnectableCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ConnectableCI' => '接続可能なCI', // 'Connectable CI',	# 'Connectable CI'
	'Class:ConnectableCI+' => 'フィジカルCI', // 'Physical CI',	# 'Physical CI'
	'Class:ConnectableCI/Attribute:brand' => 'ブランド', // 'Brand', # 'Brand'
	'Class:ConnectableCI/Attribute:brand+' => '',	  # ''
	'Class:ConnectableCI/Attribute:model' => 'モデル', // 'Model', # 'Model'
	'Class:ConnectableCI/Attribute:model+' => '',	  # ''
	'Class:ConnectableCI/Attribute:serial_number' => 'シリアル番号', // 'Serial  Number',	# 'Serial  Number'
	'Class:ConnectableCI/Attribute:serial_number+' => '',	  # ''
	'Class:ConnectableCI/Attribute:asset_ref' => 'アセットリファレンス', // 'Asset Reference',	# 'Asset Reference'
	'Class:ConnectableCI/Attribute:asset_ref+' => '',   # ''
));

//
// Class: NetworkInterface
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NetworkInterface' => 'ネットワークインタフェース', // 'Network Interface',	# 'Network Interface'
	'Class:NetworkInterface+' => '',     # ''
	'Class:NetworkInterface/Attribute:device_id' => 'デバイス', // 'Device',	# 'Device'
	'Class:NetworkInterface/Attribute:device_id+' => '',		# ''
	'Class:NetworkInterface/Attribute:device_name' => 'デバイス', // 'Device',	# 'Device'
	'Class:NetworkInterface/Attribute:device_name+' => '',		# ''
	'Class:NetworkInterface/Attribute:logical_type' => '論理タイプ', // 'Logical Type',	# 'Logical Type'
	'Class:NetworkInterface/Attribute:logical_type+' => '',	    # ''
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'バックアップ', // 'Backup',	# 'Backup'
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',		# ''
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => '論理', // 'Logical',	# 'Logical'
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',		# ''
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'ポート', // 'Port',		# 'Port'
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',		# ''
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'プライマリ', // 'Primary',	# 'Primary'
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',		# ''
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'セカンダリ', // 'Secondary',	# 'Secondary'
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',		# ''
	'Class:NetworkInterface/Attribute:physical_type' => '物理タイプ', // 'Physical Type',		# 'Physical Type'
	'Class:NetworkInterface/Attribute:physical_type+' => '',      # ''
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',	# 'ATM'
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',	# ''
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'イーサネット', // 'Ethernet',	# 'Ethernet'
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',		# ''
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'フレームリレー', // 'Frame Relay',	# 'Frame Relay'
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',   # ''
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',	    # 'VLAN'
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',	    # ''
	'Class:NetworkInterface/Attribute:ip_address' => 'IPアドレス', // 'IP Address',	# 'IP Address'
	'Class:NetworkInterface/Attribute:ip_address+' => '',		# ''
	'Class:NetworkInterface/Attribute:ip_mask' => 'IPマスク', // 'IP Mask',	# 'IP Mask'
	'Class:NetworkInterface/Attribute:ip_mask+' => '',		# ''
	'Class:NetworkInterface/Attribute:mac_address' => 'MACアドレス', // 'MAC Address',  # 'MAC Address'
	'Class:NetworkInterface/Attribute:mac_address+' => '', # ''
	'Class:NetworkInterface/Attribute:speed' => '速度', // 'Speed',   # 'Speed'
	'Class:NetworkInterface/Attribute:speed+' => '',       # ''
	'Class:NetworkInterface/Attribute:duplex' => '多重', // 'Duplex', # 'Duplex'
	'Class:NetworkInterface/Attribute:duplex+' => '',      # ''
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => '自動', // 'Auto',	# 'Auto'
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => '自動', // 'Auto',  # 'Auto'
	'Class:NetworkInterface/Attribute:duplex/Value:full' => '全', // 'Full',	  # 'Full'
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',	  # ''
	'Class:NetworkInterface/Attribute:duplex/Value:half' => '半', // 'Half',	  # 'Half'
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',	  # ''
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => '不明', // 'Unknown',	# 'Unknown'
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',		# ''
	'Class:NetworkInterface/Attribute:connected_if' => '接続済み', // 'Connected to',		# 'Connected to'
	'Class:NetworkInterface/Attribute:connected_if+' => '接続済みインタフェース', // 'Connected interface',	# 'Connected interface'
	'Class:NetworkInterface/Attribute:connected_name' => '接続済み', // 'Connected to',		# 'Connected to'
	'Class:NetworkInterface/Attribute:connected_name+' => '',			# ''
	'Class:NetworkInterface/Attribute:connected_if_device_id' => '接続先デバイス', // 'Connected device', # 'Connected device'
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',		 # ''
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'デバイス', // 'Device',	# 'Device'
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',		# ''
	'Class:NetworkInterface/Attribute:link_type' => 'リンクタイプ', // 'Link type',	# 'Link type'
	'Class:NetworkInterface/Attribute:link_type+' => '',  		# ''
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'ダウンリンク', // 'Down link',	# 'Down link'
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',  		# ''
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'アップリンク', // 'Up link',	# 'Up link'
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',	# ''
));



//
// Class: Device
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Device' => 'デバイス', // 'Device',	# 'Device'
	'Class:Device+' => '',		# ''
	'Class:Device/Attribute:nwinterface_list' => 'ネットワークインタフェース', // 'Network interfaces',	# 'Network interfaces'
	'Class:Device/Attribute:nwinterface_list+' => '',     # ''
));

//
// Class: PC
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:PC' => 'PC',   # 'PC'
	'Class:PC+' => '',    # ''
	'Class:PC/Attribute:cpu' => 'CPU',	# 'CPU'
	'Class:PC/Attribute:cpu+' => '',	# ''
	'Class:PC/Attribute:ram' => 'RAM',	# 'RAM'
	'Class:PC/Attribute:ram+' => '',	# ''
	'Class:PC/Attribute:hdd' => 'ハードディスク', // 'Hard disk',  # 'Hard disk'
	'Class:PC/Attribute:hdd+' => '',  # ''
	'Class:PC/Attribute:os_family' => 'OSファミリ', // 'OS Family',	# 'OS Family'
	'Class:PC/Attribute:os_family+' => '',		# ''
	'Class:PC/Attribute:os_version' => 'OSバージョン', // 'OS Version',  # 'OS Version'
	'Class:PC/Attribute:os_version+' => '',		  # ''
	'Class:PC/Attribute:application_list' => 'アプリケーション', // 'Applications',	# 'Applications'
	'Class:PC/Attribute:application_list+' => '本PCにインストール済みアプリケーション', // 'Applications installed on this PC',	# 'Applications installed on this PC'
	'Class:PC/Attribute:patch_list' => 'パッチ', // 'Patches',		# 'Patches'
	'Class:PC/Attribute:patch_list+' => '本PCにインストール済みのパッチ', // 'Patches installed on this PC',	# 'Patches installed on this PC'
));

//
// Class: MobileCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:MobileCI' => 'モバイルCI', // 'Mobile CI',      # 'Mobile CI'
	'Class:MobileCI+' => '',    # ''
));

//
// Class: MobilePhone
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:MobilePhone' => 'ケータイ', // 'Mobile Phone',	# 'Mobile Phone'
	'Class:MobilePhone+' => '',    # ''
	'Class:MobilePhone/Attribute:number' => 'ケータイ番号', // 'Phone number',	# 'Phone number'
	'Class:MobilePhone/Attribute:number+' => '',   # ''
	'Class:MobilePhone/Attribute:imei' => 'IMEI',  # 'IMEI'
	'Class:MobilePhone/Attribute:imei+' => '',     # ''
	'Class:MobilePhone/Attribute:hw_pin' => 'ハードウェアPIN', // 'Hardware PIN',	# 'Hardware PIN'
	'Class:MobilePhone/Attribute:hw_pin+' => '',	  # ''
));

//
// Class: InfrastructureCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:InfrastructureCI' => 'インフラCI', // 'Infrastructure CI',	# 'Infrastructure CI'
	'Class:InfrastructureCI+' => '',	    # ''
	'Class:InfrastructureCI/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:InfrastructureCI/Attribute:description+' => '',			# ''
	'Class:InfrastructureCI/Attribute:location_id' => 'ロケーション', // 'Location',		# 'Location'
	'Class:InfrastructureCI/Attribute:location_id+' => '',			# ''
	'Class:InfrastructureCI/Attribute:location_name' => 'ロケーション', // 'Location',		# 'Location'
	'Class:InfrastructureCI/Attribute:location_name+' => '',		# ''
	'Class:InfrastructureCI/Attribute:location_details' => 'ロケーション詳細', // 'Location details',	# 'Location details'
	'Class:InfrastructureCI/Attribute:location_details+' => '',	 # ''
	'Class:InfrastructureCI/Attribute:management_ip' => '管理IP', // 'Management IP',	# 'Management IP'
	'Class:InfrastructureCI/Attribute:management_ip+' => '',	# ''
	'Class:InfrastructureCI/Attribute:default_gateway' => 'デフォルトゲートウェイ', // 'Default Gateway',	# 'Default Gateway'
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',     # ''
));

//
// Class: NetworkDevice
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:NetworkDevice' => 'ネットワークデバイス', // 'Network Device',	# 'Network Device'
	'Class:NetworkDevice+' => '',	  # ''
	'Class:NetworkDevice/Attribute:type' => 'タイプ', // 'Type',	# 'Type'
	'Class:NetworkDevice/Attribute:type+' => '',	# ''
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WANアクセラレータ', // 'WAN Accelerator',	# 'WAN Accelerator'
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '', 		# ''
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'ファイアウォール', // 'Firewall',  # 'Firewall'
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',	    # ''
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'ハブ', // 'Hub',	    # 'Hub'
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',		    # ''
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'ロードバランサ', // 'Load Balancer',	# 'Load Balancer'
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',			# ''
	'Class:NetworkDevice/Attribute:type/Value:router' => 'ルータ', // 'Router',	# 'Router'
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',	# ''
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'スイッチ', // 'Switch',	# 'Switch'
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',	# ''
	'Class:NetworkDevice/Attribute:ios_version' => 'IOSバージョン', // 'IOS Version',	# 'IOS Version'
	'Class:NetworkDevice/Attribute:ios_version+' => '', 		# ''
	'Class:NetworkDevice/Attribute:ram' => 'RAM',			# 'RAM'
	'Class:NetworkDevice/Attribute:ram+' => '',			# ''
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP Read',	# 'SNMP Read'
	'Class:NetworkDevice/Attribute:snmp_read+' => '',  		# ''
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP Write',	# 'SNMP Write'
	'Class:NetworkDevice/Attribute:snmp_write+' => '',  		# ''
));

//
// Class: Server
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Server' => 'サーバ', //  'Server',	# 'Server'
	'Class:Server+' => '',		# ''
	'Class:Server/Attribute:cpu' => 'CPU',	# 'CPU'
	'Class:Server/Attribute:cpu+' => '',	# ''
	'Class:Server/Attribute:ram' => 'RAM',	# 'RAM'
	'Class:Server/Attribute:ram+' => '',	# ''
	'Class:Server/Attribute:hdd' => 'ハードディスク', // 'Hard Disk',	# 'Hard Disk'
	'Class:Server/Attribute:hdd+' => '',  		# ''
	'Class:Server/Attribute:os_family' => 'OSファミリ', // 'OS Family',	# 'OS Family'
	'Class:Server/Attribute:os_family+' => '',		# ''
	'Class:Server/Attribute:os_version' => 'OSバージョン', // 'OS Version',	# 'OS Version'
	'Class:Server/Attribute:os_version+' => '',		# ''
	'Class:Server/Attribute:application_list' => 'アプリケーション', // 'Applications',	# 'Applications'
	'Class:Server/Attribute:application_list+' => '本サーバにインストール済みのアプリケーション', // 'Applications installed on this server',	# 'Applications installed on this server'
	'Class:Server/Attribute:patch_list' => 'パッチ', // 'Patches',	    	      	      		# 'Patches'
	'Class:Server/Attribute:patch_list+' => '本サーバにインストール済みのパッチ', // 'Patches installed on this server',		# 'Patches installed on this server'
));

//
// Class: Printer
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Printer' => 'プリンタ', // 'Printer',	# 'Printer'
	'Class:Printer+' => '',		# ''
	'Class:Printer/Attribute:type' => 'タイプ', // 'Type',	# 'Type'
	'Class:Printer/Attribute:type+' => '',		# ''
	'Class:Printer/Attribute:type/Value:mopier' => 'MFP', // 'Mopier',	# 'Mopier'
	'Class:Printer/Attribute:type/Value:mopier+' => '',		# ''
	'Class:Printer/Attribute:type/Value:printer' => 'プリンタ', // 'Printer',	# 'Printer'
	'Class:Printer/Attribute:type/Value:printer+' => '',		# ''
	'Class:Printer/Attribute:technology' => 'テクノロジ', // 'Technology',		# 'Technology'
	'Class:Printer/Attribute:technology+' => '',			# ''
	'Class:Printer/Attribute:technology/Value:inkjet' => 'インクジェット', // 'Inkjet',	# 'Inkjet'
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',	# ''
	'Class:Printer/Attribute:technology/Value:laser' => 'レーザー', // 'Laser',	# 'Laser'
	'Class:Printer/Attribute:technology/Value:laser+' => '',	# ''
	'Class:Printer/Attribute:technology/Value:tracer' => 'トレーサー', // 'Tracer',	# 'Tracer'
	'Class:Printer/Attribute:technology/Value:tracer+' => '',	# ''
));

//
// Class: lnkCIToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCIToDoc' => 'ドキュメント/CI', // 'Doc/CI',	# 'Doc/CI'
	'Class:lnkCIToDoc+' => '',	# ''
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',	# 'CI'
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',	# ''
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',	# 'CI'
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',	# ''
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CIステータス', // 'CI Status',	# 'CI Status'
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',		# ''
	'Class:lnkCIToDoc/Attribute:document_id' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkCIToDoc/Attribute:document_id+' => '',	# ''
	'Class:lnkCIToDoc/Attribute:document_name' => 'ドキュメント', // 'Document', # 'Document'
	'Class:lnkCIToDoc/Attribute:document_name+' => '',	  # ''
	'Class:lnkCIToDoc/Attribute:document_type' => 'ドキュメントタイプ', // 'Document Type',	# 'Document Type'
	'Class:lnkCIToDoc/Attribute:document_type+' => '',		# ''
	'Class:lnkCIToDoc/Attribute:document_status' => 'ドキュメントステータス', // 'Document Status',	# 'Document Status'
	'Class:lnkCIToDoc/Attribute:document_status+' => '',	  		# ''
));

//
// Class: lnkCIToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCIToContact' => 'CI/コンタクト', // 'CI/Contact',	# 'CI/Contact'
	'Class:lnkCIToContact+' => '',		# ''
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',	# 'CI'
	'Class:lnkCIToContact/Attribute:ci_id+' => '',	# ''
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI', # 'CI'
	'Class:lnkCIToContact/Attribute:ci_name+' => '',  # ''
	'Class:lnkCIToContact/Attribute:ci_status' => 'CIステータス', // 'CI Status',	# 'CI Status'
	'Class:lnkCIToContact/Attribute:ci_status+' => '',		# ''
	'Class:lnkCIToContact/Attribute:contact_id' => 'コンタクト', // 'Contact',	# 'Contact'
	'Class:lnkCIToContact/Attribute:contact_id+' => '',		# ''
	'Class:lnkCIToContact/Attribute:contact_name' => 'コンタクト', // 'Contact',	# 'Contact'
	'Class:lnkCIToContact/Attribute:contact_name+' => '',		# ''
	'Class:lnkCIToContact/Attribute:contact_email' => 'コンタクトEメール', // 'Contact Email',	# 'Contact Email'
	'Class:lnkCIToContact/Attribute:contact_email+' => '',	   # ''
	'Class:lnkCIToContact/Attribute:role' => '役割', // 'Role',   # 'Role'
	'Class:lnkCIToContact/Attribute:role+' => 'このCIに言及するコンタクトの役割', // 'Role of the contact regarding the CI',	# 'Role of the contact regarding the CI'
));

//
// Class: lnkSolutionToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkSolutionToCI' => 'CI/ソリューション', // 'CI/Solution',	# 'CI/Solution'
	'Class:lnkSolutionToCI+' => '',			# ''
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'アプリケーションソリューション', // 'Application solution',	# 'Application solution'
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',	      # ''
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'アプリケーションソリューション', // 'Application solution',	# 'Application solution'
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',		# ''
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI', # 'CI'
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',	 # ''
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI', # 'CI'
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',  # ''
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CIステータス', // 'CI Status',	# 'CI Status'
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',		# ''
	'Class:lnkSolutionToCI/Attribute:utility' => 'ユーティリティ', // 'Utility',		# 'Utility'
	'Class:lnkSolutionToCI/Attribute:utility+' => 'ソリューション中のCIユーティリティ', // 'Utility of the CI in the solution',	# 'Utility of the CI in the solution'
));

//
// Class: lnkProcessToSolution
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkProcessToSolution' => 'ビジネスプロセス/ソリューション', // 'Business process/Solution',	# 'Business process/Solution'
	'Class:lnkProcessToSolution+' => '',	  # ''
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'アプリケーションソリューション', // 'Application solution',	# 'Application solution'
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',	   # ''
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'アプリケーションソリューション', // 'Application solution',	# 'Application solution'
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',	     # ''
	'Class:lnkProcessToSolution/Attribute:process_id' => 'プロセス', // 'Process',	     # 'Process'
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',	     # ''
	'Class:lnkProcessToSolution/Attribute:process_name' => 'プロセス', // 'Process',    # 'Process'
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',	     # ''
	'Class:lnkProcessToSolution/Attribute:reason' => '理由', // 'Reason',	     # 'Reason'
	'Class:lnkProcessToSolution/Attribute:reason+' => 'プロセスとソリューション間のリンクに関する詳細情報', // 'More information on the link between the process and the solution', # 'More information on the link between the process and the solution'
));



//
// Class extensions
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
'Class:Subnet/Tab:IPUsage' => 'IPの用途', // 'IP Usage',     # 'IP Usage'
'Class:Subnet/Tab:IPUsage-explain' => '<em>%1$s</em>から<em>%2$s</em>の範囲のIPアドレスを保持するインタフェース', // 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>',	# 'Interfaces having an IP in the range: <em>%1$s</em> to <em>%2$s</em>'
'Class:Subnet/Tab:FreeIPs' => '未割り当てIP', // 'Free IPs',	  # 'Free IPs'
'Class:Subnet/Tab:FreeIPs-count' => '未割り当てIPアドレス：%1$s', // 'Free IPs: %1$s',	  # 'Free IPs: %1$s'
'Class:Subnet/Tab:FreeIPs-explain' => 'これが未割り当てIPアドレスから10個を抽出したものです。', // 'Here is an extract of 10 free IP addresses',	# 'Here is an extract of 10 free IP addresses'
));

//
// Application Menu
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	      'Menu:Catalogs' => 'カタログ', // 'Catalogs',		# 'Catalogs'
	      'Menu:Catalogs+' => 'データタイプ', // 'Data types',	# 'Data types'
	      'Menu:Audit' => '監査', // 'Audit',  # 'Audit'
	      'Menu:Audit+' => '監査', // 'Audit', # 'Audit'
	      'Menu:Organization' => '組織', // 'Organizations',	# 'Organizations'
	      'Menu:Organization+' => '全組織', // 'All Organizations',	# 'All Organizations'
	      'Menu:Application' => 'アプリケーション', // 'Applications',		# 'Applications'
	      'Menu:Application+' => '全アプリケーション', // 'All Applications',	# 'All Applications'
	      'Menu:DBServer' => 'データベースサーバ', // 'Database Servers',		# 'Database Servers'
	      'Menu:DBServer+' => 'データベースサーバ', // 'Database Servers',		# 'Database Servers'
	      'Menu:Audit' => '監査', // 'Audit',      # 'Audit'
	      'Menu:ConfigManagement' => '設定管理', // 'Configuration Management',	# 'Configuration Management'
	      'Menu:ConfigManagement+' => '設定管理', // 'Configuration Management',	# 'Configuration Management'
	      'Menu:ConfigManagementOverview' => '概要',		# 'Overview'
	      'Menu:ConfigManagementOverview+' => '概要',		# 'Overview'
	      'Menu:Contact' => 'コンタクト', // 'Contacts',	 # 'Contacts'
	      'Menu:Contact+' => 'コンタクト', // 'Contacts',	 # 'Contacts'
	      'Menu:Person' => 'パーソン', // 'Persons',	 # 'Persons'
	      'Menu:Person+' => '全パーソン', // 'All Persons', # 'All Persons'
	      'Menu:Team' => 'チーム', // 'Teams',		 # 'Teams'
	      'Menu:Team+' => '全チーム', // 'All Teams',	 # 'All Teams'
	      'Menu:Document' => 'ドキュメント', // 'Documents',	 # 'Documents'
	      'Menu:Document+' => '全ドキュメント', // 'All Documents',	# 'All Documents'
	      'Menu:Location' => 'ロケーション', // 'Locations',		# 'Locations'
	      'Menu:Location+' => '全ロケーション', // 'All Locations',	# 'All Locations'
	      'Menu:ConfigManagementCI' => '設定項目', // 'Configuration Items',	# 'Configuration Items'
	      'Menu:ConfigManagementCI+' => '設定項目', // 'Configuration Items',	# 'Configuration Items'
	      'Menu:BusinessProcess' => 'ビジネスプロセス', // 'Business Processes',		# 'Business Processes'
	      'Menu:BusinessProcess+' => '全ビジネスプロセス', // 'All Business Processes',	# 'All Business Processes'
	      'Menu:ApplicationSolution' => 'アプリケーションソリューション', // 'Application Solutions',	# 'Application Solutions'
	      'Menu:ApplicationSolution+' => '全アプリケーションソリューション', // 'All Application Solutions',	       # 'All Application Solutions'
	      'Menu:ConfigManagementSoftware' => 'アプリケーション管理', // 'Application Management',	       # 'Application Management'
	      'Menu:Licence' => 'ライセンス', // 'Licences',	# 'Licences'
	      'Menu:Licence+' => '全ライセンス', // 'All Licences',	# 'All Licences'
	      'Menu:Patch' => 'パッチ', // 'Patches',		# 'Patches'
	      'Menu:Patch+' => '全パッチ', // 'All Patches',		# 'All Patches'
	      'Menu:ApplicationInstance' => 'インストール済みソフトウェア', // 'Installed Software',	# 'Installed Software'
	      'Menu:ApplicationInstance+' => 'アプリケーションとデータベースサーバ', // 'Applications and Database servers',  # 'Applications and Database servers'
	      'Menu:ConfigManagementHardware' => 'インフラストラクチャ管理', // 'Infrastructure Management',	     # 'Infrastructure Management'
	      'Menu:Subnet' => 'サブネット', // 'Subnets',	# 'Subnets'
	      'Menu:Subnet+' => '全サブネット', // 'All Subnets',  # 'All Subnets'
	      'Menu:NetworkDevice' => 'ネットワークデバイス', // 'Network Devices',	# 'Network Devices'
	      'Menu:NetworkDevice+' => '全ネットワークデバイス', // 'All Network Devices',	# 'All Network Devices'
	      'Menu:Server' => 'サーバ', // 'Servers',   # 'Servers'
	      'Menu:Server+' => '全サーバ', // 'All Servers',	# 'All Servers'
	      'Menu:Printer' => 'プリンタ', // 'Printers',		# 'Printers'
	      'Menu:Printer+' => '全プリンタ', // 'All Printers',	# 'All Printers'
	      'Menu:MobilePhone' => 'モバイルフォン', // 'Mobile Phones',	# 'Mobile Phones'
	      'Menu:MobilePhone+' => '全モバイルフォン', // 'All Mobile Phones',	  # 'All Mobile Phones'
	      'Menu:PC' => 'パーソナルコンピュータ', // 'Personal Computers', # 'Personal Computers'
	      'Menu:PC+' => '全パーソナルコンピュータ', // 'All Personal Computers',	       # 'All Personal Computers'
	      'Menu:NewContact' => '新規コンタクト', // 'New Contact',	       # 'New Contact'
	      'Menu:NewContact+' => '新規コンタクト', // 'New Contact',	       # 'New Contact'
	      'Menu:SearchContacts' => 'コンタクトを検索', // 'Search for contacts',	 # 'Search for contacts'
	      'Menu:SearchContacts+' => 'コンタクトを検索', // 'Search for contacts', # 'Search for contacts'
	      'Menu:NewCI' => '新規CI', // 'New CI', # 'New CI'
	      'Menu:NewCI+' => '新規CI', // 'New CI',  # 'New CI'
	      'Menu:SearchCIs' => 'CIを検索', // 'Search for CIs',	# 'Search for CIs'
	      'Menu:SearchCIs+' => 'CIを検索', // 'Search for CIs',	# 'Search for CIs'
	      'Menu:ConfigManagement:Devices' => 'デバイス', // 'Devices',	  # 'Devices'
	      'Menu:ConfigManagement:AllDevices' => 'デバイス数: %1$d', // 'Number of devices: %1$d',	# 'Number of devices: %1$d'
	      'Menu:ConfigManagement:SWAndApps' => 'ソフトウェアとアプリケーション', // 'Software and Applications',	# 'Software and Applications'
	      'Menu:ConfigManagement:Misc' => 'Misc', // 'Miscellaneous',   # 'Miscellaneous'
	      'Menu:Group' => 'CIグループ', // 'Groups of CIs',		   # 'Groups of CIs'
	      'Menu:Group+' => 'CIグループ', // 'Groups of CIs',		   # 'Groups of CIs'
	      'Menu:ConfigManagement:Shortcuts' => 'ショートカット',  # 'Shortcuts'
	      'Menu:ConfigManagement:AllContacts' => '全コンタクト：%1$d', // 'All contacts: %1$d',	# 'All contacts: %1$d'
));
?>
