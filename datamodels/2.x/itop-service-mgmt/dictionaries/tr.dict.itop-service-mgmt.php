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
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
	'Menu:ProviderContract' => 'Tedarikçi Sözleşmeleri',
	'Menu:ProviderContract+' => 'Tedarikçi Sözleşmeleri',
	'Menu:CustomerContract' => 'Müşteri Sözleşmeleri',
	'Menu:CustomerContract+' => 'Müşteri Sözleşmeleri',
	'Menu:ServiceSubcategory' => 'Hizmet alt kategorileri',
	'Menu:ServiceSubcategory+' => 'Hizmet alt kategorileri',
	'Menu:Service' => 'Hizmetler',
	'Menu:Service+' => 'Hizmetler',
	'Menu:ServiceElement' => 'Servis elemanları',
	'Menu:ServiceElement+' => 'Servis elemanları',
	'Menu:SLA' => 'SLAs',
	'Menu:SLA+' => 'Hizmet Seviyesi Anlaşmaları',
	'Menu:SLT' => 'SLTs',
	'Menu:SLT+' => 'Hizmet Seviyesi Taahütleri',
	'Menu:DeliveryModel' => 'Teslimat modelleri',
	'Menu:DeliveryModel+' => 'Teslimat modelleri',
	'Menu:ServiceFamily' => 'Servis Aileleri',
	'Menu:ServiceFamily+' => 'Servis Aileleri',
	'Menu:Procedure' => 'Prosedür Kataloğu',
	'Menu:Procedure+' => 'Tüm Prosedürler Kataloğu',
));

//
// Class: Organization
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Teslimat Modeli',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Teslimat Modeli Adı',
));


//
// Class: ContractType
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ContractType' => 'Sözleşme Tipi',
	'Class:ContractType+' => '~~',
));

//
// Class: Contract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Contract' => 'Sözleşme',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Adı',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Müşteri',
	'Class:Contract/Attribute:org_id+' => '~~',
	'Class:Contract/Attribute:organization_name' => 'Müşteri Adı',
	'Class:Contract/Attribute:organization_name+' => 'Yaygın Adı',
	'Class:Contract/Attribute:contacts_list' => 'Kişiler',
	'Class:Contract/Attribute:contacts_list+' => 'Bu müşteri sözleşmesi için tüm kişiler',
	'Class:Contract/Attribute:documents_list' => 'Belgeler',
	'Class:Contract/Attribute:documents_list+' => 'Bu müşteri sözleşmesi için tüm belgeler',
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
	'Class:Contract/Attribute:contracttype_id' => 'Sözleşme Tipi',
	'Class:Contract/Attribute:contracttype_id+' => '~~',
	'Class:Contract/Attribute:contracttype_name' => 'Sözleşme Tip Adı',
	'Class:Contract/Attribute:contracttype_name+' => '~~',
	'Class:Contract/Attribute:billing_frequency' => 'Faturlandırma dönemleri',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Cost unit',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Sağlayıcı',
	'Class:Contract/Attribute:provider_id+' => '~~',
	'Class:Contract/Attribute:provider_name' => 'Sağlayıcı Adı',
	'Class:Contract/Attribute:provider_name+' => 'Yaygın Adı',
	'Class:Contract/Attribute:status' => 'Durum',
	'Class:Contract/Attribute:status+' => '~~',
	'Class:Contract/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:Contract/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Kullanım dışı',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'Kullanım dışı',
	'Class:Contract/Attribute:status/Value:production' => 'Kullanımda',
	'Class:Contract/Attribute:status/Value:production+' => 'Kullanımda',
	'Class:Contract/Attribute:finalclass' => 'Tip',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CustomerContract' => 'Müşteri Sözleşmesi',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Hizmetler',
	'Class:CustomerContract/Attribute:services_list+' => 'Bu sözleşme için satın alınan tüm hizmetler',
));

//
// Class: ProviderContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ProviderContract' => 'Tedarikçi Sözleşmesi',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CI\'lar',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Bu sağlayıcı sözleşmesi tarafından kapsanan tüm yapılandırma öğeleri',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => 'Service Level Agreement~~',
	'Class:ProviderContract/Attribute:coverage' => 'Service hours~~',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Sözleşme Tipi',
	'Class:ProviderContract/Attribute:contracttype_id+' => '~~',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Sözleşme Tip Adı',
	'Class:ProviderContract/Attribute:contracttype_name+' => '~~',
));

