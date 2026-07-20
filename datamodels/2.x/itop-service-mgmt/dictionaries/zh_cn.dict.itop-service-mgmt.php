<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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
// Menu, fieldsets, UI, messages translations
//
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Menu:ServiceManagement' => '服务管理',
	'Menu:ServiceManagement+' => '服务管理概况',
	'Menu:Service:Overview' => '概况',
	'Menu:Service:Overview+' => '',
	'UI-ServiceManagementMenu-ContractsBySrvLevel' => '合同 (按服务级别)',
	'UI-ServiceManagementMenu-ContractsByStatus' => '合同 (按状态)',
	'UI-ServiceManagementMenu-ContractsEndingIn30Days' => '未来30天内截止的合同',

	'Menu:ProviderContract' => '供应商合同',
	'Menu:ProviderContract+' => '为外部公司采购',
	'Menu:CustomerContract' => '客户合同',
	'Menu:CustomerContract+' => '谁购买服务',
	'Menu:ServiceSubcategory' => '子服务',
	'Menu:ServiceSubcategory+' => '服务架构中的最低层级',
	'Menu:Service' => '服务',
	'Menu:Service+' => '服务架构中的第二层级',
	'Menu:SLA' => 'SLA',
	'Menu:SLA+' => '服务级别协议',
	'Menu:SLT' => 'SLT',
	'Menu:SLT+' => '服务级别目标',
	'Menu:DeliveryModel' => '交付模式',
	'Menu:DeliveryModel+' => '处理工单的团队',
	'Menu:ServiceFamily' => '服务家族',
	'Menu:ServiceFamily+' => '服务架构的最高层级',
	'Menu:ServiceCatalog' => '服务清单',
	'Menu:ServiceCatalog+' => '定义可提供的服务',
	'UI-ServiceCatalogMenu-Title' => '服务清单',
	'UI-ServiceCatalogMenu-NotInPortal' => '在用户门户中不可见',
	'UI-ServiceCatalogMenu-OnlyProductionInPortal' => '只有生产状态的服务和子服务才会在用户门户中可见',
	'UI-ServiceCatalogMenu-UnusedService' => '未被任何客户使用的服务',
	'UI-ServiceCatalogMenu-ServiceWithoutFamilyNotInPortal' => '没有服务家族的服务在用户门户中不可见',
	'UI-ServiceCatalogMenu-SLTBySLA' => '统计 SLA/SLT',
	'UI-ServiceCatalogMenu-ContractByService' => '统计 服务/合同',
	'UI-ServiceCatalogMenu-ContractBySLA' => '统计 SLA/合同',

	'Menu:Procedure' => '流程清单',
	'Menu:Procedure+' => '所有流程清单',
	'Contract:baseinfo' => '基本信息',
	'Contract:moreinfo' => '合同信息',
	'Contract:cost' => '费用信息',
]);

//
// Class: Organization
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Organization/Attribute:deliverymodel_id' => '交付模式',
	'Class:Organization/Attribute:deliverymodel_id+' => '工单处理必备.
交付模式指定了工单可以分配到的团队.',
	'Class:Organization/Attribute:deliverymodel_name' => '交付模式名称',
]);

//
// Class: ContractType
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ContractType' => '合同类型',
	'Class:ContractType+' => '用于对客户和供应商合同进行分类.',
]);

