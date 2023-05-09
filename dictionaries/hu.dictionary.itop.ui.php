<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:AuditCategory' => 'Audit kategória',
    'Class:AuditCategory+' => '',
    'Class:AuditCategory/Attribute:name' => 'Kategórianév',
    'Class:AuditCategory/Attribute:name+' => '',
    'Class:AuditCategory/Attribute:description' => 'Leírás',
    'Class:AuditCategory/Attribute:description+' => '',
    'Class:AuditCategory/Attribute:definition_set' => 'Definíciókészlet',
    'Class:AuditCategory/Attribute:definition_set+' => '',
    'Class:AuditCategory/Attribute:rules_list' => 'Auditszabályok',
    'Class:AuditCategory/Attribute:rules_list+' => 'Audit rules for this category~~',
));

//
// Class: AuditRule
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:AuditRule' => 'Auditszabály',
    'Class:AuditRule+' => '',
    'Class:AuditRule/Attribute:name' => 'Szabály név',
    'Class:AuditRule/Attribute:name+' => '',
    'Class:AuditRule/Attribute:description' => 'Leírás',
    'Class:AuditRule/Attribute:description+' => '',
    'Class:TagSetFieldData/Attribute:finalclass' => 'Címkeosztály',
    'Class:TagSetFieldData/Attribute:obj_class' => 'Objektumosztály',
    'Class:TagSetFieldData/Attribute:obj_attcode' => 'Mezőkód',
    'Class:AuditRule/Attribute:query' => 'Lekérdezés',
    'Class:AuditRule/Attribute:query+' => '',
    'Class:AuditRule/Attribute:valid_flag' => 'Érvényes objektum?',
    'Class:AuditRule/Attribute:valid_flag+' => '',
    'Class:AuditRule/Attribute:valid_flag/Value:true' => 'igaz',
    'Class:AuditRule/Attribute:valid_flag/Value:true+' => '',
    'Class:AuditRule/Attribute:valid_flag/Value:false' => 'hamis',
    'Class:AuditRule/Attribute:valid_flag/Value:false+' => '',
    'Class:AuditRule/Attribute:category_id' => 'Kategória',
    'Class:AuditRule/Attribute:category_id+' => '',
    'Class:AuditRule/Attribute:category_name' => 'Kategórianév',
    'Class:AuditRule/Attribute:category_name+' => '',
));

//
// Class: QueryOQL
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:Query' => 'Lekérdezés',
    'Class:Query+' => 'A query is a data set defined in a dynamic way~~',
    'Class:Query/Attribute:name' => 'Név',
    'Class:Query/Attribute:name+' => 'Identifies the query~~',
    'Class:Query/Attribute:description' => 'Leírás',
    'Class:Query/Attribute:description+' => 'Long description for the query (purpose, usage, etc.)~~',
    'Class:Query/Attribute:is_template' => 'OQL mező sablonok',
    'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications~~',
    'Class:Query/Attribute:is_template/Value:yes' => 'Igen',
    'Class:Query/Attribute:is_template/Value:no' => 'Nem',
    'Class:QueryOQL/Attribute:fields' => 'Mezők',
    'Class:QueryOQL/Attribute:fields+' => 'Comma separated list of attributes (or alias.attribute) to export~~',
    'Class:QueryOQL' => 'OQL lekérdezés',
    'Class:QueryOQL+' => 'A query based on the Object Query Language~~',
    'Class:QueryOQL/Attribute:oql' => 'Kifejezés',
    'Class:QueryOQL/Attribute:oql+' => 'OQL kifejezés',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:User' => 'Felhasználó',
    'Class:User+' => '',
    'Class:User/Attribute:finalclass' => 'Felhasználó típus',
    'Class:User/Attribute:finalclass+' => '',
    'Class:User/Attribute:contactid' => 'Kapcsolattartó',
    'Class:User/Attribute:contactid+' => '',
    'Class:User/Attribute:org_id' => 'Szervezeti egység',
    'Class:User/Attribute:org_id+' => 'A társított személy szervezeti egysége',
    'Class:User/Attribute:last_name' => 'Családnév',
    'Class:User/Attribute:last_name+' => '',
    'Class:User/Attribute:first_name' => 'Keresztnév',
    'Class:User/Attribute:first_name+' => '',
    'Class:User/Attribute:email' => 'Email cím',
    'Class:User/Attribute:email+' => '',
    'Class:User/Attribute:login' => 'Felhasználónév',
    'Class:User/Attribute:login+' => '',
    'Class:User/Attribute:language' => 'Nyelv',
    'Class:User/Attribute:language+' => '',
    'Class:User/Attribute:language/Value:EN US' => 'Angol',
    'Class:User/Attribute:language/Value:EN US+' => '',
    'Class:User/Attribute:language/Value:FR FR' => 'Francia',
    'Class:User/Attribute:language/Value:FR FR+' => '',
    'Class:User/Attribute:profile_list' => 'Profil',
    'Class:User/Attribute:profile_list+' => 'Roles, granting rights for that person~~',
    'Class:User/Attribute:allowed_org_list' => 'Engedélyezett szervezeti egységek',
    'Class:User/Attribute:allowed_org_list+' => 'The end user is allowed to see data belonging to the following organizations. If no organization is specified, there is no restriction.~~',
    'Class:User/Attribute:status' => 'Állapot',
    'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
    'Class:User/Attribute:status/Value:enabled' => 'Engedélyezett',
    'Class:User/Attribute:status/Value:disabled' => 'Letiltott',

    'Class:User/Error:LoginMustBeUnique' => 'A felhasználónévnek egyedinek kell lennie - %1s már létezik.',
    'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Legalább egy profilt a felhasználóhoz kell rendelni.',
    'Class:User/Error:ProfileNotAllowed' => 'A %1$s profil nem adható hozzá, le lesz tiltva',
    'Class:User/Error:StatusChangeIsNotAllowed' => 'A saját felhasználó státuszának cseréje nem engedélyezett',
    'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Az engedélyezett szervezeteknek tartalmazniuk kell a felhasználói szervezetet',
    'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'A profilok jelenlegi listája nem ad elegendő hozzáférési jogot (a felhasználók már nem módosíthatók)',
    'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'A felhasználóhoz legalább egy szervezeti egységet hozzá kell rendelni',
    'Class:User/Error:OrganizationNotAllowed' => 'A szervezeti egység nem engedélyezett.',
    'Class:User/Error:UserOrganizationNotAllowed' => 'A felhasználói fiók nem tartozik engedélyezett szervezeti egységhez.',
    'Class:User/Error:PersonIsMandatory' => 'A kapcsolattartó megadása kötelező',
    'Class:UserInternal' => 'Belső felhasználó',
    'Class:UserInternal+' => ''.ITOP_APPLICATION_SHORT.'-ban létrehozott felhasználó',
));

//
// Class: URP_Profiles
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_Profiles' => 'Profil',
    'Class:URP_Profiles+' => '',
    'Class:URP_Profiles/Attribute:name' => 'Profilnév',
    'Class:URP_Profiles/Attribute:name+' => '',
    'Class:URP_Profiles/Attribute:description' => 'Leírás',
    'Class:URP_Profiles/Attribute:description+' => '',
    'Class:URP_Profiles/Attribute:user_list' => 'Felhasználók',
    'Class:URP_Profiles/Attribute:user_list+' => '',
));

//
// Class: URP_Dimensions
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_Dimensions' => 'Dimenzió',
    'Class:URP_Dimensions+' => '',
    'Class:URP_Dimensions/Attribute:name' => 'Dimenziónév',
    'Class:URP_Dimensions/Attribute:name+' => '',
    'Class:URP_Dimensions/Attribute:description' => 'Leírás',
    'Class:URP_Dimensions/Attribute:description+' => '',
    'Class:URP_Dimensions/Attribute:type' => 'Típus',
    'Class:URP_Dimensions/Attribute:type+' => '',
));

//
// Class: URP_UserProfile
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_UserProfile' => 'Profilhoz rendelt felhasználók',
    'Class:URP_UserProfile+' => '',
    'Class:URP_UserProfile/Name' => 'Kapcsolat %1$s és %2$s között',
    'Class:URP_UserProfile/Attribute:userid' => 'Felhasználó',
    'Class:URP_UserProfile/Attribute:userid+' => '',
    'Class:URP_UserProfile/Attribute:userlogin' => 'Felhasználónév',
    'Class:URP_UserProfile/Attribute:userlogin+' => '',
    'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
    'Class:URP_UserProfile/Attribute:profileid+' => '',
    'Class:URP_UserProfile/Attribute:profile' => 'Profilnév',
    'Class:URP_UserProfile/Attribute:profile+' => '',
    'Class:URP_UserProfile/Attribute:reason' => 'Indoklás',
    'Class:URP_UserProfile/Attribute:reason+' => '',
));

//
// Class: URP_UserOrg
//


Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_UserOrg' => 'Felhasználó szervezeti tagsága',
    'Class:URP_UserOrg+' => '',
    'Class:URP_UserOrg/Name' => 'Kapcsolat %1$s és %2$s között',
    'Class:URP_UserOrg/Attribute:userid' => 'Felhasználó',
    'Class:URP_UserOrg/Attribute:userid+' => '',
    'Class:URP_UserOrg/Attribute:userlogin' => 'Felhasználónév',
    'Class:URP_UserOrg/Attribute:userlogin+' => '',
    'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Szervezeti egység',
    'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
    'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Szervezeti egység név',
    'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
    'Class:URP_UserOrg/Attribute:reason' => 'Indoklás',
    'Class:URP_UserOrg/Attribute:reason+' => '',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_ProfileProjection' => 'profile_projection',
    'Class:URP_ProfileProjection+' => '',
    'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimenzió',
    'Class:URP_ProfileProjection/Attribute:dimensionid+' => '',
    'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimenziónév',
    'Class:URP_ProfileProjection/Attribute:dimension+' => '',
    'Class:URP_ProfileProjection/Attribute:profileid' => 'Profil',
    'Class:URP_ProfileProjection/Attribute:profileid+' => '',
    'Class:URP_ProfileProjection/Attribute:profile' => 'Profilnév',
    'Class:URP_ProfileProjection/Attribute:profile+' => '',
    'Class:URP_ProfileProjection/Attribute:value' => 'Érték',
    'Class:URP_ProfileProjection/Attribute:value+' => '',
    'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribútum',
    'Class:URP_ProfileProjection/Attribute:attribute+' => '',
));

//
// Class: URP_ClassProjection
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_ClassProjection' => 'class_projection',
    'Class:URP_ClassProjection+' => '',
    'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimenzió',
    'Class:URP_ClassProjection/Attribute:dimensionid+' => '',
    'Class:URP_ClassProjection/Attribute:dimension' => 'Dimenziónév',
    'Class:URP_ClassProjection/Attribute:dimension+' => '',
    'Class:URP_ClassProjection/Attribute:class' => 'Osztály',
    'Class:URP_ClassProjection/Attribute:class+' => '',
    'Class:URP_ClassProjection/Attribute:value' => 'Érték',
    'Class:URP_ClassProjection/Attribute:value+' => '',
    'Class:URP_ClassProjection/Attribute:attribute' => 'Attribútum',
    'Class:URP_ClassProjection/Attribute:attribute+' => '',
));

//
// Class: URP_ActionGrant
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_ActionGrant' => 'action_permission',
    'Class:URP_ActionGrant+' => '',
    'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
    'Class:URP_ActionGrant/Attribute:profileid+' => '',
    'Class:URP_ActionGrant/Attribute:profile' => 'Profilnév',
    'Class:URP_ActionGrant/Attribute:profile+' => '',
    'Class:URP_ActionGrant/Attribute:class' => 'Osztály',
    'Class:URP_ActionGrant/Attribute:class+' => '',
    'Class:URP_ActionGrant/Attribute:permission' => 'Jogosultság',
    'Class:URP_ActionGrant/Attribute:permission+' => '',
    'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Igen',
    'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '',
    'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nem',
    'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '',
    'Class:URP_ActionGrant/Attribute:action' => 'Művelet',
    'Class:URP_ActionGrant/Attribute:action+' => '',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_StimulusGrant' => 'stimulus_permission',
    'Class:URP_StimulusGrant+' => '',
    'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
    'Class:URP_StimulusGrant/Attribute:profileid+' => '',
    'Class:URP_StimulusGrant/Attribute:profile' => 'Profilnév',
    'Class:URP_StimulusGrant/Attribute:profile+' => '',
    'Class:URP_StimulusGrant/Attribute:class' => 'Osztály',
    'Class:URP_StimulusGrant/Attribute:class+' => '',
    'Class:URP_StimulusGrant/Attribute:permission' => 'Jogosultság',
    'Class:URP_StimulusGrant/Attribute:permission+' => '',
    'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Igen',
    'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => '',
    'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nem',
    'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => '',
    'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
    'Class:URP_StimulusGrant/Attribute:stimulus+' => '',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:URP_AttributeGrant' => 'attribute_permission',
    'Class:URP_AttributeGrant+' => '',
    'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Művelet engedély',
    'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '',
    'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribútum',
    'Class:URP_AttributeGrant/Attribute:attcode+' => '',
));

//
// Class: UserDashboard
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Class:UserDashboard' => 'Felhasználói műszerfal',
    'Class:UserDashboard+' => '~~',
    'Class:UserDashboard/Attribute:user_id' => 'Felhasználó',
    'Class:UserDashboard/Attribute:user_id+' => '~~',
    'Class:UserDashboard/Attribute:menu_code' => 'Menükód',
    'Class:UserDashboard/Attribute:menu_code+' => '~~',
    'Class:UserDashboard/Attribute:contents' => 'Tartalom',
    'Class:UserDashboard/Attribute:contents+' => '~~',
));

//
// Expression to Natural language
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Expression:Unit:Short:DAY' => 'n',
    'Expression:Unit:Short:WEEK' => 'w~~',
    'Expression:Unit:Short:MONTH' => 'h',
    'Expression:Unit:Short:YEAR' => 'é',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'BooleanLabel:yes' => 'Igen',
    'BooleanLabel:no' => 'Nem',
    'UI:Login:Title' => ITOP_APPLICATION_SHORT.' bejelentkezés',
    'Menu:WelcomeMenu' => 'Főoldal',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:WelcomeMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:WelcomeMenuPage' => 'Áttekintő',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:WelcomeMenuPage+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'UI:WelcomeMenu:Title' => 'Üdvözli az '.ITOP_APPLICATION_SHORT,

    'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' egy teljeskörű, OpenSource, IT üzemeltetés támogató portál.</p>
