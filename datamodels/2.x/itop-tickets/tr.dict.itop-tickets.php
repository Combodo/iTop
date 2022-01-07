<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Çağrı Kaydı',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '~~',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '~~',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Kişi',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '~~',
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
// Class: lnkFunctionalCIToTicket
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkFunctionalCIToTicket' => 'İşlevsel CI / Çağrı kaydı bağla',
	'Class:lnkFunctionalCIToTicket+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id' => 'Çağrı Kaydı',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_id+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref' => 'Ref',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_ref+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title' => 'Ticket title~~',
	'Class:lnkFunctionalCIToTicket/Attribute:ticket_title+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id' => 'CI',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_id+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name' => 'CI Adı',
	'Class:lnkFunctionalCIToTicket/Attribute:functionalci_name+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact' => 'Etki (Metin)',
	'Class:lnkFunctionalCIToTicket/Attribute:impact+' => '~~',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code' => 'Etki',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:manual' => 'Elle eklendi',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:computed' => 'Hesaplandı',
	'Class:lnkFunctionalCIToTicket/Attribute:impact_code/Value:not_impacted' => 'Etkilemedi',
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

	'portal:itop-portal' => 'Standard portal~~', // This is the portal name that will be displayed in portal dispatcher (eg. URL in menus)
	'Page:DefaultTitle' => '%1$s - User portal~~',
	'Brick:Portal:UserProfile:Title' => 'My profile~~',
	'Brick:Portal:NewRequest:Title' => 'New request~~',
	'Brick:Portal:NewRequest:Title+' => '<p>Need help?</p><p>Pick from the services catalog and submit your request to our support teams.</p>~~',
	'Brick:Portal:OngoingRequests:Title' => 'Ongoing requests~~',
	'Brick:Portal:OngoingRequests:Title+' => '<p>Follow up with your ongoing requests.</p><p>Check the progress, add comments, attach documents, acknowledge the solution.</p>~~',
	'Brick:Portal:OngoingRequests:Tab:OnGoing' => 'Open~~',
	'Brick:Portal:OngoingRequests:Tab:Resolved' => 'Resolved~~',
	'Brick:Portal:ClosedRequests:Title' => 'Closed requests~~',
));
