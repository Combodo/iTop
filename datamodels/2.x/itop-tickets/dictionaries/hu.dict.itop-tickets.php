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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:Ticket' => 'Hibajegy',
    'Class:Ticket+' => '',
    'Class:Ticket/Attribute:ref' => 'Referenciaszám',
    'Class:Ticket/Attribute:ref+' => '',
    'Class:Ticket/Attribute:org_id' => 'Szervezeti egység',
    'Class:Ticket/Attribute:org_id+' => '',
    'Class:Ticket/Attribute:org_name' => 'Szervezeti egység név',
    'Class:Ticket/Attribute:org_name+' => '',
    'Class:Ticket/Attribute:caller_id' => 'Kérelmező',
    'Class:Ticket/Attribute:caller_id+' => '',
    'Class:Ticket/Attribute:caller_name' => 'Kérelmező név',
    'Class:Ticket/Attribute:caller_name+' => '',
    'Class:Ticket/Attribute:team_id' => 'Csapat',
    'Class:Ticket/Attribute:team_id+' => '',
    'Class:Ticket/Attribute:team_name' => 'Csapat név',
    'Class:Ticket/Attribute:team_name+' => '',
    'Class:Ticket/Attribute:agent_id' => 'Ügyintéző',
    'Class:Ticket/Attribute:agent_id+' => '',
    'Class:Ticket/Attribute:agent_name' => 'Ügyintéző név',
    'Class:Ticket/Attribute:agent_name+' => '',
    'Class:Ticket/Attribute:title' => 'Tárgy',
    'Class:Ticket/Attribute:title+' => '',
    'Class:Ticket/Attribute:description' => 'Leírás',
    'Class:Ticket/Attribute:description+' => '',
    'Class:Ticket/Attribute:start_date' => 'Megbízási dátum',
    'Class:Ticket/Attribute:start_date+' => '',
    'Class:Ticket/Attribute:end_date' => 'Megoldási dátum dátum',
    'Class:Ticket/Attribute:end_date+' => '',
    'Class:Ticket/Attribute:last_update' => 'Utoljára frissítve',
    'Class:Ticket/Attribute:last_update+' => '',
    'Class:Ticket/Attribute:close_date' => 'Lezárási dátum',
    'Class:Ticket/Attribute:close_date+' => '',
    'Class:Ticket/Attribute:private_log' => 'Privát napló',
    'Class:Ticket/Attribute:private_log+' => '',
    'Class:Ticket/Attribute:contacts_list' => 'Kapcsolattartók',
    'Class:Ticket/Attribute:contacts_list+' => '',
    'Class:Ticket/Attribute:functionalcis_list' => 'CI-k',
    'Class:Ticket/Attribute:functionalcis_list+' => '',
    'Class:Ticket/Attribute:workorders_list' => 'Munkaelrendelők',
    'Class:Ticket/Attribute:workorders_list+' => 'Munkaelrendelő ehhez a hibajegyhez',
    'Class:Ticket/Attribute:finalclass' => 'Hibajegy típus',
    'Class:Ticket/Attribute:finalclass+' => 'A részletes állapot után számítja',
    'Class:Ticket/Attribute:operational_status' => 'Műveleti állapot',
    'Class:Ticket/Attribute:operational_status+' => '',
    'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Folyamatban',
    'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Munka folyamatban',
    'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Megoldva',
    'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
    'Class:Ticket/Attribute:operational_status/Value:closed' => 'Lezárva',
    'Class:Ticket/Attribute:operational_status/Value:closed+' => '',
    'Ticket:ImpactAnalysis' => 'Hatáselemzés',
));


//
// Class: lnkContactToTicket
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:lnkContactToTicket' => 'Kapcsolattartó / Hibajegy',
    'Class:lnkContactToTicket+' => '~~',
    'Class:lnkContactToTicket/Attribute:ticket_id' => 'Hibajegy',
    'Class:lnkContactToTicket/Attribute:ticket_id+' => '~~',
    'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Referencia szám',
    'Class:lnkContactToTicket/Attribute:ticket_ref+' => '~~',
    'Class:lnkContactToTicket/Attribute:contact_id' => 'Kapcsolattartó',
    'Class:lnkContactToTicket/Attribute:contact_id+' => '~~',
    'Class:lnkContactToTicket/Attribute:contact_email' => 'Kapcsolattartó email',
    'Class:lnkContactToTicket/Attribute:contact_email+' => '~~',
    'Class:lnkContactToTicket/Attribute:role' => 'Beosztás (szöveg)',
    'Class:lnkContactToTicket/Attribute:role+' => '~~',
    'Class:lnkContactToTicket/Attribute:role_code' => 'Beosztás',
    'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Kézzel hozzáadva',
    'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Számított',
    'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Ne értesítsen',
));