<ul>A következőket tartalmazza:
<li>Teljeskörű CMDB (Konfigurációkezelés adatbázis) az IT eszközök dokumentálására és verzió kezelésére.</li>
<li>Incidenskezelés modul az összes IT-hez kapcsolódó kérelem életciklusának követésére.</li>
<li>Változáskezelés modul az IT infrastruktúra változásainak nyomonkövetésére és tervezésére.</li>
<li>Ismert hibák adatbázisa az incidens kezelés sebességének növelésére.</li>
<li>Üzemszünet modul az összes tervezett leállás tervezésére és azzal kapcsolatos kommunikáció támogatására.</li>
<li>Műszerfalak az IT infrastruktúra pillanatnyi állapotának gyors áttekintésére.</li>
</ul>
<p>Mindegyik modul önállóan telepíthető és használható.</p>',

    'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' egy szolgáltatás-orientált megoldás, amely segít az IT szakembereknek több ügyfél és szervezet egyidejű menedzselését.
<ul>az iTop az üzleti folyamatok javításához egy hatékony eszköz, mert:
<li>javítja az IT menedzsment hatékonyságát</li> 
<li>növeli IT üzemeltetés teljesítményét</li> 
<li>növeli az ügyfél elégedettséget és a vezetők számára lehetőséget ad az üzleti teljesítmény növelésére</li>
</ul>
</p>
<p>Az iTop teljesn nyílt ezért, egyszerűen integrálható a jelenlegi IT infrastruktúrába</p>
<p>
<ul>Az üzemeltetési portál bevezetésével:
<li>jobban menedzselhető az egyre komplexebb IT infrastruktúra</li>
<li>az ITIL folyamatok bevezetésre kerülnek</li>
<li>hatékonyan tudja kezelni az egyik legfontosabb IT eszközt, a dokumentációt.</li>
</ul>
</p>',
    'UI:WelcomeMenu:Text'=> '<div>Gratulálunk, megérkezett a '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.' oldalára!</div>

<div>Ez a verzió egy vadonatúj, modern és könnyen hozzáférhető backoffice dizájnnal rendelkezik..</div>

<div>Megtartottuk az '.ITOP_APPLICATION.' alapvető funkcióit és modernizáltuk őket, hogy megszerettessük önnel.
Reméljük, hogy ezt a verziót ugyanúgy kedvelni fogja, mint ahogy mi élveztük a megtervezéssét és létrehozását.</div>

