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
 * @author Erik Bøg <erik@boegmoeller.dk>
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:AuditCategory' => 'Audit-kategori',
	'Class:AuditCategory+' => 'Udsnit af alle Audits',
	'Class:AuditCategory/Attribute:name' => 'Kategori navn',
	'Class:AuditCategory/Attribute:name+' => 'Kort navn for denne kategori',
	'Class:AuditCategory/Attribute:description' => 'Beskrivelse af Audit-kategori',
	'Class:AuditCategory/Attribute:description+' => 'Udførlig beskrivelse af denne Audit-kategori',
	'Class:AuditCategory/Attribute:definition_set' => 'Definition Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL begreber, der definerer omfanget af objekter, der skal auditeres',
	'Class:AuditCategory/Attribute:rules_list' => 'Audit-regler',
	'Class:AuditCategory/Attribute:rules_list+' => 'Audit-regler for denne kategori',
));

//
// Class: AuditRule
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:AuditRule' => 'Audit-regel',
	'Class:AuditRule+' => 'En regel til at efterprøve den angivne Audit-kategori med',
	'Class:AuditRule/Attribute:name' => 'Regel Navn',
	'Class:AuditRule/Attribute:name+' => 'Kort navn for denne regel',
	'Class:AuditRule/Attribute:description' => 'Audit-regel beskrivelse',
	'Class:AuditRule/Attribute:description+' => 'Udførlig beskrivelse af denne Audit-regel',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',
	'Class:AuditRule/Attribute:query' => 'Søgning at udføre',
	'Class:AuditRule/Attribute:query+' => 'Den OQL forespørgsel, der skal udføres',
	'Class:AuditRule/Attribute:valid_flag' => 'Gyldige objekter?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Sand, hvis reglen returnerer et gyldigt objekt, ellers Falsk',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Sand',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Sand',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Falsk',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Falsk',
	'Class:AuditRule/Attribute:category_id' => 'Kategori',
	'Class:AuditRule/Attribute:category_id+' => 'Kategori for denne regel',
	'Class:AuditRule/Attribute:category_name' => 'Kategori',
	'Class:AuditRule/Attribute:category_name+' => 'Kategorinavn for denne regel',
));

//
// Class: QueryOQL
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:Query' => 'Forespørgsel',
	'Class:Query+' => '',
	'Class:Query/Attribute:name' => 'Navn',
	'Class:Query/Attribute:name+' => '',
	'Class:Query/Attribute:description' => 'Beskrivelse',
	'Class:Query/Attribute:description+' => '',
	'Class:QueryOQL/Attribute:fields' => 'Felter',
	'Class:QueryOQL/Attribute:fields+' => '',
	'Class:QueryOQL' => 'OQL forespørgsel',
	'Class:QueryOQL+' => '',
	'Class:QueryOQL/Attribute:oql' => 'Udtryk',
	'Class:QueryOQL/Attribute:oql+' => '',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:User' => 'Bruger',
	'Class:User+' => 'Bruger log in',
	'Class:User/Attribute:finalclass' => 'Type af brugerkonto',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'kontakt (person)',
	'Class:User/Attribute:contactid+' => 'Personlige oplysninger og virksomheds data',
	'Class:User/Attribute:org_id' => 'Organisation',
	'Class:User/Attribute:org_id+' => 'Organization of the associated person~~',
	'Class:User/Attribute:last_name' => 'Efternavn',
	'Class:User/Attribute:last_name+' => 'Kontaktens efternavn',
	'Class:User/Attribute:first_name' => 'Fornavn',
	'Class:User/Attribute:first_name+' => 'Kontaktens fornavn',
	'Class:User/Attribute:email' => 'Email-adresse',
	'Class:User/Attribute:email+' => 'Kontaktens Email-adresse',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Bruger navn',
	'Class:User/Attribute:language' => 'Sprog',
	'Class:User/Attribute:language+' => 'Bruger valgt sprog',
	'Class:User/Attribute:language/Value:EN US' => 'Englsk',
	'Class:User/Attribute:language/Value:EN US+' => 'English (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Fransk',
	'Class:User/Attribute:language/Value:FR FR+' => 'French (France)',
	'Class:User/Attribute:profile_list' => 'Profiler',
	'Class:User/Attribute:profile_list+' => 'Roller, rettighedsstyring for denne person',
	'Class:User/Attribute:allowed_org_list' => 'Tilladte organisation(er)',
	'Class:User/Attribute:allowed_org_list+' => 'Brugeren har tilladelse til at se data om følgende organisationer. Hvis ingen organisation er vist, er der ingen indskrænkninger',
	'Class:User/Attribute:status' => 'Status~~',
	'Class:User/Attribute:status+' => 'Whether the user account is enabled or disabled.~~',
	'Class:User/Attribute:status/Value:enabled' => 'Enabled~~',
	'Class:User/Attribute:status/Value:disabled' => 'Disabled~~',

	'Class:User/Error:LoginMustBeUnique' => 'Login skal være entydig - "%1s" er allerede i brug.',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Mindst en profil skal knyttes til denne bruger.',
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

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_Profiles' => 'Profil',
	'Class:URP_Profiles+' => 'Brugerprofil',
	'Class:URP_Profiles/Attribute:name' => 'Navn',
	'Class:URP_Profiles/Attribute:name+' => 'Label',
	'Class:URP_Profiles/Attribute:description' => 'Beskrivele',
	'Class:URP_Profiles/Attribute:description+' => 'Kort beskrivelse',
	'Class:URP_Profiles/Attribute:user_list' => 'Brugere',
	'Class:URP_Profiles/Attribute:user_list+' => 'Personer, der har denne Rolle',
));

//
// Class: URP_Dimensions
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_Dimensions' => 'Dimension',
	'Class:URP_Dimensions+' => 'Anvendelsesdimension (Fastlæggelse af siloer)',
	'Class:URP_Dimensions/Attribute:name' => 'Navn',
	'Class:URP_Dimensions/Attribute:name+' => 'Label',
	'Class:URP_Dimensions/Attribute:description' => 'Beskrivelse',
	'Class:URP_Dimensions/Attribute:description+' => 'Kort beskrivelse',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'Klassenavn eller datatype',
));

//
// Class: URP_UserProfile
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_UserProfile' => 'Brugerprofil',
	'Class:URP_UserProfile+' => 'Brugerprofil',
	'Class:URP_UserProfile/Attribute:userid' => 'Bruger',
	'Class:URP_UserProfile/Attribute:userid+' => 'Brugerkonto',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Bruger login',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profil',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Anvend profil',
	'Class:URP_UserProfile/Attribute:profile' => 'Profil',
	'Class:URP_UserProfile/Attribute:profile+' => 'Profilnavn',
	'Class:URP_UserProfile/Attribute:reason' => 'Begrundelse',
	'Class:URP_UserProfile/Attribute:reason+' => 'Begrundelse, hvorfor denne bruger skal have denne profil',
));

