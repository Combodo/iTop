<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify	
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 *
 *
 */
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:AuditCategory' => 'Kategória auditu',
	'Class:AuditCategory+' => '',
	'Class:AuditCategory/Attribute:name' => 'Názov kategórie',
	'Class:AuditCategory/Attribute:name+' => '',
	'Class:AuditCategory/Attribute:description' => 'Popis kategórie auditu',
	'Class:AuditCategory/Attribute:description+' => '',
	'Class:AuditCategory/Attribute:definition_set' => 'Definícia nastavená',
	'Class:AuditCategory/Attribute:definition_set+' => '',
	'Class:AuditCategory/Attribute:rules_list' => 'Pravidlá auditu',
	'Class:AuditCategory/Attribute:rules_list+' => '',
));

//
// Class: AuditRule
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:AuditRule' => 'Pravidlo auditu',
	'Class:AuditRule+' => '',
	'Class:AuditRule/Attribute:name' => 'Názov pravidla',
	'Class:AuditRule/Attribute:name+' => '',
	'Class:AuditRule/Attribute:description' => 'Popis pravidla auditu',
	'Class:AuditRule/Attribute:description+' => '',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => 'Spustenie dopytu',
	'Class:AuditRule/Attribute:query+' => '',
	'Class:AuditRule/Attribute:valid_flag' => 'Platný objekt?',
	'Class:AuditRule/Attribute:valid_flag+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Správny',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => '',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Nesprávný',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => '',
	'Class:AuditRule/Attribute:category_id' => 'Kategória',
	'Class:AuditRule/Attribute:category_id+' => '',
	'Class:AuditRule/Attribute:category_name' => 'Kategória',
	'Class:AuditRule/Attribute:category_name+' => '',
));

//
// Class: QueryOQL
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:Query' => 'Dopyt',
	'Class:Query+' => '',
	'Class:Query/Attribute:name' => 'Názov',
	'Class:Query/Attribute:name+' => '',
	'Class:Query/Attribute:description' => 'Popis',
	'Class:Query/Attribute:description+' => '',
	'Class:QueryOQL/Attribute:fields' => 'Polia',
	'Class:QueryOQL/Attribute:fields+' => 'Comma separated list of attributes (or alias.attribute) to export~~',
	'Class:QueryOQL' => 'OQL Dopyt',
	'Class:QueryOQL+' => '',
	'Class:QueryOQL/Attribute:oql' => 'Výraz',
	'Class:QueryOQL/Attribute:oql+' => '',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:User' => 'Užívateľ',
	'Class:User+' => '',
	'Class:User/Attribute:finalclass' => 'Typ účtu',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Kontakt (osoba)',
	'Class:User/Attribute:contactid+' => '',
	'Class:User/Attribute:org_id' => 'Organizácia',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => 'Priezvisko',
	'Class:User/Attribute:last_name+' => '',
	'Class:User/Attribute:first_name' => 'Krstné meno',
	'Class:User/Attribute:first_name+' => '',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => '',
	'Class:User/Attribute:login' => 'Prihlasovacie meno',
	'Class:User/Attribute:login+' => '',
	'Class:User/Attribute:language' => 'Jazyk',
	'Class:User/Attribute:language+' => '',
	'Class:User/Attribute:language/Value:EN US' => 'Angličtina',
	'Class:User/Attribute:language/Value:EN US+' => '',
	'Class:User/Attribute:language/Value:FR FR' => 'Francúzština',
	'Class:User/Attribute:language/Value:FR FR+' => '',
	'Class:User/Attribute:profile_list' => 'Profily',
	'Class:User/Attribute:profile_list+' => '',
	'Class:User/Attribute:allowed_org_list' => 'Povolené organizácie',
	'Class:User/Attribute:allowed_org_list+' => '',
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',

	'Class:User/Error:LoginMustBeUnique' => 'Prihlasovacie meno musí byť jedinečné - "%1s" sa už používa.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Aspoň jeden profil musí byť priradený k profilu.',
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

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_Profiles' => 'Profily',
	'Class:URP_Profiles+' => '',
	'Class:URP_Profiles/Attribute:name' => 'Názov',
	'Class:URP_Profiles/Attribute:name+' => '',
	'Class:URP_Profiles/Attribute:description' => 'Popis',
	'Class:URP_Profiles/Attribute:description+' => '',
	'Class:URP_Profiles/Attribute:user_list' => 'Užívatelia',
	'Class:URP_Profiles/Attribute:user_list+' => '',
));

//
// Class: URP_Dimensions
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_Dimensions' => 'Rozmery',
	'Class:URP_Dimensions+' => '',
	'Class:URP_Dimensions/Attribute:name' => 'Názov rozmeru',
	'Class:URP_Dimensions/Attribute:name+' => '',
	'Class:URP_Dimensions/Attribute:description' => 'Popis rozmeru',
	'Class:URP_Dimensions/Attribute:description+' => '',
	'Class:URP_Dimensions/Attribute:type' => 'Typ rozmeru',
	'Class:URP_Dimensions/Attribute:type+' => '',
));

//
// Class: URP_UserProfile
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_UserProfile' => 'Z užívateľa na profil',
	'Class:URP_UserProfile+' => '',
	'Class:URP_UserProfile/Attribute:userid' => 'Užívateľ',
	'Class:URP_UserProfile/Attribute:userid+' => '',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Prihlasovacie meno',
	'Class:URP_UserProfile/Attribute:userlogin+' => '',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => '',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => '',
	'Class:URP_UserProfile/Attribute:reason' => 'Dôvod',
	'Class:URP_UserProfile/Attribute:reason+' => '',
));

//
// Class: URP_UserOrg
//


Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_UserOrg' => 'Užívateľské organizácie',
	'Class:URP_UserOrg+' => '',
	'Class:URP_UserOrg/Attribute:userid' => 'Užívateľ',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Prihlasovacie meno',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organizácia',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Názov povolenej organizácie',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
	'Class:URP_UserOrg/Attribute:reason' => 'Dôvod',
	'Class:URP_UserOrg/Attribute:reason+' => '',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_ProfileProjection' => 'Projekcia profilu',
	'Class:URP_ProfileProjection+' => '',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'ID rozmeru',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => '',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Rozmer',
	'Class:URP_ProfileProjection/Attribute:dimension+' => '',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'ID Profilu',
	'Class:URP_ProfileProjection/Attribute:profileid+' => '',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profile+' => '',
	'Class:URP_ProfileProjection/Attribute:value' => 'Hodnota',
	'Class:URP_ProfileProjection/Attribute:value+' => '',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Atribút',
	'Class:URP_ProfileProjection/Attribute:attribute+' => '',
));

//
// Class: URP_ClassProjection
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_ClassProjection' => 'Projekcia triedy',
	'Class:URP_ClassProjection+' => '',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'ID rozmeru',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => '',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Rozmer',
	'Class:URP_ClassProjection/Attribute:dimension+' => '',
	'Class:URP_ClassProjection/Attribute:class' => 'Trieda',
	'Class:URP_ClassProjection/Attribute:class+' => '',
	'Class:URP_ClassProjection/Attribute:value' => 'Hodnota',
	'Class:URP_ClassProjection/Attribute:value+' => '',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Atribút',
	'Class:URP_ClassProjection/Attribute:attribute+' => '',
));

