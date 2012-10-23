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

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Relation:impacts/Description' => 'Etkilenen kalemler',
	'Relation:impacts/VerbUp' => 'Etkiler...',
	'Relation:impacts/VerbDown' => 'Etkilenenler...',
	'Relation:depends on/Description' => 'Bu kaleme bağımlı olan kalemler',
	'Relation:depends on/VerbUp' => 'Bağımlı olanlar...',
	'Relation:depends on/VerbDown' => 'Etkiledikleri...',
));


// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

//
// Class: Organization
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Organization' => 'Kurum',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Adı',
	'Class:Organization/Attribute:name+' => 'Kullanılan adı',
	'Class:Organization/Attribute:code' => 'Kodu',
	'Class:Organization/Attribute:code+' => 'Kurumu kodu (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Durumu',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Etkin',
	'Class:Organization/Attribute:status/Value:active+' => 'Etkin',
	'Class:Organization/Attribute:status/Value:inactive' => 'Etkin değil',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Etkin değil',
	'Class:Organization/Attribute:parent_id' => 'Bağlı olduğu kurum',
	'Class:Organization/Attribute:parent_id+' => 'Bağlı olduğu kurum',
	'Class:Organization/Attribute:parent_name' => 'Bağlı olduğu kurumun adı',
	'Class:Organization/Attribute:parent_name+' => 'Bağlı olduğu kurumun adı',
));


//
// Class: Location
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Location' => 'Yerleşke',
	'Class:Location+' => 'Yerleşke : Bölge, Ülke, Şehir, Yerleşke, Bina, Kat, Oda, kabin,...',
	'Class:Location/Attribute:name' => 'Adı',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Durumu',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Etkin',
	'Class:Location/Attribute:status/Value:active+' => 'Etkin',
	'Class:Location/Attribute:status/Value:inactive' => 'Etkin değil',
	'Class:Location/Attribute:status/Value:inactive+' => 'Etkin değil',
	'Class:Location/Attribute:org_id' => 'Kurumun sahibi',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Kurumun sahibinin adı',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adres',
	'Class:Location/Attribute:address+' => 'Posta adresi',
	'Class:Location/Attribute:postal_code' => 'Posta kodu',
	'Class:Location/Attribute:postal_code+' => 'Posta kodu',
	'Class:Location/Attribute:city' => 'Şehir',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Ülke',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Bağlı olduğu yerleşke',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Ebebeyn adı',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'İrtibatlar',
	'Class:Location/Attribute:contact_list+' => 'Yerleşkedeki irtibatlar',
	'Class:Location/Attribute:infra_list' => 'Altyapı',
	'Class:Location/Attribute:infra_list+' => 'Yerleşkedeki Konfigürasyon Kalemleri (KK)',
));
//
// Class: Group
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Group' => 'Grup',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Adı',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Surumu',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:Group/Attribute:status/Value:implementation+' => 'Uygulama',
	'Class:Group/Attribute:status/Value:obsolete' => 'Üretimden kalkan',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Üretimden kalkan',
	'Class:Group/Attribute:status/Value:production' => 'Kullanımda',
	'Class:Group/Attribute:status/Value:production+' => 'Kullanımda',
	'Class:Group/Attribute:org_id' => 'Kurum',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Adı',
	'Class:Group/Attribute:owner_name+' => 'Kullanılan Adı',
	'Class:Group/Attribute:description' => 'Tanımlama',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tip',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Bağlı olduğu grup',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Adı',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Bağlantılı Konfigürasyon Kalemleri (KK)',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkGroupToCI' => 'Grup / KK',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Grup',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Adı',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'KK',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Adı',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'KK durumu',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Sebep',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Class: Contact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Contact' => 'İrtibat',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Adı',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Durumu',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Etkin',
	'Class:Contact/Attribute:status/Value:active+' => 'Etkin',
	'Class:Contact/Attribute:status/Value:inactive' => 'Etkin değil',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Etkin değil',
	'Class:Contact/Attribute:org_id' => 'Kurum',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Kurum',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'E-posta',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefon',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Yerleşke',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Yerleşke',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'Konfigürasyon kalemleri',
	'Class:Contact/Attribute:ci_list+' => 'İrtibatla ilgili KK',
	'Class:Contact/Attribute:contract_list' => 'İrtibatlar',
	'Class:Contact/Attribute:contract_list+' => 'İrtibatla ilgili sözleşmeler',
	'Class:Contact/Attribute:service_list' => 'Hizmetler',
	'Class:Contact/Attribute:service_list+' => 'İrtibatla ilgili hizmetler',
	'Class:Contact/Attribute:ticket_list' => 'Çağrılar',
	'Class:Contact/Attribute:ticket_list+' => 'İrtibatla ilgili çağrılar',
	'Class:Contact/Attribute:team_list' => 'Ekipler',
	'Class:Contact/Attribute:team_list+' => 'İrtibatın dahil olduğu ekip',
	'Class:Contact/Attribute:finalclass' => 'Tip',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Person' => 'Kişi',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Adı',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'Çalışan No',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Team' => 'Ekip',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Üyeleri',
	'Class:Team/Attribute:member_list+' => 'Ekip üyeleri',
));