//
// Class: URP_UserOrg
//


Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_UserOrg' => 'Bruger organisation(er)',
	'Class:URP_UserOrg+' => 'Tilladte organisation(er)',
	'Class:URP_UserOrg/Attribute:userid' => 'Bruger',
	'Class:URP_UserOrg/Attribute:userid+' => '',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => '',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Organisation',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => '',
	'Class:URP_UserOrg/Attribute:reason' => 'Begrundelse',
	'Class:URP_UserOrg/Attribute:reason+' => '',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_ProfileProjection' => 'Profil_projection',
	'Class:URP_ProfileProjection+' => 'Profilbillede',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Anvendelsesdimension',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Anvendelsesdimension',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Profil vilkår',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profil',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Profilnavn',
	'Class:URP_ProfileProjection/Attribute:value' => 'Værdi udtryk',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL-udtryk (Benyttes af $user) | konstant | | + Attribut-Code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribut',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Mål for Attribut-kode (valgfri)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_ClassProjection' => 'Klasse_projection',
	'Class:URP_ClassProjection+' => 'Klassebillede',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'Anvendelsesdimension',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'Anvendelsesdimension',
	'Class:URP_ClassProjection/Attribute:class' => 'Klasse',
	'Class:URP_ClassProjection/Attribute:class+' => 'Målklasse',
	'Class:URP_ClassProjection/Attribute:value' => 'Værdi udtryk',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL-udtryk (Benyttes af $this) | konstant | | + Attribut-Code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribut',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Mål for Attribut-kode (valgfri)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_ActionGrant' => 'Handlings godkendelser',
	'Class:URP_ActionGrant+' => 'Tilladelser på klasser',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Anvendelsesprofil',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profil',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Anvendelsesprofil',
	'Class:URP_ActionGrant/Attribute:class' => 'Klasse',
	'Class:URP_ActionGrant/Attribute:class+' => 'Målklasse',
	'Class:URP_ActionGrant/Attribute:permission' => 'Tilladelse',
	'Class:URP_ActionGrant/Attribute:permission+' => 'Tilladt eller nægtet?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nej',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'Nej',
	'Class:URP_ActionGrant/Attribute:action' => 'Handling',
	'Class:URP_ActionGrant/Attribute:action+' => 'Handling som skal udføres på den valgte klasse',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_StimulusGrant' => 'Tilladels til påvirkning',
	'Class:URP_StimulusGrant+' => 'Tilladelserne til påvirkning af livscyklus af objektet',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Anvendelsesprofil',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profil',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Anvendelsesprofil',
	'Class:URP_StimulusGrant/Attribute:class' => 'Klasse',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Målklasse',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Tilladelse',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Tilladt eller nægtet?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Js',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nej',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'Nej',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Påvirkning',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Påvirknings-kode',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:URP_AttributeGrant' => 'Godkendelse af Attributter',
	'Class:URP_AttributeGrant+' => 'Godkendelse af Attributter',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Tillad handling',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'Tillad handling',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribut',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Attribut-kode',
));

//
// Class: UserDashboard
//
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Expression:Unit:Short:DAY' => 'd~~',
	'Expression:Unit:Short:WEEK' => 'w~~',
	'Expression:Unit:Short:MONTH' => 'm~~',
	'Expression:Unit:Short:YEAR' => 'y~~',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'BooleanLabel:yes' => 'yes~~',
	'BooleanLabel:no' => 'no~~',
	'UI:Login:Title' => 'iTop login~~',
	'Menu:WelcomeMenu' => 'Velkomen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Velkommen til iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Velkomen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Velkommen til iTop', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Velkommen til iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop er en komplet, OpenSource, webbaseret IT-Service-Management-Værktøj.</p>
