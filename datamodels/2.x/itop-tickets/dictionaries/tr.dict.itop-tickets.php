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
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
//
// Class: Ticket
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Ticket' => 'Kayıt',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Referans',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Organizasyon',
	'Class:Ticket/Attribute:org_id+' => '~~',
	'Class:Ticket/Attribute:org_name' => 'Organizasyon Adı',
	'Class:Ticket/Attribute:org_name+' => '~~',
	'Class:Ticket/Attribute:caller_id' => 'Çağrı Sahibi',
	'Class:Ticket/Attribute:caller_id+' => '~~',
	'Class:Ticket/Attribute:caller_name' => 'Çağrı Sahibinin Adı',
	'Class:Ticket/Attribute:caller_name+' => '~~',
	'Class:Ticket/Attribute:team_id' => 'Birim',
	'Class:Ticket/Attribute:team_id+' => '~~',
	'Class:Ticket/Attribute:team_name' => 'Birim adı',
	'Class:Ticket/Attribute:team_name+' => '~~',
	'Class:Ticket/Attribute:agent_id' => 'Temsilci',
	'Class:Ticket/Attribute:agent_id+' => '~~',
	'Class:Ticket/Attribute:agent_name' => 'Temsilci adı',
	'Class:Ticket/Attribute:agent_name+' => '~~',
	'Class:Ticket/Attribute:title' => 'Başlık',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Tanımlama',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Açılış tarihi',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Son Tarihi',
	'Class:Ticket/Attribute:end_date+' => '~~',
	'Class:Ticket/Attribute:last_update' => 'Son güncelleme',
	'Class:Ticket/Attribute:last_update+' => '~~',
	'Class:Ticket/Attribute:close_date' => 'Kapanma tarihi',
	'Class:Ticket/Attribute:close_date+' => '~~',
	'Class:Ticket/Attribute:private_log' => 'Özel kayıt',
	'Class:Ticket/Attribute:private_log+' => '~~',
	'Class:Ticket/Attribute:contacts_list' => 'Kişiler',
	'Class:Ticket/Attribute:contacts_list+' => 'Bu çağrı kaydıyla bağlantılı tüm kişiler',
	'Class:Ticket/Attribute:functionalcis_list' => 'CI \'lar',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Bu çağrı kaydı için etkilenen tüm yapılandırma maddeleri',
	'Class:Ticket/Attribute:workorders_list' => 'İş emirleri',
	'Class:Ticket/Attribute:workorders_list+' => 'Bu çağrı kaydı için tüm iş emirleri',
	'Class:Ticket/Attribute:finalclass' => 'Tip',
	'Class:Ticket/Attribute:finalclass+' => '',
	'Class:Ticket/Attribute:operational_status' => 'Operational status~~',
	'Class:Ticket/Attribute:operational_status+' => 'Computed after the detailed status~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'Ongoing~~',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Work in progress~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Resolved~~',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '~~',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Closed~~',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => '~~',
	'Ticket:ImpactAnalysis' => 'Etki Analizi',
));


//
// Class: lnkContactToTicket
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkContactToTicket' => 'Kişi / Çağrı kaydı bağla',
	'Class:lnkContactToTicket+' => '~~',
	'Class:lnkContactToTicket/Name' => '%1$s / %2$s~~',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Çağrı Kaydı',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '~~',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '~~',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kişi',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '~~',
	'Class:lnkContactToTicket/Attribute:contact_name' => 'Contact name~~',
	'Class:lnkContactToTicket/Attribute:contact_name+' => '~~',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'İletişim e-postası',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '~~',
	'Class:lnkContactToTicket/Attribute:role' => 'Rol (metin)',
	'Class:lnkContactToTicket/Attribute:role+' => '~~',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Rol',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Elle eklendi',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Hesaplandı',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Bildirme',
));

