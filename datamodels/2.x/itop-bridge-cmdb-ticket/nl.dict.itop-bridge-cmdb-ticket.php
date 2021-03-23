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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToTicket' => 'Link Functioneel CI / Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referentie',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Titel ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impact',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impact',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Manueel toegevoegd',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Automatisch afgeleid',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Niet geïmpacteerd',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToProviderContract' => 'Link Functioneel CI / Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Naam leverancierscontract',
	'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkFunctionalCIToService' => 'Link Functioneel CI / Service',
	'Class:lnkFunctionalCIToService+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Service',
	'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Naam service',
	'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FunctionalCI/Attribute:providercontracts_list' => 'Leverancierscontracten',
	'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Alle leverancierscontracten voor dit configuratie-item',
	'Class:FunctionalCI/Attribute:services_list' => 'Services',
	'Class:FunctionalCI/Attribute:services_list+' => 'Alle services die impact hebben op dit configuratie-item',
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Alle tickets voor dit configuratie-item',
));
