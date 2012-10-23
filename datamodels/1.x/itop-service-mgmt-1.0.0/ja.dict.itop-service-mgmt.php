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


Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:ServiceManagement' => 'サービス管理', // 'Service Management',	# 'Service Management'
	'Menu:ServiceManagement+' => 'サービス管理概要', // 'Service Management Overview',	   # 'Service Management Overview'
	'Menu:Service:Overview' => '概要',		 # 'Overview'
	'Menu:Service:Overview+' => '',			 # ''
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'サービスレベル別コンタクト', // 'Contracts by service level',	# 'Contracts by service level'
	'UI-ServiceManagementMenu-ContractsByStatus' => 'ステータス別コンタクト', // 'Contracts by status',	# 'Contracts by status'
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '30日以内に終了するコンタクト', // 'Contracts ending in less then 30 days',	# 'Contracts ending in less then 30 days'
	'Menu:ServiceType' => 'サービスタイプ', // 'Service Types',		   # 'Service Types'
	'Menu:ServiceType+' => 'サービスタイプ', // 'Service Types',		   # 'Service Types'
	'Menu:ProviderContract' => 'プロバイダコンタクト', // 'Provider Contracts',   # 'Provider Contracts'
	'Menu:ProviderContract+' => 'プロバイダコンタクト', // 'Provider Contracts',  # 'Provider Contracts'
	'Menu:CustomerContract' => 'カスタマーコンタクト', // 'Customer Contracts',   # 'Customer Contracts'
	'Menu:CustomerContract+' => 'カスタマーコンタクト', // 'Customer Contracts',  # 'Customer Contracts'
	'Menu:ServiceSubcategory' => 'サービスのサブカテゴリ', // 'Service Subcategories',	       # 'Service Subcategories'
	'Menu:ServiceSubcategory+' => 'サービスのサブカテゴリ', // 'Service Subcategories',	       # 'Service Subcategories'
	'Menu:Service' => 'サービス', // 'Services',	# 'Services'
	'Menu:Service+' => 'サービス', // 'Services',	# 'Services'
	'Menu:SLA' => 'SLA', // 'SLAs',		# 'SLAs'
	'Menu:SLA+' => 'サービスレベルアグリーメント', // 'Service Level Agreements',	# 'Service Level Agreements'
	'Menu:SLT' => 'SLT', // 'SLTs',	# 'SLTs'
	'Menu:SLT+' => 'サービスレベルターゲット', // 'Service Level Targets',	# 'Service Level Targets'
));


/*
	'UI:ServiceManagementMenu' => 'Gestion des Services',
	'UI:ServiceManagementMenu+' => 'Gestion des Services',
	'UI:ServiceManagementMenu:Title' => 'R辿sum辿 des services & contrats',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Contrats par niveau de service',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Contrats par 辿tat',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => 'Contrats se terminant dans moins de 30 jours',
*/