//
// Class: lnkContactToContract
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContactToContract' => 'İletişim / Sözleşme bağla',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContactToContract/Attribute:contract_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Sözleşme adı',
	'Class:lnkContactToContract/Attribute:contract_name+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Kişi',
	'Class:lnkContactToContract/Attribute:contact_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Kişi Adı',
	'Class:lnkContactToContract/Attribute:contact_name+' => '~~',
));

//
// Class: lnkContractToDocument
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContractToDocument' => 'Sözleşmesi / Belge bağla',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Sözleşme',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Sözleşme Adı',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Belge',
	'Class:lnkContractToDocument/Attribute:document_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Belge Adı',
	'Class:lnkContractToDocument/Attribute:document_name+' => '~~',
));

//
// Class: ServiceFamily
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ServiceFamily' => 'Servis Ailesi',
	'Class:ServiceFamily+' => '~~',
	'Class:ServiceFamily/Attribute:name' => 'İsim',
	'Class:ServiceFamily/Attribute:name+' => '~~',
	'Class:ServiceFamily/Attribute:icon' => 'Simgesi',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:ServiceFamily/Attribute:services_list' => 'Hizmetler',
	'Class:ServiceFamily/Attribute:services_list+' => 'Bu kategorideki tüm hizmetler',
));

//
// Class: Service
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Service' => 'Hizmet',
	'Class:Service+' => '',
	'Class:Service/Attribute:name' => 'Adı',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Hizmet Sağlayıcı',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Sağlayıcı Adı',
	'Class:Service/Attribute:organization_name+' => '~~',
	'Class:Service/Attribute:servicefamily_id' => 'Servis Ailesi',
	'Class:Service/Attribute:servicefamily_id+' => '~~',
	'Class:Service/Attribute:servicefamily_name' => 'Servis Aile Adı',
	'Class:Service/Attribute:servicefamily_name+' => '~~',
	'Class:Service/Attribute:description' => 'Tanımlama',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Belgeler',
	'Class:Service/Attribute:documents_list+' => 'Hizmetle bağlantılı tüm belgeler',
	'Class:Service/Attribute:contacts_list' => 'İletişim',
	'Class:Service/Attribute:contacts_list+' => 'Bu hizmet için tüm kişiler',
	'Class:Service/Attribute:status' => 'Durum',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:Service/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:Service/Attribute:status/Value:obsolete' => 'Üretimden Kalkan',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Kullanımda',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Simgesi',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:Service/Attribute:customercontracts_list' => 'Müşteri Sözleşmeleri',
	'Class:Service/Attribute:customercontracts_list+' => 'Bu hizmeti satın alan tüm müşterilerin sözleşmeleri',
	'Class:Service/Attribute:providercontracts_list' => 'Sağlayıcı Sözleşmeleri',
	'Class:Service/Attribute:providercontracts_list+' => 'Bu hizmeti destekleyen tüm sağlayıcıların sözleşmeleri',
	'Class:Service/Attribute:functionalcis_list' => 'CI\'lara bağlıdır',
	'Class:Service/Attribute:functionalcis_list+' => 'Bu hizmeti sağlamak için kullanılan tüm yapılandırma öğeleri',
	'Class:Service/Attribute:servicesubcategories_list' => 'Servis alt kategorileri',
	'Class:Service/Attribute:servicesubcategories_list+' => 'Bu hizmet için tüm alt kategoriler',
));

//
// Class: lnkDocumentToService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToService' => 'Belge / servis bağla',
	'Class:lnkDocumentToService+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Servis',
	'Class:lnkDocumentToService/Attribute:service_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Servis Adı',
	'Class:lnkDocumentToService/Attribute:service_name+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToService/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToService/Attribute:document_name+' => '~~',
));

//
// Class: lnkContactToService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContactToService' => 'Kişi / Servis bağla',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Attribute:service_id' => 'Servis',
	'Class:lnkContactToService/Attribute:service_id+' => '~~',
	'Class:lnkContactToService/Attribute:service_name' => 'Servis Adı',
	'Class:lnkContactToService/Attribute:service_name+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id' => 'Kişi',
	'Class:lnkContactToService/Attribute:contact_id+' => '~~',
	'Class:lnkContactToService/Attribute:contact_name' => 'Kişi Adı',
	'Class:lnkContactToService/Attribute:contact_name+' => '~~',
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
	'Class:ServiceSubcategory/Attribute:request_type' => 'İstek türü',
	'Class:ServiceSubcategory/Attribute:request_type+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Olay',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'Olay',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Servis İsteği',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'Servis İsteği',
	'Class:ServiceSubcategory/Attribute:status' => 'Durum',
	'Class:ServiceSubcategory/Attribute:status+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Kullanım dışı',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'Kullanım dışı',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Kullanımda',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'Kullanımda',
));

