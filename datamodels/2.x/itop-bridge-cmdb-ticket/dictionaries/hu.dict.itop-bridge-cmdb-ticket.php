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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:lnkFunctionalCIToTicket' => 'Funkcionális CI / Hibajegy',
    'Class:lnkFunctionalCIToTicket+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Hibajegy',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referenciaszám',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Hibajegy tárgya',
    'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI név',
    'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Hatása (szöveg)',
    'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Hatás kód',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Kézzel hozzáadva',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Számított',
    'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Nincs hatása',
));

//
// Class: lnkFunctionalCIToProviderContract
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:lnkFunctionalCIToProviderContract' => 'Funkcionális CI / Szolgáltatói szerződés',
    'Class:lnkFunctionalCIToProviderContract+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id' => 'Szolgáltatói szerződés',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_id+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name' => 'Szolgáltatói szerződés név',
    'Class:lnkFunctionalCIToProviderContract/Attribute:providercontract_name+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id' => 'CI',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name' => 'CI név',
    'Class:lnkFunctionalCIToProviderContract/Attribute:functionalci_name+' => '',
));

//
// Class: lnkFunctionalCIToService
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:lnkFunctionalCIToService' => 'Funkcionális CI / Szolgáltatás',
    'Class:lnkFunctionalCIToService+' => '',
    'Class:lnkFunctionalCIToService/Attribute:service_id' => 'Szolgáltatás',
    'Class:lnkFunctionalCIToService/Attribute:service_id+' => '',
    'Class:lnkFunctionalCIToService/Attribute:service_name' => 'Szolgáltatás név',
    'Class:lnkFunctionalCIToService/Attribute:service_name+' => '',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_id' => 'CI',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_id+' => '',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_name' => 'CI név',
    'Class:lnkFunctionalCIToService/Attribute:functionalci_name+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:FunctionalCI/Attribute:providercontracts_list' => 'Szolgáltatói szerződések',
    'Class:FunctionalCI/Attribute:providercontracts_list+' => 'Ehhez a konfigurációs elemhez tartozó beszállítói szerződések',
    'Class:FunctionalCI/Attribute:services_list' => 'Szolgáltatások',
    'Class:FunctionalCI/Attribute:services_list+' => 'Szolgáltatások amelyekre hatással vannak ez a konfigurációs elem',
    'Class:FunctionalCI/Attribute:tickets_list' => 'Hibajegyek',
    'Class:FunctionalCI/Attribute:tickets_list+' => 'Hibajegyek ehhez a konfigurációs elemhez',
));