//
// Class: Contract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Contract' => '契約', // 'Contract',	# 'Contract'
	'Class:Contract+' => '',	# ''
	'Class:Contract/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:Contract/Attribute:name+' => '',		# ''
	'Class:Contract/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Contract/Attribute:description+' => '',			# ''
	'Class:Contract/Attribute:start_date' => '開始日付', // 'Start date',		# 'Start date'
	'Class:Contract/Attribute:start_date+' => '',	# ''
	'Class:Contract/Attribute:end_date' => '終了日付', // 'End date',	# 'End date'
	'Class:Contract/Attribute:end_date+' => '', # ''
	'Class:Contract/Attribute:cost' => 'コスト', // 'Cost',  # 'Cost'
	'Class:Contract/Attribute:cost+' => '',	    # ''
	'Class:Contract/Attribute:cost_currency' => 'コスト通貨', // 'Cost Currency',	# 'Cost Currency'
	'Class:Contract/Attribute:cost_currency+' => '',  # ''
	'Class:Contract/Attribute:cost_currency/Value:dollars' => '米ドル', // 'Dollars',	# 'Dollars'
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',		# ''
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'ユーロ', // 'Euros',	# 'Euros'
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',		# ''
	'Class:Contract/Attribute:cost_unit' => 'コスト単位', // 'Cost unit',  # 'Cost unit'
	'Class:Contract/Attribute:cost_unit+' => '',  # ''
	'Class:Contract/Attribute:billing_frequency' => 'ビリング頻度', // 'Billing frequency',	# 'Billing frequency'
	'Class:Contract/Attribute:billing_frequency+' => '',	 # ''
	'Class:Contract/Attribute:contact_list' => 'コンタクト', // 'Contacts',	 # 'Contacts'
	'Class:Contract/Attribute:contact_list+' => '本契約に関連するコンタクト', // 'Contacts related to the contract',	# 'Contacts related to the contract'
	'Class:Contract/Attribute:document_list' => 'ドキュメント', // 'Documents',      # 'Documents'
	'Class:Contract/Attribute:document_list+' => '本契約に付随するドキュメント', // 'Documents attached to the contract',	# 'Documents attached to the contract'
	'Class:Contract/Attribute:ci_list' => 'CI', // 'CIs', # 'CIs'
	'Class:Contract/Attribute:ci_list+' => '本契約でサポートされるCI', // 'CI supported by the contract',	# 'CI supported by the contract'
	'Class:Contract/Attribute:finalclass' => 'タイプ', // 'Type',     # 'Type'
	'Class:Contract/Attribute:finalclass+' => '',	     # ''
));

//
// Class: ProviderContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ProviderContract' => 'プロバイダ契約', // 'Provider Contract',	# 'Provider Contract'
	'Class:ProviderContract+' => '',      # ''
	'Class:ProviderContract/Attribute:provider_id' => 'プロバイダ', // 'Provider',	# 'Provider'
	'Class:ProviderContract/Attribute:provider_id+' => '',		# ''
	'Class:ProviderContract/Attribute:provider_name' => 'プロバイダ名', // 'Provider name',	# 'Provider name'
	'Class:ProviderContract/Attribute:provider_name+' => '',      # ''
	'Class:ProviderContract/Attribute:sla' => 'SLA',  # 'SLA'
	'Class:ProviderContract/Attribute:sla+' => 'サービスレベルアグリーメント', // 'Service Level Agreement',	# 'Service Level Agreement'
	'Class:ProviderContract/Attribute:coverage' => 'サービス時間', // 'Service hours',		# 'Service hours'
	'Class:ProviderContract/Attribute:coverage+' => '',	# ''
));

//
// Class: CustomerContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:CustomerContract' => 'カスタマ契約', // 'Customer Contract',	# 'Customer Contract'
	'Class:CustomerContract+' => '',      # ''
	'Class:CustomerContract/Attribute:org_id' => 'カスタマ', // 'Customer',	# 'Customer'
	'Class:CustomerContract/Attribute:org_id+' => '',		# ''
	'Class:CustomerContract/Attribute:org_name' => 'カスタマ名', // 'Customer name',	# 'Customer name'
	'Class:CustomerContract/Attribute:org_name+' => '',	 # ''
	'Class:CustomerContract/Attribute:provider_id' => 'プロバイダ', // 'Provider',	# 'Provider'
	'Class:CustomerContract/Attribute:provider_id+' => '',		# ''
	'Class:CustomerContract/Attribute:provider_name' => 'プロバイダ名', // 'Provider name',	# 'Provider name'
	'Class:CustomerContract/Attribute:provider_name+' => '',      # ''
	'Class:CustomerContract/Attribute:support_team_id' => 'サポートチーム', // 'Support team',	# 'Support team'
	'Class:CustomerContract/Attribute:support_team_id+' => '',     # ''
	'Class:CustomerContract/Attribute:support_team_name' => 'サポートチーム', // 'Support team',	# 'Support team'
	'Class:CustomerContract/Attribute:support_team_name+' => '',	 # ''
	'Class:CustomerContract/Attribute:provider_list' => 'プロバイダ', // 'Providers', # 'Providers'
	'Class:CustomerContract/Attribute:provider_list+' => '',	 # ''
	'Class:CustomerContract/Attribute:sla_list' => 'SLA', // 'SLAs',		 # 'SLAs'
	'Class:CustomerContract/Attribute:sla_list+' => '本契約に関連するSLAリスト', // 'List of SLA related to the contract',	# 'List of SLA related to the contract'
	'Class:CustomerContract/Attribute:provider_list' => '前提となる契約', // 'Underpinning Contracts',		# 'Underpinning Contracts'
	'Class:CustomerContract/Attribute:sla_list+' => '', # ''
));