//
// Class: WorkOrder
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:WorkOrder' => 'İş emri',
	'Class:WorkOrder+' => '~~',
	'Class:WorkOrder/Attribute:name' => 'İsim',
	'Class:WorkOrder/Attribute:name+' => '~~',
	'Class:WorkOrder/Attribute:status' => 'Durum',
	'Class:WorkOrder/Attribute:status+' => '~~',
	'Class:WorkOrder/Attribute:status/Value:open' => 'açık',
	'Class:WorkOrder/Attribute:status/Value:open+' => '~~',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'kapalı',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '~~',
	'Class:WorkOrder/Attribute:description' => 'Açıklama',
	'Class:WorkOrder/Attribute:description+' => '~~',
	'Class:WorkOrder/Attribute:ticket_id' => 'Çağrı Kaydı',
	'Class:WorkOrder/Attribute:ticket_id+' => '~~',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Çağrı kaydı ref',
	'Class:WorkOrder/Attribute:ticket_ref+' => '~~',
	'Class:WorkOrder/Attribute:team_id' => 'Birim',
	'Class:WorkOrder/Attribute:team_id+' => '~~',
	'Class:WorkOrder/Attribute:team_name' => 'Birim adı',
	'Class:WorkOrder/Attribute:team_name+' => '~~',
	'Class:WorkOrder/Attribute:agent_id' => 'Temsilci',
	'Class:WorkOrder/Attribute:agent_id+' => '~~',
	'Class:WorkOrder/Attribute:agent_email' => 'Temsilci e-postası',
	'Class:WorkOrder/Attribute:agent_email+' => '~~',
	'Class:WorkOrder/Attribute:start_date' => 'Başlangıç tarihi',
	'Class:WorkOrder/Attribute:start_date+' => '~~',
	'Class:WorkOrder/Attribute:end_date' => 'Bitiş Tarihi',
	'Class:WorkOrder/Attribute:end_date+' => '~~',
	'Class:WorkOrder/Attribute:log' => 'Kayıt',
	'Class:WorkOrder/Attribute:log+' => '~~',
	'Class:WorkOrder/Stimulus:ev_close' => 'Kapat',
	'Class:WorkOrder/Stimulus:ev_close+' => '~~',
));