//
// Class: lnkTeamToContact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkTeamToContact' => 'Ekip üyeleri',
	'Class:lnkTeamToContact+' => 'Ekip üyeleri',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Ekip',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Üye',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Yerleşke',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'E-posta',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Telefon',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Rolü',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Document' => 'Doküman',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Adı',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Kurum',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => 'Kurum Adı',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => 'Tanımlama',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Tip',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Sözleşme',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Ağ haritası',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Sunum',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Eğitim',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'Tanıtım',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'İş talimatı',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Durumu',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Taslak',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Geçersiz',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Yayınlanan',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'Konfigürasyon kalemleri',
	'Class:Document/Attribute:ci_list+' => 'Dokümana referans veren KKler',
	'Class:Document/Attribute:contract_list' => 'İrtibatlar',
	'Class:Document/Attribute:contract_list+' => 'Dokümanla ilgili sözleşmeler',
	'Class:Document/Attribute:service_list' => 'Servisler',
	'Class:Document/Attribute:service_list+' => 'Dokümanla ilgili servisler',
	'Class:Document/Attribute:ticket_list' => 'Çağrılar',
	'Class:Document/Attribute:ticket_list+' => 'Dokümanla ilgili çağrılar',
	'Class:Document:PreviewTab' => 'Ön görünüm',
));

//
// Class: WebDoc
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:WebDoc' => 'Web Dokümanı',
	'Class:WebDoc+' => 'Farklı web sunucunda olan dokümanlar',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Note' => 'Not',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Metin',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FileDoc' => 'Doküman (dosya)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'İçerik',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Licence' => 'Lisans',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Lisansı veren',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'Sahibi',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => 'Adı',
	'Class:Licence/Attribute:org_name+' => 'Adı',
	'Class:Licence/Attribute:product' => 'Ürün',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Adı',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Başlangıç tarihi',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Bitiş tarihi',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Lisans',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Kapsam',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Kullanım limit',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Kullanım',
	'Class:Licence/Attribute:usage_list+' => 'Lisansı kullanan uygulamalar',
));


//
// Class: Subnet
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Adı',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Kurum',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Tanımlama',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Patch' => 'Yama',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Adı',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Tanımlama',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Hedef uygulama',
	'Class:Patch/Attribute:target_sw+' => 'Hedef yazılım',
	'Class:Patch/Attribute:version' => 'Versiyon',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Tip',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Uygulama',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'İşletim sistemi',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Güvenlik',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Servis paketi',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Cihazlar',
	'Class:Patch/Attribute:ci_list+' => 'Yamanın yüklendiği cihazlar',
));

//
// Class: Software
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Software' => 'Yazılım',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Adı',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Tanımlama',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Yüklemeler',
	'Class:Software/Attribute:instance_list+' => 'Yazılımın kurulumları',
	'Class:Software/Attribute:finalclass' => 'Tip',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Application' => 'Uygulama',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Adı',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Tanımlama',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Yüklemeler',
	'Class:Application/Attribute:instance_list+' => 'Uygulamanın kurulumları',
));

//
// Class: DBServer
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DBServer' => 'Veritabanı',
	'Class:DBServer+' => 'Veritabanı yazılımı',
	'Class:DBServer/Attribute:instance_list' => 'Yüklemeler',
	'Class:DBServer/Attribute:instance_list+' => 'Veritabanı kurulumları',
));

//
// Class: lnkPatchToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkPatchToCI' => 'Yama kullanımı',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Yama',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Yama',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'KK',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'KK',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'KK Durumu',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FunctionalCI' => 'Fonksiyonel KK',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Adı',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Durumu',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Üretimden kalkan',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Kullanımda',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Sahip kurum',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Sahip kurum',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'İş açısından kritikliği',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Yüksek',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Düşük',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Orta',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'İrtibatlar',
	'Class:FunctionalCI/Attribute:contact_list+' => 'KK için İrtibatlar',
	'Class:FunctionalCI/Attribute:document_list' => 'Dokümanlar',
	'Class:FunctionalCI/Attribute:document_list+' => 'KK için dokümanlar',
	'Class:FunctionalCI/Attribute:solution_list' => 'Uygulama çözümleri',
	'Class:FunctionalCI/Attribute:solution_list+' => 'KKyi kullanan uygulama çözümleri',
	'Class:FunctionalCI/Attribute:contract_list' => 'İrtibatlar',
	'Class:FunctionalCI/Attribute:contract_list+' => 'KKyi destekleyen sözleşmeler',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Çağrılar',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'KK ile ilgili çağrılar',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tip',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SoftwareInstance' => 'Yazılım Kurulumu',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Cihaz',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Cihaz',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Lisans',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Lisans',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Yazılım',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Versiyon',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Tanımlama',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ApplicationInstance' => 'Uygulama Kurulumu',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => 'Yazılım',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => 'Adı',
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DBServerInstance' => 'Veritabanı Sunucusu',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => 'Yazılım',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'Adı',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Veritabanları',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Veritabanları',
));


