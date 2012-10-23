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
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
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


Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
'Menu:ServiceManagement' => 'Hizmet Yönetimi',
'Menu:ServiceManagement+' => 'Hizmet Yönetimi',
'Menu:Service:Overview' => 'Özet',
'Menu:Service:Overview+' => '',
'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Hizmet seviyesine göre sözleşmeler',
'UI-ServiceManagementMenu-ContractsByStatus' => 'Durumuna göre sözleşmeler',
'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '30 gün çinde biten sözleşmeler',

'Menu:ServiceType' => 'Hizmet Tipleri',
'Menu:ServiceType+' => 'Hizmet Tipleri',
'Menu:ProviderContract' => 'Tedarikçi Sözleşmeleri',
'Menu:ProviderContract+' => 'Tedarikçi Sözleşmeleri',
'Menu:CustomerContract' => 'Müşteri Sözleşmeleri',
'Menu:CustomerContract+' => 'Müşteri Sözleşmeleri',
'Menu:ServiceSubcategory' => 'Hizmet alt kategorileri',
'Menu:ServiceSubcategory+' => 'Hizmet alt kategorileri',
'Menu:Service' => 'Hizmetler',
'Menu:Service+' => 'Hizmetler',
'Menu:SLA' => 'SLAs',
'Menu:SLA+' => 'Hizmet Seviyesi Anlaşmaları',
'Menu:SLT' => 'SLTs',
'Menu:SLT+' => 'Hizmet Seviyesi Taahütleri',

));

//
// Class: Contract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Contract' => 'Sözleşme',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Adı',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:description' => 'Tanımlama',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Başlangıç Tarihi',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Bitiş Tarihi',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Maliyet',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Para Birimi',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'ABD Doları',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Avro',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Cost unit',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:billing_frequency' => 'Faturlandırma dönemleri',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:contact_list' => 'İrtibatlar',
	'Class:Contract/Attribute:contact_list+' => 'Sözleşme için İrtibatlar',
	'Class:Contract/Attribute:document_list' => 'Dokümanlar',
	'Class:Contract/Attribute:document_list+' => 'Sözleşmeye eklenen dokümanlar',
	'Class:Contract/Attribute:ci_list' => 'KK',
	'Class:Contract/Attribute:ci_list+' => 'Sözleşme kapsamındaki KK',
	'Class:Contract/Attribute:finalclass' => 'Tip',
	'Class:Contract/Attribute:finalclass+' => '',
));

//
// Class: ProviderContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ProviderContract' => 'Tedarikçi Sözleşmesi',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:provider_id' => 'Tedarikçi',
	'Class:ProviderContract/Attribute:provider_id+' => '',
	'Class:ProviderContract/Attribute:provider_name' => 'Tedarikçi Adı',
	'Class:ProviderContract/Attribute:provider_name+' => '',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement',
	'Class:ProviderContract/Attribute:coverage' => 'Service hours',
	'Class:ProviderContract/Attribute:coverage+' => '',
));

//
// Class: CustomerContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CustomerContract' => 'Müşteri Sözleşmesi',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:org_id' => 'Müşteri',
	'Class:CustomerContract/Attribute:org_id+' => '',
	'Class:CustomerContract/Attribute:org_name' => 'Müşteri Adı',
	'Class:CustomerContract/Attribute:org_name+' => '',
	'Class:CustomerContract/Attribute:provider_id' => 'Tedarikçi',
	'Class:CustomerContract/Attribute:provider_id+' => '',
	'Class:CustomerContract/Attribute:provider_name' => 'Tedarikçi Adı',
	'Class:CustomerContract/Attribute:provider_name+' => '',
	'Class:CustomerContract/Attribute:support_team_id' => 'Destek Ekibi',
	'Class:CustomerContract/Attribute:support_team_id+' => '',
	'Class:CustomerContract/Attribute:support_team_name' => 'Destek Ekibi',
	'Class:CustomerContract/Attribute:support_team_name+' => '',
	'Class:CustomerContract/Attribute:provider_list' => 'Tedarikçiler',
	'Class:CustomerContract/Attribute:provider_list+' => '',
	'Class:CustomerContract/Attribute:sla_list' => 'SLAs',
	'Class:CustomerContract/Attribute:sla_list+' => 'List of SLA related to the contract',
	'Class:CustomerContract/Attribute:provider_list' => 'Altyüklenici Sözleşmeleri',
	'Class:CustomerContract/Attribute:sla_list+' => '',
));
//
// Class: lnkCustomerContractToProviderContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkCustomerContractToProviderContract' => 'Müşteri ve Tedarikçi Sözleşmesi ilişkilendirmesi',
	'Class:lnkCustomerContractToProviderContract+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id' => 'Müşteri Sözleşmesi',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name' => 'Adı',
	'Class:lnkCustomerContractToProviderContract/Attribute:customer_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id' => 'Tedarikçi Sözleşmesi',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_id+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name' => 'Adı',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_contract_name+' => '',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla' => 'Tedarikçi SLA',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_sla+' => 'Hizmet Seviyesi Anlaşması',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage' => 'Hizmet süresi (saat)',
	'Class:lnkCustomerContractToProviderContract/Attribute:provider_coverage+' => '',
));


