<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:ServiceManagement' => 'Szolgáltatáskezelés',
	'Menu:ServiceManagement+' => '',
	'Menu:Service:Overview' => 'Áttekintő',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => 'Szerződések szolgáltatás szintenként',
	'UI-ServiceManagementMenu-ContractsByStatus' => 'Szerződések állapotuk szerint',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '30 napon belül lejáró szerződések',
	'Menu:ProviderContract' => 'Szállítói szerződés',
	'Menu:ProviderContract+' => '',
	'Menu:CustomerContract' => 'Ügyfélszerződés',
	'Menu:CustomerContract+' => '',
	'Menu:ServiceSubcategory' => 'Szolgáltatás alkategóriák',
	'Menu:ServiceSubcategory+' => '',
	'Menu:Service' => 'Szolgáltatások',
	'Menu:Service+' => '',
	'Menu:ServiceElement' => 'Szolgáltatás elemek',
	'Menu:ServiceElement+' => 'Sevice elements~~',
	'Menu:SLA' => 'SLA-k',
	'Menu:SLA+' => '',
	'Menu:SLT' => 'SLT-k',
	'Menu:SLT+' => '',
	'Menu:DeliveryModel' => 'Teljesítési modellek',
	'Menu:DeliveryModel+' => 'Delivery models~~',
	'Menu:ServiceFamily' => 'Szolgáltatáscsaládok',
	'Menu:ServiceFamily+' => 'Service families~~',
	'Menu:Procedure' => 'Eljáráskatalógus',
	'Menu:Procedure+' => 'All procedures catalog~~',
	'Contract:baseinfo' => 'General information~~',
	'Contract:moreinfo' => 'Contractual information~~',
	'Contract:cost' => 'Cost information~~',
));

//
// Class: Organization
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Organization/Attribute:deliverymodel_id' => 'Teljesítési modell',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Teljesítési modell név',
));


//
// Class: ContractType
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ContractType' => 'Szerződés típus',
	'Class:ContractType+' => '~~',
));

//
// Class: Contract
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Contract' => 'Szerződés',
	'Class:Contract+' => '',
	'Class:Contract/Attribute:name' => 'Név',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => 'Ügyfél',
	'Class:Contract/Attribute:org_id+' => '~~',
	'Class:Contract/Attribute:organization_name' => 'Ügyfél név',
	'Class:Contract/Attribute:organization_name+' => 'Általános név',
	'Class:Contract/Attribute:contacts_list' => 'Kapcsolattartók',
	'Class:Contract/Attribute:contacts_list+' => 'Az ügyfélszerződés kapcsolattartói',
	'Class:Contract/Attribute:documents_list' => 'Dokumentumok',
	'Class:Contract/Attribute:documents_list+' => 'Az ügyfélszerződés dokumentumai',
	'Class:Contract/Attribute:description' => 'Leírás',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => 'Érvényesség kezdete',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => 'Érvényesség vége',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => 'Költség',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => 'Költség pénznem',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => 'Forint',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => 'Euró',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => 'Szerződés típus',
	'Class:Contract/Attribute:contracttype_id+' => '~~',
	'Class:Contract/Attribute:contracttype_name' => 'Szerződés típus név',
	'Class:Contract/Attribute:contracttype_name+' => '~~',
	'Class:Contract/Attribute:billing_frequency' => 'Számlázási gyakoriság',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => 'Költség egység',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => 'Szolgáltató',
	'Class:Contract/Attribute:provider_id+' => '~~',
	'Class:Contract/Attribute:provider_name' => 'Szolgáltató név',
	'Class:Contract/Attribute:provider_name+' => 'Általános név',
	'Class:Contract/Attribute:status' => 'Állapot',
	'Class:Contract/Attribute:status+' => '~~',
	'Class:Contract/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:Contract/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Contract/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:Contract/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:Contract/Attribute:status/Value:production' => 'Használatban',
	'Class:Contract/Attribute:status/Value:production+' => 'használatban',
	'Class:Contract/Attribute:finalclass' => 'Típus',
	'Class:Contract/Attribute:finalclass+' => '',
));
//
// Class: CustomerContract
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:CustomerContract' => 'Ügyfél kapcsolattartó',
	'Class:CustomerContract+' => '',
	'Class:CustomerContract/Attribute:services_list' => 'Szolgáltatások',
	'Class:CustomerContract/Attribute:services_list+' => 'Szolgáltatások melyek be lettek szerezve ennek a kapcsolattartónak',
));

