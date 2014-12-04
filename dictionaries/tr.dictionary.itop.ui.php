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
	'Class:User/Attribute:language/Value:TR TR' => 'Turkish',
	'Class:User/Attribute:language/Value:TR TR+' => 'Turkish (Turkey)',
	'Class:User/Attribute:profile_list' => 'Profiller',
	'Class:User/Attribute:profile_list+' => 'Kullanıcı rolü',
	'Class:User/Attribute:allowed_org_list' => 'Erişim yetkisi verilen kurumlar',
	'Class:User/Attribute:allowed_org_list+' => 'Kullanıcın erişime yetkili olduğu kurumlar. Kurum tanımlanmaz ise sınırlama olmaz.',

	'Class:User/Error:LoginMustBeUnique' => 'Kullanıcı adı tekil olmalı - "%1s" mevcut bir kullanıcıya ait.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'En az bir profil kullanıcıya atanmalı',
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
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'BooleanLabel:yes' => 'evet',
	'BooleanLabel:no' => 'hayır',
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
	'UI:Button:Cancel' => 'İptal',
	'UI:Button:Apply' => 'Uygula',
	'UI:Button:Back' => ' << Geri ',
	'UI:Button:Next' => ' İleri >> ',
	'UI:Button:Finish' => ' Bitir ',
	'UI:Button:DoImport' => ' Dışardan Veri alı çalıştır ! ',
	'UI:Button:Done' => ' Biiti ',
	'UI:Button:SimulateImport' => ' Veri alışını simule et ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Değerlendir ',
	'UI:Button:AddObject' => ' Ekle... ',
	'UI:Button:BrowseObjects' => ' Listele... ',
	'UI:Button:Add' => ' Ekle ',
	'UI:Button:AddToList' => ' << Ekle ',
	'UI:Button:RemoveFromList' => ' Sil >> ',
	'UI:Button:FilterList' => ' Filtreleme... ',
	'UI:Button:Create' => ' Yarat ',
	'UI:Button:Delete' => ' Sil ! ',
	'UI:Button:ChangePassword' => ' Şifre değiştir ',
	'UI:Button:ResetPassword' => ' Şifreyi sıfırla ',

	'UI:SearchToggle' => 'Ara',
	'UI:ClickToCreateNew' => 'Yeni %1$s yarat',
	'UI:SearchFor_Class' => '%1$s Arama',
	'UI:NoObjectToDisplay' => 'Görüntülenecek nesne bulunamadı.',
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


	'UI:GroupBy:Count' => 'Say',
	'UI:GroupBy:Count+' => 'Eleman sayısı',
	'UI:CountOfObjects' => 'Kritere uyan %1$d nesne bulundu.',
	'UI_CountOfObjectsShort' => '%1$d nesne.',
	'UI:NoObject_Class_ToDisplay' => '%1$s nesne listelenecek',
	'UI:History:LastModified_On_By' => '%1$s tarihinde %2$s tarafından değiştirilmiş.',
	'UI:HistoryTab' => 'Tarihçe',
	'UI:NotificationsTab' => 'Uyarılar',
	'UI:History:Date' => 'Tarih',
	'UI:History:Date+' => 'Değişiklik tarihi',
	'UI:History:User' => 'Kullanıcı',
	'UI:History:User+' => 'Değişikliğ yapan kullanıcı',
	'UI:History:Changes' => 'Değişiklikler',
	'UI:History:Changes+' => 'Nesneye yapılan değişiklikler',
	'UI:Loading' => 'Yükleniyor...',
	'UI:Menu:Actions' => 'İşlemler',
	'UI:Menu:OtherActions' => 'Diğer İşlemler',
	'UI:Menu:New' => 'Yeni...',
	'UI:Menu:Add' => 'Ekle...',
	'UI:Menu:Manage' => 'Yönet...',
	'UI:Menu:EMail' => 'e-posta',
	'UI:Menu:CSVExport' => 'CSV olarak dışarı ver',
	'UI:Menu:Modify' => 'Düzenle...',
	'UI:Menu:Delete' => 'Sil...',
	'UI:Menu:Manage' => 'Yönet...',
	'UI:Menu:BulkDelete' => 'Sil...',
	'UI:UndefinedObject' => 'tanımsız',
	'UI:Document:OpenInNewWindow:Download' => 'Yeni pencerede aç: %1$s, Karşıdan yükle: %2$s',
	'UI:SelectAllToggle+' => 'Tümünü Seç / Tümünü seçme',
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
	'UI:SelectOne' => '-- Birini seçiniz --',
	'UI:Login:Welcome' => 'iTop\'a Hoşgeldiniz!',
	'UI:Login:IncorrectLoginPassword' => 'Hatalı kullanıcı/şifre tekrar deneyiniz.',
	'UI:Login:IdentifyYourself' => 'Devam etmeden önce kendinizi tanıtınız',
	'UI:Login:UserNamePrompt' => 'Kullanıcı Adı',
	'UI:Login:PasswordPrompt' => 'Şifre',
	'UI:Login:ChangeYourPassword' => 'Şifre Değiştir',
	'UI:Login:OldPasswordPrompt' => 'Mevcut şifre',
	'UI:Login:NewPasswordPrompt' => 'Yeni şifre',
	'UI:Login:RetypeNewPasswordPrompt' => 'Yeni şifre tekrar',
	'UI:Login:IncorrectOldPassword' => 'Hata: mevcut şifre hatalı',
	'UI:LogOffMenu' => 'Çıkış',
	'UI:LogOff:ThankYou' => 'iTop Kullanıdığınız için teşekkürler',
	'UI:LogOff:ClickHereToLoginAgain' => 'Tekrar bağlanmak için tıklayınız...',
	'UI:ChangePwdMenu' => 'Şifre değiştir...',
	'UI:Login:RetypePwdDoesNotMatch' => 'Yeni şifre eşlenmedi !',
	'UI:Button:Login' => 'iTop\'a Giriş',
	'UI:Login:Error:AccessRestricted' => 'iTop erişim sınırlandırıldı. Sistem yöneticisi ile irtibata geçiniz',
	'UI:Login:Error:AccessAdmin' => 'Erişim sistem yönetci hesaplaları ile mümkün. Sistem yöneticisi ile irtibata geçiniz.',
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
	'UI:CSVImport:AdvancedMode+' => 'Uzman modunda (In advanced mode) "id" (primary key) alanı nesnenin güncellenmesi ve adının değiştirilmesi için kullanılabilir.' .
									'"id" (mevcut ise) alanı tek sorgu kriteri olarak kullnılabilri ve diğer sorgu kriterleri ile birleştirilmez.',
	'UI:CSVImport:SelectAClassFirst' => 'Eşlemeyi yapmak için önce sınıfı seçiniz.',
	'UI:CSVImport:HeaderFields' => 'Alanlar',
	'UI:CSVImport:HeaderMappings' => 'Eşlemeler',
	'UI:CSVImport:HeaderSearch' => 'Arama?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Lütfen tüm alanlar için alan eşlemesini yapınız.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Lütfen en az bir sorgu kriteri seçiniz.',
	'UI:CSVImport:Encoding' => 'Karakter kodlaması',
	'UI:UniversalSearchTitle' => 'iTop - Genel arama',
	'UI:UniversalSearch:Error' => 'Hata: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Aranacak sınıfı seçiniz: ',

	'UI:Audit:Title' => 'iTop - CMDB Denetleme',
	'UI:Audit:InteractiveAudit' => 'Etkileşimli Denetleme',
	'UI:Audit:HeaderAuditRule' => 'Denetleme Kuralı',
	'UI:Audit:HeaderNbObjects' => 'Nesne Sayısı',
	'UI:Audit:HeaderNbErrors' => 'Hata sayısı',
	'UI:Audit:PercentageOk' => '% Tamam',

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
	'UI:RunQuery:Error' => 'Sorgu sırasında hata oluştu: %1$s',

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

	'UI:LinksWidget:Autocomplete+' => 'İlk 3 karakteri giriniz...',
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
	'UI:Error:CannotDeleteBecause' => 'This object could not be deleted because: %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Nesne yetersiz yetki nedeniyle silinemedi',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Bu nesneyi silmek için öncelikli dışarıdan yapılması gereken işlemler var',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s',
	'UI:Delete:AutomaticallyDeleted' => 'otomatik olarak silindi',
	'UI:Delete:AutomaticResetOf_Fields' => '%1$s alanlarını otomatik sıfırla',
	'UI:Delete:CleaningUpRefencesTo_Object' => '%1$s nesnesine verilen tüm referansları temizle...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnesinin tüm referanslarını temizle ...',
	'UI:Delete:Done+' => 'Ne yapıldı...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s silindi.',
	'UI:Delete:ConfirmDeletionOf_Name' => '%1$s\'in silimi',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnelerinin silimi ',
