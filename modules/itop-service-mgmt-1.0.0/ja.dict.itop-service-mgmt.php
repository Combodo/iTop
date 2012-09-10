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


Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Menu:ServiceManagement' => 'サービス管理',
	'Menu:ServiceManagement+' => 'サービス管理概要',
	'Menu:Service:Overview' => '概要',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'サービスレベル別連絡先',
	'UI-ServiceManagementMenu-ContractsByStatus' => '状態別連絡先',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '30日以内に終了する契約',

	'Menu:ServiceType' => 'サービスタイプ',
	'Menu:ServiceType+' => 'サービスタイプ',
	'Menu:ProviderContract' => 'プロバイダ契約',
	'Menu:ProviderContract+' => 'プロバイダ契約',
	'Menu:CustomerContract' => '顧客契約',
	'Menu:CustomerContract+' => '顧客契約',
	'Menu:ServiceSubcategory' => 'サービスのサブカテゴリ',
	'Menu:ServiceSubcategory+' => 'サービスのサブカテゴリ',
	'Menu:Service' => 'サービス',
	'Menu:Service+' => 'サービス',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => 'サービスレベルアグリーメント',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => 'サービスレベルターゲット',

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
	'Class:Contract' => '契約',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => '名前',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:description' => '説明',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => '開始日',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => '終了日',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => '費用',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => '費用通貨',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => '米ドル',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'ユーロ',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:cost_unit' => '費用単位',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:billing_frequency' => '課金頻度',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:contact_list' => '連絡先',
	'Class:Contract/Attribute:contact_list+' => 'この契約に関連する連絡先',
	'Class:Contract/Attribute:document_list' => '文書',
	'Class:Contract/Attribute:document_list+' => 'この契約に付随する文書',
	'Class:Contract/Attribute:ci_list' => 'CI',
	'Class:Contract/Attribute:ci_list+' => 'この契約でサポートされるCI',
	'Class:Contract/Attribute:finalclass' => 'タイプ',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ProviderContract' => 'プロバイダ契約',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:provider_id' => 'プロバイダ',
	'Class:ProviderContract/Attribute:provider_id+' => '',
	'Class:ProviderContract/Attribute:provider_name' => 'プロバイダ名',
	'Class:ProviderContract/Attribute:provider_name+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'サービスレベルアグリーメント',
	'Class:ProviderContract/Attribute:coverage' => 'サービス時間帯',
	'Class:ProviderContract/Attribute:coverage+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:CustomerContract' => '顧客契約',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:org_id' => '顧客',
	'Class:CustomerContract/Attribute:org_id+' => '',
	'Class:CustomerContract/Attribute:org_name' => '顧客名',
	'Class:CustomerContract/Attribute:org_name+' => '',
	'Class:CustomerContract/Attribute:provider_id' => 'プロバイダ',
	'Class:CustomerContract/Attribute:provider_id+' => '',
	'Class:CustomerContract/Attribute:provider_name' => 'プロバイダ名',
	'Class:CustomerContract/Attribute:provider_name+' => '',
	'Class:CustomerContract/Attribute:support_team_id' => 'サポートチーム',
	'Class:CustomerContract/Attribute:support_team_id+' => '',
	'Class:CustomerContract/Attribute:support_team_name' => 'サポートチーム',
	'Class:CustomerContract/Attribute:support_team_name+' => '',
	'Class:CustomerContract/Attribute:provider_list' => 'プロバイダ',
	'Class:CustomerContract/Attribute:provider_list+' => '',
	'Class:CustomerContract/Attribute:sla_list' => 'SLA',
	'Class:CustomerContract/Attribute:sla_list+' => 'この契約に関連するSLAリスト',
	'Class:CustomerContract/Attribute:provider_list' => '前提となる契約', // 'Underpinning Contracts',
	'Class:CustomerContract/Attribute:sla_list+' => '',
));
//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkCustomerContractToProviderContract' => '顧客契約とプロバイダ契約のリンク',
	'Class:lnkCustomerContractToProviderContract+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id' => 'カスタマ契約',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name' => '名前',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id' => 'プロバイダ契約',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name' => '名前',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla' => 'プロバイダSLA',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla+' => 'サービスレベルアグリーメント',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage' => 'サービス時間帯',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage+' => '',
));


//
// Class: lnkContractToSLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToSLA' => '契約/SLA',
	'Class:lnkContractToSLA+' => '',
	'Class:lnkContractToSLA/Attribute:contract_id' => '契約',
	'Class:lnkContractToSLA/Attribute:contract_id+' => '',
	'Class:lnkContractToSLA/Attribute:contract_name' => '契約',
	'Class:lnkContractToSLA/Attribute:contract_name+' => '',
	'Class:lnkContractToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_id+' => '',
	'Class:lnkContractToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_name+' => '',
	'Class:lnkContractToSLA/Attribute:coverage' => 'サービス時間帯',
	'Class:lnkContractToSLA/Attribute:coverage+' => '',
));