//
// Class: lnkContractToSLA
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContractToSLA' => 'Sözleşme/SLA',
	'Class:lnkContractToSLA+' => '',
	'Class:lnkContractToSLA/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContractToSLA/Attribute:contract_id+' => '',
	'Class:lnkContractToSLA/Attribute:contract_name' => 'Sözleşme',
	'Class:lnkContractToSLA/Attribute:contract_name+' => '',
	'Class:lnkContractToSLA/Attribute:sla_id' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_id+' => '',
	'Class:lnkContractToSLA/Attribute:sla_name' => 'SLA',
	'Class:lnkContractToSLA/Attribute:sla_name+' => '',
	'Class:lnkContractToSLA/Attribute:coverage' => 'Hizmet süresi (saat)',
	'Class:lnkContractToSLA/Attribute:coverage+' => '',
));

//
// Class: lnkContractToDoc
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContractToDoc' => 'Sözleşme/Doküman',
	'Class:lnkContractToDoc+' => '',
	'Class:lnkContractToDoc/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContractToDoc/Attribute:contract_id+' => '',
	'Class:lnkContractToDoc/Attribute:contract_name' => 'Sözleşme',
	'Class:lnkContractToDoc/Attribute:contract_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_id' => 'Doküman',
	'Class:lnkContractToDoc/Attribute:document_id+' => '',
	'Class:lnkContractToDoc/Attribute:document_name' => 'Doküman',
	'Class:lnkContractToDoc/Attribute:document_name+' => '',
	'Class:lnkContractToDoc/Attribute:document_type' => 'Doküman tipi',
	'Class:lnkContractToDoc/Attribute:document_type+' => '',
	'Class:lnkContractToDoc/Attribute:document_status' => 'Doküman durumu',
	'Class:lnkContractToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkContractToContact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContractToContact' => 'Sözleşme/Sözleşme',
	'Class:lnkContractToContact+' => '',
	'Class:lnkContractToContact/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContractToContact/Attribute:contract_id+' => '',
	'Class:lnkContractToContact/Attribute:contract_name' => 'Sözleşme',
	'Class:lnkContractToContact/Attribute:contract_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_id' => 'Sözleşme',
	'Class:lnkContractToContact/Attribute:contact_id+' => '',
	'Class:lnkContractToContact/Attribute:contact_name' => 'Sözleşme',
	'Class:lnkContractToContact/Attribute:contact_name+' => '',
	'Class:lnkContractToContact/Attribute:contact_email' => 'Sözleşme e-posta',
	'Class:lnkContractToContact/Attribute:contact_email+' => '',
	'Class:lnkContractToContact/Attribute:role' => 'Rol',
	'Class:lnkContractToContact/Attribute:role+' => '',
));