//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCustomerContractToProviderContract' => 'カスタマー契約とプロバイダ契約のリンク', // 'lnkCustomerContractToProviderContract',	# 'lnkCustomerContractToProviderContract'
	'Class:lnkCustomerContractToProviderContract+' => '',						# ''
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id' => 'カスタマ契約', // 'Customer Contract',	# 'Customer Contract'
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id+' => '',	  # ''
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name' => '名前', // 'Name', # 'Name'
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name+' => '',	  # ''
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id' => 'プロバイダ契約', // 'Provider Contract',	# 'Provider Contract'
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id+' => '',	  # ''
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name' => '名前', // 'Name', # 'Name'
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name+' => '',	  # ''
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla' => 'プロバイダSLA', // 'Provider SLA',	  # 'Provider SLA'
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla+' => 'サービスレベルアグリーメント', // 'Service Level Agreement',   # 'Service Level Agreement'
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage' => 'サービス時間', // 'Service hours',	      # 'Service hours'
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage+' => '',     # ''
));


//
// Class: lnkContractToSLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToSLA' => '契約/SLA', // 'Contract/SLA',	# 'Contract/SLA'
	'Class:lnkContractToSLA+' => '',		# ''
	'Class:lnkContractToSLA/Attribute:contract_id' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToSLA/Attribute:contract_id+' => '',		# ''
	'Class:lnkContractToSLA/Attribute:contract_name' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToSLA/Attribute:contract_name+' => '',	# ''
	'Class:lnkContractToSLA/Attribute:sla_id' => 'SLA',  # 'SLA'
	'Class:lnkContractToSLA/Attribute:sla_id+' => '',    # ''
	'Class:lnkContractToSLA/Attribute:sla_name' => 'SLA',  # 'SLA'
	'Class:lnkContractToSLA/Attribute:sla_name+' => '',    # ''
	'Class:lnkContractToSLA/Attribute:coverage' => 'サービス時間', // 'Service Hours',	# 'Service Hours'
	'Class:lnkContractToSLA/Attribute:coverage+' => '',	# ''
));

//
// Class: lnkContractToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToDoc' => '契約/ドキュメント', // 'Contract/Doc',	# 'Contract/Doc'
	'Class:lnkContractToDoc+' => '',		# ''
	'Class:lnkContractToDoc/Attribute:contract_id' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToDoc/Attribute:contract_id+' => '',		# ''
	'Class:lnkContractToDoc/Attribute:contract_name' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToDoc/Attribute:contract_name+' => '',	# ''
	'Class:lnkContractToDoc/Attribute:document_id' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkContractToDoc/Attribute:document_id+' => '',		# ''
	'Class:lnkContractToDoc/Attribute:document_name' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkContractToDoc/Attribute:document_name+' => '',	# ''
	'Class:lnkContractToDoc/Attribute:document_type' => 'ドキュメントタイプ', // 	'Document type',	 # 'Document type'
	'Class:lnkContractToDoc/Attribute:document_type+' => '',		 # ''
	'Class:lnkContractToDoc/Attribute:document_status' => 'ドキュメントステータス', // 'Document status', # 'Document status'
	'Class:lnkContractToDoc/Attribute:document_status+' => '',		 # ''
));