<div>Szabja testre az '.ITOP_APPLICATION.' beállításait a kényelmesebb használathoz.</div>',
    'UI:WelcomeMenu:AllOpenRequests' => 'Összes nyitott kérelem: %1$d',
    'UI:WelcomeMenu:MyCalls' => 'Saját kérelmek',
    'UI:WelcomeMenu:OpenIncidents' => 'Nyitott incidensek: %1$d',
    'UI:WelcomeMenu:AllConfigItems' => 'Konfigurációs elemek: %1$d',
    'UI:WelcomeMenu:MyIncidents' => 'Hozzám rendelt incidensek',
    'UI:AllOrganizations' => 'Összes Szervezeti egység',
    'UI:YourSearch' => 'Saját keresések',
    'UI:LoggedAsMessage' => 'Bejelentkezve %1$s (%2$s)',
    'UI:LoggedAsMessage+Admin' => 'Bejelentkezve %1$s (%2$s, Administrator)',
    'UI:Button:Logoff' => 'Kijelentkezés',
    'UI:Button:GlobalSearch' => 'Globális keresés',
    'UI:Button:Search' => ' Keresés',
    'UI:Button:Clear' => ' Törlés',
    'UI:Button:SearchInHierarchy' => 'Keresés a hierarchiában',
    'UI:Button:Query' => ' Lekérdezés',
    'UI:Button:Ok' => 'OK',
    'UI:Button:Save' => 'Mentés',
    'UI:Button:SaveAnd' => 'Mentés és %1$s',
    'UI:Button:Cancel' => 'Mégse',
    'UI:Button:Close' => 'Bezárás',
    'UI:Button:Apply' => 'Alkalmazás',
    'UI:Button:Send' => 'Küldés',
    'UI:Button:SendAnd' => 'Küldés és %1$s',
    'UI:Button:Back' => ' << Vissza',
    'UI:Button:Restart' => ' |<< Újraindítás',
    'UI:Button:Next' => ' Következő >>',
    'UI:Button:Finish' => ' Befejezés',
    'UI:Button:DoImport' => ' Importálás indítása',
    'UI:Button:Done' => ' Kész',
    'UI:Button:SimulateImport' => ' Importálás szimulálása',
    'UI:Button:Test' => 'Teszt!',
    'UI:Button:Evaluate' => ' Kiértékelés',
    'UI:Button:Evaluate:Title' => ' Értékelés (Ctrl+Enter)',
    'UI:Button:AddObject' => ' Hozzáadás...',
    'UI:Button:BrowseObjects' => ' Böngészés...',
    'UI:Button:Add' => ' Hozzáadás ',
    'UI:Button:AddToList' => ' << Hozzáadás ',
    'UI:Button:RemoveFromList' => ' Eltávolítás >> ',
    'UI:Button:FilterList' => ' Szűrés... ',
    'UI:Button:Create' => ' Létrehozás',
    'UI:Button:Delete' => ' Törlés',
    'UI:Button:Rename' => ' Átnevezés... ',
    'UI:Button:ChangePassword' => ' Jelszó változtatás',
    'UI:Button:ResetPassword' => ' Jelszó visszaállítás',
    'UI:Button:Insert' => 'Beillesztés',
    'UI:Button:More' => 'Több',
    'UI:Button:Less' => 'Kevesebb',
    'UI:Button:Wait' => 'Várjon, amíg a mezők frissülnek',
    'UI:Treeview:CollapseAll' => 'Összes összecsukása',
    'UI:Treeview:ExpandAll' => 'Összes lenyitása',
    'UI:UserPref:DoNotShowAgain' => 'Ne mutassa újra',
    'UI:InputFile:NoFileSelected' => 'Nincs fájl kiválasztva',
    'UI:InputFile:SelectFile' => 'Válasszon egy fájlt',

    'UI:SearchToggle' => 'Keresés',
    'UI:ClickToCreateNew' => 'Új %1$s létrehozása',
    'UI:SearchFor_Class' => '%1$s objektumok keresése',
    'UI:NoObjectToDisplay' => 'Nincs megjeleníthető objektum',
    'UI:Error:SaveFailed' => 'Az objektum nem menthető le :',
    'UI:Error:MandatoryTemplateParameter_object_id' => 'object_id paraméter kötelező a link_attr megadásánál. Ellenőrizze a sablon definíciót.',
    'UI:Error:MandatoryTemplateParameter_target_attr' => 'target_attr paraméter kötelező a link_attr megadásánál. Ellenőrizze a sablon definíciót.',
    'UI:Error:MandatoryTemplateParameter_group_by' => 'group_by paraméter kötelező. Ellenőrizze a sablon definíciót.',
    'UI:Error:InvalidGroupByFields' => 'Csoportosításnál használt érvénytelen mezők: %1$s.',
    'UI:Error:UnsupportedStyleOfBlock' => 'Hiba: nem támogatott stílus tömb: %1$s.',
    'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Nem megfelelő kapcsolat meghatározás: kapcsolódó osztály: %1$s nem külső kulcs a %2$s osztályban',
    'UI:Error:Object_Class_Id_NotFound' => 'Objektum: %1$s:%2$d nem található.',
    'UI:Error:WizardCircularReferenceInDependencies' => 'Hiba: Körkörös hivatkozás az egymásra mutató mezők között. Ellenőrizze az adatmodellt.',
    'UI:Error:UploadedFileTooBig' => 'A feltöltendő fájl túl nagy. (Maximális méret: %1$s). Ellenőrizze a PHP konfigurációs fájlban az upload_max_filesize és post_max_size beállításokat.',
    'UI:Error:UploadedFileTruncated.' => 'Feltöltött fájl átméretezett!',
    'UI:Error:NoTmpDir' => 'Az átmeneti könyvtár nem meghatározott.',
    'UI:Error:CannotWriteToTmp_Dir' => 'Az átmeneti fájl nem írható. upload_tmp_dir = %1$s.',
    'UI:Error:UploadStoppedByExtension_FileName' => 'A feltöltés megállt a fájl kiterjesztése miatt. (Eredeti fájl név = %1$s).',
    'UI:Error:UploadFailedUnknownCause_Code' => 'Fájl feltöltés sikertelen ismeretlen hiba miatt. (Hibakód = %1$s).',

    'UI:Error:1ParametersMissing' => 'Hiba: a következő paramétert meg kell adni ennél a műveletnél: %1$s.',
    'UI:Error:2ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s és %2$s.',
    'UI:Error:3ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s, %2$s és %3$s.',
    'UI:Error:4ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s, %2$s, %3$s és %4$s.',
    'UI:Error:IncorrectOQLQuery_Message' => 'Hiba: nem megfelelő OQL lekérdezés: %1$',
    'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Hiba történt a lekérdezés futtatása közben: %1$s',
    'UI:Error:ObjectAlreadyUpdated' => 'Hiba: az objketum már korábban módosításra került.',
    'UI:Error:ObjectCannotBeUpdated' => 'Hiba: az objektum nem frissíthető.',
    'UI:Error:ObjectsAlreadyDeleted' => 'Hiba: az objektum már korában törlésre került!',
    'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Az osztály objektumainak tömeges törlése nem engedélyezett %1$s',
    'UI:Error:DeleteNotAllowedOn_Class' => 'Az osztály objektumainak törlése nem engedélyezett %1$s',
    'UI:Error:ReadNotAllowedOn_Class' => ' Nincs engedélye hogy a %1$s osztály objektumait lássa',
    'UI:Error:BulkModifyNotAllowedOn_Class' => 'Az osztály objektumainak tömeges frissítése nem engedélyezett %1$s',
    'UI:Error:ObjectAlreadyCloned' => 'Hiba: az objektum már klónozott!',
    'UI:Error:ObjectAlreadyCreated' => 'Hiba: az objektum már létrehozva!',
    'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Hiba: érvénytelen stimulus %1$s a következő objektum %2$s következő állapotában %3$s.',
    'UI:Error:InvalidDashboardFile' => 'Hiba: Érvénytelen műszerfal fájl',
    'UI:Error:InvalidDashboard' => 'Hiba: Érvénytelen műszerfal',
    'UI:Error:MaintenanceMode' => 'Az alkalmazás jelenleg karbantartás alatt van',
    'UI:Error:MaintenanceTitle' => 'Karbantartás',
    'UI:Error:InvalidToken' => 'Hiba: a kért művelet már végrehajtásra került (CSRF token nem található)',

    'UI:Error:SMTP:UnknownVendor' => 'A %1$s OAuth SMTP szolgáltató nem létezik  (email_transport_smtp.oauth.provider)',

    'UI:GroupBy:Count' => 'Mennyiség',
    'UI:GroupBy:Count+' => '',
    'UI:CountOfObjects' => '%1$d darab objektum felel meg a kritériumoknak.',
    'UI_CountOfObjectsShort' => '%1$d objektum.',
    'UI:NoObject_Class_ToDisplay' => 'Nincs megjeleníthető %1$s',
    'UI:History:LastModified_On_By' => 'Utolsó módosítást a következő objektumon %1$s %2$s végezte.',
    'UI:HistoryTab' => 'Előzmény',
    'UI:NotificationsTab' => 'Értesítés',
    'UI:History:BulkImports' => 'Előzmények',
    'UI:History:BulkImports+' => '',
    'UI:History:BulkImportDetails' => 'CSV importálás végrehajtva: %1$s (%2$s által)',
    'UI:History:Date' => 'Dátum',
    'UI:History:Date+' => '',
    'UI:History:User' => 'Felhasználó',
    'UI:History:User+' => '',
    'UI:History:Changes' => 'Változások',
    'UI:History:Changes+' => '',
    'UI:History:StatsCreations' => 'Létrehozva',
    'UI:History:StatsCreations+' => '',
    'UI:History:StatsModifs' => 'Módosítva',
    'UI:History:StatsModifs+' => '',
    'UI:History:StatsDeletes' => 'Törölve',
    'UI:History:StatsDeletes+' => '',
    'UI:Loading' => 'Betöltés...',
    'UI:Menu:Actions' => 'Műveletek',
    'UI:Menu:OtherActions' => 'Egyéb műveletek',
    'UI:Menu:Transitions' => 'Átvezetés',
    'UI:Menu:OtherTransitions' => 'Más átvezetések',
    'UI:Menu:New' => 'Új...',
    'UI:Menu:Add' => 'Hozzáadás...',
    'UI:Menu:Manage' => 'Kezelés...',
    'UI:Menu:EMail' => 'Email',
    'UI:Menu:CSVExport' => 'CSV exportálás...',
    'UI:Menu:Modify' => 'Módosítás...',
    'UI:Menu:Delete' => 'Törlés...',
    'UI:Menu:BulkDelete' => 'Törlés...',
    'UI:UndefinedObject' => 'Nem meghatározott',
    'UI:Document:OpenInNewWindow:Download' => 'Megnyitás új ablakban: %1$s, Letöltés: %2$s',
    'UI:SplitDateTime-Date' => 'Dátum',
    'UI:SplitDateTime-Time' => 'Idő',
    'UI:TruncatedResults' => '%1$d objektum megjelenítve %2$d példányból',
    'UI:DisplayAll' => 'Összes megjelenítése',
    'UI:CollapseList' => 'Elemek',
    'UI:CountOfResults' => '%1$d objektum',
    'UI:ChangesLogTitle' => 'Változásnapló (%1$d):',
    'UI:EmptyChangesLogTitle' => 'Változásnapló üres',
    'UI:SearchFor_Class_Objects' => 'Keresés %1$s objektumra',
    'UI:OQLQueryBuilderTitle' => 'OQL lekérdezés szerkesztő',
    'UI:OQLQueryTab' => 'OQL lekérdezés',
    'UI:SimpleSearchTab' => 'Egyszerű keresés',
    'UI:Details+' => '',
    'UI:SearchValue:Any' => '* Bármely *',
    'UI:SearchValue:Mixed' => '* Kevert *',
    'UI:SearchValue:NbSelected' => '# kiválasztva',
    'UI:SearchValue:CheckAll' => 'Összes bejelölése',
    'UI:SearchValue:UncheckAll' => 'Bejelölés megszüntetése',
    'UI:SelectOne' => '-- válasszon ki egyet --',
    'UI:Login:Welcome' => 'Üdvözli az '.ITOP_APPLICATION_SHORT.'!',
    'UI:Login:IncorrectLoginPassword' => 'Nem megfelelő bejelentkezési név/jelszó, kérjük próbálja újra.',
    'UI:Login:IdentifyYourself' => 'Folytatás előtt azonosítsa magát',
    'UI:Login:UserNamePrompt' => 'Felhasználónév',
    'UI:Login:PasswordPrompt' => 'Jelszó',
    'UI:Login:ForgotPwd' => 'Elfelejtette a jelszavát?',
    'UI:Login:ForgotPwdForm' => 'Elfelejtett jelszó',
    'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' küldhet Önnek egy emailt, amelyben utasításokat talál a fiókja visszaállításához.',
    'UI:Login:ResetPassword' => 'Küldje most!',
    'UI:Login:ResetPwdFailed' => 'Sikertelen email küldés: %1$s',
    'UI:Login:SeparatorOr' => 'Vagy',

    'UI:ResetPwd-Error-WrongLogin' => '%1$s nem érvényes fiók',
    'UI:ResetPwd-Error-NotPossible' => 'a külső fiókok jelszava itt nem állítható vissza.',
    'UI:ResetPwd-Error-FixedPwd' => 'a fiók nem teszi lehetővé a jelszó visszaállítását.',
    'UI:ResetPwd-Error-NoContact' => 'a fiók nem személyhez tartozik',
    'UI:ResetPwd-Error-NoEmailAtt' => 'a fiók nem olyan személyhez tartozik amelynek van email címe. Keresse a rendszergazdát.',
    'UI:ResetPwd-Error-NoEmail' => 'hiányzik az email cím. Keresse a rendszergazdát.',
    'UI:ResetPwd-Error-Send' => 'email továbbítási hiba. Keresse a rendszergazdát',
    'UI:ResetPwd-EmailSent' => 'Kérjük, ellenőrizze az email postafiókját, és kövesse az utasításokat. Ha nem kap emailt, kérjük, ellenőrizze a beírt bejelentkezési adatait.',
    'UI:ResetPwd-EmailSubject' => 'Állítsa vissza az '.ITOP_APPLICATION_SHORT.' jelszavát',
    'UI:ResetPwd-EmailBody' => '<body><p>Ön vissza szeretné állítani az '.ITOP_APPLICATION_SHORT.' jelszavát.</p><p>Kattintson erre a linkre <a href="%1$s">új jelszó</a></p>.',

    'UI:ResetPwd-Title' => 'Jelszó visszaállítás',
    'UI:ResetPwd-Error-InvalidToken' => 'Sajnáljuk, de vagy már visszaállították a jelszót, vagy már több emailt is kapott. Kérjük, mindenképpen használja a legutolsó kapott emailben megadott linket.',
    'UI:ResetPwd-Error-EnterPassword' => 'Adja meg az új jelszavát a %1$s a fiókjának',
    'UI:ResetPwd-Ready' => 'A jelszó megváltozott',
    'UI:ResetPwd-Login' => 'Jelentkezzen be...',

    'UI:Login:About'                               => 'Névjegy',
    'UI:Login:ChangeYourPassword'                  => 'Jelszó változtatás',
    'UI:Login:OldPasswordPrompt'                   => 'Jelenlegi jelszó',
    'UI:Login:NewPasswordPrompt'                   => 'Új jelszó',
    'UI:Login:RetypeNewPasswordPrompt'             => 'Jelszó megerősítése',
    'UI:Login:IncorrectOldPassword'                => 'Hiba: a jelenlegi jelszó hibás',
    'UI:LogOffMenu'                                => 'Kilépés',
    'UI:LogOff:ThankYou' => 'Köszönjük, hogy az '.ITOP_APPLICATION_SHORT.'-ot használja!',
    'UI:LogOff:ClickHereToLoginAgain'              => 'Ismételt bejelentkezéshez kattintson ide',
    'UI:ChangePwdMenu'                             => 'Jelszó módosítás...',
    'UI:Login:PasswordChanged'                     => 'Jelszó sikeresen beállítva!',
    'UI:AccessRO-All' => ITOP_APPLICATION_SHORT.' csak olvasás módban',
    'UI:AccessRO-Users' => ITOP_APPLICATION_SHORT.' csak olvasás módban a végfelhasználók számára',
    'UI:ApplicationEnvironment'                    => 'Alkalmazáskörnyezet: %1$s',
    'UI:Login:RetypePwdDoesNotMatch'               => 'A jelszavak nem egyeznek!',
    'UI:Button:Login' => 'Belépés az '.ITOP_APPLICATION_SHORT.' alkalmazásba',
    'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' hozzáférés korlátozva. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' rendszergazdához!',
    'UI:Login:Error:AccessAdmin' => 'Adminisztrátori hozzáférés korlátozott. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' rendszergazdához!',
    'UI:Login:Error:WrongOrganizationName'         => 'Ismeretlen szervezeti egység',
    'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Több kapcsolattartónál ugyanez az emailcím',
    'UI:Login:Error:NoValidProfiles'               => 'Érvénytelen a megadott profil',
    'UI:CSVImport:MappingSelectOne'                => '-- válasszon ki egyet --',
    'UI:CSVImport:MappingNotApplicable'            => '-- mező figyelmen kívül hagyása --',
    'UI:CSVImport:NoData'                          => 'Üres mező..., kérem adjon meg adatot!',
    'UI:Title:DataPreview'                         => 'Adatok előnézete',
    'UI:CSVImport:ErrorOnlyOneColumn'              => 'Hiba: Az import fájl egyetlen oszlopot tartalmaz. A megfelelő elválasztó karaktert adta meg?',
    'UI:CSVImport:FieldName'                       => 'Mező %1$d',
    'UI:CSVImport:DataLine1'                       => 'Adatsor 1',
    'UI:CSVImport:DataLine2'                       => 'Adatsor 2',
    'UI:CSVImport:idField'                         => 'id (elsődeges kulcs)',
    'UI:Title:BulkImport' => ITOP_APPLICATION_SHORT.' - tömeges betöltés',
    'UI:Title:BulkImport+'                         => '',
    'UI:Title:BulkSynchro_nbItem_ofClass_class'    => '%2$s osztály %1$d objektumának szinkronizációja',
    'UI:CSVImport:ClassesSelectOne'                => '-- válasszon ki egyet --',
    'UI:CSVImport:ErrorExtendedAttCode'            => 'Belső hiba: %1$s nem megfelelő kód, mert %2$s nem külső kulcsa a %3$s osztálynak',
    'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objektumok változatlanok maradnak.',
    'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objektumok fognak megváltozni.',
    'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objektumok hozzáadásra kerülnek.',
    'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objektumok hibásak lesznek.',
    'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objektumok változatlanak maradtak',
    'UI:CSVImport:ObjectsWereModified' => '%1$d objektumok módosításra kerültek.',
    'UI:CSVImport:ObjectsWereAdded' => '%1$d objektumok hozzáadásra kerültek.',
    'UI:CSVImport:ObjectsHadErrors' => '%1$d objektumok hibásak.',
    'UI:Title:CSVImportStep2' => '2. lépés az 5-ből: CSV adat beállítások',
    'UI:Title:CSVImportStep3' => '3. lépés az 5-ből: Adatok összerendelés',
    'UI:Title:CSVImportStep4' => '4. lépés az 5-ből: Importálás szimuláció',
    'UI:Title:CSVImportStep5' => '5. lépés az 5-ből: Importálás befejezve',
    'UI:CSVImport:LinesNotImported' => 'Sorok, melyek nem lettek betöltve:',
    'UI:CSVImport:LinesNotImported+' => '',
    'UI:CSVImport:SeparatorComma+' => 'vessző',
    'UI:CSVImport:SeparatorSemicolon+' => 'pontosvessző',
    'UI:CSVImport:SeparatorTab+' => 'tabulátor',
    'UI:CSVImport:SeparatorOther' => 'egyéb:',
    'UI:CSVImport:QualifierDoubleQuote+' => 'dupla idézőjel',
    'UI:CSVImport:QualifierSimpleQuote+' => 'szimpla idézőjel',
    'UI:CSVImport:QualifierOther' => 'egyéb:',
    'UI:CSVImport:TreatFirstLineAsHeader' => 'Első sor fejléc információkat tartalmaz (oszlopok nevei)',
    'UI:CSVImport:Skip_N_LinesAtTheBeginning' => '%1$s sor kihagyása a fájl elejéről',
    'UI:CSVImport:CSVDataPreview' => 'CSV adat előnézet',
    'UI:CSVImport:SelectFile' => 'Import fájl kiválasztása:',
    'UI:CSVImport:Tab:LoadFromFile' => 'Betöltés fájlból',
    'UI:CSVImport:Tab:CopyPaste' => 'Adat másolás és beillesztés',
    'UI:CSVImport:Tab:Templates' => 'Sablonok',
    'UI:CSVImport:PasteData' => 'Import adatok beillesztése:',
    'UI:CSVImport:PickClassForTemplate' => 'Letöltendő sablon kiválasztása:',
    'UI:CSVImport:SeparatorCharacter' => 'Elválasztó karakter:',
    'UI:CSVImport:TextQualifierCharacter' => 'Szövegjelölő karakter',
    'UI:CSVImport:CommentsAndHeader' => 'Megjegyzések és fejléc',
    'UI:CSVImport:SelectClass' => 'Importálandó osztály kiválasztása:',
    'UI:CSVImport:AdvancedMode' => 'Haladó mód',
    'UI:CSVImport:AdvancedMode+' => '',
    'UI:CSVImport:SelectAClassFirst' => 'Adat összerendeléshez először válassza ki az osztályt.',
    'UI:CSVImport:HeaderFields' => 'Mező',
    'UI:CSVImport:HeaderMappings' => 'Összerendelés',
    'UI:CSVImport:HeaderSearch' => 'Keresés?',
    'UI:CSVImport:AlertIncompleteMapping' => 'Kérem adja meg az összes mezőre az összerendelési szabályokat.',
    'UI:CSVImport:AlertMultipleMapping' => 'Győződjön meg arról, hogy egy célmező csak egyszer kerül hozzárendelésre.',
    'UI:CSVImport:AlertNoSearchCriteria' => 'Kérem adjon eg legalább egy keresési kritériumot',
    'UI:CSVImport:Encoding' => 'Karakterkódolás',
    'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Univerzális kereső',
    'UI:UniversalSearch:Error' => 'Hiba: %1$s',
    'UI:UniversalSearch:LabelSelectTheClass' => 'Keresendő osztály kiválasztása:',

    'UI:CSVReport-Value-Modified' => 'Módosítva',
    'UI:CSVReport-Value-SetIssue' => 'Érvénytelen érték az attribútumhoz',
    'UI:CSVReport-Value-ChangeIssue' => '%1$s egy érvénytelen érték',
    'UI:CSVReport-Value-NoMatch' => 'Nincs egyezés a %1$s értékhez',
    'UI:CSVReport-Value-Missing' => 'Hiányzó kötelező érték',
    'UI:CSVReport-Value-Ambiguous' => 'Kétértelműség: %1$s objektumban találva',
    'UI:CSVReport-Row-Unchanged' => 'változatlan',
    'UI:CSVReport-Row-Created' => 'létrehozva',
    'UI:CSVReport-Row-Updated' => '%1$d oszlop frissítve',
    'UI:CSVReport-Row-Disappeared' => '%1$d eltűnt, megváltozott oszlop',
    'UI:CSVReport-Row-Issue' => 'Probléma: %1$s',
    'UI:CSVReport-Value-Issue-Null' => 'A nulla nem engedélyezett',
    'UI:CSVReport-Value-Issue-NotFound' => 'Az objektum nincs meg',
    'UI:CSVReport-Value-Issue-FoundMany' => '%1$d egyezés található',
    'UI:CSVReport-Value-Issue-Readonly' => 'A %1$s attribútum csak olvasható (jelenlegi érték: %2$s, várható érték: %3$s)',
    'UI:CSVReport-Value-Issue-Format' => 'A bevitel feldolgozása sikertelen: %1$s',
    'UI:CSVReport-Value-Issue-NoMatch' => 'A %1$s attribútum nem várt értéket kapott: nincs egyezés, ellenőrizze a beírást',
    'UI:CSVReport-Value-Issue-Unknown' => 'A %1$s attribútum nem várt értéket kapott: %2$s',
    'UI:CSVReport-Row-Issue-Inconsistent' => 'Egymással nem konzisztens attribútumok: %1$s',
    'UI:CSVReport-Row-Issue-Attribute' => 'Nem várt attribútum érték(ek)',
    'UI:CSVReport-Row-Issue-MissingExtKey' => 'Nem lehetett létrehozni hiányzó külső kulcs(ok) miatt: %1$s',
    'UI:CSVReport-Row-Issue-DateFormat' => 'hibás dátumformátum',
    'UI:CSVReport-Row-Issue-Reconciliation' => 'nem sikerült összeegyeztetni',
    'UI:CSVReport-Row-Issue-Ambiguous' => 'kétértelmű összeegyeztetés',
    'UI:CSVReport-Row-Issue-Internal' => 'Belső hiba: %1$s, %2$s',

    'UI:CSVReport-Icon-Unchanged' => 'Változatlan',
    'UI:CSVReport-Icon-Modified' => 'Módosított',
    'UI:CSVReport-Icon-Missing' => 'Hiányzó',
    'UI:CSVReport-Object-MissingToUpdate' => 'Hiányzó objektum: frissítve lesz',
    'UI:CSVReport-Object-MissingUpdated' => 'Hiányzó objektum: frissítve',
    'UI:CSVReport-Icon-Created' => 'Létrehozva',
    'UI:CSVReport-Object-ToCreate' => 'Az objektum létre lesz hozva',
    'UI:CSVReport-Object-Created' => 'Az objektum létrehozva',
    'UI:CSVReport-Icon-Error' => 'Hiba',
    'UI:CSVReport-Object-Error' => 'HIBA: %1$s',
    'UI:CSVReport-Object-Ambiguous' => 'KÉTÉRTELMŰ: %1$s',
    'UI:CSVReport-Stats-Errors' => '%1$.0f %% -a a betöltött objektumoknak hibás, ezért figyelmen kívül lesznek hagyva.',
    'UI:CSVReport-Stats-Created' => '%1$.0f %% -a a betöltött objektumoknak létre lesz hozva.',
    'UI:CSVReport-Stats-Modified' => '%1$.0f %% -a a betöltött objektumoknak módosítva lesz.',

    'UI:CSVExport:AdvancedMode' => 'Haladó mód',
    'UI:CSVExport:AdvancedMode+' => 'Haladó módban több oszlopot is hozzáadunk az exportáláshoz: az objektum azonosítóját, a külső kulcsok azonosítóját és egyeztetési attribútumait.',
    'UI:CSVExport:LostChars' => 'Kódolási probléma',
    'UI:CSVExport:LostChars+' => 'A letöltött fájl %1$s kódolású lesz. '.ITOP_APPLICATION_SHORT.' olyan karaktereket észlelt, amelyek nem kompatibilisek ezzel a formátummal. Ezeket a karaktereket vagy helyettesítő karakterekkel helyettesítjük (pl. az ékezetes karakterek elveszítik az ékezetet), vagy elvetjük őket. Az adatokat a webböngészőből másolhatja/beillesztheti. Alternatívaként a rendszergazdához is fordulhat a kódolás megváltoztatásához (lásd a \'csv_file_default_charset\' paramétert).',

    'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',
    'UI:Audit:InteractiveAudit' => 'Interaktív Audit',
    'UI:Audit:HeaderAuditRule' => 'Auditszabály',
    'UI:Audit:HeaderNbObjects' => '# Objektumok',
    'UI:Audit:HeaderNbErrors' => '# Hibák',
    'UI:Audit:PercentageOk' => '% OK',
    'UI:Audit:OqlError' => 'OQL hiba',
    'UI:Audit:Error:ValueNA' => 'n/a',
    'UI:Audit:ErrorIn_Rule' => 'Hiba a szabályban',
    'UI:Audit:ErrorIn_Rule_Reason' => 'OQL hiba a %1$s szabályban: %2$s.',
    'UI:Audit:ErrorIn_Category' => 'Hiba a kategóriában',
    'UI:Audit:ErrorIn_Category_Reason' => 'OQL hiba a %1$s kategóriában: %2$s.',
    'UI:Audit:AuditErrors' => 'Audit hibák',
    'UI:Audit:Dashboard:ObjectsAudited' => 'Auditált objektum',
    'UI:Audit:Dashboard:ObjectsInError' => 'Hibás objektum',
    'UI:Audit:Dashboard:ObjectsValidated' => 'Érvényesített objektum',
    'UI:Audit:AuditCategory:Subtitle' => '%1$s hiba a %2$s - %3$s%% -ból',


    'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - OQL lekérdezés értékelés',
    'UI:RunQuery:QueryExamples' => 'Lekérdezés példák',
    'UI:RunQuery:QueryResults' => 'Lekérdezés eredményei',
    'UI:RunQuery:HeaderPurpose' => 'Cél',
    'UI:RunQuery:HeaderPurpose+' => '',
    'UI:RunQuery:HeaderOQLExpression' => 'OQL kifejezés',
    'UI:RunQuery:HeaderOQLExpression+' => '',
    'UI:RunQuery:ExpressionToEvaluate' => 'Kiértékelendő kifejezés: ',
    'UI:RunQuery:QueryArguments' => 'Lekérdezés argumentumok',
    'UI:RunQuery:MoreInfo' => 'Több információ a lekérdezésről: ',
    'UI:RunQuery:DevelopedQuery' => 'Fejlesztett lekérdezés kiértékelés: ',
    'UI:RunQuery:SerializedFilter' => 'Szerializált szűrő: ',
    'UI:RunQuery:DevelopedOQL' => 'Fejlesztett OQL',
    'UI:RunQuery:DevelopedOQLCount' => 'Fejlesztett OQL a számításhoz',
    'UI:RunQuery:ResultSQLCount' => 'Létrejött SQL',
    'UI:RunQuery:ResultSQL' => 'Létrejött SQL',
    'UI:RunQuery:Error' => 'A lekérdezés futtatása közben a következő hiba jelentkezett',
    'UI:Query:UrlForExcel' => 'URL az MS-Excel web-lekérdezésekhez',
    'UI:Query:UrlV1' => 'A mezők listája nem került meghatározásra. Az <em>export-V2.php</em> oldal nem hívható meg ezen információ nélkül. Ezért az alábbiakban javasolt URL az örökölt oldalra mutat: <em>export.php</em>. Az exportálásnak ez a régi változata a következő korlátozással rendelkezik: az exportált mezők listája a kimeneti formátumtól és a '.ITOP_APPLICATION_SHORT.' adatmodelltől függően változhat. Ha garantálni szeretné, hogy az exportált oszlopok listája hosszú távon stabil maradjon, akkor meg kell adnia a "Fields" attribútum értékét, és használnia kell a <em>export-V2.php</em> oldalt.',
    'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' objektum séma',
    'UI:Schema:TitleForClass' => '%1$s séma',
    'UI:Schema:CategoryMenuItem' => '<b>%1$s</b> kategória',
    'UI:Schema:Relationships' => 'Kapcsolatok',
    'UI:Schema:AbstractClass' => 'Absztrakt osztály: nem példányosítható belőle objektum.',
    'UI:Schema:NonAbstractClass' => 'Nem absztrakt osztály: objektum példányosítható belőle.',
    'UI:Schema:ClassHierarchyTitle' => 'Osztály hierarchia',
    'UI:Schema:AllClasses' => 'Összes osztály',
    'UI:Schema:ExternalKey_To' => 'Külső kulcs %1$s-hoz',
    'UI:Schema:Columns_Description' => 'Oszlopok: <em>%1$s</em>',
    'UI:Schema:Default_Description' => 'Alapértelmezett: %1$s',
    'UI:Schema:NullAllowed' => 'Null érték engedélyezett',
    'UI:Schema:NullNotAllowed' => 'Null érték nem engedélyezett',
    'UI:Schema:Attributes' => 'Attribútumok',
    'UI:Schema:AttributeCode' => 'Attribútum kód',
    'UI:Schema:AttributeCode+' => '',
    'UI:Schema:Label' => 'Címke',
    'UI:Schema:Label+' => '',
    'UI:Schema:Type' => 'Típus',

    'UI:Schema:Type+' => '',
    'UI:Schema:Origin' => 'Származás',
    'UI:Schema:Origin+' => '',
    'UI:Schema:Description' => 'Leírás',
    'UI:Schema:Description+' => '',
    'UI:Schema:AllowedValues' => 'Engedélyezett értékek',
    'UI:Schema:AllowedValues+' => '',
    'UI:Schema:MoreInfo' => 'További információ',
    'UI:Schema:MoreInfo+' => '',
    'UI:Schema:SearchCriteria' => 'Keresési kritériumok',
    'UI:Schema:FilterCode' => 'Szűrőkód',
    'UI:Schema:FilterCode+' => '',
    'UI:Schema:FilterDescription' => 'Leírás',
    'UI:Schema:FilterDescription+' => '',
    'UI:Schema:AvailOperators' => 'Elérhető műveletek',
    'UI:Schema:AvailOperators+' => '',
    'UI:Schema:ChildClasses' => 'Leszármazott osztályok',
    'UI:Schema:ReferencingClasses' => 'Referált osztályok',
    'UI:Schema:RelatedClasses' => 'Kapcsolódó osztályok',
    'UI:Schema:LifeCycle' => 'Életciklus',
    'UI:Schema:Triggers' => 'Eseményindítók',
    'UI:Schema:Relation_Code_Description' => 'Kapcsolat <em>%1$s</em> (%2$s)',
    'UI:Schema:RelationDown_Description' => 'Lenn: %1$s',
    'UI:Schema:RelationUp_Description' => 'Fenn: %1$s',
    'UI:Schema:RelationPropagates' => '%1$s: kiterjesztése %2$d szintre, lekérdezés: %3$s',
    'UI:Schema:RelationDoesNotPropagate' => '%1$s: nincs kiterjesztve (%2$d szintekre), lekérdezés: %3$s',
    'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s hivatkozva %2$s az osztályban %3$s mezőn keresztül',
    'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s hozzácsatolva %2$s-hoz %3$s-n keresztül::<em>%4$s</em>',
    'UI:Schema:Links:1-n' => 'A következő osztályok mutatnak %1$s-ra (1:n kapcsolat):',
    'UI:Schema:Links:n-n' => 'A következő osztályok mutatnak %1$s-ra (n:n kapcsolat):',
    'UI:Schema:Links:All' => 'Összekapcsolódó osztályok grafikonja',
    'UI:Schema:NoLifeCyle' => 'Nincs életciklus rendelve ehhez az osztályhoz.',
    'UI:Schema:LifeCycleTransitions' => 'Átvezetés',
    'UI:Schema:LifeCyleAttributeOptions' => 'Attribútum opciók',
    'UI:Schema:LifeCycleHiddenAttribute' => 'Rejtett',
    'UI:Schema:LifeCycleReadOnlyAttribute' => 'Csak olvasható',
    'UI:Schema:LifeCycleMandatoryAttribute' => 'Kötelező',
    'UI:Schema:LifeCycleAttributeMustChange' => 'Változtatni kell',
    'UI:Schema:LifeCycleAttributeMustPrompt' => 'Felhasználó kéri a változtatását',
    'UI:Schema:LifeCycleEmptyList' => 'Üres lista',
    'UI:Schema:ClassFilter' => 'Osztály:',
    'UI:Schema:DisplayLabel' => 'Megjelenítés:',
    'UI:Schema:DisplaySelector/LabelAndCode' => 'Felirat és kód',
    'UI:Schema:DisplaySelector/Label' => 'Címke',
    'UI:Schema:DisplaySelector/Code' => 'Kód',
    'UI:Schema:Attribute/Filter' => 'Szűrő',
    'UI:Schema:DefaultNullValue' => 'Alapértelmezett null érték : %1$s',
    'UI:LinksWidget:Autocomplete+' => '',
    'UI:Edit:SearchQuery' => 'Válasszon egy előre megadott lekérdezést',
    'UI:Edit:TestQuery' => 'Teszt lekérdezés',
    'UI:Combo:SelectValue' => '--- válasszon értéket ---',
    'UI:Label:SelectedObjects' => 'Kiválasztott objektumok: ',
    'UI:Label:AvailableObjects' => 'Lehetséges objektumok: ',
    'UI:Link_Class_Attributes' => '%1$s attribútumai',
    'UI:SelectAllToggle+' => '',
    'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => '%1$s objektumok hozzáadása %2$s osztályhoz kapcsolással: %3$s',
    'UI:AddObjectsOf_Class_LinkedWith_Class' => '%1$s objektumok hozzáadása %2$s osztályhoz csatolással',
    'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => '%2$s osztályhoz kapcsolt %1$s objektumok kezelése: %3$s',
    'UI:AddLinkedObjectsOf_Class' => 'Hozzáadás %1$s osztályhoz',
    'UI:RemoveLinkedObjectsOf_Class' => 'Kiválasztott objektum eltávolítása',
    'UI:Message:EmptyList:UseAdd' => 'A lista üres, használja a "Hozzáadás..." gombot az elemekre.',
    'UI:Message:EmptyList:UseSearchForm' => 'Használja a keresőmezőt a hozzáadandó objektumok kiválasztásához.',
    'UI:Wizard:FinalStepTitle' => 'Utolsó lépés: megerősítés',
    'UI:Title:DeletionOf_Object' => '%1$s törlése',
    'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '%2$s osztály %1$d objektumának tömeges törlése',
    'UI:Delete:NotAllowedToDelete' => 'Nem engedélyezett az objektum törlése',
    'UI:Delete:NotAllowedToUpdate_Fields' => 'A következő mező módosítása nem engedélyezett: %1$s',
    'UI:Error:ActionNotAllowed' => 'Önnek nem engedélyezett ez a művelet',
    'UI:Error:NotEnoughRightsToDelete' => 'Az objektum nem törölhető, mert a felhasználónak nincs elegendő jogosultsága',
    'UI:Error:CannotDeleteBecause' => 'Az objektum nem törölhető, mert: %1$s',
    'UI:Error:CannotDeleteBecauseOfDepencies' => 'Az objektum nem törölhető, mert néhány hozzá kapcsolódó magasabb prioritású manuális művelet végrehajtásra vár',
    'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Az objektum nem törölhető, mert néhány hozzá kapcsolódó magasabb prioritású manuális művelet végrehajtásra vár',
    'UI:Archive_User_OnBehalfOf_User' => '%1$s felhasználó %2$s nevében',
    'UI:Delete:Deleted' => 'törölve',
    'UI:Delete:AutomaticallyDeleted' => 'automatikusan törölve',
    'UI:Delete:AutomaticResetOf_Fields' => ' következő mezők automatikus újratöltése: %1$s',
    'UI:Delete:CleaningUpRefencesTo_Object' => 'Összes referencia tisztítása %1$s...',
    'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s osztály %1$d objektumára mutató referenciák tisztítása',
    'UI:Delete:Done+' => '',
    'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s törölve.',
    'UI:Delete:ConfirmDeletionOf_Name' => '%1$s törlése',
    'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$s osztály %1$d objektumának törlése',
    'UI:Delete:CannotDeleteBecause' => 'Sikertelenül töröltek: %1$s',
    'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Automatikusan kellett volna törlődniük, de a művelet nem volt végrehajtható: %1$s',
    'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Manuális törlés nem végrehajtható: %1$s',
    'UI:Delete:WillBeDeletedAutomatically' => 'Automatikusan lesznek törölve',
    'UI:Delete:MustBeDeletedManually' => 'Manuálisan törlendők',
    'UI:Delete:CannotUpdateBecause_Issue' => 'Automatikus frissítés sikertelen: %1$s',
    'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Automatikusan lesznek frissítve (reset: %1$s)',
    'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objektumok / kapcsolatok hivatkoznak erre: %2$s',
    'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objektumok / kapcsolatok hivatkoznak törlendő objektumokra',
    'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Adatbázis integritás ellenőrzés szükséges. Néhány hivatkozás megszüntetésre kerül.',
    'UI:Delete:Consequence+' => '',
    'UI:Delete:SorryDeletionNotAllowed' => 'Az objektum törlése nem engedélyezett. Részletes magyarázat a következő sorokban.',
    'UI:Delete:PleaseDoTheManualOperations' => 'Hajtsa végre a következő listában található műveleteket manuálisan az objektum törlésének kéréséhez',
    'UI:Delect:Confirm_Object' => 'Hagyja jóvá a %1$s törlését!',
    'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Hagyja jóvá a %2$s osztály %1$d objektumának törlését!',
    'UI:WelcomeToITop' => 'Üdvözli az '.ITOP_APPLICATION_SHORT,
    'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s részletek',
    'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Hiba',
    'UI:ObjectDoesNotExist' => 'Sajnáljuk, ez az objektum nem létezik (vagy a megtekintése nem engedélyezett a felhasználó számára).',
    'UI:ObjectArchived' => 'Ez az objektum archiválva lett. Kérjük, engedélyezze az archív módot, vagy lépjen kapcsolatba a rendszergazdával.',
    'Tag:Archived' => 'Archivált',
    'Tag:Archived+' => 'Csak archív módban hozzáférhető',
    'Tag:Obsolete' => 'Elavult',
    'Tag:Obsolete+' => 'Kizárva a hatáselemzésből és a keresési eredményekből',
    'Tag:Synchronized' => 'Szinkronizált',
    'ObjectRef:Archived' => 'Archivált',
    'ObjectRef:Obsolete' => 'Elavult',
    'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Keresés eredményei',
    'UI:SearchResultsTitle' => 'Keresés eredményei',
    'UI:SearchResultsTitle+' => 'Szöveges keresés eredményei',
    'UI:Search:NoSearch' => 'Nincs keresés',
    'UI:Search:NeedleTooShort' => 'A %1$s kereső karakterlánc túl rövid. Legalább %2$d karaktert írjon be.',
    'UI:Search:Ongoing' => '%1$s keresése',
    'UI:Search:Enlarge' => 'Keresés kibővítése',
    'UI:FullTextSearchTitle_Text' => '%1$s keresés eredményei:',
    'UI:Search:Count_ObjectsOf_Class_Found' => '%2$s osztály %1$d objektuma',
    'UI:Search:NoObjectFound' => 'Objektum nem található',
    'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s módosítása',
    'UI:ModificationTitle_Class_Object' => '%1$s: <span class=\\"hilite\\">%2$s</span> módosítása',
    'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s klón módosítása',
    'UI:CloneTitle_Class_Object' => '%1$s: <span class=\\"hilite\\">%2$s</span> klón',
    'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - %1$s létrehozása',
    'UI:CreationTitle_Class' => '%1$s létrehozása',
    'UI:SelectTheTypeOf_Class_ToCreate' => 'Válassza ki a létrehozni kívánt %1$s osztály típusát:',
    'UI:Class_Object_NotUpdated' => 'Változás nem történt, %1$s (%2$s) <strong>NEM</strong> lett módosítva.',
    'UI:Class_Object_Updated' => '%1$s (%2$s) frissítve.',
    'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Tömeges törlés',
    'UI:BulkDeleteTitle' => 'Válassza ki a törölni kívánt objektumokat:',
    'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' objektum létrehozva.',
    'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s létrehozva.',
    'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Alkalmazva %1$s objektumon: %2$s.Kinduló állapot: %3$s cél állapot: %4$s.',
    'UI:ObjectCouldNotBeWritten' => 'Az objektum írása sikertelen: %1$s',
    'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Végzetes hiba',
    'UI:SystemIntrusion' => 'Hozzáférés megtagadva. A művelet végrehajtása nem engedélyezett.',
    'UI:FatalErrorMessage' => 'Végzetes hiba, '.ITOP_APPLICATION_SHORT.' nem tudja a műveletet folytatni',
    'UI:Error_Details' => 'Hiba: %1$s.',

    'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' Felhasználókezelés - Profiltervezés',
    'UI:UserManagement:Class' => 'Osztály',
    'UI:UserManagement:Class+' => '',
    'UI:UserManagement:ProjectedObject' => 'Objektum',
    'UI:UserManagement:ProjectedObject+' => '',
    'UI:UserManagement:AnyObject' => '* Bármely *',
    'UI:UserManagement:User' => 'Felhasználó',
    'UI:UserManagement:User+' => '',
    'UI:UserManagement:Action:Read' => 'Olvasás',
    'UI:UserManagement:Action:Read+' => '',
    'UI:UserManagement:Action:Modify' => 'Módosítás',
    'UI:UserManagement:Action:Modify+' => '',
    'UI:UserManagement:Action:Delete' => 'Törlés',
    'UI:UserManagement:Action:Delete+' => '',
    'UI:UserManagement:Action:BulkRead' => 'Tömeges beolvasás (Export)',
    'UI:UserManagement:Action:BulkRead+' => '',
    'UI:UserManagement:Action:BulkModify' => 'Tömeges módosítás',
    'UI:UserManagement:Action:BulkModify+' => '',
    'UI:UserManagement:Action:BulkDelete' => 'Tömeges törlés',
    'UI:UserManagement:Action:BulkDelete+' => '',
    'UI:UserManagement:Action:Stimuli' => 'Stimuli',
    'UI:UserManagement:Action:Stimuli+' => '',
    'UI:UserManagement:Action' => 'Művelet',
    'UI:UserManagement:Action+' => '',
    'UI:UserManagement:TitleActions' => 'Műveletek',
    'UI:UserManagement:Permission' => 'Jogosultság',
    'UI:UserManagement:Permission+' => '',
    'UI:UserManagement:Attributes' => 'Attribútumok',
    'UI:UserManagement:ActionAllowed:Yes' => 'Igen',
    'UI:UserManagement:ActionAllowed:No' => 'Nem',
    'UI:UserManagement:AdminProfile+' => '',
    'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
    'UI:UserManagement:NoLifeCycleApplicable+' => '',
    'UI:UserManagement:GrantMatrix' => 'Jogosultságmátrix',

    'Menu:AdminTools' => 'Adminisztrációs eszközök',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:AdminTools+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:AdminTools?' => 'Az eszközök csak az adminisztrátori profilhoz rendelt felhasználók számára elérhetők.',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:SystemTools' => 'Rendszereszközök',

    'UI:ChangeManagementMenu' => 'Változáskezelés',
    'UI:ChangeManagementMenu+' => '',
    'UI:ChangeManagementMenu:Title' => 'Változások áttekintése',
    'UI-ChangeManagementMenu-ChangesByType' => 'Változások típusonként',
    'UI-ChangeManagementMenu-ChangesByStatus' => 'Változások állapotuk szerint',
    'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Még nem kiosztott változások',

    'UI:ConfigurationManagementMenu' => 'Konfigurációkezelés',
    'UI:ConfigurationManagementMenu+' => '',
    'UI:ConfigurationManagementMenu:Title' => 'Infrastruktúra áttekintő',
    'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastruktúra objektumok típusonként',
    'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastruktúra objektumok állapotuk szerint',

    'UI:ConfigMgmtMenuOverview:Title' => 'Konfigurációkezelés műszerfal',
    'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Konfigurációs elemek állapotuk szerint',
    'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Konfigurációs elemek típusonként',

    'UI:RequestMgmtMenuOverview:Title' => 'Kérelemkezelés műszerfal',
    'UI-RequestManagementOverview-RequestByService' => 'Felhasználói kérelmek szolgáltatásonként',
    'UI-RequestManagementOverview-RequestByPriority' => 'Felhasználói kérelmek prioritás szerint',
    'UI-RequestManagementOverview-RequestUnassigned' => 'Felhasználói kérelmek, amelyek még nem lettek ügyintézőhöz rendelve',

    'UI:IncidentMgmtMenuOverview:Title' => 'Incidenskezelés műszerfal',
    'UI-IncidentManagementOverview-IncidentByService' => 'Incidensek szolgáltatásonként',
    'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidensek prioritás szerint',
    'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidensek, amelyek még nem lettek ügyintézőhöz rendelve',

    'UI:ChangeMgmtMenuOverview:Title' => 'Változáskezelés műszerfal',
    'UI-ChangeManagementOverview-ChangeByType' => 'Változások típusonként',
    'UI-ChangeManagementOverview-ChangeUnassigned' => 'Változások, amelyek még nem lettek ügyintézőhöz rendelve',
    'UI-ChangeManagementOverview-ChangeWithOutage' => 'Változások által okozott üzemszünet',

    'UI:ServiceMgmtMenuOverview:Title' => 'Szolgáltatáskezelés műszerfal',
    'UI-ServiceManagementOverview-CustomerContractToRenew' => 'A következő 30 napban lejáró ügyfélszerződések',
    'UI-ServiceManagementOverview-ProviderContractToRenew' => 'A következő 30 napban lejáró szolgáltatói szerződések',

    'UI:ContactsMenu' => 'Kapcsolattartók',
    'UI:ContactsMenu+' => '',
    'UI:ContactsMenu:Title' => 'Kapcsolattartó áttekintő',
    'UI-ContactsMenu-ContactsByLocation' => 'Kapcsolattartók helyszín szerint',
    'UI-ContactsMenu-ContactsByType' => 'Kapcsolattartók típusonként',
    'UI-ContactsMenu-ContactsByStatus' => 'Kapcsolattartók állapotuk szerint',

    'Menu:CSVImportMenu' => 'CSV importálás',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:CSVImportMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:DataModelMenu' => 'Adatmodell',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:DataModelMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:ExportMenu' => 'Exportálás',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:ExportMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:NotificationsMenu' => 'Értesítések',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:NotificationsMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'UI:NotificationsMenu:Title' => 'Értesítések beállítása',
    'UI:NotificationsMenu:Help' => 'Súgó',
    'UI:NotificationsMenu:HelpContent' => '<p>Az '.ITOP_APPLICATION_SHORT.' alkalmazásban az értesítések teljesen testreszabhatók. Értesítések az objektumok két csoportjára épülnek: <i>eseményindítók és műveletek</i>.</p>