//
// Class: lnkContractToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContractToCI' => 'Sözleşme/KK',
	'Class:lnkContractToCI+' => '',
	'Class:lnkContractToCI/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContractToCI/Attribute:contract_id+' => '',
	'Class:lnkContractToCI/Attribute:contract_name' => 'Sözleşme',
	'Class:lnkContractToCI/Attribute:contract_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_id' => 'KK',
	'Class:lnkContractToCI/Attribute:ci_id+' => '',
	'Class:lnkContractToCI/Attribute:ci_name' => 'KK',
	'Class:lnkContractToCI/Attribute:ci_name+' => '',
	'Class:lnkContractToCI/Attribute:ci_status' => 'KK Durumu',
	'Class:lnkContractToCI/Attribute:ci_status+' => '',
));

//
// Class: Service
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Service' => 'Hizmet',
	'Class:Service+' => '',
	'Class:Service/Attribute:org_id' => 'Hizmet Sağlayıcı',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:provider_name' => 'Hizmet Sağlayıcı',
	'Class:Service/Attribute:provider_name+' => '',
	'Class:Service/Attribute:name' => 'Adı',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:description' => 'Tanımlama',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:type' => 'Tip',
	'Class:Service/Attribute:type+' => '',
	'Class:Service/Attribute:type/Value:IncidentManagement' => 'Arıza Yönetimi',
	'Class:Service/Attribute:type/Value:IncidentManagement+' => 'Arıza Yönetimi',
	'Class:Service/Attribute:type/Value:RequestManagement' => 'Çağrı Yönetimi',
	'Class:Service/Attribute:type/Value:RequestManagement+' => 'Çağrı Yönetimi',
	'Class:Service/Attribute:status' => 'Durum',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:design' => 'Tasarım',
	'Class:Service/Attribute:status/Value:design+' => '',
	'Class:Service/Attribute:status/Value:obsolete' => 'Üretimden Kalkan',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Kullanımda',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:subcategory_list' => 'Hizmet alt kategorisi',
	'Class:Service/Attribute:subcategory_list+' => '',
	'Class:Service/Attribute:sla_list' => 'SLAs',
	'Class:Service/Attribute:sla_list+' => '',
	'Class:Service/Attribute:document_list' => 'Dokümanlar',
	'Class:Service/Attribute:document_list+' => 'Hizmet bağlı dokümanlar',
	'Class:Service/Attribute:contact_list' => 'Sözleşmeler',
	'Class:Service/Attribute:contact_list+' => 'Hizmet ile ilintili Sözleşmeler',
	'Class:Service/Tab:Related_Contracts' => 'Hizmet ile ilintili Sözleşmeler',
	'Class:Service/Tab:Related_Contracts+' => 'Hizmet ile ilintili Sözleşmeler',
));

//
// Class: ServiceSubcategory
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ServiceSubcategory' => 'Hizmet alt kategorisi',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/Attribute:name' => 'Adı',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Tanımlama',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Hizmet',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Hizmet',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
));

//
// Class: SLA
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => 'Hizmet Seviyesi Anlaşması',
	'Class:SLA/Attribute:name' => 'Adı',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:service_id' => 'Hizmet',
	'Class:SLA/Attribute:service_id+' => '',
	'Class:SLA/Attribute:service_name' => 'Hizmet',
	'Class:SLA/Attribute:service_name+' => '',
	'Class:SLA/Attribute:slt_list' => 'SLTs',
	'Class:SLA/Attribute:slt_list+' => 'Hizmet Seviyesi Taahütler',
));

//
// Class: SLT
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Hizmet Seviyesi Taahütler',
	'Class:SLT/Attribute:name' => 'Adı',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:metric' => 'Metrik',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:TTO' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTO+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:TTR' => 'TTR',
	'Class:SLT/Attribute:metric/Value:TTR+' => 'TTR',
	'Class:SLT/Attribute:ticket_priority' => 'Çağrı önceliği',
	'Class:SLT/Attribute:ticket_priority+' => '',
	'Class:SLT/Attribute:ticket_priority/Value:1' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:1+' => '1',
	'Class:SLT/Attribute:ticket_priority/Value:2' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:2+' => '2',
	'Class:SLT/Attribute:ticket_priority/Value:3' => '3',
	'Class:SLT/Attribute:ticket_priority/Value:3+' => '3',
	'Class:SLT/Attribute:value' => 'Değer',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:value_unit' => 'Birim',
	'Class:SLT/Attribute:value_unit+' => '',
	'Class:SLT/Attribute:value_unit/Value:days' => 'gün',
	'Class:SLT/Attribute:value_unit/Value:days+' => 'gün',
	'Class:SLT/Attribute:value_unit/Value:hours' => 'saat',
	'Class:SLT/Attribute:value_unit/Value:hours+' => 'saat',
	'Class:SLT/Attribute:value_unit/Value:minutes' => 'dakika',
	'Class:SLT/Attribute:value_unit/Value:minutes+' => 'dakika',
	'Class:SLT/Attribute:sla_list' => 'SLAs',
	'Class:SLT/Attribute:sla_list+' => 'SLAs using the SLT',
));

