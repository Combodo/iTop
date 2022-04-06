<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AuditCategory' => 'Audit kategória',
	'Class:AuditCategory+' => '',
	'Class:AuditCategory/Attribute:name' => 'Kategória neve',
	'Class:AuditCategory/Attribute:name+' => '',
	'Class:AuditCategory/Attribute:description' => 'Audit kategória leírása',
	'Class:AuditCategory/Attribute:description+' => '',
	'Class:AuditCategory/Attribute:definition_set' => 'Meghatározás halmaz',
	'Class:AuditCategory/Attribute:definition_set+' => '',
	'Class:AuditCategory/Attribute:rules_list' => 'Audit szabályok',
	'Class:AuditCategory/Attribute:rules_list+' => '',
));

//
// Class: AuditRule
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:AuditRule' => 'Auditálási szabály',
	'Class:AuditRule+' => '',
	'Class:AuditRule/Attribute:name' => 'Szabály neve',
	'Class:AuditRule/Attribute:name+' => '',
	'Class:AuditRule/Attribute:description' => 'Auditálási szabály leírása',
	'Class:AuditRule/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => 'Futtatandó lekérdezés',
	'Class:AuditRule/Attribute:query+' => '',
	'Class:AuditRule/Attribute:valid_flag' => 'Érvényes objektum?',
	'Class:AuditRule/Attribute:valid_flag+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'igaz',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'hamis',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => '',
	'Class:AuditRule/Attribute:category_id' => 'Kategória',
	'Class:AuditRule/Attribute:category_id+' => '',
	'Class:AuditRule/Attribute:category_name' => 'Kategória',
	'Class:AuditRule/Attribute:category_name+' => '',
));

//
// Class: QueryOQL
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Query' => 'Query~~',
	'Class:Query+' => 'A query is a data set defined in a dynamic way~~',
	'Class:Query/Attribute:name' => 'Name~~',
	'Class:Query/Attribute:name+' => 'Identifies the query~~',
	'Class:Query/Attribute:description' => 'Description~~',
	'Class:Query/Attribute:description+' => 'Long description for the query (purpose, usage, etc.)~~',
	'Class:Query/Attribute:is_template' => 'Template for OQL fields~~',
	'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications~~',
	'Class:Query/Attribute:is_template/Value:yes' => 'Yes~~',
	'Class:Query/Attribute:is_template/Value:no' => 'No~~',
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

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:User' => 'Felhasználó',
	'Class:User+' => '',
	'Class:User/Attribute:finalclass' => 'Felhasználó típusa',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Kapcsolattartó (személy)',
	'Class:User/Attribute:contactid+' => '',
	'Class:User/Attribute:org_id' => 'Szervezeti egység',
	'Class:User/Attribute:org_id+' => 'A társult személy szervezete',
	'Class:User/Attribute:last_name' => 'Családnév',
	'Class:User/Attribute:last_name+' => '',
	'Class:User/Attribute:first_name' => 'Keresztnév',
	'Class:User/Attribute:first_name+' => '',
	'Class:User/Attribute:email' => 'E-mail',
	'Class:User/Attribute:email+' => '',
	'Class:User/Attribute:login' => 'Bejelentkezési név',
	'Class:User/Attribute:login+' => '',
	'Class:User/Attribute:language' => 'Nyelv',
	'Class:User/Attribute:language+' => '',
	'Class:User/Attribute:language/Value:EN US' => 'Angol',
	'Class:User/Attribute:language/Value:EN US+' => '',
	'Class:User/Attribute:language/Value:FR FR' => 'Francia',
	'Class:User/Attribute:language/Value:FR FR+' => '',
	'Class:User/Attribute:profile_list' => 'Profil',
	'Class:User/Attribute:profile_list+' => '',
	'Class:User/Attribute:allowed_org_list' => 'Engedélyezett szervezeti egységek',
	'Class:User/Attribute:allowed_org_list+' => '',
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',

	'Class:User/Error:LoginMustBeUnique' => 'Bejelentkezési névnek egyedinek kell lennie - "%1s" már létezik.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Legalább egy profilt a felhasználóhoz kell rendelni.',
	'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice~~',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User~~',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization~~',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)~~',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'User Internal~~',
	'Class:UserInternal+' => 'User defined within '.ITOP_APPLICATION_SHORT.'~~',
));

//
// Class: URP_Profiles
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:URP_Profiles' => 'Profil',
	'Class:URP_Profiles+' => '',
	'Class:URP_Profiles/Attribute:name' => 'Neve',
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
	'Class:URP_Dimensions/Attribute:name' => 'Neve',
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
	'Class:URP_UserProfile/Attribute:userlogin' => 'Bejelentkezési név',
	'Class:URP_UserProfile/Attribute:userlogin+' => '',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => '',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => '',
	'Class:URP_UserProfile/Attribute:reason' => 'Ok',
	'Class:URP_UserProfile/Attribute:reason+' => '',
));

//
// Class: URP_UserOrg
//


Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:URP_UserOrg' => 'Felhasználó szervezeti egysége',
	'Class:URP_UserOrg+' => '',
	'Class:URP_UserOrg/Name' => 'Kapcsolat %1$s és %2$s között',
	'Class:URP_UserOrg/Attribute:userid' => 'Felhasználó',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Bejelentkezési név',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Szervezeti egység',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Szervezeti egység',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
	'Class:URP_UserOrg/Attribute:reason' => 'Ok',
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
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimenzió',
	'Class:URP_ProfileProjection/Attribute:dimension+' => '',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profileid+' => '',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profil',
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
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimenzió',
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
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => '',
	'Class:URP_ActionGrant/Attribute:class' => 'Osztály',
	'Class:URP_ActionGrant/Attribute:class+' => '',
	'Class:URP_ActionGrant/Attribute:permission' => 'Hozzáférés',
	'Class:URP_ActionGrant/Attribute:permission+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Igen',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nem',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '',
	'Class:URP_ActionGrant/Attribute:action' => 'Akció',
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
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => '',
	'Class:URP_StimulusGrant/Attribute:class' => 'Osztály',
	'Class:URP_StimulusGrant/Attribute:class+' => '',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Hozzáférés',
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
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Akció engedély',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribútum',
	'Class:URP_AttributeGrant/Attribute:attcode+' => '',
));

