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
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkFunctionalCIToTicket' => 'Link CI / Solicitação',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Solicitação',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Título do bilhete',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CIs',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Adicionado manualmente',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Computado',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Não impactado',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Link CI / Contrato provedor(a)',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Contrato provedor(a)',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Nome contrato provedor(a)',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CIs',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:lnkFunctionalCIToService' => 'Link CI / Serviço',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Serviço',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Nome serviço',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CIs',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Contrato provedor(a)',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Todos os contratos para esse item de configuração',
	'Class:FunctionalCI/Attribute:services_list' => 'Serviços',
	'Class:FunctionalCI/Attribute:services_list+' => 'Todos os serviços impactados por esse item de configuração',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Solicitações',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Todos as solicitações para este item de configuração',
));
