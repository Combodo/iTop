<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//
//
// Class: AuditCategory
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:AuditCategory' => 'Denetleme Kategorisi',
	'Class:AuditCategory+' => 'Denetlemedeki kategori',
	'Class:AuditCategory/Attribute:name' => 'Kategori Adı',
	'Class:AuditCategory/Attribute:name+' => 'Kategornin kısa adı',
	'Class:AuditCategory/Attribute:description' => 'Kategori tanımlaması',
	'Class:AuditCategory/Attribute:description+' => 'Kategori tanımlaması',
	'Class:AuditCategory/Attribute:definition_set' => 'Tanımlama seti',
	'Class:AuditCategory/Attribute:definition_set+' => 'Denetlenecek nesneler için OQL ifadesi',
	'Class:AuditCategory/Attribute:rules_list' => 'Denetlem kuralları',
	'Class:AuditCategory/Attribute:rules_list+' => 'Kategori için denetleme kuralları',
));

//
// Class: AuditRule
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:AuditRule' => 'Denetleme Kuralı',
	'Class:AuditRule+' => 'Denetleme Kategorisi kuralı',
	'Class:AuditRule/Attribute:name' => 'Kural Adı',
	'Class:AuditRule/Attribute:name+' => 'Kural Adı',
	'Class:AuditRule/Attribute:description' => 'Kural tanımlaması',
	'Class:AuditRule/Attribute:description+' => 'Kural tanımlaması',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => 'Çalıştırılacak Sorgu',
	'Class:AuditRule/Attribute:query+' => 'Çalıştırılcak OQL ifadesi',
	'Class:AuditRule/Attribute:valid_flag' => 'Geçerli nesneler?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Kural geçerli nesne döndürüse doğru, diğer durumda yanlış',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'doğru',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'doğru',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'yanlış',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'yanlış',
	'Class:AuditRule/Attribute:category_id' => 'Kategori',
	'Class:AuditRule/Attribute:category_id+' => 'Kuralın kategorisi',
	'Class:AuditRule/Attribute:category_name' => 'Kategori',
	'Class:AuditRule/Attribute:category_name+' => 'Kural için kategori adı',
));

//
// Class: QueryOQL
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Query' => 'Query~~',
	'Class:Query+' => 'A query is a data set defined in a dynamic way~~',
	'Class:Query/Attribute:name' => 'Name~~',
	'Class:Query/Attribute:name+' => 'Identifies the query~~',
	'Class:Query/Attribute:description' => 'Description~~',
	'Class:Query/Attribute:description+' => 'Long description for the query (purpose, usage, etc.)~~',
	'Class:QueryOQL/Attribute:fields' => 'Fields~~',
	'Class:QueryOQL/Attribute:fields+' => 'Comma separated list of attributes (or alias.attribute) to export~~',
	'Class:QueryOQL' => 'OQL Query~~',
	'Class:QueryOQL+' => 'A query based on the Object Query Language~~',
	'Class:QueryOQL/Attribute:oql' => 'Expression~~',
	'Class:QueryOQL/Attribute:oql+' => 'OQL Expression~~',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:User' => 'Kullanıcı',
	'Class:User+' => 'Kullanıcı',
	'Class:User/Attribute:finalclass' => 'Hesap tipi',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'İrtibat (kişi)',
	'Class:User/Attribute:contactid+' => 'İrtibat detayları',
	'Class:User/Attribute:org_id' => 'Kurum',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => 'Soyadı',
	'Class:User/Attribute:last_name+' => 'İrtibatın soyadı',
	'Class:User/Attribute:first_name' => 'Adı',
	'Class:User/Attribute:first_name+' => 'İrtibatın adı',
	'Class:User/Attribute:email' => 'E-posta',
	'Class:User/Attribute:email+' => 'Kişinin e-posta adresi',
	'Class:User/Attribute:login' => 'Kullanıcı adı',
	'Class:User/Attribute:login+' => 'Kullanıcı adı',
	'Class:User/Attribute:language' => 'Dil',
	'Class:User/Attribute:language+' => 'Dil',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profiller',
	'Class:User/Attribute:profile_list+' => 'Kullanıcı rolü',
	'Class:User/Attribute:allowed_org_list' => 'Erişim yetkisi verilen kurumlar',
	'Class:User/Attribute:allowed_org_list+' => 'Kullanıcın erişime yetkili olduğu kurumlar. Kurum tanımlanmaz ise sınırlama olmaz.',
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',

	'Class:User/Error:LoginMustBeUnique' => 'Kullanıcı adı tekil olmalı - "%1s" mevcut bir kullanıcıya ait.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'En az bir profil kullanıcıya atanmalı',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'User Internal~~',
	'Class:UserInternal+' => 'User defined within iTop~~',
));

//
// Class: URP_Profiles
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_Profiles' => 'Profil',
	'Class:URP_Profiles+' => 'Kullanıcı profili',
	'Class:URP_Profiles/Attribute:name' => 'Adı',
	'Class:URP_Profiles/Attribute:name+' => 'Profil adı',
	'Class:URP_Profiles/Attribute:description' => 'Tanımlama',
	'Class:URP_Profiles/Attribute:description+' => 'Profil tanımlama',
	'Class:URP_Profiles/Attribute:user_list' => 'Kullanıcılar',
	'Class:URP_Profiles/Attribute:user_list+' => 'bu rolü kullanan kullanıcılar',
));

//
// Class: URP_Dimensions
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_Dimensions' => 'boyut',
	'Class:URP_Dimensions+' => 'uygulama boyutları (silo kullanımları)',
	'Class:URP_Dimensions/Attribute:name' => 'Adı',
	'Class:URP_Dimensions/Attribute:name+' => 'Boyut adı',
	'Class:URP_Dimensions/Attribute:description' => 'Tanımlama',
	'Class:URP_Dimensions/Attribute:description+' => 'Tanımlama',
	'Class:URP_Dimensions/Attribute:type' => 'Tip',
	'Class:URP_Dimensions/Attribute:type+' => 'sınıf adı veya veri tipi (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_UserProfile' => 'Kullanıcı Profili',
	'Class:URP_UserProfile+' => 'Kullanıcı Profili',
	'Class:URP_UserProfile/Attribute:userid' => 'Kullanıcı',
	'Class:URP_UserProfile/Attribute:userid+' => 'Kullanıcı hesabı',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Kullanıcı adı',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Kullanıcı hesabı',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Kullanıcı profili',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Profil adı',
	'Class:URP_UserProfile/Attribute:reason' => 'Sebep',
	'Class:URP_UserProfile/Attribute:reason+' => 'Kullanıcının bu rolü alma sebebini açıklayınız',
));

//
// Class: URP_UserOrg
//


Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_UserOrg' => 'Kullanıcı Kurumu',
	'Class:URP_UserOrg+' => 'İzin verilen kurumlar',
	'Class:URP_UserOrg/Attribute:userid' => 'Kullanıcı',
	'Class:URP_UserOrg/Attribute:userid+' => 'Kullanıcı hesabı',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Kullanıcı',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Kullanıcı hesabı',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Kurum',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Erişim yetkisi kurumlar',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Kurumu',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Erişim yetkisi verilen kurumlar',
	'Class:URP_UserOrg/Attribute:reason' => 'Sebep',
	'Class:URP_UserOrg/Attribute:reason+' => 'Kullanıcının bu rolü alma sebebini açıklayınız',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Boyut',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'uygulama boyutu',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Boyut',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'uygulama boyutu',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'profil kullanımı',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profil adı',
	'Class:URP_ProfileProjection/Attribute:value' => 'Değer ifadesi',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL ifadesi (kullanıcı $user) | sabit |  | +özellik kodu',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Hedef özellik kodu (opsiyonel)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_ClassProjection' => 'sınıf projeksiyonu',
	'Class:URP_ClassProjection+' => 'sınıf projeksiyonu',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Boyut',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'uygulama boyutu',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Boyut',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'uygulama boyutu',
	'Class:URP_ClassProjection/Attribute:class' => 'Sınıf',
	'Class:URP_ClassProjection/Attribute:class+' => 'Hedef sınıf',
	'Class:URP_ClassProjection/Attribute:value' => 'Değer ifadesi',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL ifadesi (kullanıcı $user) | sabit |  | +özellik kodu',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Özellik',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Hedef özellik kodu (opsiyonel)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_ActionGrant' => 'işlem yetkileri',
	'Class:URP_ActionGrant+' => 'sınıf üzerindeki yetkiler',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Kullanım profili',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Kullanım profili',
	'Class:URP_ActionGrant/Attribute:class' => 'Sınıf',
	'Class:URP_ActionGrant/Attribute:class+' => 'Hedef sınıf',
	'Class:URP_ActionGrant/Attribute:permission' => 'Erişim yetkisi',
	'Class:URP_ActionGrant/Attribute:permission+' => 'yetkili veya yetkisiz?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'evet',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'evet',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'hayır',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'hayır',
	'Class:URP_ActionGrant/Attribute:action' => 'İşlem',
	'Class:URP_ActionGrant/Attribute:action+' => 'verilen sınıf üzerinde uygulanacak işlemler',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_StimulusGrant' => 'uyarı yetkileri',
	'Class:URP_StimulusGrant+' => 'nesnenin yaşam döngüsündeki uyarı yetkileri',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Kullanım profili',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Kullanım profili',
	'Class:URP_StimulusGrant/Attribute:class' => 'Sınıf',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Hedef sınıf',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Yetki',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'yetkili veya yetkisiz?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'evet',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'evet',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'hayır',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'hayır',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Uyarı',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'uyarı kodu',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:URP_AttributeGrant' => 'özellik yetkisi',
	'Class:URP_AttributeGrant+' => 'özellik seviyesinde yetki',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'İzin verilen işlem',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'İşlem izni',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Özellik',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Özellik kodu',
));