//
// Class: Contract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Contract' => '合同',
	'Class:Contract+' => '用于处理不同合同类型的抽象类.',
	'Class:Contract/Attribute:name' => '名称',
	'Class:Contract/Attribute:name+' => '',
	'Class:Contract/Attribute:org_id' => '客户',
	'Class:Contract/Attribute:org_id+' => '',
	'Class:Contract/Attribute:organization_name' => '客户名称',
	'Class:Contract/Attribute:organization_name+' => '通用名称',
	'Class:Contract/Attribute:contacts_list' => '联系人',
	'Class:Contract/Attribute:contacts_list+' => '此客户合同相关的所有联系人',
	'Class:Contract/Attribute:documents_list' => '文档',
	'Class:Contract/Attribute:documents_list+' => '此客户合同相关的所有文档',
	'Class:Contract/Attribute:description' => '描述',
	'Class:Contract/Attribute:description+' => '',
	'Class:Contract/Attribute:start_date' => '开始日期',
	'Class:Contract/Attribute:start_date+' => '',
	'Class:Contract/Attribute:end_date' => '结束日期',
	'Class:Contract/Attribute:end_date+' => '',
	'Class:Contract/Attribute:cost' => '费用',
	'Class:Contract/Attribute:cost+' => '',
	'Class:Contract/Attribute:cost_currency' => '结算货币',
	'Class:Contract/Attribute:cost_currency+' => '',
	'Class:Contract/Attribute:cost_currency/Value:dollars' => '美元',
	'Class:Contract/Attribute:cost_currency/Value:dollars+' => '',
	'Class:Contract/Attribute:cost_currency/Value:euros' => '欧元',
	'Class:Contract/Attribute:cost_currency/Value:euros+' => '',
	'Class:Contract/Attribute:contracttype_id' => '合同类型',
	'Class:Contract/Attribute:contracttype_id+' => '',
	'Class:Contract/Attribute:contracttype_name' => '合同类型名称',
	'Class:Contract/Attribute:contracttype_name+' => '',
	'Class:Contract/Attribute:billing_frequency' => '付款周期',
	'Class:Contract/Attribute:billing_frequency+' => '',
	'Class:Contract/Attribute:cost_unit' => '计费单位',
	'Class:Contract/Attribute:cost_unit+' => '',
	'Class:Contract/Attribute:provider_id' => '供应商',
	'Class:Contract/Attribute:provider_id+' => '此合同的供应商组织, 可以与相关服务的供应商不同.',
	'Class:Contract/Attribute:provider_name' => '供应商名称',
	'Class:Contract/Attribute:provider_name+' => '通用名称',
	'Class:Contract/Attribute:status' => '状态',
	'Class:Contract/Attribute:status+' => '状态并非根据起止日期自动计算, 必须手动设置.',
	'Class:Contract/Attribute:status/Value:implementation' => '生效',
	'Class:Contract/Attribute:status/Value:implementation+' => '生效',
	'Class:Contract/Attribute:status/Value:obsolete' => '废弃',
	'Class:Contract/Attribute:status/Value:obsolete+' => '废弃',
	'Class:Contract/Attribute:status/Value:production' => '生产',
	'Class:Contract/Attribute:status/Value:production+' => '生产',
	'Class:Contract/Attribute:finalclass' => '合同子类型',
	'Class:Contract/Attribute:finalclass+' => '根本属性的名称',
]);
//
// Class: CustomerContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:CustomerContract' => '客户合同',
	'Class:CustomerContract+' => '客户与供应商之间关于服务交付的协议，可选择包含承诺服务级别 (SLA, 窗口时间).',
	'Class:CustomerContract/Attribute:services_list' => '服务',
	'Class:CustomerContract/Attribute:services_list+' => '此合同包含的所有服务',
]);

//
// Class: ProviderContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ProviderContract' => '供应商合同',
	'Class:ProviderContract+' => '外部供应商与内部组织之间的协议.',
	'Class:ProviderContract/Attribute:functionalcis_list' => '配置项',
	'Class:ProviderContract/Attribute:functionalcis_list+' => '此供应商合同包含的所有配置项',
	'Class:ProviderContract/Attribute:sla' => 'SLA',
	'Class:ProviderContract/Attribute:sla+' => '服务级别协议',
	'Class:ProviderContract/Attribute:coverage' => '服务时间',
	'Class:ProviderContract/Attribute:coverage+' => '合同覆盖的服务时间, 例如. 24x7, 9x5, 等.',
	'Class:ProviderContract/Attribute:contracttype_id' => '合同类型',
	'Class:ProviderContract/Attribute:contracttype_id+' => '',
	'Class:ProviderContract/Attribute:contracttype_name' => '合同类型名称',
	'Class:ProviderContract/Attribute:contracttype_name+' => '',
	'Class:ProviderContract/Attribute:services_list' => '服务',
	'Class:ProviderContract/Attribute:services_list+' => '此供应商合同包含的所有服务',
]);