//
// Class: DatabaseInstance
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DatabaseInstance' => 'Veritabanı',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Veritabanı sunucusu',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Veritabanı versiyonu',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Tanımlama',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ApplicationSolution' => 'Uygulama çözümleri',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Tanımlama',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'Konfigürasyon kalemleri',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'Çözümü oluşturan KKler',
	'Class:ApplicationSolution/Attribute:process_list' => 'İş süreçleri',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Uygulama çözümüne bağımlı iş süreci',
));

//
// Class: BusinessProcess
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:BusinessProcess' => 'İş süreci',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Tanımlama',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Uygulama çözümleri',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Sürecin bağımlı olduğu iş süreci',
));

//
// Class: ConnectableCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ConnectableCI' => 'Bağlanabilir KK',
	'Class:ConnectableCI+' => 'Fiziksel KK',
	'Class:ConnectableCI/Attribute:brand' => 'Marka',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Model',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Seri No',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Kaynak referansı',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NetworkInterface' => 'Network arayüzü',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'Cihaz',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Cihaz',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Mantıksal tipi',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Yedek',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Mantıksal',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Port',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Birincil',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'İkincil',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Fiziksel tip',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'IP Adresi',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'IP maskesi',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'MAC Adresi',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Hızı',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Çift yönlü',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Otomatik',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Otomatik',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Unknown',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Bağlantıda',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Bağlantıdaki arayüz',
	'Class:NetworkInterface/Attribute:connected_name' => 'Bağlantıda',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Bağlı cihazlar',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'Cihaz',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Hat tipi',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Hat kapalı',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Hat açık',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Device' => 'Cihaz',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Network arayüzleri',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Sabit disk',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OS Family',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OS Versiyonu',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Uygulamalar',
	'Class:PC/Attribute:application_list+' => 'PCye yüklü programlar',
	'Class:PC/Attribute:patch_list' => 'Yamalar',
	'Class:PC/Attribute:patch_list+' => 'PCye yüklü yamalar',
));

//
// Class: MobileCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:MobileCI' => 'Mobil KK',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:MobilePhone' => 'Cep telefonu',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Tel No',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:InfrastructureCI' => 'Altyapı KK',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Tanımlama',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Yerleşke',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Yerleşke',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Yerleşke detayları',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'Yönetim IPsi',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Default Gateway',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:NetworkDevice' => 'Ağ Cihazı',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Tip',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Firewall',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Hub',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Load Balancer',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Router',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Switch',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'IOS Version',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP Read',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP Write',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Server' => 'Sunucu',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Sabit Disk',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'OS Family',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'OS Versiyonu',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Uygulamalar',
	'Class:Server/Attribute:application_list+' => 'Sunucuya yüklü uygulamalar',
	'Class:Server/Attribute:patch_list' => 'Yamalar',
	'Class:Server/Attribute:patch_list+' => 'Sunucuya yüklü yamalar',
));

//
// Class: Printer
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Printer' => 'Yazıcı',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Tip',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Printer',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Technology',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Inkjet',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkCIToDoc' => 'Doküman/KK',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'KK',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'KK',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'KK Durumu',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Doküman',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Doküman',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Doküman tipi',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Doküman durumu',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkCIToContact' => 'KK/İrtibat',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'KK',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'KK',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'KK Durumu',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'İrtibat',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'İrtibat',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'İrtibat E-posta',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Rolü',
	'Class:lnkCIToContact/Attribute:role+' => 'KK ile ilgili irtibatın rolü',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkSolutionToCI' => 'KK/Çözüm',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Uygulama Çözümü',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Uygulama Çözümü',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'KK',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'KK',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'KK Durumu',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Yardımcı araç',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Çözümdeki KK için yardımcı araç',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkProcessToSolution' => 'İş Süreci/Çözüm',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Uygulama Çözümü',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Uygulama Çözümü',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Süreç',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Süreç',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Sebep',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Çözüm ve süreç arasındaki ilişkinin detayı',
));