//
// Class: WorkOrder
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:WorkOrder' => 'Munkaelrendelő',
    'Class:WorkOrder+' => '~~',
    'Class:WorkOrder/Attribute:name' => 'Név',
    'Class:WorkOrder/Attribute:name+' => '~~',
    'Class:WorkOrder/Attribute:status' => 'Állapot',
    'Class:WorkOrder/Attribute:status+' => '~~',
    'Class:WorkOrder/Attribute:status/Value:open' => 'Nyitott',
    'Class:WorkOrder/Attribute:status/Value:open+' => '~~',
    'Class:WorkOrder/Attribute:status/Value:closed' => 'Lezárt',
    'Class:WorkOrder/Attribute:status/Value:closed+' => '~~',
    'Class:WorkOrder/Attribute:description' => 'Leírás',
    'Class:WorkOrder/Attribute:description+' => '~~',
    'Class:WorkOrder/Attribute:ticket_id' => 'Hibajegy',
    'Class:WorkOrder/Attribute:ticket_id+' => '~~',
    'Class:WorkOrder/Attribute:ticket_ref' => 'Referencia szám',
    'Class:WorkOrder/Attribute:ticket_ref+' => '~~',
    'Class:WorkOrder/Attribute:team_id' => 'Csapat',
    'Class:WorkOrder/Attribute:team_id+' => '~~',
    'Class:WorkOrder/Attribute:team_name' => 'Csapat név',
    'Class:WorkOrder/Attribute:team_name+' => '~~',
    'Class:WorkOrder/Attribute:agent_id' => 'Ügyintéző',
    'Class:WorkOrder/Attribute:agent_id+' => '~~',
    'Class:WorkOrder/Attribute:agent_email' => 'Ügyintéző email cím',
    'Class:WorkOrder/Attribute:agent_email+' => '~~',
    'Class:WorkOrder/Attribute:start_date' => 'Megbízási dátum',
    'Class:WorkOrder/Attribute:start_date+' => '~~',
    'Class:WorkOrder/Attribute:end_date' => '~~',
    'Class:WorkOrder/Attribute:end_date+' => 'Megoldási dátum',
    'Class:WorkOrder/Attribute:log' => 'Napló',
    'Class:WorkOrder/Attribute:log+' => '~~',
    'Class:WorkOrder/Stimulus:ev_close' => 'Bezárás',
    'Class:WorkOrder/Stimulus:ev_close+' => '~~',
));