//
// Class: lnkContactToContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContactToContract' => '链接 联系人/合同',
	'Class:lnkContactToContract+' => '管理每个客户或供应商合同的关键联系人.',
	'Class:lnkContactToContract/Name' => '%1$s / %2$s',
	'Class:lnkContactToContract/Attribute:contract_id' => '合同',
	'Class:lnkContactToContract/Attribute:contract_id+' => '',
	'Class:lnkContactToContract/Attribute:contract_name' => '合同名称',
	'Class:lnkContactToContract/Attribute:contract_name+' => '',
	'Class:lnkContactToContract/Attribute:contact_id' => '联系人',
	'Class:lnkContactToContract/Attribute:contact_id+' => '',
	'Class:lnkContactToContract/Attribute:contact_name' => '联系人名称',
	'Class:lnkContactToContract/Attribute:contact_name+' => '',
]);

//
// Class: lnkContractToDocument
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContractToDocument' => '链接 合同/文档',
	'Class:lnkContractToDocument+' => '此链接用于当某个文档适用于某个合同.',
	'Class:lnkContractToDocument/Name' => '%1$s / %2$s',
	'Class:lnkContractToDocument/Attribute:contract_id' => '合同',
	'Class:lnkContractToDocument/Attribute:contract_id+' => '',
	'Class:lnkContractToDocument/Attribute:contract_name' => '合同名称',
	'Class:lnkContractToDocument/Attribute:contract_name+' => '',
	'Class:lnkContractToDocument/Attribute:document_id' => '文档',
	'Class:lnkContractToDocument/Attribute:document_id+' => '',
	'Class:lnkContractToDocument/Attribute:document_name' => '文档名称',
	'Class:lnkContractToDocument/Attribute:document_name+' => '',
]);

//
// Class: ServiceFamily
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ServiceFamily' => '服务家族',
	'Class:ServiceFamily+' => '服务架构的最高层级.在用户门户中对外提供服务时需要.',
	'Class:ServiceFamily/Attribute:name' => '名称',
	'Class:ServiceFamily/Attribute:name+' => '',
	'Class:ServiceFamily/Attribute:icon' => '图标',
	'Class:ServiceFamily/Attribute:icon+' => '',
	'Class:ServiceFamily/Attribute:services_list' => '服务',
	'Class:ServiceFamily/Attribute:services_list+' => '所有的服务',
]);