<ul>Den inkluderer:
<li>En komplet CMDB (Configuration management database) til at dokumentere og styre IT-portfolioen.</li>
<li>Et Incident management modul til brug for sporing og kommunikation omkring alle spørgsmål vedrørende IT.</li>
<li>Et change management modul til planlægning og sporing af ændringer i IT miljøet.</li>
<li>En "known error database" til brug for at mindske tiden for løsning af hændelser.</li>
<li>Et outage modul til dokumentation for planlagt nedetid og advisering af relevante kontakter.</li>
<li>Dashboards for hurtigt overblik over IT.</li>
</ul>
<p>Alle moduler kan installeres, step by step, uafhængigt af hinanden.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop er service udbyder orienteret, det tillader let IT teknikere at administrere flere kunder eller organisationer.
<ul>iTop, leverer et feature-rich sæt af forretnings processer som:
<li>Forøger IT administrationens effektivitet</li> 
<li>Drives IT operations performance</li> 
<li>Improves customer satisfaction and provides executives with insights into business performance.</li>
</ul>
</p>
<p>iTop is completely open to be integrated within your current IT Management infrastructure.</p>
<p>
<ul>Adopting this new generation of IT Operational portal will help you to:
<li>Better manage a more and more complex IT environment.</li>
<li>Implement ITIL processes at your own pace.</li>
<li>Manage the most important asset of your IT: Documentation.</li>
</ul>
</p>~~',
	'UI:WelcomeMenu:AllOpenRequests' => 'Åbne anmodninger: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mine brugerhenvendelser',
	'UI:WelcomeMenu:OpenIncidents' => 'Åbne Incidents: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuration Items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Incidents tildelt mig',
	'UI:AllOrganizations' => ' Alle Organisationer',
	'UI:YourSearch' => 'Din Søgning',
	'UI:LoggedAsMessage' => 'Logget ind som %1$s',
	'UI:LoggedAsMessage+Admin' => 'Logget ind som %1$s (Administrator)',
	'UI:Button:Logoff' => 'Log ud',
	'UI:Button:GlobalSearch' => 'Søg',
	'UI:Button:Search' => ' Søg ',
	'UI:Button:Query' => ' Forespørgsel ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Gem',
	'UI:Button:Cancel' => 'Afbryd',
	'UI:Button:Close' => 'Close~~',
	'UI:Button:Apply' => 'Anvend',
	'UI:Button:Back' => ' << Tilbage ',
	'UI:Button:Restart' => ' |<< Start igen ',
	'UI:Button:Next' => ' Næste >> ',
	'UI:Button:Finish' => ' Afslut ',
	'UI:Button:DoImport' => ' Kør Importen ! ',
	'UI:Button:Done' => ' Færdig ',
	'UI:Button:SimulateImport' => ' Simuler Importen ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Evaluér ',
	'UI:Button:Evaluate:Title' => ' Evaluér (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Tilføj... ',
	'UI:Button:BrowseObjects' => ' Gennemse... ',
	'UI:Button:Add' => ' Tilføj ',
	'UI:Button:AddToList' => ' << Tilføj ',
	'UI:Button:RemoveFromList' => ' Fjern >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Opret ',
	'UI:Button:Delete' => ' Slet! ',
	'UI:Button:Rename' => ' Omdøb... ',
	'UI:Button:ChangePassword' => ' Skift Password ',
	'UI:Button:ResetPassword' => ' Reset Password ',
	'UI:Button:Insert' => 'Insert~~',
	'UI:Button:More' => 'More~~',
	'UI:Button:Less' => 'Less~~',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Søg',
	'UI:ClickToCreateNew' => 'Opret nyt objekt af typen %1$s ',
	'UI:SearchFor_Class' => 'Søg efter objekter af typen %1$s ',
	'UI:NoObjectToDisplay' => 'Ingen objekter at vise.',
	'UI:Error:SaveFailed' => 'The object cannot be saved :~~',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter objekt_id er obligatorisk når link_attr er specificeret. Tjek definitionen af display skabelonen.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter objekt_id er obligatorisk når link_attr er specificeret. Tjek definitionen af display skabelonen.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by er obligatorisk. Tjek definitionen af display skabelonen.',
	'UI:Error:InvalidGroupByFields' => 'Ugyldig liste af felter at gruppere efter: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Fejl: ikke understøttet blokform: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Ukorrekt link definition: klassen af ​​objekter, der skal styres: %1$s blev ikke fundet som fremmednøgle i klassen %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Objekt: %1$s:%2$d ikke fundet.',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Fejl: Circulær reference i afhængigheder mellem felterne, tjek datamodellen.',
	'UI:Error:UploadedFileTooBig' => 'Den uploadede fil er for stor. (Max tilladt størrelse er %1$s). Kontakt din iTop administrator for at få ændret denne grænse limit. (Tjek PHP konfigurationen for upload_max_filesize og post_max_size på serveren).',
	'UI:Error:UploadedFileTruncated.' => 'Den uploadede fil er blevet afkortet !',
	'UI:Error:NoTmpDir' => 'Det midlertidige bibliotek er ikke defineret.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Kan ikke skrive den midlertidige fil til disken: upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload stoppet på grund af filtype. (Original fil navn = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Fil upload fejlede, ukendt årsag. (Fejl kode = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Fejl: følgende parameter skal angives for denne operation: %1$s.',
	'UI:Error:2ParametersMissing' => 'Fejl: følgende parametre skal angives for denne operation: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Fejl: følgende parametre skal angives for denne operation: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Fejl: følgende parametre skal angives for denne operation: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Fejl: Ukorrekt OQL forespørgsel: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Der opstod en fejl ved afvikling af forespørgslen: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Fejl: objektet er allerede opdateret.',
	'UI:Error:ObjectCannotBeUpdated' => 'Fejl: objektet kan ikke opdateres.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Fejl: objekterne er allerede slettet!',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Du har ikke tilladelse til at foretage en masse sletning af objekter i klassen %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Du har ikke tilladelse til at slette objekter af klassen %1$s',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Du har ikke tilladelse til at foretage en masse opdatering af objekter i klassen %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Fejl: objektet er allerede klonet!',
	'UI:Error:ObjectAlreadyCreated' => 'Fejl: objektet er allerede oprettet!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Fejl: ikke lovlig påvirkning "%1$s" på objekt %2$s i tilstand "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Error: invalid dashboard file~~',
	'UI:Error:InvalidDashboard' => 'Error: invalid dashboard~~',
	'UI:Error:MaintenanceMode' => 'Application is currently in maintenance~~',
	'UI:Error:MaintenanceTitle' => 'Maintenance~~',

	'UI:GroupBy:Count' => 'Antal',
	'UI:GroupBy:Count+' => 'Antal af elementer',
	'UI:CountOfObjects' => '%1$d objekter opfylder kriteriet.',
	'UI_CountOfObjectsShort' => '%1$d objekter.',
	'UI:NoObject_Class_ToDisplay' => 'Ingen objekter af typen %1$s at vise.',
	'UI:History:LastModified_On_By' => 'Sidst ændret den %1$s af %2$s.',
	'UI:HistoryTab' => 'Historik',
	'UI:NotificationsTab' => 'Bemærkninger',
	'UI:History:BulkImports' => 'Historik',
	'UI:History:BulkImports+' => '',
	'UI:History:BulkImportDetails' => 'Ændringer som følge af CSV import foretaget den %1$s (af %2$s)',
	'UI:History:Date' => 'Dato',
	'UI:History:Date+' => 'Dato for ændring',
	'UI:History:User' => 'Bruger',
	'UI:History:User+' => 'Bruger, som gennemførte ændringen',
	'UI:History:Changes' => 'Ændringer',
	'UI:History:Changes+' => 'Ændringer som er gennemført på objektet',
	'UI:History:StatsCreations' => 'Oprettet',
	'UI:History:StatsCreations+' => 'Antal oprettede objekter',
	'UI:History:StatsModifs' => 'Modified',
	'UI:History:StatsModifs+' => 'Antal modificerede objekter',
	'UI:History:StatsDeletes' => 'Slettet',
	'UI:History:StatsDeletes+' => 'Antal slettede objekter',
	'UI:Loading' => 'Henter...',
	'UI:Menu:Actions' => 'Handlinger',
	'UI:Menu:OtherActions' => 'Andre handlinger',
	'UI:Menu:New' => 'Ny...',
	'UI:Menu:Add' => 'Tilføj...',
	'UI:Menu:Manage' => 'Administrer...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Eksport...',
	'UI:Menu:Modify' => 'Modificer...',
	'UI:Menu:Delete' => 'Slet...',
	'UI:Menu:BulkDelete' => 'Slet...',
	'UI:UndefinedObject' => 'Ikke defineret',
	'UI:Document:OpenInNewWindow:Download' => 'Åben i nyt vindue: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'Dato',
	'UI:SplitDateTime-Time' => 'Tid',
	'UI:TruncatedResults' => '%1$d objekter vist ud af %2$d',
	'UI:DisplayAll' => 'Vis Alle',
	'UI:CollapseList' => 'Fold sammen',
	'UI:CountOfResults' => '%1$d objekt(er)',
	'UI:ChangesLogTitle' => 'Ændrings log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Ændrings log er tom',
	'UI:SearchFor_Class_Objects' => 'Søg efter %1$s Objekter',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Simpel Søgning',
	'UI:Details+' => 'Detaljer',
	'UI:SearchValue:Any' => '* Enhver *',
	'UI:SearchValue:Mixed' => '* Blandet *',
	'UI:SearchValue:NbSelected' => '# Valgte',
	'UI:SearchValue:CheckAll' => 'Check All~~',
	'UI:SearchValue:UncheckAll' => 'Uncheck All~~',
	'UI:SelectOne' => '-- Vælg venligst --',
	'UI:Login:Welcome' => 'Velkommen til iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Ukorrekt login/adgangskode, venligst prøv igen.',
	'UI:Login:IdentifyYourself' => 'Identificer dig før du fortsætter',
	'UI:Login:UserNamePrompt' => 'Bruger Navn',
	'UI:Login:PasswordPrompt' => 'Adgangskode',
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

	'UI:Login:About' => 'Om',
	'UI:Login:ChangeYourPassword' => 'Skift Adgangskode',
	'UI:Login:OldPasswordPrompt' => 'Gammel Adgangskode',
	'UI:Login:NewPasswordPrompt' => 'Ny Adgangskode',
	'UI:Login:RetypeNewPasswordPrompt' => 'Gentag ny adgangskode',
	'UI:Login:IncorrectOldPassword' => 'Fejl: den gamle adgangskode er forkert',
	'UI:LogOffMenu' => 'Log ud',
	'UI:LogOff:ThankYou' => 'Tak for at du brugte iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Klik her for at logge ind igen...',
	'UI:ChangePwdMenu' => 'Skift Adgangskode...',
	'UI:Login:PasswordChanged' => 'Adgangskode oprettet med success!',
	'UI:AccessRO-All' => 'iTop er skrivebeskyttet',
	'UI:AccessRO-Users' => 'iTop er skrivebeskyttet for slutbrugere',
	'UI:ApplicationEnvironment' => 'Applikations miljø: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Ny adgangskode og gentaget adgangskode passer ikke sammen!',
	'UI:Button:Login' => 'Enter iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop adgang er begrænset. Venligst, kontakt en iTop administrator.',
	'UI:Login:Error:AccessAdmin' => 'Adgang er begrænset til administratorer. Venligst, kontakt en iTop administrator.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
	'UI:CSVImport:MappingSelectOne' => '-- Vælg venligst --',
	'UI:CSVImport:MappingNotApplicable' => '-- ignorer dette felt --',
	'UI:CSVImport:NoData' => 'Tomt data sæt..., venligst angiv nogle data!',
	'UI:Title:DataPreview' => 'Data Preview',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Fejl: Data indeholder kun en kolonne. Har du valgt den korrekte separator?',
	'UI:CSVImport:FieldName' => 'Felt %1$d',
	'UI:CSVImport:DataLine1' => 'Data Linje 1',
	'UI:CSVImport:DataLine2' => 'Data Linje 2',
	'UI:CSVImport:idField' => 'id (Primær Nøgle)',
	'UI:Title:BulkImport' => 'iTop - Bulk import',
	'UI:Title:BulkImport+' => 'CSV-Import assistent',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronisering af %1$d objekter af klasse %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- Vælg venligst --~~',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Intern fejl: "%1$s" er en ukorrekt kode fordi "%2$s" er IKKE en fremmed nøgle af klassen "%3$s"',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objekt(er) vil forblive uændrede.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objekt(er) vil blive ændret.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objekt(er) vil blive tilføjet.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objekt(er) har fejl.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objekt(er) forbliver uændrede.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objekt(er) blev ændret.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objekt(er) blev tilføjet.',
	'UI:CSVImport:ObjectsHadErrors' => '%1$d objekt(er) har fejl.',
	'UI:Title:CSVImportStep2' => 'Step 2 af 5: CSV data muligheder',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Data mapping',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Import simulering',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Import fuldført',
	'UI:CSVImport:LinesNotImported' => 'Linjer som ikke kunne loades:',
	'UI:CSVImport:LinesNotImported+' => 'Dele som ikke kunne importeres, da de indeholder fejl',
	'UI:CSVImport:SeparatorComma+' => ', (komma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (semikolon)',
	'UI:CSVImport:SeparatorTab+' => 'Tabulator',
	'UI:CSVImport:SeparatorOther' => 'Andre:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (anførselstegn)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (enkelt citationstegn)',
	'UI:CSVImport:QualifierOther' => 'Andre:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Betragt første linje som overskrift (kolonnenavne)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Spring %1$s linje(r) over i begyndelsen af filen.',
	'UI:CSVImport:CSVDataPreview' => 'CSV Data eksempel',
	'UI:CSVImport:SelectFile' => 'Vælg den fil , der skal importeres:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Indlæs fra fil',
	'UI:CSVImport:Tab:CopyPaste' => 'Kopier og indsæt data',
	'UI:CSVImport:Tab:Templates' => 'Skabelon(er)',
	'UI:CSVImport:PasteData' => 'Indsæt de data der skal importeres:',
	'UI:CSVImport:PickClassForTemplate' => 'Vælg den skabelon der skal hentes: ',
	'UI:CSVImport:SeparatorCharacter' => 'Separator karakter:',
	'UI:CSVImport:TextQualifierCharacter' => 'Tekst qualifier karakter',
	'UI:CSVImport:CommentsAndHeader' => 'Kommentarer og header',
	'UI:CSVImport:SelectClass' => 'Vælg den klasse, der skal importeres: ',
	'UI:CSVImport:AdvancedMode' => 'Advanceret tilstand',
	'UI:CSVImport:AdvancedMode+' => 'I den avancerede tilstand, kan "ID" (primær nøgle) af objekter bruges til at opdatere eller omdøbe ojekter. Allers kan kolonnen "ID" (hvis nogen) kun bruges som en søgekriterium og kan ikke kombineres med andre søgekriterier.',
	'UI:CSVImport:SelectAClassFirst' => 'For at konfigurere mapning, vælg først en klasse.',
	'UI:CSVImport:HeaderFields' => 'Felter',
	'UI:CSVImport:HeaderMappings' => 'Mapninger',
	'UI:CSVImport:HeaderSearch' => 'Søg?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Vælg venligst en mapning for hvert felt.',
	'UI:CSVImport:AlertMultipleMapping' => 'Please make sure that a target field is mapped only once.~~',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Vælg venligst mindst et søgekriterie',
	'UI:CSVImport:Encoding' => 'Karakter encoding',
	'UI:UniversalSearchTitle' => 'iTop - Universal Søgning',
	'UI:UniversalSearch:Error' => 'Fejl: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Vælg klasse at søge efter: ',

	'UI:CSVReport-Value-Modified' => 'Ændret',
	'UI:CSVReport-Value-SetIssue' => 'Kunne ikke ændres - årsag: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Kunne ikke ændres til %1$s - årsag: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'No match',
	'UI:CSVReport-Value-Missing' => 'Mangler obligatorisk værdi',
	'UI:CSVReport-Value-Ambiguous' => 'Tvetydig: fandt %1$s objekter',
	'UI:CSVReport-Row-Unchanged' => 'Uændret',
	'UI:CSVReport-Row-Created' => 'Oprettet',
	'UI:CSVReport-Row-Updated' => 'Opdateret %1$d kolonne(r)',
	'UI:CSVReport-Row-Disappeared' => 'Forsvundet, ændrede %1$d kolonne(r)',
	'UI:CSVReport-Row-Issue' => 'Emne: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null ikke tilladt',
	'UI:CSVReport-Value-Issue-NotFound' => 'Objekt ikke fundet',
	'UI:CSVReport-Value-Issue-FoundMany' => 'Fandt %1$d emner',
	'UI:CSVReport-Value-Issue-Readonly' => 'Attributten \'%1$s\' er skrivebeskyttet og kan ikke ændres (nuværende værdi: %2$s, foreslået værdi: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Fejl i behandling af input: %1$s',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Uventet værdi for attribut \'%1$s\': ingen emner fundet, tjek stavningen',
	'UI:CSVReport-Value-Issue-Unknown' => 'Uventet værdi for attribut \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributterne er ikke i overensstemmelse med hinanden: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Uventet attribut værdi(er)',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Kunne ikke oprettes, på grund af manglende fremmednøgle(r): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Forkert dato format',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Fejl ved forening',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Tvetydig forening',
	'UI:CSVReport-Row-Issue-Internal' => 'Intern fejl: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Uændret',
	'UI:CSVReport-Icon-Modified' => 'Ændret',
	'UI:CSVReport-Icon-Missing' => 'Mangler',
	'UI:CSVReport-Object-MissingToUpdate' => 'Mangler objekt: vil blive opdateret',
	'UI:CSVReport-Object-MissingUpdated' => 'Manglende objekt: opdateret',
	'UI:CSVReport-Icon-Created' => 'Oprettet',
	'UI:CSVReport-Object-ToCreate' => 'Objekt vil blive oprettet',
	'UI:CSVReport-Object-Created' => 'Objekt oprettet',
	'UI:CSVReport-Icon-Error' => 'Fejl',
	'UI:CSVReport-Object-Error' => 'FEJL: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'TVETYDIG: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% af de hentede objekter har fejl og vil blive ignoreret',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% af de hentede objekter vil blive oprettet.',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% af de hentede objekter vil blive ændret.',

	'UI:CSVExport:AdvancedMode' => 'Advanceret tilstand',
	'UI:CSVExport:AdvancedMode+' => '',
	'UI:CSVExport:LostChars' => 'Encoding problem',
	'UI:CSVExport:LostChars+' => '',

	'UI:Audit:Title' => 'iTop - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interaktiv Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit Regel',
	'UI:Audit:HeaderNbObjects' => '# Objekt(er)',
	'UI:Audit:HeaderNbErrors' => '# Fejl',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Fejl i regel %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Fejl i kategorien %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - OQL Query Evaluering',
	'UI:RunQuery:QueryExamples' => 'Query Eksempler',
	'UI:RunQuery:HeaderPurpose' => 'Formål',
	'UI:RunQuery:HeaderPurpose+' => 'Beskrivelse af forespørgslen',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL Udtryk',
	'UI:RunQuery:HeaderOQLExpression+' => 'Forespørgslen i OQL syntaks',
	'UI:RunQuery:ExpressionToEvaluate' => 'Udtryk der skal evalueres: ',
	'UI:RunQuery:MoreInfo' => 'Mere information om forespørgslen: ',
	'UI:RunQuery:DevelopedQuery' => 'Videreudviklet forespørgselsudtryk: ',
	'UI:RunQuery:SerializedFilter' => 'Serielt filter: ',
	'UI:RunQuery:DevelopedOQL' => 'Developed OQL~~',
	'UI:RunQuery:DevelopedOQLCount' => 'Developed OQL for count~~',
	'UI:RunQuery:ResultSQLCount' => 'Resulting SQL for count~~',
	'UI:RunQuery:ResultSQL' => 'Resulting SQL~~',
	'UI:RunQuery:Error' => 'Der opstod en fejl under afviklingen af forespøgrslen: %1$s',
	'UI:Query:UrlForExcel' => 'URL til brug for MS-Excel web forespøgrsler',
	'UI:Query:UrlV1' => 'The list of fields has been left unspecified. The page <em>export-V2.php</em> cannot be invoked without this information. Therefore, the URL suggested herebelow points to the legacy page: <em>export.php</em>. This legacy version of the export has the following limitation: the list of exported fields may vary depending on the output format and the data model of iTop. Should you want to garantee that the list of exported columns will remain stable on the long run, then you must specify a value for the attribute "Fields" and use the page <em>export-V2.php</em>.~~',
	'UI:Schema:Title' => 'iTop objekt skema',
	'UI:Schema:CategoryMenuItem' => 'Kategori <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relationer',
	'UI:Schema:AbstractClass' => 'Abstrakt klasse: intet objekt fra denne klasse kan instantieres.',
	'UI:Schema:NonAbstractClass' => 'Non abstrakt klasse: objekter fra denne klasse kan instantieres.',
	'UI:Schema:ClassHierarchyTitle' => 'Klasse hierarki',
	'UI:Schema:AllClasses' => 'Alle klasser',
	'UI:Schema:ExternalKey_To' => 'Fremmednøgle til %1$s',
	'UI:Schema:Columns_Description' => 'Kolonner: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Standard: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null Tilladt',
	'UI:Schema:NullNotAllowed' => 'Null IKKE Tilladt',
	'UI:Schema:Attributes' => 'Attributter',
	'UI:Schema:AttributeCode' => 'Attribut Kode',
	'UI:Schema:AttributeCode+' => 'Interne kode for attributter',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label for attributten',
	'UI:Schema:Type' => 'Type',

	'UI:Schema:Type+' => 'Datatype for attributten',
	'UI:Schema:Origin' => 'Oprindelse',
	'UI:Schema:Origin+' => 'Basisklasse, hvor denne attribut er defineret',
	'UI:Schema:Description' => 'Beskrivelse',
	'UI:Schema:Description+' => 'Beskrivelse af disse attributter',
	'UI:Schema:AllowedValues' => 'Tilladte værdier',
	'UI:Schema:AllowedValues+' => '',
	'UI:Schema:MoreInfo' => 'Mere information',
	'UI:Schema:MoreInfo+' => 'Mere information om dette felt',
	'UI:Schema:SearchCriteria' => 'Søgekriterie',
	'UI:Schema:FilterCode' => 'Filter kode',
	'UI:Schema:FilterCode+' => 'Kode for dette søgekriterie',
	'UI:Schema:FilterDescription' => 'Beskrivelse',
	'UI:Schema:FilterDescription+' => 'Beskrivelse af dette søgekriterie',
	'UI:Schema:AvailOperators' => 'Tilgængelige operatorer',
	'UI:Schema:AvailOperators+' => 'Mulige operatorer for dette søgekriterie',
	'UI:Schema:ChildClasses' => 'Child klasser',
	'UI:Schema:ReferencingClasses' => 'Refererende klasser',
	'UI:Schema:RelatedClasses' => 'Relaterede klasser',
	'UI:Schema:LifeCycle' => 'Livs cyclus',
	'UI:Schema:Triggers' => 'Triggere',
	'UI:Schema:Relation_Code_Description' => 'Relation <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Ned: %1$s',
	'UI:Schema:RelationUp_Description' => 'Op: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: udbred til %2$d niveauer, forespørgsel: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: udbred ikke til (%2$d niveauer), forespørsel: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s bliver refereret af klasse %2$s via feltet %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s er kædet til %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Klasser peger på %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Klasser kædet til %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Graf af alle relaterede klasser',
	'UI:Schema:NoLifeCyle' => 'Der er ingen livscyclus defineret for denne klasse.',
	'UI:Schema:LifeCycleTransitions' => 'Overgange',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribut options',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Skjult',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Skrivebeskyttet',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Obligatorisk',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Skal ændres',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Bruger vil blive bedt om at ændre værdien',
	'UI:Schema:LifeCycleEmptyList' => 'Tom liste',
	'UI:Schema:ClassFilter' => 'Class:~~',
	'UI:Schema:DisplayLabel' => 'Display:~~',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label and code~~',
	'UI:Schema:DisplaySelector/Label' => 'Label~~',
	'UI:Schema:DisplaySelector/Code' => 'Code~~',
	'UI:Schema:Attribute/Filter' => 'Filter~~',
	'UI:Schema:DefaultNullValue' => 'Default null : "%1$s"~~',
	'UI:LinksWidget:Autocomplete+' => '',
	'UI:Edit:TestQuery' => 'Test forespørgsel',
	'UI:Combo:SelectValue' => '--- vælg en værdi ---',
	'UI:Label:SelectedObjects' => 'Valgte objekter: ',
	'UI:Label:AvailableObjects' => 'Tilgængelige objekter: ',
	'UI:Link_Class_Attributes' => '%1$s attributer',
	'UI:SelectAllToggle+' => 'Alle vælg/fravælg',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Tilføj %1$s objekter kædet til %2$s: %3$s',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Tilføj %1$s objekter til kæden til %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Administrer %1$s objekter kædet til %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Tilføj %1$s objekter...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Fjern valgte objekter',
	'UI:Message:EmptyList:UseAdd' => 'Listen er tom, brug "Tilføj..." knappen for at tilføje elementer.',
	'UI:Message:EmptyList:UseSearchForm' => 'Brug søgeformularen ovenfor, til søgning efter objekters som skal tilføjes.',
	'UI:Wizard:FinalStepTitle' => 'Sidste skridt: bekræftelse',
	'UI:Title:DeletionOf_Object' => 'Sletning af %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Massesletning af %1$d objekter af klassen %2$s',
	'UI:Delete:NotAllowedToDelete' => 'Du har ikke tilladelse til at slette dette objekt',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Du har ikke tilladelse til at opdatere følgende felt(er): %1$s',
	'UI:Error:ActionNotAllowed' => 'You are not allowed to do this action~~',
	'UI:Error:NotEnoughRightsToDelete' => 'Dette objekt kunne ikke slettes, fordi den nuværende bruger ikke har tilstrækkelige rettigheder',
	'UI:Error:CannotDeleteBecause' => 'Dette objekt kunne ikke slettes fordi: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Dette objekt kunne ikke slettes, fordi nogle manuelle operationer skal udføres først',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Dette objekt kunne ikke slettes, fordi nogle manuelle operationer skal udføres først',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s på vegne af %2$s',
	'UI:Delete:Deleted' => 'Slettet',
	'UI:Delete:AutomaticallyDeleted' => 'Automatisk slettet',
	'UI:Delete:AutomaticResetOf_Fields' => 'Automatisk reset af felt(er): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Oprydning af alle referencer til %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Oprydning af alle referencer til %1$d objekter af klasse %2$s...',
	'UI:Delete:Done+' => '',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s slettet.',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Sletning af %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Sletning af %1$d objekter af klasse %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Kunne ikke slettes: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Bør automatisk slettes, men dette ikke er muligt: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Bør automatisk slettes, men dette ikke er muligt: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Vil automatisk blive slettet',
	'UI:Delete:MustBeDeletedManually' => 'Skal slettes manuelt',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Skulle blive automatisk opdateret, men: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Vil blive automatisk opdateret (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objekter/links refererer %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objekter/links refererer til nogle af de objekter som slettes',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'For at sikre Database integritet, skal alle referencer slettes',
	'UI:Delete:Consequence+' => '',
	'UI:Delete:SorryDeletionNotAllowed' => 'Beklager, du har ikke tilladelse til at slette dette objekt, se the detaljeret forklaring ovenfor',
	'UI:Delete:PleaseDoTheManualOperations' => 'Venligst foretag den manuelle opreration som er nævnt ovenfor, før sletning af objektet',
	'UI:Delect:Confirm_Object' => 'Venligst bekræft at du ønsker at slette %1$s.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Venligst bekræft at du ønsker at slette følgende %1$d objekter af klassen %2$s.',
	'UI:WelcomeToITop' => 'Velkommen til iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s detaljer',
	'UI:ErrorPageTitle' => 'iTop - Fejl',
	'UI:ObjectDoesNotExist' => 'Beklager, dette objekt eksisterer ikke (eller du har ikke tilladelse til at se det).',
	'UI:ObjectArchived' => 'This object has been archived. Please enable the archive mode or contact your administrator.~~',
	'Tag:Archived' => 'Archived~~',
	'Tag:Archived+' => 'Can be accessed only in archive mode~~',
	'Tag:Obsolete' => 'Obsolete~~',
	'Tag:Obsolete+' => 'Excluded from the impact analysis and search results~~',
	'Tag:Synchronized' => 'Synchronized~~',
	'ObjectRef:Archived' => 'Archived~~',
	'ObjectRef:Obsolete' => 'Obsolete~~',
	'UI:SearchResultsPageTitle' => 'iTop - Søge Resultater',
	'UI:SearchResultsTitle' => 'Søge Resultater',
	'UI:SearchResultsTitle+' => 'Full-text search results~~',
	'UI:Search:NoSearch' => 'Intet at søge efter',
	'UI:Search:NeedleTooShort' => 'The search string \\"%1$s\\" is too short. Please type at least %2$d characters.~~',
	'UI:Search:Ongoing' => 'Searching for \\"%1$s\\"~~',
	'UI:Search:Enlarge' => 'Broaden the search~~',
	'UI:FullTextSearchTitle_Text' => 'Resultater for "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d objekt(er) af klasse %2$s fundet.',
	'UI:Search:NoObjectFound' => 'Intet objekt fundet.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s ændring',
	'UI:ModificationTitle_Class_Object' => 'Ændring af %1$s: <span class=\\"hilite\\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Clone %1$s - %2$s ændring',
	'UI:CloneTitle_Class_Object' => 'Clone af %1$s: <span class=\\"hilite\\">%2$s</span>~~',
	'UI:CreationPageTitle_Class' => 'iTop - Oprettelse af ny %1$s ',
	'UI:CreationTitle_Class' => 'Oprettelse af ny %1$s',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Vælg type af %1$s for oprettelse:',
	'UI:Class_Object_NotUpdated' => 'Ingen ændring, %1$s (%2$s) er <strong>not</strong> ændret.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) opdateret.',
	'UI:BulkDeletePageTitle' => 'iTop - Massesletning',
	'UI:BulkDeleteTitle' => 'Vælg objekt som ønskes slettet:',
	'UI:PageTitle:ObjectCreated' => 'iTop Objekt Oprettet.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s oprettet.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Anvender %1$s på objekt: %2$s i tilstand %3$s for sluttilstand: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Objektet kunne ikke skrives: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Fatal Fejl',
	'UI:SystemIntrusion' => 'Adgang nægtet. Du prøver at udføre en handling som du ikke har tilladelse til.',
	'UI:FatalErrorMessage' => 'Fatal fejl, iTop kan ikke fortsætte.',
	'UI:Error_Details' => 'Fejl: %1$s.',

	'UI:PageTitle:ClassProjections' => 'iTop bruger styring - klasse projection',
	'UI:PageTitle:ProfileProjections' => 'iTop bruger styring - profil projection',
	'UI:UserManagement:Class' => 'Klasse',
	'UI:UserManagement:Class+' => '',
	'UI:UserManagement:ProjectedObject' => 'Objekt',
	'UI:UserManagement:ProjectedObject+' => '',
	'UI:UserManagement:AnyObject' => '* enhver *',
	'UI:UserManagement:User' => 'Bruger',
	'UI:UserManagement:User+' => '',
	'UI:UserManagement:Profile' => 'Profil',
	'UI:UserManagement:Profile+' => '',
	'UI:UserManagement:Action:Read' => 'Læs',
	'UI:UserManagement:Action:Read+' => '',
	'UI:UserManagement:Action:Modify' => 'Ændring',
	'UI:UserManagement:Action:Modify+' => '',
	'UI:UserManagement:Action:Delete' => 'Slet',
	'UI:UserManagement:Action:Delete+' => '',
	'UI:UserManagement:Action:BulkRead' => 'Masselæsning (Export)',
	'UI:UserManagement:Action:BulkRead+' => '',
	'UI:UserManagement:Action:BulkModify' => 'Masseændring',
	'UI:UserManagement:Action:BulkModify+' => '',
	'UI:UserManagement:Action:BulkDelete' => 'Massesletning',
	'UI:UserManagement:Action:BulkDelete+' => '',
	'UI:UserManagement:Action:Stimuli' => 'Påvirkning',
	'UI:UserManagement:Action:Stimuli+' => '',
	'UI:UserManagement:Action' => 'Handling',
	'UI:UserManagement:Action+' => '',
	'UI:UserManagement:TitleActions' => 'Handlinger',
	'UI:UserManagement:Permission' => 'Tilladelse',
	'UI:UserManagement:Permission+' => '',
	'UI:UserManagement:Attributes' => 'Attributter',
	'UI:UserManagement:ActionAllowed:Yes' => 'Ja',
	'UI:UserManagement:ActionAllowed:No' => 'Nej',
	'UI:UserManagement:AdminProfile+' => '',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => '',
	'UI:UserManagement:GrantMatrix' => 'Grant Matrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Link mellem %1$s and %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Link mellem %1$s og %2$s',

	'Menu:AdminTools' => 'Admin værktøjer', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Værktøjer kun tilgængelige for brugere med administrator profil', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'System~~',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => '',
	'UI:ChangeManagementMenu:Title' => 'Changes Overblik',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes efter type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes efter status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes efter workgroup',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Changes endnu ikke tildelt',

	'UI:ConfigurationManagementMenu' => 'Configuration Management',
	'UI:ConfigurationManagementMenu+' => '',
	'UI:ConfigurationManagementMenu:Title' => 'Infrastruktur Overblik',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Infrastruktur objekter efter type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Infrastruktur objekter efter status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard for Configuration Management',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuration Items efter status',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items efter type',

	'UI:RequestMgmtMenuOverview:Title' => 'Dashboard for Anmodnings styring',
	'UI-RequestManagementOverview-RequestByService' => 'Bruger anmodninger efter service',
	'UI-RequestManagementOverview-RequestByPriority' => 'Bruger anmodninger efter prioritet',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Bruger anmodninger endnu ikke tildelt',

	'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard for Incident Management',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidents efter service',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidents efter prioritet',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Incidents endnu ikke tildelt',

	'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard for Change Management~~',
	'UI-ChangeManagementOverview-ChangeByType' => 'Changes efter type',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Changes endnu ikke tildelt',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Udfald på grund af changes (ændringer)',

	'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard for Service Management',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Kunde kontrakter til fornyelse indenfor 30 dage',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Leverandør kontrakter til fornyelse indenfor 30 dage',

	'UI:ContactsMenu' => 'Kontakter',
	'UI:ContactsMenu+' => '',
	'UI:ContactsMenu:Title' => 'Kontakter Overblik',
	'UI-ContactsMenu-ContactsByLocation' => 'Kontakter efter lokation',
	'UI-ContactsMenu-ContactsByType' => 'Kontakter efter type',
	'UI-ContactsMenu-ContactsByStatus' => 'Kontakter efter status',

	'Menu:CSVImportMenu' => 'CSV import', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Data Model', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Export', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Notifikationer', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Konfiguration af <span class="hilite">Notifikationer</span>~~',
	'UI:NotificationsMenu:Help' => 'Hjælp',
	'UI:NotificationsMenu:HelpContent' => '<p>I iTop er notifikationer fuldt modificerbare. De er baseret på to sæt af objekter: <i>triggers og handlinger</i>.</p>
<p><i><b>Triggers</b></i> define when a notification will be executed. There are different triggers as part of iTop core, but others can be brought by extensions:
<ol>
	<li>Some triggers are executed when an object of the specified class is <b>created</b>, <b>updated</b> or <b>deleted</b>.</li>
	<li>Some triggers are executed when an object of a given class <b>enter</b> or <b>leave</b> a specified </b>state</b>.</li>
	<li>Some triggers are executed when a <b>threshold on TTO or TTR</b> has been <b>reached</b>.</li>
</ol>
</p>
<p>
<i><b>Handlinger</b></i> definer de handlinger som udføres når triggeren udløses. For nuværende er der kun en handling, som består af at sende en email besked.
Sådanne handlinger definerer den skabelon som bruges til afsendelse af email såvel som andre parametre indhold i beskeden, modtger, vigtighed, etc.
</p>
<p>En speciel side: <a href="../setup/email.test.php" target="_blank">email.test.php</a> er til rådighed for test og problemløsning af PHP mail konfigurationen.</p>
<p>For udførelse, handlinger skal være knyttet til triggers.
Ved tilknytningen til en trigger, bliver hver handling tildelt et "rækkefølge" nummer, der specificerer i hvilken rækkefølge handlingerne udføres.</p>~~',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Tilgængelige triggers',
	'UI:NotificationsMenu:OnCreate' => 'Når et objekt oprettes',
	'UI:NotificationsMenu:OnStateEnter' => 'Når et objekt indtræder i en give tilstand',
	'UI:NotificationsMenu:OnStateLeave' => 'Når et objekt forlader en give tilstand',
	'UI:NotificationsMenu:Actions' => 'Handlinger',
	'UI:NotificationsMenu:AvailableActions' => 'Tilgængelige handlinger',

	'Menu:TagAdminMenu' => 'Tags configuration~~',
	'Menu:TagAdminMenu+' => 'Tags values management~~',
	'UI:TagAdminMenu:Title' => 'Tags configuration~~',
	'UI:TagAdminMenu:NoTags' => 'No Tag field configured~~',
	'UI:TagSetFieldData:Error' => 'Error: %1$s~~',

	'Menu:AuditCategories' => 'Audit Kategorier', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Audit Kategorier', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Kør forespørgsler', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Query parlør', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Data administration', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Universal Søgning', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Bruger styring', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profiler', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profiler', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Bruger konti', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => '', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Bruger konti', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s version %2$s',
	'UI:iTopVersion:Long' => '%1$s version %2$s-%3$s built on %4$s',
	'UI:PropertiesTab' => 'Egenskaber',

	'UI:OpenDocumentInNewWindow_' => 'Åbn dette dokument i et nyt vindue: %1$s',
	'UI:DownloadDocument_' => 'Hent dette dokument: %1$s',
	'UI:Document:NoPreview' => 'Forhåndsvisning er ikke tilgængelig for denne dokumenttype',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Overskredet med %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Hjælp',
	'UI:PasswordConfirm' => '(Bekræft)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Gem dette objekt, før der tilføjes flere %1$s objekter.',
	'UI:DisplayThisMessageAtStartup' => 'Vis denne beksed ved start',
	'UI:RelationshipGraph' => 'Grafisk visning',
	'UI:RelationshipList' => 'Liste',
	'UI:RelationGroups' => 'Groups~~',
	'UI:OperationCancelled' => 'Handling afbrudt',
	'UI:ElementsDisplayed' => 'Filtrering',
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
	'Portal:Title' => 'iTop bruger portal',
	'Portal:NoRequestMgmt' => 'Kære %1$s, du er blevet omdirigeret til denne side, fordi din konto er konfigureret med profilen \'Portal user\'. Desværre er iTop ikke installeret med denne funktionalitet \'Request Management\'. Venligst kontakt din administrator.',
	'Portal:Refresh' => 'Opdater',
	'Portal:Back' => 'Tilbage',
	'Portal:WelcomeUserOrg' => 'Velkommen %1$s, fra %2$s',
	'Portal:TitleDetailsFor_Request' => 'Detaljer for anmodning',
	'Portal:ShowOngoing' => 'Vis åbne anmodninger',
	'Portal:ShowClosed' => 'Vis lukkede anmodninger',
	'Portal:CreateNewRequest' => 'Opret ny anmodning',
	'Portal:CreateNewRequestItil' => 'Vytvořit nový požadavek',
	'Portal:CreateNewIncidentItil' => 'Create a new incident report~~',
	'Portal:ChangeMyPassword' => 'Skift password',
	'Portal:Disconnect' => 'Disconnect',
	'Portal:OpenRequests' => 'Mine åbne anmodninger',
	'Portal:ClosedRequests' => 'Mine lukkede anmodninger',
	'Portal:ResolvedRequests' => 'Mine løste anmodninger',
	'Portal:SelectService' => 'Vælg en ydelse fra kataloget:',
	'Portal:PleaseSelectOneService' => 'Vælg venligst en ydelse',
	'Portal:SelectSubcategoryFrom_Service' => 'Vælg en under-kategori for ydelsen %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Vælg venligst en under-kategori',
	'Portal:DescriptionOfTheRequest' => 'Indtast beskrivelse af din anmodning:',
	'Portal:TitleRequestDetailsFor_Request' => 'Detaljer for anmodning %1$s:',
	'Portal:NoOpenRequest' => 'Ingen anmodning i denne kategori',
	'Portal:NoClosedRequest' => 'Ingen anmodning i denne kategori',
	'Portal:Button:ReopenTicket' => 'Genåben denne ticket',
	'Portal:Button:CloseTicket' => 'Luk denne ticket',
	'Portal:Button:UpdateRequest' => 'Opdater denne anmodning',
	'Portal:EnterYourCommentsOnTicket' => 'Indtast din kommentar til løsningen af denne:',
	'Portal:ErrorNoContactForThisUser' => 'Fejl: nuværnede bruger er ikke tilknyttet en Kontact/Person. Kontakt venligst din administrator.',
	'Portal:Attachments' => 'Vedhæftninger',
	'Portal:AddAttachment' => ' Vedhæft fil ',
	'Portal:RemoveAttachment' => ' Fjern vedhæftning ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Vedhæftning #%1$d til %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Vælg en skabelon for %1$s',
	'Enum:Undefined' => 'Udefineret',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Dage %2$s Timer %3$s Minutter %4$s Sekunder',
	'UI:ModifyAllPageTitle' => 'Modificer Alle',
	'UI:Modify_N_ObjectsOf_Class' => 'Ændrer %1$d objekter af klasse %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Ændrer %1$d objekter af klasse %2$s ud af %3$d',
	'UI:Menu:ModifyAll' => 'Modificer...',
	'UI:Button:ModifyAll' => 'Modificer Alle',
	'UI:Button:PreviewModifications' => 'Preview Ændringer >>',
	'UI:ModifiedObject' => 'Objekt Ændret',
	'UI:BulkModifyStatus' => 'Operation',
	'UI:BulkModifyStatus+' => '',
	'UI:BulkModifyErrors' => 'Fejl (hvis nogen)',
	'UI:BulkModifyErrors+' => '',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Fejl',
	'UI:BulkModifyStatusModified' => 'Ændret',
	'UI:BulkModifyStatusSkipped' => 'Sprunget over',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinkte værdier:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d gang(e)',
	'UI:BulkModify:N_MoreValues' => '%1$d flere værdier...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Forsøger at skrivebeskytte feltet: %1$s',
	'UI:FailedToApplyStimuli' => 'Handlingen fejlede.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Ændrer %2$d objekter af klasse %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Skriv din tekst her:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Begyndelses værdi:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Feltet %1$s er skrivebeskyttet, fordi det administreres af data synchronization. Værdien er ikke sat.',
	'UI:ActionNotAllowed' => 'Du har ikke tilladelse til at foretage denne handling op disse objekter.',
	'UI:BulkAction:NoObjectSelected' => 'Vælg venligst mindst et objekt for at foretage denne handling',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Feltet %1$s er skrivebeskyttet, fordi det administreres af data synchronization. Værdien forbliver uændret.',
	'UI:Pagination:HeaderSelection' => 'Total: %1$s objekter (%2$s objekter valgt).',
	'UI:Pagination:HeaderNoSelection' => 'Total: %1$s objekter.',
	'UI:Pagination:PageSize' => '%1$s objekter per side',
	'UI:Pagination:PagesLabel' => 'Sider:',
	'UI:Pagination:All' => 'Alle',
	'UI:HierarchyOf_Class' => 'Hierarchy af %1$s',
	'UI:Preferences' => 'Indstillinger...',
	'UI:ArchiveModeOn' => 'Activate archive mode~~',
	'UI:ArchiveModeOff' => 'Deactivate archive mode~~',
	'UI:ArchiveMode:Banner' => 'Archive mode~~',
	'UI:ArchiveMode:Banner+' => 'Archived objects are visible, and no modification is allowed~~',
	'UI:FavoriteOrganizations' => 'Favorit Organisationer',
	'UI:FavoriteOrganizations+' => '',
	'UI:FavoriteLanguage' => 'Sprog i brugergrænseflade',
	'UI:Favorites:SelectYourLanguage' => 'Vælg dit foretrukne sprog',
	'UI:FavoriteOtherSettings' => 'Andre indstillinger',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Default længde for lister:  %1$s emner per side',
	'UI:Favorites:ShowObsoleteData' => 'Show obsolete data~~',
	'UI:Favorites:ShowObsoleteData+' => 'Show obsolete data in search results and lists of items to select~~',
	'UI:NavigateAwayConfirmationMessage' => 'Enhver ændring vil blive kasseret.',
	'UI:CancelConfirmationMessage' => 'Du vil miste dine ændringer. Fortsæt alligevel?',
	'UI:AutoApplyConfirmationMessage' => 'Nogle ændringer er ikke gemt endnu. Ønsker du at itop skal tage hensyn til dem?',
	'UI:Create_Class_InState' => 'Opret %1$s i tilstand: ',
	'UI:OrderByHint_Values' => 'Sorterings orden: %1$s',
	'UI:Menu:AddToDashboard' => 'Tilføj til Dashboard...',
	'UI:Button:Refresh' => 'Opdater',
	'UI:Button:GoPrint' => 'Print...~~',
	'UI:ExplainPrintable' => 'Click onto the %1$s icon to hide items from the print.<br/>Use the "print preview" feature of your browser to preview before printing.<br/>Note: this header and the other tuning controls will not be printed.~~',
	'UI:PrintResolution:FullSize' => 'Full size~~',
	'UI:PrintResolution:A4Portrait' => 'A4 Portrait~~',
	'UI:PrintResolution:A4Landscape' => 'A4 Landscape~~',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portrait~~',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landscape~~',
	'UI:Toggle:StandardDashboard' => 'Standard~~',
	'UI:Toggle:CustomDashboard' => 'Custom~~',

	'UI:ConfigureThisList' => 'Konfigurer denne liste...',
	'UI:ListConfigurationTitle' => 'Liste Konfiguration',
	'UI:ColumnsAndSortOrder' => 'Kolonner og sortering:',
	'UI:UseDefaultSettings' => 'Brug de anbefalede indstillinger',
	'UI:UseSpecificSettings' => 'Brug følgende indstillinger:',
	'UI:Display_X_ItemsPerPage' => 'Vis %1$s emner per side',
	'UI:UseSavetheSettings' => 'Gem indstillinger',
	'UI:OnlyForThisList' => 'Kun for denne liste',
	'UI:ForAllLists' => 'For alle lister',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Ryk Op',
	'UI:Button:MoveDown' => 'Ryk Ned',

	'UI:OQL:UnknownClassAndFix' => 'Ukendt klasse "%1$s". Forsøg "%2$s" i stedet for.',
	'UI:OQL:UnknownClassNoFix' => 'Ukendt klasse "%1$s"',

	'UI:Dashboard:Edit' => 'Rediger denne side...',
	'UI:Dashboard:Revert' => 'Tilbage til original version...',
	'UI:Dashboard:RevertConfirm' => 'Enhver ændring foretaget i den oprindelige version vil blive tabt. Bekræft venligst at du ønsker dette.',
	'UI:ExportDashBoard' => 'Exporter til fil',
	'UI:ImportDashBoard' => 'Importer fra fil...',
	'UI:ImportDashboardTitle' => 'Importer Fra Fil',
	'UI:ImportDashboardText' => 'Vælg en dashboard fil til import:',


	'UI:DashletCreation:Title' => 'Opret en ny Dashlet',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Dashlet Type',
	'UI:DashletCreation:EditNow' => 'Rediger Dashboard',

	'UI:DashboardEdit:Title' => 'Dashboard Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Titel',
	'UI:DashboardEdit:AutoReload' => 'Automatic refresh~~',
	'UI:DashboardEdit:AutoReloadSec' => 'Automatic refresh interval (seconds)~~',
	'UI:DashboardEdit:AutoReloadSec+' => 'The minimum allowed is %1$d seconds~~',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Dashboard Egenskaber',
	'UI:DashboardEdit:Dashlets' => 'Tilgængelige Dashlets',
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Egenskaber',

	'UI:Form:Property' => 'Egenskab',
	'UI:Form:Value' => 'Værdi',

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

	'UI:DashletPlainText:Label' => 'Tekst',
	'UI:DashletPlainText:Description' => 'Plain text (ingen formatering)',
	'UI:DashletPlainText:Prop-Text' => 'Tekst',
	'UI:DashletPlainText:Prop-Text:Default' => 'Indtast venligst noget tekst her...',

	'UI:DashletObjectList:Label' => 'Objekt liste',
	'UI:DashletObjectList:Description' => 'Objekt liste dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Titel',
	'UI:DashletObjectList:Prop-Query' => 'Forespørgsel',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Titel',
	'UI:DashletGroupBy:Prop-Query' => 'Forespørgsel',
	'UI:DashletGroupBy:Prop-Style' => 'Style',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Grupper efter...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Timer af %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Måned af %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Ugedag for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Dag i måneden for %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (time)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (måned)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (ugedag)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (dag i måned)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Vælg venligst det felt, somobjekterne skal grupperes efter',

	'UI:DashletGroupByPie:Label' => 'Pie Chart',
	'UI:DashletGroupByPie:Description' => 'Pie Chart',
	'UI:DashletGroupByBars:Label' => 'Bar Chart',
	'UI:DashletGroupByBars:Description' => 'Bar Chart',
	'UI:DashletGroupByTable:Label' => 'Grupper Efter (tabel)',
	'UI:DashletGroupByTable:Description' => 'Liste (Grupperet efter felt)',

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

	'UI:DashletHeaderStatic:Label' => 'Header',
	'UI:DashletHeaderStatic:Description' => 'Vis en horisontal separator',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titel',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Kontakter',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Ikon',

	'UI:DashletHeaderDynamic:Label' => 'Header med statistik',
	'UI:DashletHeaderDynamic:Description' => 'Header med stats (grupperet efter...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Titel',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Kontakter',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Ikon',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Undertitel',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Kontakter',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Forespørgsel',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Gruper efter',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Værdier',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Objekt Ikon med ny/søg',
	'UI:DashletBadge:Prop-Class' => 'Klasse',

	'DayOfWeek-Sunday' => 'Søndag',
	'DayOfWeek-Monday' => 'Mandag',
	'DayOfWeek-Tuesday' => 'Tirsdag',
	'DayOfWeek-Wednesday' => 'Onsdag',
	'DayOfWeek-Thursday' => 'Torsdag',
	'DayOfWeek-Friday' => 'Fredag',
	'DayOfWeek-Saturday' => 'Lørdag',
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

	'UI:Menu:ShortcutList' => 'Opret Genvej...',
	'UI:ShortcutRenameDlg:Title' => 'Omdøb genvej',
	'UI:ShortcutListDlg:Title' => 'Opret en genvej for denne liste',
	'UI:ShortcutDelete:Confirm' => 'Bekræft venligst at du ønsker at slette genvej(e).',
	'Menu:MyShortcuts' => 'Mine Genveje', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Genvej',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Navn',
	'Class:Shortcut/Attribute:name+' => '',
	'Class:ShortcutOQL' => 'Søge resultat genvej',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Søgning',
	'Class:ShortcutOQL/Attribute:oql+' => '',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatic refresh~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Disabled~~',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Automatic refresh interval (seconds)~~',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'The minimum allowed is %1$d seconds~~',

	'UI:FillAllMandatoryFields' => 'Venligst udfyld alle obligatoriske felter.',
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
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
Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'UI:Newsroom:NoNewMessage' => 'No new message~~',
	'UI:Newsroom:MarkAllAsRead' => 'Mark all messages as read~~',
	'UI:Newsroom:ViewAllMessages' => 'View all messages~~',
	'UI:Newsroom:Preferences' => 'Newsroom preferences~~',
	'UI:Newsroom:ConfigurationLink' => 'Configuration~~',
	'UI:Newsroom:ResetCache' => 'Reset cache~~',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Display messages from %1$s~~',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Display up to %1$s messages in the %2$s menu.~~',
));