//
// Class: URP_ActionGrant
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_ActionGrant' => 'Povolenia akcie',
	'Class:URP_ActionGrant+' => '',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => '',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => '',
	'Class:URP_ActionGrant/Attribute:class' => 'Trieda',
	'Class:URP_ActionGrant/Attribute:class+' => '',
	'Class:URP_ActionGrant/Attribute:permission' => 'Povolenie',
	'Class:URP_ActionGrant/Attribute:permission+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Áno',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => '',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nie',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => '',
	'Class:URP_ActionGrant/Attribute:action' => 'Akcia',
	'Class:URP_ActionGrant/Attribute:action+' => '',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_StimulusGrant' => 'Povolenia stimulu',
	'Class:URP_StimulusGrant+' => '',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'ID Profilu',
	'Class:URP_StimulusGrant/Attribute:profileid+' => '',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => '',
	'Class:URP_StimulusGrant/Attribute:class' => 'Trieda',
	'Class:URP_StimulusGrant/Attribute:class+' => '',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Povolenie',
	'Class:URP_StimulusGrant/Attribute:permission+' => '',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Áno',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => '',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nie',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => '',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Podnet',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => '',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Class:URP_AttributeGrant' => 'Udelenie atribútu',
	'Class:URP_AttributeGrant+' => '',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Udelenie akcie',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Kód atribútu',
	'Class:URP_AttributeGrant/Attribute:attcode+' => '',
));

//
// Class: UserDashboard
//
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'BooleanLabel:yes' => 'yes~~',
	'BooleanLabel:no' => 'no~~',
	'UI:Login:Title' => 'iTop login~~',
	'Menu:WelcomeMenu' => 'Vitajte', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Vitajte', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Vitajte v iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop je kompletne voľne šíriteľný operačný IT program.</p>
	
