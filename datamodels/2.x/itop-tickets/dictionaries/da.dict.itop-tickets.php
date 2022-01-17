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
 * @author	Erik Bøg <erik@boegmoeller.dk>
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Reference',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organisation',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Organisations navn',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Bruger',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Bruger navn',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Team',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Team navn',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Tildelt til',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Tildelt til',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Titel',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Beskrivelse',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Start dato',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Slut dato',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Sidste opdatering',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Lukket dato',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Privat Log',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Kontakt',
	'Class:Ticket/Attribute:contacts_list+' => '',
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => '',
	'Class:Ticket/Attribute:workorders_list' => 'Arbejdsordre',
	'Class:Ticket/Attribute:workorders_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Type',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Operational status~~',
	'Class:Ticket/Attribute:operational_status+' => 'Computed after the detailed status~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Ongoing~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Work in progress~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Resolved~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '~~',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Closed~~',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '~~',
	'Ticket:ImpactAnalysis' => 'Impact Analysis~~',
));


//
// Class: lnkContactToTicket
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:lnkContactToTicket' => 'Sammenhæng Kontakt/Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Reference',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kontakt',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Kontakt Email',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rolle',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Role~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Added manually~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Computed~~',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Do not notify~~',
));

//
// Class: WorkOrder
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:WorkOrder' => 'Arbejdsordre',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Navn',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Status',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'Åben',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'Lukket',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Beskrivelse',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Refererede Ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Team',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Team navn',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Tildelt til',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Bruger Email',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Start dato',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Slut dato',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Log',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Luk',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Ticket:baseinfo'                                                => 'Almindelig information',
	'Ticket:date'                                                    => 'Dato',
	'Ticket:contact'                                                 => 'Kontakt',
	'Ticket:moreinfo'                                               => 'Yderligere information',
	'Ticket:relation'                                               => 'Betegnelse',
	'Ticket:log'                                                    => 'Kommunikation',
	'Ticket:Type'                                                   => 'Qualifikation',
	'Ticket:support'                                                => 'Support',
	'Ticket:resolution'                                             => 'Løsning',
	'Ticket:SLA'                                                    => 'SLA Report',
	'WorkOrder:Details'                                             => 'Detaljer',
	'WorkOrder:Moreinfo'                                            => 'Yderligere information',
	'Tickets:ResolvedFrom'                                          => 'Automatically resolved from %1$s~~',
	'Class:cmdbAbstractObject/Method:Set'                           => 'Set~~',
	'Class:cmdbAbstractObject/Method:Set+'                          => 'Set a field with a static value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1'                   => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Set/Param:1+'                  => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2'                   => 'Value~~',
	'Class:cmdbAbstractObject/Method:Set/Param:2+'                  => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate'                => 'SetCurrentDate~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+'               => 'Set a field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+'       => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull'          => 'SetCurrentDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+'         => 'Set an empty field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser'                => 'SetCurrentUser~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+'               => 'Set a field with the currently logged in user~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+'       => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson'              => 'SetCurrentPerson~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+'             => 'Set a field with the currently logged in person (the \\"person\\" attached to the logged in \\"user\\").~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1'      => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+'     => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used.~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime'                => 'SetElapsedTime~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+'               => 'Set a field with the time (seconds) elapsed since a date given by another field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+'       => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2'        => 'Reference Field~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+'       => 'The field from which to get the reference date~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3'        => 'Working Hours~~',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+'       => 'Leave empty to rely on the standard working hours scheme, or set to \\"DefaultWorkingTimeComputer\\" to force a 24x7 scheme~~',
	'Class:cmdbAbstractObject/Method:SetIfNull'                     => 'SetIfNull~~',
	'Class:cmdbAbstractObject/Method:SetIfNull+'                    => 'Set a field only if it is empty, with a static value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1'             => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+'            => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2'              => 'Value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+'             => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:AddValue'                       => 'AddValue~~',
	'Class:cmdbAbstractObject/Method:AddValue+'                      => 'Add a fixed value to a field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1'               => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+'              => 'The field to modify, in the current object~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2'               => 'Value~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+'              => 'Decimal value which will be added, can be negative~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate'                => 'SetComputedDate~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate+'               => 'Set a field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1'        => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+'       => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2'        => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+'       => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3'        => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+'       => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull'          => 'SetComputedDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+'         => 'Set non empty field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1'  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2'  => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3'  => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:Reset'                          => 'Reset~~',
	'Class:cmdbAbstractObject/Method:Reset+'                         => 'Reset a field to its default value~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1'                  => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+'                 => 'The field to reset, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy'                           => 'Copy~~',
	'Class:cmdbAbstractObject/Method:Copy+'                          => 'Copy the value of a field to another field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1'                   => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+'                  => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2'                   => 'Source Field~~',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+'                  => 'The field to get the value from, in the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus'                  => 'ApplyStimulus~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+'                 => 'Apply the specified stimulus to the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1'          => 'Stimulus code~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+'         => 'A valid stimulus code for the current class~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer'              => 'Time To Own~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+'             => 'Goal based on a SLT of type TTO~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer'              => 'Time To Resolve~~',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+'             => 'Goal based on a SLT of type TTR~~',
));

//
// Class: Document
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Document/Attribute:contracts_list' => 'Kontrakter',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Ydelser',
	'Class:Document/Attribute:services_list+' => '',
));