//	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Otomatik silimesini mi istiyorsunuz, ancak buna yetkiniz yok',
//	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Manuel silinmeli - ancak bu nesneyi silmeye yetkiniz yok, lütfen sistem yöneticisiyle irtibata geçiniz.',
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
	'UI:SearchResultsPageTitle' => 'iTop - Arama Sonuçları',
	'UI:Search:NoSearch' => 'Nothing to search for',
	'UI:FullTextSearchTitle_Text' => '"%1$s" için arama sonuçları:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%2$s sınıfına ait %1$d nesne bulundu.',
	'UI:Search:NoObjectFound' => 'Kayıt bulunamadı.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modifikasyon',
	'UI:ModificationTitle_Class_Object' => '%1$s: <span class=\"hilite\">%2$s</span> modifikasyonu',
	'UI:ClonePageTitle_Object_Class' => 'iTop - %1$s - %2$s modifikasyonunu klonlayınız',
	'UI:CloneTitle_Class_Object' => '%1$s klonu: <span class=\"hilite\">%2$s</span>',
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

	'UI:PageTitle:ClassProjections'	=> 'iTop Kullanıcı Yönetimi - sınıf koruması',
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
<p><i><b>Triggers</b></i> uyarının ne zaman yapılacağını belirler. 3 tip tetikleme vardır ve nesnenin 3 durumuna ilişkilendirilmiştir:
<ol>
	<li>the "OnCreate" tetikleme nesne yaratıldığı zaman çalışır</li>
	<li>the "OnStateEnter" tetikleme nesne belli bir duruma girişinde  çalışır</li>
	<li>the "OnStateLeave" tetikleme nesne belli bir durumdan çıkışında  çalışır</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> tetikleme olduğunda yapılacak işlemleri belirler. Şimdilik sadece e-posta gönderme işlemi yapılabilmektedir.