//
// Class: UserDashboard
//
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'BooleanLabel:yes' => 'Igen',
	'BooleanLabel:no' => 'Nem',
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'Menu:WelcomeMenu' => 'Üdvözlöm',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Üdvözlöm',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Üdvözli az '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' egy teljeskörű, OpenSource, IT üzemeltetés támogató portál.</p>
<ul>A következőket tartalmazza:
<li>Teljeskörű CMDB (Konfiguráció menedzsment adatbázis) az IT eszközök dokumentálására és verzió kezelésére.</li>
<li>Incidens menedzsment modul az összes IT-hez kapcsolódó kérés életciklusának követésére.</li>
<li>Változás menedzsment modul az IT infrastruktúra változásainak nyomonkövetésére és tervezésére.</li>
<li>Ismert hibák adatbázisa az incidens kezelés sebességének növelésére.</li>
<li>Üzmeszünet modul az összes tervezett leállás tervezésére és azzal kapcsolatos kommunikáció támogatására.</li>
<li>Dashboard-ok az IT infrastruktúra pillanatnyi állapotának gyors áttekintésére.</li>
</ul>
<p>Mindegyik modul önállóan telepíthető és használható.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' egy szolgáltatás orientált megoldás, amely segít az IT szakembereknek több ügyfél és szervezet egyidejű menedzselését.
<ul>az iTop az üzleti folyamatok javításához egy hatékony eszköz, mert:
<li>javítja az IT menedzsment hatékonyságát</li> 
<li>növeli IT üzemeltetés teljesítményét</li> 
<li>növeli az ügyfél elégedettséget és a vezetők számára lehetőséget ad az üzleti teljesítmény növelésére</li>
</ul>
</p>
<p>Az iTop teljesn nyílt ezért, egyszerűen integrálható a jelenlegi IT infrastruktúrába</p>
<p>
<ul>Az üzemeltetési portál bevezetésével:
<li>jobban menedzselhető az egyre komplexebb IT infrstruktúra</li>
<li>az ITIL folyamatok bevezetésre kerülnek</li>
<li>hatékonyan tudja kezelni az egyik legfontosabb IT eszközt, a dokumentációt.</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>This version features a brand new modern and accessible backoffice design.</div>

<div>We kept '.ITOP_APPLICATION.' core functions that you liked and modernized them to make you love them.
We hope you’ll enjoy this version as much as we enjoyed imagining and creating it.</div>