//
// Class: lnkContractToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToContact' => '契約/コンタクト', // 'Contract/Contact',	# 'Contract/Contact'
	'Class:lnkContractToContact+' => '',			# ''
	'Class:lnkContractToContact/Attribute:contract_id' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToContact/Attribute:contract_id+' => '',		# ''
	'Class:lnkContractToContact/Attribute:contract_name' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToContact/Attribute:contract_name+' => '',		# ''
	'Class:lnkContractToContact/Attribute:contact_id' => 'コンタクト', // 'Contact',		# 'Contact'
	'Class:lnkContractToContact/Attribute:contact_id+' => '',		# ''
	'Class:lnkContractToContact/Attribute:contact_name' => 'コンタクト', // 'Contact',	# 'Contact'
	'Class:lnkContractToContact/Attribute:contact_name+' => '',		# ''
	'Class:lnkContractToContact/Attribute:contact_email' => 'コンタクトEメール', // 'Contact email',  # 'Contact email'
	'Class:lnkContractToContact/Attribute:contact_email+' => '',	 # ''
	'Class:lnkContractToContact/Attribute:role' => '役割', // 'Role',	 # 'Role'
	'Class:lnkContractToContact/Attribute:role+' => '',	 # ''
));

//
// Class: lnkContractToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToCI' => '契約/CI', // 'Contract/CI',	# 'Contract/CI'
	'Class:lnkContractToCI+' => '',			# ''
	'Class:lnkContractToCI/Attribute:contract_id' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToCI/Attribute:contract_id+' => '',		# ''
	'Class:lnkContractToCI/Attribute:contract_name' => '契約', // 'Contract',	# 'Contract'
	'Class:lnkContractToCI/Attribute:contract_name+' => '',		# ''
	'Class:lnkContractToCI/Attribute:ci_id' => 'CI', # 'CI'
	'Class:lnkContractToCI/Attribute:ci_id+' => '',	 # ''
	'Class:lnkContractToCI/Attribute:ci_name' => 'CI', # 'CI'
	'Class:lnkContractToCI/Attribute:ci_name+' => '',  # ''
	'Class:lnkContractToCI/Attribute:ci_status' => 'CIステータス', // 'CI status',	# 'CI status'
	'Class:lnkContractToCI/Attribute:ci_status+' => '',		# ''
));

//
// Class: Service
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Service' => 'サービス', // 'Service',	# 'Service'
	'Class:Service+' => '',		# ''
	'Class:Service/Attribute:org_id' => 'プロバイダ', // 'Provider',	# 'Provider'
	'Class:Service/Attribute:org_id+' => '',	# ''
	'Class:Service/Attribute:provider_name' => 'プロバイダ', // 'Provider',	# 'Provider'
	'Class:Service/Attribute:provider_name+' => '',		# ''
	'Class:Service/Attribute:name' => '名前', // 'Name',   # 'Name'
	'Class:Service/Attribute:name+' => '',	    # ''
	'Class:Service/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:Service/Attribute:description+' => '',		# ''
	'Class:Service/Attribute:type' => 'タイプ', // 'Type', # 'Type'
	'Class:Service/Attribute:type+' => '',	  # ''
	'Class:Service/Attribute:type/Value:IncidentManagement'  => 'インシデント管理',	# 'Incident Management'
	'Class:Service/Attribute:type/Value:IncidentManagement+' => 'インシデント管理',	# 'Incident Management'
	'Class:Service/Attribute:type/Value:RequestManagement'   => 'リクエスト管理',	# 'Request Management'
	'Class:Service/Attribute:type/Value:RequestManagement+'  => 'リクエスト管理',	# 'Request Management'
	'Class:Service/Attribute:status' => 'Status',		# 'Status'
	'Class:Service/Attribute:status+' => '',		# ''
	'Class:Service/Attribute:status/Value:design' => '設計', // 'Design',	# 'Design'
	'Class:Service/Attribute:status/Value:design+' => '',		# ''
	'Class:Service/Attribute:status/Value:obsolete' => 'すでに利用されていない', // 'Obsolete',	# 'Obsolete'
	'Class:Service/Attribute:status/Value:obsolete+' => '',		# ''
	'Class:Service/Attribute:status/Value:production' => 'プロダクション', // 'Production',	# 'Production'
	'Class:Service/Attribute:status/Value:production+' => '',		# ''
	'Class:Service/Attribute:subcategory_list' => 'サービスサブカテゴリ', // 'Service subcategories',	# 'Service subcategories'
	'Class:Service/Attribute:subcategory_list+' => '',     # ''
	'Class:Service/Attribute:sla_list' => 'SLA', // 'SLAs',  # 'SLAs'
	'Class:Service/Attribute:sla_list+' => '',     # ''
	'Class:Service/Attribute:document_list' => 'ドキュメント', // 'Documents',	# 'Documents'
	'Class:Service/Attribute:document_list+' => 'サービスに添付されているドキュメント', // 'Documents attached to the service',	# 'Documents attached to the service'
	'Class:Service/Attribute:contact_list' => 'コンタクト', // 'Contacts',  # 'Contacts'
	'Class:Service/Attribute:contact_list+' => '本サービスに対する役割を保持するコンタクト', // 'Contacts having a role for this service',	# 'Contacts having a role for this service'
	'Class:Service/Tab:Related_Contracts' => '関連する契約', // 'Related Contracts', # 'Related Contracts'
	'Class:Service/Tab:Related_Contracts+' => '本サービス用に締結された契約', // 'Contracts signed for this service',	# 'Contracts signed for this service'
));

