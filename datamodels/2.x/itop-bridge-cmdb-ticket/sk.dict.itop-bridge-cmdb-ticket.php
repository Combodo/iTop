<?php
// Copyright (C) 2010-2015 Combodo SARL
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
* @copyright   Copyright (C) 2010-2018 Combodo SARL
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
// Class: lnkContactToFunctionalCI
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkContactToFunctionalCI' => 'väzba - Kontakt / Komponent',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'Názov funkčných CI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Názov kontaktu',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:lnkFunctionalCIToTicket' => 'väzba - Komponent / Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referencia na Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'Komponent',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Názov CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Dopad',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impact~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Added manually~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Computed~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Not impacted~~',
));