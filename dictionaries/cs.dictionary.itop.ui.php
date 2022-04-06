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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:AuditCategory' => 'Kategorie auditu',
	'Class:AuditCategory+' => 'Část celkového auditu',
	'Class:AuditCategory/Attribute:name' => 'Název kategorie',
	'Class:AuditCategory/Attribute:name+' => 'Krátký název pro tuto kategorii',
	'Class:AuditCategory/Attribute:description' => 'Popis kategorie',
	'Class:AuditCategory/Attribute:description+' => 'Dlouhý popis této kategorie auditu',
	'Class:AuditCategory/Attribute:definition_set' => 'Definice množiny',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL výraz definující množinu objektů pro audit',
	'Class:AuditCategory/Attribute:rules_list' => 'Pravidla pro audit',
	'Class:AuditCategory/Attribute:rules_list+' => 'Pravidla pro tuto kategorii auditu',
));

//
// Class: AuditRule
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:AuditRule' => 'Pravidlo auditu',
	'Class:AuditRule+' => 'Pravidlo pro kontrolu v dané kategorii auditu',
	'Class:AuditRule/Attribute:name' => 'Název pravidla',
	'Class:AuditRule/Attribute:name+' => 'Krátký název pro toto pravidlo',
	'Class:AuditRule/Attribute:description' => 'Popis pravidla',
	'Class:AuditRule/Attribute:description+' => 'Dlouhý popis tohoto pravidla auditu',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => 'Dotaz ke spuštění',
	'Class:AuditRule/Attribute:query+' => 'OQL výraz ke spuštění',
	'Class:AuditRule/Attribute:valid_flag' => 'Interpretace',
	'Class:AuditRule/Attribute:valid_flag+' => 'Jsou výsledkem dotazu platné prvky?',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Platné objekty',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Výsledkem dotazu jsou platné objekty',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Neplatné objekty',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Výsledkem dotazu jsou neplatné objekty',
	'Class:AuditRule/Attribute:category_id' => 'Kategorie',
	'Class:AuditRule/Attribute:category_id+' => 'Kategorie pro toto pravidlo',
	'Class:AuditRule/Attribute:category_name' => 'Kategorie',
	'Class:AuditRule/Attribute:category_name+' => 'Název kategorie pro toto pravidlo',
));

//
// Class: QueryOQL
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:Query' => 'Dotaz',
	'Class:Query+' => '',
	'Class:Query/Attribute:name' => 'Název',
	'Class:Query/Attribute:name+' => 'Název dotazu',
	'Class:Query/Attribute:description' => 'Popis',
	'Class:Query/Attribute:description+' => 'Dlouhý popis dotazu',
	'Class:Query/Attribute:is_template' => 'Template for OQL fields~~',
	'Class:Query/Attribute:is_template+' => 'Usable as source for recipient OQL in Notifications~~',
	'Class:Query/Attribute:is_template/Value:yes' => 'Yes~~',
	'Class:Query/Attribute:is_template/Value:no' => 'No~~',
	'Class:QueryOQL/Attribute:fields' => 'Atributy',
	'Class:QueryOQL/Attribute:fields+' => 'Seznam atributů oddělených čárkami',
	'Class:QueryOQL' => 'OQL dotaz',
	'Class:QueryOQL+' => '',
	'Class:QueryOQL/Attribute:oql' => 'Výraz',
	'Class:QueryOQL/Attribute:oql+' => 'OQL výraz',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:User' => 'Uživatel',
	'Class:User+' => 'Uživatelské jméno',
	'Class:User/Attribute:finalclass' => 'Typ účtu',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Kontakt (osoba)',
	'Class:User/Attribute:contactid+' => 'Osobní údaje',
	'Class:User/Attribute:org_id' => 'Organizace',
	'Class:User/Attribute:org_id+' => 'Přístupná organizace',
	'Class:User/Attribute:last_name' => 'Příjmení',
	'Class:User/Attribute:last_name+' => '',
	'Class:User/Attribute:first_name' => 'Jméno',
	'Class:User/Attribute:first_name+' => '',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => '',
	'Class:User/Attribute:login' => 'Přihlašovací jméno',
	'Class:User/Attribute:login+' => '',
	'Class:User/Attribute:language' => 'Jazyk',
	'Class:User/Attribute:language+' => '',
	'Class:User/Attribute:language/Value:EN US' => 'English',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'French',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profily/role',
	'Class:User/Attribute:profile_list+' => '',
	'Class:User/Attribute:allowed_org_list' => 'Přístupné organizace',
	'Class:User/Attribute:allowed_org_list+' => 'Uživatel má oprávnění přistupovat k údajům následujících organizací. Pokud není zvolena žádná organizace, neuplatňují se žádná omezení.',
	'Class:User/Attribute:status' => 'Stav',
	'Class:User/Attribute:status+' => '',
	'Class:User/Attribute:status/Value:enabled' => 'Aktivní',
	'Class:User/Attribute:status/Value:disabled' => 'Neaktivní',

	'Class:User/Error:LoginMustBeUnique' => 'Uživatelské jméno musí být jedinečné - "%1s" je již použito.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Uživateli musí být přidělen alespoň jeden profil.',
	'Class:User/Error:ProfileNotAllowed' => 'Profile "%1$s" cannot be added it will deny the access to backoffice~~',
	'Class:User/Error:StatusChangeIsNotAllowed' => 'Changing status is not allowed for your own User~~',
	'Class:User/Error:AllowedOrgsMustContainUserOrg' => 'Allowed organizations must contain User organization~~',
	'Class:User/Error:CurrentProfilesHaveInsufficientRights' => 'The current list of profiles does not give sufficient access rights (Users are not modifiable anymore)~~',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'At least one organization must be assigned to this user.~~',
	'Class:User/Error:OrganizationNotAllowed' => 'Organization not allowed.~~',
	'Class:User/Error:UserOrganizationNotAllowed' => 'The user account does not belong to your allowed organizations.~~',
	'Class:User/Error:PersonIsMandatory' => 'The Contact is mandatory.~~',
	'Class:UserInternal' => 'Interní uživatel',
	'Class:UserInternal+' => 'Uživatel definovaný v '.ITOP_APPLICATION_SHORT,
));

//
// Class: URP_Profiles
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_Profiles' => 'Profil (role)',
	'Class:URP_Profiles+' => 'Uživatelský profil (role)',
	'Class:URP_Profiles/Attribute:name' => 'Název',
	'Class:URP_Profiles/Attribute:name+' => 'Označení',
	'Class:URP_Profiles/Attribute:description' => 'Popis',
	'Class:URP_Profiles/Attribute:description+' => 'Krátký popis',
	'Class:URP_Profiles/Attribute:user_list' => 'Uživatelé',
	'Class:URP_Profiles/Attribute:user_list+' => 'Uživatelé mající tento profil (roli)',
));

//
// Class: URP_Dimensions
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_Dimensions' => 'Rozměry',
	'Class:URP_Dimensions+' => 'Rozměry aplikace (defining silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Jméno',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Popis',
	'Class:URP_Dimensions/Attribute:description+' => '',
	'Class:URP_Dimensions/Attribute:type' => 'Typ',
	'Class:URP_Dimensions/Attribute:type+' => 'Název třídy nebo typu dat (projekční jednotka)',
));

//
// Class: URP_UserProfile
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_UserProfile' => 'Uživatel/Profil',
	'Class:URP_UserProfile+' => '',
	'Class:URP_UserProfile/Name' => 'Spojení mezi uživatelem %1$s a profilem %2$s',
	'Class:URP_UserProfile/Attribute:userid' => 'Uživatel',
	'Class:URP_UserProfile/Attribute:userid+' => '',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Přihlašovací jméno',
	'Class:URP_UserProfile/Attribute:userlogin+' => '',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => '',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => '',
	'Class:URP_UserProfile/Attribute:reason' => 'Důvod',
	'Class:URP_UserProfile/Attribute:reason+' => 'proč má uživatel tento profil',
));

//
// Class: URP_UserOrg
//


Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_UserOrg' => 'Přístupné organizace',
	'Class:URP_UserOrg+' => '',
	'Class:URP_UserOrg/Name' => 'Spojení mezi uživatelem %1$s a organizací %2$s',
	'Class:URP_UserOrg/Attribute:userid' => 'Uživatel',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Přihlašovací jméno',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizace',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Přístupná organizace',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organizace',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Přístupná organizace',
	'Class:URP_UserOrg/Attribute:reason' => 'Důvod',
	'Class:URP_UserOrg/Attribute:reason+' => 'proč má uživatel oprávnění přistupovat k údajům této organizace',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profile name',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (using $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'permissions on classes',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => '',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => '',
	'Class:URP_ActionGrant/Attribute:class' => 'Třída',
	'Class:URP_ActionGrant/Attribute:class+' => '',
	'Class:URP_ActionGrant/Attribute:permission' => 'Oprávnění',
	'Class:URP_ActionGrant/Attribute:permission+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'ano',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'ano',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'ne',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'ne',
	'Class:URP_ActionGrant/Attribute:action' => 'Akce',
	'Class:URP_ActionGrant/Attribute:action+' => 'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => '',
	'Class:URP_StimulusGrant/Attribute:class' => 'Třída',
	'Class:URP_StimulusGrant/Attribute:class+' => '',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Oprávnění',
	'Class:URP_StimulusGrant/Attribute:permission+' => '',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'ano',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'ano',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'ne',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'ne',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'permissions at the attributes level',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Action grant',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribute',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribute code',
));

