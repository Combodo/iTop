<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com> 
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
// Class: Ticket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => 'Ticket',
	'Class:Ticket/Attribute:ref' => 'Ref',
	'Class:Ticket/Attribute:ref+' => 'Ref',
	'Class:Ticket/Attribute:org_id' => 'Organización',
	'Class:Ticket/Attribute:org_id+' => 'Organización',
	'Class:Ticket/Attribute:org_name' => 'Organización',
	'Class:Ticket/Attribute:org_name+' => 'Organización',
	'Class:Ticket/Attribute:caller_id' => 'Reportado por',
	'Class:Ticket/Attribute:caller_id+' => 'Reportado por',
	'Class:Ticket/Attribute:caller_name' => 'Reportado por',
	'Class:Ticket/Attribute:caller_name+' => 'Reportado por',
	'Class:Ticket/Attribute:team_id' => 'Grupo',
	'Class:Ticket/Attribute:team_id+' => 'Grupo',
	'Class:Ticket/Attribute:team_name' => 'Grupo de Trabajo',
	'Class:Ticket/Attribute:team_name+' => 'Grupo de Trabajo',
	'Class:Ticket/Attribute:agent_id' => 'Analista',
	'Class:Ticket/Attribute:agent_id+' => 'Analista',
	'Class:Ticket/Attribute:agent_name' => 'Analista',
	'Class:Ticket/Attribute:agent_name+' => 'Analista',
	'Class:Ticket/Attribute:title' => 'Asunto',
	'Class:Ticket/Attribute:title+' => 'Asunto',
	'Class:Ticket/Attribute:description' => 'Descripción',
	'Class:Ticket/Attribute:description+' => 'Descripción',
	'Class:Ticket/Attribute:start_date' => 'Fecha de Inicio',
	'Class:Ticket/Attribute:start_date+' => 'Fecha de Inicio',
	'Class:Ticket/Attribute:end_date' => 'Fecha de Fin',
	'Class:Ticket/Attribute:end_date+' => 'Fecha de Fin',
	'Class:Ticket/Attribute:last_update' => 'Última Actualización',
	'Class:Ticket/Attribute:last_update+' => 'Última Actualización',
	'Class:Ticket/Attribute:close_date' => 'Fecha de Cierre',
	'Class:Ticket/Attribute:close_date+' => 'Fecha de Cierre',
	'Class:Ticket/Attribute:private_log' => 'Bitácora Privada',
	'Class:Ticket/Attribute:private_log+' => 'Bitácora Privada',
	'Class:Ticket/Attribute:contacts_list' => 'Contactos',
	'Class:Ticket/Attribute:contacts_list+' => 'Contactos',
	'Class:Ticket/Attribute:functionalcis_list' => 'ECs',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Elementos de Configuración',
	'Class:Ticket/Attribute:workorders_list' => 'Ordenes de Trabajo',
	'Class:Ticket/Attribute:workorders_list+' => 'Ordenes de Trabajo',
	'Class:Ticket/Attribute:finalclass' => 'Clase',
	'Class:Ticket/Attribute:finalclass+' => 'Clase',
));


//
// Class: lnkContactToTicket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkContactToTicket' => 'Relación Contacto y Ticket',
	'Class:lnkContactToTicket+' => 'Relación Contacto y Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => 'Ref',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contacto',
	'Class:lnkContactToTicket/Attribute:contact_id+' => 'Contacto',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Correo Electrónico',
	'Class:lnkContactToTicket/Attribute:contact_email+' => 'Correo Electrónico',
	'Class:lnkContactToTicket/Attribute:role' => 'Rol',
	'Class:lnkContactToTicket/Attribute:role+' => 'Rol',
));

//
// Class: lnkFunctionalCIToTicket
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:lnkFunctionalCIToTicket' => 'Relación EC Funcional y Ticket',
	'Class:lnkFunctionalCIToTicket+' => 'Relación EC Funcional y Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => 'Ticket',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Ref.',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => 'Ref.',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'EC',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => 'Elemanto de Configuración',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Impacto',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => 'Impacto',
));