//
// Class: lnkSLTToSLA
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
	'Class:lnkSLTToSLA/Attribute:slt_metric' => 'Metrik',
	'Class:lnkSLTToSLA/Attribute:slt_metric+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority' => 'Çağrı önceliği',
	'Class:lnkSLTToSLA/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value' => 'Değer',
	'Class:lnkSLTToSLA/Attribute:slt_value+' => '',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit' => 'Birim',
	'Class:lnkSLTToSLA/Attribute:slt_value_unit+' => '',
));

//
// Class: lnkServiceToDoc
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkServiceToDoc' => 'Hizmet/Doküman',
	'Class:lnkServiceToDoc+' => '',
	'Class:lnkServiceToDoc/Attribute:service_id' => 'Hizmet',
	'Class:lnkServiceToDoc/Attribute:service_id+' => '',
	'Class:lnkServiceToDoc/Attribute:service_name' => 'Hizmet',
	'Class:lnkServiceToDoc/Attribute:service_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_id' => 'Doküman',
	'Class:lnkServiceToDoc/Attribute:document_id+' => '',
	'Class:lnkServiceToDoc/Attribute:document_name' => 'Doküman',
	'Class:lnkServiceToDoc/Attribute:document_name+' => '',
	'Class:lnkServiceToDoc/Attribute:document_type' => 'Doküman tipi',
	'Class:lnkServiceToDoc/Attribute:document_type+' => '',
	'Class:lnkServiceToDoc/Attribute:document_status' => 'Doküman durumu',
	'Class:lnkServiceToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkServiceToContact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkServiceToContact' => 'Hizmet/Sözleşme',
	'Class:lnkServiceToContact+' => '',
	'Class:lnkServiceToContact/Attribute:service_id' => 'Hizmet',
	'Class:lnkServiceToContact/Attribute:service_id+' => '',
	'Class:lnkServiceToContact/Attribute:service_name' => 'Hizmet',
	'Class:lnkServiceToContact/Attribute:service_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_id' => 'Sözleşme',
	'Class:lnkServiceToContact/Attribute:contact_id+' => '',
	'Class:lnkServiceToContact/Attribute:contact_name' => 'Sözleşme',
	'Class:lnkServiceToContact/Attribute:contact_name+' => '',
	'Class:lnkServiceToContact/Attribute:contact_email' => 'Sözleşme e-posta',
	'Class:lnkServiceToContact/Attribute:contact_email+' => '',
	'Class:lnkServiceToContact/Attribute:role' => 'Rol',
	'Class:lnkServiceToContact/Attribute:role+' => '',
));

//
// Class: lnkServiceToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkServiceToCI' => 'Hizmet/KK',
	'Class:lnkServiceToCI+' => '',
	'Class:lnkServiceToCI/Attribute:service_id' => 'Hizmet',
	'Class:lnkServiceToCI/Attribute:service_id+' => '',
	'Class:lnkServiceToCI/Attribute:service_name' => 'Hizmet',
	'Class:lnkServiceToCI/Attribute:service_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_id' => 'KK',
	'Class:lnkServiceToCI/Attribute:ci_id+' => '',
	'Class:lnkServiceToCI/Attribute:ci_name' => 'KK',
	'Class:lnkServiceToCI/Attribute:ci_name+' => '',
	'Class:lnkServiceToCI/Attribute:ci_status' => 'KK durumu',
	'Class:lnkServiceToCI/Attribute:ci_status+' => '',
));


?>