//
// Class: UserDashboard
//
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'BooleanLabel:yes' => 'ano',
	'BooleanLabel:no' => 'ne',
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'Menu:WelcomeMenu' => 'Vítejte',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Vítejte v '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Vítejte',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Vítejte v '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Vítejte v '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' je komplexní „opensource” provozní IT portál.</p>
<ul>Obsahuje:
<li>Kompletní CMDB (databázi pro správu konfigurací) sloužící pro dokumentování a správu evidovaného IT.</li>
<li>Modul pro řízení rizik umožňující sledovat veškeré problémy, které se vyskytly v souvislosti s provozem IT.</li>
<li>Modul pro řízení změn, který slouží k plánování a sledování změn v IT prostředí.</li>
<li>Databázi známých chyb, díky které lze urychlit řešení incidentů.</li>
<li>Modul pro správu výpadků umožňující nejen dokumentovat plánované výpadky, ale také informovat příslušné adresáty.</li>
<li>Úvodní obrazovku poskytující rychlý a aktuální přehled o Vašem IT.</li>
</ul>
<p>Každý modul může být nastaven "krok za krokem" nezávisle na ostatních modulech.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' je servisně orientovaný produkt, který umožňuje správcům IT velmi jednoduše spravovat více zákazníků nebo organizací.
<ul>iTop přináší mnoho dalších výhod umožňujících "optimální" nastavení podnikových procesů které:
<li>Zvyšují účinnost řízení IT.</li>
<li>Efektivně řídí operace prováděné nad IT infrastrukturou.</li>
<li>Zvyšují spokojenost zákazníků a poskytují vedoucím pracovníkům ucelený pohled na výkonnost organizace.</li>
</ul>
</p>
<p>iTop je zcela otevřený a umožňuje bezproblémovou integraci s Vaším současným IT systémem pro správu infrastruktury.</p>
<p>
<ul>Zavedení nové generace provozního IT portálu Vám pomůže:
<li>Lépe řídit stále více a více komplexní IT prostředí.</li>
<li>Implementovat ITIL procesy svým vlastním tempem.</li>
<li>Spravovat Vaše nejdůležitější IT aktivum - Dokumentaci.</li>
</ul>
</p>',
	'UI:WelcomeMenu:Text'=> '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>

<div>This version features a brand new modern and accessible backoffice design.</div>

<div>We kept '.ITOP_APPLICATION.' core functions that you liked and modernized them to make you love them.
We hope you’ll enjoy this version as much as we enjoyed imagining and creating it.</div>

