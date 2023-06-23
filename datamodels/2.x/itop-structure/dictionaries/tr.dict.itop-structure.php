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
 * @author      Benjamin Planque <benjamin.planque@combodo.com>
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
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
	'Class:Organization/Attribute:deliverymodel_id' => 'Delivery model~~',
	'Class:Organization/Attribute:deliverymodel_id+' => '~~',
	'Class:Organization/Attribute:deliverymodel_name' => 'Delivery model name~~',
	'Class:Organization/Attribute:deliverymodel_name+' => '~~',
	'Class:Organization/Attribute:parent_id_friendlyname' => 'Ana',
	'Class:Organization/Attribute:parent_id_friendlyname+' => 'Ana organizasyon',
	'Class:Organization/Attribute:overview' => 'Overview~~',
	'Organization:Overview:FunctionalCIs' => 'Configuration items of this organization~~',
	'Organization:Overview:FunctionalCIs:subtitle' => 'by type~~',
	'Organization:Overview:Users' => ITOP_APPLICATION_SHORT.' Users within this organization~~',
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
	'Class:Location/Attribute:physicaldevice_list' => 'Cihazlar',
	'Class:Location/Attribute:physicaldevice_list+' => 'Bu konumdaki tüm cihazlar',
	'Class:Location/Attribute:person_list' => 'Kişiler',
	'Class:Location/Attribute:person_list+' => 'Bu konumda bulunan tüm kişiler',
));

//
// Class: Contact
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Contact' => 'İrtibat',
	'Class:Contact+' => '',
	'Class:Contact/ComplementaryName' => '%1$s - %2$s~~',
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
	'Class:Contact/Attribute:notify' => 'Bildirim',
	'Class:Contact/Attribute:notify+' => '~~',
	'Class:Contact/Attribute:notify/Value:no' => 'hayır',
	'Class:Contact/Attribute:notify/Value:no+' => 'hayır',
	'Class:Contact/Attribute:notify/Value:yes' => 'evet',
	'Class:Contact/Attribute:notify/Value:yes+' => 'evet',
	'Class:Contact/Attribute:function' => 'İşlev',
	'Class:Contact/Attribute:function+' => '~~',
	'Class:Contact/Attribute:cis_list' => 'Cls',
	'Class:Contact/Attribute:cis_list+' => 'Bu kişiyle bağlantılı tüm yapılandırma öğeleri',
	'Class:Contact/Attribute:finalclass' => 'Tip',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Person' => 'Kişi',
	'Class:Person+' => '',
	'Class:Person/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Person/Attribute:name' => 'Soyad',
	'Class:Person/Attribute:name+' => '~~',
	'Class:Person/Attribute:first_name' => 'Adı',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_number' => 'Sicil numarası',
	'Class:Person/Attribute:employee_number+' => '~~',
	'Class:Person/Attribute:mobile_phone' => 'Cep telefonu',
	'Class:Person/Attribute:mobile_phone+' => '~~',
	'Class:Person/Attribute:location_id' => 'Konum',
	'Class:Person/Attribute:location_id+' => '~~',
	'Class:Person/Attribute:location_name' => 'Konum adı',
	'Class:Person/Attribute:location_name+' => '~~',
	'Class:Person/Attribute:manager_id' => 'Yönetici',
	'Class:Person/Attribute:manager_id+' => '~~',
	'Class:Person/Attribute:manager_name' => 'Yönetici adı',
	'Class:Person/Attribute:manager_name+' => '~~',
	'Class:Person/Attribute:team_list' => 'Ekipler',
	'Class:Person/Attribute:team_list+' => 'Bu kişinin ait olduğu tüm ekipler',
	'Class:Person/Attribute:tickets_list' => 'Çağrı kayıtları',
	'Class:Person/Attribute:tickets_list+' => 'Bu kişinin oluşturduğu tüm çağrı kayıtları',
	'Class:Person/Attribute:user_list' => 'Users~~',
	'Class:Person/Attribute:user_list+' => 'All the Users associated to this person~~',
	'Class:Person/Attribute:manager_id_friendlyname' => 'Yöneticinin kullandığı adı',
	'Class:Person/Attribute:manager_id_friendlyname+' => '~~',
	'Class:Person/Attribute:picture' => 'Picture~~',
	'Class:Person/Attribute:picture+' => '~~',
	'Class:Person/UniquenessRule:employee_number+' => 'The employee number must be unique in the organization~~',
	'Class:Person/UniquenessRule:employee_number' => 'there is already a person in \'$this->org_name$\' organization with the same employee number~~',
	'Class:Person/UniquenessRule:name+' => 'The employee name should be unique inside its organization~~',
	'Class:Person/UniquenessRule:name' => 'There is already a person in \'$this->org_name$\' organization with the same name~~',
	'Class:Person/Error:ChangingOrgDenied' => 'Impossible to move this person under organization \'%1$s\' as it would break his access to the User Portal, his associated user not being allowed on this organization~~',
));