//
// Class: lnkContractToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToDoc' => '契約/文書',
	'Class:lnkContractToDoc+' => '',
	'Class:lnkContractToDoc/Attribute:contract_id' => '契約',
	'Class:lnkContractToDoc/Attribute:contract_id+' => '',
	'Class:lnkContractToDoc/Attribute:contract_name' => '契約',
	'Class:lnkContractToDoc/Attribute:contract_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_id' => '文書',
	'Class:lnkContractToDoc/Attribute:document_id+' => '',
	'Class:lnkContractToDoc/Attribute:document_name' => '文書',
	'Class:lnkContractToDoc/Attribute:document_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_type' => '文書のタイプ',
	'Class:lnkContractToDoc/Attribute:document_type+' => '',
	'Class:lnkContractToDoc/Attribute:document_status' => '文書の状態',
	'Class:lnkContractToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkContractToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToContact' => '契約/連絡先',
	'Class:lnkContractToContact+' => '',
	'Class:lnkContractToContact/Attribute:contract_id' => '契約',
	'Class:lnkContractToContact/Attribute:contract_id+' => '',
	'Class:lnkContractToContact/Attribute:contract_name' => '契約',
	'Class:lnkContractToContact/Attribute:contract_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_id' => '連絡先',
	'Class:lnkContractToContact/Attribute:contact_id+' => '',
	'Class:lnkContractToContact/Attribute:contact_name' => '連絡先',
	'Class:lnkContractToContact/Attribute:contact_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_email' => '連絡先Eメール',
	'Class:lnkContractToContact/Attribute:contact_email+' => '',
	'Class:lnkContractToContact/Attribute:role' => '役割',
	'Class:lnkContractToContact/Attribute:role+' => '',
));

//
// Class: lnkContractToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkContractToCI' => '契約/CI',
	'Class:lnkContractToCI+' => '',
	'Class:lnkContractToCI/Attribute:contract_id' => '契約',
	'Class:lnkContractToCI/Attribute:contract_id+' => '',
	'Class:lnkContractToCI/Attribute:contract_name' => '契約',
	'Class:lnkContractToCI/Attribute:contract_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_id' => 'CI',
	'Class:lnkContractToCI/Attribute:ci_id+' => '',
	'Class:lnkContractToCI/Attribute:ci_name' => 'CI',
	'Class:lnkContractToCI/Attribute:ci_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkContractToCI/Attribute:ci_status+' => '',
));

//
// Class: Service
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:Service' => 'サービス',
	'Class:Service+' => '',
	'Class:Service/Attribute:org_id' => 'プロバイダ',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:provider_name' => 'プロバイダ',
	'Class:Service/Attribute:provider_name+' => '',
	'Class:Service/Attribute:name' => '名前',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:description' => '説明',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:type' => 'タイプ',
	'Class:Service/Attribute:type+' => '',
	'Class:Service/Attribute:type/Value:IncidentManagement'  => 'インシデント管理',
	'Class:Service/Attribute:type/Value:IncidentManagement+' => 'インシデント管理',
	'Class:Service/Attribute:type/Value:RequestManagement'   => '要求管理',
	'Class:Service/Attribute:type/Value:RequestManagement+'  => '要求管理',
	'Class:Service/Attribute:status' => '状態',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:design' => '設計',
	'Class:Service/Attribute:status/Value:design+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => '廃止済',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => '稼働中',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:subcategory_list' => 'サービスサブカテゴリ',
	'Class:Service/Attribute:subcategory_list+' => '',
	'Class:Service/Attribute:sla_list' => 'SLA',
	'Class:Service/Attribute:sla_list+' => '',
	'Class:Service/Attribute:document_list' => '文書',
	'Class:Service/Attribute:document_list+' => 'サービスに添付されている文書',
	'Class:Service/Attribute:contact_list' => '連絡先',
	'Class:Service/Attribute:contact_list+' => 'このサービスの役割を持つ連絡先',
	'Class:Service/Tab:Related_Contracts' => '関連する契約',
	'Class:Service/Tab:Related_Contracts+' => 'このサービスのために締結された契約',
));

//
// Class: ServiceSubcategory
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:ServiceSubcategory' => 'サービスサブカテゴリ',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => '名前',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => '説明',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'サービス',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'サービス',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
));

//
// Class: SLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => '名前',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:service_id' => 'サービス',
	'Class:SLA/Attribute:service_id+' => '',
	'Class:SLA/Attribute:service_name' => 'サービス',
	'Class:SLA/Attribute:service_name+' => '',
	'Class:SLA/Attribute:slt_list' => 'SLT',
	'Class:SLA/Attribute:slt_list+' => 'サービスレベル閾値リスト',
));