//
// Class: WorkOrder
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:WorkOrder' => 'Orden de Trabajo',
	'Class:WorkOrder+' => 'Orden de Trabajo',
	'Class:WorkOrder/Attribute:name' => 'Nombre',
	'Class:WorkOrder/Attribute:name+' => 'Nombre de la Orden de Trabajo',
	'Class:WorkOrder/Attribute:status' => 'Estatus',
	'Class:WorkOrder/Attribute:status+' => 'Estatus',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Abierto',
	'Class:WorkOrder/Attribute:status/Value:open+' => 'Abierto',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Cerrado',
	'Class:WorkOrder/Attribute:status/Value:closed+' => 'Cerrado',
	'Class:WorkOrder/Attribute:description' => 'Descripción',
	'Class:WorkOrder/Attribute:description+' => 'Descripción',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Ref. Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => 'Ref. Ticket',
	'Class:WorkOrder/Attribute:team_id' => 'Grupo',
	'Class:WorkOrder/Attribute:team_id+' => 'Grupo',
	'Class:WorkOrder/Attribute:team_name' => 'Grupo de Trabajo',
	'Class:WorkOrder/Attribute:team_name+' => 'Grupo de Trabajo',
	'Class:WorkOrder/Attribute:agent_id' => 'Analista',
	'Class:WorkOrder/Attribute:agent_id+' => 'Analista',
	'Class:WorkOrder/Attribute:agent_email' => 'Correo Electrónico del Analista',
	'Class:WorkOrder/Attribute:agent_email+' => 'Correo Electrónico del Analista',
	'Class:WorkOrder/Attribute:start_date' => 'Fecha de Inicio',
	'Class:WorkOrder/Attribute:start_date+' => 'Fecha de Inicio',
	'Class:WorkOrder/Attribute:end_date' => 'Fecha de Fin',
	'Class:WorkOrder/Attribute:end_date+' => 'Fecha de Fin',
	'Class:WorkOrder/Attribute:log' => 'Bitácora',
	'Class:WorkOrder/Attribute:log+' => 'Bitácora',
	'Class:WorkOrder/Stimulus:ev_close' => 'Cerrar',
	'Class:WorkOrder/Stimulus:ev_close+' => 'Cerrar',
));


// Fieldset translation
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(

	'Ticket:baseinfo' => 'Información General',
	'Ticket:date' => 'Fechas',
	'Ticket:contact' => 'Contactos',
	'Ticket:moreinfo' => 'Más Información',
	'Ticket:relation' => 'Relaciones',
	'Ticket:log' => 'Comunicaciones',
	'Ticket:Type' => 'Clasificación',
	'Ticket:support' => 'Soporte',
	'Ticket:resolution' => 'Solución',
	'Ticket:SLA' => 'Reporte de SLA',
	'WorkOrder:Details' => 'Detalles',
	'WorkOrder:Moreinfo' => 'Más Información',

	'Ticket:ImpactAnalysis' => 'Impact Analysis~~',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Role~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Added manually~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Computed~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Do not notify~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Impact~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Added manually~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Computed~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Not impacted~~',
	'Tickets:ResolvedFrom' => 'Automatically resolved from %1$s~~',
	'Class:cmdbAbstractObject/Method:Set' => 'Set~~',
	'Class:cmdbAbstractObject/Method:Set+' => 'Set a field with a static value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'SetCurrentDate~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Set a field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'SetCurrentUser~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Set a field with the currently logged in user~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'SetCurrentPerson~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Set a field with the currently logged in person (the \"person\" attached to the logged in \"user\").~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used.~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'SetElapsedTime~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Set a field with the time (seconds) elapsed since a date given by another field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Reference Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'The field from which to get the reference date~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Working Hours~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Leave empty to rely on the standard working hours scheme, or set to \"DefaultWorkingTimeComputer\" to force a 24x7 scheme~~',
	'Class:cmdbAbstractObject/Method:Reset' => 'Reset~~',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Reset a field to its default value~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'The field to reset, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy' => 'Copy~~',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Copy the value of a field to another field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Source Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'The field to get the value from, in the current object~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Time To Own~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTO~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Time To Resolve~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'Goal based on a SLT of type TTR~~',
));







?>