//
// Class: Team
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Team' => 'Ekip',
	'Class:Team+' => '',
	'Class:Team/ComplementaryName' => '%1$s - %2$s~~',
	'Class:Team/Attribute:persons_list' => 'Üyeler',
	'Class:Team/Attribute:persons_list+' => 'Bu ekibe ait tüm kişiler',
	'Class:Team/Attribute:tickets_list' => 'Çağrı Kayıtları',
	'Class:Team/Attribute:tickets_list+' => 'Bu ekibe atanan tüm çağrı kayıtları',
));

//
// Class: Document
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Document' => 'Doküman',
	'Class:Document+' => '',
	'Class:Document/ComplementaryName' => '%1$s - %2$s - %3$s~~',
	'Class:Document/Attribute:name' => 'Adı',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Kurum',
	'Class:Document/Attribute:org_id+' => '~~',
	'Class:Document/Attribute:org_name' => 'Kurum Adı',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:documenttype_id' => 'Belge Türü',
	'Class:Document/Attribute:documenttype_id+' => '~~',
	'Class:Document/Attribute:documenttype_name' => 'Belge türü adı',
	'Class:Document/Attribute:documenttype_name+' => '~~',
	'Class:Document/Attribute:version' => 'Sürüm',
	'Class:Document/Attribute:version+' => '~~',
	'Class:Document/Attribute:description' => 'Tanımlama',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:status' => 'Durumu',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Taslak',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Geçersiz',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Yayınlanan',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:cis_list' => 'CI\'lar',
	'Class:Document/Attribute:cis_list+' => 'Bu belgeye bağlı tüm yapılandırma öğeleri',
	'Class:Document/Attribute:finalclass' => 'Belge Türü',
	'Class:Document/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: DocumentFile
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DocumentFile' => 'Belge dosyası',
	'Class:DocumentFile+' => '~~',
	'Class:DocumentFile/Attribute:file' => 'Dosya',
	'Class:DocumentFile/Attribute:file+' => '~~',
));

//
// Class: DocumentNote
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DocumentNote' => 'Belge Notu',
	'Class:DocumentNote+' => '~~',
	'Class:DocumentNote/Attribute:text' => 'Metin',
	'Class:DocumentNote/Attribute:text+' => '~~',
));

//
// Class: DocumentWeb
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DocumentWeb' => 'Belge Web',
	'Class:DocumentWeb+' => '~~',
	'Class:DocumentWeb/Attribute:url' => 'URL',
	'Class:DocumentWeb/Attribute:url+' => '~~',
));

//
// Class: Typology
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Typology' => 'Tipoloji',
	'Class:Typology+' => '~~',
	'Class:Typology/Attribute:name' => 'İsim',
	'Class:Typology/Attribute:name+' => '~~',
	'Class:Typology/Attribute:finalclass' => 'Tip',
	'Class:Typology/Attribute:finalclass+' => 'Name of the final class~~',
));

//
// Class: DocumentType
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:DocumentType' => 'Belge Türü',
	'Class:DocumentType+' => '~~',
));