//
// Class: ServiceSubcategory
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ServiceSubcategory' => 'サービスサブカテゴリ', // 'Service Subcategory',	# 'Service Subcategory'
	'Class:ServiceSubcategory+' => '',     # ''
	'Class:ServiceSubcategory/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:ServiceSubcategory/Attribute:name+' => '',	# ''
	'Class:ServiceSubcategory/Attribute:description' => '詳細記述', // 'Description',	# 'Description'
	'Class:ServiceSubcategory/Attribute:description+' => '',		# ''
	'Class:ServiceSubcategory/Attribute:service_id' => 'サービス', // 'Service',		# 'Service'
	'Class:ServiceSubcategory/Attribute:service_id+' => '',			# ''
	'Class:ServiceSubcategory/Attribute:service_name' => 'サービス', // 'Service',		# 'Service'
	'Class:ServiceSubcategory/Attribute:service_name+' => '',		# ''
));

//
// Class: SLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SLA' => 'SLA', # 'SLA'
	'Class:SLA+' => '',   # ''
	'Class:SLA/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:SLA/Attribute:name+' => '',	# ''
	'Class:SLA/Attribute:service_id' => 'サービス', // 'Service',	# 'Service'
	'Class:SLA/Attribute:service_id+' => '',	# ''
	'Class:SLA/Attribute:service_name' => 'サービス', // 'Service',  # 'Service'
	'Class:SLA/Attribute:service_name+' => '',	  # ''
	'Class:SLA/Attribute:slt_list' => 'SLT', // 'SLTs',	  # 'SLTs'
	'Class:SLA/Attribute:slt_list+' => 'サービスレベル閾値リスト', // 'List Service Level Thresholds',	# 'List Service Level Thresholds'
));