E-posta için şablon tanımlanabilmektedir. Şablona parametreler girilebilmektedir (recipients, importance, etc.).
</p>
<p>Özel sayfa: <a href="../setup/email.test.php" target="_blank">email.test.php</a> PHP e-posta konfigürnunu test ediniz.</p>
<p>İşlemin gerçekleşmesi için bir tetikleme ile ilişkilendirilmesi gerekir.
Tetikleme gerçekleştiriğinde işlemler tanımlanan sıra numarası ile gerçekleştirilir.</p>',
	'UI:NotificationsMenu:Triggers' => 'Tetikleyiciler',
	'UI:NotificationsMenu:AvailableTriggers' => 'Kullanılabilir tetikleyiciler',
	'UI:NotificationsMenu:OnCreate' => 'Nesne yaratıldığında',
	'UI:NotificationsMenu:OnStateEnter' => 'Nesnenin durumuna girişinde',
	'UI:NotificationsMenu:OnStateLeave' => 'Nesnenin durumdan çıkışında',
	'UI:NotificationsMenu:Actions' => 'İşlemler',
	'UI:NotificationsMenu:AvailableActions' => 'Kullanılabilir işlemler',

	'Menu:AuditCategories' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)

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

	'UI:iTopVersion:Short' => 'iTop versiyonu %1$s',
	'UI:iTopVersion:Long' => 'iTop  %3$s tarihli versiyonu %1$s-%2$s',
	'UI:PropertiesTab' => 'Özellikler',

	'UI:OpenDocumentInNewWindow_' => 'Dokümanı yeni pencerede aç: %1$s',
	'UI:DownloadDocument_' => 'Dokümanı indir: %1$s',
	'UI:Document:NoPreview' => 'Bu tip doküman için öngösterim mevcut değil',

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

	'Portal:Title' => 'iTop Kullanıcı Portalı',
	'Portal:Refresh' => 'Yenile',
	'Portal:Back' => 'Geri',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Yeni istek yarat',
	'Portal:ChangeMyPassword' => 'Şifre değiştir',
	'Portal:Disconnect' => 'Çıkış',
	'Portal:OpenRequests' => 'Açık isteklerim',
	'Portal:ClosedRequests'  => 'My closed requests',
	'Portal:ResolvedRequests'  => 'Çözdüğüm istekler',
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

	'Enum:Undefined' => 'Tanımsız',
	'UI:Button:Refresh' => 'Yenile',
));



?>