//
// Class: SLT
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'SLT サービスレベルターゲット',
	'Class:SLT/Attribute:name' => '名前',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:metric' => 'メトリック',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:TTO' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTO+' => 'TTO Time To Own, 対応開始までの時間',	# 'TTO'
	'Class:SLT/Attribute:metric/Value:TTR' => 'TTR',
	'Class:SLT/Attribute:metric/Value:TTR+' => 'TTR Time To Resolve, 解決までの時間',	# 'TTR'
	'Class:SLT/Attribute:ticket_priority' => 'チケット優先度',
	'Class:SLT/Attribute:ticket_priority+' => '',
	'Class:SLT/Attribute:ticket_priority/Value:1' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:1+' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:2' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:2+' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:3' => '3',
	'Class:SLT/Attribute:ticket_priority/Value:3+' => '3',
	'Class:SLT/Attribute:value' => '値',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:value_unit' => '単位',
	'Class:SLT/Attribute:value_unit+' => '',
	'Class:SLT/Attribute:value_unit/Value:days' => '日',
	'Class:SLT/Attribute:value_unit/Value:days+' => '日',
	'Class:SLT/Attribute:value_unit/Value:hours' => '時',
	'Class:SLT/Attribute:value_unit/Value:hours+' => '時',
	'Class:SLT/Attribute:value_unit/Value:minutes' => '分',
	'Class:SLT/Attribute:value_unit/Value:minutes+' => '分',
	'Class:SLT/Attribute:sla_list' => 'SLA',
	'Class:SLT/Attribute:sla_list+' => 'このSLTを使うSLA',
));

//
// Class: lnkSLTToSLA
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkSLTToSLA' => 'SLT/SLA',
	'Class:lnkSLTToSLA+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_id+' => '',
	'Class:lnkSLTToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkSLTToSLA/Attribute:sla_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_id' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_id+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_name' => 'SLT',
	'Class:lnkSLTToSLA/Attribute:slt_name+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_metric' => 'メトリック',
	'Class:lnkSLTToSLA/Attribute:slt_metric+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority' => 'チケット優先度',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value' => '値',
	'Class:lnkSLTToSLA/Attribute:slt_value+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit' => '単位',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkServiceToDoc
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToDoc' => 'サービス/文書',
	'Class:lnkServiceToDoc+' => '',
	'Class:lnkServiceToDoc/Attribute:service_id' => 'サービス',
	'Class:lnkServiceToDoc/Attribute:service_id+' => '',
	'Class:lnkServiceToDoc/Attribute:service_name' => 'サービス',
	'Class:lnkServiceToDoc/Attribute:service_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_id' => '文書',
	'Class:lnkServiceToDoc/Attribute:document_id+' => '',
	'Class:lnkServiceToDoc/Attribute:document_name' => '文書',
	'Class:lnkServiceToDoc/Attribute:document_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_type' => '文書のタイプ',
	'Class:lnkServiceToDoc/Attribute:document_type+' => '',
	'Class:lnkServiceToDoc/Attribute:document_status' => '文書の状態',
	'Class:lnkServiceToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkServiceToContact
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToContact' => 'サービス/連絡先',
	'Class:lnkServiceToContact+' => '',
	'Class:lnkServiceToContact/Attribute:service_id' => 'サービス',
	'Class:lnkServiceToContact/Attribute:service_id+' => '',
	'Class:lnkServiceToContact/Attribute:service_name' => 'サービス',
	'Class:lnkServiceToContact/Attribute:service_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_id' => '連絡先',
	'Class:lnkServiceToContact/Attribute:contact_id+' => '',
	'Class:lnkServiceToContact/Attribute:contact_name' => '連絡先',
	'Class:lnkServiceToContact/Attribute:contact_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_email' => '連絡先Eメール',
	'Class:lnkServiceToContact/Attribute:contact_email+' => '',
	'Class:lnkServiceToContact/Attribute:role' => '役割',
	'Class:lnkServiceToContact/Attribute:role+' => '',
));

//
// Class: lnkServiceToCI
//

Dict::Add('JA JP', 'Japanese', '日本語', array (
	'Class:lnkServiceToCI' => 'サービス/CI',
	'Class:lnkServiceToCI+' => '',
	'Class:lnkServiceToCI/Attribute:service_id' => 'サービス',
	'Class:lnkServiceToCI/Attribute:service_id+' => '',
	'Class:lnkServiceToCI/Attribute:service_name' => 'サービス',
	'Class:lnkServiceToCI/Attribute:service_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_id' => 'CI',
	'Class:lnkServiceToCI/Attribute:ci_id+' => '',
	'Class:lnkServiceToCI/Attribute:ci_name' => 'CI',
	'Class:lnkServiceToCI/Attribute:ci_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_status' => 'CIの状態',
	'Class:lnkServiceToCI/Attribute:ci_status+' => '',
));


?>