<ul>Zahŕňa:
<li>Kompletnú CMDB (Konfiguračná databáza manažmentu) pre zdokumentovanie a manažovanie IT inventáru.</li>
<li>Modul manažmentu incidentov pre sledovanie a komunikovanie o všetkých problémoch vyskytujúcich sa v IT.</li>
<li>Modul manažmentu zmien pre plánovanie a sledovanie zmien v IT prostredí.</li>
<li>Databáza známych chýb pre urýchlenie riešenia incidentov.</li>
<li>Modul výpadkov pre zdokumentovanie všetkých plánovaných výpadkov a oboznámenie vhodných kontaktov o výpadkoch.</li>
<li>Dashboard panel pre rýchle získanie prehľadu o Vašom IT.</li>
</ul>
<p>Všetky moduly môžu byť nastavené, krok po kroku, nezávisle jeden od druhého.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop orientovaný na poskytovateľa služieb, dovoľuje IT technikom ľahko manažovať viacerých zákazníkov alebo organizácií.
<ul>iTop, dodáva súbor biznis procesov bohatých na služby, ktoré:
<li>Zdokonalujú efektivitu IT manažmentu</li> 
<li>Poháňa výkon IT operácií</li> 
<li>Zlepšuje spokojnosť zákazníka a poskytuje vedúcim osobám náhľad do výkonu biznisu.</li>
</ul>
</p>
<p>iTop je kompletne otvorený myšlienke byť integrovaný vo Vašej súčasnej infraštruktúre IT manažmentu.</p>
<p>
<ul>Adoptovanie tejto novej generácie IT operačného portálu Vám pomôže:
<li>Lepšie manažovať viac a viac zložitejšie IT prostredie.</li>
<li>Implementovať ITIL procesy Vaším vlastným tempom.</li>
<li>Manažovať najdôležitejšie aktíva Vášho IT: Dokumentáciu.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Otvoriť žiadosť: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Moje žiadosti',
	'UI:WelcomeMenu:OpenIncidents' => 'Otvoriť incidenty: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Konfiguračné položky: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Mne priradené incidenty',
	'UI:AllOrganizations' => ' Všetky organizácie ',
	'UI:YourSearch' => 'Vaše vyhľadávanie',
	'UI:LoggedAsMessage' => 'Prihlásený ako %1$s',
	'UI:LoggedAsMessage+Admin' => 'Prihlásený ako %1$s (Administrátor)',
	'UI:Button:Logoff' => 'Odhlásenie',
	'UI:Button:GlobalSearch' => 'Globálne Vyhľadávanie',
	'UI:Button:Search' => ' Vyhľadávanie',
	'UI:Button:Query' => ' Dopyt ',
	'UI:Button:Ok' => 'OK',
	'UI:Button:Save' => 'Uložiť',
	'UI:Button:Cancel' => 'Zrušiť',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Použiť',
	'UI:Button:Back' => ' << Späť ',
	'UI:Button:Restart' => ' |<< Reštart ',
	'UI:Button:Next' => ' Ďalší >> ',
	'UI:Button:Finish' => ' Ukončiť ',
	'UI:Button:DoImport' => ' Spustiť importáciu ! ',
	'UI:Button:Done' => ' Vykonané ',
	'UI:Button:SimulateImport' => ' Simulovať importáciu ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Vyhodnotiť ',
	'UI:Button:Evaluate:Title' => ' Evaluate (Ctrl+Enter)~~',
	'UI:Button:AddObject' => ' Pridať... ',
	'UI:Button:BrowseObjects' => ' Vyhľadať objekt... ',
	'UI:Button:Add' => ' Pridať ',
	'UI:Button:AddToList' => ' << Pridať ',
	'UI:Button:RemoveFromList' => ' Odstrániť >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Vytvoriť ',
	'UI:Button:Delete' => ' Vymazať ! ',
	'UI:Button:Rename' => ' Premenovať... ',
	'UI:Button:ChangePassword' => ' Zmeniť heslo ',
	'UI:Button:ResetPassword' => ' Reset hesla ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Vyhľadávanie',
	'UI:ClickToCreateNew' => 'Vytvoriť nové %1$s',
	'UI:SearchFor_Class' => 'Vyhľadávanie pre %1$s objekty',
	'UI:NoObjectToDisplay' => 'Žiadny objekt na zobrazenie.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter objekt_id je potrebný, keď je špecifikovaný link_attr . Skontrolujte definíciu šablóny zobrazenia.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr je potrebný, keď je špecifikovaný link_attr . Skontrolujte definíciu šablóny zobrazenia.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by je povinný. Skontrolujte definíciu šablóny zobrazenia.',
	'UI:Error:InvalidGroupByFields' => 'Neplatný zoznam polí pre skupinu podľa: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Chyba: nepodporovaný štýl bloku: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Nesprávna definícia spojenia : trieda objektov na manažovanie : %l$s nebol nájdený ako externý kľúč v triede %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objekt: %1$s:%2$d nebol nájdený.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Chyba: Cyklický odkaz v závislostiach medzi poliami, skontrolujte dátový model.',
	'UI:Error:UploadedFileTooBig' => 'Nahraný súbor je príliš veľký. (Max povolená veľkosť je %1$s). Ak chcete zmeniť tento limit, obráťte sa na správcu ITOP . (Skontrolujte, PHP konfiguráciu pre upload_max_filesize a post_max_size na serveri).',
	'UI:Error:UploadedFileTruncated.' => 'Nahraný súbor bol skrátený !',
	'UI:Error:NoTmpDir' => 'Dočasný adresár nie je definovaný.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Nepodarilo sa zapísať dočasný súbor na disk . upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Nahrávanie zastavené rozšírením. (Pôvodné meno súboru = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Nahranie súboru zlyhalo z neznámej príčiny . ( Kód chyby = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Chyba: následujúci parameter musí byť zadaný pre túto operáciu: %1$s.',
	'UI:Error:2ParametersMissing' => 'Chyba: následujúce parametre musia byť zadané pre túto operáciu: %1$s a %2$s.',
	'UI:Error:3ParametersMissing' => 'Chyba: následujúce parametre musia byť zadané pre túto operáciu: %1$s, %2$s a %3$s.',
	'UI:Error:4ParametersMissing' => 'Chyba: následujúce parametre musia byť zadané pre túto operáciu: %1$s, %2$s, %3$s a %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Chyba: nesprávny OQL dopyt: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Vyskytla sa chyba počas dopytu: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Chyba: objekt bol už aktualizovaný.',
	'UI:Error:ObjectCannotBeUpdated' => 'Chyba: objekt nemôže byť aktualizovaný.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Chyba: objekty už boli vymazané!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Nemáte povolenie vykonať hromadné vymazanie objektov triedy %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Nemáte povolenie na vymazanie objektov triedy %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Nemáte povolenie na vykonanie hromadnej aktualizácie objektov triedy %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Chyba: objekt už bol klonovaný!',
	'UI:Error:ObjectAlreadyCreated' => 'Chyba: objekt už bol vytvorený!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Chyba: neplatný podnet "%1$s" na objekt %2$s v stave "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

	'UI:GroupBy:Count' => 'Počet',
	'UI:GroupBy:Count+' => '',
	'UI:CountOfObjects' => '%1$d objekt/y/ov sa nezhoduje s kritériami.',
	'UI_CountOfObjectsShort' => '%1$d objekt/y/ov.',
	'UI:NoObject_Class_ToDisplay' => 'Žiadne %1$s na zobrazenie',
	'UI:History:LastModified_On_By' => 'Posledná úprava %1$s %2$s.',
	'UI:HistoryTab' => 'História',
	'UI:NotificationsTab' => 'Upozornenia',
	'UI:History:BulkImports' => 'História',
	'UI:History:BulkImports+' => '',
	'UI:History:BulkImportDetails' => 'Zmeny vyplývajúce z importu CSV vykonané %1$s (%2$s)',
	'UI:History:Date' => 'Dátum',
	'UI:History:Date+' => '',
	'UI:History:User' => 'Užívateľ',
	'UI:History:User+' => '',
	'UI:History:Changes' => 'Zmeny',
	'UI:History:Changes+' => '',
	'UI:History:StatsCreations' => 'Vytvorený',
	'UI:History:StatsCreations+' => '',
	'UI:History:StatsModifs' => 'Upravený',
	'UI:History:StatsModifs+' => '',
	'UI:History:StatsDeletes' => 'Vymazané',
	'UI:History:StatsDeletes+' => '',
	'UI:Loading' => 'Načitavam...',
	'UI:Menu:Actions' => 'Akcie',
	'UI:Menu:OtherActions' => 'Ostatné akcie',
	'UI:Menu:New' => 'Nové...',
	'UI:Menu:Add' => 'Pridať...',
	'UI:Menu:Manage' => 'Manažovať...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export',
	'UI:Menu:Modify' => 'Upraviť...',
	'UI:Menu:Delete' => 'Vymazať...',
	'UI:Menu:BulkDelete' => 'Vymazať...',
	'UI:UndefinedObject' => 'Nedefinovaný objekt',
	'UI:Document:OpenInNewWindow:Download' => 'Otvoriť v novom okne: %1$s, stiahnuť: %2$s',
	'UI:SplitDateTime-Date' => 'Dátum',
	'UI:SplitDateTime-Time' => 'Čas',
	'UI:TruncatedResults' => '%1$d objektov zobrazených z %2$d',
	'UI:DisplayAll' => 'Zobraziť všetko',
	'UI:CollapseList' => 'Kolapsový zoznam',
	'UI:CountOfResults' => '%1$d objekt/y/ov',
	'UI:ChangesLogTitle' => 'Denník zmien (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Dennik zmien je prázdny',
	'UI:SearchFor_Class_Objects' => 'Vyhľadávanie pre %1$s objekt/y/ov',
	'UI:OQLQueryBuilderTitle' => 'Stavba OQL dopytu',
	'UI:OQLQueryTab' => 'OQL Dopyt',
	'UI:SimpleSearchTab' => 'Jednoduché vyhľadávanie',
	'UI:Details+' => '',
	'UI:SearchValue:Any' => '* Akýkoľvek *',
	'UI:SearchValue:Mixed' => '* Kombinovaný *',
	'UI:SearchValue:NbSelected' => '# vybraných',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- Vyberte jeden --',
	'UI:Login:Welcome' => 'Vitajte v iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Nesprávne prihlasovacie meno/heslo, prosím skúste znova.',
	'UI:Login:IdentifyYourself' => 'Identifikujte sa pred pokračovaním',
	'UI:Login:UserNamePrompt' => 'Užívateľské meno',
	'UI:Login:PasswordPrompt' => 'Heslo',
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

	'UI:Login:About' => 'O účte',
	'UI:Login:ChangeYourPassword' => 'Zmeň heslo',
	'UI:Login:OldPasswordPrompt' => 'Staré heslo',
	'UI:Login:NewPasswordPrompt' => 'Nové heslo',
	'UI:Login:RetypeNewPasswordPrompt' => 'Znova zadaj nové heslo',
	'UI:Login:IncorrectOldPassword' => 'Chyba: staré heslo je nesprávne',
	'UI:LogOffMenu' => 'Odhlásenie',
	'UI:LogOff:ThankYou' => 'Ďakujeme za používanie iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Kliknite sem pre nové prihlásenie...',
	'UI:ChangePwdMenu' => 'Zmeniť heslo...',
	'UI:Login:PasswordChanged' => 'Heslo úspešne nastavené !',
	'UI:AccessRO-All' => 'iTop je iba na čítanie',
	'UI:AccessRO-Users' => 'iTop je iba na čítanie pre uživatelov',
	'UI:ApplicationEnvironment' => 'Aplikačné prostredie: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nové heslo a znova zadané nové heslo sa nezhodujú !',
	'UI:Button:Login' => 'Vstup do iTop',
	'UI:Login:Error:AccessRestricted' => 'Prístup do iTopu je obmedzený. Kontaktujte prosím iTop administrátora.',
	'UI:Login:Error:AccessAdmin' => 'Prístup je vyhradený len pre ľudí, ktorí majú oprávnenia od administrátora. Kontaktujte prosím iTop administrátora.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne' => '-- vyberte jeden --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignorujte toto pole --',
	'UI:CSVImport:NoData' => 'Prázdny dátový súbor..., prosím poskytnite nejaké dáta!',
	'UI:Title:DataPreview' => 'Zobrazenie dát',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Chyba: Dáta obsahujú iba jeden stĺpec. Vybrali ste vhodný oddelovací znak ?',
	'UI:CSVImport:FieldName' => 'Pole %1$d',
	'UI:CSVImport:DataLine1' => 'Dátovy riadok 1',
	'UI:CSVImport:DataLine2' => 'Dátovy riadok 2',
	'UI:CSVImport:idField' => 'ID (Primárny kľúč)',
	'UI:Title:BulkImport' => 'iTop - hromadná importácia',
	'UI:Title:BulkImport+' => '',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronizácia %1$d objektov triedy %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- vyberte jeden --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Interná chyba: "%1$s" je nesprávny kód pretože "%2$s" nie je externý kľuč triedy "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objektov ktoré ostanú nezmené.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objektov bude upravených.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objektov bude pridaných.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objektov bude mať chyby.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objektov zostalo nezmenených.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objektov bolo upravených.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objektov bolo pridaných.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objektov má chyby.',
	'UI:Title:CSVImportStep2' => 'Krok 2 z 5: Možnosti CSV dát',
	'UI:Title:CSVImportStep3' => 'Krok 3 z 5: Mapovanie dát',
	'UI:Title:CSVImportStep4' => 'Krok 4 z 5: Simulácia importu',
	'UI:Title:CSVImportStep5' => 'Krok 5 z 5: Importácia dokončený',
	'UI:CSVImport:LinesNotImported' => 'Riadky , ktoré nemožno načítať:',
	'UI:CSVImport:LinesNotImported+' => '',
	'UI:CSVImport:SeparatorComma+' => '',
	'UI:CSVImport:SeparatorSemicolon+' => '',
	'UI:CSVImport:SeparatorTab+' => '',
	'UI:CSVImport:SeparatorOther' => 'Ostatné:',
	'UI:CSVImport:QualifierDoubleQuote+' => '',
	'UI:CSVImport:QualifierSimpleQuote+' => '',
	'UI:CSVImport:QualifierOther' => 'Ostatné:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Spracovať prvý riadok ako hlavičku (názvy stĺpcov)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Preskočiť %1$s riadkov na začiatku súboru',
	'UI:CSVImport:CSVDataPreview' => 'Náhľad CSV dát',
	'UI:CSVImport:SelectFile' => 'Vyberte súbor na importovanie:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Načítať zo suboru',
	'UI:CSVImport:Tab:CopyPaste' => 'Kopírovanie a vkladanie dát',
	'UI:CSVImport:Tab:Templates' => 'Šablóny',
	'UI:CSVImport:PasteData' => 'Vložiť dáta na importovanie:',
	'UI:CSVImport:PickClassForTemplate' => 'Vyberte šablónu na stiahnutie: ',
	'UI:CSVImport:SeparatorCharacter' => 'Oddeľovací znak:',
	'UI:CSVImport:TextQualifierCharacter' => 'Znak kvalifikátoru textu',
	'UI:CSVImport:CommentsAndHeader' => 'Komentáre a hlavička',
	'UI:CSVImport:SelectClass' => 'Vyberte triedu na importovanie:',
	'UI:CSVImport:AdvancedMode' => 'Rozšírený režim',
	'UI:CSVImport:AdvancedMode+' => '',
	'UI:CSVImport:SelectAClassFirst' => 'Ak chcete nakonfigurovať mapovanie , vyberte najprv triedu.',
	'UI:CSVImport:HeaderFields' => 'Polia',
	'UI:CSVImport:HeaderMappings' => 'Mapovanie',
	'UI:CSVImport:HeaderSearch' => 'Vyhľadávanie?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Vyberte mapovanie pre každé pole.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Vyberte aspoň jedno alebo viac kritérií vyhladávania',
	'UI:CSVImport:Encoding' => 'Kódovanie znakov',
	'UI:UniversalSearchTitle' => 'iTop - Univerzálne vyhľadávanie',
	'UI:UniversalSearch:Error' => 'Chyba: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Vyberte triedu na vyhľadávanie: ',

	'UI:CSVReport-Value-Modified' => 'Upravený',
	'UI:CSVReport-Value-SetIssue' => 'Nemožno zmeniť - dôvod: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Nemožno zmeniť na %1$s - dôvod: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Žiadna zhoda',
	'UI:CSVReport-Value-Missing' => 'Chýbajúca povinná hodnota',
	'UI:CSVReport-Value-Ambiguous' => 'Nejednoznačné: nájdených %1$s objektov',
	'UI:CSVReport-Row-Unchanged' => 'Nezmený',
	'UI:CSVReport-Row-Created' => 'Vytvorený',
	'UI:CSVReport-Row-Updated' => 'Aktualizovaných %1$d stĺpcov',
	'UI:CSVReport-Row-Disappeared' => 'Zmiznutých, zmenených %1$d stĺpcov',
	'UI:CSVReport-Row-Issue' => 'Problém: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null nie je povolený',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objekt nenájdený',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Nájdených %1$d zhôd',
	'UI:CSVReport-Value-Issue-Readonly' => 'Atribút \'%1$s\' je len na čítanie a nemožno ho zmeniť (súčasná hodnota: %2$s, navrhovaná hodnota: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Spracovanie vstupu zlyhalo: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Neočakávaná hodnota pre atribút \'%1$s\': žiadny zhoda nebola nájdená, skontrolujte hláskovanie',
	'UI:CSVReport-Value-Issue-Unknown' => 'Neočakávaná hodnota pre atribút \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Atribúty nie sú konzistentné jeden s druhým: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Neočakávaná/é hodnota/y atribútu/ov ',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Nemôže byť vytvorený, v dôsledku chýbajúceho kľúča/ov: %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Nesprávny formát dátumu',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Zlyhalo schválenie',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Nejednoznačné schválenie',
	'UI:CSVReport-Row-Issue-Internal' => 'Interná chyba: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Nezmené',
	'UI:CSVReport-Icon-Modified' => 'Upravené',
	'UI:CSVReport-Icon-Missing' => 'Chýbajúce',
	'UI:CSVReport-Object-MissingToUpdate' => 'Chýbajúci objekt: bude aktualizovaný',
	'UI:CSVReport-Object-MissingUpdated' => 'Chýbajúci objekt: aktualizovaný',
	'UI:CSVReport-Icon-Created' => 'Vytvorený',
	'UI:CSVReport-Object-ToCreate' => 'Objekt bude vytvorený',
	'UI:CSVReport-Object-Created' => 'Objekt bol vytvorený',
	'UI:CSVReport-Icon-Error' => 'Chyba',
	'UI:CSVReport-Object-Error' => 'Chyba: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'NEJEDNOZNAČNÉ: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% načitaných objektov má chyby a budú ignorované.',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% načitaných objektov bude vytvorených.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% načitaných objektov bude upravených.',

	'UI:CSVExport:AdvancedMode' => 'Rozšírený režim',
	'UI:CSVExport:AdvancedMode+' => '',
	'UI:CSVExport:LostChars' => 'Kódovanie problému',
	'UI:CSVExport:LostChars+' => '',

	'UI:Audit:Title' => 'iTop - CMDB audit',
	'UI:Audit:InteractiveAudit' => 'Interaktívny audit',
	'UI:Audit:HeaderAuditRule' => 'Pravidlo auditu',
	'UI:Audit:HeaderNbObjects' => '# Objekty',
	'UI:Audit:HeaderNbErrors' => '# Chyby',
	'UI:Audit:PercentageOk' => '% OK',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL chyba v pravidle %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL chyba v kategórii %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - Určenie OQL Dopytu',
	'UI:RunQuery:QueryExamples' => 'Príklad dopytu',
	'UI:RunQuery:HeaderPurpose' => 'Účel',
	'UI:RunQuery:HeaderPurpose+' => '',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL Výraz',
	'UI:RunQuery:HeaderOQLExpression+' => '',
	'UI:RunQuery:ExpressionToEvaluate' => 'Výraz k určeniu: ',
	'UI:RunQuery:MoreInfo' => 'Viac informácií o dopyte: ',
	'UI:RunQuery:DevelopedQuery' => 'Dopyt rozvinutého výrazu: ',
	'UI:RunQuery:SerializedFilter' => 'Serializovaný filter: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Vyskytla sa chyba počas dopytu: %1$s',
	'UI:Query:UrlForExcel' => 'URL pre použitie MS-Excel webového dopytu',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested here below points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. <br/>Should you want to guarantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
	'UI:Schema:Title' => 'iTop objektová schéma',
	'UI:Schema:CategoryMenuItem' => 'Kategória <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Vzťahy',
	'UI:Schema:AbstractClass' => 'Abstraktná trieda: žiadny objekt z tejto triedy nemôže byť inštancovaný.',
	'UI:Schema:NonAbstractClass' => 'Žiadna abstraktná trieda: objekty z tejto triedy nemôžu byť inštancované.',
	'UI:Schema:ClassHierarchyTitle' => 'Hierarchia triedy',
	'UI:Schema:AllClasses' => 'Všetky triedy',
	'UI:Schema:ExternalKey_To' => 'Externý kľuč pre %1$s',
	'UI:Schema:Columns_Description' => 'Stĺpce: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Štandardné: "%1$s"',
	'UI:Schema:NullAllowed' => 'Prázdna hodnota povolená',
	'UI:Schema:NullNotAllowed' => 'Prázdna hodnota nie je povolená',
	'UI:Schema:Attributes' => 'Atribúty',
	'UI:Schema:AttributeCode' => 'Kód atributu',
	'UI:Schema:AttributeCode+' => '',
	'UI:Schema:Label' => 'Označenie',
	'UI:Schema:Label+' => '',
	'UI:Schema:Type' => 'Typ',

	'UI:Schema:Type+' => '',
	'UI:Schema:Origin' => 'Pôvod',
	'UI:Schema:Origin+' => '',
	'UI:Schema:Description' => 'Popis',
	'UI:Schema:Description+' => '',
	'UI:Schema:AllowedValues' => 'Povolené hodnoty',
	'UI:Schema:AllowedValues+' => '',
	'UI:Schema:MoreInfo' => 'Viac info',
	'UI:Schema:MoreInfo+' => '',
	'UI:Schema:SearchCriteria' => 'Kritéria vyhľadávania',
	'UI:Schema:FilterCode' => 'Kód filtru',
	'UI:Schema:FilterCode+' => '',
	'UI:Schema:FilterDescription' => 'Popis',
	'UI:Schema:FilterDescription+' => '',
	'UI:Schema:AvailOperators' => 'Dostupní operátori',
	'UI:Schema:AvailOperators+' => '',
	'UI:Schema:ChildClasses' => 'Child triedy',
	'UI:Schema:ReferencingClasses' => 'Odkazovanie na triedy',
	'UI:Schema:RelatedClasses' => 'Príbuzná triedy',
	'UI:Schema:LifeCycle' => 'Životný cyklus',
	'UI:Schema:Triggers' => 'Spúštače',
	'UI:Schema:Relation_Code_Description' => 'Vzťah <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Neaktívne: %1$s',
	'UI:Schema:RelationUp_Description' => 'Aktívne: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propagovať do %2$d úrovní, dopyt: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: sa nepropaguje (%2$d úrovne), dopyt: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => 'na %1$s je odkazované triedou %2$s cez pole %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s je spojený s %2$s cez %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Triedy ukazujúce na %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Triedy prepojené s %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Graf všetkých príbuzných tried',
	'UI:Schema:NoLifeCyle' => 'Nie je tu žiadny životný cyklus definovaný pre túto triedu.',
	'UI:Schema:LifeCycleTransitions' => 'Prechody',
	'UI:Schema:LifeCyleAttributeOptions' => 'Možnosti atribútu',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Skrytý',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Iba na čítanie',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Povinný',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Musí sa zmeniť',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Užívateľ bude vyzvaný aby si zmenil danú hodnotu',
	'UI:Schema:LifeCycleEmptyList' => 'Prázdny zoznam',
	'UI:Schema:ClassFilter' => 'Class:~~~~',
	'UI:Schema:DisplayLabel' => 'Display:~~~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~~~',
	'UI:LinksWidget:Autocomplete+' => '',
	'UI:Edit:TestQuery' => 'Testovací dopyt',
	'UI:Combo:SelectValue' => '--- výber hodnoty ---',
	'UI:Label:SelectedObjects' => 'Zvolené objekty: ',
	'UI:Label:AvailableObjects' => 'Dostupné objekty: ',
	'UI:Link_Class_Attributes' => '%1$s atribút/y/ov',
	'UI:SelectAllToggle+' => '',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Pridať %1$s objektov prepojených s %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Pridať %1$s objektov na prepojenie s %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Manažovať %1$s objektov prpojených s %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Pridať %1$ss...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Odstrániť zvolené objekty',
	'UI:Message:EmptyList:UseAdd' => 'Zoznam je prázdny, použite "Pridať..." tlačidlo na pridanie prvkov.',
	'UI:Message:EmptyList:UseSearchForm' => 'Použite vyhľadávaciu formu vyššie na vyhľadávanie objektov, ktoré budú pridané.',
	'UI:Wizard:FinalStepTitle' => 'Finálny krok: potvrdenie',
	'UI:Title:DeletionOf_Object' => 'Odstránenie %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Hromadné odstránenie of %1$d objektov triedy %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Nie ste oprávnený vymazať tento objekt',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Nemáte povolenie aktualizovať nasledovné pole/ia: %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Tento objekt nemohol byť vymazaný pretože súčasný užívateľ nemá postačujúce práva',
	'UI:Error:CannotDeleteBecause' => 'Tento objekt nemohol byť vymazaný pretože: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Tento objekt nemohol byť vymazaný pretože niektoré manuálne operácie musia byť vykonané ešte predtým',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Tento objekt nemohol byť vymazaný pretože niektoré manuálne operácie musia byť vykonané ešte predtým',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s v mene %2$s',
	'UI:Delete:Deleted' => 'Vymazané',
	'UI:Delete:AutomaticallyDeleted' => 'Automaticky vymazané',
	'UI:Delete:AutomaticResetOf_Fields' => 'Automatický reset pola/í: %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Upratovanie všetkých odkazovaní sa na %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Upratovanie všetkých odkazovaní sa na %1$d objektov triedy %2$s...',
	'UI:Delete:Done+' => '',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s vymazané.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Vymazanie %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Vymazanie %1$d objektov triedy %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Nemohli byť vymazané: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Mali byť automaticky vymazané, ale toto nie je možné: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Musia byť vymazané manuálne, ale toto nie je možné: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'budú automaticky vymazané',
	'UI:Delete:MustBeDeletedManually' => 'Musia byť vymazané manuálne',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Mali by byť automaticky aktualizované, ale: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Budú automaticky aktualizované (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objektov/spojení odkazujú na %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objektov/spojení odkazujú na niektoré z objektov čo majú byť vymazané',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Pre zabezpečenie integrity databázy, akákoľvek súvislosť by mala byť eliminovaná',
	'UI:Delete:Consequence+' => '',
	'UI:Delete:SorryDeletionNotAllowed' => 'Prepáčte, nemáte povolenie vymazať tento objekt, pozrite si detailné vysvetlenie vyššie',
	'UI:Delete:PleaseDoTheManualOperations' => 'Prosím vykonajte manuálne operácie vypísané vyššie predtým ako budete žiadať o odstraňovanie tohto objektu',
	'UI:Delect:Confirm_Object' => 'Prosím potvrďte, že chcete vymazať %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Prosím potvrďte, že chcete vymazať nasledovné %1$d objekty triedy %2$s.',
	'UI:WelcomeToITop' => 'Vitajte to iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s detaily',
	'UI:ErrorPageTitle' => 'iTop - Chyba',
	'UI:ObjectDoesNotExist' => 'Prepáčte, tento objekt neexistuje (alebo nemáte povolenie na jeho čítanie).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => 'iTop - Výsledky vyhľadávania',
	'UI:SearchResultsTitle' => 'Search Results~~',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => 'Nie je nič na vyhľadávanie',
	'UI:Search:NeedleTooShort' => 'The search string "%1$s" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for "%1$s"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => 'Výsledky pre "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objekt/y/ov triedy %2$s nájdených.',
	'UI:Search:NoObjectFound' => 'Žiadny objekt nebol nájdený.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s úprava',
	'UI:ModificationTitle_Class_Object' => 'Úprava of %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Klon %1$s - %2$s úprava',
	'UI:CloneTitle_Class_Object' => 'Klon of %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Vytvorenie nového %1$s ',
	'UI:CreationTitle_Class' => 'Vytvorenie nového %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Zvoľte typ %1$s na vytvorenie:',
	'UI:Class_Object_NotUpdated' => 'Žiadna zmena nebola zistená, %1$s (%2$s) <strong>nebola</strong> upravená.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) aktualizovaný.',
	'UI:BulkDeletePageTitle' => 'iTop - Hromadné vymazanie',
	'UI:BulkDeleteTitle' => 'Zvoľte objekty, ktoré chcete vymazať:',
	'UI:PageTitle:ObjectCreated' => 'iTop objekt vytvorený.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s vytvorený.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Použiť %1$s na objekt: %2$s v stave %3$s do cieľového stavu: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Do objekt sa nedá zapisovať: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Fatálna chyba',
	'UI:SystemIntrusion' => 'Prístup zamietnutý. Snažili ste sa vykonať operáciu, ktorá Vám nie je povolená.',
	'UI:FatalErrorMessage' => 'Fatálna chyba, iTop nemôže pokračovať.',
	'UI:Error_Details' => 'Chyba: %1$s.',

	'UI:PageTitle:ClassProjections' => 'iTop užívateľský manažment - projekcie tried',
	'UI:PageTitle:ProfileProjections' => 'iTop užívateľský manažment - projekcie profilov',
	'UI:UserManagement:Class' => 'Trieda',
	'UI:UserManagement:Class+' => '',
	'UI:UserManagement:ProjectedObject' => 'Objekt',
	'UI:UserManagement:ProjectedObject+' => '',
	'UI:UserManagement:AnyObject' => '* Akýkoľvek *',
	'UI:UserManagement:User' => 'Užívateľ',
	'UI:UserManagement:User+' => '',
	'UI:UserManagement:Profile' => 'Profil',
	'UI:UserManagement:Profile+' => '',
	'UI:UserManagement:Action:Read' => 'Čítať',
	'UI:UserManagement:Action:Read+' => '',
	'UI:UserManagement:Action:Modify' => 'Upravovať',
	'UI:UserManagement:Action:Modify+' => '',
	'UI:UserManagement:Action:Delete' => 'Vymazať',
	'UI:UserManagement:Action:Delete+' => '',
	'UI:UserManagement:Action:BulkRead' => 'Hromadné čítanie (Export)',
	'UI:UserManagement:Action:BulkRead+' => '',
	'UI:UserManagement:Action:BulkModify' => 'Hromadná úprava',
	'UI:UserManagement:Action:BulkModify+' => '',
	'UI:UserManagement:Action:BulkDelete' => 'Hromadné vymazanie',
	'UI:UserManagement:Action:BulkDelete+' => '',
	'UI:UserManagement:Action:Stimuli' => 'Podnety',
	'UI:UserManagement:Action:Stimuli+' => '',
	'UI:UserManagement:Action' => 'Akcia',
	'UI:UserManagement:Action+' => '',
	'UI:UserManagement:TitleActions' => 'Akcie',
	'UI:UserManagement:Permission' => 'Povolenie',
	'UI:UserManagement:Permission+' => '',
	'UI:UserManagement:Attributes' => 'Atribúty',
	'UI:UserManagement:ActionAllowed:Yes' => 'Áno',
	'UI:UserManagement:ActionAllowed:No' => 'Nie',
	'UI:UserManagement:AdminProfile+' => '',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '',
	'UI:UserManagement:GrantMatrix' => 'Udelovacia matica',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Spojenie medzi %1$s a %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Spojenie medzi %1$s a %2$s',

	'Menu:AdminTools' => 'Administrátorské pomôcky', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Pomôcky prístupné iba užívateľom majúcim administrátorský profil', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Manažment zmien',
	'UI:ChangeManagementMenu+' => '',
	'UI:ChangeManagementMenu:Title' => 'Prehľad zmien',
	'UI-ChangeManagementMenu-ChangesByType' => 'Zmeny podľa typu',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Zmeny podľa stavu',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Zmeny podľa pracovnej skupiny',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Zmeny ešte nepriradené',

	'UI:ConfigurationManagementMenu' => 'Konfiguračný manažment',
	'UI:ConfigurationManagementMenu+' => '',
	'UI:ConfigurationManagementMenu:Title' => 'Prehľad infraštruktúry',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infraštruktúra objektov podľa typu',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infraštruktúra objektov podľa stavu',

	'UI:ConfigMgmtMenuOverview:Title' => 'Panel pre konfiguračný manažment',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Konfiguračné položky podľa stavu',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Konfiguračné položky podľa typu',

	'UI:RequestMgmtMenuOverview:Title' => 'Panel pre manažment žiadostí',
	'UI-RequestManagementOverview-RequestByService' => 'Užívateľská žiadosť podľa služby',
	'UI-RequestManagementOverview-RequestByPriority' => 'Užívateľská žiadosť podľa priority',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Užívateľská žiadosť zatiaľ nepriradená agentovi',

	'UI:IncidentMgmtMenuOverview:Title' => 'Panel pre manažment incidentov',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidenty podľa služby',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenty podľa priority',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidenty zatiaľ nepriradené agentovi',

	'UI:ChangeMgmtMenuOverview:Title' => 'Panel pre manažment zmien',
	'UI-ChangeManagementOverview-ChangeByType' => 'Zmeny podľa typu',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Zmeny zatiaľ nepriradené agentovi',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Výpadky v dôsledku zmien',

	'UI:ServiceMgmtMenuOverview:Title' => 'Panel manažment služieb',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Zákaznícke kontrakty na obnovenie v najbližších 30 dňoch',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Poskytovateľské kontrakty na obnovenie v najbližších 30 dňoch',

	'UI:ContactsMenu' => 'Kontakty',
	'UI:ContactsMenu+' => '',
	'UI:ContactsMenu:Title' => 'Prehľad kontaktov',
	'UI-ContactsMenu-ContactsByLocation' => 'Kontakty podľa polohy',
	'UI-ContactsMenu-ContactsByType' => 'Kontakty podľa typu',
	'UI-ContactsMenu-ContactsByStatus' => 'Kontakty podľa stavu',

	'Menu:CSVImportMenu' => 'CSV import', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Dátový model', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Upozornenia', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Konfigurácia <span class="hilite">Upozornení</span>',
	'UI:NotificationsMenu:Help' => 'Pomoc',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop upozornenia sú plne upraviteľné. Sú založené na dvoch setoch objektov: <i>spúštače a akcie</i>.</p>
<p><i><b>Spúštače</b></i> definujte bude spustené nejaké upozornenie. Máme 5 typov spúštačov pre pokrytie 3 rôznych fáz životného cyklu objektu:
<ol>
	<li>"pri vytvorení objektu" spúštače budú vykonané keď objekt špecifikovanej triedy je vytvorený</li>
	<li>"pri vchádzaní do stavu" spúštače budú vykonané predtým ako objekt danej triedy vojde do špecifikovaného stav (prichádzajúci z iného stavu)</li>
	<li>"pri opúšťaní stavu" spúštače budú vykonané keď objekt danej triedy opúšťa špecifikovaný stav</li>
	<li>"pri prekročení hranice" spúštače budú vykonané keď hranica pre TTR alebo TTO bola prekročená</li>
	<li>"pri aktualizácií portálu" spúštače budú vykonané keď je lístok aktualizovaný z portálu</li>
</ol>
</p>
<p>
<i><b>Akcie</b></i> definujte akcie, ktoré budú vykonané, keď sa spúštače spustia. Zatiaľ je tu iba 1 druh akcie pozostávajúci zo zasielania emailovej správy.
Také akcie tiež definujú šablónu, ktorá bude použitá pre zasielanie emailov ako aj ostatné parametre správy ako prijímatelia, dôležitosť, atď.
</p>
<p>Špeciálna stránka: <a href="../setup/email.test.php" target="_blank">email.test.php</a> je dostupná pre testovanie a odstraňovanie problémov Vašej PHP mailovej konfigurácie.</p>
<p>Na vykonanie, akcie musia byť priradené spúštačom.
Keď sú priradené spúštačom, každej akcii je dané číslo "príkazu", špecifikujúce v akej postupnosti budú akcie vykonané.</p>',
	'UI:NotificationsMenu:Triggers' => 'Spúštače',
	'UI:NotificationsMenu:AvailableTriggers' => 'Dostupné spúštače',
	'UI:NotificationsMenu:OnCreate' => 'Keď je objekt vytvorený',
	'UI:NotificationsMenu:OnStateEnter' => 'Keď objekt vstupuje do daného stavu',
	'UI:NotificationsMenu:OnStateLeave' => 'Keď objekt vychádza z daného stavu',
	'UI:NotificationsMenu:Actions' => 'Akcie',
	'UI:NotificationsMenu:AvailableActions' => 'Dostupné akcie',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Kategórie auditu', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Kategórie auditu', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Spustiť dopyty', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Dopyt frázy', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Dátová administrácia', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Univerzálne vyhľadávanie', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Užívateľský manažment', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profily', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profily', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Užívateľské účty', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Užívateľské účty', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => 'iTop verzia %1$s',
	'UI:iTopVersion:Long' => 'iTop verzia %1$s-%2$s postavená na %3$s',
	'UI:PropertiesTab' => 'Vlastnosti',

	'UI:OpenDocumentInNewWindow_' => 'Otvoriť tento dokument v novom okne: %1$s',
	'UI:DownloadDocument_' => 'Stiahnuť tento dokument: %1$s',
	'UI:Document:NoPreview' => 'Žiadny náhľad nie je dostupný pre tento typ dokumentu',
	'UI:Download-CSV' => 'Stiahnuť %1$s',

	'UI:DeadlineMissedBy_duration' => 'Prekročené o %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Pomoc',
	'UI:PasswordConfirm' => '(Potvrdiť)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Pred pridaním viacerých %1$s objektov, uložte tento objekt.',
	'UI:DisplayThisMessageAtStartup' => 'Zobraziť túto správu pri spustení',
	'UI:RelationshipGraph' => 'Grafický pohľad',
	'UI:RelationshipList' => 'Zoznam',
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Zrušené operácie',
	'UI:ElementsDisplayed' => 'Filtrovanie',
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
	'Portal:Title' => 'iTop užívateľský portál',
	'Portal:NoRequestMgmt' => 'Drahý/á %1$s, boli ste presmerovaný na túto stránku pretože Váš účet je nastavený na profil \'Užívateľ portálu\'. Nanešťastie, iTop nebol nainštalovaný s funkciou \'Manažment žiadostí\'. Prosím kontaktujte Vášho administrátora.',
	'Portal:Refresh' => 'Obnoviť',
	'Portal:Back' => 'Späť',
	'Portal:WelcomeUserOrg' => 'Vitajte %1$s, z %2$s',
	'Portal:TitleDetailsFor_Request' => 'Detaily pre požiadavky',
	'Portal:ShowOngoing' => 'Ukázať otvorené žiadosti',
	'Portal:ShowClosed' => 'Ukázať zatvorené žiadosti',
	'Portal:CreateNewRequest' => 'Vytvoriť novú žiadosť',
	'Portal:CreateNewRequestItil' => 'Create a new request~~',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
	'Portal:ChangeMyPassword' => 'Zmeniť moje heslo',
	'Portal:Disconnect' => 'Odpojiť',
	'Portal:OpenRequests' => 'Moje otvorené žiadosti',
	'Portal:ClosedRequests' => 'Moje closed žiadosti',
	'Portal:ResolvedRequests' => 'Moje vyriešené žiadosti',
	'Portal:SelectService' => 'Zvoľte službu z katalógu:',
	'Portal:PleaseSelectOneService' => 'Prosím zvoľte jednu službu',
	'Portal:SelectSubcategoryFrom_Service' => 'Zvoľte subkategóriu pre službu %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Prosím zvoľte jednu subkategóriu',
	'Portal:DescriptionOfTheRequest' => 'Vložte popis Vašej žiadosti:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detaily pre žiadosti %1$s:',
	'Portal:NoOpenRequest' => 'Žiadna žiadosť v tejto kategórii',
	'Portal:NoClosedRequest' => 'Žiadna žiadosť v tejto kategórii',
	'Portal:Button:ReopenTicket' => 'Znova otvoriť tento lístok',
	'Portal:Button:CloseTicket' => 'Zatvoriť tento lístok',
	'Portal:Button:UpdateRequest' => 'Aktualizovať žiadosť',
	'Portal:EnterYourCommentsOnTicket' => 'Vložte Vaše komentáre o riešení tohto lístku:',
	'Portal:ErrorNoContactForThisUser' => 'Chyba: súčasný užívateľ nemá priradený kontakt/osobu. Prosím kontaktujte Vášho administrátora.',
	'Portal:Attachments' => 'Prílohy',
	'Portal:AddAttachment' => ' Pridať prílohu ',
	'Portal:RemoveAttachment' => ' Odstrániť prílohu ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Príloha #%1$d do %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Zvoľ predlohu pre %1$s',
	'Enum:Undefined' => 'Nedefinovaný',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Dní %2$s Hodín %3$s Minút %4$s Sekúnd',
	'UI:ModifyAllPageTitle' => 'Upraviť všetko',
	'UI:Modify_N_ObjectsOf_Class' => 'Modifying %1$d objects of class %2$s~~',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Upravovanie %1$d objektov triedy %2$s z %3$d',
	'UI:Menu:ModifyAll' => 'Upraviť...',
	'UI:Button:ModifyAll' => 'Upraviť všetko',
	'UI:Button:PreviewModifications' => 'Náhľad úpravy >>',
	'UI:ModifiedObject' => 'Objekt Upravený',
	'UI:BulkModifyStatus' => 'Operácie',
	'UI:BulkModifyStatus+' => '',
	'UI:BulkModifyErrors' => 'Chyby (ak nejaké)',
	'UI:BulkModifyErrors+' => '',
	'UI:BulkModifyStatusOk' => 'OK',
	'UI:BulkModifyStatusError' => 'Chyba',
	'UI:BulkModifyStatusModified' => 'Upravený',
	'UI:BulkModifyStatusSkipped' => 'Preskočené',
	'UI:BulkModify_Count_DistinctValues' => '%1$d rozdielne hodnoty:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d krát',
	'UI:BulkModify:N_MoreValues' => '%1$d viac hodnôt...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Pokúšanie sa nastaviť "iba na čítanie" políčko: %1$s',
	'UI:FailedToApplyStimuli' => 'Akcia zlyhala.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Upravovanie %2$d objektov triedy %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Napíšte Váš text tu:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Počiatočná hodnota:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Pole %1$s nie je upravovateľné pretože je spravované dátovou synchronizáciou. Hodnota nenastavená.',
	'UI:ActionNotAllowed' => 'Nemáte povolenie vykonať túto akciu na týchto objektoch.',
	'UI:BulkAction:NoObjectSelected' => 'Prosím zvoľte aspoň jeden objekt na vykonanie tejto operácie',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Pole %1$s nie je upravovateľné pretože je spravované dátovou synchronizáciou. Hodnota zostala nezmená.',
	'UI:Pagination:HeaderSelection' => 'Celkom: %1$s objektov (%2$s objektov zvolených).',
	'UI:Pagination:HeaderNoSelection' => 'Celkom: %1$s objektov.',
	'UI:Pagination:PageSize' => '%1$s objektov na stránku',
	'UI:Pagination:PagesLabel' => 'Stránky:',
	'UI:Pagination:All' => 'Všetko',
	'UI:HierarchyOf_Class' => 'Hierarchia of %1$s',
	'UI:Preferences' => 'Preferencie...',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Obľúbené organizácie',
	'UI:FavoriteOrganizations+' => '',
	'UI:FavoriteLanguage' => 'Jazyk užívateľského rozhrania',
	'UI:Favorites:SelectYourLanguage' => 'Vyberte si svoj preferovaný jazyk',
	'UI:FavoriteOtherSettings' => 'Iné nastavenia',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Štandardná dĺžka pre zoznamy:  %1$s položiek na stránku',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Akákoľvek úprava bude zahodená.',
	'UI:CancelConfirmationMessage' => 'Prídete o všetky svoje zmeny. Chcete pokračovať?',
	'UI:AutoApplyConfirmationMessage' => 'Niektoré zmeny neboli použité zatiaľ. Chcete aby ich iTop vzal do úvahy?',
	'UI:Create_Class_InState' => 'Vytvoriť %1$s v stave: ',
	'UI:OrderByHint_Values' => 'Triediaci príkaz: %1$s',
	'UI:Menu:AddToDashboard' => 'Pridať na panel...',
	'UI:Button:Refresh' => 'Obnoviť',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Konfigurovať tento zoznam...',
	'UI:ListConfigurationTitle' => 'Zoznam konfigurácii',
	'UI:ColumnsAndSortOrder' => 'Stĺpce a triediaci príkaz:',
	'UI:UseDefaultSettings' => 'Použite štandardné nastavenia',
	'UI:UseSpecificSettings' => 'Použite nasledovné nastavenia:',
	'UI:Display_X_ItemsPerPage' => 'Zobraziť %1$s položiek na stránku',
	'UI:UseSavetheSettings' => 'Uložiť nastavenia',
	'UI:OnlyForThisList' => 'Iba pre tento zoznam',
	'UI:ForAllLists' => 'Pre všetky zoznamy',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Priateľské meno)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Posunúť hore',
	'UI:Button:MoveDown' => 'Posunúť dole',

	'UI:OQL:UnknownClassAndFix' => 'Neznáma trieda "%1$s". Môžete skúsiť "%2$s" namiesto toho.',
	'UI:OQL:UnknownClassNoFix' => 'Neznáma trieda "%1$s"',

	'UI:Dashboard:Edit' => 'Upraviť túto stránku...',
	'UI:Dashboard:Revert' => 'Vrátiť sa do originálnej verzie...',
	'UI:Dashboard:RevertConfirm' => 'Každá zmena spravená do originálnej verzie bude stratená. Prosím potvrďte, že to chcete urobiť.',
	'UI:ExportDashBoard' => 'Exportovať do súboru',
	'UI:ImportDashBoard' => 'Importovať zo súboru...',
	'UI:ImportDashboardTitle' => 'Importovať zo súboru',
	'UI:ImportDashboardText' => 'Zvoľte panel súboru na importovanie:',


	'UI:DashletCreation:Title' => 'Vytvoriť a nový Dashlet',
	'UI:DashletCreation:Dashboard' => 'Panel',
	'UI:DashletCreation:DashletType' => 'Typ Dashletu',
	'UI:DashletCreation:EditNow' => 'Upraviť panel',

	'UI:DashboardEdit:Title' => 'Panel Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Nadpis',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',

	'UI:DashboardEdit:Layout' => 'Rozloženie',
	'UI:DashboardEdit:Properties' => 'Vlastnosti panelu',
	'UI:DashboardEdit:Dashlets' => 'Dostupné Dashlety',
	'UI:DashboardEdit:DashletProperties' => 'Vlastnosti Dashletu',

	'UI:Form:Property' => 'Vlastnosť',
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
	'UI:DashletPlainText:Description' => 'Obyčajný text (žiadne formátovanie)',
	'UI:DashletPlainText:Prop-Text' => 'Text',
	'UI:DashletPlainText:Prop-Text:Default' => 'Prosím vložte nejaký text sem...',

	'UI:DashletObjectList:Label' => 'Zoznam objektu',
	'UI:DashletObjectList:Description' => 'Zoznam objektu dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Nadpis',
	'UI:DashletObjectList:Prop-Query' => 'Dopyt',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Nadpis',
	'UI:DashletGroupBy:Prop-Query' => 'Dopyt',
	'UI:DashletGroupBy:Prop-Style' => 'Štýl',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Zoskupiť podľa...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Hodina %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Mesiac %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Deň v týždni pre %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Deň v mesiaci pre %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (hodina)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (mesiac)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$ (deň v týžni)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (deň v mesiaci)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Prosím zvoľte pole na ktorom objekty budú zoskupené spolu',

	'UI:DashletGroupByPie:Label' => 'Koláčový graf',
	'UI:DashletGroupByPie:Description' => 'Koláčový graf',
	'UI:DashletGroupByBars:Label' => 'Tyčinkový graf',
	'UI:DashletGroupByBars:Description' => 'Tyčinkový graf',
	'UI:DashletGroupByTable:Label' => 'Zoskupiť podľa tabuliek (table)',
	'UI:DashletGroupByTable:Description' => 'Zoznam (zoskupené podľa polí)',

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
	'UI:DashletHeaderStatic:Description' => 'Zobrazuje an horizontálny oddelovač',
	'UI:DashletHeaderStatic:Prop-Title' => 'Nadpis',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Ikonka',

	'UI:DashletHeaderDynamic:Label' => 'Hlavička so štatistikami',
	'UI:DashletHeaderDynamic:Description' => 'Hlavička s vlastnosťami (zoskupené podľa...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Nadpis',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Ikonka',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Podnadpis',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kontakty',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Dopyt',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Zoskupiť podľa',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Hodnoty',

	'UI:DashletBadge:Label' => 'Odznak',
	'UI:DashletBadge:Description' => 'Ikonka objektu s novým/vyhľadávanie',
	'UI:DashletBadge:Prop-Class' => 'Trieda',

	'DayOfWeek-Sunday' => 'Nedeľa',
	'DayOfWeek-Monday' => 'Pondelok',
	'DayOfWeek-Tuesday' => 'Utorok',
	'DayOfWeek-Wednesday' => 'Streda',
	'DayOfWeek-Thursday' => 'Štvrtok',
	'DayOfWeek-Friday' => 'Piatok',
	'DayOfWeek-Saturday' => 'Sobota',
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

	'UI:Menu:ShortcutList' => 'Vytvorenie skratky...',
	'UI:ShortcutRenameDlg:Title' => 'Premenovanie skratky',
	'UI:ShortcutListDlg:Title' => 'Vytvoriť skratku pre zoznam',
	'UI:ShortcutDelete:Confirm' => 'Prosím potvrďte, že si želáte vymazať skratku/y.',
	'Menu:MyShortcuts' => 'Moje skratky', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Skratka',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Názov',
	'Class:Shortcut/Attribute:name+' => '',
	'Class:ShortcutOQL' => 'Skratka výsledkov vyhľadávania',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Dopyt',
	'Class:ShortcutOQL/Attribute:oql+' => '',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds~~',

	'UI:FillAllMandatoryFields' => 'Prosím vyplňte všetky povinné políčka.',
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
	'UI:About:RemoteExtensionSource' => 'iTop Hub~~',

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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', array(
	'UI:Newsroom:NoNewMessage' => 'No new message~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));