//
// Class: Service
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:Service' => '服务',
	'Class:Service+' => '服务由组织提供并通过客户合同订阅.它必须包含至少一个子服务.',
	'Class:Service/ComplementaryName' => '%1$s - %2$s',
	'Class:Service/Attribute:name' => '名称',
	'Class:Service/Attribute:name+' => '',
	'Class:Service/Attribute:org_id' => '供应商',
	'Class:Service/Attribute:org_id+' => '',
	'Class:Service/Attribute:organization_name' => '供应商名称',
	'Class:Service/Attribute:organization_name+' => '',
	'Class:Service/Attribute:servicefamily_id' => '服务家族',
	'Class:Service/Attribute:servicefamily_id+' => '在用户门户中可见此服务所需的必要条件',
	'Class:Service/Attribute:servicefamily_name' => '服务家族名称',
	'Class:Service/Attribute:servicefamily_name+' => '',
	'Class:Service/Attribute:description' => '描述',
	'Class:Service/Attribute:description+' => '',
	'Class:Service/Attribute:documents_list' => '文档',
	'Class:Service/Attribute:documents_list+' => '此服务相关的所有文档',
	'Class:Service/Attribute:contacts_list' => '联系人',
	'Class:Service/Attribute:contacts_list+' => '此服务相关的所有联系人',
	'Class:Service/Attribute:status' => '状态',
	'Class:Service/Attribute:status+' => '默认情况下,只有生产状态的服务才会在用户门户中可见',
	'Class:Service/Attribute:status/Value:implementation' => '生效',
	'Class:Service/Attribute:status/Value:implementation+' => '生效',
	'Class:Service/Attribute:status/Value:obsolete' => '废弃',
	'Class:Service/Attribute:status/Value:obsolete+' => '',
	'Class:Service/Attribute:status/Value:production' => '生产',
	'Class:Service/Attribute:status/Value:production+' => '',
	'Class:Service/Attribute:icon' => '图标',
	'Class:Service/Attribute:icon+' => '',
	'Class:Service/Attribute:customercontracts_list' => '客户合同',
	'Class:Service/Attribute:customercontracts_list+' => '所有包含此服务的客户合同',
	'Class:Service/Attribute:providercontracts_list' => '供应商合同',
	'Class:Service/Attribute:providercontracts_list+' => '所有包含此服务的供应商合同',
	'Class:Service/Attribute:functionalcis_list' => '配置项',
	'Class:Service/Attribute:functionalcis_list+' => '提供此服务所需的所有配置项',
	'Class:Service/Attribute:servicesubcategories_list' => '子服务',
	'Class:Service/Attribute:servicesubcategories_list+' => '此服务的所有子服务',
]);

//
// Class: lnkDocumentToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDocumentToService' => '链接 文档/服务',
	'Class:lnkDocumentToService+' => 'Link used when a Document is applicable to a Service.~~',
	'Class:lnkDocumentToService/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToService/Attribute:service_id' => '服务',
	'Class:lnkDocumentToService/Attribute:service_id+' => '',
	'Class:lnkDocumentToService/Attribute:service_name' => '服务名称',
	'Class:lnkDocumentToService/Attribute:service_name+' => '',
	'Class:lnkDocumentToService/Attribute:document_id' => '文档',
	'Class:lnkDocumentToService/Attribute:document_id+' => '',
	'Class:lnkDocumentToService/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToService/Attribute:document_name+' => '',
]);

//
// Class: lnkContactToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContactToService' => '链接 联系人/服务',
	'Class:lnkContactToService+' => 'Ideal for defining the team to which Tickets created on the related Service will be assigned (automatically or manually).~~',
	'Class:lnkContactToService/Name' => '%1$s / %2$s',
	'Class:lnkContactToService/Attribute:service_id' => '服务',
	'Class:lnkContactToService/Attribute:service_id+' => '',
	'Class:lnkContactToService/Attribute:service_name' => '服务名称',
	'Class:lnkContactToService/Attribute:service_name+' => '',
	'Class:lnkContactToService/Attribute:contact_id' => '联系人',
	'Class:lnkContactToService/Attribute:contact_id+' => '',
	'Class:lnkContactToService/Attribute:contact_name' => '联系人名称',
	'Class:lnkContactToService/Attribute:contact_name+' => '',
]);

//
// Class: ServiceSubcategory
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:ServiceSubcategory' => '子服务',
	'Class:ServiceSubcategory+' => '服务架构的最低层级. 用户需求通常与某个子服务相关联.',
	'Class:ServiceSubcategory/ComplementaryName' => '%1$s - %2$s',
	'Class:ServiceSubcategory/Attribute:name' => '名称',
	'Class:ServiceSubcategory/Attribute:name+' => '',
	'Class:ServiceSubcategory/Attribute:description' => '描述',
	'Class:ServiceSubcategory/Attribute:description+' => '',
	'Class:ServiceSubcategory/Attribute:service_id' => '服务',
	'Class:ServiceSubcategory/Attribute:service_id+' => '',
	'Class:ServiceSubcategory/Attribute:service_name' => '服务名称',
	'Class:ServiceSubcategory/Attribute:service_name+' => '',
	'Class:ServiceSubcategory/Attribute:request_type' => '需求类型',
	'Class:ServiceSubcategory/Attribute:request_type+' => '定义工单类型(事件或服务需求)，当门户用户选择此服务子类时将创建的工单.',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident' => '事件',
	'Class:ServiceSubcategory/Attribute:request_type/Value:incident+' => '事件',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request' => '服务需求',
	'Class:ServiceSubcategory/Attribute:request_type/Value:service_request+' => '服务需求',
	'Class:ServiceSubcategory/Attribute:status' => '状态',
	'Class:ServiceSubcategory/Attribute:status+' => '子服务状态通常影响在用户门户的可见度',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation' => '生效',
	'Class:ServiceSubcategory/Attribute:status/Value:implementation+' => '生效',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete' => '废弃',
	'Class:ServiceSubcategory/Attribute:status/Value:obsolete+' => '废弃',
	'Class:ServiceSubcategory/Attribute:status/Value:production' => '生产',
	'Class:ServiceSubcategory/Attribute:status/Value:production+' => '生产',
]);