//
// Class: SLT
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SLT' => 'SLT', # 'SLT'
	'Class:SLT+' => '',   # ''
	'Class:SLT/Attribute:name' => '名前', // 'Name',	# 'Name'
	'Class:SLT/Attribute:name+' => '',	# ''
	'Class:SLT/Attribute:metric' => 'メトリック', // 'Metric', # 'Metric'
	'Class:SLT/Attribute:metric+' => '',	  # ''
	'Class:SLT/Attribute:metric/Value:TTO' => 'TTO',	# 'TTO'
	'Class:SLT/Attribute:metric/Value:TTO+' => 'TTO',	# 'TTO'
	'Class:SLT/Attribute:metric/Value:TTR' => 'TTR',	# 'TTR'
	'Class:SLT/Attribute:metric/Value:TTR+' => 'TTR',	# 'TTR'
	'Class:SLT/Attribute:ticket_priority' => 'チケットプライオリティ', // 'Ticket priority',	# 'Ticket priority'
	'Class:SLT/Attribute:ticket_priority+' => '',	 # ''
	'Class:SLT/Attribute:ticket_priority/Value:1' => '1',	# '1'
	'Class:SLT/Attribute:ticket_priority/Value:1+' => '1',	# '1'
	'Class:SLT/Attribute:ticket_priority/Value:2' => '2',	# '2'
	'Class:SLT/Attribute:ticket_priority/Value:2+' => '2',	# '2'
	'Class:SLT/Attribute:ticket_priority/Value:3' => '3',	# '3'
	'Class:SLT/Attribute:ticket_priority/Value:3+' => '3',	# '3'
	'Class:SLT/Attribute:value' => '値', // 'Value',	       # 'Value'
	'Class:SLT/Attribute:value+' => '',	       # ''
	'Class:SLT/Attribute:value_unit' => '単位', // 'Unit',    # 'Unit'
	'Class:SLT/Attribute:value_unit+' => '',       # ''
	'Class:SLT/Attribute:value_unit/Value:days' => '日間', // 'days',	# 'days'
	'Class:SLT/Attribute:value_unit/Value:days+' => '日間', // 'days',	# 'days'
	'Class:SLT/Attribute:value_unit/Value:hours' => '時間', // 'hours',  # 'hours'
	'Class:SLT/Attribute:value_unit/Value:hours+' => '時間', // 'hours', # 'hours'
	'Class:SLT/Attribute:value_unit/Value:minutes' => '分間', // 'minutes',	# 'minutes'
	'Class:SLT/Attribute:value_unit/Value:minutes+' => '分間', // 'minutes',	# 'minutes'
	'Class:SLT/Attribute:sla_list' => 'SLA', // 'SLAs',	# 'SLAs'
	'Class:SLT/Attribute:sla_list+' => '本SLTを使うSLA', // 'SLAs using the SLT',	# 'SLAs using the SLT'
));

//
// Class: lnkSLTToSLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkSLTToSLA' => 'SLT/SLA',     # 'SLT/SLA'
	'Class:lnkSLTToSLA+' => '',	      # ''
	'Class:lnkSLTToSLA/Attribute:sla_id' => 'SLA',	# 'SLA'
	'Class:lnkSLTToSLA/Attribute:sla_id+' => '',	# ''
	'Class:lnkSLTToSLA/Attribute:sla_name' => 'SLA',  # 'SLA'
	'Class:lnkSLTToSLA/Attribute:sla_name+' => '',	  # ''
	'Class:lnkSLTToSLA/Attribute:slt_id' => 'SLT',	  # 'SLT'
	'Class:lnkSLTToSLA/Attribute:slt_id+' => '',	  # ''
	'Class:lnkSLTToSLA/Attribute:slt_name' => 'SLT',  # 'SLT'
	'Class:lnkSLTToSLA/Attribute:slt_name+' => '',	  # ''
	'Class:lnkSLTToSLA/Attribute:slt_metric' => 'メトリック', // 'Metric',	# 'Metric'
	'Class:lnkSLTToSLA/Attribute:slt_metric+' => '',	# ''
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority' => 'チケットプライオリティ', // 'Ticket priority',	# 'Ticket priority'
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority+' => '',    # ''
	'Class:lnkSLTToSLA/Attribute:slt_value' => '値', // 'Value',   # 'Value'
	'Class:lnkSLTToSLA/Attribute:slt_value+' => '',	      # ''
	'Class:lnkSLTToSLA/Attribute:slt_value_unit' => '単位', // 'Unit',	# 'Unit'
	'Class:lnkSLTToSLA/Attribute:slt_value_unit+' => '',	# ''
));