//
// Class: UserDashboard
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:UserDashboard' => 'User dashboard~~',
	'Class:UserDashboard+' => '~~',
	'Class:UserDashboard/Attribute:user_id' => 'User~~',
	'Class:UserDashboard/Attribute:user_id+' => '~~',
	'Class:UserDashboard/Attribute:menu_code' => 'Menu code~~',
	'Class:UserDashboard/Attribute:menu_code+' => '~~',
	'Class:UserDashboard/Attribute:contents' => 'Contents~~',
	'Class:UserDashboard/Attribute:contents+' => '~~',
));

//
// Expression to Natural language
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'BooleanLabel:yes' => 'evet',
	'BooleanLabel:no' => 'hayır',
	'UI:Login:Title' => 'iTop login~~',
	'Menu:WelcomeMenu' => 'Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'iTop\'a Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'iTop\'a Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'iTop\'a Hoşgeldiniz',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop açık kaynak Bilişim İşlem Potalıdır.</p>
<ul>Kapsamı:
<li>Bilişim altyapısının tanımlandığı ve dokümante edildiği Konfigürasyon Yönetimi CMDB (Configuration management database)modülü.</li>
<li>Bilişim altyapısı ile ilgili tüm olayların takibi.</li>
<li>Bilişim altyapısının değişim yönetimi.</li>
<li>Bilinen hatalar bilgi kütüphanesi.</li>
<li>Planlı kesintilerin kayıt altına alınması ve ilgililerin uyarılması.</li>
<li>Özet gösterge ekranları</li>
</ul>
<p>Tüm modüller bağımsız olarak, adım adım kurulabilir.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop servis sağlayıcı maktığı ile hazırlanmış olup, birden fazla müşteri ve kuruma kolaylıkla hizmet vermeye imkan sağlar.
<ul>iTop, zengin iş süreçleri tanımlama imkanıyla:
<li>Bilişim yönetim etkinliğini</li>
<li>Operasyon performansını</li>
<li>Müşteri memnuniyetini ve yönetimin iş performansı hakkında bilgi sahibi olmasını sağlar.</li>
</ul>
</p>
<p>iTop mevcut Bilşim altyapınızla entegre edilmeye açıktır.</p>
<p>
<ul>Yeni nesil operasyonel Bilişim portalı :
<li>Bilişim ortamının daha iyi yönetilmesini.</li>
<li>ITIL süreçlerinin kendi başınıza uygulanmaya.</li>
<li>İşletmenin en önemli kaynağı olan dokümantasyonu yönetmesine imkan sağlar.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Açık istekler: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'İsteklerim',
	'UI:WelcomeMenu:OpenIncidents' => 'Açık Arızalar: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Konfigürasyon Kalemleri: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Bana atanan hatalar',
	'UI:AllOrganizations' => ' Tüm Kurumlar ',
	'UI:YourSearch' => 'Arama',
	'UI:LoggedAsMessage' => '%1$s kullanıcısı ile bağlanıldı',
	'UI:LoggedAsMessage+Admin' => '%1$s (Administrator) kullanıcısı ile bağlanıldı',
	'UI:Button:Logoff' => 'Çıkış',
	'UI:Button:GlobalSearch' => 'Arama',
	'UI:Button:Search' => ' Arama ',
	'UI:Button:Query' => ' Sorgu ',
	'UI:Button:Ok' => 'Tamam',
	'UI:Button:Save' => 'Save~~',
	'UI:Button:Cancel' => 'İptal',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Uygula',
	'UI:Button:Back' => ' << Geri ',
	'UI:Button:Restart' => ' |<< Restart ~~',
	'UI:Button:Next' => ' İleri >> ',
	'UI:Button:Finish' => ' Bitir ',
	'UI:Button:DoImport' => ' Dışardan Veri alı çalıştır ! ',
	'UI:Button:Done' => ' Biiti ',
	'UI:Button:SimulateImport' => ' Veri alışını simule et ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Değerlendir ',
	'UI:Button:Evaluate:Title' => ' Değerlendir (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Ekle... ',
	'UI:Button:BrowseObjects' => ' Listele... ',
	'UI:Button:Add' => ' Ekle ',
	'UI:Button:AddToList' => ' << Ekle ',
	'UI:Button:RemoveFromList' => ' Sil >> ',
	'UI:Button:FilterList' => ' Filtreleme... ',
	'UI:Button:Create' => ' Yarat ',
	'UI:Button:Delete' => ' Sil ! ',
	'UI:Button:Rename' => ' Rename... ~~',
	'UI:Button:ChangePassword' => ' Şifre değiştir ',
	'UI:Button:ResetPassword' => ' Şifreyi sıfırla ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Ara',
	'UI:ClickToCreateNew' => 'Yeni %1$s yarat',
	'UI:SearchFor_Class' => '%1$s Arama',
	'UI:NoObjectToDisplay' => 'Görüntülenecek nesne bulunamadı.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'link_attr tanımlandığında object_id alanı zorunludur. Görüntülme (Display) şablonun tanımlamasını kontrol ediniz.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'link_attr tanımlandığında target_attr alanı zorunludur. Görüntülme (Display) şablonun tanımlamasını kontrol ediniz.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'group_by alanı zorunludur. Görüntülme (Display) şablonun tanımlamasını kontrol ediniz.',
	'UI:Error:InvalidGroupByFields' => 'group by geçersiz alan listesi: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Hata: blok için desteklenmeyen stil: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Hatalı ilişki tanımı: yönetilecek sınıfa: %1$s ait ilişki anahtarı (an external key) sınıfında %2$s bulunamadı',
	'UI:Error:Object_Class_Id_NotFound' => 'Nesne: %1$s:%2$d bulunamadı.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Hata: Alanlar arasında döngüsel bağımlılık (Circular reference in the dependencies) tespit edildi. Veri modelinizi kontrol ediniz.',
	'UI:Error:UploadedFileTooBig' => 'Yüklenmek istenen dosya çok büyük. (üst sınır %1$s). PHP configürasyonunu kontrol ediniz (upload_max_filesize ve post_max_size parametrelerini düzenleyiniz).',
	'UI:Error:UploadedFileTruncated.' => 'Yüklenen dosyanın tamamı yüklenemedi !',
	'UI:Error:NoTmpDir' => 'Gecici dizi (temporary directory) tanımlı değil.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Geçici dosya diske yazılamadı. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Dosya yükleme dosya uzantısı nedeniyle duruduruldu. (Dosya adı = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Dosya yükleme bilinmeyen bir sebeple başarısız oldu. (Hata kodu = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Hata: Bu operasyon için %1$s parametresi tanımlanmalı.',
	'UI:Error:2ParametersMissing' => 'Hata: Bu operasyon için %1$s ve %2$s parametreleri tanımlanmalı.',
	'UI:Error:3ParametersMissing' => 'Hata: Bu operasyon için %1$s, %2$s ve %3$s parametreleri tanımlanmalı.',
	'UI:Error:4ParametersMissing' => 'Hata: Bu operasyon için %1$s, %2$s, %3$s ve %4$s parametreleri tanımlanmalı.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Hata: hatalı OQL sorgusu: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Sorgu sırasında hata oluştu: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Hata: nesne hali hazırda güncellendi.',
	'UI:Error:ObjectCannotBeUpdated' => 'Hata: nesne güncellenemedi.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Hata: nesne hali hazırda silinmiş!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => '%1$s sınıfına ait nesnelerin toplu silimine yetkiniz yok.',
	'UI:Error:DeleteNotAllowedOn_Class' => '%1$s sınıfına ait nesnelerin silimine yetkiniz yok.',
	'UI:Error:BulkModifyNotAllowedOn_Class' => '%1$s sınıfına ait nesnelerin toplu güncellenmesine yetkiniz yok.',
	'UI:Error:ObjectAlreadyCloned' => 'Hata: nesne hali hazırda klonlanmış!',
	'UI:Error:ObjectAlreadyCreated' => 'Hata: nesne hali hazırda yaratılmış!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Hata: "%3$s" durumundaki %2$s nesnesi için "%1$s" uyarısı geçersizdir.',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

	'UI:GroupBy:Count' => 'Say',
	'UI:GroupBy:Count+' => 'Eleman sayısı',
	'UI:CountOfObjects' => 'Kritere uyan %1$d nesne bulundu.',
	'UI_CountOfObjectsShort' => '%1$d nesne.',
	'UI:NoObject_Class_ToDisplay' => '%1$s nesne listelenecek',
	'UI:History:LastModified_On_By' => '%1$s tarihinde %2$s tarafından değiştirilmiş.',
	'UI:HistoryTab' => 'Tarihçe',
	'UI:NotificationsTab' => 'Uyarılar',
	'UI:History:BulkImports' => 'History~~',
	'UI:History:BulkImports+' => 'List of CSV imports (latest import first)~~',
	'UI:History:BulkImportDetails' => 'Changes resulting from the CSV import performed on %1$s (by %2$s)~~',
	'UI:History:Date' => 'Tarih',
	'UI:History:Date+' => 'Değişiklik tarihi',
	'UI:History:User' => 'Kullanıcı',
	'UI:History:User+' => 'Değişikliğ yapan kullanıcı',
	'UI:History:Changes' => 'Değişiklikler',
	'UI:History:Changes+' => 'Nesneye yapılan değişiklikler',
	'UI:History:StatsCreations' => 'Created~~',
	'UI:History:StatsCreations+' => 'Count of objects created~~',
	'UI:History:StatsModifs' => 'Modified~~',
	'UI:History:StatsModifs+' => 'Count of objects modified~~',
	'UI:History:StatsDeletes' => 'Deleted~~',
	'UI:History:StatsDeletes+' => 'Count of objects deleted~~',
	'UI:Loading' => 'Yükleniyor...',
	'UI:Menu:Actions' => 'İşlemler',
	'UI:Menu:OtherActions' => 'Diğer İşlemler',
	'UI:Menu:New' => 'Yeni...',
	'UI:Menu:Add' => 'Ekle...',
	'UI:Menu:Manage' => 'Yönet...',
	'UI:Menu:EMail' => 'e-posta',
	'UI:Menu:CSVExport' => 'CSV olarak dışarı ver...',
	'UI:Menu:Modify' => 'Düzenle...',
	'UI:Menu:Delete' => 'Sil...',
	'UI:Menu:BulkDelete' => 'Sil...',
	'UI:UndefinedObject' => 'tanımsız',
	'UI:Document:OpenInNewWindow:Download' => 'Yeni pencerede aç: %1$s, Karşıdan yükle: %2$s',
	'UI:SplitDateTime-Date' => 'date~~',
	'UI:SplitDateTime-Time' => 'time~~',
	'UI:TruncatedResults' => '%1$d / %2$d',
	'UI:DisplayAll' => 'Hepsini göster',
	'UI:CollapseList' => 'Gizle',
	'UI:CountOfResults' => '%1$d nesne',
	'UI:ChangesLogTitle' => 'değişiklik kaydı (%1$d):',
	'UI:EmptyChangesLogTitle' => 'deiğişiklik kaydı boş',
	'UI:SearchFor_Class_Objects' => '%1$s nesnelerini ara',
	'UI:OQLQueryBuilderTitle' => 'OQL Sorgu hazırlama',
	'UI:OQLQueryTab' => 'OQL Sorgu',
	'UI:SimpleSearchTab' => 'Basit arama',
	'UI:Details+' => 'Detaylar',
	'UI:SearchValue:Any' => '* Herhangi *',
	'UI:SearchValue:Mixed' => '* karışık *',
	'UI:SearchValue:NbSelected' => '# selected~~',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- Birini seçiniz --',
	'UI:Login:Welcome' => 'iTop\'a Hoşgeldiniz!',
	'UI:Login:IncorrectLoginPassword' => 'Hatalı kullanıcı/şifre tekrar deneyiniz.',
	'UI:Login:IdentifyYourself' => 'Devam etmeden önce kendinizi tanıtınız',
	'UI:Login:UserNamePrompt' => 'Kullanıcı Adı',
	'UI:Login:PasswordPrompt' => 'Şifre',
	'UI:Login:ForgotPwd' => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm' => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+' => 'iTop can send you an email in which you will find instructions to follow to reset your account.~~',
	'UI:Login:ResetPassword' => 'Send now!~~',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s~~',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login~~',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.~~',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.~~',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated to a person.~~',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.~~',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Reset your iTop password~~',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your iTop password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',

	'UI:ResetPwd-Title' => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready' => 'The password has been changed.~~',
	'UI:ResetPwd-Login' => 'Click here to login...~~',

	'UI:Login:About' => '~~',
	'UI:Login:ChangeYourPassword' => 'Şifre Değiştir',
	'UI:Login:OldPasswordPrompt' => 'Mevcut şifre',
	'UI:Login:NewPasswordPrompt' => 'Yeni şifre',
	'UI:Login:RetypeNewPasswordPrompt' => 'Yeni şifre tekrar',
	'UI:Login:IncorrectOldPassword' => 'Hata: mevcut şifre hatalı',
	'UI:LogOffMenu' => 'Çıkış',
	'UI:LogOff:ThankYou' => 'iTop Kullanıdığınız için teşekkürler',
	'UI:LogOff:ClickHereToLoginAgain' => 'Tekrar bağlanmak için tıklayınız...',
	'UI:ChangePwdMenu' => 'Şifre değiştir...',
	'UI:Login:PasswordChanged' => 'Password successfully set!~~',
	'UI:AccessRO-All' => 'iTop is read-only~~',
	'UI:AccessRO-Users' => 'iTop is read-only for end-users~~',
	'UI:ApplicationEnvironment' => 'Application environment: %1$s~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Yeni şifre eşlenmedi !',
	'UI:Button:Login' => 'iTop\'a Giriş',
	'UI:Login:Error:AccessRestricted' => 'iTop erişim sınırlandırıldı. Sistem yöneticisi ile irtibata geçiniz',
	'UI:Login:Error:AccessAdmin' => 'Erişim sistem yönetci hesaplaları ile mümkün. Sistem yöneticisi ile irtibata geçiniz.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne' => '-- Birini seçiniz --',
	'UI:CSVImport:MappingNotApplicable' => '-- alanı ihmal et --',
	'UI:CSVImport:NoData' => 'Boş veri seti..., veri giriniz!',
	'UI:Title:DataPreview' => 'Veri öngörüntüleme',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Hata: Veri sadece bir kolon içeriyor. Doğru ayıraç karakteri seçtiniz mi ?',
	'UI:CSVImport:FieldName' => 'Alan %1$d',
	'UI:CSVImport:DataLine1' => 'Veri Satırı 1',
	'UI:CSVImport:DataLine2' => 'Veri Satırı 2',
	'UI:CSVImport:idField' => 'id (Tekil anahtar)',
	'UI:Title:BulkImport' => 'iTop - Toplu giriş',
	'UI:Title:BulkImport+' => 'CSV içeri aktarma aracı',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronization of %1$d objects of class %2$s~~',
	'UI:CSVImport:ClassesSelectOne' => '-- Birini seçiniz --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Hata: "%1$s" hatalı kod, çünkü "%2$s" ile "%3$s" tekil ilişkide değil',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d adet nesne değişmeyecek.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d adet nesne değiştirilecek.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d adet nesne eklenecek.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d adet nesnede hata oluştu.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d adet nesne değişmedi.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d adet nesne güncellendi.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d adet nesne eklendi.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d adet nesnede hata tespit edildi.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: CSV veri seçenekleri',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Veri eşleme',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Verinin içeri aktarım simülasyonu',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: İçeri aktarım tamamlandı',
	'UI:CSVImport:LinesNotImported' => 'Satırlar yüklenemedi:',
	'UI:CSVImport:LinesNotImported+' => 'Aşağıdaki satırlar hata nedeniyle yüklenemedi',
	'UI:CSVImport:SeparatorComma+' => ', (virgül)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (noktalı virgül)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'diğer:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (çift tırnak)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (tırnak)',
	'UI:CSVImport:QualifierOther' => 'diğer:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'İlk satırı başlık olarak değerlendir(kolon isimleri)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Skip %1$s line(s) at the beginning of the file',
	'UI:CSVImport:CSVDataPreview' => 'CSV Veri Görüntüleme',
	'UI:CSVImport:SelectFile' => 'İçeri aktarılacak dosyayı seçiniz:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Dosyadan oku',
	'UI:CSVImport:Tab:CopyPaste' => 'Veriyi kopyala yapıştır',
	'UI:CSVImport:Tab:Templates' => 'Şablonlar',
	'UI:CSVImport:PasteData' => 'İçeri aktarılacak veriyi yapıştır:',
	'UI:CSVImport:PickClassForTemplate' => 'İndirilecek şablonu seçiniz: ',
	'UI:CSVImport:SeparatorCharacter' => 'Ayıraç karakteri:',
	'UI:CSVImport:TextQualifierCharacter' => 'Metin belirteç karakteri',
	'UI:CSVImport:CommentsAndHeader' => 'Yorum ve başlık',
	'UI:CSVImport:SelectClass' => 'İçeri aktarılacak sınıfı seçiniz:',
	'UI:CSVImport:AdvancedMode' => 'Uzman modu',
	'UI:CSVImport:AdvancedMode+' => 'Uzman modunda (In advanced mode) "id" (primary key) alanı nesnenin güncellenmesi ve adının değiştirilmesi için kullanılabilir."id" (mevcut ise) alanı tek sorgu kriteri olarak kullnılabilri ve diğer sorgu kriterleri ile birleştirilmez.',
	'UI:CSVImport:SelectAClassFirst' => 'Eşlemeyi yapmak için önce sınıfı seçiniz.',
	'UI:CSVImport:HeaderFields' => 'Alanlar',
	'UI:CSVImport:HeaderMappings' => 'Eşlemeler',
	'UI:CSVImport:HeaderSearch' => 'Arama?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Lütfen tüm alanlar için alan eşlemesini yapınız.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Lütfen en az bir sorgu kriteri seçiniz.',
	'UI:CSVImport:Encoding' => 'Karakter kodlaması',
	'UI:UniversalSearchTitle' => 'iTop - Genel arama',
	'UI:UniversalSearch:Error' => 'Hata: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Aranacak sınıfı seçiniz: ',

	'UI:CSVReport-Value-Modified' => 'Modified~~',
	'UI:CSVReport-Value-SetIssue' => 'Could not be changed - reason: %1$s~~',
	'UI:CSVReport-Value-ChangeIssue' => 'Could not be changed to %1$s - reason: %2$s~~',
	'UI:CSVReport-Value-NoMatch' => 'No match~~',
	'UI:CSVReport-Value-Missing' => 'Missing mandatory value~~',
	'UI:CSVReport-Value-Ambiguous' => 'Ambiguous: found %1$s objects~~',
	'UI:CSVReport-Row-Unchanged' => 'unchanged~~',
	'UI:CSVReport-Row-Created' => 'created~~',
	'UI:CSVReport-Row-Updated' => 'updated %1$d cols~~',
	'UI:CSVReport-Row-Disappeared' => 'disappeared, changed %1$d cols~~',
	'UI:CSVReport-Row-Issue' => 'Issue: %1$s~~',
	'UI:CSVReport-Value-Issue-Null' => 'Null not allowed~~',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object not found~~',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Found %1$d matches~~',
	'UI:CSVReport-Value-Issue-Readonly' => 'The attribute \'%1$s\' is read-only and cannot be modified (current value: %2$s, proposed value: %3$s)~~',
	'UI:CSVReport-Value-Issue-Format' => 'Failed to process input: %1$s~~',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Unexpected value for attribute \'%1$s\': no match found, check spelling~~',
	'UI:CSVReport-Value-Issue-Unknown' => 'Unexpected value for attribute \'%1$s\': %2$s~~',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributes not consistent with each others: %1$s~~',
	'UI:CSVReport-Row-Issue-Attribute' => 'Unexpected attribute value(s)~~',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Could not be created, due to missing external key(s): %1$s~~',
	'UI:CSVReport-Row-Issue-DateFormat' => 'wrong date format~~',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'failed to reconcile~~',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'ambiguous reconciliation~~',
	'UI:CSVReport-Row-Issue-Internal' => 'Internal error: %1$s, %2$s~~',

	'UI:CSVReport-Icon-Unchanged' => 'Unchanged~~',
	'UI:CSVReport-Icon-Modified' => 'Modified~~',
	'UI:CSVReport-Icon-Missing' => 'Missing~~',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missing object: will be updated~~',
	'UI:CSVReport-Object-MissingUpdated' => 'Missing object: updated~~',
	'UI:CSVReport-Icon-Created' => 'Created~~',
	'UI:CSVReport-Object-ToCreate' => 'Object will be created~~',
	'UI:CSVReport-Object-Created' => 'Object created~~',
	'UI:CSVReport-Icon-Error' => 'Error~~',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s~~',
	'UI:CSVReport-Object-Ambiguous' => 'AMBIGUOUS: %1$s~~',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% of the loaded objects have errors and will be ignored.~~',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% of the loaded objects will be created.~~',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% of the loaded objects will be modified.~~',

	'UI:CSVExport:AdvancedMode' => 'Advanced mode~~',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode, several columns are added to the export: the id of the object, the id of external keys and their reconciliation attributes.~~',
	'UI:CSVExport:LostChars' => 'Encoding issue~~',
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. iTop has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').~~',

	'UI:Audit:Title' => 'iTop - CMDB Denetleme',
	'UI:Audit:InteractiveAudit' => 'Etkileşimli Denetleme',
	'UI:Audit:HeaderAuditRule' => 'Denetleme Kuralı',
	'UI:Audit:HeaderNbObjects' => 'Nesne Sayısı',
	'UI:Audit:HeaderNbErrors' => 'Hata sayısı',
	'UI:Audit:PercentageOk' => '% Tamam',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.~~',

	'UI:RunQuery:Title' => 'iTop - OQL Sorgu değerlendirme',
	'UI:RunQuery:QueryExamples' => 'Sorgu örnekleri',
	'UI:RunQuery:HeaderPurpose' => 'Amaç',
	'UI:RunQuery:HeaderPurpose+' => 'Sorgu açıklaması',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL ifadesi',
	'UI:RunQuery:HeaderOQLExpression+' => 'OQL yapısında sorgu',
	'UI:RunQuery:ExpressionToEvaluate' => 'Değerlendirilecek ifade: ',
	'UI:RunQuery:MoreInfo' => 'Sorgu hakkında detaylı bilgi: ',
	'UI:RunQuery:DevelopedQuery' => 'Yeniden düzenlenen sorgu: ',
	'UI:RunQuery:SerializedFilter' => 'Özel filtre: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Sorgu sırasında hata oluştu: %1$s',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries~~',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
	'UI:Schema:Title' => 'iTop objects schema',
	'UI:Schema:CategoryMenuItem' => 'Kategori <b>%1$s</b>',
	'UI:Schema:Relationships' => 'İlişkiler',
	'UI:Schema:AbstractClass' => 'Soyut sınıf: bu sınıftan nesne türetilemez.',
	'UI:Schema:NonAbstractClass' => 'Soyut olmayan sınıf: bu sınıftan nesne türetilebilir.',
	'UI:Schema:ClassHierarchyTitle' => 'Sınıf ilişkisi',
	'UI:Schema:AllClasses' => 'Tüm sınıflar',
	'UI:Schema:ExternalKey_To' => 'Harici anahtar %1$s',
	'UI:Schema:Columns_Description' => 'Kolonlar: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Öndeğer: "%1$s"',
	'UI:Schema:NullAllowed' => 'Boş olamaz',
	'UI:Schema:NullNotAllowed' => 'Boş olabilir',
	'UI:Schema:Attributes' => 'Özellikler',
	'UI:Schema:AttributeCode' => 'Özellik kodu',
	'UI:Schema:AttributeCode+' => 'Özellik için dahili kod',
	'UI:Schema:Label' => 'Etiket',
	'UI:Schema:Label+' => 'Özellik etiketi',
	'UI:Schema:Type' => 'Tip',

	'UI:Schema:Type+' => 'Özellik veri tipi',
	'UI:Schema:Origin' => 'Kaynak',
	'UI:Schema:Origin+' => 'Özelliğin tanımlandığı ana sınıf',
	'UI:Schema:Description' => 'Tanımlama',
	'UI:Schema:Description+' => 'Özellik tanımı',
	'UI:Schema:AllowedValues' => 'Alabileceği değerler',
	'UI:Schema:AllowedValues+' => 'Özelliğin alabileceği değer kısıtları',
	'UI:Schema:MoreInfo' => 'Daha fazla bilgi',
	'UI:Schema:MoreInfo+' => 'Veritabanında tanımlı alan için daha fazla bilgi',
	'UI:Schema:SearchCriteria' => 'Arama kriteri',
	'UI:Schema:FilterCode' => 'Filtreleme kodu',
	'UI:Schema:FilterCode+' => 'Arama kriter kodu',
	'UI:Schema:FilterDescription' => 'Tanımlama',
	'UI:Schema:FilterDescription+' => 'Arama kiter kodu tanılaması',
	'UI:Schema:AvailOperators' => 'Kullanılabilir işlemler',
	'UI:Schema:AvailOperators+' => 'Arama kriteri için kullanılabilir işlemler',
	'UI:Schema:ChildClasses' => 'Alt sınıflar',
	'UI:Schema:ReferencingClasses' => 'Refrans sınıflar',
	'UI:Schema:RelatedClasses' => 'İlgili sınıflar',
	'UI:Schema:LifeCycle' => 'yaşam döngüsü',
	'UI:Schema:Triggers' => 'Tetikleyiciler',
	'UI:Schema:Relation_Code_Description' => 'İlişki <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Aşağı: %1$s',
	'UI:Schema:RelationUp_Description' => 'Yukarı: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: %2$d seviye öteler, sorgu: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: (%2$d seviye) ötelenmez, sorgu: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s  %2$s\'nın %3$s alanı ile ilişkilendirilmiştir',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s alanı %3$s::<em>%4$s</em> aracılığı %2$s ile ilişkilendirilmiştir',
	'UI:Schema:Links:1-n' => 'Sınıf bağlantısı %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Sınıf bağlantısı %1$s (n:n links):',
	'UI:Schema:Links:All' => 'İlişkili sınıfların grafiği',
	'UI:Schema:NoLifeCyle' => 'Bu sınıf için yaşam döngüsü tanımlanmamış.',
	'UI:Schema:LifeCycleTransitions' => 'Geçişler',
	'UI:Schema:LifeCyleAttributeOptions' => 'Özellik seçenekleri',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Gizli',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Salt okunur',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Zorunlu Alan',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Değiştirilmesi gereken',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Kullanıcıdan değeri değüiştirmesi istenir',
	'UI:Schema:LifeCycleEmptyList' => 'boş liste',
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => 'İlk 3 karakteri giriniz...',
	'UI:Edit:TestQuery' => 'Test query~~',
	'UI:Combo:SelectValue' => '--- değer seçiniz ---',
	'UI:Label:SelectedObjects' => 'Seçilen nesneler: ',
	'UI:Label:AvailableObjects' => 'Seçilebilir nesneler: ',
	'UI:Link_Class_Attributes' => '%1$s özellikler',
	'UI:SelectAllToggle+' => 'Tümünü seç / Tümünü seçme',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => '%2$s: %3$s ile ilişkideki %1$s nesnelerini Ekle ',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => '%2$s ile %1$s arasında yeni bağlantı oluştur ',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => '%2$s: %3$s ile bağlantılı %1$s nesnelerini yönet ',
	'UI:AddLinkedObjectsOf_Class' => '%1$s nesnelerini ekle...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Seçili nesnleri sil',
	'UI:Message:EmptyList:UseAdd' => 'Liste boş, Yeni nesne ekleme için "Yeni..." seçiniz.',
	'UI:Message:EmptyList:UseSearchForm' => 'Eklemek istediğiniz nesneleri bulmak için yukarıdaki arama formunu kullanınız.',
	'UI:Wizard:FinalStepTitle' => 'Final step: confirmation',
	'UI:Title:DeletionOf_Object' => '%1$s silimi',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '%2$s sınıfına ait çoklu %1$d nesne silimi',
	'UI:Delete:NotAllowedToDelete' => 'Bu nesneyi silmek için yetkiniz yok',
	'UI:Delete:NotAllowedToUpdate_Fields' => '%1$s alanlarını güncellemek için yetkiniz yok',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Nesne yetersiz yetki nedeniyle silinemedi',
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Bu nesneyi silmek için öncelikli dışarıdan yapılması gereken işlemler var',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'This object could not be deleted because some manual operations must be performed prior to that~~',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:Deleted' => 'deleted~~',
	'UI:Delete:AutomaticallyDeleted' => 'otomatik olarak silindi',
	'UI:Delete:AutomaticResetOf_Fields' => '%1$s alanlarını otomatik sıfırla',
	'UI:Delete:CleaningUpRefencesTo_Object' => '%1$s nesnesine verilen tüm referansları temizle...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnesinin tüm referanslarını temizle ...',
	'UI:Delete:Done+' => 'Ne yapıldı...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s silindi.',
	'UI:Delete:ConfirmDeletionOf_Name' => '%1$s\'in silimi',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnelerinin silimi ',
	'UI:Delete:CannotDeleteBecause' => 'Could not be deleted: %1$s~~',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Should be automaticaly deleted, but this is not feasible: %1$s~~',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Must be deleted manually, but this is not feasible: %1$s~~',
	'UI:Delete:WillBeDeletedAutomatically' => 'Otomatik olarak silinecek',
	'UI:Delete:MustBeDeletedManually' => 'Manuel silinmeli',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Otomatik güncellenmeli, ancak: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'otomatik güncellenecek (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d nesne/ilişki %2$s\'yi referans ediyor',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d silinmek istenen nesne/bağlantıları referans veriyor',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Veri tabanı doğruluğu(Database integrity) için yeni referans verilmesi engellenmelidir',
	'UI:Delete:Consequence+' => 'Ne yapılacak',
	'UI:Delete:SorryDeletionNotAllowed' => 'Bu nesneyi silmeye yetkiniz yok, yukarıdaki açıklamayı bakınız',
	'UI:Delete:PleaseDoTheManualOperations' => 'Bu nesneyi silmeden önce yukarıdaki işlemleri manuel olarak yapınız',
	'UI:Delect:Confirm_Object' => '%1$s\'i silmek istediğnizden emin misiniz?',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => '%1$d nesnesini (sınıfı %2$s) silmek istediğinizden emin misiniz?',
	'UI:WelcomeToITop' => 'iTop\'a Hoşgeldiniz',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s detayları',
	'UI:ErrorPageTitle' => 'iTop - Hata',
	'UI:ObjectDoesNotExist' => 'Nesne mevcut değil veya yetkiniz yok.',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => 'iTop - Arama Sonuçları',
	'UI:SearchResultsTitle' => 'Arama Sonuçları',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => 'Nothing to search for',
	'UI:Search:NeedleTooShort' => 'The search string \\"%1$s\\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \\"%1$s\\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => '"%1$s" için arama sonuçları:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%2$s sınıfına ait %1$d nesne bulundu.',
	'UI:Search:NoObjectFound' => 'Kayıt bulunamadı.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modifikasyon',
	'UI:ModificationTitle_Class_Object' => '%1$s: <span class=\\"hilite\\">%2$s</span> modifikasyonu',
	'UI:ClonePageTitle_Object_Class' => 'iTop - %1$s - %2$s modifikasyonunu klonlayınız',
	'UI:CloneTitle_Class_Object' => '%1$s klonu: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Yeni %1$s yaratımı',
	'UI:CreationTitle_Class' => 'Yeni %1$s yarat',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Yaratılacak %1$s nesne tipini seçiniz',
	'UI:Class_Object_NotUpdated' => 'Değişiklik tespit edilemedi, %1$s (%2$s) <strong>güncellenmedi</strong>.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) güncellendi.',
	'UI:BulkDeletePageTitle' => 'iTop - Toplu silme işlemi',
	'UI:BulkDeleteTitle' => 'Silmek istediğiniz nesneleri seçiniz:',
	'UI:PageTitle:ObjectCreated' => 'iTop Nesne yaratıldı.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s yaratıldı.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '%1$s işlemi %2$s durumunda %3$s nesnesine uygulanır. Bir sonraki durum: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Nesne kaydedilemedi: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Kritik Hata',
	'UI:SystemIntrusion' => 'Bu işlem için yetkiniz yok',
	'UI:FatalErrorMessage' => 'Kritik Hata, iTop devam edemiyor.',
	'UI:Error_Details' => 'Hata: %1$s.',

	'UI:PageTitle:ClassProjections' => 'iTop Kullanıcı Yönetimi - sınıf koruması',
	'UI:PageTitle:ProfileProjections' => 'iTop Kullanıcı Yönetimi - profil koruması',
	'UI:UserManagement:Class' => 'Sınıf',
	'UI:UserManagement:Class+' => 'Nesnin sınıfı',
	'UI:UserManagement:ProjectedObject' => 'Nesne',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
	'UI:UserManagement:AnyObject' => '* herhangi *',
	'UI:UserManagement:User' => 'Kullanıcı',
	'UI:UserManagement:User+' => 'User involved in the projection',
	'UI:UserManagement:Profile' => 'Profil',
	'UI:UserManagement:Profile+' => 'Profile in which the projection is specified',
	'UI:UserManagement:Action:Read' => 'Oku',
	'UI:UserManagement:Action:Read+' => 'Nesneyi görüntüle',
	'UI:UserManagement:Action:Modify' => 'Güncelle',
	'UI:UserManagement:Action:Modify+' => 'Nesneyi yarat/güncelle',
	'UI:UserManagement:Action:Delete' => 'Sil',
	'UI:UserManagement:Action:Delete+' => 'Nesneleri sil',
	'UI:UserManagement:Action:BulkRead' => 'Toplu oku (dışarı aktar)',
	'UI:UserManagement:Action:BulkRead+' => 'Nesneleri listele veya toplu dışarı aktar',
	'UI:UserManagement:Action:BulkModify' => 'Toplu güncelleme',
	'UI:UserManagement:Action:BulkModify+' => 'Toplu yaratma/güncelleme(CSV içeri aktar)',
	'UI:UserManagement:Action:BulkDelete' => 'Toplu Silim',
	'UI:UserManagement:Action:BulkDelete+' => 'Nesneleri toplu olarak sil',
	'UI:UserManagement:Action:Stimuli' => 'Uyarı',
	'UI:UserManagement:Action:Stimuli+' => 'İzin verilen çoklu işlemler',
	'UI:UserManagement:Action' => 'İşlem',
	'UI:UserManagement:Action+' => 'İşlem kullanıcı tarafından yapıldı',
	'UI:UserManagement:TitleActions' => 'İşlemler',
	'UI:UserManagement:Permission' => 'Yetki',
	'UI:UserManagement:Permission+' => 'Kullanıcı yetkileri',
	'UI:UserManagement:Attributes' => 'Özellikler',
	'UI:UserManagement:ActionAllowed:Yes' => 'Evet',
	'UI:UserManagement:ActionAllowed:No' => 'Hayır',
	'UI:UserManagement:AdminProfile+' => 'Sistem Yöneticisi tüm okuma/yazma işlemleri için yetkilidir.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Bu nesne için yaşam döngüsü tanımsız',
	'UI:UserManagement:GrantMatrix' => 'Yetkiler',
	'UI:UserManagement:LinkBetween_User_And_Profile' => '%1$s ve %2$s arasındaki ilişki',
	'UI:UserManagement:LinkBetween_User_And_Org' => '%1$s ve %2$s arasındaki ilişki',

	'Menu:AdminTools' => 'Yönetim Araçları', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Yönetim Araçları', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Yönetici profiline izin verilen araçlar', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Değişiklik Yönetimi',
	'UI:ChangeManagementMenu+' => 'Değişiklik Yönetimi',
	'UI:ChangeManagementMenu:Title' => 'Değişiklik Özeti',
	'UI-ChangeManagementMenu-ChangesByType' => 'Değişiklik tipine göre',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Değişiklik durumuna göre',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'İş grubuna değişiklikler',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Atanmamış Değişiklikler',

	'UI:ConfigurationManagementMenu' => 'Konfigürasyon Yönetimi',
	'UI:ConfigurationManagementMenu+' => 'Konfigürasyon Yönetimi',
	'UI:ConfigurationManagementMenu:Title' => 'Altyapı Özeti',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastructure objects by type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastructure objects by status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Konfigürasyon Yönetimi Gösterge Tablosu',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Durumlarına göre Konfigürasyon Kalemleri(KK)',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Tiplerine göre Konfigürasyon Kalemleri(KK)',

	'UI:RequestMgmtMenuOverview:Title' => 'Çağrı Yönetimi Gösterge Tablosu',
	'UI-RequestManagementOverview-RequestByService' => 'Hizmetlere göre çağrılar',
	'UI-RequestManagementOverview-RequestByPriority' => 'Önceliklere göre çağrılar',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Henüz atanmamış çağrılar',

	'UI:IncidentMgmtMenuOverview:Title' => 'Arıza Gösterge Tablosu',
	'UI-IncidentManagementOverview-IncidentByService' => 'Servislere göre arızalar',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Önceliklere göre arızalar',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Henüz atanmamış arızalar',

	'UI:ChangeMgmtMenuOverview:Title' => 'Değişiklik Yönetimi Gösterge Tablosu',
	'UI-ChangeManagementOverview-ChangeByType' => 'Tiplerine göre değişiklikler',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Henüz atanmamış değişiklikler',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Değişiklik nedeniyle devre dışı',

	'UI:ServiceMgmtMenuOverview:Title' => 'Hizmet Yönetimi Gösterge Tablosu',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => '30 gün içinde biten Müşteri Sözleşmeleri',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => '30 gün içinde biten Tedarikçi Sözleşmeleri',

	'UI:ContactsMenu' => 'İrtibatlar',
	'UI:ContactsMenu+' => 'İrtibatlar',
	'UI:ContactsMenu:Title' => 'İrtibatlar Özetleri',
	'UI-ContactsMenu-ContactsByLocation' => 'Yerleşkeye göre irtibatlar',
	'UI-ContactsMenu-ContactsByType' => 'Tipine göre irtibatlar',
	'UI-ContactsMenu-ContactsByStatus' => 'Durumuna göre irtibatlar',

	'Menu:CSVImportMenu' => 'CSV dışardan al', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Çoklu yaratım veya güncelleme', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Veri Modeli', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Veri Modeli Özeti', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Dışarı ver', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Sorgu sonucunu HTML, CSV veya XML olarak dışarı aktar', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Uyarılar', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Uyarıların yapılandırılması', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => '<span class="hilite">Uyarıların</span> yapılandırılması',
	'UI:NotificationsMenu:Help' => 'Yardım',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop uyarı mekanizması ihtiyaca göre uyarlanabilir. Uyarılar iki tip nesne üzerine kurulmuştur: <i>tetikleme (triggers) ve işlemler (actions)</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> tetikleme olduğunda yapılacak işlemleri belirler. Şimdilik sadece e-posta gönderme işlemi yapılabilmektedir.