//
// Class: ContactType
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ContactType' => 'İletişim Tipi',
	'Class:ContactType+' => '~~',
));

//
// Class: lnkPersonToTeam
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkPersonToTeam' => 'Bağlantılı Kişi / Ekip',
	'Class:lnkPersonToTeam+' => '~~',
	'Class:lnkPersonToTeam/Name' => '%1$s / %2$s~~',
	'Class:lnkPersonToTeam/Name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_id' => 'Ekip',
	'Class:lnkPersonToTeam/Attribute:team_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:team_name' => 'Ekip adı',
	'Class:lnkPersonToTeam/Attribute:team_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_id' => 'Kişi',
	'Class:lnkPersonToTeam/Attribute:person_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:person_name' => 'Kişi Adı',
	'Class:lnkPersonToTeam/Attribute:person_name+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_id' => 'Rol',
	'Class:lnkPersonToTeam/Attribute:role_id+' => '~~',
	'Class:lnkPersonToTeam/Attribute:role_name' => 'Rol Adı',
	'Class:lnkPersonToTeam/Attribute:role_name+' => '~~',
));

//
// Application Menu
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:DataAdministration' => 'Veri yönetimi',
	'Menu:DataAdministration+' => 'Veri yönetimi',
	'Menu:Catalogs' => 'Kataloglar',
	'Menu:Catalogs+' => 'Veri tipleri',
	'Menu:Audit' => 'Denetleme',
	'Menu:Audit+' => 'Denetleme',
	'Menu:CSVImport' => 'CSV dışardan al',
	'Menu:CSVImport+' => 'Çoklu yaratım veya güncelleme',
	'Menu:Organization' => 'Kurumlar',
	'Menu:Organization+' => 'Tüm Kurumlar',
	'Menu:ConfigManagement' => 'Konfigürasyon yönetimi',
	'Menu:ConfigManagement+' => 'Konfigürasyon yönetimi',
	'Menu:ConfigManagementCI' => 'Konfigürasyon kalemleri',
	'Menu:ConfigManagementCI+' => 'Konfigürasyon kalemleri',
	'Menu:ConfigManagementOverview' => 'Özet',
	'Menu:ConfigManagementOverview+' => 'Özet',
	'Menu:Contact' => 'İrtibatlar',
	'Menu:Contact+' => 'İrtibatlar',
	'Menu:Contact:Count' => '%1$d',
	'Menu:Person' => 'Kişiler',
	'Menu:Person+' => 'Tüm Kişiler',
	'Menu:Team' => 'Ekipler',
	'Menu:Team+' => 'Tüm ekipler',
	'Menu:Document' => 'Dokümanlar',
	'Menu:Document+' => 'Tüm dokümanlar',
	'Menu:Location' => 'Yerleşkeler',
	'Menu:Location+' => 'Tüm yerleşkeler',
	'Menu:NewContact' => 'Yeni İrtibat',
	'Menu:NewContact+' => 'Yeni İrtibat',
	'Menu:SearchContacts' => 'İrtibat ara',
	'Menu:SearchContacts+' => 'İrtibat ara',
	'Menu:ConfigManagement:Shortcuts' => 'Kısalyollar',
	'Menu:ConfigManagement:AllContacts' => 'Tüm irtibatlar: %1$d',
	'Menu:Typology' => 'Tipoloji Yapılandırması',
	'Menu:Typology+' => 'Tipoloji Yapılandırması',
	'UI_WelcomeMenu_AllConfigItems' => 'Özet',
	'Menu:ConfigManagement:Typology' => 'Tipoloji Yapılandırması',
));

// Add translation for Fieldsets

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Person:info' => 'Genel Bilgi',
	'User:info' => 'Genel Bilgi',
	'User:profiles' => 'Profiles (minimum one)~~',
	'Person:personal_info' => 'Personal information~~',
	'Person:notifiy' => 'Bildirim',
));

// Themes
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'theme:fullmoon' => 'Full moon~~',
	'theme:test-red' => 'Test instance (Red)~~',
));
