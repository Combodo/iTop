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
* @author       Benjamin Planque <benjamin.planque@combodo.com>
* @copyright   Copyright (C) 2010-2023 Combodo SARL
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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkFunctionalCIToTicket' => 'Link FunctionalCI / Ticket~~',
	'Class:lnkFunctionalCIToTicket/Name' => '%1$s / %2$s~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket~~',
 	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Rif',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Titolo del Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Nome CI',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impatto (testo',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impatto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Aggiunto manualmente',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Calcolato',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Non impattato',
));

//
// Class: FunctionalCI
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:FunctionalCI/Attribute:tickets_list' => 'Tickets~~',
	'Class:FunctionalCI/Attribute:tickets_list+' => 'Tutti i ticket per questo elemento di configurazione',
));