// Fieldset translation
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Ticket:baseinfo'                                                => 'Általános információk',
    'Ticket:date'                                                    => 'Dátum',
    'Ticket:contact'                                                 => 'Kapcsolattartók',
    'Ticket:moreinfo'                                               => 'Kiegészítő információk',
    'Ticket:relation'                                               => 'Kapcsolatok',
    'Ticket:log'                                                    => 'Kommunikációk',
    'Ticket:Type'                                                   => 'Osztályozás',
    'Ticket:support'                                                => 'Támogatás',
    'Ticket:resolution'                                             => 'Megoldás',
    'Ticket:SLA'                                                    => 'SLA jelentés',
    'WorkOrder:Details'                                             => 'Részletek',
    'WorkOrder:Moreinfo'                                            => 'További információk',
    'Tickets:ResolvedFrom'                                          => '%1$s -ból automatikusan megoldva',
    'Class:cmdbAbstractObject/Method:Set'                           => 'Set',
    'Class:cmdbAbstractObject/Method:Set+'                          => 'Set a field with a static value~~',
    'Class:cmdbAbstractObject/Method:Set/Param:1'                   => 'Célmező',
    'Class:cmdbAbstractObject/Method:Set/Param:1+'                  => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:Set/Param:2'                   => 'Érték',
    'Class:cmdbAbstractObject/Method:Set/Param:2+'                  => 'The value to set~~',
    'Class:cmdbAbstractObject/Method:SetCurrentDate'                => 'SetCurrentDate',
    'Class:cmdbAbstractObject/Method:SetCurrentDate+'               => 'Set a field with the current date and time~~',
    'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1'        => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+'       => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull'          => 'SetCurrentDateIfNull',
    'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+'         => 'Set an empty field with the current date and time~~',
    'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1'  => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetCurrentUser'                => 'SetCurrentUser',
    'Class:cmdbAbstractObject/Method:SetCurrentUser+'               => 'Set a field with the currently logged in user~~',
    'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1'        => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+'       => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used. That friendly name is the name of the person if any is attached to the user, otherwise it is the login.~~',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson'              => 'SetCurrentPerson',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson+'             => 'Set a field with the currently logged in person (the "person" attached to the logged in "user").~~',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1'      => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+'     => 'The field to set, in the current object. If the field is a string then the friendly name will be used, otherwise the identifier will be used.~~',
    'Class:cmdbAbstractObject/Method:SetElapsedTime'                => 'SetElapsedTime',
    'Class:cmdbAbstractObject/Method:SetElapsedTime+'               => 'Set a field with the time (seconds) elapsed since a date given by another field~~',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1'        => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+'       => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2'        => 'Referencia mező',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+'       => 'The field from which to get the reference date~~',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3'        => 'Munkaórák',
    'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+'       => 'Leave empty to rely on the standard working hours scheme, or set to "DefaultWorkingTimeComputer" to force a 24x7 scheme~~',
    'Class:cmdbAbstractObject/Method:SetIfNull'                     => 'SetIfNull',
    'Class:cmdbAbstractObject/Method:SetIfNull+'                    => 'Set a field only if it is empty, with a static value~~',
    'Class:cmdbAbstractObject/Method:SetIfNull/Param:1'             => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+'            => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetIfNull/Param:2'              => 'Érték',
    'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+'             => 'The value to set~~',
    'Class:cmdbAbstractObject/Method:AddValue'                       => 'AddValue',
    'Class:cmdbAbstractObject/Method:AddValue+'                      => 'Add a fixed value to a field~~',
    'Class:cmdbAbstractObject/Method:AddValue/Param:1'               => 'Célmező',
    'Class:cmdbAbstractObject/Method:AddValue/Param:1+'              => 'The field to modify, in the current object~~',
    'Class:cmdbAbstractObject/Method:AddValue/Param:2'               => 'Érték',
    'Class:cmdbAbstractObject/Method:AddValue/Param:2+'              => 'Decimal value which will be added, can be negative~~',
    'Class:cmdbAbstractObject/Method:SetComputedDate'                => 'SetComputedDate',
    'Class:cmdbAbstractObject/Method:SetComputedDate+'               => 'Set a field with a date computed from another field with extra logic~~',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1'        => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+'       => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2'        => 'Módosító',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+'       => 'Textual information to modify the source date, eg. "+3 days"~~',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3'        => 'Forrásmező',
    'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+'       => 'The field used as source to apply the Modifier logic~~',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull'          => 'SetComputedDateIfNull',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+'         => 'Set non empty field with a date computed from another field with extra logic~~',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1'  => 'Célmező',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2'  => 'Módosító',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3'  => 'Forrásmező',
    'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'The field used as source to apply the Modifier logic~~',
    'Class:cmdbAbstractObject/Method:Reset'                          => 'Visszaállítás',
    'Class:cmdbAbstractObject/Method:Reset+'                         => 'Reset a field to its default value~~',
    'Class:cmdbAbstractObject/Method:Reset/Param:1'                  => 'Célmező',
    'Class:cmdbAbstractObject/Method:Reset/Param:1+'                 => 'The field to reset, in the current object~~',
    'Class:cmdbAbstractObject/Method:Copy'                           => 'Másolás',
    'Class:cmdbAbstractObject/Method:Copy+'                          => 'Copy the value of a field to another field~~',
    'Class:cmdbAbstractObject/Method:Copy/Param:1'                   => 'Célmező',
    'Class:cmdbAbstractObject/Method:Copy/Param:1+'                  => 'The field to set, in the current object~~',
    'Class:cmdbAbstractObject/Method:Copy/Param:2'                   => 'Forrásmező',
    'Class:cmdbAbstractObject/Method:Copy/Param:2+'                  => 'The field to get the value from, in the current object~~',
    'Class:cmdbAbstractObject/Method:ApplyStimulus'                  => 'ApplyStimulus',
    'Class:cmdbAbstractObject/Method:ApplyStimulus+'                 => 'Apply the specified stimulus to the current object~~',
    'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1'          => 'Stimulus kód',
    'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+'         => 'A valid stimulus code for the current class~~',
    'Class:ResponseTicketTTO/Interface:iMetricComputer'              => 'Hozzárendelési idő',
    'Class:ResponseTicketTTO/Interface:iMetricComputer+'             => 'Goal based on a SLT of type TTO~~',
    'Class:ResponseTicketTTR/Interface:iMetricComputer'              => 'Megoldási idő',
    'Class:ResponseTicketTTR/Interface:iMetricComputer+'             => 'Goal based on a SLT of type TTR~~',
));

//
// Class: Document
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:Document/Attribute:contracts_list' => 'Szerződések',
    'Class:Document/Attribute:contracts_list+' => 'Ehhez a dokumentumhoz kapcsolódó szerződések',
    'Class:Document/Attribute:services_list' => 'Szolgáltatások',
    'Class:Document/Attribute:services_list+' => 'Ehhez a dokumentumhoz kapcsolódó szolgáltatások',
));
