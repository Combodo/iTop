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

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkContactToFunctionalCI' => 'Verknüpfung Kontakt/FunctionalCI',
	'Class:lnkContactToFunctionalCI+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id' => 'FunctionalCI',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name' => 'FunctionalCI-Name',
	'Class:lnkContactToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToFunctionalCI/Attribute:contact_id+' => '',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name' => 'Kontakt-Name',
	'Class:lnkContactToFunctionalCI/Attribute:contact_name+' => '',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkFunctionalCIToTicket' => 'Verknüpfung FunctionalCI/Ticket',
	'Class:lnkFunctionalCIToTicket+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Referenz',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Tickettitel',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI-Name',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Auswirkung (Text)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Auswirkung',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Manuell hinzugefügt',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Berechnet',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Nicht betroffen',
));