//
// Class: SLA
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SLA' => 'SLA',
	'Class:SLA+' => '服务级别协议(SLA)适用于客户订阅的服务，并通过 SLT 进行衡量和考核.',
	'Class:SLA/Attribute:name' => '名称',
	'Class:SLA/Attribute:name+' => '',
	'Class:SLA/Attribute:description' => '描述',
	'Class:SLA/Attribute:description+' => '',
	'Class:SLA/Attribute:org_id' => '供应商',
	'Class:SLA/Attribute:org_id+' => '',
	'Class:SLA/Attribute:organization_name' => '供应商名称',
	'Class:SLA/Attribute:organization_name+' => '通用名称',
	'Class:SLA/Attribute:slts_list' => 'SLT',
	'Class:SLA/Attribute:slts_list+' => '此SLA包含的所有服务级别目标',
	'Class:SLA/Attribute:customercontracts_list' => '客户合同',
	'Class:SLA/Attribute:customercontracts_list+' => '使用此SLA的所有客户合同',
	'Class:SLA/Error:UniqueLnkCustomerContractToService' => '无法保存客户合同%1$s与服务%2$s的链接: SLA已存在',
]);

//
// Class: SLT
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:SLT' => 'SLT',
	'Class:SLT+' => '服务级别目标(SLT)位于服务级别协议(SLA)之下. 它定义了(TTO 或 TTR)指标的最大时限, 需求类型 (事件或服务需求) 和优先级.',
	'Class:SLT/Attribute:name' => '名称',
	'Class:SLT/Attribute:name+' => '',
	'Class:SLT/Attribute:priority' => '优先级',
	'Class:SLT/Attribute:priority+' => '此 SLT 适用的工单优先级。仅有此优先级的工单需遵守此 SLT 的要求.',
	'Class:SLT/Attribute:priority/Value:1' => '紧急',
	'Class:SLT/Attribute:priority/Value:1+' => '紧急',
	'Class:SLT/Attribute:priority/Value:2' => '高',
	'Class:SLT/Attribute:priority/Value:2+' => '高',
	'Class:SLT/Attribute:priority/Value:3' => '中',
	'Class:SLT/Attribute:priority/Value:3+' => '中',
	'Class:SLT/Attribute:priority/Value:4' => '低',
	'Class:SLT/Attribute:priority/Value:4+' => '低',
	'Class:SLT/Attribute:request_type' => '需求类型',
	'Class:SLT/Attribute:request_type+' => '定义工单类型(事件或服务需求)，当门户用户选择此服务级别目标时将创建的工单.',
	'Class:SLT/Attribute:request_type/Value:incident' => '事件',
	'Class:SLT/Attribute:request_type/Value:incident+' => '事件',
	'Class:SLT/Attribute:request_type/Value:service_request' => '服务需求',
	'Class:SLT/Attribute:request_type/Value:service_request+' => '服务需求',
	'Class:SLT/Attribute:metric' => '衡量指标',
	'Class:SLT/Attribute:metric+' => '定义适用于此 SLT 的衡量指标, TTO (响应时间) 或 TTR (解决时限).',
	'Class:SLT/Attribute:metric/Value:tto' => 'TTO',
	'Class:SLT/Attribute:metric/Value:tto+' => '响应时间',
	'Class:SLT/Attribute:metric/Value:ttr' => 'TTR',
	'Class:SLT/Attribute:metric/Value:ttr+' => '解决时限',
	'Class:SLT/Attribute:value' => '值',
	'Class:SLT/Attribute:value+' => '定义符合目标要求的最大延迟值, 在 "度量单位" 属性中定义。',
	'Class:SLT/Attribute:unit' => '度量单位',
	'Class:SLT/Attribute:unit+' => '时间的单位',
	'Class:SLT/Attribute:unit/Value:hours' => '小时',
	'Class:SLT/Attribute:unit/Value:hours+' => '小时',
	'Class:SLT/Attribute:unit/Value:minutes' => '分钟',
	'Class:SLT/Attribute:unit/Value:minutes+' => '分钟',
	'Class:SLT/Attribute:slas_list' => 'SLA',
	'Class:SLT/Attribute:slas_list+' => '使用此 SLT 的所有 SLA',
]);