<p><i><b>Az eseményindítók</b></i> meghatározzák, hogy mikor kerüljön végrehajtásra az értesítés. Az iTop magjának részei a különböző eseményindítók, de a bővítményekkel továbbiak is létrehozhatók:
<ol>
    <li>Egyes eseményindítók akkor hajtódnak végre, amikor a megadott osztály egy objektuma <b>létrehozódik</b>, <b>frissül</b> vagy <b>törlődik</b></li>
    <li>Egyes eseményindítók akkor hajtódnak végre, amikor a megadott osztály egy objektuma <b>felvesz</b> vagy <b>elhagy</b> egy meghatározott </b>állapotot</b>.</li>
    <li>Egyes eseményindítók akkor hajtódnak végre, amikor egy <b>TTO vagy TTR küszöbérték</b><b>el lett érve</b>.</li>
</ol>
</p>
<p>
<i><b>A műveletek</b></i> meghatározzák a kiváltó programok végrehajtásakor végrehajtandó műveleteket. Egyelőre csak kétféle művelet létezik:
<ol>
    <li>Email üzenet küldése: Az ilyen műveletek meghatározzák az email küldéséhez használandó sablont, valamint az üzenet egyéb paramétereit, mint például a címzettek, fontosság stb.<br />
    Egy speciális oldalon: <a href="../setup/email.test.php" target="_blank">email.test.php</a> oldalon keresztül a PHP mail konfiguráció tesztelhető.</li>
    <li>Kimenő webhook-ok: Lehetővé teszik a harmadik fél alkalmazásával való integrációt strukturált adatok küldésével egy meghatározott URL-címen keresztül.</li>