//
// Class: ProviderContract
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ProviderContract' => 'Szolgáltatói szerződés',
	'Class:ProviderContract+' => '',
	'Class:ProviderContract/Attribute:functionalcis_list' => 'CI-k',
	'Class:ProviderContract/Attribute:functionalcis_list+' => 'Konfigurációs elemek, melyeket lefed ez a szolgáltatói szerződés',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => '',
	'Class:ProviderContract/Attribute:coverage' => 'Szolgáltatási időtartam',
	'Class:ProviderContract/Attribute:coverage+' => '',
	'Class:ProviderContract/Attribute:contracttype_id' => 'Szerződés típus',
	'Class:ProviderContract/Attribute:contracttype_id+' => '~~',
	'Class:ProviderContract/Attribute:contracttype_name' => 'Szerződés típus név',
	'Class:ProviderContract/Attribute:contracttype_name+' => '~~',
));

//
// Class: lnkContactToContract
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkContactToContract' => 'Kapcsolattartó / Szerződés',
	'Class:lnkContactToContract+' => '~~',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToContract/Attribute:contract_id' => 'Szerződés',
	'Class:lnkContactToContract/Attribute:contract_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contract_name' => 'Szerződés név',
	'Class:lnkContactToContract/Attribute:contract_name+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_id' => 'Kapcsolattartó',
	'Class:lnkContactToContract/Attribute:contact_id+' => '~~',
	'Class:lnkContactToContract/Attribute:contact_name' => 'Kapcsolattartó név',
	'Class:lnkContactToContract/Attribute:contact_name+' => '~~',
));

//
// Class: lnkContractToDocument
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkContractToDocument' => 'Szerződés / Dokumentum',
	'Class:lnkContractToDocument+' => '~~',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s~~',
	'Class:lnkContractToDocument/Attribute:contract_id' => 'Szerződés',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:contract_name' => 'Szerződés név',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_id' => 'Dokumentum',
	'Class:lnkContractToDocument/Attribute:document_id+' => '~~',
	'Class:lnkContractToDocument/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkContractToDocument/Attribute:document_name+' => '~~',
));

//
// Class: ServiceFamily
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ServiceFamily' => 'Szolgáltatáscsalád',
	'Class:ServiceFamily+' => '~~',
	'Class:ServiceFamily/Attribute:name' => 'Név',
	'Class:ServiceFamily/Attribute:name+' => '~~',
	'Class:ServiceFamily/Attribute:icon' => 'Ikon',
	'Class:ServiceFamily/Attribute:icon+' => '~~',
	'Class:ServiceFamily/Attribute:services_list' => 'Szolgáltatások',
	'Class:ServiceFamily/Attribute:services_list+' => 'Szolgáltatások ebben a kategóriában',
));

//
// Class: Service
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Service' => 'Szolgáltatás',
	'Class:Service+' => '',
	'Class:Service/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Service/Attribute:name' => 'Név',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => 'Szolgáltató szervezeti egység',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => 'Szolgáltató név',
	'Class:Service/Attribute:organization_name+' => '~~',
	'Class:Service/Attribute:servicefamily_id' => 'Szolgáltatáscsalád',
	'Class:Service/Attribute:servicefamily_id+' => '~~',
	'Class:Service/Attribute:servicefamily_name' => 'Szolgáltatáscsalád név',
	'Class:Service/Attribute:servicefamily_name+' => '~~',
	'Class:Service/Attribute:description' => 'Leírás',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => 'Dokumentumok',
	'Class:Service/Attribute:documents_list+' => 'A szolgáltatás dokumentumai',
	'Class:Service/Attribute:contacts_list' => 'Kapcsolattartók',
	'Class:Service/Attribute:contacts_list+' => 'A szolgáltatás kapcsolattartói',
	'Class:Service/Attribute:status' => 'Állapot',
	'Class:Service/Attribute:status+' => '',
	'Class:Service/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:Service/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:Service/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => 'Használatban',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => 'Ikon',
	'Class:Service/Attribute:icon+' => '~~',
	'Class:Service/Attribute:customercontracts_list' => 'Ügyfélszerződések',
	'Class:Service/Attribute:customercontracts_list+' => 'Ügyfélszerződések, melyek beszerezték ezt a szolgáltatást',
	'Class:Service/Attribute:providercontracts_list' => 'Szolgáltatói szerződések',
	'Class:Service/Attribute:providercontracts_list+' => 'Szolgáltatói szerződések, melyek támogatják ezt a szerződést',
	'Class:Service/Attribute:functionalcis_list' => 'CI függőség',
	'Class:Service/Attribute:functionalcis_list+' => 'Konfigurációs elemek, melyek ehhez a szolgáltatáshoz kellenek',
	'Class:Service/Attribute:servicesubcategories_list' => 'Szolgáltatás alkategóriák',
	'Class:Service/Attribute:servicesubcategories_list+' => 'A szolgáltatás alkategóriái',
));