//
// Class: lnkSLAToSLT
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkSLAToSLT' => '链接 SLA/SLT',
	'Class:lnkSLAToSLT+' => 'This link indicates that an SLT is included in the Service Level Agreement (SLA). An SLA usually contains several SLTs. An SLT can be reused as is by several SLAs (seldom).~~',
	'Class:lnkSLAToSLT/Name' => '%1$s / %2$s',
	'Class:lnkSLAToSLT/Attribute:sla_id' => 'SLA',
	'Class:lnkSLAToSLT/Attribute:sla_id+' => '',
	'Class:lnkSLAToSLT/Attribute:sla_name' => 'SLA名称',
	'Class:lnkSLAToSLT/Attribute:sla_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_id' => 'SLT',
	'Class:lnkSLAToSLT/Attribute:slt_id+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_name' => 'SLT名称',
	'Class:lnkSLAToSLT/Attribute:slt_name+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_metric' => 'SLT指标',
	'Class:lnkSLAToSLT/Attribute:slt_metric+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_request_type' => 'SLT类别',
	'Class:lnkSLAToSLT/Attribute:slt_request_type+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority' => 'SLT工单优先级',
	'Class:lnkSLAToSLT/Attribute:slt_ticket_priority+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value' => 'SLT 值',
	'Class:lnkSLAToSLT/Attribute:slt_value+' => '',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit' => 'SLT 单位',
	'Class:lnkSLAToSLT/Attribute:slt_value_unit+' => '',
]);

//
// Class: lnkCustomerContractToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkCustomerContractToService' => '链接 客户合同/服务',
	'Class:lnkCustomerContractToService+' => 'A single line of a customer contract, specifying the Service provided and, for this service, the subscribed commitment levels (Service Level Aggrement and Coverage Window).~~',
	'Class:lnkCustomerContractToService/Name' => '%1$s / %2$s',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id' => '客户合同',
	'Class:lnkCustomerContractToService/Attribute:customercontract_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name' => '客户合同名称',
	'Class:lnkCustomerContractToService/Attribute:customercontract_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:service_id' => '服务',
	'Class:lnkCustomerContractToService/Attribute:service_id+' => '与该服务相关的所有子服务也均包含在本合同范围内',
	'Class:lnkCustomerContractToService/Attribute:service_name' => '服务名称',
	'Class:lnkCustomerContractToService/Attribute:service_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_id' => 'SLA',
	'Class:lnkCustomerContractToService/Attribute:sla_id+' => '',
	'Class:lnkCustomerContractToService/Attribute:sla_name' => 'SLA 名称',
	'Class:lnkCustomerContractToService/Attribute:sla_name+' => '',
	'Class:lnkCustomerContractToService/Attribute:provider_id' => '供应商id',
	'Class:lnkCustomerContractToService/Attribute:provider_id+' => '',
]);