//
// Class: lnkServiceToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToDoc' => 'サービス/ドキュメント', // 'Service/Doc',	# 'Service/Doc'
	'Class:lnkServiceToDoc+' => '',			# ''
	'Class:lnkServiceToDoc/Attribute:service_id' => 'サービス', // 'Service',	# 'Service'
	'Class:lnkServiceToDoc/Attribute:service_id+' => '',		# ''
	'Class:lnkServiceToDoc/Attribute:service_name' => 'サービス', // 'Service',	# 'Service'
	'Class:lnkServiceToDoc/Attribute:service_name+' => '',		# ''
	'Class:lnkServiceToDoc/Attribute:document_id' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkServiceToDoc/Attribute:document_id+' => '',		# ''
	'Class:lnkServiceToDoc/Attribute:document_name' => 'ドキュメント', // 'Document',	# 'Document'
	'Class:lnkServiceToDoc/Attribute:document_name+' => '',		# ''
	'Class:lnkServiceToDoc/Attribute:document_type' => 'ドキュメントタイプ', // 'Document type',	# 'Document type'
	'Class:lnkServiceToDoc/Attribute:document_type+' => '',	     # ''
	'Class:lnkServiceToDoc/Attribute:document_status' => 'ドキュメントステータス', // 'Document status',	# 'Document status'
	'Class:lnkServiceToDoc/Attribute:document_status+' => '',      # ''
));

//
// Class: lnkServiceToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToContact' => 'サービス/コンタクト', // 'Service/Contact',	# 'Service/Contact'
	'Class:lnkServiceToContact+' => '',			# ''
	'Class:lnkServiceToContact/Attribute:service_id' => 'サービス', // 'Service',	# 'Service'
	'Class:lnkServiceToContact/Attribute:service_id+' => '',	# ''
	'Class:lnkServiceToContact/Attribute:service_name' => 'サービス', // 'Service',  # 'Service'
	'Class:lnkServiceToContact/Attribute:service_name+' => '',	  # ''
	'Class:lnkServiceToContact/Attribute:contact_id' => 'コンタクト', // 'Contact',	  # 'Contact'
	'Class:lnkServiceToContact/Attribute:contact_id+' => '',	  # ''
	'Class:lnkServiceToContact/Attribute:contact_name' => 'コンタクト', // 'Contact',  # 'Contact'
	'Class:lnkServiceToContact/Attribute:contact_name+' => '',	  # ''
	'Class:lnkServiceToContact/Attribute:contact_email' => 'コンタクトEメール', // 'Contact email',	# 'Contact email'
	'Class:lnkServiceToContact/Attribute:contact_email+' => '',	# ''
	'Class:lnkServiceToContact/Attribute:role' => '役割', // 'Role',	# 'Role'
	'Class:lnkServiceToContact/Attribute:role+' => '',	# ''
));

//
// Class: lnkServiceToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToCI' => 'サービス/CI', // 'Service/CI',	# 'Service/CI'
	'Class:lnkServiceToCI+' => '',		# ''
	'Class:lnkServiceToCI/Attribute:service_id' => 'サービス', // 'Service',	# 'Service'
	'Class:lnkServiceToCI/Attribute:service_id+' => '',		# ''
	'Class:lnkServiceToCI/Attribute:service_name' => 'サービス', // 'Service',	# 'Service'
	'Class:lnkServiceToCI/Attribute:service_name+' => '',		# ''
	'Class:lnkServiceToCI/Attribute:ci_id' => 'CI',	  # 'CI'
	'Class:lnkServiceToCI/Attribute:ci_id+' => '',	  # ''
	'Class:lnkServiceToCI/Attribute:ci_name' => 'CI', # 'CI'
	'Class:lnkServiceToCI/Attribute:ci_name+' => '',  # ''
	'Class:lnkServiceToCI/Attribute:ci_status' => 'CIステータス', // 'CI status',	# 'CI status'
	'Class:lnkServiceToCI/Attribute:ci_status+' => '',		# ''
));

?>