E-posta için şablon tanımlanabilmektedir. Şablona parametreler girilebilmektedir (recipients, importance, etc.).
</p>
<p>Özel sayfa: <a href="../setup/email.test.php" target="_blank">email.test.php</a> PHP e-posta konfigürnunu test ediniz.</p>
<p>İşlemin gerçekleşmesi için bir tetikleme ile ilişkilendirilmesi gerekir.
Tetikleme gerçekleştiriğinde işlemler tanımlanan sıra numarası ile gerçekleştirilir.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Tetikleyiciler',
	'UI:NotificationsMenu:AvailableTriggers' => 'Kullanılabilir tetikleyiciler',
	'UI:NotificationsMenu:OnCreate' => 'Nesne yaratıldığında',
	'UI:NotificationsMenu:OnStateEnter' => 'Nesnenin durumuna girişinde',
	'UI:NotificationsMenu:OnStateLeave' => 'Nesnenin durumdan çıkışında',
	'UI:NotificationsMenu:Actions' => 'İşlemler',
	'UI:NotificationsMenu:AvailableActions' => 'Kullanılabilir işlemler',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook~~', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Veri Yönetimi', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Veri Yönetimi', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Genel sorgu', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Herhangi bir arama...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Kullanıcı Yönetimi', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Kullanıcı Yönetimi', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profiller', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profiller', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profiller', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Kullanıcı Hesapları', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Kullanıcı Hesapları', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Kullanıcı Hesapları', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s versiyonu %2$s',
	'UI:iTopVersion:Long' => '%1$s  %4$s tarihli versiyonu %2$s-%3$s',
	'UI:PropertiesTab' => 'Özellikler',

	'UI:OpenDocumentInNewWindow_' => 'Dokümanı yeni pencerede aç: %1$s',
	'UI:DownloadDocument_' => 'Dokümanı indir: %1$s',
	'UI:Document:NoPreview' => 'Bu tip doküman için öngösterim mevcut değil',
	'UI:Download-CSV' => 'Download %1$s~~',

	'UI:DeadlineMissedBy_duration' => '%1$s ile kaçırıldı',
	'UI:Deadline_LessThan1Min' => '< 1 dk.',
	'UI:Deadline_Minutes' => '%1$d dk.',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$ddk',
	'UI:Deadline_Days_Hours_Minutes' => '%1$d gün %2$d saat %3$d dk',
	'UI:Help' => 'Yardım',
	'UI:PasswordConfirm' => '(Onay)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Yeni %1$s nesneleri eklemeden önce bu nesneyi kaydediniz.',
	'UI:DisplayThisMessageAtStartup' => 'Bu mesajı başlangıçta göster',
	'UI:RelationshipGraph' => 'Grafiksel gösterim',
	'UI:RelationshipList' => 'List',
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Operation Cancelled~~',
	'UI:ElementsDisplayed' => 'Filtering~~',
	'UI:RelationGroupNumber_N' => 'Group #%1$d~~',
	'UI:Relation:ExportAsPDF' => 'Export as PDF...~~',
	'UI:RelationOption:GroupingThreshold' => 'Grouping threshold~~',
	'UI:Relation:AdditionalContextInfo' => 'Additional context info~~',
	'UI:Relation:NoneSelected' => 'None~~',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Export as Attachment...~~',
	'UI:Relation:DrillDown' => 'Details...~~',
	'UI:Relation:PDFExportOptions' => 'PDF Export Options~~',
	'UI:Relation:AttachmentExportOptions_Name' => 'Options for Attachment to %1$s~~',
	'UI:RelationOption:Untitled' => 'Untitled~~',
	'UI:Relation:Key' => 'Key~~',
	'UI:Relation:Comments' => 'Comments~~',
	'UI:RelationOption:Title' => 'Title~~',
	'UI:RelationOption:IncludeList' => 'Include the list of objects~~',
	'UI:RelationOption:Comments' => 'Comments~~',
	'UI:Button:Export' => 'Export~~',
	'UI:Relation:PDFExportPageFormat' => 'Page format~~',
	'UI:PageFormat_A3' => 'A3~~',
	'UI:PageFormat_A4' => 'A4~~',
	'UI:PageFormat_Letter' => 'Letter~~',
	'UI:Relation:PDFExportPageOrientation' => 'Page orientation~~',
	'UI:PageOrientation_Portrait' => 'Portrait~~',
	'UI:PageOrientation_Landscape' => 'Landscape~~',
	'UI:RelationTooltip:Redundancy' => 'Redundancy~~',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# of impacted items: %1$d / %2$d~~',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Critical threshold: %1$d / %2$d~~',
	'Portal:Title' => 'iTop Kullanıcı Portalı',
	'Portal:NoRequestMgmt' => 'Dear %1$s, you have been redirected to this page because your account is configured with the profile \'Portal user\'. Unfortunately, iTop has not been installed with the feature \'Request Management\'. Please contact your administrator.~~',
	'Portal:Refresh' => 'Yenile',
	'Portal:Back' => 'Geri',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request~~',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Yeni istek yarat',
	'Portal:CreateNewRequestItil' => 'Yeni istek yarat',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
	'Portal:ChangeMyPassword' => 'Şifre değiştir',
	'Portal:Disconnect' => 'Çıkış',
	'Portal:OpenRequests' => 'Açık isteklerim',
	'Portal:ClosedRequests' => 'My closed requests',
	'Portal:ResolvedRequests' => 'Çözdüğüm istekler',
	'Portal:SelectService' => 'Kataloğdan servis seçiniz:',
	'Portal:PleaseSelectOneService' => 'Sevis seçiniz',
	'Portal:SelectSubcategoryFrom_Service' => '%1$s servis için alt kategorsi seçiniz:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Alt kategori seçiniz',
	'Portal:DescriptionOfTheRequest' => 'İstek tanımlaması:',
	'Portal:TitleRequestDetailsFor_Request' => 'İsteğin detayı %1$s:',
	'Portal:NoOpenRequest' => 'Bu kategoride istek yok.',
	'Portal:NoClosedRequest' => 'Bu kategoride istek yok.',
	'Portal:Button:ReopenTicket' => 'Reopen this ticket',
	'Portal:Button:CloseTicket' => 'Çağrıyı kapat',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => 'İsteğin çözümüne yönelik açıklamalar:',
	'Portal:ErrorNoContactForThisUser' => 'Hata: mevcut kullanıcının irtibat bilgisi yok. Sistem yöneticisi ile irtibata geçiniz.',
	'Portal:Attachments' => 'Attachments~~',
	'Portal:AddAttachment' => ' Add Attachment ~~',
	'Portal:RemoveAttachment' => ' Remove Attachment ~~',
	'Portal:Attachment_No_To_Ticket_Name' => 'Attachment #%1$d to %2$s (%3$s)~~',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
	'Enum:Undefined' => 'Tanımsız',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Days %2$s Hours %3$s Minutes %4$s Seconds~~',
	'UI:ModifyAllPageTitle' => 'Modify All~~',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifying %1$d objects of class %2$s~~',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Modifying %1$d objects of class %2$s out of %3$d~~',
	'UI:Menu:ModifyAll' => 'Modify...~~',
	'UI:Button:ModifyAll' => 'Modify All~~',
	'UI:Button:PreviewModifications' => 'Preview Modifications >>~~',
	'UI:ModifiedObject' => 'Object Modified~~',
	'UI:BulkModifyStatus' => 'Operation~~',
	'UI:BulkModifyStatus+' => 'Status of the operation~~',
	'UI:BulkModifyErrors' => 'Errors (if any)~~',
	'UI:BulkModifyErrors+' => 'Errors preventing the modification~~',
	'UI:BulkModifyStatusOk' => 'Ok~~',
	'UI:BulkModifyStatusError' => 'Error~~',
	'UI:BulkModifyStatusModified' => 'Modified~~',
	'UI:BulkModifyStatusSkipped' => 'Skipped~~',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:~~',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d time(s)~~',
	'UI:BulkModify:N_MoreValues' => '%1$d more values...~~',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Attempting to set the read-only field: %1$s~~',
	'UI:FailedToApplyStimuli' => 'The action has failed.~~',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Modifying %2$d objects of class %3$s~~',
	'UI:CaseLogTypeYourTextHere' => 'Type your text here:~~',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:~~',
	'UI:CaseLog:InitialValue' => 'Initial value:~~',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value not set.~~',
	'UI:ActionNotAllowed' => 'You are not allowed to perform this action on these objects.~~',
	'UI:BulkAction:NoObjectSelected' => 'Please select at least one object to perform this operation~~',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'The field %1$s is not writable because it is mastered by the data synchronization. Value remains unchanged.~~',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objects (%2$s objects selected).~~',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objects.~~',
	'UI:Pagination:PageSize' => '%1$s objects per page~~',
	'UI:Pagination:PagesLabel' => 'Pages:~~',
	'UI:Pagination:All' => 'All~~',
	'UI:HierarchyOf_Class' => 'Hierarchy of %1$s~~',
	'UI:Preferences' => 'Preferences...~~',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Favorite Organizations~~',
	'UI:FavoriteOrganizations+' => 'Check in the list below the organizations that you want to see in the drop-down menu for a quick access. Note that this is not a security setting, objects from any organization are still visible and can be accessed by selecting \\"All Organizations\\" in the drop-down list.~~',
	'UI:FavoriteLanguage' => 'Language of the User Interface~~',
	'UI:Favorites:SelectYourLanguage' => 'Select your preferred language~~',
	'UI:FavoriteOtherSettings' => 'Other Settings~~',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default length for lists:  %1$s items per page~~',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Any modification will be discarded.~~',
	'UI:CancelConfirmationMessage' => 'You will loose your changes. Continue anyway?~~',
	'UI:AutoApplyConfirmationMessage' => 'Some changes have not been applied yet. Do you want itop to take them into account?~~',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Add To Dashboard...~~',
	'UI:Button:Refresh' => 'Yenile',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Configure This List...~~',
	'UI:ListConfigurationTitle' => 'List Configuration~~',
	'UI:ColumnsAndSortOrder' => 'Columns and sort order:~~',
	'UI:UseDefaultSettings' => 'Use the Default Settings~~',
	'UI:UseSpecificSettings' => 'Use the Following Settings:~~',
	'UI:Display_X_ItemsPerPage' => 'Display %1$s items per page~~',
	'UI:UseSavetheSettings' => 'Save the Settings~~',
	'UI:OnlyForThisList' => 'Only for this list~~',
	'UI:ForAllLists' => 'Default for all lists~~',
	'UI:ExtKey_AsLink' => '%1$s (Link)~~',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)~~',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)~~',
	'UI:Button:MoveUp' => 'Move Up~~',
	'UI:Button:MoveDown' => 'Move Down~~',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class \\"%1$s\\". You may try \\"%2$s\\" instead.~~',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class \\"%1$s\\"~~',

	'UI:Dashboard:Edit' => 'Edit This Page...~~',
	'UI:Dashboard:Revert' => 'Revert To Original Version...~~',
	'UI:Dashboard:RevertConfirm' => 'Every changes made to the original version will be lost. Please confirm that you want to do this.~~',
	'UI:ExportDashBoard' => 'Export to a file~~',
	'UI:ImportDashBoard' => 'Import from a file...~~',
	'UI:ImportDashboardTitle' => 'Import From a File~~',
	'UI:ImportDashboardText' => 'Select a dashboard file to import:~~',


	'UI:DashletCreation:Title' => 'Create a new Dashlet~~',
	'UI:DashletCreation:Dashboard' => 'Dashboard~~',
	'UI:DashletCreation:DashletType' => 'Dashlet Type~~',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard~~',

	'UI:DashboardEdit:Title' => 'Dashboard Editor~~',
	'UI:DashboardEdit:DashboardTitle' => 'Title~~',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',

	'UI:DashboardEdit:Layout' => 'Layout~~',
	'UI:DashboardEdit:Properties' => 'Dashboard Properties~~',
	'UI:DashboardEdit:Dashlets' => 'Available Dashlets~~',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Properties~~',

	'UI:Form:Property' => 'Property~~',
	'UI:Form:Value' => 'Value~~',

	'UI:DashletUnknown:Label' => 'Unknown~~',
	'UI:DashletUnknown:Description' => 'Unknown dashlet (might have been uninstalled)~~',
	'UI:DashletUnknown:RenderText:View' => 'Unable to render this dashlet.~~',
	'UI:DashletUnknown:RenderText:Edit' => 'Unable to render this dashlet (class "%1$s"). Check with your administrator if it is still available.~~',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'No preview available for this dashlet (class "%1$s").~~',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletProxy:Label' => 'Proxy~~',
	'UI:DashletProxy:Description' => 'Proxy dashlet~~',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'No preview available for this third-party dashlet (class "%1$s").~~',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuration (shown as raw XML)~~',

	'UI:DashletPlainText:Label' => 'Text~~',
	'UI:DashletPlainText:Description' => 'Plain text (no formatting)~~',
	'UI:DashletPlainText:Prop-Text' => 'Text~~',
	'UI:DashletPlainText:Prop-Text:Default' => 'Please enter some text here...~~',

	'UI:DashletObjectList:Label' => 'Object list~~',
	'UI:DashletObjectList:Description' => 'Object list dashlet~~',
	'UI:DashletObjectList:Prop-Title' => 'Title~~',
	'UI:DashletObjectList:Prop-Query' => 'Query~~',
	'UI:DashletObjectList:Prop-Menu' => 'Menu~~',

	'UI:DashletGroupBy:Prop-Title' => 'Title~~',
	'UI:DashletGroupBy:Prop-Query' => 'Query~~',
	'UI:DashletGroupBy:Prop-Style' => 'Style~~',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Group by...~~',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hour of %1$s (0-23)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Month of %1$s (1 - 12)~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Day of week for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Day of month for %1$s~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hour)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (month)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (day of week)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (day of month)~~',
	'UI:DashletGroupBy:MissingGroupBy' => 'Please select the field on which the objects will be grouped together~~',

	'UI:DashletGroupByPie:Label' => 'Pie Chart~~',
	'UI:DashletGroupByPie:Description' => 'Pie Chart~~',
	'UI:DashletGroupByBars:Label' => 'Bar Chart~~',
	'UI:DashletGroupByBars:Description' => 'Bar Chart~~',
	'UI:DashletGroupByTable:Label' => 'Group By (table)~~',
	'UI:DashletGroupByTable:Description' => 'List (Grouped by a field)~~',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Aggregation function~~',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Function attribute~~',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Direction~~',
	'UI:DashletGroupBy:Prop-OrderField' => 'Order by~~',
	'UI:DashletGroupBy:Prop-Limit' => 'Limit~~',

	'UI:DashletGroupBy:Order:asc' => 'Ascending~~',
	'UI:DashletGroupBy:Order:desc' => 'Descending~~',

	'UI:GroupBy:count' => 'Count~~',
	'UI:GroupBy:count+' => 'Number of elements~~',
	'UI:GroupBy:sum' => 'Sum~~',
	'UI:GroupBy:sum+' => 'Sum of %1$s~~',
	'UI:GroupBy:avg' => 'Average~~',
	'UI:GroupBy:avg+' => 'Average of %1$s~~',
	'UI:GroupBy:min' => 'Minimum~~',
	'UI:GroupBy:min+' => 'Minimum of %1$s~~',
	'UI:GroupBy:max' => 'Maximum~~',
	'UI:GroupBy:max+' => 'Maximum of %1$s~~',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Header~~',
	'UI:DashletHeaderStatic:Description' => 'Displays an horizontal separator~~',
	'UI:DashletHeaderStatic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icon~~',

	'UI:DashletHeaderDynamic:Label' => 'Header with statistics~~',
	'UI:DashletHeaderDynamic:Description' => 'Header with stats (grouped by...)~~',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Title~~',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icon~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtitle~~',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacts~~',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query~~',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Group by~~',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Values~~',

	'UI:DashletBadge:Label' => 'Badge~~',
	'UI:DashletBadge:Description' => 'Object Icon with new/search~~',
	'UI:DashletBadge:Prop-Class' => 'Class~~',

	'DayOfWeek-Sunday' => 'Sunday~~',
	'DayOfWeek-Monday' => 'Monday~~',
	'DayOfWeek-Tuesday' => 'Tuesday~~',
	'DayOfWeek-Wednesday' => 'Wednesday~~',
	'DayOfWeek-Thursday' => 'Thursday~~',
	'DayOfWeek-Friday' => 'Friday~~',
	'DayOfWeek-Saturday' => 'Saturday~~',
	'Month-01' => 'January~~',
	'Month-02' => 'February~~',
	'Month-03' => 'March~~',
	'Month-04' => 'April~~',
	'Month-05' => 'May~~',
	'Month-06' => 'June~~',
	'Month-07' => 'July~~',
	'Month-08' => 'August~~',
	'Month-09' => 'September~~',
	'Month-10' => 'October~~',
	'Month-11' => 'November~~',
	'Month-12' => 'December~~',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Su~~',
	'DayOfWeek-Monday-Min' => 'Mo~~',
	'DayOfWeek-Tuesday-Min' => 'Tu~~',
	'DayOfWeek-Wednesday-Min' => 'We~~',
	'DayOfWeek-Thursday-Min' => 'Th~~',
	'DayOfWeek-Friday-Min' => 'Fr~~',
	'DayOfWeek-Saturday-Min' => 'Sa~~',
	'Month-01-Short' => 'Jan~~',
	'Month-02-Short' => 'Feb~~',
	'Month-03-Short' => 'Mar~~',
	'Month-04-Short' => 'Apr~~',
	'Month-05-Short' => 'May~~',
	'Month-06-Short' => 'Jun~~',
	'Month-07-Short' => 'Jul~~',
	'Month-08-Short' => 'Aug~~',
	'Month-09-Short' => 'Sep~~',
	'Month-10-Short' => 'Oct~~',
	'Month-11-Short' => 'Nov~~',
	'Month-12-Short' => 'Dec~~',
	'Calendar-FirstDayOfWeek' => '0~~', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Create a Shortcut...~~',
	'UI:ShortcutRenameDlg:Title' => 'Rename the shortcut~~',
	'UI:ShortcutListDlg:Title' => 'Create a shortcut for the list~~',
	'UI:ShortcutDelete:Confirm' => 'Please confirm that wou wish to delete the shortcut(s).~~',
	'Menu:MyShortcuts' => 'My Shortcuts~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Shortcut~~',
	'Class:Shortcut+' => '~~',
	'Class:Shortcut/Attribute:name' => 'Name~~',
	'Class:Shortcut/Attribute:name+' => 'Label used in the menu and page title~~',
	'Class:ShortcutOQL' => 'Search result shortcut~~',
	'Class:ShortcutOQL+' => '~~',
	'Class:ShortcutOQL/Attribute:oql' => 'Query~~',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL defining the list of objects to search for~~',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds~~',

	'UI:FillAllMandatoryFields' => 'Please fill all mandatory fields.~~',
	'UI:ValueMustBeSet' => 'Please specify a value~~',
	'UI:ValueMustBeChanged' => 'Please change the value~~',
	'UI:ValueInvalidFormat' => 'Invalid format~~',

	'UI:CSVImportConfirmTitle' => 'Please confirm the operation~~',
	'UI:CSVImportConfirmMessage' => 'Are you sure you want to do this?~~',
	'UI:CSVImportError_items' => 'Errors: %1$d~~',
	'UI:CSVImportCreated_items' => 'Created: %1$d~~',
	'UI:CSVImportModified_items' => 'Modified: %1$d~~',
	'UI:CSVImportUnchanged_items' => 'Unchanged: %1$d~~',
	'UI:CSVImport:DateAndTimeFormats' => 'Date and time format~~',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Default format: %1$s (e.g. %2$s)~~',
	'UI:CSVImport:CustomDateTimeFormat' => 'Custom format: %1$s~~',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Available placeholders:<table>