//
// Class: SLA
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => 'Hizmet Seviyesi Anlaşması',
	'Class:SLA/Attribute:name' => 'Adı',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Açıklama',
	'Class:SLA/Attribute:description+' => '~~',
	'Class:SLA/Attribute:org_id' => 'Sağlayıcı',
	'Class:SLA/Attribute:org_id+' => '~~',
	'Class:SLA/Attribute:organization_name' => 'Sağlayıcı Adı',
	'Class:SLA/Attribute:organization_name+' => 'Yaygın Adı',
	'Class:SLA/Attribute:slts_list' => 'SLT\'ler',
	'Class:SLA/Attribute:slts_list+' => 'Bu SLA için tüm hizmet seviyesi hedefleri',
	'Class:SLA/Attribute:customercontracts_list' => 'Müşteri Sözleşmeleri',
	'Class:SLA/Attribute:customercontracts_list+' => 'Bu SLA\'yı kullanan tüm müşterilerin sözleşmeleri',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Could not save link with Customer contract %1$s and service %2$s : SLA already exists~~',
));

//
// Class: SLT
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => 'Hizmet Seviyesi Taahütler',
	'Class:SLT/Attribute:name' => 'Adı',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Öncelik',
	'Class:SLT/Attribute:priority+' => '~~',
	'Class:SLT/Attribute:priority/Value:1' => 'kritik',
	'Class:SLT/Attribute:priority/Value:1+' => 'kritik',
	'Class:SLT/Attribute:priority/Value:2' => 'yüksek',
	'Class:SLT/Attribute:priority/Value:2+' => 'yüksek',
	'Class:SLT/Attribute:priority/Value:3' => 'orta',
	'Class:SLT/Attribute:priority/Value:3+' => 'orta',
	'Class:SLT/Attribute:priority/Value:4' => 'düşük',
	'Class:SLT/Attribute:priority/Value:4+' => 'düşük',
	'Class:SLT/Attribute:request_type' => 'İstek türü',
	'Class:SLT/Attribute:request_type+' => '~~',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Olay',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'Olay',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Servis İsteği',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'Servis İsteği',
	'Class:SLT/Attribute:metric' => 'Metrik',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR',
	'Class:SLT/Attribute:value' => 'Değer',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Birim',
	'Class:SLT/Attribute:unit+' => '~~',
	'Class:SLT/Attribute:unit/Value:hours' => 'saatler',
	'Class:SLT/Attribute:unit/Value:hours+' => 'saatler',
	'Class:SLT/Attribute:unit/Value:minutes' => 'dakikalar',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'dakikalar',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkSLAToSLT' => 'SLA / SLT bağla',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA Adı',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT Adı',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'Slt metric~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'Slt request type~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'Slt ticket priority~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'Slt value~~',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'Slt value unit~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '~~',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkCustomerContractToService' => 'Müşteri Sözleşmesi / Servis bağla',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Müşteri Sözleşmesi',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Müşteri Sözleşmesi Adı',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Servis',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Servis Adı',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA Adı',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '~~',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkProviderContractToService' => 'Sağlayıcı Sözleşmesi / Servis bağla',
	'Class:lnkProviderContractToService+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Servis',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Servis Adı',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Sağlayıcı Sözleşmesi',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Sağlayıcı Sözleşme Adı',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '~~',
));

//
// Class: DeliveryModel
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DeliveryModel' => 'Teslimat Modeli',
	'Class:DeliveryModel+' => '~~',
	'Class:DeliveryModel/Attribute:name' => 'İsim',
	'Class:DeliveryModel/Attribute:name+' => '~~',
	'Class:DeliveryModel/Attribute:org_id' => 'Organizasyon',
	'Class:DeliveryModel/Attribute:org_id+' => '~~',
	'Class:DeliveryModel/Attribute:organization_name' => 'Organizasyon Adı',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Ortak Adı',
	'Class:DeliveryModel/Attribute:description' => 'Açıklama',
	'Class:DeliveryModel/Attribute:description+' => '~~',
	'Class:DeliveryModel/Attribute:contacts_list' => 'İletişim',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Bu teslimat modeli için tüm temaslar (birimler ve kişi)',
	'Class:DeliveryModel/Attribute:customers_list' => 'Müşteriler',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Bu teslimat modeline sahip tüm müşteriler',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDeliveryModelToContact' => 'Teslimatı Modeli / Kişi bağla',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Teslimat Modeli',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Teslimat Modeli Adı',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kişiler',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Kişi Adı',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Rol',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Rol Adı',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '~~',
));
