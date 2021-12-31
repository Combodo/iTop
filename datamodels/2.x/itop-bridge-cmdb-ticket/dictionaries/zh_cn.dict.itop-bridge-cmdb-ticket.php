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
* @author       Benjamin Planque <benjamin.planque@combodo.com>
* @copyright   Copyright (C) 2010-2021 Combodo SARL
* @license     http://opensource.org/licenses/AGPL-3.0
*/
//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
//
// Class: lnkFunctionalCIToTicket
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToTicket' => '关联 功能配置项/工单',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => '工单',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => '工单编号',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => '工单标题',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => '配置项',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => '配置项名称',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => '影响 (文本)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => '影响',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => '手动添加',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => '自动添加',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => '不通知',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToProviderContract' => '关联 功能配置项/供应商合同',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => '供应商合同',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => '供应商合同名称',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => '配置项',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => '配置项名称',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkFunctionalCIToService' => '关联 功能配置项/服务',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => '服务',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => '服务名称',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => '配置项',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => '配置项名称',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => '供应商合同',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => '该配置项的所有供应商合同',
	'Class:FunctionalCI/Attribute:services_list' => '服务',
	'Class:FunctionalCI/Attribute:services_list+' => '该配置项影响的所有服务',
	'Class:FunctionalCI/Attribute:tickets_list' => '工单',
	'Class:FunctionalCI/Attribute:tickets_list+' => '该配置项包含的所有工单',
));