<div>Customize your '.ITOP_APPLICATION.' preferences for a personalized experience.</div>~~',
	'UI:WelcomeMenu:AllOpenRequests' => 'Otevřené požadavky: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mé požadavky',
	'UI:WelcomeMenu:OpenIncidents' => 'Otevřené incidenty: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Konfigurační položky: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidenty přidělené mně',
	'UI:AllOrganizations' => ' Všechny organizace ',
	'UI:YourSearch' => 'hledat',
	'UI:LoggedAsMessage' => 'Přihlášen - %1$s (%2$s)~~',
	'UI:LoggedAsMessage+Admin' => 'Přihlášen - %1$s (%2$s, Administrátor)~~',
	'UI:Button:Logoff' => 'Odhlásit',
	'UI:Button:GlobalSearch' => 'Hledat',
	'UI:Button:Search' => ' Hledat ',
	'UI:Button:Clear' => ' Clear ~~',
	'UI:Button:SearchInHierarchy' => 'Search in hierarchy~~',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Uložit',
	'UI:Button:SaveAnd' => 'Save and %1$s~~',
	'UI:Button:Cancel' => 'Zrušit',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Použít',
	'UI:Button:Send' => 'Send~~',
	'UI:Button:SendAnd' => 'Send and %1$s~~',
	'UI:Button:Back' => ' << Zpět ',
	'UI:Button:Restart' => ' |<< Začít znovu ',
	'UI:Button:Next' => ' Další >> ',
	'UI:Button:Finish' => ' Dokončit ',
	'UI:Button:DoImport' => ' Importovat ! ',
	'UI:Button:Done' => ' Hotovo ',
	'UI:Button:SimulateImport' => ' Simulovat import ',
	'UI:Button:Test' => 'Testovat!',
	'UI:Button:Evaluate' => ' Vyhodnotit ',
	'UI:Button:Evaluate:Title' => ' Vyhodnotit (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Přidat... ',
	'UI:Button:BrowseObjects' => ' Procházet... ',
	'UI:Button:Add' => ' Přidat ',
	'UI:Button:AddToList' => ' << Přidat ',
	'UI:Button:RemoveFromList' => ' Odebrat >> ',
	'UI:Button:FilterList' => ' Filtrovat... ',
	'UI:Button:Create' => ' Vytvořit ',
	'UI:Button:Delete' => ' Odstranit ! ',
	'UI:Button:Rename' => ' Přejmenovat... ',
	'UI:Button:ChangePassword' => ' Změnit heslo ',
	'UI:Button:ResetPassword' => ' Obnovit heslo ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',
	'UI:UserPref:DoNotShowAgain' => 'Do not show again~~',
	'UI:InputFile:NoFileSelected' => 'No File Selected~~',
	'UI:InputFile:SelectFile' => 'Select a file~~',

	'UI:SearchToggle' => 'Hledání',
	'UI:ClickToCreateNew' => 'Nový objekt (%1$s)~~',
	'UI:SearchFor_Class' => 'Hledat objekty třídy %1$s',
	'UI:NoObjectToDisplay' => 'Žádný objekt k zobrazení.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parametr object_id je povinný, pokud je uveden parametr link_attr. Zkontrolujte definici šablony zobrazení.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parametr target_attr je povinný, pokud je uveden parametr link_attr. Zkontrolujte definici šablony zobrazení.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parametr group_by je povinný. Zkontrolujte definici šablony zobrazení.',
	'UI:Error:InvalidGroupByFields' => 'Neplatný seznam polí, podle kterých seskupit: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Chyba: nepodporovaný styl bloku: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Nesprávná definice vazby: třída objektů ke správě: %1$s nebyla nalezena jako externí klíč ve třídě %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objekt: %1$s:%2$d nebyl nalezen.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Chyba: Cyklický odkaz v závislostech, zkontrolujte datový model.',
	'UI:Error:UploadedFileTooBig' => 'Nahraný soubor je příliš velký. (Maximální povolená velikost je %1$s). Pro změnu tohoto limitu kontaktujte administrátora. (Parametry upload_max_filesize a post_max_size v konfiguraci PHP na serveru).',
	'UI:Error:UploadedFileTruncated.' => 'Nahraný soubor byl zkrácen!',
	'UI:Error:NoTmpDir' => 'Dočasný adresář není nastaven (tmp).',
	'UI:Error:CannotWriteToTmp_Dir' => 'Nemohu zapisovat do dočasného adresáře (tmp). upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Nahrávání zastaveno díky příponě. (Původní jméno souboru = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Nahrávání selhalo z neznámé příčiny. (Kód chyby = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Chyba: pro tuto operaci musí být uveden následující parametr: %1$s.',
	'UI:Error:2ParametersMissing' => 'Chyba: pro tuto operaci musí být uvedeny následující parametry: %1$s a %2$s.',
	'UI:Error:3ParametersMissing' => 'Chyba: pro tuto operaci musí být uvedeny následující parametry: %1$s, %2$s a %3$s.',
	'UI:Error:4ParametersMissing' => 'Chyba: pro tuto operaci musí být uvedeny následující parametry: %1$s, %2$s, %3$s a %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Chyba: nesprávný OQL dotaz: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Nastala chyba při provádění dotazu: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Chyba: objekt byl již aktualizován.',
	'UI:Error:ObjectCannotBeUpdated' => 'Chyba: objekt nemůže být aktualizován.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Chyba: objekt byl již odstraněn!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Nemáte oprávnění k hromadnému odstranění objektů třídy %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Nemáte oprávnění k odstranění objektů třídy %1$s',
	'UI:Error:ReadNotAllowedOn_Class' => 'You are not allowed to view objects of class %1$s~~',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Nemáte oprávnění k hromadné aktualizaci objektů třídy %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Chyba: objekt byl již naklonován!',
	'UI:Error:ObjectAlreadyCreated' => 'Chyba: objekt byl již vytvořen!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Chyba: neplatná operace "%1$s" na objektu %2$s ve stavu "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',
	'UI:Error:InvalidToken' => 'Error: the requested operation has already been performed (CSRF token not found)~~',

	'UI:GroupBy:Count' => 'Množství',
	'UI:GroupBy:Count+' => 'Množství prvků',
	'UI:CountOfObjects' => 'Počet objektů odpovídajícíh ktritériím: %1$d',
	'UI_CountOfObjectsShort' => '%1$d objektů.',
	'UI:NoObject_Class_ToDisplay' => 'Žádné objekty třídy %1$s k zobrazení',
	'UI:History:LastModified_On_By' => 'Poslední úprava %1$s (%2$s)',
	'UI:HistoryTab' => 'Historie',
	'UI:NotificationsTab' => 'Upozornění',
	'UI:History:BulkImports' => 'Historie',
	'UI:History:BulkImports+' => 'Seznam CSV importů (od nejnovějších)',
	'UI:History:BulkImportDetails' => 'Změny vyplývající z CSV importu ze dne %1$s (%2$s)',
	'UI:History:Date' => 'Datum',
	'UI:History:Date+' => 'Datum změny',
	'UI:History:User' => 'Uživatel',
	'UI:History:User+' => 'Uživatel, který změnu provedl',
	'UI:History:Changes' => 'Změny',
	'UI:History:Changes+' => 'Změny provedené na objektu',
	'UI:History:StatsCreations' => 'Vytvořených',
	'UI:History:StatsCreations+' => 'Počet vytvořených objektů',
	'UI:History:StatsModifs' => 'Upravených',
	'UI:History:StatsModifs+' => 'Počet upravených objektů',
	'UI:History:StatsDeletes' => 'Odstraněných',
	'UI:History:StatsDeletes+' => 'Počet odstraněných objektů',
	'UI:Loading' => 'Načítám...',
	'UI:Menu:Actions' => 'Akce',
	'UI:Menu:OtherActions' => 'Další akce',
	'UI:Menu:Transitions' => 'Transitions~~',
	'UI:Menu:OtherTransitions' => 'Other Transitions~~',
	'UI:Menu:New' => 'Nový...',
	'UI:Menu:Add' => 'Přidat...',
	'UI:Menu:Manage' => 'Spravovat...',
	'UI:Menu:EMail' => 'Email',
	'UI:Menu:CSVExport' => 'CSV export',
	'UI:Menu:Modify' => 'Upravit...',
	'UI:Menu:Delete' => 'Odstranit...',
	'UI:Menu:BulkDelete' => 'Odstranit...',
	'UI:UndefinedObject' => 'nedefinováno',
	'UI:Document:OpenInNewWindow:Download' => 'Otevřít v novém okně: %1$s, Stáhnout: %2$s',
	'UI:SplitDateTime-Date' => 'datum',
	'UI:SplitDateTime-Time' => 'čas',
	'UI:TruncatedResults' => 'zobrazeno %1$d objektů z %2$d',
	'UI:DisplayAll' => 'Zobrazit vše',
	'UI:CollapseList' => 'Sbalit',
	'UI:CountOfResults' => '%1$d objekt(ů)',
	'UI:ChangesLogTitle' => 'Seznam změn (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Seznam změn je prázdný',
	'UI:SearchFor_Class_Objects' => 'Hledat objekty třídy %1$s',
	'UI:OQLQueryBuilderTitle' => 'Tvůrce OQL dotazu',
	'UI:OQLQueryTab' => 'OQL dotaz',
	'UI:SimpleSearchTab' => 'Jednoduché hledání',
	'UI:Details+' => 'Podrobnosti',
	'UI:SearchValue:Any' => '* všechny *',
	'UI:SearchValue:Mixed' => '* smíšené *',
	'UI:SearchValue:NbSelected' => ' vybráno',
	'UI:SearchValue:CheckAll' => 'Vybrat vše',
	'UI:SearchValue:UncheckAll' => 'Zrušit výběr',
	'UI:SelectOne' => '-- zvolte jednu z možností --',
	'UI:Login:Welcome' => 'Vítejte v '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nesprávné uživatelské jméno nebo heslo. Zkuste to prosím znovu.',
	'UI:Login:IdentifyYourself' => 'Před pokračováním se prosím identifikujte.',
	'UI:Login:UserNamePrompt' => 'Uživatelské jméno',
	'UI:Login:PasswordPrompt' => 'Heslo',
	'UI:Login:ForgotPwd' => 'Zapomněli jste své heslo?',
	'UI:Login:ForgotPwdForm' => 'Zapomenuté heslo',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' vám může zaslat instrukce pro obnovení vašeho hesla.',
	'UI:Login:ResetPassword' => 'Zaslat nyní!',
	'UI:Login:ResetPwdFailed' => 'Chyba při odesílání emailu: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' není platné uživatelské jméno',
	'UI:ResetPwd-Error-NotPossible' => 'obnova hesla u externích účtů není možná.',
	'UI:ResetPwd-Error-FixedPwd' => 'obnova hesla u tohoto účtu není povolená.',
	'UI:ResetPwd-Error-NoContact' => 'účet není spojen s žádnou osobou.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'účet není spojen s osobou s uvedenou emailovou adresou. Kontaktujte administrátora.',
	'UI:ResetPwd-Error-NoEmail' => 'chybí emailová adresa. Kontaktujte administrátora.',
	'UI:ResetPwd-Error-Send' => 'technický problém při odesílání emailu. Kontaktujte administrátora.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Obnovení hesla pro '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody' => '<body><p>Vyžádali jste obovení hesla pro '.ITOP_APPLICATION_SHORT.'.</p><p>Pokračujte kliknutím na následující <a href="%1$s">jednorázový odkaz</a> a zadejte nové heslo.</p>',

	'UI:ResetPwd-Title' => 'Obnovení hesla',
	'UI:ResetPwd-Error-InvalidToken' => 'Omlouváme se, ale heslo již bylo obnoveno nebo jste obdrželi více emailů. Ujistěte se, že používate odkaz z posledního emailu který jste obdrželi.',
	'UI:ResetPwd-Error-EnterPassword' => 'Vložte nové heslo k účtu \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Heslo bylo obnoveno.',
	'UI:ResetPwd-Login' => 'Pro přihlášení klikněte zde...',

	'UI:Login:About'                               => '',
	'UI:Login:ChangeYourPassword'                  => 'Změnit heslo',
	'UI:Login:OldPasswordPrompt'                   => 'Původní heslo',
	'UI:Login:NewPasswordPrompt'                   => 'Nové heslo',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Znovu nové heslo',
	'UI:Login:IncorrectOldPassword'                => 'Chyba: původní heslo je nesprávné',
	'UI:LogOffMenu'                                => 'Odhlásit',
	'UI:LogOff:ThankYou' => 'Děkujeme za užívání '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Klikněte zde pro nové přihlášení...',
	'UI:ChangePwdMenu'                             => 'Změnit heslo',
	'UI:Login:PasswordChanged'                     => 'Heslo nastaveno úspěšně!',
	'UI:AccessRO-All' => ITOP_APPLICATION_SHORT.' je pouze ke čtení',
	'UI:AccessRO-Users' => ITOP_APPLICATION_SHORT.' je pouze ke čtení pro koncové uživatele',
	'UI:ApplicationEnvironment'                    => 'Aplikační prostředí: %1$s',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Nová hesla se neshodují!',
	'UI:Button:Login'                              => 'Přihlásit',
	'UI:Login:Error:AccessRestricted'              => 'Přístup je omezen. Kontaktujte administrátora.',
	'UI:Login:Error:AccessAdmin'                   => 'Přístup vyhrazen osobám s administrátorskými právy. Kontaktujte administrátora.',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne'                => '-- zvolte jednu z možností --',
	'UI:CSVImport:MappingNotApplicable'            => '-- ignorovat --',
	'UI:CSVImport:NoData'                          => 'Žádná data!',
	'UI:Title:DataPreview'                         => 'Náhled dat',
	'UI:CSVImport:ErrorOnlyOneColumn'              => 'Chyba: Data obsahují pouze jeden sloupec. Zvolili jste odpovídající znak pro oddělení položek?',
	'UI:CSVImport:FieldName'                       => 'Pole %1$d',
	'UI:CSVImport:DataLine1'                       => '1. řádek dat',
	'UI:CSVImport:DataLine2'                       => '2. řádek dat',
	'UI:CSVImport:idField'                         => 'id (primární klíč)',
	'UI:Title:BulkImport' => ITOP_APPLICATION_SHORT.' - hromadný import',
	'UI:Title:BulkImport+'                         => 'Průvodce importem CSV',
	'UI:Title:BulkSynchro_nbItem_ofClass_class'    => 'Synchronizace %1$d objektů třídy %2$s',
	'UI:CSVImport:ClassesSelectOne'                => '-- zvolte jednu z možností --',
	'UI:CSVImport:ErrorExtendedAttCode'            => 'Interní chyba: "%1$s" je nesprávný kód, protože "%2$s" není externím klíčem třídy "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged'        => '%1$d objekt(ů) zůstane nezměněno.',
	'UI:CSVImport:ObjectsWillBeModified'           => '%1$d objekt(ů) bude upraveno.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objekt(ů) bude přidáno.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objekt(ů) bude mít chyby.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objekt(ů) zůstalo nezměněných.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objekt(ů) bylo upraveno.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objekt(ů) bylo přidáno.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objekt(ů) mělo chyby.',
	'UI:Title:CSVImportStep2' => 'Krok 2 z 5: Volby pro CSV data',
	'UI:Title:CSVImportStep3' => 'Krok 3 z 5: Mapování dat',
	'UI:Title:CSVImportStep4' => 'Krok 4 z 5: Simulace importu',
	'UI:Title:CSVImportStep5' => 'Krok 5 z 5: Import dokončen',
	'UI:CSVImport:LinesNotImported' => 'Řádky, které se nepodařilo načíst:',
	'UI:CSVImport:LinesNotImported+' => 'Následující řádky se nepodařilo importovat, protože obsahují chyby',
	'UI:CSVImport:SeparatorComma+' => ', (čárka)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (středník)',
	'UI:CSVImport:SeparatorTab+' => '&lttab&gt (tabulátor)',
	'UI:CSVImport:SeparatorOther' => 'jiný:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (dvojité uvozovky)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (jednoduché uvozovky)',
	'UI:CSVImport:QualifierOther' => 'jiný:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'V prvním řádku jsou názvy sloupců',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Přeskočit %1$s řádky na začátku souboru',
	'UI:CSVImport:CSVDataPreview' => 'Náhled CSV dat',
	'UI:CSVImport:SelectFile' => 'Vybrat soubor k importu:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Import ze souboru',
	'UI:CSVImport:Tab:CopyPaste' => 'Vložit data',
	'UI:CSVImport:Tab:Templates' => 'Šablony',
	'UI:CSVImport:PasteData' => 'Vložte data k importu:',
	'UI:CSVImport:PickClassForTemplate' => 'Vyberte šablonu ke stažení: ',
	'UI:CSVImport:SeparatorCharacter' => 'Znak pro oddělení položek:',
	'UI:CSVImport:TextQualifierCharacter' => 'Textový kvalifikátor',
	'UI:CSVImport:CommentsAndHeader' => 'Záhlaví a komentáře',
	'UI:CSVImport:SelectClass' => 'Vyberte třídu pro import:',
	'UI:CSVImport:AdvancedMode' => 'Pokročilý režim',
	'UI:CSVImport:AdvancedMode+' => 'V pokročilém režimu může být "id" (primární klíč) objektů použito k aktualizaci a přejmenování objektů.Nicméně sloupec "id" (pokud existuje) slouží pouze pro vyhledávání a nemůže být použit v kombinaci s jinými vyhledávacími kritérii.',
	'UI:CSVImport:SelectAClassFirst' => 'Pro konfiguraci mapování nejdříve vyberte třídu.',
	'UI:CSVImport:HeaderFields' => 'Pole',
	'UI:CSVImport:HeaderMappings' => 'Mapování',
	'UI:CSVImport:HeaderSearch' => 'Vyhledávat?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Vyberte prosím mapování pro každé pole.',
	'UI:CSVImport:AlertMultipleMapping' => 'Ujistěte se prosím, že cílové pole je mapováno pouze jednou.',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Vyberte prosím alespoň jedno vyhledávací kritérium.',
	'UI:CSVImport:Encoding' => 'Kódování znaků',
	'UI:UniversalSearchTitle' => ITOP_APPLICATION_SHORT.' - Univerzální hledání',
	'UI:UniversalSearch:Error' => 'Chyba: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Vyberte třídu pro hledání: ',

	'UI:CSVReport-Value-Modified' => 'Upraveno',
	'UI:CSVReport-Value-SetIssue' => 'Nemůže být změněno - důvod: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Nemůže být změněno na %1$s - důvod: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Žádná shoda',
	'UI:CSVReport-Value-Missing' => 'Chybí povinná hodnota',
	'UI:CSVReport-Value-Ambiguous' => 'Nejednoznačné: nalezeno %1$s objektů',
	'UI:CSVReport-Row-Unchanged' => 'nezměněn',
	'UI:CSVReport-Row-Created' => 'vytvořen',
	'UI:CSVReport-Row-Updated' => 'aktualizováno %1$d sloupců',
	'UI:CSVReport-Row-Disappeared' => 'ztracen, změněno %1$d sloupců',
	'UI:CSVReport-Row-Issue' => 'Problém: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Nulová hodnota není povolena',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objekt nenalezen',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Nalezeno %1$d výsledků',
	'UI:CSVReport-Value-Issue-Readonly' => 'Atribut \'%1$s\' je pouze ke čtení a nemůže být upraven (stávající hodnota: %2$s, navrhovaná hodnota: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Nepodařilo se zpracovat vstup: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Neočekávaná hodnota atributu \'%1$s\': nenalezena shoda, zkontrolujte zadání',
	'UI:CSVReport-Value-Issue-Unknown' => 'Neočekávaná hodnota atributu \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Atributy spolu nejsou v souladu: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Neočekávané hodnoty atributů',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Nemůže být vytvořen z důvodu chybějícího externího klíče: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'špatný formát data',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'sladění selhalo',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'nejednoznačné sladění',
	'UI:CSVReport-Row-Issue-Internal' => 'Interní chyba: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Nezměněno',
	'UI:CSVReport-Icon-Modified' => 'Upraveno',
	'UI:CSVReport-Icon-Missing' => 'Chybí',
	'UI:CSVReport-Object-MissingToUpdate' => 'Chybějící objekt: bude aktualizováno',
	'UI:CSVReport-Object-MissingUpdated' => 'Chybějící objekt: aktualizováno',
	'UI:CSVReport-Icon-Created' => 'Vytvořeno',
	'UI:CSVReport-Object-ToCreate' => 'Objekt bude vytvořen',
	'UI:CSVReport-Object-Created' => 'Objekt vytvořen',
	'UI:CSVReport-Icon-Error' => 'Chyba',
	'UI:CSVReport-Object-Error' => 'CHYBA: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'NEJEDNOZNAČNÉ: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% načtených objektů obsahuje chyby a bude ignorováno.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% načtených objektů bude vytvořeno.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% načtených objektů bude upraveno.',

	'UI:CSVExport:AdvancedMode' => 'Pokročilý režim',
	'UI:CSVExport:AdvancedMode+' => 'V pokročilém režimu jsou vyexportovány další sloupce: id objektu, id externích klíčů a jejich slaďovacích atributů.',
	'UI:CSVExport:LostChars' => 'Problém s kódováním',
	'UI:CSVExport:LostChars+' => 'CSV soubor bude kódován v %1$s. '.ITOP_APPLICATION_SHORT.' zjistil, že některé charaktery nejsou s tímto kódováním kompatibilní. Tyto znaky budou nahrazeny zástupným znakem, nebo budou vynechány. Kontaktujte administrátora pro změnu kódování (parametr \'csv_file_default_charset\').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interaktivní Audit',
	'UI:Audit:HeaderAuditRule' => 'Pravidlo auditu',
	'UI:Audit:HeaderNbObjects' => 'Počet objektů',
	'UI:Audit:HeaderNbErrors' => 'Počet chyb',
	'UI:Audit:PercentageOk' => '% OK',
	'UI:Audit:OqlError' => 'OQL Error~~',
	'UI:Audit:Error:ValueNA' => 'n/a~~',
	'UI:Audit:ErrorIn_Rule' => 'Error in Rule~~',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL chyba v pravidle %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category' => 'Error in Category~~',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL chyba v kategorii %1$s: %2$s.',
	'UI:Audit:AuditErrors' => 'Audit Errors~~',
	'UI:Audit:Dashboard:ObjectsAudited' => 'Objects audited~~',
	'UI:Audit:Dashboard:ObjectsInError' => 'Objects in errors~~',
	'UI:Audit:Dashboard:ObjectsValidated' => 'Objects validated~~',
	'UI:Audit:AuditCategory:Subtitle' => '%1$s errors ouf of %2$s - %3$s%%~~',


	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - Vyhodnocení OQL dotazu',
	'UI:RunQuery:QueryExamples' => 'Příklady dotazů',
	'UI:RunQuery:QueryResults' => 'Query Results~~',
	'UI:RunQuery:HeaderPurpose' => 'Účel',
	'UI:RunQuery:HeaderPurpose+' => 'Vysvětlení účelu dotazi',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL dotaz',
	'UI:RunQuery:HeaderOQLExpression+' => 'Dotaz v OQL syntaxi',
	'UI:RunQuery:ExpressionToEvaluate' => 'Dotaz k vyhodnocení: ',
	'UI:RunQuery:QueryArguments' => 'Query Arguments~~',
	'UI:RunQuery:MoreInfo' => 'Více informací o dotazu: ',
	'UI:RunQuery:DevelopedQuery' => 'Rekonstruovaný dotaz: ',
	'UI:RunQuery:SerializedFilter' => 'Serializovaný filtr: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Nastala chyba při provádění dotazu',
	'UI:Query:UrlForExcel' => 'URL pro MS-Excel web queries',
	'UI:Query:UrlV1' => 'Nebyl specifikován seznam sloupců k exportu. Bez této informace nemůže stránka <em>export-V2.php</em> provést export. Pro export všech polí použijte stránku <em>export.php</em>. Pokud však chcete udržet konzistenci v delším časovém horzontu, použijte stávající stránku a specifikujte paramter "fields".',
	'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' schéma objektů',
	'UI:Schema:TitleForClass' => '%1$s schéma~~',
	'UI:Schema:CategoryMenuItem' => 'Kategorie <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Vztahy',
	'UI:Schema:AbstractClass' => 'Abstraktní třída: instance objektu této třídy nemůže být vytvořena.',
	'UI:Schema:NonAbstractClass' => 'Konkrétní třída: instance objektu této třídy může být vytvořena.',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarchie tříd',
	'UI:Schema:AllClasses' => 'Všechny třídy',
	'UI:Schema:ExternalKey_To' => 'Externí klíč (%1$s)',
	'UI:Schema:Columns_Description' => 'Sloupce: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Výchozí: "%1$s"',
	'UI:Schema:NullAllowed' => 'Nulová hodnota povolena',
	'UI:Schema:NullNotAllowed' => 'Nulová hodnota zakázána',
	'UI:Schema:Attributes' => 'Atributy',
	'UI:Schema:AttributeCode' => 'Kód atributu',
	'UI:Schema:AttributeCode+' => 'Interní kód atributu',
	'UI:Schema:Label' => 'Název',
	'UI:Schema:Label+' => 'Název atributu',
	'UI:Schema:Type' => 'Typ',

	'UI:Schema:Type+' => 'Datový typ atributu',
	'UI:Schema:Origin' => 'Původ',
	'UI:Schema:Origin+' => 'Základní třída, ve které je tento atribut definován',
	'UI:Schema:Description' => 'Popis',
	'UI:Schema:Description+' => 'Popis atributu',
	'UI:Schema:AllowedValues' => 'Přípustné hodnoty',
	'UI:Schema:AllowedValues+' => 'Omezení týkající se možných hodnot pro tento atribut',
	'UI:Schema:MoreInfo' => 'Více informací',
	'UI:Schema:MoreInfo+' => 'Více informací o poli definovaném v databázi',
	'UI:Schema:SearchCriteria' => 'Vyhledávací kritéria',
	'UI:Schema:FilterCode' => 'Kód filtru',
	'UI:Schema:FilterCode+' => 'Kód tohoto vyhledávacího kritéria',
	'UI:Schema:FilterDescription' => 'Popis',
	'UI:Schema:FilterDescription+' => 'Popis tohoto vyhledávacího kritéria',
	'UI:Schema:AvailOperators' => 'Dostupné operátory',
	'UI:Schema:AvailOperators+' => 'Dostupné operátory tohoto vyhledávacího kritéria',
	'UI:Schema:ChildClasses' => 'Podřízené třídy',
	'UI:Schema:ReferencingClasses' => 'Odkazující třídy',
	'UI:Schema:RelatedClasses' => 'Související třídy',
	'UI:Schema:LifeCycle' => 'Životní cyklus',
	'UI:Schema:Triggers' => 'Triggery',
	'UI:Schema:Relation_Code_Description' => 'Vazba <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Dolů: %1$s',
	'UI:Schema:RelationUp_Description' => 'Nahoru: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: rozšířen na %2$d úrovně, dotaz: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: nerozšířen (%2$d úrovně), dotaz: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s je odkazován třídou %2$s přes pole %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s je propojen s %2$s přes %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Třídy ukazující na ""%1$s" (1:n links):',
	'UI:Schema:Links:n-n' => 'Třídy propojené s "%1$s" (n:n links):',
	'UI:Schema:Links:All' => 'Graf všech souvisejících tříd',
	'UI:Schema:NoLifeCyle' => 'Pro tuto třídu není definovaný žádný životní cyklus.',
	'UI:Schema:LifeCycleTransitions' => 'Přechody',
	'UI:Schema:LifeCyleAttributeOptions' => 'Možnosti atributu',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Skrytý',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Jen pro čtení',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Povinný',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Musí se změnit',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Uživatel bude vyzván ke změně hodnoty',
	'UI:Schema:LifeCycleEmptyList' => 'prázdný seznam',
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => 'Zadejte první tři znaky...',
	'UI:Edit:SearchQuery' => 'Select a predefined query~~',
	'UI:Edit:TestQuery' => 'Otestovat dotaz',
	'UI:Combo:SelectValue' => '--- vyberte hodnotu ---',
	'UI:Label:SelectedObjects' => 'Vybrané objekty: ',
	'UI:Label:AvailableObjects' => 'Dostupné objekty: ',
	'UI:Link_Class_Attributes' => '%1$s atributy',
	'UI:SelectAllToggle+' => 'Vybrat vše / Zrušit výběr',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Přidat %1$s objekty spojené s %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Přidat %1$s ke spojení s %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Spravovat %1$s objekty spojené s %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Přidat objekt (%1$s)',
	'UI:RemoveLinkedObjectsOf_Class' => 'Odstranit vybrané objekty',
	'UI:Message:EmptyList:UseAdd' => 'Seznam je prázdný, použijte tlačítko "Přidat..." pro přidání položek.',
	'UI:Message:EmptyList:UseSearchForm' => 'Použijte hledání k vyhledání objektů pro přidání.',
	'UI:Wizard:FinalStepTitle' => 'Poslední krok: potvrzení',
	'UI:Title:DeletionOf_Object' => 'Odstranění %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Hromadné odstranění %1$d objektů třídy %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Nemáte oprávnění k odstranění tohoto objektu',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Nemáte oprávnění upravovat následující pole: %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Tento objekt nemůže být odstraněn, protože stávající uživatel nemá dostatečná oprávnění',
	'UI:Error:CannotDeleteBecause' => 'Tento objekt nemůže být odstraněn, protože: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Tento objekt nelze odstranit, protože před tím musí být provedeny nějaké manuální operace',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Tento objekt nelze odstranit, protože před tím musí být provedeny nějaké manuální operace',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s jménem uživatele %2$s',
	'UI:Delete:Deleted' => 'odstraněno',
	'UI:Delete:AutomaticallyDeleted' => 'automaticky odstraněno',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatická obnova pole: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Čištění všech referencí na %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Čištění všech referencí na %1$d objekty třídy %2$s...',
	'UI:Delete:Done+' => 'Co bylo vykonáno...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s odstraněn.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Odstraňování %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Odstraňování %1$d objektů třídy %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Nemůže být odstraněno: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Mělo být odstraněno automaticky, ale to není možné: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Musí být odstraněno automaticky, ale to není možné: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Bude aoutomaticky odstraněno',
	'UI:Delete:MustBeDeletedManually' => 'Musí být odstraněno manuálně',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Mělo být automaticky aktualizováno, ale: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'bude automaticky aktualizováno (obnova: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objekty/linky odkazují na %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objekty/linky odkazují na některé objekty k odstranění',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Pro zajištění integrity databáze by měly být odstraněny všechny reference',
	'UI:Delete:Consequence+' => 'Co bude vykonáno',
	'UI:Delete:SorryDeletionNotAllowed' => 'Nemáte oprávnění k odstranění tohoto objektu',
	'UI:Delete:PleaseDoTheManualOperations' => 'Před odstraněním tohoto objektu nejdříve proveďte výše uvedené manuální operace.',
	'UI:Delect:Confirm_Object' => 'Potvrďte, že chcete odstranit objekt %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Potvrďte, že chcete odstranit tyto objekty (%1$d) třídy %2$s.',
	'UI:WelcomeToITop' => 'Vítejte v '.ITOP_APPLICATION_SHORT,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s detaily',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Chyba',
	'UI:ObjectDoesNotExist' => 'Tento objekt neexistuje (nebo nemáte oprávnění k jeho zobrazení).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Výsledky hledání',
	'UI:SearchResultsTitle' => 'Výsledky hledání',
	'UI:SearchResultsTitle+' => 'Výsledky fulltextového hledání',
	'UI:Search:NoSearch' => 'Nic k hledání',
	'UI:Search:NeedleTooShort' => 'Zadaný výraz "%1$s" je příliš krátký. Zadejte prosím alespoň %2$d znaky.',
	'UI:Search:Ongoing' => 'Hledám "%1$s"',
	'UI:Search:Enlarge' => 'Rozšířit hledání',
	'UI:FullTextSearchTitle_Text' => 'Výsledky pro "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objekt(ů) třídy %2$s nalezeno.',
	'UI:Search:NoObjectFound' => 'Nenalezen žádný objekt.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - úprava - %1$s - %2$s',
	'UI:ModificationTitle_Class_Object' => 'Úprava %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - Klonování %1$s - %2$s',
	'UI:CloneTitle_Class_Object' => 'Klonování %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - Vytváření nového objektu (%1$s) ',
	'UI:CreationTitle_Class' => 'Vytváření nového objektu (%1$s)',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Vyberte typ objektu "%1$s" k vytvoření:',
	'UI:Class_Object_NotUpdated' => 'Nenalezeny žádné změny, objekt %1$s (%2$s) <strong>nebude</strong> upraven.',
	'UI:Class_Object_Updated' => 'Objekt %1$s (%2$s) byl aktualizován.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Hromadné odstranění',
	'UI:BulkDeleteTitle' => 'Vyberte objekty, které chcete odstranit:',
	'UI:PageTitle:ObjectCreated' => ITOP_APPLICATION_SHORT.' Objekt vytvořen.',
	'UI:Title:Object_Of_Class_Created' => 'Objekt %1$s - %2$s vytvořen.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Aplikace %1$s na objekt: %2$s ve stavu %3$s do cílového stavu: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Objekt nemohl být zapsán: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Závažná chyba',
	'UI:SystemIntrusion' => 'Přístup odepřen. Vyžadujete operaci, která vám není povolena.',
	'UI:FatalErrorMessage' => 'Závažná chyba, '.ITOP_APPLICATION_SHORT.' nemůže pokračovat.',
	'UI:Error_Details' => 'Chyba: %1$s',

	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' správa uživatelů - projekce profilů',
	'UI:UserManagement:Class' => 'Třída',
	'UI:UserManagement:Class+' => 'Třída objektů',
	'UI:UserManagement:ProjectedObject' => 'Objekt',
	'UI:UserManagement:ProjectedObject+' => 'Projektovaný objekt',
	'UI:UserManagement:AnyObject' => '* jakýkoli *',
	'UI:UserManagement:User' => 'Uživatel',
	'UI:UserManagement:User+' => 'User zapojený do projekce',
	'UI:UserManagement:Action:Read' => 'Čtení',
	'UI:UserManagement:Action:Read+' => 'Čtení/zobrazování objektů',
	'UI:UserManagement:Action:Modify' => 'Upravování',
	'UI:UserManagement:Action:Modify+' => 'Vytváření a upravování objektů',
	'UI:UserManagement:Action:Delete' => 'Odstraňování',
	'UI:UserManagement:Action:Delete+' => 'Odstraňování objektů',
	'UI:UserManagement:Action:BulkRead' => 'Hromadné čtení (export)',
	'UI:UserManagement:Action:BulkRead+' => 'Vypisování objektů nebo export',
	'UI:UserManagement:Action:BulkModify' => 'Hromadné upravování (import)',
	'UI:UserManagement:Action:BulkModify+' => 'Hromadné vytváření/upravování objektů (CSV import)',
	'UI:UserManagement:Action:BulkDelete' => 'Hromadné odstraňování',
	'UI:UserManagement:Action:BulkDelete+' => 'Hromadné odstraňování objektů',
	'UI:UserManagement:Action:Stimuli' => 'Operace',
	'UI:UserManagement:Action:Stimuli+' => 'Povolené (složené) akce',
	'UI:UserManagement:Action' => 'Akce',
	'UI:UserManagement:Action+' => 'Akce prováděné uživatelem',
	'UI:UserManagement:TitleActions' => 'Akce',
	'UI:UserManagement:Permission' => 'Oprávnění',
	'UI:UserManagement:Permission+' => 'Uživatelská orpávnění',
	'UI:UserManagement:Attributes' => 'Atributy',
	'UI:UserManagement:ActionAllowed:Yes' => 'Ano',
	'UI:UserManagement:ActionAllowed:No' => 'Ne',
	'UI:UserManagement:AdminProfile+' => 'Administrátoři mají plný přístup ke všem objektům v databázi.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'nedefinováno',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Pro tuto třídu nebyl definován žádný životní cyklus',
	'UI:UserManagement:GrantMatrix' => 'Matice oprávnění',

	'Menu:AdminTools' => 'Administrace',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Nástroje pro administraci',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Nástroje přístupné pouze uživatelům, kteří mají potřbná oprávnění',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Řízení změn',
	'UI:ChangeManagementMenu+' => 'Řízení změn',
	'UI:ChangeManagementMenu:Title' => 'Přehled změn',
	'UI-ChangeManagementMenu-ChangesByType' => 'Změny podle typu',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Změny podle stavu',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Nepřidělené změny',

	'UI:ConfigurationManagementMenu' => 'Správa konfigurací',
	'UI:ConfigurationManagementMenu+' => 'Správa konfigurací',
	'UI:ConfigurationManagementMenu:Title' => 'Přehled infrastruktury',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objekty infrastruktury podle typu',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objekty infrastruktury podle stavu',

	'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard pro správu konfigurací (Configuration management)',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Konfigurační položky podle stavu',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Konfigurační položky podle typu',

	'UI:RequestMgmtMenuOverview:Title' => 'Dashboard pro správu požadavků (Request management)',
	'UI-RequestManagementOverview-RequestByService' => 'Požadavky uživatelů podle služby',
	'UI-RequestManagementOverview-RequestByPriority' => 'Požadavky uživatelů podle priority',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Nepřidělené požadavky',

	'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard pro správu incidentů (Incident management)',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidenty podle služby',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenty podle priority',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Nepřidělené incidenty',

	'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard pro řízení změn (Change management)',
	'UI-ChangeManagementOverview-ChangeByType' => 'Změny podle typu',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Nepřidělené změny',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Výpadky z důvodu změn',

	'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard pro správu služeb (Service Management)',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Smlouvy se zákazníky k obnovení do 30 dní',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Smlouvy s poskytovateli k obnovení do 30 dní',

	'UI:ContactsMenu' => 'Kontakty',
	'UI:ContactsMenu+' => 'Kontakty',
	'UI:ContactsMenu:Title' => 'Přehled kontaktů',
	'UI-ContactsMenu-ContactsByLocation' => 'Kontakty podle umístění',
	'UI-ContactsMenu-ContactsByType' => 'Kontakty podle typu',
	'UI-ContactsMenu-ContactsByStatus' => 'Kontakty podle stavu',

	'Menu:CSVImportMenu' => 'CSV import',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'Hromadné vytvoření nebo aktualizace',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Datový model',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Přehled datového modelu',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Exportovat',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Exportovat výsledky jakéhokoli dotazu do HTML, CSV nebo XML',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Upozornění',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Konfigurace upozornění',// Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Konfigurace upozornění',
	'UI:NotificationsMenu:Help' => 'Nápověda',
	'UI:NotificationsMenu:HelpContent' => '<p>Upozornění v '.ITOP_APPLICATION_SHORT.' jsou plně nastavitelné. Jsou založena na dvou druzích objektů: <i>triggery (spouštěče) a akce</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of '.ITOP_APPLICATION_SHORT.' core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<i><b>Akce</b></i> define the actions to be performed when the triggers execute. For now there are only two kind of actions:
<ol>
	<li>Sending an email message: Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.<br />
	Speciální stránka <a href="../setup/email.test.php" target="_blank">email.test.php</a> je dostupná pro testování a řešení problémů s configurací PHP mailu.</li>
	<li>Outgoing webhooks: Allow integration with a third-party application by sending structured data to a defined URL.</li>
</ol>
</p>
<p>Aby mohly být akce spuštěny, musí být přiřazeny ke triggerům. Každá akce pak dostane své "pořadové" číslo, které určí v jakém pořadí se akce spustí.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Triggery',
	'UI:NotificationsMenu:AvailableTriggers' => 'Dostupné triggery',
	'UI:NotificationsMenu:OnCreate' => 'Při vytvoření objektu',
	'UI:NotificationsMenu:OnStateEnter' => 'Při změně stavu na',
	'UI:NotificationsMenu:OnStateLeave' => 'Při změně stavu z',
	'UI:NotificationsMenu:Actions' => 'Akce',
	'UI:NotificationsMenu:Actions:ActionEmail' => 'Email actions~~',
	'UI:NotificationsMenu:Actions:ActionWebhook' => 'Webhook actions (outgoing integrations)~~',
	'UI:NotificationsMenu:Actions:Action' => 'Other actions~~',
	'UI:NotificationsMenu:AvailableActions' => 'Dostupné akce',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Kategorie auditu',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Kategorie auditu',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Kategorie auditu',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Provést dotaz',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Provést dotaz',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Knihovna dotazů',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Knihovna dotazů',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Správa dat',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Správa dat',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Univerzální hledání',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Hledejte cokoli...',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Správa uživatelů',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Správa uživatelů',// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profily (Role)',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profily (Role)',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profily (Role)',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Uživatelské účty',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Uživatelské účty',
	// Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Uživatelské účty',
	// Duplicated into itop-welcome-itil (will be removed from here...)

	'UI:iTopVersion:Short' => '%1$s verze %2$s',
	'UI:iTopVersion:Long' => '%1$s verze %2$s-%3$s ze dne %4$s',
	'UI:PropertiesTab' => 'Vlastnosti',

	'UI:OpenDocumentInNewWindow_' => 'Otevřít~~',
	'UI:DownloadDocument_' => 'Stáhnout~~',
	'UI:Document:NoPreview' => 'Pro tento typ dokumentu není k dispozici žádný náhled',
	'UI:Download-CSV' => 'Stáhnout %1$s',

	'UI:DeadlineMissedBy_duration' => 'Zmeškáno o %1$s',
	'UI:Deadline_LessThan1Min' => 'méně než 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Nápověda',
	'UI:PasswordConfirm' => 'Potvrzení',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Než přidáte další objekty třídy %1$s, uložte tento objekt.',
	'UI:DisplayThisMessageAtStartup' => 'Zobrazovat tuto zprávu při spuštění',
	'UI:RelationshipGraph' => 'Grafické zobrazení',
	'UI:RelationshipList' => 'Seznam',
	'UI:RelationGroups' => 'Skupiny',
	'UI:OperationCancelled' => 'Operace byla zrušena',
	'UI:ElementsDisplayed' => 'Filtrování',
	'UI:RelationGroupNumber_N' => 'Skupina #%1$d',
	'UI:Relation:ExportAsPDF' => 'PDF export',
	'UI:RelationOption:GroupingThreshold' => 'Práh pro seskupení',
	'UI:Relation:AdditionalContextInfo' => 'Zobrazit dodatečné informace:',
	'UI:Relation:NoneSelected' => 'Žádný',
	'UI:Relation:Zoom' => 'Zoom~~',
	'UI:Relation:ExportAsAttachment' => 'Exportovat jako přílohu',
	'UI:Relation:DrillDown' => 'Podrobnosti...',
	'UI:Relation:PDFExportOptions' => 'Možnosti PDF exportu',
	'UI:Relation:AttachmentExportOptions_Name' => 'Volby přílohy %1$s',
	'UI:RelationOption:Untitled' => 'Bez názvu',
	'UI:Relation:Key' => 'Legenda',
	'UI:Relation:Comments' => 'Komentáře',
	'UI:RelationOption:Title' => 'Nadpis',
	'UI:RelationOption:IncludeList' => 'Zahrnout seznam objektů',
	'UI:RelationOption:Comments' => 'Komentáře',
	'UI:Button:Export' => 'Exportovat',
	'UI:Relation:PDFExportPageFormat' => 'Formát stránky',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter',
	'UI:Relation:PDFExportPageOrientation' => 'Orientace stránky',
	'UI:PageOrientation_Portrait' => 'Na výšku',
	'UI:PageOrientation_Landscape' => 'Na šířku',
	'UI:RelationTooltip:Redundancy' => 'Redundance',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => 'Počet zasažených objektů: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Prahová hranice: %1$d / %2$d',
	'Portal:Title' => ITOP_APPLICATION_SHORT.' uživatelský portál',
	'Portal:NoRequestMgmt' => 'Byli jste přesměrováni na tuto stránku, protože k vašemu účtu je přidělen profil \'Portal user\'. '.ITOP_APPLICATION_SHORT.' však nebyl nainstalován s funkcí \'Request Management\'. Kontaktujte prosím vašeho administrátora.',
	'Portal:Refresh' => 'Obnovit',
	'Portal:Back' => 'Zpět',
	'Portal:WelcomeUserOrg' => 'Vítejte, %1$s (%2$s)',
	'Portal:TitleDetailsFor_Request' => 'Detaily požadavku',
	'Portal:ShowOngoing' => 'Zobrazit otevřené požadavky',
	'Portal:ShowClosed' => 'Zobrazit uzavřené požadavky',
	'Portal:CreateNewRequest' => 'Vytvořit nový požadavek',
	'Portal:CreateNewRequestItil' => 'Vytvořit nový požadavek',
	'Portal:CreateNewIncidentItil' => 'Nahlásit nový incident',
	'Portal:ChangeMyPassword' => 'Změnit heslo',
	'Portal:Disconnect' => 'Odpojit',
	'Portal:OpenRequests' => 'Mé otevřené požadavky',
	'Portal:ClosedRequests' => 'Mé uzavřené požadavky',
	'Portal:ResolvedRequests' => 'Mé vyřešené požadavky',
	'Portal:SelectService' => 'Vyberte službu z katalogu:',
	'Portal:PleaseSelectOneService' => 'Vyberte prosím jednu službu',
	'Portal:SelectSubcategoryFrom_Service' => 'Vyberte podkategorii pro službu %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Vyberte prosím jednu podkategorii',
	'Portal:DescriptionOfTheRequest' => 'Zadejte popis vašeho požadavku:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detaily požadavku "%1$s":',
	'Portal:NoOpenRequest' => 'Žádný požadavek v této kategorii',
	'Portal:NoClosedRequest' => 'Žádný požadavek v této kategorii',
	'Portal:Button:ReopenTicket' => 'Znovu otevřít tento tiket',
	'Portal:Button:CloseTicket' => 'Uzavřít tento tiket',
	'Portal:Button:UpdateRequest' => 'Aktualizovat požadavek',
	'Portal:EnterYourCommentsOnTicket' => 'Vložte své připomínky k řešení tohoto tiketu:',
	'Portal:ErrorNoContactForThisUser' => 'Stávající uživatel není spojený s žádným kontaktem/osobou. Kontaktujte prosím svého administrátora.',
	'Portal:Attachments' => 'Přílohy',
	'Portal:AddAttachment' => ' Přidat přílohu ',
	'Portal:RemoveAttachment' => ' Odstranit přílohu ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Příloha č. %1$d k %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Zvolte šablonu pro %1$s',
	'Enum:Undefined' => 'Nedefinováno',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$sd %2$sh %3$sm %4$ss',
	'UI:ModifyAllPageTitle' => 'Upravit vše',
	'UI:Modify_N_ObjectsOf_Class' => 'Úprava %1$d objektů třídy %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Úprava %1$d objektů třídy %2$s ze %3$d',
	'UI:Menu:ModifyAll' => 'Upravit...',
	'UI:Button:ModifyAll' => 'Upravit vše',
	'UI:Button:PreviewModifications' => 'Náhled úprav >>',
	'UI:ModifiedObject' => 'Objekt upraven',
	'UI:BulkModifyStatus' => 'Stav',
	'UI:BulkModifyStatus+' => 'Stav operace',
	'UI:BulkModifyErrors' => 'Chyby',
	'UI:BulkModifyErrors+' => 'Chyby zabraňující úpravám',
	'UI:BulkModifyStatusOk' => 'OK',
	'UI:BulkModifyStatusError' => 'Chyba',
	'UI:BulkModifyStatusModified' => 'Upraveno',
	'UI:BulkModifyStatusSkipped' => 'Vynecháno',
	'UI:BulkModify_Count_DistinctValues' => '%1$d odlišných hodnot:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s existuje %2$dx',
	'UI:BulkModify:N_MoreValues' => 'o %1$d více hodnot...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Pokoušíte se upravit pole jen pro čtení: %1$s',
	'UI:FailedToApplyStimuli' => 'Akce se nezdařila.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Upravuji %2$d objekt(ů) třídy %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Zadejte text zde:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Počáteční hodnota:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Pole %1$s není zapisovatelné, protože je spravováno synchronizací dat.',
	'UI:ActionNotAllowed' => 'Nemáte oprávnění provádět tuto akci na těchto objektech.',
	'UI:BulkAction:NoObjectSelected' => 'Vyberte prosím alespoň jeden objekt',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Pole %1$s není zapisovatelné, protože je spravováno synchronizací dat.',
	'UI:Pagination:HeaderSelection' => 'Celkem: %1$s objektů (%2$s objektů vybráno).',
	'UI:Pagination:HeaderNoSelection' => 'Celkem objektů: %1$s',
	'UI:Pagination:PageSize' => '%1$s objektů na stránku',
	'UI:Pagination:PagesLabel' => 'Stránek:',
	'UI:Pagination:All' => 'Vše',
	'UI:HierarchyOf_Class' => 'Hierarchie %1$s',
	'UI:Preferences' => 'Předvolby',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Oblíbené organizace',
	'UI:FavoriteOrganizations+' => 'Zaškrtněte, které organizace chcete vidět v rozbalovacím menu pro rychlý přístup. Mějte na paměti, že toto není bezpečnostní opatření. Objekty všech organizací jsou pořád viditelné a přístupné vybráním "Všechny organizace" z rozbalovacího menu.',
	'UI:FavoriteLanguage' => 'Jazyk uživatelského rozhraní~~',
	'UI:Favorites:SelectYourLanguage' => 'Preferovaný jazyk:',
	'UI:FavoriteOtherSettings' => 'Další nastavení',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Výchozí délka seznamů: %1$s položek na stránku~~',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Všechny úpravy budou zahozeny.',
	'UI:CancelConfirmationMessage' => 'Přijdete o všechny změny. Přejete si přesto pokračovat?',
	'UI:AutoApplyConfirmationMessage' => 'Některé změny nebyly dosud použity. Chcete aby je '.ITOP_APPLICATION_SHORT.' zohlednil?',
	'UI:Create_Class_InState' => 'Vytvořit %1$s ve stavu: ',
	'UI:OrderByHint_Values' => 'Řadit dle: %1$s',
	'UI:Menu:AddToDashboard' => 'Přidat na Dashboard...',
	'UI:Button:Refresh' => 'Obnovit',
	'UI:Button:GoPrint' => 'Tisknout',
	'UI:ExplainPrintable' => 'Klikněte na ikonu %1$s pro skrytí položek v tisku.<br/>Tato hlavička a ostatní nastavení nebudou vytištěny.',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:SwitchToStandardDashboard' => 'Switch to standard dashboard~~',
	'UI:Toggle:SwitchToCustomDashboard' => 'Switch to custom dashboard~~',

	'UI:ConfigureThisList' => 'Konfigurovat tento seznam...',
	'UI:ListConfigurationTitle' => 'Konfigurace seznamu',
	'UI:ColumnsAndSortOrder' => 'Sloupce a jejich řazení:',
	'UI:UseDefaultSettings' => 'Použít výchozí nastavení',
	'UI:UseSpecificSettings' => 'Použít následující nastavení:',
	'UI:Display_X_ItemsPerPage_prefix' => 'Zobrazit',
	'UI:Display_X_ItemsPerPage_suffix' => 'položek na stránku',
	'UI:UseSavetheSettings' => 'Uložit nastavení',
	'UI:OnlyForThisList' => 'Jen pro tento seznam',
	'UI:ForAllLists' => 'Pro všechny seznamy',
	'UI:ExtKey_AsLink' => '%1$s (odkaz)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (popis)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Posunout nahoru',
	'UI:Button:MoveDown' => 'Posunout dolů',

	'UI:OQL:UnknownClassAndFix' => 'Neznámá třída "%1$s". Můžete zkusit "%2$s".',
	'UI:OQL:UnknownClassNoFix' => 'Neznámá třída "%1$s"',

	'UI:Dashboard:EditCustom' => 'Edit custom version...~~',
	'UI:Dashboard:CreateCustom' => 'Create a custom version...~~',
	'UI:Dashboard:DeleteCustom' => 'Delete custom version...~~',
	'UI:Dashboard:RevertConfirm' => 'Všechny změny oproti původní verzi budou ztraceny. Potvrďte prosím, že to chcete opravdu udělat.',
	'UI:ExportDashBoard' => 'Export do souboru',
	'UI:ImportDashBoard' => 'Import ze souboru',
	'UI:ImportDashboardTitle' => 'Import ze souboru',
	'UI:ImportDashboardText' => 'Vyberte šablonu dashboardu k importu:',
	'UI:Dashboard:Actions' => 'Dashboard actions~~',
	'UI:Dashboard:NotUpToDateUntilContainerSaved' => 'This dashboard displays information that does not include the on-going changes.~~',


	'UI:DashletCreation:Title' => 'Vytvořit nový dashlet',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Typ dashletu',
	'UI:DashletCreation:EditNow' => 'Upravit dashboard',

	'UI:DashboardEdit:Title' => 'Upravit dashboard',
	'UI:DashboardEdit:DashboardTitle' => 'Nadpis',
	'UI:DashboardEdit:AutoReload' => 'Automatické obnovování',
	'UI:DashboardEdit:AutoReloadSec' => 'Interval pro automatické obnovování (v sekundách)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Minimální povolená hodnota je %1$d sekund',
	'UI:DashboardEdit:Revert' => 'Revert~~',
	'UI:DashboardEdit:Apply' => 'Apply~~',

	'UI:DashboardEdit:Layout' => 'Uspořádání',
	'UI:DashboardEdit:Properties' => 'Dashboard - vlastnosti',
	'UI:DashboardEdit:Dashlets' => 'Dostupné dashlety',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet - vlastnosti',

	'UI:Form:Property' => 'Vlastnost',
	'UI:Form:Value' => 'Hodnota',

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

	'UI:DashletPlainText:Label' => 'Text',
	'UI:DashletPlainText:Description' => 'Prostý text (bez formátování)',
	'UI:DashletPlainText:Prop-Text' => 'Text',
	'UI:DashletPlainText:Prop-Text:Default' => 'Vložte text...',

	'UI:DashletObjectList:Label' => 'Seznam objektů',
	'UI:DashletObjectList:Description' => '',
	'UI:DashletObjectList:Prop-Title' => 'Titul',
	'UI:DashletObjectList:Prop-Query' => 'Dotaz',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Titul',
	'UI:DashletGroupBy:Prop-Query' => 'Dotaz',
	'UI:DashletGroupBy:Prop-Style' => 'Styl',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Seskupit...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hodina %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Měsíc %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Den týdne (%1$s)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Den měsíce (%1$s)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (h)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (m)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (den týdne)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (den měsíce)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Vyberte prosím pole, podle kterého budou objekty seskupeny',

	'UI:DashletGroupByPie:Label' => 'Koláčový graf',
	'UI:DashletGroupByPie:Description' => 'Koláčový graf',
	'UI:DashletGroupByBars:Label' => 'Sloupcový graf',
	'UI:DashletGroupByBars:Description' => 'Sloupcový graf',
	'UI:DashletGroupByTable:Label' => 'Seskupit dle (tabulka)',
	'UI:DashletGroupByTable:Description' => 'Seznam (seskupeno dle pole)',

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

	'UI:DashletHeaderStatic:Label' => 'Hlavička',
	'UI:DashletHeaderStatic:Description' => 'Zobrazí horizontální oddělovač',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titul',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Ikona',

	'UI:DashletHeaderDynamic:Label' => 'Hlavička se statistikami',
	'UI:DashletHeaderDynamic:Description' => 'Hlavička se statistikami',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Titul',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Ikona',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Podtitul',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Dotaz',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Seskupit dle',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Hodnoty',

	'UI:DashletBadge:Label' => 'Ikona',
	'UI:DashletBadge:Description' => 'Ikona objektu se schopností vytvářet a hledat',
	'UI:DashletBadge:Prop-Class' => 'Třída',

	'DayOfWeek-Sunday' => 'Neděle',
	'DayOfWeek-Monday' => 'Pondělí',
	'DayOfWeek-Tuesday' => 'Úterý',
	'DayOfWeek-Wednesday' => 'Středa',
	'DayOfWeek-Thursday' => 'Čtvrtek',
	'DayOfWeek-Friday' => 'Pátek',
	'DayOfWeek-Saturday' => 'Sobota',
	'Month-01' => 'Leden',
	'Month-02' => 'Únor',
	'Month-03' => 'Březen',
	'Month-04' => 'Duben',
	'Month-05' => 'Květen',
	'Month-06' => 'Červen',
	'Month-07' => 'Červenec',
	'Month-08' => 'Srpen',
	'Month-09' => 'Září',
	'Month-10' => 'Říjen',
	'Month-11' => 'Listopad',
	'Month-12' => 'Prosinec',

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Ne',
	'DayOfWeek-Monday-Min' => 'Po',
	'DayOfWeek-Tuesday-Min' => 'Út',
	'DayOfWeek-Wednesday-Min' => 'St',
	'DayOfWeek-Thursday-Min' => 'Čt',
	'DayOfWeek-Friday-Min' => 'Pá',
	'DayOfWeek-Saturday-Min' => 'So',
	'Month-01-Short' => 'Led',
	'Month-02-Short' => 'Úno',
	'Month-03-Short' => 'Bře',
	'Month-04-Short' => 'Dub',
	'Month-05-Short' => 'Kvě',
	'Month-06-Short' => 'Čvn',
	'Month-07-Short' => 'Čvc',
	'Month-08-Short' => 'Srp',
	'Month-09-Short' => 'Zář',
	'Month-10-Short' => 'Říj',
	'Month-11-Short' => 'Lis',
	'Month-12-Short' => 'Pro',
	'Calendar-FirstDayOfWeek' => '1',// 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Vytvořit odkaz',
	'UI:ShortcutRenameDlg:Title' => 'Přejmenovat odkaz',
	'UI:ShortcutListDlg:Title' => 'Vytvořit odkaz na seznam',
	'UI:ShortcutDelete:Confirm' => 'Potvrďte prosím, že chcete odkaz odstranit.',
	'Menu:MyShortcuts' => 'Mé odkazy',// Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Odkaz',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Název',
	'Class:Shortcut/Attribute:name+' => 'Označení použité v menu a názvu stránky',
	'Class:ShortcutOQL' => 'Odkaz na výsledky vyhledávání',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Dotaz',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL stanovující seznam objektů pro hledání',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatické obnovování',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Zakázáno',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Vlastní interval',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Interval pro automatické obnovování (v sekundách)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'Minimální povolená hodnota je %1$d sekund',

	'UI:FillAllMandatoryFields' => 'Vyplňte prosím všechna povinná pole.',
	'UI:ValueMustBeSet' => 'Toto pole je poviné',
	'UI:ValueMustBeChanged' => 'Hodnota musí být změněna',
	'UI:ValueInvalidFormat' => 'Nesprávný formát',

	'UI:CSVImportConfirmTitle' => 'Potvrďte prosím operaci',
	'UI:CSVImportConfirmMessage' => 'Jste si jisti, že to chcete udělat?',
	'UI:CSVImportError_items' => 'Chyby: %1$d',
	'UI:CSVImportCreated_items' => 'Vytvořeno: %1$d',
	'UI:CSVImportModified_items' => 'Upraveno: %1$d',
	'UI:CSVImportUnchanged_items' => 'Nezměněno: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Formát data a času',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Výchozí formát: %1$s (např. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Vlastní formát: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Dostupné zástupné znaky:<table>
      <tr><td>Y</td><td>rok (4 znaky, např. 2016)</td></tr>
      <tr><td>y</td><td>rok (2 znaky, např. 16 pro 2016)</td></tr>
      <tr><td>m</td><td>měsíc (2 znaky, např. 01..12)</td></tr>
      <tr><td>n</td><td>měsíc (1 nebo 2 znaky bez úvodních nul, např. 1..12)</td></tr>
      <tr><td>d</td><td>den (2 znaky, např. 01..31)</td></tr>
      <tr><td>j</td><td>den (1 nebo 2 znaky bez úvodnách nul, např. 1..31)</td></tr>
      <tr><td>H</td><td>hodina (24h formát, 2 znaky, např. 00..23)</td></tr>
      <tr><td>h</td><td>hodina (12h formát, 2 znaky, např. 01..12)</td></tr>
      <tr><td>G</td><td>hodina (24h formát, 1 nebo 2 znaky bez úvodních nul, např. 0..23)</td></tr>
      <tr><td>g</td><td>hodina (12h formát, 1 nebo 2 znaky bez úvodních nul, např. 1..12)</td></tr>
      <tr><td>a</td><td>hodina, am nebo pm</td></tr>
      <tr><td>A</td><td>hodina, AM nebo PM</td></tr>
      <tr><td>i</td><td>minuty (2 znaky, např. 00..59)</td></tr>
      <tr><td>s</td><td>sekundy (2 znaky, např. 00..59)</td></tr>
    </table>',

	'UI:Button:Remove' => 'Odstranit',
	'UI:AddAnExisting_Class' => 'Přidat objekty typu %1$s...',
	'UI:SelectionOf_Class' => 'Výběr objektů typu %1$s',

	'UI:AboutBox' => 'O '.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'O '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Datový model',
	'UI:About:Support' => 'Informace pro podporu',
	'UI:About:Licenses' => 'Licence',
	'UI:About:InstallationOptions' => 'Installation options~~',
	'UI:About:ManualExtensionSource' => 'Extension~~',
	'UI:About:Extension_Version' => 'Version: %1$s~~',
	'UI:About:RemoteExtensionSource' => 'Data~~',

	'UI:DisconnectedDlgMessage' => 'Byli jste odpojeni. Pokud chcete aplikaci nadále používat, musíte se znovu přihlásit.',
	'UI:DisconnectedDlgTitle' => 'Varování!',
	'UI:LoginAgain' => 'Znovu přihlásit',
	'UI:StayOnThePage' => 'Zůstat na této stránce',

	'ExcelExporter:ExportMenu' => 'Export do Excelu',
	'ExcelExporter:ExportDialogTitle' => 'Export do Excelu',
	'ExcelExporter:ExportButton' => 'Export',
	'ExcelExporter:DownloadButton' => 'Stáhnout %1$s',
	'ExcelExporter:RetrievingData' => 'Načítám data...',
	'ExcelExporter:BuildingExcelFile' => 'Vytvářím soubor...',
	'ExcelExporter:Done' => 'Hotovo.',
	'ExcelExport:AutoDownload' => 'Stáhnout soubor automaticky po dokončení exportu.',
	'ExcelExport:PreparingExport' => 'Připravuji export...',
	'ExcelExport:Statistics' => 'Statistiky',
	'portal:legacy_portal' => 'Uživatelský portál',
	'portal:backoffice' => ITOP_APPLICATION_SHORT.' Back-Office',

	'UI:CurrentObjectIsLockedBy_User' => 'Objekt je uzamčen, protože ho nyní upravuje %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Objekt právě upravuje %1$s. Vaše úpravy nemohou být odeslány, protože by byly přepsány.',
	'UI:CurrentObjectIsSoftLockedBy_User' => 'The object is currently being modified by %1$s. You\'ll be able to submit your modifications once they have finished.~~',
	'UI:CurrentObjectLockExpired' => 'Zámek objektu vypršel.',
	'UI:CurrentObjectLockExpired_Explanation' => 'Objekt byl znovu odemčen. Nemůžete odeslat své úpravy, protože objekt mezitím mohl být upraven někým jiným.',
	'UI:ConcurrentLockKilled' => 'Váš zámek tohoto objektu byl odstraněn někým jiným.',
	'UI:Menu:KillConcurrentLock' => 'Odtranit zámek. (Znemožní uložení úprav osobě, která zámek vytvořila)',

	'UI:Menu:ExportPDF' => 'PDF export',
	'UI:Menu:PrintableVersion' => 'Verze pro tisk',

	'UI:BrowseInlineImages' => 'Procházet obrázky...',
	'UI:UploadInlineImageLegend' => 'Nahrát nový obrázek',
	'UI:SelectInlineImageToUpload' => 'Vyberte obrázek',
	'UI:AvailableInlineImagesLegend' => 'Dostupné obrázky',
	'UI:NoInlineImage' => 'Na serveru není dostupný žádný obrázek. Nahrajte nějaký pomocí tlačítka výše.',

	'UI:ToggleFullScreen' => 'Přepnout zobrazení',
	'UI:Button:ResetImage' => 'Obnovit původní obrázek',
	'UI:Button:RemoveImage' => 'Odebrat obrázek',
	'UI:Button:UploadImage' => 'Upload an image from the disk~~',
	'UI:UploadNotSupportedInThisMode' => 'Úprava obrázků není v tomto režimu podporována.',

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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
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


Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Menu:DataSources' => 'Zdroje dat pro synchronizaci',
	'Menu:DataSources+' => 'Všechny zdroje dat pro synchronizaci',
	'Menu:WelcomeMenu' => 'Vítejte',
	'Menu:WelcomeMenu+' => 'Vítejte v '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenuPage' => 'Vítejte',
	'Menu:WelcomeMenuPage+' => 'Vítejte v '.ITOP_APPLICATION_SHORT,
	'Menu:AdminTools' => 'Administrace',
	'Menu:AdminTools+' => 'Nástroje pro administraci',
	'Menu:AdminTools?' => 'Nástroje přístupné pouze uživatelům, kteří mají potřbná oprávnění',
	'Menu:DataModelMenu' => 'Datový model',
	'Menu:DataModelMenu+' => 'Přehled datového modelu',
	'Menu:ExportMenu' => 'Exportovat',
	'Menu:ExportMenu+' => 'Exportovat výsledky jakéhokoli dotazu do HTML, CSV nebo XML',
	'Menu:NotificationsMenu' => 'Upozornění',
	'Menu:NotificationsMenu+' => 'Konfigurace upozornění',
	'Menu:AuditCategories' => 'Kategorie auditu',
	'Menu:AuditCategories+' => 'Kategorie auditu',
	'Menu:Notifications:Title' => 'Kategorie auditu',
	'Menu:RunQueriesMenu' => 'Provést dotaz',
	'Menu:RunQueriesMenu+' => 'Provést dotaz',
	'Menu:QueryMenu' => 'Knihovna dotazů',
	'Menu:QueryMenu+' => 'Knihovna dotazů',
	'Menu:UniversalSearchMenu' => 'Univerzální hledání',
	'Menu:UniversalSearchMenu+' => 'Hledejte cokoli...',
	'Menu:UserManagementMenu' => 'Správa uživatelů',
	'Menu:UserManagementMenu+' => 'Správa uživatelů',
	'Menu:ProfilesMenu' => 'Profily (Role)',
	'Menu:ProfilesMenu+' => 'Profily (Role)',
	'Menu:ProfilesMenu:Title' => 'Profily (Role)',
	'Menu:UserAccountsMenu' => 'Uživatelské účty',
	'Menu:UserAccountsMenu+' => 'Uživatelské účty',
	'Menu:UserAccountsMenu:Title' => 'Uživatelské účty',
	'Menu:MyShortcuts' => 'Mé odkazy',
	'Menu:UserManagement' => 'User Management~~',
	'Menu:Queries' => 'Queries~~',
	'Menu:ConfigurationTools' => 'Configuration~~',
));

// Additional language entries not present in English dict
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
 'UI:Toggle:StandardDashboard' => 'Standard~~',
 'UI:Toggle:CustomDashboard' => 'Custom~~',
 'UI:Dashboard:Edit' => 'Upravit tuto stránku...',
 'UI:Dashboard:Revert' => 'Vrátit se k původní verzi...',
));