//
// Class: lnkDocumentToService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToService' => 'Dokumentum / Szolgáltatás',
	'Class:lnkDocumentToService+' => '~~',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToService/Attribute:service_id' => 'Szolgáltatás',
	'Class:lnkDocumentToService/Attribute:service_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:service_name' => 'Szolgáltatás név',
	'Class:lnkDocumentToService/Attribute:service_name+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToService/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToService/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToService/Attribute:document_name+' => '~~',
));

//
// Class: lnkContactToService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkContactToService' => 'Kapcsolattartó / Szolgáltatás',
	'Class:lnkContactToService+' => '~~',
	'Class:lnkContactToService/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToService/Attribute:service_id' => 'Szolgáltatás',
	'Class:lnkContactToService/Attribute:service_id+' => '~~',
	'Class:lnkContactToService/Attribute:service_name' => 'Szolgáltatás név',
	'Class:lnkContactToService/Attribute:service_name+' => '~~',
	'Class:lnkContactToService/Attribute:contact_id' => 'Kapcsolattartó',
	'Class:lnkContactToService/Attribute:contact_id+' => '~~',
	'Class:lnkContactToService/Attribute:contact_name' => 'Kapcsolattartó név',
	'Class:lnkContactToService/Attribute:contact_name+' => '~~',
));

//
// Class: ServiceSubcategory
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:ServiceSubcategory' => 'Szolgáltatás alkategória',
	'Class:ServiceSubcategory+' => '',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s~~',
	'Class:ServiceSubcategory/Attribute:name' => 'Név',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => 'Leírás',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => 'Szolgáltatás',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => 'Szolgáltatás',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => 'Kérelem típus',
	'Class:ServiceSubcategory/Attribute:request_type+' => '~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => 'Incidens',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => 'Szolgáltatás kérelem',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:ServiceSubcategory/Attribute:status' => 'Állapot',
	'Class:ServiceSubcategory/Attribute:status+' => '~~',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => 'Megvalósítás alatt',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => 'implementation~~',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => 'Elavult',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => 'obsolete~~',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => 'Használatban',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => 'production~~',
));

//
// Class: SLA
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '',
	'Class:SLA/Attribute:name' => 'Név',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => 'Leírás',
	'Class:SLA/Attribute:description+' => '~~',
	'Class:SLA/Attribute:org_id' => 'Szolgáltató szervezeti egység',
	'Class:SLA/Attribute:org_id+' => '~~',
	'Class:SLA/Attribute:organization_name' => 'Szolgáltató név',
	'Class:SLA/Attribute:organization_name+' => 'Általános név',
	'Class:SLA/Attribute:slts_list' => 'SLT-k',
	'Class:SLA/Attribute:slts_list+' => 'All the service level targets for this SLA~~',
	'Class:SLA/Attribute:customercontracts_list' => 'Ügyfélszerződések',
	'Class:SLA/Attribute:customercontracts_list+' => 'Ügyfélszerződések, melyek használják ezt az SLA-t',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => 'Nem sikerült elmenteni a linket az Ügyfél szerződés %1$s és szolgáltatás %2$s : SLA már létezik',
));

