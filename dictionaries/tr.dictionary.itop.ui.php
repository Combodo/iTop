<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
	'Class:Query' => 'Sorgu',
	'Class:Query+' => 'Bir sorgu, dinamik bir şekilde tanımlanan bir veri setidir',
	'Class:Query/Attribute:name' => 'İsim',
	'Class:Query/Attribute:name+' => 'Sorgusunu tanımlar',
	'Class:Query/Attribute:description' => 'Açıklama',
	'Class:Query/Attribute:description+' => 'Sorgu için uzun açıklama (amaç, kullanım vb.)',
	'Class:Query/Attribute:is_template' => 'Template for OQL fields~~',
	'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications~~',
	'Class:Query/Attribute:is_template/Value:yes' => 'Yes~~',
	'Class:Query/Attribute:is_template/Value:no' => 'No~~',
	'Class:QueryOQL/Attribute:fields' => 'Alanlar',
	'Class:QueryOQL/Attribute:fields+' => 'Dışarı aktarmak için virgülle ayrılmış nitelikler listesi (veya alias.attribute)',
	'Class:QueryOQL' => 'OQL Query',
	'Class:QueryOQL+' => 'Nesne sorgusu diline dayanan bir sorgu',
	'Class:QueryOQL/Attribute:oql' => 'İfade',
	'Class:QueryOQL/Attribute:oql+' => 'OQL ifadesi',
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
	'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice~~',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User~~',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization~~',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)~~',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'Dahili kullanıcı',
	'Class:UserInternal+' => ITOP_APPLICATION_SHORT.'\'ta tanımlanan kullanıcı',
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
	'Class:URP_UserProfile/Name' => '%1$s ve %2$s arasındaki ilişki',
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
	'Class:URP_UserOrg/Name' => '%1$s ve %2$s arasındaki ilişki',
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
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'Menu:WelcomeMenu' => 'Hoşgeldiniz',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Hoşgeldiniz',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz',

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
	'UI:WelcomeMenu:Text'=> '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>This version features a brand new modern and accessible backoffice design.</div>

<div>We kept '.ITOP_APPLICATION.' core functions that you liked and modernized them to make you love them.
We hope you’ll enjoy this version as much as we enjoyed imagining and creating it.</div>