//
// Class: lnkProviderContractToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkProviderContractToService' => '链接 供应商合同/服务',
	'Class:lnkProviderContractToService+' => 'This link can model that a provider contract enables the delivery of a Service.~~',
	'Class:lnkProviderContractToService/Name' => '%1$s / %2$s',
	'Class:lnkProviderContractToService/Attribute:service_id' => '服务',
	'Class:lnkProviderContractToService/Attribute:service_id+' => '',
	'Class:lnkProviderContractToService/Attribute:service_name' => '服务名称',
	'Class:lnkProviderContractToService/Attribute:service_name+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_id' => '供应商合同',
	'Class:lnkProviderContractToService/Attribute:providercontract_id+' => '',
	'Class:lnkProviderContractToService/Attribute:providercontract_name' => '供应商合同名称',
	'Class:lnkProviderContractToService/Attribute:providercontract_name+' => '',
	'Class:lnkProviderContractToService/Attribute:provider_id' => '供应商id',
	'Class:lnkProviderContractToService/Attribute:provider_id+' => '',
]);

//
// Class: DeliveryModel
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:DeliveryModel' => '交付模式',
	'Class:DeliveryModel+' => '交付模式指定了可以分配工单的团队;它必须在联系人选项卡中包含至少一个团队.
每个客户组织都必须有定义好的交付模式.',
	'Class:DeliveryModel/Attribute:name' => '名称',
	'Class:DeliveryModel/Attribute:name+' => '别忘了给这个交付模式添加团队',
	'Class:DeliveryModel/Attribute:org_id' => '组织',
	'Class:DeliveryModel/Attribute:org_id+' => '通常是提供服务的那个组织',
	'Class:DeliveryModel/Attribute:organization_name' => '组织名称',
	'Class:DeliveryModel/Attribute:organization_name+' => '通用名称',
	'Class:DeliveryModel/Attribute:description' => '描述',
	'Class:DeliveryModel/Attribute:description+' => '',
	'Class:DeliveryModel/Attribute:contacts_list' => '联系人',
	'Class:DeliveryModel/Attribute:contacts_list+' => '必须至少有一个团队才能进行工单分配',
	'Class:DeliveryModel/Attribute:customers_list' => '客户',
	'Class:DeliveryModel/Attribute:customers_list+' => '使用此交付模式的所有客户',
]);

//
// Class: lnkDeliveryModelToContact
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkDeliveryModelToContact' => '链接 交付模式/联系人',
	'Class:lnkDeliveryModelToContact+' => '此链接指定了团队 (较少是个体) 在交付模式中的角色.',
	'Class:lnkDeliveryModelToContact/Name' => '%1$s / %2$s',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id' => '交付模式',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name' => '交付模式名称',
	'Class:lnkDeliveryModelToContact/Attribute:deliverymodel_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id' => '联系人',
	'Class:lnkDeliveryModelToContact/Attribute:contact_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name' => '联系人名称',
	'Class:lnkDeliveryModelToContact/Attribute:contact_name+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_id' => '角色',
	'Class:lnkDeliveryModelToContact/Attribute:role_id+' => '',
	'Class:lnkDeliveryModelToContact/Attribute:role_name' => '角色名称',
	'Class:lnkDeliveryModelToContact/Attribute:role_name+' => '',
]);

//
// Class: lnkContactToContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContactToContract/Attribute:customer_id' => '客户id',
	'Class:lnkContactToContract/Attribute:customer_id+' => '',
	'Class:lnkContactToContract/Attribute:provider_id' => '供应商id',
	'Class:lnkContactToContract/Attribute:provider_id+' => '',
]);

//
// Class: lnkContractToDocument
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:lnkContractToDocument/Attribute:customer_id' => '客户id',
	'Class:lnkContractToDocument/Attribute:customer_id+' => '',
	'Class:lnkContractToDocument/Attribute:provider_id' => '供应商id',
	'Class:lnkContractToDocument/Attribute:provider_id+' => '',
]);