</ol>
</p>
<p>Művelet végrehjatásához azt egy eseményindítóhoz kell rendelni.
A művelet eseményindítóhoz rendelésekor kap egy sorszámot , amely meghatározza a műveletek végrehajtási sorrendjét.</p>',
    'UI:NotificationsMenu:Triggers' => 'Eseményindítók',
    'UI:NotificationsMenu:AvailableTriggers' => 'Elérhető eseményindítók',
    'UI:NotificationsMenu:OnCreate' => 'Objektum létrehozás',
    'UI:NotificationsMenu:OnStateEnter' => 'Objektum állapot felvétele',
    'UI:NotificationsMenu:OnStateLeave' => 'Objektum állapot elhagyása',
    'UI:NotificationsMenu:Actions' => 'Műveletek',
    'UI:NotificationsMenu:Actions:ActionEmail' => 'Email műveletek',
    'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook műveletek (kimenő integrációk)',
    'UI:NotificationsMenu:Actions:Action' => 'Más műveletek',
    'UI:NotificationsMenu:AvailableActions' => 'Elérhető műveletek',

    'Menu:TagAdminMenu' => 'Címkék konfigurációja',
    'Menu:TagAdminMenu+' => 'Címkeérték kezelés',
    'UI:TagAdminMenu:Title' => 'Címke konfiguráció',
    'UI:TagAdminMenu:NoTags' => 'Nincs címkemező konfigurálva',
    'UI:TagSetFieldData:Error' => 'Hiba: %1$s',

    'Menu:AuditCategories' => 'Audit-kategóriák',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:AuditCategories+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:Notifications:Title' => 'Audit-kategóriák',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:RunQueriesMenu' => 'Lekérdezés futtatás',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:RunQueriesMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:QueryMenu' => 'Lekérdezés-gyűjtemény',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:QueryMenu+' => 'Query phrasebook~~',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:DataAdministration' => 'Adat adminisztráció',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:DataAdministration+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:UniversalSearchMenu' => 'Univerzális keresés',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:UniversalSearchMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:UserManagementMenu' => 'Felhasználókezelés',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:UserManagementMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:ProfilesMenu' => 'Profilok',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:ProfilesMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:ProfilesMenu:Title' => 'Profilok',
    // Duplicated into itop-welcome-itil (will be removed from here...)

    'Menu:UserAccountsMenu' => 'Felhasználói fiókok',
    // Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:UserAccountsMenu+' => '',
    // Duplicated into itop-welcome-itil (will be removed from here...)
    'Menu:UserAccountsMenu:Title' => 'Felhasználói fiókok',
    // Duplicated into itop-welcome-itil (will be removed from here...)

    'UI:iTopVersion:Short' => '%1$s verzió: %2$s',
    'UI:iTopVersion:Long' => '%1$s verzió: %2$s-%3$s %4$s',
    'UI:PropertiesTab' => 'Tulajdonságok',

    'UI:OpenDocumentInNewWindow_' => 'Megnyitás',
    'UI:DownloadDocument_' => 'Letöltés',
    'UI:Document:NoPreview' => 'Nincs elérhető előnézet ehhez a dokumentumhoz',
    'UI:Download-CSV' => '%1$s letöltése',

    'UI:DeadlineMissedBy_duration' => 'Túllépve: %1$s ',
    'UI:Deadline_LessThan1Min' => '< 1 perc',
    'UI:Deadline_Minutes' => '%1$d perc',
    'UI:Deadline_Hours_Minutes' => '%1$d óra %2$d perc',
    'UI:Deadline_Days_Hours_Minutes' => '%1$d nap %2$d óra %3$d perc',
    'UI:Help' => 'Súgó',
    'UI:PasswordConfirm' => 'Jóváhagyás',
    'UI:BeforeAdding_Class_ObjectsSaveThisObject' => '%1$s objektumok hozzáadása előtt mentse ezt az objektumot',
    'UI:DisplayThisMessageAtStartup' => 'Az üzenet megjelenítése indításkor',
    'UI:RelationshipGraph' => 'Grafikus nézet',
    'UI:RelationshipList' => 'Lista',
    'UI:RelationGroups' => 'Csoportok',
    'UI:OperationCancelled' => 'Művelet visszavonva',
    'UI:ElementsDisplayed' => 'Szűrés',
    'UI:RelationGroupNumber_N' => '#%1$d csoport',
    'UI:Relation:ExportAsPDF' => 'Exportálás PDF-ként...',
    'UI:RelationOption:GroupingThreshold' => 'Csoportosítási küszöb',
    'UI:Relation:AdditionalContextInfo' => 'További háttér-információk',
    'UI:Relation:NoneSelected' => 'Nincs',
    'UI:Relation:Zoom' => 'Nagyítás',
    'UI:Relation:ExportAsAttachment' => 'Exportálás mellékletként...',
    'UI:Relation:DrillDown' => 'Részletek...',
    'UI:Relation:PDFExportOptions' => 'PDF Exportálás beállításai',
    'UI:Relation:AttachmentExportOptions_Name' => 'Melléklet beállításai %1$s -hoz',
    'UI:RelationOption:Untitled' => 'Névtelen',
    'UI:Relation:Key' => 'Kulcs',
    'UI:Relation:Comments' => 'Megjegyzések',
    'UI:RelationOption:Title' => 'Cím',
    'UI:RelationOption:IncludeList' => 'Foglalja bele az objektumok listáját',
    'UI:RelationOption:Comments' => 'Megjegyzések',
    'UI:Button:Export' => 'Exportálás',
    'UI:Relation:PDFExportPageFormat' => 'Oldalformátum',
    'UI:PageFormat_A3' => 'A3',
    'UI:PageFormat_A4' => 'A4',
    'UI:PageFormat_Letter' => 'Letter',
    'UI:Relation:PDFExportPageOrientation' => 'Tájolás',
    'UI:PageOrientation_Portrait' => 'Függőleges',
    'UI:PageOrientation_Landscape' => 'Vízszintes',
    'UI:RelationTooltip:Redundancy' => 'Redundancia',
    'UI:RelationTooltip:ImpactedItems_N_of_M' => '# érintett elemei: %1$d / %2$d',
    'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Kritikus küszöb: %1$d / %2$d',
    'Portal:Title' => ITOP_APPLICATION_SHORT.' felhasználói portál',
    'Portal:NoRequestMgmt' => 'Üdv, %1$s, Önt erre az oldalra irányítottuk át, mert fiókjában a \'Portál felhasználó\' profil van beállítva. Sajnos a '.ITOP_APPLICATION_SHORT.' nem lett telepítve \'Kérelemkezelés\' funkcióval. Kérjük, lépjen kapcsolatba a rendszergazdával.',
    'Portal:Refresh' => 'Frissítés',
    'Portal:Back' => 'Vissza',
    'Portal:WelcomeUserOrg' => 'Üdvözöljük %1$s, a %2$s -ból',
    'Portal:TitleDetailsFor_Request' => 'Kérelem részletei',
    'Portal:ShowOngoing' => 'Nyitott kérelmek megjelenítése',
    'Portal:ShowClosed' => 'Lezárt kérelmek megjelenítése',
    'Portal:CreateNewRequest' => 'Új kérelem létrehozása',
    'Portal:CreateNewRequestItil' => 'Új kérelem létrehozása',
    'Portal:CreateNewIncidentItil' => 'Új incidensjelentés létrehozása',
    'Portal:ChangeMyPassword' => 'Jelszóváltoztatás',
    'Portal:Disconnect' => 'Kilépés',
    'Portal:OpenRequests' => 'Nyitott kérelmeim',
    'Portal:ClosedRequests' => 'Lezárt kérelmeim',
    'Portal:ResolvedRequests' => 'Megoldott kérelmeim',
    'Portal:SelectService' => 'Válasszon szolgáltatást a katalógusból:',
    'Portal:PleaseSelectOneService' => 'Kérem válasszon egy szolgáltatást',
    'Portal:SelectSubcategoryFrom_Service' => 'Válassza ki a %1$s szolgáltatás alkategóriáját:',
    'Portal:PleaseSelectAServiceSubCategory' => 'Kérem válasszon egy alkategóriát',
    'Portal:DescriptionOfTheRequest' => 'Adja meg a kérelem leírását:',
    'Portal:TitleRequestDetailsFor_Request' => '%1$s kérelem részletei:',
    'Portal:NoOpenRequest' => 'A kategóriához nem tartozik nyitott kérelem.',
    'Portal:NoClosedRequest' => 'Nincs kérelem ebben a kategóriában',
    'Portal:Button:ReopenTicket' => 'Hibajegy újranyitása',
    'Portal:Button:CloseTicket' => 'Hibajegy lezárása',
    'Portal:Button:UpdateRequest' => 'Kérelem frissítése',
    'Portal:EnterYourCommentsOnTicket' => 'Adjon megjegyzést a megoldáshoz:',
    'Portal:ErrorNoContactForThisUser' => 'Hiba: az aktuális felhasználó nem tartozik egyetlen Kapcsolattartóhoz / Személyhez sem. Kérem vegye fel a kapcsolatot a rendszergazdával.',
    'Portal:Attachments' => 'Mellékletek',
    'Portal:AddAttachment' => 'Melléklet hozzáadása',
    'Portal:RemoveAttachment' => 'Melléklet eltávolítása',
    'Portal:Attachment_No_To_Ticket_Name' => 'Mellékletek: #%1$d a %2$s (%3$s) hibajegyhez',
    'Portal:SelectRequestTemplate' => 'Válasszon sablont %1$s -hoz',
    'Enum:Undefined' => 'Nem meghatározott',
    'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s nap %2$s óra %3$s perc %4$s másodperc',
    'UI:ModifyAllPageTitle' => 'Összes módosítása',
    'UI:Modify_N_ObjectsOf_Class' => '%2$s osztály %1$d objektumainak módosítása',
    'UI:Modify_M_ObjectsOf_Class_OutOf_N' => '%2$s osztály %1$d objektumának módosítása, a %3$d -ban',
    'UI:Menu:ModifyAll' => 'Módosítás...',
    'UI:Button:ModifyAll' => 'Összes módosítása',
    'UI:Button:PreviewModifications' => 'Módosítások előnézete >>',
    'UI:ModifiedObject' => 'Objektum módosítva',
    'UI:BulkModifyStatus' => 'Állapot',
    'UI:BulkModifyStatus+' => '',
    'UI:BulkModifyErrors' => 'Hibák (ha vannak)',
    'UI:BulkModifyErrors+' => '',
    'UI:BulkModifyStatusOk' => 'OK',
    'UI:BulkModifyStatusError' => 'Hiba',
    'UI:BulkModifyStatusModified' => 'Módosítva',
    'UI:BulkModifyStatusSkipped' => 'Átugorva',
    'UI:BulkModify_Count_DistinctValues' => '%1$d eltérő értékek:',
    'UI:BulkModify:Value_Exists_N_Times' => '%1$s *, %2$d *',
    'UI:BulkModify:N_MoreValues' => '%1$d további értékei ...',
    'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Kísérlet a következő csak olvaható mező beállítására: %1$s',
    'UI:FailedToApplyStimuli' => 'A művelet sikertelen',
    'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: %3$s osztály %2$d objketumainak módosítása',
    'UI:CaseLogTypeYourTextHere' => 'Írjon ide:',
    'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
    'UI:CaseLog:InitialValue' => 'Kezdeti érték:',
    'UI:AttemptingToSetASlaveAttribute_Name' => '%1$s mező nem írható, mert az a szinkronizációnál használt kulcs. Érték nem lett beállítva.',
    'UI:ActionNotAllowed' => 'Ennek a műveletnek a végrehajtása nem engedélyezett ezen az objektumon.',
    'UI:BulkAction:NoObjectSelected' => 'Válasszon ki legalább egy objektumot a művelet végrehajtásához',
    'UI:AttemptingToChangeASlaveAttribute_Name' => '%1$s mező nem írható, mert az a szinkronizációnál használt kulcs. Érték változatlan maradt.',
    'UI:Pagination:HeaderSelection' => 'Összesen: %1$s objektum (%2$s objektum kiválasztva).',
    'UI:Pagination:HeaderNoSelection' => 'Összesen: %1$s objektum',
    'UI:Pagination:PageSize' => '%1$s objektum oldalanként',
    'UI:Pagination:PagesLabel' => 'Oldalak:',
    'UI:Pagination:All' => 'Összes',
    'UI:HierarchyOf_Class' => '%1$s hierarchiája',
    'UI:Preferences' => 'Beállítások...',
    'UI:ArchiveModeOn' => 'Archív módba lépés',
    'UI:ArchiveModeOff' => 'Kilépés az archív módból',
    'UI:ArchiveMode:Banner' => 'Archív mód',
    'UI:ArchiveMode:Banner+' => 'Az archivált objektumok láthatók és nincs lehetőség a módosításukra',
    'UI:FavoriteOrganizations' => 'Előnyben részesített szervezeti egységek',
    'UI:FavoriteOrganizations+' => 'Jelölje be az alábbi listában azokat a szervezeti egységeket, amelyeket a gyors hozzáférés érdekében a legördülő menüben szeretne látni. Vegye figyelembe, hogy ez nem biztonsági beállítás, bármely szervezet objektumai továbbra is láthatóak és elérhetők a legördülő listában a \\"Minden szervezet\\" kiválasztásával..',
    'UI:FavoriteLanguage' => 'A felhasználói felület nyelve',
    'UI:Favorites:SelectYourLanguage' => 'Válassza ki a kívánt nyelvet',
    'UI:FavoriteOtherSettings' => 'Egyéb beállítások',
    'UI:Favorites:Default_X_ItemsPerPage' => 'Alapértelmezett hossz:  %1$s elem oldalanként',
    'UI:Favorites:ShowObsoleteData' => 'Elavult adatok megjelenítése',
    'UI:Favorites:ShowObsoleteData+' => 'Elavult adatok megjelenítése a keresési eredményekben és a kiválasztandó elemek listáiban',
    'UI:NavigateAwayConfirmationMessage' => 'Bármely módosítás eldobásra kerül',
    'UI:CancelConfirmationMessage' => 'A változtatásai elvesznek. Mindenképp folytatja?',
    'UI:AutoApplyConfirmationMessage' => 'Néhány változtatás még nem került alkalmazásra. Szeretné, ha az iTop figyelembe venné őket?',
    'UI:Create_Class_InState' => '%1$s létrehozása: ',
    'UI:OrderByHint_Values' => 'Rendezési sorrend: %1$s',
    'UI:Menu:AddToDashboard' => 'Hozzáadás a műszerfalhoz...',
    'UI:Button:Refresh' => 'Frissítés',
    'UI:Button:GoPrint' => 'Nyomtatás...',
    'UI:ExplainPrintable' => 'Kattintson a %1$s ikonra az elemek elrejtéséhez a nyomtatásból.<br/>A nyomtatás előtti előnézet megtekintéséhez használja a böngésző "nyomtatási előnézet" funkcióját.<br/>Figyelem: ez a fejléc és a többi hangolási vezérlőelem nem kerül kinyomtatásra.',
    'UI:PrintResolution:FullSize' => 'Teljes méret',
    'UI:PrintResolution:A4Portrait' => 'A4 függőleges',
    'UI:PrintResolution:A4Landscape' => 'A4 vízszintes',
    'UI:PrintResolution:LetterPortrait' => 'Letter függőleges',
    'UI:PrintResolution:LetterLandscape' => 'Letter vízszintes',
    'UI:Toggle:SwitchToStandardDashboard' => 'Átváltás a standard műszerfalra',
    'UI:Toggle:SwitchToCustomDashboard' => 'Átváltás az egyéni műszerfalra',

    'UI:ConfigureThisList' => 'Lista konfigurálása...',
    'UI:ListConfigurationTitle' => 'Lista konfiguráció',
    'UI:ColumnsAndSortOrder' => 'Oszlopok és sorbarendezés:',
    'UI:UseDefaultSettings' => 'Használja az alapbeállításokat',
    'UI:UseSpecificSettings' => 'Használja a következő beállításokat:',
    'UI:Display_X_ItemsPerPage_prefix' => 'Megjelenítés',
    'UI:Display_X_ItemsPerPage_suffix' => 'Elemek oldalanként',
    'UI:UseSavetheSettings' => 'Beállítások mentése',
    'UI:OnlyForThisList' => 'Csak ehhez a listához',
    'UI:ForAllLists' => 'Alapértelmezett minden listához',
    'UI:ExtKey_AsLink' => '%1$s (Link)',
    'UI:ExtKey_AsFriendlyName' => '%1$s (Barátságos név)',
    'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
    'UI:Button:MoveUp' => 'Feljebb',
    'UI:Button:MoveDown' => 'Lejjebb',

    'UI:OQL:UnknownClassAndFix' => 'Ismeretlen osztály: %1$s. Próbálja meg %2$s -t helyette',
    'UI:OQL:UnknownClassNoFix' => 'Ismeretlen osztály: %1$s',

    'UI:Dashboard:EditCustom' => 'Egyéni verzió szerkesztése...',
    'UI:Dashboard:CreateCustom' => 'Egyéni verzió létrehozása...',
    'UI:Dashboard:DeleteCustom' => 'Egyéni verzió törlése...',
    'UI:Dashboard:RevertConfirm' => 'Az eredeti változaton végrehajtott minden változtatás elveszik. Kérjük, erősítse meg, hogy ezt szeretné.',
    'UI:ExportDashBoard' => 'Exportálás fájlba',
    'UI:ImportDashBoard' => 'Importálás fájlból...',
    'UI:ImportDashboardTitle' => 'Importálás egy fájlból',
    'UI:ImportDashboardText' => 'Importáláshoz válasszon ki egy műszerfal fájlt',
    'UI:Dashboard:Actions' => 'Műszerfal műveletek',
    'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'Ez a műszerfal olyan információkat jelenít meg, amelyek nem tartalmazzák a folyamatban lévő változásokat.',


    'UI:DashletCreation:Title' => 'Új műszer létrehozása',
    'UI:DashletCreation:Dashboard' => 'Műszerfal',
    'UI:DashletCreation:DashletType' => 'Műszerfal típus',
    'UI:DashletCreation:EditNow' => 'Műszerfal szerkesztése',

    'UI:DashboardEdit:Title' => 'Műszerfal szerkesztő',
    'UI:DashboardEdit:DashboardTitle' => 'Cím',
    'UI:DashboardEdit:AutoReload' => 'Automatikus frissítés',
    'UI:DashboardEdit:AutoReloadSec' => 'Frissítési időköz (mp)',
    'UI:DashboardEdit:AutoReloadSec+' => 'A megengedett minimum %1$d mp',
    'UI:DashboardEdit:Revert' => 'Visszavonás',
    'UI:DashboardEdit:Apply' => 'Alkalmazás',

    'UI:DashboardEdit:Layout' => 'Elrendezés',
    'UI:DashboardEdit:Properties' => 'Műszerfal tulajdonságai',
    'UI:DashboardEdit:Dashlets' => 'Elérhető műszerek',
    'UI:DashboardEdit:DashletProperties' => 'Műszer tulajdonságai',

    'UI:Form:Property' => 'Tulajdonság',
    'UI:Form:Value' => 'Érték',

    'UI:DashletUnknown:Label' => 'Ismeretlen',
    'UI:DashletUnknown:Description' => 'Ismeretlen műszer (talán eltávolították)',
    'UI:DashletUnknown:RenderText:View' => 'Nem lehet megjeleníteni ezt a műszert.',
    'UI:DashletUnknown:RenderText:Edit' => 'Nem lehet megjeleníteni ezt a műszert (%1$s osztály). Ellenőriztesse a rendszergazdával, hogy elérhető-e.',
    'UI:DashletUnknown:RenderNoDataText:Edit' => 'Nincs előnézet ehhez a műszerhez (%1$s osztály).',
    'UI:DashletUnknown:Prop-XMLConfiguration' => 'Konfiguráció (nyers XML)',

    'UI:DashletProxy:Label' => 'Proxy',
    'UI:DashletProxy:Description' => 'Proxy műszer',
    'UI:DashletProxy:RenderNoDataText:Edit' => 'Nincs előnézet ehhez a harmadik féltől származó műszerhez (%1$s osztály).',
    'UI:DashletProxy:Prop-XMLConfiguration' => 'Konfiguráció (nyers XML)',

    'UI:DashletPlainText:Label' => 'Szöveg',
    'UI:DashletPlainText:Description' => 'Egyszerű szöveg (nincs formázás)',
    'UI:DashletPlainText:Prop-Text' => 'Szöveg',
    'UI:DashletPlainText:Prop-Text:Default' => 'Ide írja a szöveget...',

    'UI:DashletObjectList:Label' => 'Objektumlista',
    'UI:DashletObjectList:Description' => 'Objektumlista műszer',
    'UI:DashletObjectList:Prop-Title' => 'Cím',
    'UI:DashletObjectList:Prop-Query' => 'Lekérdezés',
    'UI:DashletObjectList:Prop-Menu' => 'Menü',

    'UI:DashletGroupBy:Prop-Title' => 'Cím',
    'UI:DashletGroupBy:Prop-Query' => 'Lekérdezés',
    'UI:DashletGroupBy:Prop-Style' => 'Stílus',
    'UI:DashletGroupBy:Prop-GroupBy' => 'Csoportosítás...',
    'UI:DashletGroupBy:Prop-GroupBy:Hour' => '%1$s órája (0-23)',
    'UI:DashletGroupBy:Prop-GroupBy:Month' => '%1$s hónapja (1 - 12)',
    'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => '%1$s hét napján',
    'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => '%1$s hónap napján',
    'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (óra)',
    'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (hónap)',
    'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (a hét napja)',
    'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (a hónap napja)',
    'UI:DashletGroupBy:MissingGroupBy' => 'Válassza ki azt a mezőt, amelyen az objektumok csoportosítva lesznek.',

    'UI:DashletGroupByPie:Label' => 'Tortadiagram',
    'UI:DashletGroupByPie:Description' => 'Tortadiagram',
    'UI:DashletGroupByBars:Label' => 'Oszlopdiagram',
    'UI:DashletGroupByBars:Description' => 'Oszlopdiagram',
    'UI:DashletGroupByTable:Label' => 'Csoportosítás (táblánként)',
    'UI:DashletGroupByTable:Description' => 'Lista (mezőnként csoportosítva)',

    // New in 2.5
    'UI:DashletGroupBy:Prop-Function' => 'Kigyűjtés funkció',
    'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Funkció attribútum',
    'UI:DashletGroupBy:Prop-OrderDirection' => 'Irány',
    'UI:DashletGroupBy:Prop-OrderField' => 'Sorbarendezés',
    'UI:DashletGroupBy:Prop-Limit' => 'Határérték',

    'UI:DashletGroupBy:Order:asc' => 'Növekvő',
    'UI:DashletGroupBy:Order:desc' => 'Csökkenő',

    'UI:GroupBy:count' => 'Mennyiség',
    'UI:GroupBy:count+' => 'Elemek száma',
    'UI:GroupBy:sum' => 'Összeg',
    'UI:GroupBy:sum+' => '%1$s összege',
    'UI:GroupBy:avg' => 'Átlag',
    'UI:GroupBy:avg+' => '%1$s átlaga',
    'UI:GroupBy:min' => 'Minimum',
    'UI:GroupBy:min+' => '%1$s minimuma',
    'UI:GroupBy:max' => 'Maximum',
    'UI:GroupBy:max+' => '%1$s maximuma',
    // ---

    'UI:DashletHeaderStatic:Label' => 'Fejléc',
    'UI:DashletHeaderStatic:Description' => 'Megjelenít egy vízszintes elválasztót',
    'UI:DashletHeaderStatic:Prop-Title' => 'Cím',
    'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kapcsolattartók',
    'UI:DashletHeaderStatic:Prop-Icon' => 'Ikon',

    'UI:DashletHeaderDynamic:Label' => 'Fejléc statisztikákkal',
    'UI:DashletHeaderDynamic:Description' => 'Fejléc statisztikákkal (csoportosítva...)',
    'UI:DashletHeaderDynamic:Prop-Title' => 'Cím',
    'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kapcsolattartók',
    'UI:DashletHeaderDynamic:Prop-Icon' => 'Ikon',
    'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Alcím',
    'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kapcsolattartók',
    'UI:DashletHeaderDynamic:Prop-Query' => 'Lekérdezés',
    'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Csoportosítva',
    'UI:DashletHeaderDynamic:Prop-Values' => 'Értékek',

    'UI:DashletBadge:Label' => 'Jelvény',
    'UI:DashletBadge:Description' => 'Objektum ikon új/keresés',
    'UI:DashletBadge:Prop-Class' => 'Osztály',

    'DayOfWeek-Sunday' => 'Vasárnap',
    'DayOfWeek-Monday' => 'Hétfő',
    'DayOfWeek-Tuesday' => 'Kedd',
    'DayOfWeek-Wednesday' => 'Szerda',
    'DayOfWeek-Thursday' => 'Csütörtök',
    'DayOfWeek-Friday' => 'Péntek',
    'DayOfWeek-Saturday' => 'Szombat',
    'Month-01' => 'Január',
    'Month-02' => 'Február',
    'Month-03' => 'Március',
    'Month-04' => 'Április',
    'Month-05' => 'Május',
    'Month-06' => 'Június',
    'Month-07' => 'Július',
    'Month-08' => 'Augusztus',
    'Month-09' => 'Szeptember',
    'Month-10' => 'Október',
    'Month-11' => 'November',
    'Month-12' => 'December',

    // Short version for the DatePicker
    'DayOfWeek-Sunday-Min' => 'Va',
    'DayOfWeek-Monday-Min' => 'Hé',
    'DayOfWeek-Tuesday-Min' => 'Ke',
    'DayOfWeek-Wednesday-Min' => 'Sze',
    'DayOfWeek-Thursday-Min' => 'Cs',
    'DayOfWeek-Friday-Min' => 'Pé',
    'DayOfWeek-Saturday-Min' => 'Szo',
    'Month-01-Short' => 'Jan',
    'Month-02-Short' => 'Feb',
    'Month-03-Short' => 'Már',
    'Month-04-Short' => 'Ápr',
    'Month-05-Short' => 'Máj',
    'Month-06-Short' => 'Jún',
    'Month-07-Short' => 'Júl',
    'Month-08-Short' => 'Aug',
    'Month-09-Short' => 'Szep',
    'Month-10-Short' => 'Okt',
    'Month-11-Short' => 'Nov',
    'Month-12-Short' => 'Dec',
    'Calendar-FirstDayOfWeek' => '1',// 0 = Vasárnap, 1 = Hétfő, stb...

    'UI:Menu:ShortcutList' => 'Gyorsgomb létrehozása...',
    'UI:ShortcutRenameDlg:Title' => 'Gyorsgomb átnevezése',
    'UI:ShortcutListDlg:Title' => 'Gyorsgomb létrehozása a listához',
    'UI:ShortcutDelete:Confirm' => 'Hagyja jóvá a gyorsgomb(ok) törlését.',
    'Menu:MyShortcuts' => 'Saját gyorsgombok',// Duplicated into itop-welcome-itil (will be removed from here...)
    'Class:Shortcut' => 'Gyorsgomb',
    'Class:Shortcut+' => '~~',
    'Class:Shortcut/Attribute:name' => 'Név',
    'Class:Shortcut/Attribute:name+' => '',
    'Class:ShortcutOQL' => 'Keresési eredmények gyorsgombja',
    'Class:ShortcutOQL+' => '',
    'Class:ShortcutOQL/Attribute:oql' => 'Lekérdezés',
    'Class:ShortcutOQL/Attribute:oql+' => 'A keresendő objektumok listáját meghatározó OQL',
    'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatikus frissítés',
    'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Letiltva',
    'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Egyéni érték',
    'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatikus frissítés időköz (mp)',
    'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'A minimum %1$d mp',

    'UI:FillAllMandatoryFields' => 'Töltsön ki minden kötelező mezőt',
    'UI:ValueMustBeSet' => 'Adjon meg egy értéket',
    'UI:ValueMustBeChanged' => 'Változtassa meg az értéket',
    'UI:ValueInvalidFormat' => 'Érvénytelen formátum',

    'UI:CSVImportConfirmTitle' => 'Hagyja jóvá a műveletet',
    'UI:CSVImportConfirmMessage' => 'Biztos ezt akarja tenni?',
    'UI:CSVImportError_items' => 'Hibák: %1$d',
    'UI:CSVImportCreated_items' => 'Létrehozva: %1$d',
    'UI:CSVImportModified_items' => 'Módosítva: %1$d',
    'UI:CSVImportUnchanged_items' => 'Változatlan: %1$d',
    'UI:CSVImport:DateAndTimeFormats' => 'Dátum és időformátum',
    'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Alapértelmezett formátum: %1$s (pl. %2$s)',
    'UI:CSVImport:CustomDateTimeFormat' => 'Egyéni formátum: %1$s',
    'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Elérhető alakok:<table>