//
// Class: SLT
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '',
	'Class:SLT/Attribute:name' => 'Név',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => 'Prioritás',
	'Class:SLT/Attribute:priority+' => '~~',
	'Class:SLT/Attribute:priority/Value:1' => 'Kritikus',
	'Class:SLT/Attribute:priority/Value:1+' => 'critical~~',
	'Class:SLT/Attribute:priority/Value:2' => 'Magas',
	'Class:SLT/Attribute:priority/Value:2+' => 'high~~',
	'Class:SLT/Attribute:priority/Value:3' => 'Közepes',
	'Class:SLT/Attribute:priority/Value:3+' => 'medium~~',
	'Class:SLT/Attribute:priority/Value:4' => 'Alacsony',
	'Class:SLT/Attribute:priority/Value:4+' => 'low~~',
	'Class:SLT/Attribute:request_type' => 'Kérelem típus',
	'Class:SLT/Attribute:request_type+' => '~~',
	'Class:SLT/Attribute:request_type/Value:incident' => 'Incidens',
	'Class:SLT/Attribute:request_type/Value:incident+' => 'incident~~',
	'Class:SLT/Attribute:request_type/Value:service_request' => 'Szolgáltatás kérelem',
	'Class:SLT/Attribute:request_type/Value:service_request+' => 'service request~~',
	'Class:SLT/Attribute:metric' => 'Metrika',
	'Class:SLT/Attribute:metric+' => '',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => 'TTO~~',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => 'TTR~~',
	'Class:SLT/Attribute:value' => 'Érték',
	'Class:SLT/Attribute:value+' => '',
	'Class:SLT/Attribute:unit' => 'Egység',
	'Class:SLT/Attribute:unit+' => '~~',
	'Class:SLT/Attribute:unit/Value:hours' => 'óra',
	'Class:SLT/Attribute:unit/Value:hours+' => 'hours~~',
	'Class:SLT/Attribute:unit/Value:minutes' => 'perc',
	'Class:SLT/Attribute:unit/Value:minutes+' => 'minutes~~',
	'Class:SLT/Attribute:slas_list' => 'SLAs~~',
	'Class:SLT/Attribute:slas_list+' => 'All the service level agreements using this SLT~~',
));

//
// Class: lnkSLAToSLT
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkSLAToSLT' => 'SLA / SLT',
	'Class:lnkSLAToSLT+' => '~~',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s~~',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA név',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT név',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'SLT metrika',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'SLT kérelem típus',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'SLT hibajegy prioritás',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'SLT érték',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '~~',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'SLT érték egység',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '~~',
));

//
// Class: lnkCustomerContractToService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkCustomerContractToService' => 'Ügyfélszerződés / Szolgáltatás',
	'Class:lnkCustomerContractToService+' => '~~',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => 'Ügyfélszerződés',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => 'Ügyfélszerződés név',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_id' => 'Szolgáltatás',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:service_name' => 'Szolgáltatás név',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '~~',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA név',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '~~',
));

//
// Class: lnkProviderContractToService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkProviderContractToService' => 'Szolgáltatói szerződés / Szolgáltatás',
	'Class:lnkProviderContractToService+' => '~~',
	'Class:lnkProviderContractToService/Name' => '%1$s / %2$s~~',
	'Class:lnkProviderContractToService/Attribute:service_id' => 'Szolgáltatás',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:service_name' => 'Szolgáltatás név',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => 'Szolgáltatói szerződés',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '~~',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => 'Szolgáltatói szerződés név',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '~~',
));

//
// Class: DeliveryModel
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:DeliveryModel' => 'Teljesítési modell',
	'Class:DeliveryModel+' => '~~',
	'Class:DeliveryModel/Attribute:name' => 'Név',
	'Class:DeliveryModel/Attribute:name+' => '~~',
	'Class:DeliveryModel/Attribute:org_id' => 'Szervezeti egység',
	'Class:DeliveryModel/Attribute:org_id+' => '~~',
	'Class:DeliveryModel/Attribute:organization_name' => 'Szervezeti egység név',
	'Class:DeliveryModel/Attribute:organization_name+' => 'Általános név',
	'Class:DeliveryModel/Attribute:description' => 'Leírás',
	'Class:DeliveryModel/Attribute:description+' => '~~',
	'Class:DeliveryModel/Attribute:contacts_list' => 'Kapcsolattartók',
	'Class:DeliveryModel/Attribute:contacts_list+' => 'Kapcsolattartók (csapat és személy) ehhez a teljesítési modellhez',
	'Class:DeliveryModel/Attribute:customers_list' => 'Ügyfelek',
	'Class:DeliveryModel/Attribute:customers_list+' => 'Ügyfelek, melyek rendelkeznek ezzel a teljesítési modellel',
));

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDeliveryModelToContact' => 'Teljesítési modell / Kapcsolattartó',
	'Class:lnkDeliveryModelToContact+' => '~~',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => 'Teljesítési modell',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => 'Teljesítési modell név',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => 'Kapcsolattartó',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => 'Kapcsolattartó név',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => 'Beosztás',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '~~',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => 'Beosztás név',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '~~',
));