<tr><td>Y</td><td>year (4 digits, e.g. 2016)</td></tr>
<tr><td>y</td><td>year (2 digits, e.g. 16 for 2016)</td></tr>
<tr><td>m</td><td>month (2 digits, e.g. 01..12)</td></tr>
<tr><td>n</td><td>month (1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>d</td><td>day (2 digits, e.g. 01..31)</td></tr>
<tr><td>j</td><td>day (1 or 2 digits no leading zero, e.g. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 hour, 2 digits, e.g. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 hour, 2 digits, e.g. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 hour, 1 or 2 digits no leading zero, e.g. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 hour, 1 or 2 digits no leading zero, e.g. 1..12)</td></tr>
<tr><td>a</td><td>hour, am or pm (lowercase)</td></tr>
<tr><td>A</td><td>hour, AM or PM (uppercase)</td></tr>
<tr><td>i</td><td>minutes (2 digits, e.g. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 digits, e.g. 00..59)</td></tr>
</table>~~',

	'UI:Button:Remove' => 'Remove~~',
	'UI:AddAnExisting_Class' => 'Add objects of type %1$s...~~',
	'UI:SelectionOf_Class' => 'Selection of objects of type %1$s~~',

	'UI:AboutBox' => 'About iTop...~~',
	'UI:About:Title' => 'About iTop~~',
	'UI:About:DataModel' => 'Data model~~',
	'UI:About:Support' => 'Support information~~',
	'UI:About:Licenses' => 'Licenses~~',
	'UI:About:InstallationOptions' => 'Installation options~~',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'You are disconnected. You must identify yourself to continue using the application.~~',
	'UI:DisconnectedDlgTitle' => 'Warning!~~',
	'UI:LoginAgain' => 'Login again~~',
	'UI:StayOnThePage' => 'Stay on this page~~',

	'ExcelExporter:ExportMenu' => 'Excel Export...~~',
	'ExcelExporter:ExportDialogTitle' => 'Excel Export~~',
	'ExcelExporter:ExportButton' => 'Export~~',
	'ExcelExporter:DownloadButton' => 'Download %1$s~~',
	'ExcelExporter:RetrievingData' => 'Retrieving data...~~',
	'ExcelExporter:BuildingExcelFile' => 'Building the Excel file...~~',
	'ExcelExporter:Done' => 'Done.~~',
	'ExcelExport:AutoDownload' => 'Start the download automatically when the export is ready~~',
	'ExcelExport:PreparingExport' => 'Preparing the export...~~',
	'ExcelExport:Statistics' => 'Statistics~~',
	'portal:legacy_portal' => 'End-User Portal~~',
	'portal:backoffice' => 'iTop Back-Office User Interface~~',

	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.~~',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.~~',
	'UI:CurrentObjectLockExpired' => 'The lock to prevent concurrent modifications of the object has expired.~~',
	'UI:CurrentObjectLockExpired_Explanation' => 'The lock to prevent concurrent modifications of the object has expired. You can no longer submit your modification since other users are now allowed to modify this object.~~',
	'UI:ConcurrentLockKilled' => 'The lock preventing modifications on the current object has been deleted.~~',
	'UI:Menu:KillConcurrentLock' => 'Kill the Concurrent Modification Lock !~~',

	'UI:Menu:ExportPDF' => 'Export as PDF...~~',
	'UI:Menu:PrintableVersion' => 'Printer friendly version~~',

	'UI:BrowseInlineImages' => 'Browse images...~~',
	'UI:UploadInlineImageLegend' => 'Upload a new image~~',
	'UI:SelectInlineImageToUpload' => 'Select the image to upload~~',
	'UI:AvailableInlineImagesLegend' => 'Available images~~',
	'UI:NoInlineImage' => 'There is no image available on the server. Use the "Browse" button above to select an image from your computer and upload it to the server.~~',

	'UI:ToggleFullScreen' => 'Toggle Maximize / Minimize~~',
	'UI:Button:ResetImage' => 'Recover the previous image~~',
	'UI:Button:RemoveImage' => 'Remove the image~~',
	'UI:UploadNotSupportedInThisMode' => 'The modification of images or files is not supported in this mode.~~',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Minimize / Expand~~',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto submit has been disabled for this class~~',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Based on your preferences, obsolete data are hidden~~',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.~~',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Add new criteria~~',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recently used~~',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Most popular~~',
	'UI:Search:AddCriteria:List:Others:Title' => 'Others~~',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'None yet.~~',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s is empty~~',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s is not empty~~',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s equals %2$s~~',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s contains %2$s~~',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s starts with %2$s~~',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s ends with %2$s~~',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s matches %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s~~',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s~~',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s~~',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s~~',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s between [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s until %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Any~~',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s from %2$s~~',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s up to %2$s~~',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s~~',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Any~~',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s~~',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s is defined~~',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s is not defined~~',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s~~',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Any~~',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s is defined~~',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s is not defined~~',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s and %3$s others~~',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Any~~',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Is empty~~',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Is not empty~~',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Equals~~',
	'UI:Search:Criteria:Operator:Default:Between' => 'Between~~',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Contains~~',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Starts with~~',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Ends with~~',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Regular exp.~~',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Equals~~',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Greater~~',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Greater / equals~~',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Less~~',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Less / equals~~',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Different~~',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Matches~~',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...~~',
	'UI:Search:Value:Search:Placeholder' => 'Search...~~',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Start typing for possible values.~~',
	'UI:Search:Value:Autocomplete:Wait' => 'Please wait...~~',
	'UI:Search:Value:Autocomplete:NoResult' => 'No result.~~',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Check all / none~~',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Check all / none visibles~~',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'From~~',
	'UI:Search:Criteria:Numeric:Until' => 'To~~',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Any~~',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Any~~',
	'UI:Search:Criteria:DateTime:From' => 'From~~',
	'UI:Search:Criteria:DateTime:FromTime' => 'From~~',
	'UI:Search:Criteria:DateTime:Until' => 'until~~',
	'UI:Search:Criteria:DateTime:UntilTime' => 'until~~',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Any date~~',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Any date~~',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Children of the selected objects will be included.~~',

	'UI:Search:Criteria:Raw:Filtered' => 'Filtered~~',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Filtered on %1$s~~',
));

//
// Expression to Natural language
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Expression:Operator:AND' => ' AND ~~',
	'Expression:Operator:OR' => ' OR ~~',
	'Expression:Operator:=' => ': ~~',

	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',

	'Expression:Unit:Long:DAY' => 'day(s)~~',
	'Expression:Unit:Long:HOUR' => 'hour(s)~~',
	'Expression:Unit:Long:MINUTE' => 'minute(s)~~',

	'Expression:Verb:NOW' => 'now~~',
	'Expression:Verb:ISNULL' => ': undefined~~',
));

//
// iTop Newsroom menu
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'UI:Newsroom:NoNewMessage' => 'No new message~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));