<tr><td>Y</td><td>év (4 számjegy, pl. 2016)</td></tr>
<tr><td>y</td><td>year (2 számjegy, pl. 16 2016-hoz)</td></tr>
<tr><td>m</td><td>month (2 számjegy, pl. 01..12)</td></tr>
<tr><td>n</td><td>month (1 vagy 2 számjegy, nincs kezdő nulla, pl. 1..12)</td></tr>
<tr><td>d</td><td>day (2 számjegy, pl. 01..31)</td></tr>
<tr><td>j</td><td>day (1 vagy 2 számjegy, nincs kezdő nulla, pl. 1..31)</td></tr>
<tr><td>H</td><td>hour (24 óra, 2 számjegy, pl. 00..23)</td></tr>
<tr><td>h</td><td>hour (12 óra, 2 számjegy, pl. 01..12)</td></tr>
<tr><td>G</td><td>hour (24 óra, 1 vagy 2 számjegy, nincs kezdő nulla, pl. 0..23)</td></tr>
<tr><td>g</td><td>hour (12 óra, 1 vagy 2 számjegy, nincs kezdő nulla, pl. 1..12)</td></tr>
<tr><td>a</td><td>hour, am vagy pm (kisbetűs)</td></tr>
<tr><td>A</td><td>hour, AM vagy PM (nagybetűs)</td></tr>
<tr><td>i</td><td>minutes (2 számjegy, pl. 00..59)</td></tr>
<tr><td>s</td><td>seconds (2 számjegy, pl. 00..59)</td></tr>
</table>',

    'UI:Button:Remove' => 'Eltávolítás',
    'UI:AddAnExisting_Class' => '%1$s típusú objektum hozzáadása...',
    'UI:SelectionOf_Class' => '%1$s típusú objektum választéka',

    'UI:AboutBox' => ''.ITOP_APPLICATION_SHORT.' névjegye ...',
    'UI:About:Title' => ''.ITOP_APPLICATION_SHORT.' névjegye',
    'UI:About:DataModel' => 'Adatmodell',
    'UI:About:Support' => 'Támogatás',
    'UI:About:Licenses' => 'Licencek',
    'UI:About:InstallationOptions' => 'Telepítési beállítások',
    'UI:About:ManualExtensionSource' => 'Bővítmény',
    'UI:About:Extension_Version' => 'Verzió: %1$s',
    'UI:About:RemoteExtensionSource' => 'Adat',

    'UI:DisconnectedDlgMessage' => 'Megszakadt a kapcsolat. Az alkalmazás további használatához újra azonosítania kell magát.',
    'UI:DisconnectedDlgTitle' => 'Figyelem!',
    'UI:LoginAgain' => 'Újra-bejelentkezés',
    'UI:StayOnThePage' => 'Maradjon ezen az oldalon',

    'ExcelExporter:ExportMenu' => 'Excel exportálás...',
    'ExcelExporter:ExportDialogTitle' => 'Excel exportálás',
    'ExcelExporter:ExportButton' => 'Exportálás',
    'ExcelExporter:DownloadButton' => '%1$s letöltése',
    'ExcelExporter:RetrievingData' => 'Adat lekérése...',
    'ExcelExporter:BuildingExcelFile' => 'Excel fájl felépítése...',
    'ExcelExporter:Done' => 'Kész.',
    'ExcelExport:AutoDownload' => 'Indítsa el a letöltést ha végzett az exportálással',
    'ExcelExport:PreparingExport' => 'Előkészítés az exportáláshoz...',
    'ExcelExport:Statistics' => 'Statisztikák',
    'portal:legacy_portal' => 'Végfelhasználói Portál',
    'portal:backoffice' => ITOP_APPLICATION_SHORT.' Adminisztrációs felület',

    'UI:CurrentObjectIsLockedBy_User' => 'Az objektum zárolva van, mivel jelenleg %1$s módosítja.',
    'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Az objektumot jelenleg %1$s módosítja. Az Ön módosításait nem lehet elküldeni, mivel azok felülíródnának.',
    'UI:CurrentObjectIsSoftLockedBy_User' => 'Az objektumot jelenleg %1$s módosítja. A módosítások befejezése után elküldheti a módosításokat.',
    'UI:CurrentObjectLockExpired' => 'Az objektum egyidejű módosítását megakadályozó zárolás lejárt..',
    'UI:CurrentObjectLockExpired_Explanation' => 'Az objektum egyidejű módosítását megakadályozó zárolás lejárt. Többé nem küldheti el módosítását, mivel más felhasználók már módosíthatják ezt az objektumot.',
    'UI:ConcurrentLockKilled' => 'A jelenlegi objektum módosítását megakadályozó zárolás törlődött.',
    'UI:Menu:KillConcurrentLock' => 'Az egyidejű módosítási zár megszüntetése !',

    'UI:Menu:ExportPDF' => 'Exportálás PDF-be...',
    'UI:Menu:PrintableVersion' => 'Nyomtatóbarát verzió',

    'UI:BrowseInlineImages' => 'Képek tallózása...',
    'UI:UploadInlineImageLegend' => 'Új kép feltöltése',
    'UI:SelectInlineImageToUpload' => 'Válasszon egy képet',
    'UI:AvailableInlineImagesLegend' => 'Elérhető képek',
    'UI:NoInlineImage' => 'A szerveren nincs elérhető kép. Használja a fenti "Tallózás" gombot egy kép kiválasztásához a számítógépéről, és töltse fel a szerverre.',

    'UI:ToggleFullScreen' => 'Maximalizálás / Minimalizálás',
    'UI:Button:ResetImage' => 'Az előző kép visszaállítása',
    'UI:Button:RemoveImage' => 'Kép eltávolítása',
    'UI:Button:UploadImage' => 'Kép feltöltése a merevlemezről',
    'UI:UploadNotSupportedInThisMode' => 'A képek vagy fájlok módosítása ebben az üzemmódban nem támogatott.',

    'UI:Button:RemoveDocument' => 'Dokumentum törlése',

    // Search form
    'UI:Search:Toggle' => 'Minimalizál / Kiterjeszt',
    'UI:Search:AutoSubmit:DisabledHint' => 'Az automatikus beküldés le van tiltva ebben az osztályban',
    'UI:Search:Obsolescence:DisabledHint' => 'Az Ön beállításai alapján az elavult adatok el vannak rejtve.',
    'UI:Search:NoAutoSubmit:ExplainText' => 'Adjon meg néhány feltételt a keresőmezőben, vagy kattintson a keresés gombra az objektumok megtekintéséhez.',
    'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Adjon meg egy feltételt',
    // - Add new criteria button
    'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Legutóbb használt',
    'UI:Search:AddCriteria:List:MostPopular:Title' => 'Legnépszerűbb',
    'UI:Search:AddCriteria:List:Others:Title' => 'Egyebek',
    'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Még egyik sem',

    // - Criteria header actions
    'UI:Search:Criteria:Toggle' => 'Minimalizál / Kiterjeszt',
    'UI:Search:Criteria:Remove' => 'Eltávolítás',
    'UI:Search:Criteria:Locked' => 'Zárolva',

    // - Criteria titles
    //   - Default widget
    'UI:Search:Criteria:Title:Default:Any' => '%1$s: bármely',
    'UI:Search:Criteria:Title:Default:Empty' => '%1$s üres',
    'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s nem üres',
    'UI:Search:Criteria:Title:Default:Equals' => '%1$s egyenlő %2$s -vel',
    'UI:Search:Criteria:Title:Default:Contains' => '%1$s tartalmazza %2$s -t',
    'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s kezdődik %2$s -vel',
    'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s végződik %2$s -vel',
    'UI:Search:Criteria:Title:Default:RegExp' => '%1$s egyezik %2$s -vel',
    'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
    'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
    'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
    'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
    'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
    'UI:Search:Criteria:Title:Default:Between' => '%1$s [%2$s] között',
    'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
    'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Bármely',
    'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s -től %2$s -ig',
    'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s amíg %2$s',
    'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: bármely',
    'UI:Search:Criteria:Title:Default:Between:From' => '%1$s a %2$s -ból',
    'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s egészen %2$s -ig',
    //   - Numeric widget
    //   None yet
    //   - DateTime widget
    'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
    //   - Enum widget
    'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
    'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s és %3$s másik',
    'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: bármely',
    //   - TagSet widget
    'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
    //   - External key widget
    'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s meghatározva',
    'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s nincs meghatározva',
    'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
    'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
    'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s és %3$s másik',
    'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: bármely',
    //   - Hierarchical key widget
    'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s meghatározva',
    'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s nincs meghatározva',
    'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
    'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
    'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s és %3$s másik',
    'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: bármely',

    // - Criteria operators
    //   - Default widget
    'UI:Search:Criteria:Operator:Default:Empty' => 'Üres',
    'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Nem üres',
    'UI:Search:Criteria:Operator:Default:Equals' => 'Egyenlő',
    'UI:Search:Criteria:Operator:Default:Between' => 'Közötte',
    //   - String widget
    'UI:Search:Criteria:Operator:String:Contains' => 'Tartalmazza',
    'UI:Search:Criteria:Operator:String:StartsWith' => 'Kezdődik',
    'UI:Search:Criteria:Operator:String:EndsWith' => 'Végződik',
    'UI:Search:Criteria:Operator:String:RegExp' => 'Reguláris kifejezés',
    //   - Numeric widget
    'UI:Search:Criteria:Operator:Numeric:Equals' => 'Egyenlő',// => '=',
    'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Nagyobb',// => '>',
    'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Nagyobb / egyenlő',// > '>=',
    'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Kisebb',// => '<',
    'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Kisebb / egyenlő',// > '<=',
    'UI:Search:Criteria:Operator:Numeric:Different' => 'Különböző',// => '≠',
    //   - Tag Set Widget
    'UI:Search:Criteria:Operator:TagSet:Matches' => 'Egyezik',

    // - Other translations
    'UI:Search:Value:Filter:Placeholder' => 'Szűrő...',
    'UI:Search:Value:Search:Placeholder' => 'Keresés...',
    'UI:Search:Value:Autocomplete:StartTyping' => 'Kezdje el beírni a lehetséges értékeket',
    'UI:Search:Value:Autocomplete:Wait' => 'Várjon...',
    'UI:Search:Value:Autocomplete:NoResult' => 'Nincs eredmény',
    'UI:Search:Value:Toggler:CheckAllNone' => 'Mindet / egyiket sem ellenőrzi',
    'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Mind / egyik sem látható',

    // - Widget other translations
    'UI:Search:Criteria:Numeric:From' => 'Kezdés',
    'UI:Search:Criteria:Numeric:Until' => 'Amíg',
    'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Bármely',
    'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Bármely',
    'UI:Search:Criteria:DateTime:From' => 'Kezdés',
    'UI:Search:Criteria:DateTime:FromTime' => 'Kezdés',
    'UI:Search:Criteria:DateTime:Until' => 'amíg',
    'UI:Search:Criteria:DateTime:UntilTime' => 'amíg',
    'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Bármely dátum',
    'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Bármely dátum',
    'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Bármely dátum',
    'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Bármely dátum',
    'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'A kijelölt objektumok gyermekei is szerepelnek.',

    'UI:Search:Criteria:Raw:Filtered' => 'Szűrt',
    'UI:Search:Criteria:Raw:FilteredOn' => '%1$s által szűrve',

    'UI:StateChanged' => 'Megváltozott állapot',
));