// Fieldset translation
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Ticket:baseinfo' => 'Genel Bilgi',
	'Ticket:date' => 'Tarihler',
	'Ticket:contact' => 'Kişiler',
	'Ticket:moreinfo' => 'Daha fazla bilgi',
	'Ticket:relation' => 'İlişkiler',
	'Ticket:log' => 'İletişim',
	'Ticket:Type' => 'Yeterlilik',
	'Ticket:support' => 'Destek',
	'Ticket:resolution' => 'Çözünürlük',
	'Ticket:SLA' => 'SLA raporu',
	'WorkOrder:Details' => 'Ayrıntılar',
	'WorkOrder:Moreinfo' => 'Daha fazla bilgi',
	'Tickets:ResolvedFrom' => '%1$s\'den otomatik olarak çözüldü,',
	'Class:cmdbAbstractObject/Method:Set' => 'Ayarla',
	'Class:cmdbAbstractObject/Method:Set+' => 'Sabit değeri olan bir alanı ayarlayın',
	'Class:cmdbAbstractObject/Method:Set/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:Set/Param:1+' => 'Ayarlanan alan, geçerli nesnede',
	'Class:cmdbAbstractObject/Method:Set/Param:2' => 'Değer',
	'Class:cmdbAbstractObject/Method:Set/Param:2+' => 'Ayarlanan değer',
	'Class:cmdbAbstractObject/Method:SetCurrentDate' => 'Güncel tarihi ayarla',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+' => 'Güncel tarih ve saatle bir alan ayarlayın',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+' => 'Ayarlanan alan, geçerli nesnede',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull' => 'SetCurrentDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+' => 'Set an empty field with the current date and time~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetCurrentUser' => 'Mevcut kullanıcıyı ayarla',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+' => 'Oturum açmış olan kullanıcıyla bir alan ayarlayın',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+' => 'Mevcut nesnede ayarlanan alan. Alan bir dize ise, bilinen ad kullanılacak, aksi takdirde tanımlayıcı kullanılacaktır. Bu bilinen ad, kullanıcıya atandığı takdirde geçerlidir.Aksi halde giriş yapılan kullanıcı adı geçerlidir.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson' => 'Mevcut kullanıcıyı ayarla',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+' => 'Oturum açmış kişiyle bir alan ayarlayın.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+' => 'Mevcut nesnede ayarlanan alan. Alan bir dize ise, bilinen ad kullanılacaktır, aksi takdirde tanımlayıcı kullanılacaktır.',
	'Class:cmdbAbstractObject/Method:SetElapsedTime' => 'Geçen zamanı ayarla',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+' => 'Başka bir alan tarafından belirlenmiş tarihten geçen süreye göre  bir alanı ayarla (saniye)',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+' => 'Ayarlanan alan, geçerli nesnede',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2' => 'Referans alanı',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+' => 'Referans tarihi elde etmek için alan',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3' => 'Çalışma saatleri',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+' => 'Standart çalışma saatleri şemasına uymak için boş bırakın veya 24x7 şemasını oluşturmak için \\"DefaultWorkingTimecomputer\\" olarak ayarlayın',
	'Class:cmdbAbstractObject/Method:SetIfNull' => 'SetIfNull~~',
	'Class:cmdbAbstractObject/Method:SetIfNull+' => 'Set a field only if it is empty, with a static value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2' => 'Value~~',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+' => 'The value to set~~',
	'Class:cmdbAbstractObject/Method:AddValue' => 'AddValue~~',
	'Class:cmdbAbstractObject/Method:AddValue+' => 'Add a fixed value to a field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+' => 'The field to modify, in the current object~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2' => 'Value~~',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+' => 'Decimal value which will be added, can be negative~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate' => 'SetComputedDate~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate+' => 'Set a field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2' => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3' => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+' => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull' => 'SetComputedDateIfNull~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+' => 'Set non empty field with a date computed from another field with extra logic~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1' => 'Target Field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'The field to set, in the current object~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2' => 'Modifier~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Textual information to modify the source date, eg. "+3 days"~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3' => 'Source field~~',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'The field used as source to apply the Modifier logic~~',
	'Class:cmdbAbstractObject/Method:Reset' => 'Sıfırla',
	'Class:cmdbAbstractObject/Method:Reset+' => 'Bir alanı varsayılan değerine sıfırlayın',
	'Class:cmdbAbstractObject/Method:Reset/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+' => 'Sıfırlanan alan, mevcut nesnede',
	'Class:cmdbAbstractObject/Method:Copy' => 'Kopyala',
	'Class:cmdbAbstractObject/Method:Copy+' => 'Bir alanın değerini başka bir alana kopyalayın',
	'Class:cmdbAbstractObject/Method:Copy/Param:1' => 'Hedef alanı',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+' => 'Ayarlanan alan, geçerli nesnede',
	'Class:cmdbAbstractObject/Method:Copy/Param:2' => 'Kaynak alanı',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+' => 'Mevcut nesnede değeri elde etmek için alan',
	'Class:cmdbAbstractObject/Method:ApplyStimulus' => 'ApplyStimulus~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+' => 'Apply the specified stimulus to the current object~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1' => 'Stimulus code~~',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+' => 'A valid stimulus code for the current class~~',
	'Class:ResponseTicketTTO/Interface:iMetricComputer' => 'Sahip Olunacak Zaman',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+' => 'TTO tipi bir SLT\'ye dayalı hedef',
	'Class:ResponseTicketTTR/Interface:iMetricComputer' => 'Çözme zamanı',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+' => 'TTR tipi  bir SLT\'ye dayalı hedef',
));