<div>Customize your '.ITOP_APPLICATION.' preferences for a personalized experience.</div>~~',
	'UI:WelcomeMenu:AllOpenRequests' => 'Açık istekler: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'İsteklerim',
	'UI:WelcomeMenu:OpenIncidents' => 'Açık Arızalar: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Konfigürasyon Kalemleri: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Bana atanan hatalar',
	'UI:AllOrganizations' => ' Tüm Kurumlar ',
	'UI:YourSearch' => 'Arama',
	'UI:LoggedAsMessage' => '%1$s (%2$s) kullanıcısı ile bağlanıldı~~',
	'UI:LoggedAsMessage+Admin' => '%1$s (%2$s, Administrator) kullanıcısı ile bağlanıldı~~',
	'UI:Button:Logoff' => 'Çıkış',
	'UI:Button:GlobalSearch' => 'Arama',
	'UI:Button:Search' => ' Arama ',
	'UI:Button:Clear' => ' Clear ~~',
	'UI:Button:SearchInHierarchy' => 'Search in hierarchy~~',
	'UI:Button:Query' => ' Sorgu ',
	'UI:Button:Ok' => 'Tamam',
	'UI:Button:Save' => 'Kaydet',
	'UI:Button:SaveAnd' => 'Save and %1$s~~',
	'UI:Button:Cancel' => 'İptal',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Uygula',
	'UI:Button:Send' => 'Send~~',
	'UI:Button:SendAnd' => 'Send and %1$s~~',
	'UI:Button:Back' => ' << Geri ',
	'UI:Button:Restart' => ' |<< Yeniden Başlat ',
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
	'UI:Button:Rename' => ' Yeniden adlandır... ',
	'UI:Button:ChangePassword' => ' Şifre değiştir ',
	'UI:Button:ResetPassword' => ' Şifreyi sıfırla ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',
	'UI:UserPref:DoNotShowAgain' => 'Do not show again~~',
	'UI:InputFile:NoFileSelected' => 'No File Selected~~',
	'UI:InputFile:SelectFile' => 'Select a file~~',

	'UI:SearchToggle' => 'Ara',
	'UI:ClickToCreateNew' => 'Yeni %1$s yarat~~',
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
	'UI:Error:ReadNotAllowedOn_Class' => 'You are not allowed to view objects of class %1$s~~',
	'UI:Error:BulkModifyNotAllowedOn_Class' => '%1$s sınıfına ait nesnelerin toplu güncellenmesine yetkiniz yok.',
	'UI:Error:ObjectAlreadyCloned' => 'Hata: nesne hali hazırda klonlanmış!',
	'UI:Error:ObjectAlreadyCreated' => 'Hata: nesne hali hazırda yaratılmış!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Hata: "%3$s" durumundaki %2$s nesnesi için "%1$s" uyarısı geçersizdir.',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',
	'UI:Error:InvalidToken' => 'Error: the requested operation has already been performed (CSRF token not found)~~',

	'UI:GroupBy:Count' => 'Say',
	'UI:GroupBy:Count+' => 'Eleman sayısı',
	'UI:CountOfObjects' => 'Kritere uyan %1$d nesne bulundu.',
	'UI_CountOfObjectsShort' => '%1$d nesne.',
	'UI:NoObject_Class_ToDisplay' => '%1$s nesne listelenecek',
	'UI:History:LastModified_On_By' => '%1$s tarihinde %2$s tarafından değiştirilmiş.',
	'UI:HistoryTab' => 'Tarihçe',
	'UI:NotificationsTab' => 'Uyarılar',
	'UI:History:BulkImports' => 'Tarihçe',
	'UI:History:BulkImports+' => 'CSV Dışarı Aktarma Listesi (Önce Son Dışarı Aktarma)',
	'UI:History:BulkImportDetails' => '%1$s (%2$s) \'de gerçekleştirilen CSV dışarı aktarmasından kaynaklanan değişiklikler',
	'UI:History:Date' => 'Tarih',
	'UI:History:Date+' => 'Değişiklik tarihi',
	'UI:History:User' => 'Kullanıcı',
	'UI:History:User+' => 'Değişikliğ yapan kullanıcı',
	'UI:History:Changes' => 'Değişiklikler',
	'UI:History:Changes+' => 'Nesneye yapılan değişiklikler',
	'UI:History:StatsCreations' => 'Yaratıldı',
	'UI:History:StatsCreations+' => 'Oluşturulan nesnelerin sayısı',
	'UI:History:StatsModifs' => 'Değiştirildi',
	'UI:History:StatsModifs+' => 'Değiştirilmiş nesnelerin sayısı',
	'UI:History:StatsDeletes' => 'Silindi',
	'UI:History:StatsDeletes+' => 'Silinen nesnelerin sayısı',
	'UI:Loading' => 'Yükleniyor...',
	'UI:Menu:Actions' => 'İşlemler',
	'UI:Menu:OtherActions' => 'Diğer İşlemler',
	'UI:Menu:Transitions' => 'Transitions~~',
	'UI:Menu:OtherTransitions' => 'Other Transitions~~',
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
	'UI:SplitDateTime-Date' => 'Tarih',
	'UI:SplitDateTime-Time' => 'Zaman',
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
	'UI:SearchValue:NbSelected' => '# Seçili',
	'UI:SearchValue:CheckAll' => 'Hepsini işaretleyin',
	'UI:SearchValue:UncheckAll' => 'Hepsinin işaretini kaldırın',
	'UI:SelectOne' => '-- Birini seçiniz --',
	'UI:Login:Welcome' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz!',
	'UI:Login:IncorrectLoginPassword' => 'Hatalı kullanıcı/şifre tekrar deneyiniz.',
	'UI:Login:IdentifyYourself' => 'Devam etmeden önce kendinizi tanıtınız',
	'UI:Login:UserNamePrompt' => 'Kullanıcı Adı',
	'UI:Login:PasswordPrompt' => 'Şifre',
	'UI:Login:ForgotPwd' => 'Şifrenizi mi unuttunuz?',
	'UI:Login:ForgotPwdForm' => 'Şifrenizi mi unuttunuz?',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.', hesabınızı sıfırlamak için izleyeceğiniz talimatları bulacağınız bir e-posta gönderebilir.',
	'UI:Login:ResetPassword' => 'Şimdi gönder!',
	'UI:Login:ResetPwdFailed' => 'Bir e-posta gönderilemedi: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' geçerli bir giriş değil',
	'UI:ResetPwd-Error-NotPossible' => 'Harici hesapların şifre sıfırlama izni yoktur.',
	'UI:ResetPwd-Error-FixedPwd' => 'Hesabın şifre sıfırlama izni yoktur.',
	'UI:ResetPwd-Error-NoContact' => 'Hesap bir kişiyle ilişkili değildir.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'Hesap, bir e-posta özelliğine sahip bir kişiyle ilişkili değildir. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-Error-NoEmail' => 'Bir e-posta adresi eksik. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-Error-Send' => 'E-posta ulaştırma teknik sorunu. Lütfen yöneticinize başvurun.',
	'UI:ResetPwd-EmailSent' => 'Lütfen e-posta kutunuzu kontrol edin ve talimatları izleyin...',
	'UI:ResetPwd-EmailSubject' => ITOP_APPLICATION_SHORT.'şifrenizi sıfırlayın',
	'UI:ResetPwd-EmailBody' => '<body><p>'.ITOP_APPLICATION_SHORT.' şifrenizin sıfırlanması talebinde bulundunuz.</p><p> Yeni şifre oluşturmak için lütfen aşağıdaki tek kullanımlık bağlantıyı <a href=\"%1$s\">takip ediniz.</a></p>',

	'UI:ResetPwd-Title' => 'Şifre sıfırla',
	'UI:ResetPwd-Error-InvalidToken' => 'Üzgünüz, ya parola zaten sıfırlandı ya da birkaç e-posta aldınız. Lütfen aldığınız en son e-postada verilen bağlantıyı kullandığınızdan emin olun',
	'UI:ResetPwd-Error-EnterPassword' => '\'%1$s\' hesabı için yeni bir şifre girin.',
	'UI:ResetPwd-Ready' => 'Şifre değiştirildi.',
	'UI:ResetPwd-Login' => 'Giriş yapmak için buraya tıklayın...',

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

	'UI:Login:About'                               => '~~',
	'UI:Login:ChangeYourPassword'                  => 'Şifre Değiştir',
	'UI:Login:OldPasswordPrompt'                   => 'Mevcut şifre',
	'UI:Login:NewPasswordPrompt'                   => 'Yeni şifre',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Yeni şifre tekrar',
	'UI:Login:IncorrectOldPassword'                => 'Hata: mevcut şifre hatalı',
	'UI:LogOffMenu'                                => 'Çıkış',
	'UI:LogOff:ThankYou' => ITOP_APPLICATION_SHORT.' Kullanıdığınız için teşekkürler',
	'UI:LogOff:ClickHereToLoginAgain'              => 'Tekrar bağlanmak için tıklayınız...',
	'UI:ChangePwdMenu'                             => 'Şifre değiştir...',
	'UI:Login:PasswordChanged' => 'Şifre başarıyla ayarlandı!',
	'UI:AccessRO-All' => ITOP_APPLICATION_SHORT.' salt okunurdur',
	'UI:AccessRO-Users' => ITOP_APPLICATION_SHORT.' sadece son kullanıcılar için okunurdur',
	'UI:ApplicationEnvironment' => 'Uygulama Ortamı: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Yeni şifre eşlenmedi !',
	'UI:Button:Login' => ITOP_APPLICATION_SHORT.'\'a Giriş',
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' erişim sınırlandırıldı. Sistem yöneticisi ile irtibata geçiniz',
	'UI:Login:Error:AccessAdmin'                   => 'Erişim sistem yönetci hesaplaları ile mümkün. Sistem yöneticisi ile irtibata geçiniz.',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne'                => '-- Birini seçiniz --',
	'UI:CSVImport:MappingNotApplicable'            => '-- alanı ihmal et --',
	'UI:CSVImport:NoData'                          => 'Boş veri seti..., veri giriniz!',
	'UI:Title:DataPreview'                         => 'Veri öngörüntüleme',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Hata: Veri sadece bir kolon içeriyor. Doğru ayıraç karakteri seçtiniz mi ?',
	'UI:CSVImport:FieldName'                       => 'Alan %1$d',
	'UI:CSVImport:DataLine1'                       => 'Veri Satırı 1',
	'UI:CSVImport:DataLine2'                       => 'Veri Satırı 2',
	'UI:CSVImport:idField'                         => 'id (Tekil anahtar)',
	'UI:Title:BulkImport' => ITOP_APPLICATION_SHORT.' - Toplu giriş',
	'UI:Title:BulkImport+'                         => 'CSV içeri aktarma aracı',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => '%1$d sınıfının %2$s \'nin senkronizasyonu',
	'UI:CSVImport:ClassesSelectOne'                => '-- Birini seçiniz --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Hata: "%1$s" hatalı kod, çünkü "%2$s" ile "%3$s" tekil ilişkide değil',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d adet nesne değişmeyecek.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d adet nesne değiştirilecek.',
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
	'UI:CSVImport:AlertMultipleMapping' => 'Lütfen bir hedef alanın yalnızca bir kez eşlendiğinden emin olun.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Lütfen en az bir sorgu kriteri seçiniz.',
	'UI:CSVImport:Encoding' => 'Karakter kodlaması',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Genel arama',
	'UI:UniversalSearch:Error' => 'Hata: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Aranacak sınıfı seçiniz: ',

	'UI:CSVReport-Value-Modified' => 'Değiştiridi',
	'UI:CSVReport-Value-SetIssue' => 'Değiştirilemedi - Sebep: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => '%1$s olarak değiştirilemedi - Sebep: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Eşleşme yok',
	'UI:CSVReport-Value-Missing' => 'Eksik Zorunlu Değer',
	'UI:CSVReport-Value-Ambiguous' => 'Belirsiz: %1$s nesnelerini buldum',
	'UI:CSVReport-Row-Unchanged' => 'Değiştirilmedi',
	'UI:CSVReport-Row-Created' => 'Yaratıldı',
	'UI:CSVReport-Row-Updated' => '%1$d sütunları güncellendi',
	'UI:CSVReport-Row-Disappeared' => '%1$d sütunları ortadan kayboldu',
	'UI:CSVReport-Row-Issue' => 'Sorun: %1$s~~',
	'UI:CSVReport-Value-Issue-Null' => 'Boş değere izin verilmez',
	'UI:CSVReport-Value-Issue-NotFound' => 'Nesne bulunamadı',
	'UI:CSVReport-Value-Issue-FoundMany' => '%1$d eşleşme bulundu',
	'UI:CSVReport-Value-Issue-Readonly' => 'Öznitelik \'%1$s\' salt okunurdur ve değiştirilemez (geçerli değer:%2$s, Önerilen Değer:%3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Giriş yapamadı: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Öznitelik için Beklenmeyen Değer \'%1$s\': Eşleşme bulunamadı, yazım kontrolü',
	'UI:CSVReport-Value-Issue-Unknown' => 'Öznitelik için Beklenmeyen Değer \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Nitelikler birbirleriyle tutarlı değil: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Beklenmeyen özellik değeri (ler)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Kayıp harici anahtar (lar) nedeniyle oluşturulamadı: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Yanlış Tarih Biçimi',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Uzlaşamadı',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Belirsiz uzlaşma',
	'UI:CSVReport-Row-Issue-Internal' => 'Dahili Hata: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Değiştirilmemiş',
	'UI:CSVReport-Icon-Modified' => 'Değiştirilmiş',
	'UI:CSVReport-Icon-Missing' => 'Kayıp',
	'UI:CSVReport-Object-MissingToUpdate' => 'Kayıp Nesne: Güncellenecek',
	'UI:CSVReport-Object-MissingUpdated' => 'Eksik Nesne: Güncellendi',
	'UI:CSVReport-Icon-Created' => 'Yaratıldı',
	'UI:CSVReport-Object-ToCreate' => 'Nesne oluşturulacak',
	'UI:CSVReport-Object-Created' => 'Nesne oluşturuldu',
	'UI:CSVReport-Icon-Error' => 'Hata',
	'UI:CSVReport-Object-Error' => 'HATA: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'BELIRSIZ: %1$s~~',
	'UI:CSVReport-Stats-Errors' => '%1$.0f yüklü nesnelerin %% hataları var ve göz ardı edilecek.',
	'UI:CSVReport-Stats-Created' => 'Yüklenen nesnelerin %1$.0f %% oluşturulacaktır.',
	'UI:CSVReport-Stats-Modified' => 'Yüklenen nesnelerin %1$.0f %% değiştirilecektir.',

	'UI:CSVExport:AdvancedMode' => 'Gelişmiş Mod',
	'UI:CSVExport:AdvancedMode+' => 'Gelişmiş modda, dışa aktarmaya birkaç sütun eklenir: nesnenin kimliği, harici anahtarların kimliği ve bunların uzlaşma özellikleri',
	'UI:CSVExport:LostChars' => 'Kodlama sorunu',
	'UI:CSVExport:LostChars+' => 'İndirilen dosya %1$s\'ye kodlanır. '.ITOP_APPLICATION_SHORT.', bu formatla uyumlu olmayan bazı karakterleri tespit etti. Bu karakterler ya bir ikame ile değiştirilecektir (örneğin, vurgulanmış karakterleri aksanı kaybedilen) veya atılacaklardır. Verileri web tarayıcınızdan kopyalayabilir / yapıştırabilirsiniz. Alternatif olarak, kodlamayı değiştirmek için yöneticinize başvurabilirsiniz (bkz. Parametre \'csv_file_default_charset \').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Denetleme',
	'UI:Audit:InteractiveAudit' => 'Etkileşimli Denetleme',
	'UI:Audit:HeaderAuditRule' => 'Denetleme Kuralı',
	'UI:Audit:HeaderNbObjects' => 'Nesne Sayısı',
	'UI:Audit:HeaderNbErrors' => 'Hata sayısı',
	'UI:Audit:PercentageOk' => '% Tamam',
	'UI:Audit:OqlError' => 'OQL Error~~',
	'UI:Audit:Error:ValueNA' => 'n/a~~',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule~~',
	'UI:Audit:ErrorIn_Rule_Reason' => 'Kuraldaki OQL hatası %1$s:%2$s.',
	'UI:Audit:ErrorIn_Category' => 'Error in Category~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'Kategorideki OQL Hatası %1$s:%2$s.',
	'UI:Audit:AuditErrors' => 'Audit Errors~~',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited~~',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors~~',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated~~',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%~~',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - OQL Sorgu değerlendirme',
	'UI:RunQuery:QueryExamples' => 'Sorgu örnekleri',
	'UI:RunQuery:QueryResults' => 'Query Results~~',
	'UI:RunQuery:HeaderPurpose' => 'Amaç',
	'UI:RunQuery:HeaderPurpose+' => 'Sorgu açıklaması',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL ifadesi',
	'UI:RunQuery:HeaderOQLExpression+' => 'OQL yapısında sorgu',
	'UI:RunQuery:ExpressionToEvaluate' => 'Değerlendirilecek ifade: ',
	'UI:RunQuery:QueryArguments' => 'Query Arguments~~',
	'UI:RunQuery:MoreInfo' => 'Sorgu hakkında detaylı bilgi: ',
	'UI:RunQuery:DevelopedQuery' => 'Yeniden düzenlenen sorgu: ',
	'UI:RunQuery:SerializedFilter' => 'Özel filtre: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Sorgu sırasında hata oluştu: %1$s',
	'UI:Query:UrlForExcel' => 'MS-Excel Web Queries için Kullanım URL\'si',
	'UI:Query:UrlV1' => 'Alanların listesi belirtilmeden bırakılmıştır. <em>export-V2.php</em> sayfası bu bilgi olmadan çağrılamaz. Bu nedenle, aşağıda önerilen URL eski sayfaya işaret etmektedir: <em>export.php</em>. Dışa aktarmanın bu eski sürümü aşağıdaki sınırlamaya sahiptir: dışa aktarılan alanların listesi, '.ITOP_APPLICATION_SHORT.'\'un çıktı biçimine ve veri modeline bağlı olarak değişebilir. Dışa aktarılan sütunların listesinin uzun vadede sabit kalacağını garanti etmek istiyorsanız, "Alanlar" özelliği için bir değer belirtmeli ve <em>export-V2.php</em> sayfasını kullanmalısınız.',
	'UI:Schema:Title' => 'iTop objects schema',
	'UI:Schema:TitleForClass' => '%1$s schema~~',
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
	'UI:Edit:SearchQuery' => 'Select a predefined query~~',
	'UI:Edit:TestQuery' => 'Test sorgusu',
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
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Bu nesne silinemedi, çünkü bazı manuel işlemler, bundan önce gerçekleştirilmelidir',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s on behalf of %2$s~~',
	'UI:Delete:Deleted' => 'Silindi',
	'UI:Delete:AutomaticallyDeleted' => 'otomatik olarak silindi',
	'UI:Delete:AutomaticResetOf_Fields' => '%1$s alanlarını otomatik sıfırla',
	'UI:Delete:CleaningUpRefencesTo_Object' => '%1$s nesnesine verilen tüm referansları temizle...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnesinin tüm referanslarını temizle ...',
	'UI:Delete:Done+' => 'Ne yapıldı...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s silindi.',
	'UI:Delete:ConfirmDeletionOf_Name' => '%1$s\'in silimi',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$s sınıfına ait %1$d nesnelerinin silimi ',
	'UI:Delete:CannotDeleteBecause' => 'Sililemedi: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Otomatik olarak silinmiş olmalı, ancak bu mümkün değile: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Manuel olarak silinmeli, ancak bu mümkün değil: %1$s',
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
	'UI:WelcomeToITop' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz',
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s detayları',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Hata',
	'UI:ObjectDoesNotExist' => 'Nesne mevcut değil veya yetkiniz yok.',
	'UI:ObjectArchived' => 'Bu nesne arşivlendi. Lütfen arşiv modunu etkinleştirin veya yöneticinize başvurun',
	'Tag:Archived' => 'Arşivlendi',
	'Tag:Archived+' => 'Sadece arşiv modunda erişilebilir',
	'Tag:Obsolete' => 'Kullanım dışı',
	'Tag:Obsolete+' => 'Etki analizi ve arama sonuçlarından hariç tutuldu',
	'Tag:Synchronized' => 'Senkronize edildi',
	'ObjectRef:Archived' => 'Arşivlendi',
	'ObjectRef:Obsolete' => 'Kullanım dışı',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Arama Sonuçları',
	'UI:SearchResultsTitle' => 'Arama Sonuçları',
	'UI:SearchResultsTitle+' => 'Tam Metin Arama Sonuçları',
	'UI:Search:NoSearch' => 'Nothing to search for~~',
	'UI:Search:NeedleTooShort' => 'Arama dizesi \\"%1$s\\" çok kısa. Lütfen en az %2$d karakter yazın',
	'UI:Search:Ongoing' => 'Aranıyor \\"%1$s\\"',
	'UI:Search:Enlarge' => 'Aramayı genişletin',
	'UI:FullTextSearchTitle_Text' => '"%1$s" için arama sonuçları:',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%2$s sınıfına ait %1$d nesne bulundu.',
	'UI:Search:NoObjectFound' => 'Kayıt bulunamadı.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s modifikasyon',
	'UI:ModificationTitle_Class_Object' => '%1$s: <span class=\\"hilite\\">%2$s</span> modifikasyonu',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s modifikasyonunu klonlayınız',
	'UI:CloneTitle_Class_Object' => '%1$s klonu: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - Yeni %1$s yaratımı',
	'UI:CreationTitle_Class' => 'Yeni %1$s yarat',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Yaratılacak %1$s nesne tipini seçiniz',
	'UI:Class_Object_NotUpdated' => 'Değişiklik tespit edilemedi, %1$s (%2$s) <strong>güncellenmedi</strong>.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) güncellendi.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Toplu silme işlemi',
	'UI:BulkDeleteTitle' => 'Silmek istediğiniz nesneleri seçiniz:',
	'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' Nesne yaratıldı.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s yaratıldı.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '%1$s işlemi %2$s durumunda %3$s nesnesine uygulanır. Bir sonraki durum: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Nesne kaydedilemedi: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Kritik Hata',
	'UI:SystemIntrusion' => 'Bu işlem için yetkiniz yok',
	'UI:FatalErrorMessage' => 'Kritik Hata, iTop devam edemiyor.',
	'UI:Error_Details' => 'Hata: %1$s.',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' Kullanıcı Yönetimi - profil koruması',
	'UI:UserManagement:Class' => 'Sınıf',
	'UI:UserManagement:Class+' => 'Nesnin sınıfı',
	'UI:UserManagement:ProjectedObject' => 'Nesne',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
	'UI:UserManagement:AnyObject' => '* herhangi *',
	'UI:UserManagement:User' => 'Kullanıcı',
	'UI:UserManagement:User+' => 'User involved in the projection',
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

	'Menu:AdminTools' => 'Yönetim Araçları',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Yönetim Araçları',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Yönetici profiline izin verilen araçlar',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Değişiklik Yönetimi',
	'UI:ChangeManagementMenu+' => 'Değişiklik Yönetimi',
	'UI:ChangeManagementMenu:Title' => 'Değişiklik Özeti',
	'UI-ChangeManagementMenu-ChangesByType' => 'Değişiklik tipine göre',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Değişiklik durumuna göre',
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

	'Menu:CSVImportMenu' => 'CSV dışardan al',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Çoklu yaratım veya güncelleme',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Veri Modeli',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Veri Modeli Özeti',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Dışarı ver',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Sorgu sonucunu HTML, CSV veya XML olarak dışarı aktar',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Uyarılar',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Uyarıların yapılandırılması',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Uyarıların yapılandırılması',
	'UI:NotificationsMenu:Help' => 'Yardım',
	'UI:NotificationsMenu:HelpContent' => '<p>In '.ITOP_APPLICATION_SHORT.' uyarı mekanizması ihtiyaca göre uyarlanabilir. Uyarılar iki tip nesne üzerine kurulmuştur: <i>tetikleme (triggers) ve işlemler (actions)</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> define the actions to be performed when the triggers execute. For now there are only two kind of actions:
<ol>
	<li>Sending an email message: Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.<br />
	Özel sayfa: <a href="../setup/email.test.php" target="_blank">email.test.php</a> PHP e-posta konfigürnunu test ediniz.</li>
	<li>Outgoing webhooks: Allow integration with a third-party application by sending structured data to a defined URL.</li>
</ol>
</p>
<p>İşlemin gerçekleşmesi için bir tetikleme ile ilişkilendirilmesi gerekir.
Tetikleme gerçekleştiriğinde işlemler tanımlanan sıra numarası ile gerçekleştirilir.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Tetikleyiciler',
	'UI:NotificationsMenu:AvailableTriggers' => 'Kullanılabilir tetikleyiciler',
	'UI:NotificationsMenu:OnCreate' => 'Nesne yaratıldığında',
	'UI:NotificationsMenu:OnStateEnter' => 'Nesnenin durumuna girişinde',
	'UI:NotificationsMenu:OnStateLeave' => 'Nesnenin durumdan çıkışında',
	'UI:NotificationsMenu:Actions' => 'İşlemler',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Email actions~~',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook actions (outgoing integrations)~~',
	'UI:NotificationsMenu:Actions:Action' => 'Other actions~~',
	'UI:NotificationsMenu:AvailableActions' => 'Kullanılabilir işlemler',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Denetleme Kategorileri',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Denetleme Kategorileri',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Denetleme Kategorileri',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Sorgu çalıştır',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Sorgu çalıştır',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:AuditCategories' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Denetleme Kategorileri', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Sorgu çalıştır', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Sorgu deyişleri kitabı', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Sorgu deyişleri kitabı', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Veri Yönetimi',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Veri Yönetimi',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Genel sorgu',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Herhangi bir arama...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Kullanıcı Yönetimi',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Kullanıcı Yönetimi',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profiller',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profiller',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profiller',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Kullanıcı Hesapları',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Kullanıcı Hesapları',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Kullanıcı Hesapları',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s versiyonu %2$s',
	'UI:iTopVersion:Long' => '%1$s  %4$s tarihli versiyonu %2$s-%3$s',
	'UI:PropertiesTab' => 'Özellikler',

	'UI:OpenDocumentInNewWindow_' => 'Açmak~~',
	'UI:DownloadDocument_' => 'Indirmek~~',
	'UI:Document:NoPreview' => 'Bu tip doküman için öngösterim mevcut değil',
	'UI:Download-CSV' => 'İndir %1$s',

	'UI:DeadlineMissedBy_duration' => '%1$s ile kaçırıldı',
	'UI:Deadline_LessThan1Min' => '< 1 dk.',
	'UI:Deadline_Minutes' => '%1$d dk.',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$ddk',
	'UI:Deadline_Days_Hours_Minutes' => '%1$d gün %2$d saat %3$d dk',
	'UI:Help' => 'Yardım',
	'UI:PasswordConfirm' => 'Onay',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Yeni %1$s nesneleri eklemeden önce bu nesneyi kaydediniz.',
	'UI:DisplayThisMessageAtStartup' => 'Bu mesajı başlangıçta göster',
	'UI:RelationshipGraph' => 'Grafiksel gösterim',
	'UI:RelationshipList' => 'List~~',
	'UI:RelationGroups' => 'Gruplar',
	'UI:OperationCancelled' => 'İşlem iptal edildi',
	'UI:ElementsDisplayed' => 'Filtreleme',
	'UI:RelationGroupNumber_N' => 'Grup #%1$d',
	'UI:Relation:ExportAsPDF' => 'PDF olarak dışarı aktar...',
	'UI:RelationOption:GroupingThreshold' => 'Gruplandırma eşiği',
	'UI:Relation:AdditionalContextInfo' => 'Ek bağlam bilgisi',
	'UI:Relation:NoneSelected' => 'Hiçbiri',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Ek olarak dışarı aktar...',
	'UI:Relation:DrillDown' => 'Ayrıntılar...',
	'UI:Relation:PDFExportOptions' => 'PDF Dışarı Aktarma Seçenekleri',
	'UI:Relation:AttachmentExportOptions_Name' => 'Eklenti seçenekleri %1$s',
	'UI:RelationOption:Untitled' => 'Başlıksız',
	'UI:Relation:Key' => 'Anahtar',
	'UI:Relation:Comments' => 'Yorumlar',
	'UI:RelationOption:Title' => 'Başlık',
	'UI:RelationOption:IncludeList' => 'Nesnelerin listesini ekleyin',
	'UI:RelationOption:Comments' => 'Yorumlar',
	'UI:Button:Export' => 'Dışarı aktar',
	'UI:Relation:PDFExportPageFormat' => 'Sayfa Biçimi',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Mektup',
	'UI:Relation:PDFExportPageOrientation' => 'Sayfa yönlendirme',
	'UI:PageOrientation_Portrait' => 'Dikey',
	'UI:PageOrientation_Landscape' => 'Yatay',
	'UI:RelationTooltip:Redundancy' => 'Yedeklilik',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => 'Etkilenmiş nesnelerin sayısı: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Kritik Eşik: %1$d / %2$d',
	'Portal:Title' => ITOP_APPLICATION_SHORT.' Kullanıcı Portalı',
	'Portal:NoRequestMgmt' => 'Sevgili %1$s, hesabınız profil \'Portal kullanıcısı \' ile yapılandırıldığından bu sayfaya yönlendirildiniz. Ne yazık ki, '.ITOP_APPLICATION_SHORT.', özellik \'istek yönetimi\' ile kurulmamıştır. Lütfen yöneticinize başvurun',
	'Portal:Refresh' => 'Yenile',
	'Portal:Back' => 'Geri',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s~~',
	'Portal:TitleDetailsFor_Request' => 'İstek için detaylar',
	'Portal:ShowOngoing' => 'Show open requests~~',
	'Portal:ShowClosed' => 'Show closed requests~~',
	'Portal:CreateNewRequest' => 'Yeni istek yarat',
	'Portal:CreateNewRequestItil' => 'Yeni istek yarat',
	'Portal:CreateNewIncidentItil' => 'Yeni bir olay raporu oluşturun',
	'Portal:ChangeMyPassword' => 'Şifre değiştir',
	'Portal:Disconnect' => 'Çıkış',
	'Portal:OpenRequests' => 'Açık isteklerim',
	'Portal:ClosedRequests' => 'My closed requests~~',
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
	'Portal:Attachments' => 'Eklentiler',
	'Portal:AddAttachment' => ' Dosya ekle ',
	'Portal:RemoveAttachment' => ' Dosya çıkar ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Ek # %1$d ila %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => '%1$s için bir şablon seçin',
	'Enum:Undefined' => 'Tanımsız',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Gün %2$s Saat %3$s Dakika %4$s Saniye',
	'UI:ModifyAllPageTitle' => 'Hepsini değiştir',
	'UI:Modify_N_ObjectsOf_Class' => '%1$d Sınıfının Değiştirilmesi %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => '%1$d nesnelerinin %3$s \'dışında %1$d nesnelerini değiştirme',
	'UI:Menu:ModifyAll' => 'Değiştir...',
	'UI:Button:ModifyAll' => 'Hepsini değiştir',
	'UI:Button:PreviewModifications' => 'Değişiklikleri görüntüle >>',
	'UI:ModifiedObject' => 'Nesne değiştirildi',
	'UI:BulkModifyStatus' => 'Operasyon',
	'UI:BulkModifyStatus+' => 'İşlemin durumu',
	'UI:BulkModifyErrors' => 'Hatalar (varsa)',
	'UI:BulkModifyErrors+' => 'Değişikliği önleyen hatalar',
	'UI:BulkModifyStatusOk' => 'Tamam',
	'UI:BulkModifyStatusError' => 'Hata',
	'UI:BulkModifyStatusModified' => 'Değiştirildi',
	'UI:BulkModifyStatusSkipped' => 'Atlandı',
	'UI:BulkModify_Count_DistinctValues' => '%1$d belirgin değerler:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d Zaman (lar)',
	'UI:BulkModify:N_MoreValues' => '%1$d Diğer değerler...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Salt okunur alanını ayarlamaya çalışıyor: %1$s~~',
	'UI:FailedToApplyStimuli' => 'Eylem başarısız oldu',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: %2$d Nesnelerin %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Metninizi buraya yazın:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'İlk değer:',
	'UI:AttemptingToSetASlaveAttribute_Name' => '%1$s alanı yazılabilir değildir, çünkü veri senkronizasyonu tarafından kullanılıyor. Değer ayarlanmadı.',
	'UI:ActionNotAllowed' => 'Bu işlemi bu nesnelerde yapmanıza izin verilmez.',
	'UI:BulkAction:NoObjectSelected' => 'Lütfen bu işlemi gerçekleştirmek için en az bir nesne seçin',
	'UI:AttemptingToChangeASlaveAttribute_Name' => '%1$s alanı yazılabilir değildir, çünkü veri senkronizasyonu tarafından kullanılıyor. Değer değişmeden kalır.',
	'UI:Pagination:HeaderSelection' => 'Toplam: %1$s erinin nesneleri (%2$s nesneleri seçildi).',
	'UI:Pagination:HeaderNoSelection' => 'Toplam: %1$s nesne.',
	'UI:Pagination:PageSize' => '%1$s Sayfa başına nesneler',
	'UI:Pagination:PagesLabel' => 'Sayfalar:',
	'UI:Pagination:All' => 'Hepsi',
	'UI:HierarchyOf_Class' => '%1$s \'nin hiyerarşisi',
	'UI:Preferences' => 'Tercihler',
	'UI:ArchiveModeOn' => 'Arşiv modunu etkinleştirin',
	'UI:ArchiveModeOff' => 'Arşiv modunu devre dışı bırak',
	'UI:ArchiveMode:Banner' => 'Arşiv Modu',
	'UI:ArchiveMode:Banner+' => 'Arşivlenmiş nesneler görünür ve hiçbir değişiklik yapılmasına izin verilmez',
	'UI:FavoriteOrganizations' => 'Favori organizasyonlar',
	'UI:FavoriteOrganizations+' => 'Hızlı bir erişim için açılır menüde görmek istediğiniz kuruluşların altındaki listeyi kontrol edin. Bunun bir güvenlik ayarı olmadığını, herhangi bir kuruluştan nesnelerin hala göründüğünü ve aşağı açılan listede \\"tüm kuruluşlar\\" seçilerek erişilebileceğini unutmayın',
	'UI:FavoriteLanguage' => 'Kullanıcı arayüzünün dili',
	'UI:Favorites:SelectYourLanguage' => 'Tercih ettiğiniz dili seçin',
	'UI:FavoriteOtherSettings' => 'Diğer ayarlar',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Listeler için varsayılan uzunluk: %1$s sayfa sayfa başına',
	'UI:Favorites:ShowObsoleteData' => 'Eski bilgileri göster',
	'UI:Favorites:ShowObsoleteData+' => 'Arama sonuçlarında ve seçilecek öğelerin listelerinde eski bilgileri gösterin',
	'UI:NavigateAwayConfirmationMessage' => 'Herhangi bir değişiklik atılır',
	'UI:CancelConfirmationMessage' => 'Değişikliklerinizi kaybedersiniz. Yine de devam et?',
	'UI:AutoApplyConfirmationMessage' => 'Bazı değişiklikler henüz uygulanmadı. '.ITOP_APPLICATION_SHORT.'\'un değişiklikleri uygulamasını istiyor musunuz?',
	'UI:Create_Class_InState' => '%1$s durumunda oluşturun: ',
	'UI:OrderByHint_Values' => 'Sıralama düzeni: %1$s',
	'UI:Menu:AddToDashboard' => 'Panoya ekleyin...',
	'UI:Button:Refresh' => 'Yenile',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:SwitchToStandardDashboard' => 'Switch to standard dashboard~~',
	'UI:Toggle:SwitchToCustomDashboard' => 'Switch to custom dashboard~~',

	'UI:ConfigureThisList' => 'Bu listeyi yapılandırın...',
	'UI:ListConfigurationTitle' => 'Liste Yapılandırması',
	'UI:ColumnsAndSortOrder' => 'Liste Yapılandırması:',
	'UI:UseDefaultSettings' => 'Varsayılan ayarları kullanın',
	'UI:UseSpecificSettings' => 'Aşağıdaki ayarları kullanın:',
	'UI:Display_X_ItemsPerPage' => 'Sayfa başına %1$s öğe göster',
	'UI:UseSavetheSettings' => 'Ayarları kaydedin',
	'UI:OnlyForThisList' => 'Sadece bu liste için',
	'UI:ForAllLists' => 'Tüm listeler için varsayılan',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Yaygın Adı)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Yukarıya taşı',
	'UI:Button:MoveDown' => 'Aşağıya taşı',

	'UI:OQL:UnknownClassAndFix' => 'Bilinmeyen sınıf \\"%1$s\\". Bunun yerine \\"%2$s\\" deneyebilirsiniz.',
	'UI:OQL:UnknownClassNoFix' => 'Bilinmeyen sınıf \\"%1$s\\"~~',

	'UI:OnlyForThisList' => 'Only for this list~~',
	'UI:ForAllLists' => 'Default for all lists~~',
	'UI:ExtKey_AsLink' => '%1$s (Link)~~',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)~~',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)~~',
	'UI:Button:MoveUp' => 'Move Up~~',
	'UI:Button:MoveDown' => 'Move Down~~',

	'UI:OQL:UnknownClassAndFix' => 'Unknown class \\"%1$s\\". You may try \\"%2$s\\" instead.~~',
	'UI:OQL:UnknownClassNoFix' => 'Unknown class \\"%1$s\\"~~',

	'UI:Dashboard:EditCustom' => 'Bu sayfayı düzenleyin...',
	'UI:Dashboard:CreateCustom' => 'Create a custom version...~~',
	'UI:Dashboard:DeleteCustom' => 'Delete custom version...~~',
	'UI:Dashboard:RevertConfirm' => 'Orijinal versiyonda yapılan her değişiklik kaybolacaktır. Lütfen bunu yapmak istediğinizi onaylayın.',
	'UI:ExportDashBoard' => 'Bir dosyaya çıkart',
	'UI:ImportDashBoard' => 'Bir dosyadan aktar ...',
	'UI:ImportDashboardTitle' => 'Bir dosyadan aktar',
	'UI:ImportDashboardText' => 'İçe aktarılacak bir gösterge paneli dosyası seçin:',
	'UI:Dashboard:Actions' => 'Dashboard actions~~',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'This dashboard displays information that does not include the on-going changes.~~',

	'UI:DashletCreation:Title' => 'Yeni bir gösterge paneli öğesi oluşturun',
	'UI:DashletCreation:Dashboard' => 'Gösterge paneli',
	'UI:DashletCreation:DashletType' => 'Gösterge paneli öğesi tipi',
	'UI:DashletCreation:EditNow' => 'Gösterge panelini düzenleyin',

	'UI:DashletCreation:Title' => 'Create a new Dashlet~~',
	'UI:DashletCreation:Dashboard' => 'Dashboard~~',
	'UI:DashletCreation:DashletType' => 'Dashlet Type~~',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard~~',

	'UI:DashboardEdit:Title' => 'Gösterge paneli editörü',
	'UI:DashboardEdit:DashboardTitle' => 'Başlık',
	'UI:DashboardEdit:AutoReload' => 'Otomatik yenileme',
	'UI:DashboardEdit:AutoReloadSec' => 'Otomatik Yenileme Aralığı (Saniye)',
	'UI:DashboardEdit:AutoReloadSec+' => 'İzin verilen minimum %1$d saniyedir',
	'UI:DashboardEdit:Revert' => 'Revert~~',
	'UI:DashboardEdit:Apply' => 'Apply~~',

	'UI:DashboardEdit:Layout' => 'Düzen',
	'UI:DashboardEdit:Properties' => 'Gösterge paneli özellikleri',
	'UI:DashboardEdit:Dashlets' => 'Mevcut gösterge paneli öğeleri',
	'UI:DashboardEdit:DashletProperties' => 'Gösterge paneli öğesi özellikleri',

	'UI:Form:Property' => 'Mülkiyet',
	'UI:Form:Value' => 'Değer',

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

	'UI:DashletPlainText:Label' => 'Metin',
	'UI:DashletPlainText:Description' => 'Düz metin (biçimlendirme yok)',
	'UI:DashletPlainText:Prop-Text' => 'Metin',
	'UI:DashletPlainText:Prop-Text:Default' => 'Lütfen buraya bir metin girin...',

	'UI:DashletObjectList:Label' => 'Nesne Listesi',
	'UI:DashletObjectList:Description' => 'Nesne Listesi Gösterge Paneli Öğesi',
	'UI:DashletObjectList:Prop-Title' => 'Başlık',
	'UI:DashletObjectList:Prop-Query' => 'Sorgu',
	'UI:DashletObjectList:Prop-Menu' => 'Menü',

	'UI:DashletGroupBy:Prop-Title' => 'Başlık',
	'UI:DashletGroupBy:Prop-Query' => 'Sorgu',
	'UI:DashletGroupBy:Prop-Style' => 'Stil',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Grup tarafından...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => '%1$s (0-23) \'ün bir saati',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => '%1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => '%1$s için haftanın günü',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => '%1$s için haftanın günü,',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (saat)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (ay)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (hafta Günü)~~',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (ayın günü)~~',
	'UI:DashletGroupBy:MissingGroupBy' => 'Lütfen nesnelerin birlikte gruplandırılacağı alanı seçin',

	'UI:DashletGroupByPie:Label' => 'Pasta grafiği',
	'UI:DashletGroupByPie:Description' => 'Pasta grafiği',
	'UI:DashletGroupByBars:Label' => 'Çubuk grafiği',
	'UI:DashletGroupByBars:Description' => 'Çubuk grafiği',
	'UI:DashletGroupByTable:Label' => 'Grup (tablo)',
	'UI:DashletGroupByTable:Description' => 'Liste (bir alan tarafından gruplandırılmış)',

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

	'UI:DashletHeaderStatic:Label' => 'Başlık',
	'UI:DashletHeaderStatic:Description' => 'Yatay bir ayıracı görüntüler',
	'UI:DashletHeaderStatic:Prop-Title' => 'Başlık',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kişiler',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Simge',

	'UI:DashletHeaderDynamic:Label' => 'İstatistikli Başlık',
	'UI:DashletHeaderDynamic:Description' => 'İstatistiklerle başlık (Gruplandırılmış ...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Başlık',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kişiler',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Simgesi',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Altyazı',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kişiler',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Sorgu',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Grup tarafından',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Değerler',

	'UI:DashletBadge:Label' => 'Rozeti',
	'UI:DashletBadge:Description' => 'Yeni / arama ile nesne simgesi',
	'UI:DashletBadge:Prop-Class' => 'Sınıf',

	'DayOfWeek-Sunday' => 'Pazar',
	'DayOfWeek-Monday' => 'Pazartesi',
	'DayOfWeek-Tuesday' => 'Salı günü',
	'DayOfWeek-Wednesday' => 'Çarşamba',
	'DayOfWeek-Thursday' => 'Perşembe',
	'DayOfWeek-Friday' => 'Cuma',
	'DayOfWeek-Saturday' => 'Cumartesi',
	'Month-01' => 'Ocak',
	'Month-02' => 'Şubat',
	'Month-03' => 'Mart',
	'Month-04' => 'Nisan',
	'Month-05' => 'Mayıs',
	'Month-06' => 'Haziran',
	'Month-07' => 'Temmuz',
	'Month-08' => 'Ağustos',
	'Month-09' => 'Eylül',
	'Month-10' => 'Ekim',
	'Month-11' => 'Kasım',
	'Month-12' => 'Aralık',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Paz',
	'DayOfWeek-Monday-Min' => 'Pzt',
	'DayOfWeek-Tuesday-Min' => 'Sal',
	'DayOfWeek-Wednesday-Min' => 'Car',
	'DayOfWeek-Thursday-Min' => 'Per',
	'DayOfWeek-Friday-Min' => 'Cum',
	'DayOfWeek-Saturday-Min' => 'Cts',
	'Month-01-Short' => 'Oca',
	'Month-02-Short' => 'Şub',
	'Month-03-Short' => 'Mar',
	'Month-04-Short' => 'Nis',
	'Month-05-Short' => 'May',
	'Month-06-Short' => 'Haz',
	'Month-07-Short' => 'Tem',
	'Month-08-Short' => 'Ağu',
	'Month-09-Short' => 'Eyl',
	'Month-10-Short' => 'Eki',
	'Month-11-Short' => 'Kas',
	'Month-12-Short' => 'Ara',
	'Calendar-FirstDayOfWeek' => '0', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Bir kısayol oluşturun...',
	'UI:ShortcutRenameDlg:Title' => 'Kısayolu yeniden adlandırın',
	'UI:ShortcutListDlg:Title' => 'Liste için bir kısayol oluşturun',
	'UI:ShortcutDelete:Confirm' => 'Lütfen kısayolları silmek istediğinizi onaylayın.',
	'Menu:MyShortcuts' => 'Kısayollarım', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Kısayol',
	'Class:Shortcut+' => '~~',
	'Class:Shortcut/Attribute:name' => 'İsim',
	'Class:Shortcut/Attribute:name+' => 'Menü ve sayfa başlığında kullanılan etiket',
	'Class:ShortcutOQL' => 'Arama Sonucu Kısayolu',
	'Class:ShortcutOQL+' => '~~',
	'Class:ShortcutOQL/Attribute:oql' => 'Sorgu',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL Aramak için nesnelerin listesini tanımlama',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Otomatik yenileme',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Devre dışı',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Özel Oran',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Otomatik Yenileme Aralığı (Saniye)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'İzin verilen minimum %1$d saniyedir',

	'UI:FillAllMandatoryFields' => 'Lütfen tüm zorunlu alanları doldurun.',
	'UI:ValueMustBeSet' => 'Lütfen bir değer belirtin',
	'UI:ValueMustBeChanged' => 'Lütfen değeri değiştirin',
	'UI:ValueInvalidFormat' => 'Geçersiz format',

	'UI:CSVImportConfirmTitle' => 'Lütfen operasyonu onaylayın',
	'UI:CSVImportConfirmMessage' => 'Bunu yapmak istediğinden emin misin?',
	'UI:CSVImportError_items' => 'Hatalar: %1$d',
	'UI:CSVImportCreated_items' => 'Oluşturuldu: %1$d',
	'UI:CSVImportModified_items' => 'Değiştirildi: %1$d',
	'UI:CSVImportUnchanged_items' => 'Değiştirilmedi: %1$d',
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

	'UI:Button:Remove' => 'Kaldır',
	'UI:AddAnExisting_Class' => '%1$s tipi nesneleri ekleyin...',
	'UI:SelectionOf_Class' => '%1$s türünün nesnelerinin seçimi',

	'UI:AboutBox' => 'About'.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'About '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Veri modeli',
	'UI:About:Support' => 'Destek bilgisi',
	'UI:About:Licenses' => 'Lisanslar',
	'UI:About:InstallationOptions' => 'Yüklü modüller',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'Oturumunuz kapandı. Uygulamayı kullanmaya devam etmek giriş yapmalısınız.',
	'UI:DisconnectedDlgTitle' => 'Uyarı!',
	'UI:LoginAgain' => 'Tekrar giriş yapın',
	'UI:StayOnThePage' => 'Bu sayfada kalın',

	'ExcelExporter:ExportMenu' => 'Dışarı Excel aktar...',
	'ExcelExporter:ExportDialogTitle' => 'Dışarı Excel aktar',
	'ExcelExporter:ExportButton' => 'Dışarı aktar',
	'ExcelExporter:DownloadButton' => '%1$s indir',
	'ExcelExporter:RetrievingData' => 'Verileri geri alıyor...',
	'ExcelExporter:BuildingExcelFile' => 'Excel dosyasını oluşturuyor...',
	'ExcelExporter:Done' => 'Yapıldı.',
	'ExcelExport:AutoDownload' => 'Dışarı aktarma hazır olduğunda indirmeyi otomatik olarak başlatın',
	'ExcelExport:PreparingExport' => 'Dışarı aktarma hazırlanıyor...',
	'ExcelExport:Statistics' => 'İstatistikler',
	'portal:legacy_portal' => 'Son Kullanıcı Arayüzü',
	'portal:backoffice' => ITOP_APPLICATION_SHORT.'Arka Ofis Kullanıcı Arayüzü',

	'UI:CurrentObjectIsLockedBy_User' => 'Nesne %1$s tarafından değiştirildiğinden beri kilitli.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Nesne  şu anda %1$s tarafından değiştiriliyor. Değişiklikleriniz üzerine yazıldığı için gönderilemez.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'The object is currently being modified by %1$s. You\'ll be able to submit your modifications once they have finished.~~',
	'UI:CurrentObjectLockExpired' => 'Nesnenin eşzamanlı modifikasyonlarını önleyen kilit sona ermiştir.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Nesnenin eşzamanlı modifikasyonlarını önleyen kilit sona ermiştir. Diğer kullanıcıların artık bu nesneyi değiştirmesine izin verildiğinden, artık değişikliklerinizi gönderemezsiniz.',
	'UI:ConcurrentLockKilled' => 'Geçerli nesnedeki modifikasyonları önleyen kilitleme silindi',
	'UI:Menu:KillConcurrentLock' => 'Eşzamanlı değişiklik kilidini kaldır!',

	'UI:Menu:ExportPDF' => 'PDF olarak dışarı aktar...',
	'UI:Menu:PrintableVersion' => 'Printer friendly version~~',

	'UI:BrowseInlineImages' => 'Browse images...~~',
	'UI:UploadInlineImageLegend' => 'Upload a new image~~',
	'UI:SelectInlineImageToUpload' => 'Select the image to upload~~',
	'UI:AvailableInlineImagesLegend' => 'Available images~~',
	'UI:NoInlineImage' => 'There is no image available on the server. Use the "Browse" button above to select an image from your computer and upload it to the server.~~',

	'UI:ToggleFullScreen' => 'Toggle Maximize / Minimize~~',
	'UI:Button:ResetImage' => 'Recover the previous image~~',
	'UI:Button:RemoveImage' => 'Remove the image~~',
	'UI:Button:UploadImage' => 'Upload an image from the disk~~',
	'UI:UploadNotSupportedInThisMode' => 'The modification of images or files is not supported in this mode.~~',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Minimize / Expand~~',
	'UI:Search:AutoSubmit:DisabledHint' => 'Auto submit has been disabled for this class~~',
	'UI:Search:Obsolescence:DisabledHint' => 'Based on your preferences, obsolete data are hidden~~',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Add some criterion on the search box or click the search button to view the objects.~~',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Add new criteria~~',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recently used~~',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Most popular~~',
	'UI:Search:AddCriteria:List:Others:Title' => 'Others~~',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'None yet.~~',

	// - Criteria header actions
	'UI:Search:Criteria:Toggle' => 'Minimize / Expand~~',
	'UI:Search:Criteria:Remove' => 'Remove~~',
	'UI:Search:Criteria:Locked' => 'Locked~~',

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
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Equals~~',// => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Greater~~',// => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Greater / equals~~',// > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Less~~',// => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Less / equals~~',// > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Different~~',// => '≠',
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

	'UI:StateChanged' => 'State changed~~',
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
	'UI:Newsroom:XNewMessage' => '%1$s new message(s)~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));


Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:DataSources' => 'Synchronization Data Sources~~',
	'Menu:DataSources+' => 'All Synchronization Data Sources~~',
	'Menu:WelcomeMenu' => 'Hoşgeldiniz',
	'Menu:WelcomeMenu+' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz',
	'Menu:WelcomeMenuPage' => 'Hoşgeldiniz',
	'Menu:WelcomeMenuPage+' => ITOP_APPLICATION_SHORT.'\'a Hoşgeldiniz',
	'Menu:AdminTools' => 'Yönetim Araçları',
	'Menu:AdminTools+' => 'Yönetim Araçları',
	'Menu:AdminTools?' => 'Yönetici profiline izin verilen araçlar',
	'Menu:DataModelMenu' => 'Veri Modeli',
	'Menu:DataModelMenu+' => 'Veri Modeli Özeti',
	'Menu:ExportMenu' => 'Dışarı ver',
	'Menu:ExportMenu+' => 'Sorgu sonucunu HTML, CSV veya XML olarak dışarı aktar',
	'Menu:NotificationsMenu' => 'Uyarılar',
	'Menu:NotificationsMenu+' => 'Uyarıların yapılandırılması',
	'Menu:AuditCategories' => 'Denetleme Kategorileri',
	'Menu:AuditCategories+' => 'Denetleme Kategorileri',
	'Menu:Notifications:Title' => 'Denetleme Kategorileri',
	'Menu:RunQueriesMenu' => 'Sorgu çalıştır',
	'Menu:RunQueriesMenu+' => 'Sorgu çalıştır',
	'Menu:QueryMenu' => 'Query phrasebook~~',
	'Menu:QueryMenu+' => 'Query phrasebook~~',
	'Menu:UniversalSearchMenu' => 'Genel sorgu',
	'Menu:UniversalSearchMenu+' => 'Herhangi bir arama...',
	'Menu:UserManagementMenu' => 'Kullanıcı Yönetimi',
	'Menu:UserManagementMenu+' => 'Kullanıcı Yönetimi',
	'Menu:ProfilesMenu' => 'Profiller',
	'Menu:ProfilesMenu+' => 'Profiller',
	'Menu:ProfilesMenu:Title' => 'Profiller',
	'Menu:UserAccountsMenu' => 'Kullanıcı Hesapları',
	'Menu:UserAccountsMenu+' => 'Kullanıcı Hesapları',
	'Menu:UserAccountsMenu:Title' => 'Kullanıcı Hesapları',
	'Menu:MyShortcuts' => 'My Shortcuts~~',
	'Menu:UserManagement' => 'User Management~~',
	'Menu:Queries' => 'Queries~~',
	'Menu:ConfigurationTools' => 'Configuration~~',
));

// Additional language entries not present in English dict
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
 'UI:Toggle:StandardDashboard' => 'Standard~~',
 'UI:Toggle:CustomDashboard' => 'Custom~~',
 'UI:Display_X_ItemsPerPage' => 'Display %1$s items per page~~',
 'UI:Dashboard:Edit' => 'Edit This Page...~~',
 'UI:Dashboard:Revert' => 'Revert To Original Version...~~',
));