//
// Expression to Natural language
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Expression:Operator:AND' => ' ÉS ',
    'Expression:Operator:OR' => ' VAGY ',
    'Expression:Operator:=' => ': ',

    'Expression:Unit:Short:DAY' => 'n',
    'Expression:Unit:Short:WEEK' => 'w',
    'Expression:Unit:Short:MONTH' => 'h',
    'Expression:Unit:Short:YEAR' => 'é',

    'Expression:Unit:Long:DAY' => 'nap',
    'Expression:Unit:Long:HOUR' => 'óra',
    'Expression:Unit:Long:MINUTE' => 'perc',

    'Expression:Verb:NOW' => 'most',
    'Expression:Verb:ISNULL' => ': meghatározatlan',
));

//
// iTop Newsroom menu
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'UI:Newsroom:NoNewMessage' => 'Nincs új üzenet',
    'UI:Newsroom:XNewMessage' => '%1$s új üzenet',
    'UI:Newsroom:MarkAllAsRead' => 'Üzenetek jelölése olvasottként',
    'UI:Newsroom:ViewAllMessages' => 'Összes üzenet megjelenítése',
    'UI:Newsroom:Preferences' => 'Hírfolyam beállítások',
    'UI:Newsroom:ConfigurationLink' => 'Konfiguráció',
    'UI:Newsroom:ResetCache' => 'Gyorstár ürítése',
    'UI:Newsroom:DisplayMessagesFor_Provider' => '%1$s üzeneteinek mutatása',
    'UI:Newsroom:DisplayAtMost_X_Messages' => 'Mutasson %1$s üzenetet a %2$s menüben.',
));


Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'Menu:DataSources' => 'Szinkronizációs adatforrások',
    'Menu:DataSources+' => '',
    'Menu:WelcomeMenu' => 'Kezdőoldal',
    'Menu:WelcomeMenu+' => '',
    'Menu:WelcomeMenuPage' => 'Áttekintő',
    'Menu:WelcomeMenuPage+' => '',
    'Menu:AdminTools' => 'Adminisztrációs eszközök',
    'Menu:AdminTools+' => '',
    'Menu:AdminTools?' => 'Az eszközök csak az adminisztrátori profilhoz rendelt felhasználók számára elérhetők.',
    'Menu:DataModelMenu' => 'Adatmodell',
    'Menu:DataModelMenu+' => '',
    'Menu:ExportMenu' => 'Exportálás',
    'Menu:ExportMenu+' => '',
    'Menu:NotificationsMenu' => 'Értesítések',
    'Menu:NotificationsMenu+' => '',
    'Menu:AuditCategories' => 'Audit-kategóriák',
    'Menu:AuditCategories+' => '',
    'Menu:Notifications:Title' => 'Audit-kategóriák',
    'Menu:RunQueriesMenu'         => 'Lekérdezés futtatás',
    'Menu:RunQueriesMenu+'        => '',
    'Menu:QueryMenu'              => 'Lekérdezés-gyűjtemény',
    'Menu:QueryMenu+'             => 'Lekérdezés-gyűjtemény',
    'Menu:UniversalSearchMenu'    => 'Univerzális keresés',
    'Menu:UniversalSearchMenu+'   => '',
    'Menu:UserManagementMenu'     => 'Felhasználókezelés',
    'Menu:UserManagementMenu+'    => '',
    'Menu:ProfilesMenu'           => 'Profilok',
    'Menu:ProfilesMenu+'          => '',
    'Menu:ProfilesMenu:Title'     => 'Profilok',
    'Menu:UserAccountsMenu'       => 'Felhasználói fiókok',
    'Menu:UserAccountsMenu+'      => '',
    'Menu:UserAccountsMenu:Title' => 'Felhasználói fiókok',
    'Menu:MyShortcuts'            => 'Saját gyorsgombok',
    'Menu:UserManagement'         => 'Felhasználókezelés',
    'Menu:Queries'                => 'Lekérdezések',
    'Menu:ConfigurationTools'     => 'Konfiguráció',
));

// Additional language entries not present in English dict
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
    'UI:Toggle:StandardDashboard' => 'Standard',
    'UI:Toggle:CustomDashboard'   => 'Egyéni',
    'UI:Display_X_ItemsPerPage'   => '%1$s elem megjelenítése oldalanként',
    'UI:Dashboard:Edit'           => 'Oldal szerkesztése...',
    'UI:Dashboard:Revert'         => 'Visszaállítás az eredeti verzióra...',
));