//
// Class extensions
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
'Class:Subnet/Tab:IPUsage' => 'IP Kullanımı',
'Class:Subnet/Tab:IPUsage-explain' => '<em>%1$s</em> - <em>%2$s</em> aralığındaki IPye sahip arayüzler',
'Class:Subnet/Tab:FreeIPs' => 'Boş IPler',
'Class:Subnet/Tab:FreeIPs-count' => 'Boş IPler: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Boş IP adresleri',
));

//
// Application Menu
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
'Menu:Catalogs' => 'Kataloglar',
'Menu:Catalogs+' => 'Veri tipleri',
'Menu:Audit' => 'Denetleme',
'Menu:Audit+' => 'Denetleme',
'Menu:Organization' => 'Kurumlar',
'Menu:Organization+' => 'Tüm Kurumlar',
'Menu:Application' => 'Uygulamalar',
'Menu:Application+' => 'Tüm Uygulamalar',
'Menu:DBServer' => 'Veritabanı sunucuları',
'Menu:DBServer+' => 'Veritabanı sunucuları',
'Menu:Audit' => 'Denetleme',
'Menu:ConfigManagement' => 'Konfigürasyon Yönetimi',
'Menu:ConfigManagement+' => 'Konfigürasyon Yönetimi',
'Menu:ConfigManagementOverview' => 'Özet',
'Menu:ConfigManagementOverview+' => 'Özet',
'Menu:Contact' => 'İrtibatlar',
'Menu:Contact+' => 'İrtibatlar',
'Menu:Person' => 'Kişiler',
'Menu:Person+' => 'Tüm Kişiler',
'Menu:Team' => 'Ekipler',
'Menu:Team+' => 'Tüm ekipler',
'Menu:Document' => 'Dokümanlar',
'Menu:Document+' => 'Tüm dokümanlar',
'Menu:Location' => 'Yerleşkeler',
'Menu:Location+' => 'Tüm Yerleşkeler',
'Menu:ConfigManagementCI' => 'Konfigürasyon Kalemleri',
'Menu:ConfigManagementCI+' => 'Konfigürasyon Kalemleri',
'Menu:BusinessProcess' => 'İş süreçleri',
'Menu:BusinessProcess+' => 'Tüm İş süreçleri',
'Menu:ApplicationSolution' => 'Uygulama çözümleri',
'Menu:ApplicationSolution+' => 'Tüm Uygulama çözümleri',
'Menu:ConfigManagementSoftware' => 'Uygulama Yönetimi',
'Menu:Licence' => 'Lisanslar',
'Menu:Licence+' => 'Tüm Lisanslar',
'Menu:Patch' => 'Yamalar',
'Menu:Patch+' => 'Tüm Yamalar',
'Menu:ApplicationInstance' => 'Yüklenen yazılımlar',
'Menu:ApplicationInstance+' => 'Uygulama ve Veritabanı sunucuları',
'Menu:ConfigManagementHardware' => 'Altyapı Yönetimi',
'Menu:Subnet' => 'Subnets',
'Menu:Subnet+' => 'All Subnets',
'Menu:NetworkDevice' => 'Network cihazları',
'Menu:NetworkDevice+' => 'Tüm Network cihazları',
'Menu:Server' => 'Sunucular',
'Menu:Server+' => 'Tüm Sunucular',
'Menu:Printer' => 'Yazıcılar',
'Menu:Printer+' => 'Tüm Yazıcılar',
'Menu:MobilePhone' => 'Cep Telefonları',
'Menu:MobilePhone+' => 'Tüm Cep Telefonları',
'Menu:PC' => 'Kişisel Bilgisayarlar',
'Menu:PC+' => 'Tüm Kişisel Bilgisayarlar',
'Menu:NewContact' => 'Yeni İrtibat',
'Menu:NewContact+' => 'Yeni İrtibat',
'Menu:SearchContacts' => 'İrtibat ara',
'Menu:SearchContacts+' => 'İrtibat ara',
'Menu:NewCI' => 'Yeni KK',
'Menu:NewCI+' => 'Yeni KK',
'Menu:SearchCIs' => 'KK ara',
'Menu:SearchCIs+' => 'KK ara',
'Menu:ConfigManagement:Devices' => 'Cihazlar',
'Menu:ConfigManagement:AllDevices' => 'Cihaz sayısı: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'Yazılım ve uygulamalar',
'Menu:ConfigManagement:Misc' => 'Diğer',
'Menu:Group' => 'KK Grupları',
'Menu:Group+' => 'KK Grupları',
'Menu:ConfigManagement:Shortcuts' => 'Kısalyollar',
'Menu:ConfigManagement:AllContacts' => 'Tüm irtibatlar: %1$d',

));
?>