<div>Customize your '.ITOP_APPLICATION.' preferences for a personalized experience.</div>~~',
	'UI:WelcomeMenu:AllOpenRequests' => 'Összes nyitott kérés: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Saját kérések',
	'UI:WelcomeMenu:OpenIncidents' => 'Nyitott incidensek: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Konfigurációs elemek: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Hozzám rendelt incidensek',
	'UI:AllOrganizations' => 'Összes szervezeti egység',
	'UI:YourSearch' => 'Saját keresések',
	'UI:LoggedAsMessage' => 'Bejelentkezve %1$s (%2$s)~~',
	'UI:LoggedAsMessage+Admin' => 'Bejelentkezve %1$s (%2$s, Administrator)~~',
	'UI:Button:Logoff' => 'Kijelentkezés',
	'UI:Button:GlobalSearch' => 'Keresés',
	'UI:Button:Search' => ' Keresés',
	'UI:Button:Clear' => ' Clear ~~',
	'UI:Button:SearchInHierarchy' => 'Search in hierarchy~~',
	'UI:Button:Query' => ' Lekérdezés',
	'UI:Button:Ok' => 'OK',
	'UI:Button:Save' => 'Save~~',
	'UI:Button:SaveAnd' => 'Save and %1$s~~',
	'UI:Button:Cancel' => 'Mégse',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Alkalmazás',
	'UI:Button:Send' => 'Send~~',
	'UI:Button:SendAnd' => 'Send and %1$s~~',
	'UI:Button:Back' => ' << Vissza',
	'UI:Button:Restart' => ' |<< Újraindítás',
	'UI:Button:Next' => ' Következő >>',
	'UI:Button:Finish' => ' Befejezés',
	'UI:Button:DoImport' => ' Importálás indítása',
	'UI:Button:Done' => ' Kész',
	'UI:Button:SimulateImport' => ' Import szimulálása',
	'UI:Button:Test' => 'Teszt!',
	'UI:Button:Evaluate' => ' Értékelés',
	'UI:Button:Evaluate:Title' => ' Értékelés (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Hozzáad...',
	'UI:Button:BrowseObjects' => ' Böngészés...',
	'UI:Button:Add' => ' Hozzáad ',
	'UI:Button:AddToList' => ' << Hozzáad ',
	'UI:Button:RemoveFromList' => ' Eltávolít >> ',
	'UI:Button:FilterList' => ' Szűrés... ',
	'UI:Button:Create' => ' Létrehoz',
	'UI:Button:Delete' => ' Töröl !',
	'UI:Button:Rename' => ' Rename... ~~',
	'UI:Button:ChangePassword' => ' Jelszó változtatás',
	'UI:Button:ResetPassword' => ' Jelszó változtatás',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',
	'UI:UserPref:DoNotShowAgain' => 'Do not show again~~',
	'UI:InputFile:NoFileSelected' => 'No File Selected~~',
	'UI:InputFile:SelectFile' => 'Select a file~~',

	'UI:SearchToggle' => 'Keresés',
	'UI:ClickToCreateNew' => 'Új %1$s létrehozása~~',
	'UI:SearchFor_Class' => '%1$s objektumok keresése',
	'UI:NoObjectToDisplay' => 'Nincs megjeleníthető objektum',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'object_id pareméter kötelező a link_attr megadásánál. Ellenőrizze a sablon definíciót.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'target_attr pareméter kötelező a link_attr megadásánál. Ellenőrizze a sablon definíciót.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'group_by paraméter kötelező. Ellenőrizze a sablon definíciót.',
	'UI:Error:InvalidGroupByFields' => 'Csoportosításnál használt érvénytelen mezők: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Hiba: nem támogatott stílus tömb: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Nem megfelelő kapcsolat meghatározás: kapcsolódó osztály: %1$s nem külső kulcs a %2$s osztályban',
	'UI:Error:Object_Class_Id_NotFound' => 'Objektum: %1$s:%2$d nem található.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Hiba: Körkörös hivatkozás az egymásra mutató mezők között. Ellenőrizze az adatmodelt.',
	'UI:Error:UploadedFileTooBig' => 'Feltöltendő fájl túl nagy. (Maximális méret: %1$s). Ellenőroizze a PHP konfigurációs fájlban az upload_max_filesize és post_max_size beállításokat.',
	'UI:Error:UploadedFileTruncated.' => 'Feltöltött fájl átméretezett!',
	'UI:Error:NoTmpDir' => 'Az átmeneti könyvtár nem meghatározott.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Az átmeneti fájl nem írható. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Feltöltés megállt a fájl kiterjesztés miatt. (Eredeti fájl név = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Fájl feltöltés sikertelen ismeretlen hiba miatt. (Hibakód = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Hiba: a következő paramétert meg kell adni ennél a műveletnél: %1$s.',
	'UI:Error:2ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s és %2$s.',
	'UI:Error:3ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s, %2$s és %3$s.',
	'UI:Error:4ParametersMissing' => 'Hiba: a következő paramétereket meg kell adni ennél a műveletnél: %1$s, %2$s, %3$s és %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Hiba: nem megfelelő OQL lekérdezés: %1$',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Hiba történt a lekérdezs futtatása közben: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Hiba: az objketum már korábban módosításra került.',
	'UI:Error:ObjectCannotBeUpdated' => 'Hiba: az objektum nem frissíthető.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Hiba: az objektum már korában törlésre került!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Az osztály objektumainak tömeges törlése nem engedélyezett %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Az osztály objektumainak törlése nem engedélyezett %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'You are not allowed to view objects of class %1$s~~',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Az osztály objektumainak tömeges frissítése nem engedélyezett %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Hiba: az objektum már klónozott!',
	'UI:Error:ObjectAlreadyCreated' => 'Hiba: az objekltum már létrehozva!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Hiba: érvénytelen stimulus "%1$s" a következő objektum %2$s következő állapotában "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',
	'UI:Error:InvalidToken' => 'Error: the requested operation has already been performed (CSRF token not found)~~',

	'UI:GroupBy:Count' => 'Számossága',
	'UI:GroupBy:Count+' => '',
	'UI:CountOfObjects' => '%1$d darab objektum felel meg a kritériumoknak.',
	'UI_CountOfObjectsShort' => '%1$d objketum.',
	'UI:NoObject_Class_ToDisplay' => 'Nincs megjeleníthető %1$s',
	'UI:History:LastModified_On_By' => 'Utolsó módosítást a következő objektumon %1$s %2$s végezte.',
	'UI:HistoryTab' => 'Törénet',
	'UI:NotificationsTab' => 'Értesítés',
	'UI:History:BulkImports' => 'Történet',
	'UI:History:BulkImports+' => '',
	'UI:History:BulkImportDetails' => 'CSV import végrehajtva: %1$s (%2$s által)',
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
	'UI:Menu:Actions' => 'Akciók',
	'UI:Menu:OtherActions' => 'Egyéb Akciók',
	'UI:Menu:Transitions' => 'Transitions~~',
	'UI:Menu:OtherTransitions' => 'Other Transitions~~',
	'UI:Menu:New' => 'Új...',
	'UI:Menu:Add' => 'Hozzáad...',
	'UI:Menu:Manage' => 'Kezel...',
	'UI:Menu:EMail' => 'e-mail',
	'UI:Menu:CSVExport' => 'CSV export...',
	'UI:Menu:Modify' => 'Módosít...',
	'UI:Menu:Delete' => 'Töröl...',
	'UI:Menu:BulkDelete' => 'Töröl...',
	'UI:UndefinedObject' => 'nem meghatározott',
	'UI:Document:OpenInNewWindow:Download' => 'Megnyitás új ablakban: %1$s, Letöltés: %2$s',
	'UI:SplitDateTime-Date' => 'date~~',
	'UI:SplitDateTime-Time' => 'time~~',
	'UI:TruncatedResults' => '%1$d objektum megjelenítve %2$d példányból',
	'UI:DisplayAll' => 'Összes megjelenítése',
	'UI:CollapseList' => 'Elemek',
	'UI:CountOfResults' => '%1$d objektum',
	'UI:ChangesLogTitle' => 'Változás napló (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Változás napló üres',
	'UI:SearchFor_Class_Objects' => 'Keresés %1$s objektumra',
	'UI:OQLQueryBuilderTitle' => 'OQL lekérdezés szerkesztő',
	'UI:OQLQueryTab' => 'OQL lekérdezés',
	'UI:SimpleSearchTab' => 'Egyszerű keresés',
	'UI:Details+' => '',
	'UI:SearchValue:Any' => '* Any *',
	'UI:SearchValue:Mixed' => '* mixed *',
	'UI:SearchValue:NbSelected' => '# selected~~',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- válasszon ki egyet --',
	'UI:Login:Welcome' => 'Üdvözli az '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nem megfelelő bejelentkezési név/jelszó, kérjük próbálja újra.',
	'UI:Login:IdentifyYourself' => 'Folytatás előtt azonosítsa magát',
	'UI:Login:UserNamePrompt' => 'Felhasználó név',
	'UI:Login:PasswordPrompt' => 'Jelszó',
	'UI:Login:ForgotPwd' => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm' => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' can send you an email in which you will find instructions to follow to reset your account.~~',
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
	'UI:ResetPwd-EmailSubject' => 'Reset your '.ITOP_APPLICATION_SHORT.' password~~',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your '.ITOP_APPLICATION_SHORT.' password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',

	'UI:ResetPwd-Title' => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready' => 'The password has been changed.~~',
	'UI:ResetPwd-Login' => 'Click here to login...~~',

	'UI:Login:About'                               => '~~',
	'UI:Login:ChangeYourPassword'                  => 'Jelszó változtatás',
	'UI:Login:OldPasswordPrompt'                   => 'Jelenlegi jelszó',
	'UI:Login:NewPasswordPrompt'                   => 'Új jelszó',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Új jelszó ismét',
	'UI:Login:IncorrectOldPassword'                => 'Hiba: a jelenlegi jelszó hibás',
	'UI:LogOffMenu'                                => 'Kilépés',
	'UI:LogOff:ThankYou' => 'Köszönjük, hogy az '.ITOP_APPLICATION_SHORT.'-ot használja!',
	'UI:LogOff:ClickHereToLoginAgain'              => 'Ismételt bejelentkezéshez kattintson ide',
	'UI:ChangePwdMenu'                             => 'Jelszó módosítás...',
	'UI:Login:PasswordChanged'                     => 'Jelszó sikeresen beállítva!',
	'UI:AccessRO-All' => ITOP_APPLICATION_SHORT.' csak olvasás módban',
	'UI:AccessRO-Users' => ITOP_APPLICATION_SHORT.' csak olvasás módban a végfelhasználók számára',
	'UI:ApplicationEnvironment'                    => 'Application environment: %1$s~~',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Az új jelszó és ismételten beírt érték nem egyezik!',
	'UI:Button:Login' => 'Belépés az '.ITOP_APPLICATION_SHORT.' alkalmazásba',
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' hozzáférés korlátozva. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' adminisztrátorhoz!',
	'UI:Login:Error:AccessAdmin' => 'Adminisztrátori hozzáférés korlátozott. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' adminisztrátorhoz!',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne'                => '-- válasszon ki egyet --',
	'UI:CSVImport:MappingNotApplicable'            => '-- mező figyelmen kívül hagyása --',
	'UI:CSVImport:NoData'                          => 'Üres mező..., kérem agyjon meg adatot!',
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
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Belső hiba: "%1$s" nem megfelelő kód, mert "%2$s" nem külső kulcsa a "%3$s" osztálynak',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objektumok változatlanok maradnak.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objektumok fognak megváltozni.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objektumok hozzáadásra kerülnek.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objektumok hibásak lesznek.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objektumok változatlanak maradtak',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objektumok módosításra kerültek.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objektumok hozzáadásra kerültek.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objkektumok hibásak.',
	'UI:Title:CSVImportStep2' => '2. lépés az 5-ből: CSV adat opciók',
	'UI:Title:CSVImportStep3' => '3. lépés az 5-ből: Adatok összerendelés',
	'UI:Title:CSVImportStep4' => '4. lépés az 5-ből: Import szimuláció',
	'UI:Title:CSVImportStep5' => '5. lépés az 5-ből: Import befejezve',
	'UI:CSVImport:LinesNotImported' => 'Sorok, melyek nem lettek betöltve:',
	'UI:CSVImport:LinesNotImported+' => '',
	'UI:CSVImport:SeparatorComma+' => '',
	'UI:CSVImport:SeparatorSemicolon+' => '',
	'UI:CSVImport:SeparatorTab+' => '',
	'UI:CSVImport:SeparatorOther' => 'egyéb:',
	'UI:CSVImport:QualifierDoubleQuote+' => '',
	'UI:CSVImport:QualifierSimpleQuote+' => '',
	'UI:CSVImport:QualifierOther' => 'egyéb:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Első sor fejléc információkat tartalmaz (oszlopok nevei)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => '%1$s sorok kihagyása a fájl elejéről',
	'UI:CSVImport:CSVDataPreview' => 'CSV adat előnézet',
	'UI:CSVImport:SelectFile' => 'Import fájl kiválasztása:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Betöltés fájlból',
	'UI:CSVImport:Tab:CopyPaste' => 'Adat másolás és beillesztés',
	'UI:CSVImport:Tab:Templates' => 'Sablonok',
	'UI:CSVImport:PasteData' => 'Import adatok beillesztése:',
	'UI:CSVImport:PickClassForTemplate' => 'Letöltendő sablon kiválasztása:',
	'UI:CSVImport:SeparatorCharacter' => 'Elválasztó karakter:',
	'UI:CSVImport:TextQualifierCharacter' => 'Szöveg qualifier karakter',
	'UI:CSVImport:CommentsAndHeader' => 'Megjegyzések és fejléc',
	'UI:CSVImport:SelectClass' => 'Importálandó osztály kiválasztása:',
	'UI:CSVImport:AdvancedMode' => 'Haladó mód',
	'UI:CSVImport:AdvancedMode+' => '',
	'UI:CSVImport:SelectAClassFirst' => 'Adat összerendeléshez elöször válassza ki az osztályt.',
	'UI:CSVImport:HeaderFields' => 'Mező',
	'UI:CSVImport:HeaderMappings' => 'Összerendelés',
	'UI:CSVImport:HeaderSearch' => 'Keresés?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Kérem adja meg az összes mezőre az összerendelési szabályokat.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Kérem adjon eg legalább egy keresési kritériumot',
	'UI:CSVImport:Encoding' => 'Karakter kódolása',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Univerzális kereső',
	'UI:UniversalSearch:Error' => 'Hiba: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Keresendő osztály kiválasztása:',

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
	'UI:CSVExport:LostChars+' => 'The downloaded file will be encoded into %1$s. '.ITOP_APPLICATION_SHORT.' has detected some characters that are not compatible with this format. Those characters will either be replaced by a substitute (e.g. accentuated chars losing the accent), or they will be discarded. You can copy/paste the data from your web browser. Alternatively, you can contact your administrator to change the encoding (See parameter \'csv_file_default_charset\').~~',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interaktív Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit szabály',
	'UI:Audit:HeaderNbObjects' => '# Objektumok',
	'UI:Audit:HeaderNbErrors' => '# Hibák',
	'UI:Audit:PercentageOk' => '% OK',
	'UI:Audit:OqlError' => 'OQL Error~~',
	'UI:Audit:Error:ValueNA' => 'n/a~~',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule~~',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in the Rule %1$s: %2$s.~~',
	'UI:Audit:ErrorIn_Category' => 'Error in Category~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in the Category %1$s: %2$s.~~',
	'UI:Audit:AuditErrors' => 'Audit Errors~~',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited~~',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors~~',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated~~',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%~~',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - OQL lekérdezés értékelés',
	'UI:RunQuery:QueryExamples' => 'Lekérdezés példák',
	'UI:RunQuery:QueryResults' => 'Query Results~~',
	'UI:RunQuery:HeaderPurpose' => 'Cél',
	'UI:RunQuery:HeaderPurpose+' => '',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL kifejezés',
	'UI:RunQuery:HeaderOQLExpression+' => '',
	'UI:RunQuery:ExpressionToEvaluate' => 'Értékelendő kifejezés: ',
	'UI:RunQuery:QueryArguments' => 'Query Arguments~~',
	'UI:RunQuery:MoreInfo' => 'Több információ a lekérdezésről: ',
	'UI:RunQuery:DevelopedQuery' => 'Újraírt lekérdező értékelés: ',
	'UI:RunQuery:SerializedFilter' => 'Szerializált szűrő: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'A lekérdezés futtatása közben a következő hiba jelentkezett',
	'UI:Query:UrlForExcel' => 'URL to use for MS-Excel web queries~~',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of '.ITOP_APPLICATION_SHORT.'. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
	'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' objektum séma',
	'UI:Schema:TitleForClass' => '%1$s séma~~',
	'UI:Schema:CategoryMenuItem' => '<b>%1$s</b> kategória',
	'UI:Schema:Relationships' => 'Kapcsolatok',
	'UI:Schema:AbstractClass' => 'Absztrakt osztály: nem példányosítható belőle objektum.',
	'UI:Schema:NonAbstractClass' => 'Nem absztrakt osztály: objektum példányosítható belőle.',
	'UI:Schema:ClassHierarchyTitle' => 'Osztály hierarchia',
	'UI:Schema:AllClasses' => 'Összes osztály',
	'UI:Schema:ExternalKey_To' => 'Külső kulcs %1$s-hoz',
	'UI:Schema:Columns_Description' => 'Oszlopok: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Alapértelmezett: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null érték engedélyezett',
	'UI:Schema:NullNotAllowed' => 'Null érték nem engedélyezett',
	'UI:Schema:Attributes' => 'Attribútumok',
	'UI:Schema:AttributeCode' => 'Attribútum kód',
	'UI:Schema:AttributeCode+' => '',
	'UI:Schema:Label' => 'Cimke',
	'UI:Schema:Label+' => '',
	'UI:Schema:Type' => 'Típus',

	'UI:Schema:Type+' => '',
	'UI:Schema:Origin' => 'Származás',
	'UI:Schema:Origin+' => '',
	'UI:Schema:Description' => 'Leírás',
	'UI:Schema:Description+' => '',
	'UI:Schema:AllowedValues' => 'Engedélyezett értékek',
	'UI:Schema:AllowedValues+' => '',
	'UI:Schema:MoreInfo' => 'Több információ',
	'UI:Schema:MoreInfo+' => '',
	'UI:Schema:SearchCriteria' => 'Keresési kritériumok',
	'UI:Schema:FilterCode' => 'Szűrő kód',
	'UI:Schema:FilterCode+' => '',
	'UI:Schema:FilterDescription' => 'Leírás',
	'UI:Schema:FilterDescription+' => '',
	'UI:Schema:AvailOperators' => 'Elérhető műveletek',
	'UI:Schema:AvailOperators+' => '',
	'UI:Schema:ChildClasses' => 'Leszármazott osztályok',
	'UI:Schema:ReferencingClasses' => 'Referált osztályok',
	'UI:Schema:RelatedClasses' => 'Kapcsolódó osztályok',
	'UI:Schema:LifeCycle' => 'Életciklus',
	'UI:Schema:Triggers' => 'Kiváltó okok',
	'UI:Schema:Relation_Code_Description' => 'Kapcsolat <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Lenn: %1$s',
	'UI:Schema:RelationUp_Description' => 'Fenn: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: kiterjesztése %2$d szintre, lekérdezés: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: nincs kiterjesztve (%2$d szintekre), lekérdezés: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s hivatkozva %2$s az osztályban %3$s mezőn keresztül',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s hozzácsatolva %2$s-hoz %3$s-n keresztül::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'A következő osztályok mutatnak %1$s-ra (1:n kapcsolat):',
	'UI:Schema:Links:n-n' => 'A következő osztályok mutatnak %1$s-ra (n:n kapcsolat):',
	'UI:Schema:Links:All' => 'Össze kapcsolódó osztály grafikonja',
	'UI:Schema:NoLifeCyle' => 'Nincs életciklus rendelve ehhez az osztályhoz.',
	'UI:Schema:LifeCycleTransitions' => 'Átmenetek',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribútum opciók',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Rejtett',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Csak olvasható',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Kötelező',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Változtatni kell',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Felhasználó kéri a változtatását',
	'UI:Schema:LifeCycleEmptyList' => 'üres lista',
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => '',
	'UI:Edit:SearchQuery' => 'Select a predefined query~~',
	'UI:Edit:TestQuery' => 'Test query~~',
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
	'UI:Message:EmptyList:UseSearchForm' => 'Használja a kereső formot a hozzáadandó objektumok kiválasztásához.',
	'UI:Wizard:FinalStepTitle' => 'Utolsó lépés: megerősítés',
	'UI:Title:DeletionOf_Object' => '%1$s törlése',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '%2$s osztály %1$d objektumának tömeges törlése',
	'UI:Delete:NotAllowedToDelete' => 'Nem enegedélyezett az objektum törlése',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'A következő mező módosítása nem engedélyezett: %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Az objektum nem törölhető, mert a felhasználónak nincs elegendő joga',
	'UI:Error:CannotDeleteBecause' => 'Az objektum nem törölhető, mert: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Az objektum nem törölhető, mert néhány hozzá kapcsolódó magasabb prioritású manuális művelet végrehajtásra vár',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Az objektum nem törölhető, mert néhány hozzá kapcsolódó magasabb prioritású manuális művelet végrehajtásra vár',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s felhasználó %2$s nevében',
	'UI:Delete:Deleted' => 'törölt',
	'UI:Delete:AutomaticallyDeleted' => 'automatikusan törölt',
	'UI:Delete:AutomaticResetOf_Fields' => ' következő mezők automatikus újratöltése: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Összes referencia tisztítása %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s osztály %1$d objektumára mutató referenciák tisztítása',
	'UI:Delete:Done+' => '',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s törölve.',
	'UI:Delete:ConfirmDeletionOf_Name' => '%1$s törlése',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$s osztály %1$d objektumának törlése',
	'UI:Delete:CannotDeleteBecause' => 'Sikertelenül töröltek: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Automatikusan kellett volna törlődniük, de a művelet nem volt végrehajtható: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Manuállis törlés nem végrehajtható: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Automatikusan lesznek törölve',
	'UI:Delete:MustBeDeletedManually' => 'Manuálisan törlendők',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Automatikus frissítés sikeretelen: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Automatikusan lesznek frissítve (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objektumok / kapcsolatok hivatkoznak erre: %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objektumok / kapcsolatok hivatkoznak törlendő objektumokra',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Adatbázis integritás ellenőrzés szükséges. Néhány hivatkozás megszüntetésre kerül.',
	'UI:Delete:Consequence+' => '',
	'UI:Delete:SorryDeletionNotAllowed' => 'Az objektum törlése nem engedélyezett. Részletes magyarázat a következő sorokban.',
	'UI:Delete:PleaseDoTheManualOperations' => 'Kérem hajtsa végre a következő listában található műveleteket manuálisan az objektum törlésének kéréséhez',
	'UI:Delect:Confirm_Object' => 'Kérjük hagyja jóvá a %1$s törlését!',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Kérjük hagyja jóvá a %2$s ostály %1$d objektumának törlését!',
	'UI:WelcomeToITop' => 'Üdvözli az '.ITOP_APPLICATION_SHORT,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s részletek',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Hiba',
	'UI:ObjectDoesNotExist' => 'Sajnálom ez az objektum nem létezik (vagy a megtekintése nem engedélyezett a felhasználó számára).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Keresés eredményei',
	'UI:SearchResultsTitle' => 'Keresés eredményei',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => 'Nincs keresés',
	'UI:Search:NeedleTooShort' => 'The search string \\"%1$s\\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \\"%1$s\\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => '"%1$s" keresés eredményei:',
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
	'UI:ObjectCouldNotBeWritten' => 'Au objektum írása sikertlen: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Fatális hiba',
	'UI:SystemIntrusion' => 'Hozzáférés megtagadva. A művelet végrehajtása nem engedélyezett.',
	'UI:FatalErrorMessage' => 'Fatális hiba, '.ITOP_APPLICATION_SHORT.' nem tudja a működését folytatni',
	'UI:Error_Details' => 'Hiba: %1$s.',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' felhasználó menedzsmet - profil nézet',
	'UI:UserManagement:Class' => 'Osztály',
	'UI:UserManagement:Class+' => '',
	'UI:UserManagement:ProjectedObject' => 'Objektum',
	'UI:UserManagement:ProjectedObject+' => '',
	'UI:UserManagement:AnyObject' => '* any *',
	'UI:UserManagement:User' => 'Felhasználó',
	'UI:UserManagement:User+' => '',
	'UI:UserManagement:Action:Read' => 'Olvas',
	'UI:UserManagement:Action:Read+' => '',
	'UI:UserManagement:Action:Modify' => 'Módosít',
	'UI:UserManagement:Action:Modify+' => '',
	'UI:UserManagement:Action:Delete' => 'Töröl',
	'UI:UserManagement:Action:Delete+' => '',
	'UI:UserManagement:Action:BulkRead' => 'Tömeges beolvasás (Export)',
	'UI:UserManagement:Action:BulkRead+' => '',
	'UI:UserManagement:Action:BulkModify' => 'Tömeges módosítás',
	'UI:UserManagement:Action:BulkModify+' => '',
	'UI:UserManagement:Action:BulkDelete' => 'Tömeges törlés',
	'UI:UserManagement:Action:BulkDelete+' => '',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => '',
	'UI:UserManagement:Action' => 'Akció',
	'UI:UserManagement:Action+' => '',
	'UI:UserManagement:TitleActions' => 'Akciók',
	'UI:UserManagement:Permission' => 'Engedély',
	'UI:UserManagement:Permission+' => '',
	'UI:UserManagement:Attributes' => 'Attribútumok',
	'UI:UserManagement:ActionAllowed:Yes' => 'Igen',
	'UI:UserManagement:ActionAllowed:No' => 'Nem',
	'UI:UserManagement:AdminProfile+' => '',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '',
	'UI:UserManagement:GrantMatrix' => 'Jogosutlsági mátrix',

	'Menu:AdminTools' => 'Adminisztrációs eszközök',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Eszközök csak az adminisztrátori profilhoz rendlet felhasználók számára elérhetők.',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Változás menedzsment',
	'UI:ChangeManagementMenu+' => '',
	'UI:ChangeManagementMenu:Title' => 'Változás áttekintése',
	'UI-ChangeManagementMenu-ChangesByType' => 'Változások típusok szerint',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Változások státusz szerint',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Még nem kiosztott változások',

	'UI:ConfigurationManagementMenu' => 'Konfiguráció menedzsment',
	'UI:ConfigurationManagementMenu+' => '',
	'UI:ConfigurationManagementMenu:Title' => 'Infrastruktúra áttekintő',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastruktúra objetumok típusok szerint',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastruktúra objetumok státuszok szerint',

	'UI:ConfigMgmtMenuOverview:Title' => 'Konfiguráció menedzsment dashboard',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Konfigurációs elemek státusz szerint',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Konfigurációs elemek típus szerint',

	'UI:RequestMgmtMenuOverview:Title' => 'Igény menedzsment dashboard',
	'UI-RequestManagementOverview-RequestByService' => 'Felhasználói kérések szolgáltatásonként',
	'UI-RequestManagementOverview-RequestByPriority' => 'Felhasználói kérések prioritás szerint',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Felhasználói kérések, amelyek még nem lettek felelőshöz rendelve',

	'UI:IncidentMgmtMenuOverview:Title' => 'Incidens menedzsment dashboard',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidensek szolgáltatásonként',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidensek prioritás szerint',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidensek, amelyek még nem lettek felelőshöz rendelve',

	'UI:ChangeMgmtMenuOverview:Title' => 'Változás menedzsment dashboard',
	'UI-ChangeManagementOverview-ChangeByType' => 'Változások típusonként',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Változások, amelyek még nem lettek felelőshöz rendelve',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Változások által okozott leállási idő',

	'UI:ServiceMgmtMenuOverview:Title' => 'Szolgáltatás menedszment dashboard',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'A következő 30 napban lejáró ügyfél szerződések',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'A következő 30 napban lejáró szállítói szerződések',

	'UI:ContactsMenu' => 'Kapcsolattartók',
	'UI:ContactsMenu+' => '',
	'UI:ContactsMenu:Title' => 'Kapcsolattartó áttekintő',
	'UI-ContactsMenu-ContactsByLocation' => 'Kapcsolattartók földrajzi hely szerint',
	'UI-ContactsMenu-ContactsByType' => 'Kapcsolattartók típus szerint',
	'UI-ContactsMenu-ContactsByStatus' => 'Kapcsolattartók státusz szerint',

	'Menu:CSVImportMenu' => 'CSV import',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Adatmodell',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Értesítések',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Értesítések beállítása',
	'UI:NotificationsMenu:Help' => 'Segítség',
	'UI:NotificationsMenu:HelpContent' => '<p>Az '.ITOP_APPLICATION_SHORT.' alkalmazásban az értesítések teljesen testreszabhatók. Értesítések az objektumok két csoportjára épülnek: <i>kiváltó okok és akciók</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Akciók</b></i> define the actions to be performed when the triggers execute. For now there are only two kind of actions:
<ol>
	<li>Sending an email message: Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.<br />
	Speciális oldal: <a href="../setup/email.test.php" target="_blank">email.test.php</a> oldalon keresztül a PHP mail konfiguráció tesztelhető.</li>
	<li>Outgoing webhooks: Allow integration with a third-party application by sending structured data to a defined URL.</li>
</ol>
</p>
<p>Akció végrehjatásához azt kiváltó okhoz kell rendelni.
Akció kiváltó okhoz rendelésekor kap egy sorszámot , amely meghatározza az akciók végrehatási sorrendjét.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Kiváltó okok',
	'UI:NotificationsMenu:AvailableTriggers' => 'Lehetséges kiváltó okok',
	'UI:NotificationsMenu:OnCreate' => 'Objektum létrehozás',
	'UI:NotificationsMenu:OnStateEnter' => 'Objketum állapotba való belépése',
	'UI:NotificationsMenu:OnStateLeave' => 'Objektum állotból való kilépése',
	'UI:NotificationsMenu:Actions' => 'Akciók',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Email actions~~',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook actions (outgoing integrations)~~',
	'UI:NotificationsMenu:Actions:Action' => 'Other actions~~',
	'UI:NotificationsMenu:AvailableActions' => 'Lehetséges akciók',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Audit kategóriák',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Audit kategóriák',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Lekérdezés futtatás',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query phrasebook~~',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Query phrasebook~~',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Adat adminisztráció',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Univerzális keresés',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Felhasználó menedzsment',// Duplicated into itop-welcome-itil (will be removed from here...)
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

	'UI:OpenDocumentInNewWindow_' => 'Megnyitásához~~',
	'UI:DownloadDocument_' => 'Letöltés~~',
	'UI:Document:NoPreview' => 'Nem elérhető előnézet ehhez a dokuemntumhoz',
	'UI:Download-CSV' => 'Download %1$s~~',

	'UI:DeadlineMissedBy_duration' => 'Elmulsztva %1$s által',
	'UI:Deadline_LessThan1Min' => '< 1 perc',
	'UI:Deadline_Minutes' => '%1$d perc',
	'UI:Deadline_Hours_Minutes' => '%1$dóra %2$dperc',
	'UI:Deadline_Days_Hours_Minutes' => '%1$nap %2$dóra %3$dperc',
	'UI:Help' => 'Segítség',
	'UI:PasswordConfirm' => 'Jóváhagyás',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => '%1$s objektumok hozzáadása előtt mentse ezt az  objektumot',
	'UI:DisplayThisMessageAtStartup' => 'Az üzenet megjelenítése indításkor',
	'UI:RelationshipGraph' => 'Grafikus nézet',
	'UI:RelationshipList' => 'Lista',
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Művelet visszavonva',
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
	'Portal:Title' => ITOP_APPLICATION_SHORT.' felhasználói portál',
	'Portal:NoRequestMgmt' => 'Dear %1$s, you have been redirected to this page because your account is configured with the profile \'Portal user\'. Unfortunately, '.ITOP_APPLICATION_SHORT.' has not been installed with the feature \'Request Management\'. Please contact your administrator.~~',
	'Portal:Refresh' => 'Frissítés',
	'Portal:Back' => 'Vissza',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details for request~~',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => 'Új kérés létrehozása',
	'Portal:CreateNewRequestItil' => 'Új kérés létrehozása',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
	'Portal:ChangeMyPassword' => 'Jelszó változtatás',
	'Portal:Disconnect' => 'Kilépés',
	'Portal:OpenRequests' => 'Nyitott kéréseim',
	'Portal:ClosedRequests' => 'My closed requests',
	'Portal:ResolvedRequests' => 'Megoldott kéréseim',
	'Portal:SelectService' => 'Válasszon szolgáltatást a katalógusból:',
	'Portal:PleaseSelectOneService' => 'Kérem válasszon egy szolgáltatást',
	'Portal:SelectSubcategoryFrom_Service' => 'Válassza ki a %1$s szolgáltatás alkategóriáját:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Kérem válasszon egy alkategóriát',
	'Portal:DescriptionOfTheRequest' => 'Adja meg a kérés leírásást:',
	'Portal:TitleRequestDetailsFor_Request' => '%1$s kérés részletei:',
	'Portal:NoOpenRequest' => 'A kategóriához nem tartozik nyitott kérés.',
	'Portal:NoClosedRequest' => 'No request in this category',
	'Portal:Button:ReopenTicket' => 'Reopen this ticket',
	'Portal:Button:CloseTicket' => 'Hibajegy lezárása',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => 'Adjon megjegyzést a megoldáshoz:',
	'Portal:ErrorNoContactForThisUser' => 'Hiba: az aktuális felhasználó nem tartozik egyetlen Kapcsolattartóhoz / Szemályhez sem. Kérem vegye felk a kapcsolatot az adminisztrátorral.',
	'Portal:Attachments' => 'Csatolmányok',
	'Portal:AddAttachment' => 'Csatolmány hozzáadása',
	'Portal:RemoveAttachment' => 'Csatolmány eltávolítása',
	'Portal:Attachment_No_To_Ticket_Name' => 'Csatolmányok: #%1$d a %2$s (%3$s) hibajegyhez',
	'Portal:SelectRequestTemplate' => 'Select a template for %1$s~~',
	'Enum:Undefined' => 'Nem meghatározott',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s nap %2$s óra %3$s perc %4$s másodperc',
	'UI:ModifyAllPageTitle' => 'Összes módosítása',
	'UI:Modify_N_ObjectsOf_Class' => '%2$s osztály %1$d objketumainak módosítása',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => '%2$s osztály %1$d objektumának módosítása, kivéve: %3$d',
	'UI:Menu:ModifyAll' => 'Módosítás...',
	'UI:Button:ModifyAll' => 'Összes módosítása',
	'UI:Button:PreviewModifications' => 'Módosítások előnézete >>',
	'UI:ModifiedObject' => 'Objektum módosítva',
	'UI:BulkModifyStatus' => 'Művelet',
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
	'UI:AttemptingToSetASlaveAttribute_Name' => '%1$s mező nem írható, mert a szinkronizációnál használt kulcs. Érték nem lett beállítva.',
	'UI:ActionNotAllowed' => 'Ennek a műveletnek a végrehajtása nem engedélyezett ezen az objektumon.',
	'UI:BulkAction:NoObjectSelected' => 'Válasszon ki legalább egy objketumot a művelet végrehajtásához',
	'UI:AttemptingToChangeASlaveAttribute_Name' => '%1$s mező nem írható, mert a szinkronizációnál használt kulcs. Érték változatlan maradt.',
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
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default length:  %1$s items per page~~',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Any modification will be discarded.~~',
	'UI:CancelConfirmationMessage' => 'You will loose your changes. Continue anyway?~~',
	'UI:AutoApplyConfirmationMessage' => 'Some changes have not been applied yet. Do you want itop to take them into account?~~',
	'UI:Create_Class_InState' => 'Create the %1$s in state: ~~',
	'UI:OrderByHint_Values' => 'Sort order: %1$s~~',
	'UI:Menu:AddToDashboard' => 'Add To Dashboard...~~',
	'UI:Button:Refresh' => 'Frissítés',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:SwitchToStandardDashboard' => 'Switch to standard dashboard~~',
	'UI:Toggle:SwitchToCustomDashboard' => 'Switch to custom dashboard~~',

	'UI:ConfigureThisList' => 'Configure This List...~~',
	'UI:ListConfigurationTitle' => 'List Configuration~~',
	'UI:ColumnsAndSortOrder' => 'Columns and sort order:~~',
	'UI:UseDefaultSettings' => 'Use the Default Settings~~',
	'UI:UseSpecificSettings' => 'Use the Following Settings:~~',
	'UI:Display_X_ItemsPerPage_prefix' => 'Display~~',
	'UI:Display_X_ItemsPerPage_suffix' => 'items per page~~',
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

	'UI:Dashboard:EditCustom' => 'Edit custom version...~~',
	'UI:Dashboard:CreateCustom' => 'Create a custom version...~~',
	'UI:Dashboard:DeleteCustom' => 'Delete custom version...~~',
	'UI:Dashboard:RevertConfirm' => 'Every changes made to the original version will be lost. Please confirm that you want to do this.~~',
	'UI:ExportDashBoard' => 'Export to a file~~',
	'UI:ImportDashBoard' => 'Import from a file...~~',
	'UI:ImportDashboardTitle' => 'Import From a File~~',
	'UI:ImportDashboardText' => 'Select a dashboard file to import:~~',
	'UI:Dashboard:Actions' => 'Dashboard actions~~',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'This dashboard displays information that does not include the on-going changes.~~',


	'UI:DashletCreation:Title' => 'Create a new Dashlet~~',
	'UI:DashletCreation:Dashboard' => 'Dashboard~~',
	'UI:DashletCreation:DashletType' => 'Dashlet Type~~',
	'UI:DashletCreation:EditNow' => 'Edit the Dashboard~~',

	'UI:DashboardEdit:Title' => 'Dashboard Editor~~',
	'UI:DashboardEdit:DashboardTitle' => 'Title~~',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',
	'UI:DashboardEdit:Revert' => 'Revert~~',
	'UI:DashboardEdit:Apply' => 'Apply~~',

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
	'Calendar-FirstDayOfWeek' => '0~~',// 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Create a Shortcut...~~',
	'UI:ShortcutRenameDlg:Title' => 'Rename the shortcut~~',
	'UI:ShortcutListDlg:Title' => 'Create a shortcut for the list~~',
	'UI:ShortcutDelete:Confirm' => 'Please confirm that wou wish to delete the shortcut(s).~~',
	'Menu:MyShortcuts' => 'My Shortcuts~~',// Duplicated into itop-welcome-itil (will be removed from here...)
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

	'UI:AboutBox' => 'About '.ITOP_APPLICATION_SHORT.'...~~',
	'UI:About:Title' => 'About '.ITOP_APPLICATION_SHORT.'~~',
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
	'portal:backoffice' => ITOP_APPLICATION_SHORT.' Back-Office User Interface~~',

	'UI:CurrentObjectIsLockedBy_User' => 'The object is locked since it is currently being modified by %1$s.~~',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'The object is currently being modified by %1$s. Your modifications cannot be submitted since they would be overwritten.~~',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'The object is currently being modified by %1$s. You\'ll be able to submit your modifications once they have finished.~~',
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
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
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
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


Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:DataSources' => 'Szinkronizált adatforrások',
	'Menu:DataSources+' => '',
	'Menu:WelcomeMenu' => 'Üdvözlöm',
	'Menu:WelcomeMenu+' => '',
	'Menu:WelcomeMenuPage' => 'Üdvözlöm',
	'Menu:WelcomeMenuPage+' => '',
	'Menu:AdminTools' => 'Adminisztrációs eszközök',
	'Menu:AdminTools+' => '',
	'Menu:AdminTools?' => 'Eszközök csak az adminisztrátori profilhoz rendlet felhasználók számára elérhetők.',
	'Menu:DataModelMenu' => 'Adatmodell',
	'Menu:DataModelMenu+' => '',
	'Menu:ExportMenu' => 'Export',
	'Menu:ExportMenu+' => '',
	'Menu:NotificationsMenu' => 'Értesítések',
	'Menu:NotificationsMenu+' => '',
	'Menu:AuditCategories' => 'Audit kategóriák',
	'Menu:AuditCategories+' => '',
	'Menu:Notifications:Title' => 'Audit kategóriák',
	'Menu:RunQueriesMenu' => 'Lekérdezés futtatás',
	'Menu:RunQueriesMenu+' => '',
	'Menu:QueryMenu' => 'Query phrasebook~~',
	'Menu:QueryMenu+' => 'Query phrasebook~~',
	'Menu:UniversalSearchMenu' => 'Univerzális keresés',
	'Menu:UniversalSearchMenu+' => '',
	'Menu:UserManagementMenu' => 'Felhasználó menedzsment',
	'Menu:UserManagementMenu+' => '',
	'Menu:ProfilesMenu' => 'Profilok',
	'Menu:ProfilesMenu+' => '',
	'Menu:ProfilesMenu:Title' => 'Profilok',
	'Menu:UserAccountsMenu' => 'Felhasználói fiókok',
	'Menu:UserAccountsMenu+' => '',
	'Menu:UserAccountsMenu:Title' => 'Felhasználói fiókok',
	'Menu:MyShortcuts' => 'My Shortcuts~~',
	'Menu:UserManagement' => 'User Management~~',
	'Menu:Queries' => 'Queries~~',
	'Menu:ConfigurationTools' => 'Configuration~~',
));

// Additional language entries not present in English dict
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
 'UI:Toggle:StandardDashboard' => 'Standard~~',
 'UI:Toggle:CustomDashboard' => 'Custom~~',
 'UI:Display_X_ItemsPerPage' => 'Display %1$s items per page~~',
 'UI:Dashboard:Edit' => 'Edit This Page...~~',
 'UI:Dashboard:Revert' => 'Revert To Original Version...~~',
));
