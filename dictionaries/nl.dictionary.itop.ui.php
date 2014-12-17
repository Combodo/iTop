<?php 
// Copyright (C) 2010-2013 Combodo SARL
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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('NL NL', 'Dutch', 'Nederlands' , array(
	'Class:AuditCategory' => 'Audit Categorie',
	'Class:AuditCategory+' => 'Een onderdeel van de gehele audit',
	'Class:AuditCategory/Attribute:name' => 'Categorienaam',
	'Class:AuditCategory/Attribute:name+' => 'Afkorting van de naam van deze categorie',
	'Class:AuditCategory/Attribute:description' => 'Audit categorie beschrijving' ,
	'Class:AuditCategory/Attribute:description+' => 'Uitgebreide beschrijving van deze Audit categorie' ,
	'Class:AuditCategory/Attribute:definition_set' => 'Definitie Set',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL expression die de set van objecten naar audit defineert',
	'Class:AuditCategory/Attribute:rules_list' => 'Audit Regels',
	'Class:AuditCategory/Attribute:rules_list+' => 'Audit regels voor deze categorie',
));

//
// Class: AuditRule
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:AuditRule' => 'Audit Regel',
	'Class:AuditRule+' => 'Een regel voor het controleren van een bepaalde Audit categorie',
	'Class:AuditRule/Attribute:name' => 'Naam van de regel',
	'Class:AuditRule/Attribute:name+' => 'Afkorting van de regel',
	'Class:AuditRule/Attribute:description' => 'Audit Regel beschrijving',
	'Class:AuditRule/Attribute:description+' => 'Uitgebreide beschrijving van deze Audit regel',
	'Class:AuditRule/Attribute:query' => 'Query om te runnen',
	'Class:AuditRule/Attribute:query+' => 'De OQL expression voor het runnen',
	'Class:AuditRule/Attribute:valid_flag' => 'Geldige objecten?',
	'Class:AuditRule/Attribute:valid_flag+' => 'True als de regel de geldige objecten teruggeeft, anders false',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'true',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'false',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'false',
	'Class:AuditRule/Attribute:category_id' => 'Categorie',
	'Class:AuditRule/Attribute:category_id+' => 'De categorie voor deze regel',
	'Class:AuditRule/Attribute:category_name' => 'Categorie',
	'Class:AuditRule/Attribute:category_name+' => 'Naam van de categorie voor deze regel',
));

//
// Class: QueryOQL
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:Query' => 'Query',
	'Class:Query+' => 'Een query is een data set die op een dynamische manier is gedefineerd',
	'Class:Query/Attribute:name' => 'Naam',
	'Class:Query/Attribute:name+' => 'Identificeerd de query',
	'Class:Query/Attribute:description' => 'Beschrijving',
	'Class:Query/Attribute:description+' => 'Uitgebreide beschrijving voor de query(doel, gebruik, etc.)',
	'Class:Query/Attribute:fields' => 'Velden',
	'Class:Query/Attribute:fields+' => 'Comma separated list van attributen (of alias.attribute) om te exporteren',

	'Class:QueryOQL' => 'OQL Query',
	'Class:QueryOQL+' => 'Een query gebaseerd op de Object Query Language',
	'Class:QueryOQL/Attribute:oql' => 'Expressie',
	'Class:QueryOQL/Attribute:oql+' => 'OQL Expressie',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:User' => 'Gebruiker',
	'Class:User+' => 'Gebruiker login',
	'Class:User/Attribute:finalclass' => 'Accounttype',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Contact (persoon)',
	'Class:User/Attribute:contactid+' => 'Persoonlijke details van de business data',
	'Class:User/Attribute:last_name' => 'Achternaam',
	'Class:User/Attribute:last_name+' => 'Naam van het overeenkomende contact',
	'Class:User/Attribute:first_name' => 'Voornaam',
	'Class:User/Attribute:first_name+' => 'Voornaam van het overeenkomende contact',
	'Class:User/Attribute:email' => 'Email',
	'Class:User/Attribute:email+' => 'Email van het overeenkomende contact',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Identification string van de gebruiker',
	'Class:User/Attribute:language' => 'Taal',
	'Class:User/Attribute:language+' => 'Taal van de gebruiker',
	'Class:User/Attribute:language/Value:EN US' => 'Engels',
	'Class:User/Attribute:language/Value:EN US+' => 'Engels (U.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Frans',
	'Class:User/Attribute:language/Value:FR FR+' => 'Frans (Frankrijk)',
	'Class:User/Attribute:profile_list' => 'Profielen',
	'Class:User/Attribute:profile_list+' => 'Rollen, verlenen rechten aan deze persoon',
	'Class:User/Attribute:allowed_org_list' => 'Mijn organisaties',
	'Class:User/Attribute:allowed_org_list+' => 'De eindgebruiker heeft toestemming om data te zien van de volgende organisaties. Als er geen organisatie is gespecificeerd, is er geen restrictie.',

	'Class:User/Error:LoginMustBeUnique' => 'Login moet uniek zijn- â€œ%1sâ€� is al in gebruik',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'In ieder geval een profiel moet toegewezen zijn aan deze gebruiker',

	'Class:UserInternal' => 'Interne gebruiker',
	'Class:UserInternal+' => 'Gebruiker gedefineerd in iTop',
));

//
// Class: URP_Profiles
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_Profiles' => 'Profiel',
	'Class:URP_Profiles+' => 'Gebruikersprofiel',
	'Class:URP_Profiles/Attribute:name' => 'Naam',
	'Class:URP_Profiles/Attribute:name+' => 'label',
	'Class:URP_Profiles/Attribute:description' => 'Beschrijving',
	'Class:URP_Profiles/Attribute:description+' => 'Beschrijving bestaand uit een regel',
	'Class:URP_Profiles/Attribute:user_list' => 'Gebruikers',
	'Class:URP_Profiles/Attribute:user_list+' => 'Gebruikers met deze rol',
));

//
// Class: URP_Dimensions
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_Dimensions' => 'Dimensie',
	'Class:URP_Dimensions+' => 'Dimensie van de applicatie (defineert silos)',
	'Class:URP_Dimensions/Attribute:name' => 'Naam',
	'Class:URP_Dimensions/Attribute:name+' => 'label',
	'Class:URP_Dimensions/Attribute:description' => 'Beschrijving',
	'Class:URP_Dimensions/Attribute:description+' => 'Beschrijving bestaande uit een regel',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'Klassenaam of data type (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_UserProfile' => 'Gebruiker naar profiel',
	'Class:URP_UserProfile+' => 'Gebruikerprofielen',
	'Class:URP_UserProfile/Attribute:userid' => 'Gebruiker',
	'Class:URP_UserProfile/Attribute:userid+' => 'Gebruiker account',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'Login van de gebruiker',
	'Class:URP_UserProfile/Attribute:profileid' => 'Profiel',
	'Class:URP_UserProfile/Attribute:profileid+' => 'Gebruiksprofiel',
	'Class:URP_UserProfile/Attribute:profile' => 'Profiel',
	'Class:URP_UserProfile/Attribute:profile+' => 'Naam van het profiel',
	'Class:URP_UserProfile/Attribute:reason' => 'Reden',
	'Class:URP_UserProfile/Attribute:reason+' => 'Leg uit waarom deze persoon deze rol heeft',
));

//
// Class: URP_UserOrg
//


Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_UserOrg' => 'Gebruikersorganisaties',
	'Class:URP_UserOrg+' => 'Mijn organisaties',
	'Class:URP_UserOrg/Attribute:userid' => 'Gebruiker',
	'Class:URP_UserOrg/Attribute:userid+' => 'Account van de gebruiker',
	'Class:URP_UserOrg/Attribute:userlogin' => 'Login',
	'Class:URP_UserOrg/Attribute:userlogin+' => 'Login van de gebruiker',
	'Class:URP_UserOrg/Attribute:allowed_org_id' => 'Organisatie',
	'Class:URP_UserOrg/Attribute:allowed_org_id+' => 'Mijn organisatie',
	'Class:URP_UserOrg/Attribute:allowed_org_name' => 'Mijn organisatie',
	'Class:URP_UserOrg/Attribute:allowed_org_name+' => 'Mijn organisatie',
	'Class:URP_UserOrg/Attribute:reason' => 'Reden',
	'Class:URP_UserOrg/Attribute:reason+' => 'Leg uit waarom deze persoon de data van deze organisatie mag inzien',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_ProfileProjection' => 'profile_projection',
	'Class:URP_ProfileProjection+' => 'profile projections',
	'Class:URP_ProfileProjection/Attribute:dimensionid' => 'Dimensie',
	'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'Dimensie van de applicatie',
	'Class:URP_ProfileProjection/Attribute:dimension' => 'Dimensie',
	'Class:URP_ProfileProjection/Attribute:dimension+' => 'Dimensie van de applicatie',
	'Class:URP_ProfileProjection/Attribute:profileid' => 'Profiel',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'Gebruiksprofiel',
	'Class:URP_ProfileProjection/Attribute:profile' => 'Profiel',
	'Class:URP_ProfileProjection/Attribute:profile+' => 'Naam van het profiel',
	'Class:URP_ProfileProjection/Attribute:value' => 'Value expression',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL expression (gebruikt $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribuut',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribuutcode (optioneel)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'class projections',
	'Class:URP_ClassProjection/Attribute:dimensionid' => 'Dimensie',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'Dimensie van de applicatie',
	'Class:URP_ClassProjection/Attribute:dimension' => 'Dimensie',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'Dimensie van de applicatie',
	'Class:URP_ClassProjection/Attribute:class' => 'Klasse',
	'Class:URP_ClassProjection/Attribute:class+' => 'Target klasse',
	'Class:URP_ClassProjection/Attribute:value' => 'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL expression (gebruikt $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribuut',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Target attribuutcode (optioneel)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_ActionGrant' => 'action_permission',
	'Class:URP_ActionGrant+' => 'Toestemming aan klasses',
	'Class:URP_ActionGrant/Attribute:profileid' => 'Profiel',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'Gebruiksprofiel',
	'Class:URP_ActionGrant/Attribute:profile' => 'Profiel',
	'Class:URP_ActionGrant/Attribute:profile+' => 'Gebruiksprofiel',
	'Class:URP_ActionGrant/Attribute:class' => 'Klasse',
	'Class:URP_ActionGrant/Attribute:class+' => 'Target klasse',
	'Class:URP_ActionGrant/Attribute:permission' => 'Toestemming',
	'Class:URP_ActionGrant/Attribute:permission+' => 'Toestemming of geen toestemming?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nee',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'Nee',
	'Class:URP_ActionGrant/Attribute:action' => 'Actie',
	'Class:URP_ActionGrant/Attribute:action+' => 'Actie om te ondernemen op een bepaalde klasse',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'Toegestane stimulus in de levenscyclus van het object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'Profiel',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'Gebruiksprofiel',
	'Class:URP_StimulusGrant/Attribute:profile' => 'Profiel',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'Gebruiksprofiel',
	'Class:URP_StimulusGrant/Attribute:class' => 'Klasse',
	'Class:URP_StimulusGrant/Attribute:class+' => 'Target klasse',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Toestemming',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Toestemming of geen toestemming',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nee',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'Nee',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'Toestemming op het niveau van de attributen',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Actie verleen',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'actie verleen',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribuut',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'attribuut code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'BooleanLabel:yes' => 'Ja',
	'BooleanLabel:no' => 'Nee',
	'Menu:WelcomeMenu' => 'Welkom',
	'Menu:WelcomeMenu+' => 'Welkom in iTop',
	'Menu:WelcomeMenuPage' => 'Welkom',
	'Menu:WelcomeMenuPage+' => 'Welkom in iTop',
	'UI:WelcomeMenu:Title' => 'Welkom in iTop',

	'UI:WelcomeMenu:LeftBlock' => '<p>iTop is een compleet, OpenSource, IT Operationeel Portaal.</p>
<ul>Inclusief:
<li>Een complete CMDB (Configuration management database) voor het documenteren en managen van de IT inventaris.</li>
<li>Een Incident management module voor het vinden van en communiceren over alle problemen die optreden binnen de IT.</li>
<li>Een change management module voor het plannen en natrekken van de veranderingen in de IT omgeving.</li>
<li>Een database met bekende errors om de oplossing van incidenten te versnellen.</li>
<li>Een storingsmodule voor het documenteren van alle geplande storingen en voor het informeren van de juiste contacten.</li>
<li>Dashboards om snel een overzicht te krijgen van uw IT.</li>
</ul>
<p>Alle modules kunnen apart, stap voor stap, volledig onafhankelijk van elkaar, worden opgezet.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>iTop is georiÃ«nteerd op service providers, het zorgt ervoor dat IT engineers makkelijk meerdere klanten of organisaties kunnen managen.
<ul>iTop, levert een uitgebreide set van zakenprocessen die:
<li>De effectiveit van het IT management verbeterd</li> 
<li>De prestatie van IT operaties verbeterd</li> 
<li>De klanttevredenheid verhoogd en leidinggevenden inzicht biedt in hun business performance.</li>
</ul>
</p>
<p>iTop is klaar om te worden geïntegreerd met uw huidige IT Management infrastructuur.</p>
<p>
<ul>De adoptie van dit IT Operational portaal van de nieuwste generatie zal u helpen met:
<li>Het beter managen van een meer en meer complexe IT omgeving.</li>
<li>Het implementeren van ITIL processen in uw eigen tempo.</li>
<li>Het managen van het belangrijkste middel van uw IT: Documentatie.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Open aanvragen: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mijn aanvragen',
	'UI:WelcomeMenu:OpenIncidents' => 'Open incidenten: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuratie Items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Aan mij toegewezen incidenten',
	'UI:AllOrganizations' => ' Alle Organisaties ',
	'UI:YourSearch' => 'Jouw zoekopdracht',
	'UI:LoggedAsMessage' => 'Ingelogd als %1$s',
	'UI:LoggedAsMessage+Admin' => 'Ingelogd als %1$s (Administrator)',
	'UI:Button:Logoff' => 'Log uit',
	'UI:Button:GlobalSearch' => 'Zoek',
	'UI:Button:Search' => ' Zoek ',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Opslaan',
	'UI:Button:Cancel' => 'Annuleer',
	'UI:Button:Apply' => 'Pas toe',
	'UI:Button:Back' => ' << Vorige ',
	'UI:Button:Restart' => ' |<< Herstart ',
	'UI:Button:Next' => ' Volgende >> ',
	'UI:Button:Finish' => ' Eindig ',
	'UI:Button:DoImport' => ' Run de Import ! ',
	'UI:Button:Done' => ' Klaar ',
	'UI:Button:SimulateImport' => ' Simuleer de Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Evalueer ',
	'UI:Button:AddObject' => ' Voeg toe... ',
	'UI:Button:BrowseObjects' => ' Browse... ',
	'UI:Button:Add' => ' Voeg toe ',
	'UI:Button:AddToList' => ' << Voeg toe ',
	'UI:Button:RemoveFromList' => ' Verwijder >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Maak aan ',
	'UI:Button:Delete' => ' Verwijder ! ',
	'UI:Button:Rename' => ' Hernoem... ',
	'UI:Button:ChangePassword' => ' Verander Password ',
	'UI:Button:ResetPassword' => ' Reset Password ',
	
	'UI:SearchToggle' => 'Zoek',
	'UI:ClickToCreateNew' => 'Maak een %1$s aan',
	'UI:SearchFor_Class' => 'Zoek naar %1$s objecten',
	'UI:NoObjectToDisplay' => 'Geen object om weer te geven.',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter object_id is verplicht als link_attr is gespecificeerd. Controleer de definitie van het display template.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter target_attr is verplicht als link_attr is gespecificeerd. Controleer de definitie van het display template.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter group_by is verplicht. Controleer de definitie van het display template.',
	'UI:Error:InvalidGroupByFields' => 'Invalid list of fields to group by: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Error: style of block wordt niet ondersteund: "%1$s".',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Incorrecte linkdefinitie: de klasse van objecten om te managen: %1$s is niet gevonden als external key in de klasse %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Object: %1$s:%2$d niet gevonden',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Error: Circulaire referentie in  de afhankelijke variabelen tussen de velden, controleer het data model.',
	'UI:Error:UploadedFileTooBig' => 'Het geÃ¼ploade bestand is te groot. (Maximale grootte is %1$s). Contacteer uw iTop administrator om dit limiet aan te passen. (Check de PHP configuratie voor upload_max_filesize en post_max_size op de server).',
	'UI:Error:UploadedFileTruncated.' => 'Het geÃ¼ploade bestand is afkapt !',
	'UI:Error:NoTmpDir' => 'De tijdelijke opslagruimte is niet gedefineerd.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Niet mogelijk om het tijdelijke bestand naar de schijf over te schrijven. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload gestopt door extension. (Original file name = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Uploaden van bestand mislukt, oorzaak onbekend. (Error code = "%1$s").',
	
	'UI:Error:1ParametersMissing' => 'Error: de volgende parameter moet worden gespecificeerd voor deze actie: %1$s.',
	'UI:Error:2ParametersMissing' => 'Error: de volgende parameters moeten worden gespecificeerd voor deze actie: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Error: de volgende parameters moeten worden gespecificeerd voor deze actie: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Error: de volgende parameters moeten worden gespecificeerd voor deze actie: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Error: incorrecte OQL query: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Een error trad op tijdens het runnen van de query: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Error: het object is al geupdatet.',
	'UI:Error:ObjectCannotBeUpdated' => 'Error: het object kan niet worden geupdatet.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Error: objecten zijn al verwijderd',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'U bent niet gemachtigd tot het grootschalig verwijderen van objecten in klasse %1$s',
	'UI:Error:DeleteNotAllowedOn_Class' => 'U bent niet gemachtigd objecten in klasse %1$s te verwijderen',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'U bent niet gemachtigd tot een grootschalige update van objecten in klasse %1$s',
	'UI:Error:ObjectAlreadyCloned' => 'Error: het object is al gekloond!',
	'UI:Error:ObjectAlreadyCreated' => 'Error: het object is al aangemaakt!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Error: invalide stimulus "%1$s" op object %2$s in state "%3$s".',
	
	
	'UI:GroupBy:Count' => 'Tel',
	'UI:GroupBy:Count+' => 'Aantal elementen',
	'UI:CountOfObjects' => '%1$d objecten voldoen aan de criteria.',
	'UI_CountOfObjectsShort' => '%1$d objecten.',
	'UI:NoObject_Class_ToDisplay' => 'Geen %1$s om weer te geven',
	'UI:History:LastModified_On_By' => 'Laatst bewerkt op %1$s door %2$s.',
	'UI:HistoryTab' => 'Geschiedenis',
	'UI:NotificationsTab' => 'Notificaties',
	'UI:History:BulkImports' => 'Geschiedenis',
	'UI:History:BulkImports+' => 'Lijst van CSV imports (nieuwste import eerst)',
	'UI:History:BulkImportDetails' => 'Veranderingen volgend op CSV import uitgevoerd op %1$s (door %2$s)',
	'UI:History:Date' => 'Datum',
	'UI:History:Date+' => 'Datum van verandering',
	'UI:History:User' => 'Gebruiker',
	'UI:History:User+' => 'Gebruiker die de verandering doorvoerde',
	'UI:History:Changes' => 'Verandering',
	'UI:History:Changes+' => 'Veranderingen gemaakt aan object',
	'UI:History:StatsCreations' => 'Aangemaakt',
	'UI:History:StatsCreations+' => 'Aantal aangemaakte objecten',
	'UI:History:StatsModifs' => 'Aangepast',
	'UI:History:StatsModifs+' => 'Aantal aangepaste objecten',
	'UI:History:StatsDeletes' => 'Verwijderd',
	'UI:History:StatsDeletes+' => 'Aantal verwijderde objecten',
	'UI:Loading' => 'Laden...',
	'UI:Menu:Actions' => 'Acties',
	'UI:Menu:OtherActions' => 'Andere acties',
	'UI:Menu:New' => 'Nieuw...',
	'UI:Menu:Add' => 'Voeg toe...',
	'UI:Menu:Manage' => 'Manage...',
	'UI:Menu:EMail' => 'eMail',
	'UI:Menu:CSVExport' => 'CSV Export',
	'UI:Menu:Modify' => 'Bewerk...',
	'UI:Menu:Delete' => 'Verwijder...',
	'UI:Menu:Manage' => 'Manage...',
	'UI:Menu:BulkDelete' => 'Verwijder...',
	'UI:UndefinedObject' => 'Ongedefineerd',
	'UI:Document:OpenInNewWindow:Download' => 'Open in nieuw window: %1$s, Download: %2$s',
	'UI:SelectAllToggle+' => 'Selecteer / Deselecteer Alles',
	'UI:SplitDateTime-Date' => 'datum',
	'UI:SplitDateTime-Time' => 'tijd',
	'UI:TruncatedResults' => '%1$d objecten weergegeven buiten %2$d',
	'UI:DisplayAll' => 'Geef Alles weer',
	'UI:CollapseList' => 'Collapse',
	'UI:CountOfResults' => '%1$d object(s)',
	'UI:ChangesLogTitle' => 'Changes log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Changes log is leeg',
	'UI:SearchFor_Class_Objects' => 'Zoek naar %1$s Objecten',
	'UI:OQLQueryBuilderTitle' => 'OQL Query Builder',
	'UI:OQLQueryTab' => 'OQL Query',
	'UI:SimpleSearchTab' => 'Simple Search',
	'UI:Details+' => 'Details',
	'UI:SearchValue:Any' => '* Ieder *',
	'UI:SearchValue:Mixed' => '* mixed *',
	'UI:SearchValue:NbSelected' => '# geselecteerd',
	'UI:SearchValue:CheckAll' => 'Check Alles',
	'UI:SearchValue:UncheckAll' => 'Uncheck Alles',
	'UI:SelectOne' => '-- selecteer een --',
	'UI:Login:Welcome' => 'Welkom in iTop!',
	'UI:Login:IncorrectLoginPassword' => 'Incorrect login/wachtwoord, probeer opnieuw.',
	'UI:Login:IdentifyYourself' => 'Identificeer uzelf voordat u verder gaat',
	'UI:Login:UserNamePrompt' => 'Gebruikersnaam',
	'UI:Login:PasswordPrompt' => 'Wachtwoord',
	'UI:Login:ForgotPwd' => 'Wachtwoord vergeten?',
	'UI:Login:ForgotPwdForm' => 'Wachtwoord vergeten',
	'UI:Login:ForgotPwdForm+' => 'iTop kan u een e-mail sturen waarin u de instructies voor het resetten van uw account kunt vinden.',
	'UI:Login:ResetPassword' => 'Stuur nu!',
	'UI:Login:ResetPwdFailed' => 'E-mail sturen mislukt: %1$s',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is geen geldige login',
	'UI:ResetPwd-Error-NotPossible' => 'externe accounts staan het resetten van het wachtwoord niet toe.',
	'UI:ResetPwd-Error-FixedPwd' => 'deze account staat het resetten van het wachtwoord niet toe',
	'UI:ResetPwd-Error-NoContact' => 'deze account is niet geassocieerd met een persoon.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'deze account is niet geassocieerd met een persoon die beschikt over een e-mail attribuut. Neem alstublieft contact op met uw administrator.',
	'UI:ResetPwd-Error-NoEmail' => 'Er mist een e-mailadres. Neem alstublieft contact op met uw administrator.',
	'UI:ResetPwd-Error-Send' => 'Er is een technisch probleem bij het verzenden van de e-mail. Neem alstublieft contact op met uw administrator.',
	'UI:ResetPwd-EmailSent' => 'Kijk alstublieft in uw mailbox en volg de insturcties...',
	'UI:ResetPwd-EmailSubject' => 'Reset uw iTop wachtwoord',
	'UI:ResetPwd-EmailBody' => '<body><p>U hebt een reset van uw iTop wachtwoord aangevraagd.</p><p>Klik op deze link (eenmalig gebruik) om <a href="%1$s">een nieuw wachtwoord in te voeren</a></p>.',

	'UI:ResetPwd-Title' => 'Reset wachtwoord',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, of uw wachtwoord is al gereset, of u heeft al meerdere e-mails ontvangen. Zorg ervoor dat u de link in de laatst ontvangen e-mail gebruikt.',
	'UI:ResetPwd-Error-EnterPassword' => 'Voer het nieuwe wachtwoord voor de account \'%1$s\' in.',
	'UI:ResetPwd-Ready' => 'Het wachtwoord is veranderd',
	'UI:ResetPwd-Login' => 'Klik hier om in te loggen',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Verander uw wachtwoord',
	'UI:Login:OldPasswordPrompt' => 'Oud wachtwoord',
	'UI:Login:NewPasswordPrompt' => 'Nieuw wachtwoord',
	'UI:Login:RetypeNewPasswordPrompt' => 'Herhaal nieuwe wachtwoord',
	'UI:Login:IncorrectOldPassword' => 'Error: het oude wachtwoord is incorrect',
	'UI:LogOffMenu' => 'Log uit',
	'UI:LogOff:ThankYou' => 'Bedankt voor het gebruiken van iTop',
	'UI:LogOff:ClickHereToLoginAgain' => 'Klik hier om in te loggen',
	'UI:ChangePwdMenu' => 'Verander wachtwoord',
	'UI:Login:PasswordChanged' => 'Wachtwoord succesvol veranderd',
	'UI:AccessRO-All' => 'iTop is alleen-lezen',
	'UI:AccessRO-Users' => 'iTop is alleen-lezen voor eindgebruikers',
	'UI:ApplicationEnvironment' => 'Omgeving van de applicatie: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Het nieuwe wachtwoord en de herhaling van het nieuwe wachtwoord komen niet overeen',
	'UI:Button:Login' => 'Enter iTop',
	'UI:Login:Error:AccessRestricted' => 'iTop toegang is verboden. Neem alstublieft contact op met een iTop administrator.',
	'UI:Login:Error:AccessAdmin' => 'Alleen toegankelijk voor mensen met administrator rechten. Neem alstublieft contact op met een iTop administrator',
	'UI:CSVImport:MappingSelectOne' => '-- Selecteer een --',
	'UI:CSVImport:MappingNotApplicable' => '-- Negeer dit veld --',
	'UI:CSVImport:NoData' => 'Lege data set..., voeg data toe',
	'UI:Title:DataPreview' => 'Data Preview',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Error: De data bevat slechts een column. Hebt de juiste separator karakter geselecteerd?',
	'UI:CSVImport:FieldName' => 'Veld %1$d',
	'UI:CSVImport:DataLine1' => 'Data Line 1',
	'UI:CSVImport:DataLine2' => 'Data Line 2',
	'UI:CSVImport:idField' => 'id (Primary Key)',
	'UI:Title:BulkImport' => 'iTop - Bulk import',
	'UI:Title:BulkImport+' => 'CSV Import Wizard',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronisatie van %1$d objecten van klasse %2$s',
	'UI:CSVImport:ClassesSelectOne' => '-- selecteer een --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Interne error: "%1$s" is een incorrecte code omdat "%2$s" geen externe key van klasse "%3$s" is',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objecten(s) zullen onveranderd blijven.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objecten(s) zullen worden veranderd.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objecten(s) zullen worden toegevoegd.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objecten(s) zullen errors hebben.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objecten(s) zijn onveranderd gebleven.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objecten(s) zijn veranderd.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objecten(s) zijn toegevoegd.',
	'UI:CSVImport:ObjectsHadErrors' => 'bij %1$d objecten(s) traden errors op.',
	'UI:Title:CSVImportStep2' => 'Step 2 of 5: CSV data opties',
	'UI:Title:CSVImportStep3' => 'Step 3 of 5: Data mapping',
	'UI:Title:CSVImportStep4' => 'Step 4 of 5: Import simulatie',
	'UI:Title:CSVImportStep5' => 'Step 5 of 5: Import compleet',
	'UI:CSVImport:LinesNotImported' => 'Regels die niet konden worden geladen:',
	'UI:CSVImport:LinesNotImported+' => 'De volgende regels zijn niet geïmporteerd omdat ze fouten bevatten',
	'UI:CSVImport:SeparatorComma+' => ', (komma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (semicolon)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'other:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (dubbele quote)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (enkele quote)',
	'UI:CSVImport:QualifierOther' => 'anders:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Beschouw de eerste regel als kop (column naam)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Sla %1$s regels aan het begin van het bestand over',
	'UI:CSVImport:CSVDataPreview' => 'CSV Data Preview',
	'UI:CSVImport:SelectFile' => 'Selecteer het bestand om te importeren:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Laad van een bestand',
	'UI:CSVImport:Tab:CopyPaste' => 'Kopieer en plak data',
	'UI:CSVImport:Tab:Templates' => 'Templates',
	'UI:CSVImport:PasteData' => 'Plak data om te importeren:',
	'UI:CSVImport:PickClassForTemplate' => 'Kies template om te downloaden: ',
	'UI:CSVImport:SeparatorCharacter' => 'Separator karakter:',
	'UI:CSVImport:TextQualifierCharacter' => 'Text qualifier karakter',
	'UI:CSVImport:CommentsAndHeader' => 'Opmerkingen en kopje',
	'UI:CSVImport:SelectClass' => 'Selecteer de klasse om te importeren:',
	'UI:CSVImport:AdvancedMode' => 'Advanced mode',
	'UI:CSVImport:AdvancedMode+' => 'In advanced mode kan het "id" (primary key) van de objecten worden gebruikt om deze te updaten en te hernoemen.' .
									'De column "id" (indien beschikbaar)echter, kan alleen worden gebruikt als zoekcriterium en kan niet worden gecombineerd met andere zoekcriteria.',
	'UI:CSVImport:SelectAClassFirst' => 'Om de mapping te configureren, moet u eerst een klasse selecteren.',
	'UI:CSVImport:HeaderFields' => 'Velden',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Search?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Selecteer alstublieft een mapping voor ieder veld',
	'UI:CSVImport:AlertMultipleMapping' => 'Zorg er alstublieft voor dat het veld slechts een keer gemapped is',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Selecteer alstublieft tenminste een zoekcriterium.',
	'UI:CSVImport:Encoding' => 'Character encoding',	
	'UI:UniversalSearchTitle' => 'iTop - Universele zoekopdracht',
	'UI:UniversalSearch:Error' => 'Error: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selecteer de klasse om te zoeken: ',

	'UI:CSVReport-Value-Modified' => 'Veranderd',
	'UI:CSVReport-Value-SetIssue' => 'Kon niet worden veranderd- reden: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Kon niet worden veranderd naar %1$s - reden: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Geen overeenkomst',
	'UI:CSVReport-Value-Missing' => 'Ontbrekende verplichte waarde',
	'UI:CSVReport-Value-Ambiguous' => 'Onduidelijk: gevonden %1$s objecten',
	'UI:CSVReport-Row-Unchanged' => 'onveranderd',
	'UI:CSVReport-Row-Created' => 'gemaakt',
	'UI:CSVReport-Row-Updated' => ' %1$d geupdatet cols',
	'UI:CSVReport-Row-Disappeared' => 'verdwenen, %1$d veranderde cols',
	'UI:CSVReport-Row-Issue' => 'Probleem: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null niet toegestaan',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object niet gevonden',
	'UI:CSVReport-Value-Issue-FoundMany' => ' %1$d Matches gevonden',
	'UI:CSVReport-Value-Issue-Readonly' => 'Het attribuut \'%1$s\' is alleen-lezen en kan niet worden veranderd (huidige waarde: %2$s,voorgestelde waarde: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Input %1$s verwerken mislukt',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Onverwachte waarde voor attribuut \'%1$s\': geen match gevonden, controleer spelling',
	'UI:CSVReport-Value-Issue-Unknown' => 'Onverwachte waarde voor attribuut \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributen komen niet met elkaar overeeen: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Onverwachte attribuutwaarden',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Kon niet worden gemaakt, door het ontbreken van externe code(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'verkeerde date format',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Verbeteren mislukt',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Onduidelijke verbetering',
	'UI:CSVReport-Row-Issue-Internal' => 'Interne error: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Onveranderd',
	'UI:CSVReport-Icon-Modified' => 'Aangepast',
	'UI:CSVReport-Icon-Missing' => 'Missend',
	'UI:CSVReport-Object-MissingToUpdate' => 'Missend object: zal worden geupdatet',
	'UI:CSVReport-Object-MissingUpdated' => 'Missend: geupdatet',
	'UI:CSVReport-Icon-Created' => 'Aangemaakt',
	'UI:CSVReport-Object-ToCreate' => 'Object zal worden aangemaakt',
	'UI:CSVReport-Object-Created' => 'Object aangemaakt',
	'UI:CSVReport-Icon-Error' => 'Error',
	'UI:CSVReport-Object-Error' => 'ERROR: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'ONDUIDELIJK: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% van de geladen objecten bevatten fouten en zullen worden genegeerd',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% van de geladen objecten zullen worden gemaakt',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% zullen worden aangepast.',

	'UI:CSVExport:AdvancedMode' => 'Advanced mode',
	'UI:CSVExport:AdvancedMode+' => 'In advanced mode zijn verscheidene columns toegevoegd aan de export: het id van het object, het id van de externe codes en hun herstelattributen.',
	'UI:CSVExport:LostChars' => 'Encoding probleem',
	'UI:CSVExport:LostChars+' => 'Het gedownloade bestand zal worden gecodeerd in %1$s. iTop heeft een aantal karakters gedetecteerd die niet compatible zijn met deze format. Deze karakters zullen worden vervangen door een ander karakter (bijvoorbeeld karakters met accent, zullen het accent verliezen), of ze zullen worden verwijderd. U kunt data kopiÃ«ren en plakken van uw web browser. Ook kunt u de administrator contacteren om de codes te veranderen (Zie parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => 'iTop - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interactieve Audit',
	'UI:Audit:HeaderAuditRule' => 'Audit Regel',
	'UI:Audit:HeaderNbObjects' => '# Objecten',
	'UI:Audit:HeaderNbErrors' => '# Errors',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL Error in de Regel %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL Error in de Categorie %1$s: %2$s.',

	'UI:RunQuery:Title' => 'iTop - OQL Query Evaluatie',
	'UI:RunQuery:QueryExamples' => 'Query Voorbeelden',
	'UI:RunQuery:HeaderPurpose' => 'Doel',
	'UI:RunQuery:HeaderPurpose+' => 'Uitleg over de query',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL Expression',
	'UI:RunQuery:HeaderOQLExpression+' => 'De query in OQL syntax',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expression om te evalueren: ',
	'UI:RunQuery:MoreInfo' => 'Meer informatie over de query: ',
	'UI:RunQuery:DevelopedQuery' => 'Redevelopped query expression: ',
	'UI:RunQuery:SerializedFilter' => 'Serialized filter: ',
	'UI:RunQuery:Error' => 'Een fout is opgetreden tijdens het runnen van query: %1$s',
	'UI:Query:UrlForExcel' => 'URL om te gebruiken voor MS-Excel web queries',
	'UI:Schema:Title' => 'iTop objecten schema',
	'UI:Schema:CategoryMenuItem' => 'Categorie <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relaties',
	'UI:Schema:AbstractClass' => 'Abstracte klasse: objecten van deze klasse kunnen niet worden geïnstantieerd.',
	'UI:Schema:NonAbstractClass' => 'Niet abstracte klasse: objecten van deze klasse kunnen worden geïnstantieerd.',
	'UI:Schema:ClassHierarchyTitle' => 'Klasse hierarchie',
	'UI:Schema:AllClasses' => 'Alle klassen',
	'UI:Schema:ExternalKey_To' => 'Externe key voor %1$s',
	'UI:Schema:Columns_Description' => 'Columns: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Default: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null Allowed',
	'UI:Schema:NullNotAllowed' => 'Null NOT Allowed',
	'UI:Schema:Attributes' => 'Attributen',
	'UI:Schema:AttributeCode' => 'Attribuutcode',
	'UI:Schema:AttributeCode+' => 'Interne code van het attribuut',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label van het attribuut',
	'UI:Schema:Type' => 'Type',
	
	'UI:Schema:Type+' => 'Data type van het attribuut',
	'UI:Schema:Origin' => 'Oorsprong',
	'UI:Schema:Origin+' => 'De basisklasse waarin dit attribuut is gedefineerd',
	'UI:Schema:Description' => 'Beschrijving',
	'UI:Schema:Description+' => 'Beschrijving van het attribuut',
	'UI:Schema:AllowedValues' => 'Allowed values',
	'UI:Schema:AllowedValues+' => 'Regels voor de mogelijke waarden van dit attribuut',
	'UI:Schema:MoreInfo' => 'Meer informatie',
	'UI:Schema:MoreInfo+' => 'Meer informatie over het veld gedefineerd in de database',
	'UI:Schema:SearchCriteria' => 'Zoekcriteria',
	'UI:Schema:FilterCode' => 'Filter code',
	'UI:Schema:FilterCode+' => 'Code van deze zoekcriteria',
	'UI:Schema:FilterDescription' => 'Beschrijving',
	'UI:Schema:FilterDescription+' => 'Beschrijving van deze zoekcriteria',
	'UI:Schema:AvailOperators' => 'Beschikbare medewerkers',
	'UI:Schema:AvailOperators+' => 'Mogelijke medewerkes voor deze zoekcriteria',
	'UI:Schema:ChildClasses' => 'Child klassen',
	'UI:Schema:ReferencingClasses' => 'Refererende klassen',
	'UI:Schema:RelatedClasses' => 'Gerelateerde klassen',
	'UI:Schema:LifeCycle' => 'Levenscyclus',
	'UI:Schema:Triggers' => 'Triggers',
	'UI:Schema:Relation_Code_Description' => 'Relatie <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Down: %1$s',
	'UI:Schema:RelationUp_Description' => 'Up: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propageer naar %2$d levels, query: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: propageert niet (%2$d levels), query: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s is aangeduid met klasse %2$s via het veld %3$s',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s is gelinkt met %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Klasses wijzend naar %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Klassen gelinkt aan %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Graph van alle gerelateerde klassen',
	'UI:Schema:NoLifeCyle' => 'Er is geen levenscyclus gedefineerd voor deze klasse.',
	'UI:Schema:LifeCycleTransitions' => 'Transities',
	'UI:Schema:LifeCyleAttributeOptions' => 'Attribuut options',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Verborgen',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Alleen-lezen',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Verplicht',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Moet worden veranderd',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Gebruiker zal worden gevraagd om de waarde te veranderen',
	'UI:Schema:LifeCycleEmptyList' => 'lege lijst',
	'UI:LinksWidget:Autocomplete+' => 'Typ de eerste 3 karakters...',
	'UI:Edit:TestQuery' => 'Test query',
	'UI:Combo:SelectValue' => '--- selecteer een waarde ---',
	'UI:Label:SelectedObjects' => 'Geselecteerde objecten: ',
	'UI:Label:AvailableObjects' => 'Beschikbare objecten: ',
	'UI:Link_Class_Attributes' => '%1$s attributen',
	'UI:SelectAllToggle+' => 'Selecteer Alles / Deselecteer Alles',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Voeg %1$s objecten gelinkt met %2$s: %3$s toe',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Voeg %1$s objecten toe om te linken met de %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Manage %1$s objecten gelinkt met %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Voeg %1$s toe...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Verwijder geselecteerde objecten',
	'UI:Message:EmptyList:UseAdd' => 'De lijst is leeg, gebruik de "Voeg toe..." knop om elementen toe te voegen.',
	'UI:Message:EmptyList:UseSearchForm' => 'Gebruik het bovenstaande zoekveld om te zoeker naar objecten die u wilt toevoegen.',
	'UI:Wizard:FinalStepTitle' => 'Laatste stap: bevestiging',
	'UI:Title:DeletionOf_Object' => 'Verwijderen van %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Grootschalig verwijderen van %1$d objecten van klasse %2$s',
	'UI:Delete:NotAllowedToDelete' => 'U bent niet gemachtigd om dit object te verwijderen',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'U bent niet gemachtigd om het/de volgende veld(en) te updaten: %1$s',
	'UI:Error:NotEnoughRightsToDelete' => 'Dit object kon niet worden verwijderd omdat de huidige gebruiker niet de juiste rechten heeft',
	'UI:Error:CannotDeleteBecause' => 'Dit object kon niet worden verwijderd omdat: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Dit object kon niet worden verwijderd omdat eerst enkele manuele handelingen moeten worden verricht',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Dit object kon niet worden verwijderd omdat eerst enkele manuele handelingen moeten worden verricht',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s vanwege %2$s',
	'UI:Delete:Deleted' => 'verwijderd',
	'UI:Delete:AutomaticallyDeleted' => 'automatisch verwijderd',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatische reset van veld(en): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Bezig met het opschonen van alle referenties naar %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Bezig met het opschonen van %1$d objecten van klasse %2$s...',
	'UI:Delete:Done+' => 'Wat er is gebeurd...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s verwijderd',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Verwijderen van %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Verwijderen van %1$d objecten van klasse %2$s',
	'UI:Delete:CannotDeleteBecause' => 'Kon niet worden verwijderd: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Zou automatisch moeten worden verwijderd, maar dat is niet mogelijk: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Moet handmatig worden verwijderd, maar dat is niet mogelijk: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Zal automatisch worden verwijderd',
	'UI:Delete:MustBeDeletedManually' => 'Moet handmatig worden verwijderd',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Zou automatisch moeten worden geupdatet, maar: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Zal automatisch worden geupdatet (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objecten/links refereren naar %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objecten/links refereren naar sommige objecten die worden verwijderd',	
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Om Database integriteit te verzekeren, moet elke verdere referentie worden verwijderd',
	'UI:Delete:Consequence+' => 'Wat er zal gebeuren',
	'UI:Delete:SorryDeletionNotAllowed' => 'Sorry, u bent niet gemachtigd om dit object te verwijderen. Voor uitgebreide uitleg, zie hierboven',
	'UI:Delete:PleaseDoTheManualOperations' => 'Verricht alstublieft eerst de manuele handelingen die hierboven staan beschreven voordat u dit object verwijdert',
	'UI:Delect:Confirm_Object' => 'Bevestig dat u  %1$s wil verwijderen.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Bevestig dat u de volgende %1$d objecten van klasse %2$s wilt verwijderen.',
	'UI:WelcomeToITop' => 'Welcome in iTop',
	'UI:DetailsPageTitle' => 'iTop - %1$s - %2$s details',
	'UI:ErrorPageTitle' => 'iTop - Error',
	'UI:ObjectDoesNotExist' => 'Sorry, dit object bestaat niet (of u bent niet gemachtigd het te bekijken).',
	'UI:SearchResultsPageTitle' => 'iTop - Zoekresultaten',
	'UI:Search:NoSearch' => 'Geen zoekopdracht',
	'UI:Search:NeedleTooShort' => 'De zoekopdracht "%1$s" is te kort. Type tenminste %2$d karakters.',
	'UI:Search:Ongoing' => 'Zoeken naar "%1$s"',
	'UI:Search:Enlarge' => 'Vergroot de zoekopdracht',
	'UI:FullTextSearchTitle_Text' => 'Resultaten voor "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d object(en) van klasse %2$s gevonden.',
	'UI:Search:NoObjectFound' => 'Geen object gevonden.',
	'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s modificatie',
	'UI:ModificationTitle_Class_Object' => 'Modificatie van %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => 'iTop - Kloon %1$s - %2$s modificatie',
	'UI:CloneTitle_Class_Object' => 'Kloon van %1$s: <span class=\"hilite\">%2$s</span>',
	'UI:CreationPageTitle_Class' => 'iTop - Nieuwe %1$s aangemaakt',
	'UI:CreationTitle_Class' => '%1$s aanmaken',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Selecteer het type %1$s dat moet worden aangemaakt:',
	'UI:Class_Object_NotUpdated' => 'Geen verandering waargenomen, %1$s (%2$s) is <strong>not</strong> gemodificeerd.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) geupdatet.',
	'UI:BulkDeletePageTitle' => 'iTop - Grootschalig verwijderen',
	'UI:BulkDeleteTitle' => 'Selecteer de objecten die u wilt verwijderen:',
	'UI:PageTitle:ObjectCreated' => 'iTop Object Aangemaakt.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s aangemaakt.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Bezig met het toepassen van %1$s op object: %2$s in state %3$s tot target state: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Het object kon niet geschreven worden: %1$s',
	'UI:PageTitle:FatalError' => 'iTop - Fatale Fout',
	'UI:SystemIntrusion' => 'Toegang geweigerd. U hebt een actie aangevraagd waarvoor u niet gemachtigd bent.',
	'UI:FatalErrorMessage' => 'Fatale error, iTop kan niet doorgaan.',
	'UI:Error_Details' => 'Error: %1$s.',

	'UI:PageTitle:ClassProjections'	=> 'iTop gebruikersmanagement - klasse projecties',
	'UI:PageTitle:ProfileProjections' => 'iTop gebuikersmanagement - profiel projecties',
	'UI:UserManagement:Class' => 'Klasse',
	'UI:UserManagement:Class+' => 'Klasse van objecten',
	'UI:UserManagement:ProjectedObject' => 'Object',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
	'UI:UserManagement:AnyObject' => '* elk *',
	'UI:UserManagement:User' => 'Gebruiker',
	'UI:UserManagement:User+' => 'Gebruiker bezig met de projectie',
	'UI:UserManagement:Profile' => 'Profiel',
	'UI:UserManagement:Profile+' => 'Profiel waarin de projectie is gespecificeerd',
	'UI:UserManagement:Action:Read' => 'Lees',
	'UI:UserManagement:Action:Read+' => 'Lees/display objecten',
	'UI:UserManagement:Action:Modify' => 'Pas aan',
	'UI:UserManagement:Action:Modify+' => 'Maak de objecten aan en edit ze',
	'UI:UserManagement:Action:Delete' => 'Verwijder',
	'UI:UserManagement:Action:Delete+' => 'Verwijder objecten',
	'UI:UserManagement:Action:BulkRead' => 'Grootschalig lezen (Exporteer)',
	'UI:UserManagement:Action:BulkRead+' => 'List de objecten of exporteer ze grootschalig',
	'UI:UserManagement:Action:BulkModify' => 'Grootschalig aanpassen',
	'UI:UserManagement:Action:BulkModify+' => 'Verander grootschalig/maak grootschalig aan (CSV import)',
	'UI:UserManagement:Action:BulkDelete' => 'Grootschalig verwijderen',
	'UI:UserManagement:Action:BulkDelete+' => 'Verwijder objecten grootschalig',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Toegestane (compound) acties',
	'UI:UserManagement:Action' => 'Actie',
	'UI:UserManagement:Action+' => 'Actie uitgevoerd door de gebruiker',
	'UI:UserManagement:TitleActions' => 'Acties',
	'UI:UserManagement:Permission' => 'Toestemming',
	'UI:UserManagement:Permission+' => 'De autorisaties van de gebruiker',
	'UI:UserManagement:Attributes' => 'Attributen',
	'UI:UserManagement:ActionAllowed:Yes' => 'Ja',
	'UI:UserManagement:ActionAllowed:No' => 'Nee',
	'UI:UserManagement:AdminProfile+' => 'Administrators hebben volledige lees/schrijf autorisatie in de database.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Er is geen levenscyclus gedefineerd voor deze klasse',
	'UI:UserManagement:GrantMatrix' => 'Grant Matrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Link tussen %1$s en %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Link tussen %1$s en %2$s',
	
	'Menu:AdminTools' => 'Admin tools',
	'Menu:AdminTools+' => 'Administratie tools',
	'Menu:AdminTools?' => 'Tools die alleen toegankelijk zijn voor gebruikers met een administratorprofiel',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Changes Overzicht',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes aan de hand van type',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes aan de hand van type status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes aan de hand van werkgroep',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Nog niet toegewezen Changes',

	'UI:ConfigurationManagementMenu' => 'Configuratie Management',
	'UI:ConfigurationManagementMenu+' => 'Configuratie Management',
	'UI:ConfigurationManagementMenu:Title' => 'Overzicht van de Infrastructuur',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objecten van de infrastructuur aan de hand van type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objecten van de infrastructuur aan de hand van status',

'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard voor Configuratie Management',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuratie Items aan de hand van status',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuration Items aan de hand van type',

'UI:RequestMgmtMenuOverview:Title' => 'Dashboard voor Request Management',
'UI-RequestManagementOverview-RequestByService' => 'Gebruikersaanvragen aan de hand van dienst',
'UI-RequestManagementOverview-RequestByPriority' => 'Gebruikersaanvragen aan de hand van prioriteit',
'UI-RequestManagementOverview-RequestUnassigned' => 'Nog niet toegewezen gebruikersaanvragen',

'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard voor Incident Management',
'UI-IncidentManagementOverview-IncidentByService' => 'Incidenten aan de hand van dienst',
'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenten aan de hand van prioriteit',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'Nog niet toegewezen incidenten',

'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard voor Change Management',
'UI-ChangeManagementOverview-ChangeByType' => 'Changes aan de hand van type',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'Nog niet toegewezen Changes',
'UI-ChangeManagementOverview-ChangeWithOutage' => 'Outages door changes',

'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard voor Service Management',
'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Klantencontracten die binnen 30 dagen vernieuwd moeten worden',
'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Providercontracten die binnen 30 dagen vernieuwd moeten worden',

	'UI:ContactsMenu' => 'Contacten',
	'UI:ContactsMenu+' => 'Contacten',
	'UI:ContactsMenu:Title' => 'Overzicht van contacten',
	'UI-ContactsMenu-ContactsByLocation' => 'Contacten aan de hand van location',
	'UI-ContactsMenu-ContactsByType' => 'Contacten aan de hand van type',
	'UI-ContactsMenu-ContactsByStatus' => 'Contacten aan de hand van status',

	'Menu:DataModelMenu' => 'Data Model',
	'Menu:DataModelMenu+' => 'Overzicht van het Data Model',
	
	'Menu:ExportMenu' => 'Exporteer',
	'Menu:ExportMenu+' => 'Exporteer de resultaten van elke query in HTML, CSV or XML',
	
	'Menu:NotificationsMenu' => 'Notificaties',
	'Menu:NotificationsMenu+' => 'Configuratie van de Notificaties',
	'UI:NotificationsMenu:Title' => 'Configuratie van het <span class="hilite">Notifications</span>',
	'UI:NotificationsMenu:Help' => 'Help',
	'UI:NotificationsMenu:HelpContent' => '<p>In iTop zijn de notificaties volledig aan te passen. Ze zijn gebaseerd op twee sets van objecten: <i>triggers and actions</i>.</p>
<p><i><b>Triggers</b></i> defineren wanneer een notificatie wordt verzonden. Er zijn 5 types triggers, die 3 verschillende fases in de levenscyclus van een object beslaan:
<ol>
	<li>de "on object creation" triggers worden uitgevoerd wanneer een object van een specifieke klasse is aangemaakt</li>
	<li>de "on entering a state" triggers worden uitgevoerd voordat een object van een bepaalde klasse een gespecificeerde state ingaat (komend van een andere state)</li>
	<li>de "on leaving a state" triggers worden uitgevoerd wanneer een object van een bepaalde klasse een gespecificeerde state verlaat</li>
	<li>de "on threshold" triggers worden uitgevoerd wanneer een threshold voor TTR of TTO is bereikt</li>
	<li>de "on portal update" triggers worden uitgevoerd wanneer een ticket is geupdatet vanuit het portal</li>
</ol>
</p>
<p>
<i><b>Actions</b></i> defileren de acties die worden ondernomen wanneer de triggers worden uitgevoerd. Op dit moment is er slechts een actie, bestaand uit het verzenden van een e-mail.
Zulke acties defileren ook de template die moet worden gebruikt voor het versturen van de e-mail, net als andere parameters in het bericht, zoals de ontvangers, de prioriteit, etc. </p>
<p>Een speciale pagina: <a href="../setup/email.test.php" target="_blank">email.test.php</a> is beschikbaar voor het testen en de probleemoplossing van uw  PHP mail configuratie.</p>
<p>Om te worden uitgevoerd moeten uw acties gekoppeld zijn aan triggers.
Indien gekoppeld aan een Trigger, wordt aan elke actie een "orde" nummer gegeven, dat specificeert in welke volgorde de acties moeten worden uitgevoerd.</p>',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Beschikbare triggers',
	'UI:NotificationsMenu:OnCreate' => 'Wanneer een object is aangemaakt',
	'UI:NotificationsMenu:OnStateEnter' => 'Wanneer een object een bepaalde state ingaat',
	'UI:NotificationsMenu:OnStateLeave' => 'Wanneer een object een bepaalde state verlaat',
	'UI:NotificationsMenu:Actions' => 'Acties',
	'UI:NotificationsMenu:AvailableActions' => 'Beschikbare acties',
	
	'Menu:AuditCategories' => 'Audit Categorieën',
	'Menu:AuditCategories+' => 'Audit Categorieën',
	'Menu:Notifications:Title' => 'Audit Categorieën',
	
	'Menu:RunQueriesMenu' => 'Queries uitvoeren',
	'Menu:RunQueriesMenu+' => 'Voer een query uit',
	
	'Menu:QueryMenu' => 'Query phrasebook',
	'Menu:QueryMenu+' => 'Query phrasebook',
	
	'Menu:UniversalSearchMenu' => 'Universele Zoekopdracht',
	'Menu:UniversalSearchMenu+' => 'Zoek naar alles...',
	
	'Menu:ApplicationLogMenu' => 'Log de l\'application',
	'Menu:ApplicationLogMenu+' => 'Log de l\'application',
	'Menu:ApplicationLogMenu:Title' => 'Log de l\'application',

	'Menu:UserManagementMenu' => 'Gebruikersmanagement',
	'Menu:UserManagementMenu+' => 'Gebruikersmanagement',

	'Menu:ProfilesMenu' => 'Profielen',
	'Menu:ProfilesMenu+' => 'Profielen',
	'Menu:ProfilesMenu:Title' => 'Profielen',

	'Menu:UserAccountsMenu' => 'Gebruikersaccounts',
	'Menu:UserAccountsMenu+' => 'Gebruikersaccounts',
	'Menu:UserAccountsMenu:Title' => 'Gebruikersaccounts',	

	'UI:iTopVersion:Short' => 'iTop versie %1$s',
	'UI:iTopVersion:Long' => 'iTop versie %1$s-%2$s built on %3$s',
	'UI:PropertiesTab' => 'Eigenschappen',

	'UI:OpenDocumentInNewWindow_' => 'Open dit document in een nieuwe window: %1$s',
	'UI:DownloadDocument_' => 'Download dit document: %1$s',
	'UI:Document:NoPreview' => 'Er is geen preview beschikbaar voor dit type document',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Gemist op %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',		
	'UI:Deadline_Minutes' => '%1$d min',			
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Help',
	'UI:PasswordConfirm' => '(Bevestig)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Sla dit object op, voordat u meer %1$s objecten toevoegt.',
	'UI:DisplayThisMessageAtStartup' => 'Geef dit bericht bij het opstarten weer',
	'UI:RelationshipGraph' => 'Graphical view',
	'UI:RelationshipList' => 'List',
	'UI:OperationCancelled' => 'Operatie afgebroken',
	'UI:ElementsDisplayed' => 'Filtering',

	'Portal:Title' => 'iTop gebruikersportaal',
	'Portal:NoRequestMgmt' => 'Beste %1$s, u bent naar deze pagina doorverwezen omdat uw account is geconfigureerd met het profiel \'Portal user\'. Helaas is iTop niet geïnstalleerd met de feature \'Request Management\'. Neem alstublieft contact op met uw administrator.',
	'Portal:Refresh' => 'Herlaad',
	'Portal:Back' => 'Vorige',
	'Portal:WelcomeUserOrg' => 'Welkom %1$s, van %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details voor aanvraag',
	'Portal:ShowOngoing' => 'Laat lopende aanvragen zien',
	'Portal:ShowClosed' => 'Laat gesloten aanvragen zien',
	'Portal:CreateNewRequest' => 'Maak een nieuwe aanvraag aan',
	'Portal:ChangeMyPassword' => 'Verander mijn wachtwoord',
	'Portal:Disconnect' => 'Disconnect',
	'Portal:OpenRequests' => 'Mijn lopende aanvragen',
	'Portal:ClosedRequests'  => 'Mijn gesloten aanvragen',
	'Portal:ResolvedRequests'  => 'Mijn opgeloste aanvragen',
	'Portal:SelectService' => 'Selecteer een dienst van de catalogus:',
	'Portal:PleaseSelectOneService' => 'Selecteer alstublieft een dienst',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecteer een subcategorie voor de dienst %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Selecteer alstublieft een subcategorie',
	'Portal:DescriptionOfTheRequest' => 'Voeg een beschrijving voor uw aanvraag toe:',
	'Portal:TitleRequestDetailsFor_Request' => 'Details voor de aanvraag %1$s:',
	'Portal:NoOpenRequest' => 'Geen aanvragen in deze categorie',
	'Portal:NoClosedRequest' => 'Geen aanvragen in deze categorie',
	'Portal:Button:ReopenTicket' => 'Heropen deze ticket',
	'Portal:Button:CloseTicket' => 'Sluit deze ticket',
	'Portal:Button:UpdateRequest' => 'Update de aanvraag',
	'Portal:EnterYourCommentsOnTicket' => 'Voeg opmerkingen over het oplossen van deze ticket toe:',
	'Portal:ErrorNoContactForThisUser' => 'Error: de huidige gebruiker is niet geassocieerd met een persoon/contact. Neem alstublieft contact op met uw administrator.',
	'Portal:Attachments' => 'Bijlagen',
	'Portal:AddAttachment' => ' Voeg bijlage toe ',
	'Portal:RemoveAttachment' => ' Verwijder bijlage ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Bijlage #%1$d to %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Selecteer een template voor %1$s',
	'Enum:Undefined' => 'Ongedefineerd',	
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s Dagen %2$s Uren %3$s Minuten %4$s Seconden',
	'UI:ModifyAllPageTitle' => 'Bewerk alles',
	'UI:Modify_N_ObjectsOf_Class' => 'Bezig met het aanpassen van %1$d objecten van klasse %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Bezig met het aanpassen van %1$d objecten van klasse %2$s van de %3$d',
	'UI:Menu:ModifyAll' => 'Bewerk...',
	'UI:Button:ModifyAll' => 'Bewerk alles',
	'UI:Button:PreviewModifications' => 'Preview van de bewerkingen >>',
	'UI:ModifiedObject' => 'Object Bewerkt',
	'UI:BulkModifyStatus' => 'Operatie',
	'UI:BulkModifyStatus+' => 'Status van de operatie',
	'UI:BulkModifyErrors' => 'Errors (indien van toepassing)',
	'UI:BulkModifyErrors+' => 'Errors die de bewerking verhinderen',	
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Error',
	'UI:BulkModifyStatusModified' => 'Aangepast',
	'UI:BulkModifyStatusSkipped' => 'Overgeslagen',
	'UI:BulkModify_Count_DistinctValues' => '%1$d distinct values:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d keer',
	'UI:BulkModify:N_MoreValues' => '%1$d meer waarden...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Bezig met het plaatsen van het alleen-lezen veld: %1$s',
	'UI:FailedToApplyStimuli' => 'De actie is mislukt.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Bezig met het bewerken van %2$d objecten van klasse %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Typ uw text hier:',
	'UI:CaseLog:DateFormat' => 'Y-m-d H:i:s',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'InitiÃ«le waarde:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Het veld %1$s is niet beschrijfbaar omdat het onderdeel is van de data synchronisatie. Waarde niet opgegeven',
	'UI:ActionNotAllowed' => 'U hebt geen toestemming om deze actie op deze objecten uit te voeren.',
	'UI:BulkAction:NoObjectSelected' => 'Selecteer tenminste een object om deze actie uit te voeren',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Het veld %1$s is niets beschrijfbaar omdat het onderdeel is van de data synchronisatie. Waarde blijft onveranderd',
	'UI:Pagination:HeaderSelection' => 'Totaal: %1$s objecten (%2$s objecten geselecteerd).',
	'UI:Pagination:HeaderNoSelection' => 'Totaal: %1$s objecten.',
	'UI:Pagination:PageSize' => '%1$s objecten per pagina',
	'UI:Pagination:PagesLabel' => 'Paginas:',
	'UI:Pagination:All' => 'Alles',
	'UI:HierarchyOf_Class' => 'Hierarchie van %1$s',
	'UI:Preferences' => 'Voorkeuren...',
	'UI:FavoriteOrganizations' => 'Favoriete Organizaties',
	'UI:FavoriteOrganizations+' => 'Bekijk de organisaties die u wilt zijn in het drop-down menu voor een snelle toegang in de onderstaande lijst. '.
								   'Merk op dat dit geen security instelling is, objecten van elke organisatie zijn nog steed zichtbaar en toegankelijk door "All Organizations" te selecteren in de drop-down list.',
	'UI:FavoriteLanguage' => 'Taal van de Gebruikersinterface',
	'UI:Favorites:SelectYourLanguage' => 'Selecteer uw taal',
	'UI:FavoriteOtherSettings' => 'Overige instellingen',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Standaard lengte voor lijsten:  %1$s items per pagina',
	'UI:NavigateAwayConfirmationMessage' => 'Bewerkingen zullen worden genegeerd.',
	'UI:CancelConfirmationMessage' => 'U zult uw aanpassingen verliezen. Wilt u alsnog doorgaan?',
	'UI:AutoApplyConfirmationMessage' => 'Sommige veranderingen zijn nog niet doorgevoerd. Wilt u dat iTop deze meeneemt?',
	'UI:Create_Class_InState' => 'Maak %1$s aan in state: ',
	'UI:OrderByHint_Values' => 'Sorteervolgorde: %1$s',
	'UI:Menu:AddToDashboard' => 'Voeg toe aan Dashboard...',
	'UI:Button:Refresh' => 'Herlaad',

	'UI:ConfigureThisList' => 'Configureer deze Lijst...',
	'UI:ListConfigurationTitle' => 'Lijst Configuratie',
	'UI:ColumnsAndSortOrder' => 'Columns en sorteervolgorde:',
	'UI:UseDefaultSettings' => 'Gebruik de standaard instellingen',
	'UI:UseSpecificSettings' => 'Gebruik de volgende instellingen:',
	'UI:Display_X_ItemsPerPage' => 'Geef %1$s items per pagina weer',
	'UI:UseSavetheSettings' => 'Sla de instellingen op',
	'UI:OnlyForThisList' => 'Alleen voor deze lijst',
	'UI:ForAllLists' => 'Standaard voor alle lijsten',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Friendly Name)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Ga omhoog',
	'UI:Button:MoveDown' => 'Ga naar beneden',

	'UI:OQL:UnknownClassAndFix' => 'Onbekende klasse "%1$s". U zou "%2$s" kunnen proberen.',
	'UI:OQL:UnknownClassNoFix' => 'Onbekende klasse "%1$s"',

	'UI:Dashboard:Edit' => 'Bewerk deze pagina...',
	'UI:Dashboard:Revert' => 'Ga terug naar de originele versie...',
	'UI:Dashboard:RevertConfirm' => 'Alle bewerkingen die zijn gemaakt aan de originele versie zullen verloren gaan. Bevestig dat u wilt doorgaan.',
	'UI:ExportDashBoard' => 'Exporteer naar een bestand',
	'UI:ImportDashBoard' => 'Importeer vanuit een bestand',
	'UI:ImportDashboardTitle' => 'Importeer vanuit een bestand',
	'UI:ImportDashboardText' => 'Selecteer een bestand van het dashboard om te importeren:',


	'UI:DashletCreation:Title' => 'Maak een nieuwe Dashlet aan',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Dashlet Type',
	'UI:DashletCreation:EditNow' => 'Bewerk het Dashboard',

	'UI:DashboardEdit:Title' => 'Dashboard Editor',
	'UI:DashboardEdit:DashboardTitle' => 'Titel',
	'UI:DashboardEdit:AutoReload' => 'Automatisch verversen',
	'UI:DashboardEdit:AutoReloadSec' => 'Interval voor het automatisch verversen (seconden)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Het toegestane minimun is 5 seconden',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Dashboard Eigenschappen',
	'UI:DashboardEdit:Dashlets' => 'Beschikbare Dashlets',	
	'UI:DashboardEdit:DashletProperties' => 'Dashlet Eigenschappen',	

	'UI:Form:Property' => 'Eigenschap',
	'UI:Form:Value' => 'Waarde',

	'UI:DashletPlainText:Label' => 'Text',
	'UI:DashletPlainText:Description' => 'Gewone text (niet geformatteerd)',
	'UI:DashletPlainText:Prop-Text' => 'Text',
	'UI:DashletPlainText:Prop-Text:Default' => 'Voeg hier alstublieft wat text toe...',

	'UI:DashletObjectList:Label' => 'Object lijst',
	'UI:DashletObjectList:Description' => 'Object lijst dashlet',
	'UI:DashletObjectList:Prop-Title' => 'Titel',
	'UI:DashletObjectList:Prop-Query' => 'Query',
	'UI:DashletObjectList:Prop-Menu' => 'Menu',

	'UI:DashletGroupBy:Prop-Title' => 'Titel',
	'UI:DashletGroupBy:Prop-Query' => 'Query',
	'UI:DashletGroupBy:Prop-Style' => 'Stijl',
	'UI:DashletGroupBy:Prop-GroupBy' => 'Groepeer aan de hand van...',
	'UI:DashletGroupBy:Prop-GroupBy:Hour' => 'Uur %1$s (0-23)',
	'UI:DashletGroupBy:Prop-GroupBy:Month' => 'Maand %1$s (1 - 12)',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfWeek' => 'Dag van de week voor %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:DayOfMonth' => 'Dag van de maand voor %1$s',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Hour' => '%1$s (uur)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-Month' => '%1$s (maand)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfWeek' => '%1$s (dag van de week)',
	'UI:DashletGroupBy:Prop-GroupBy:Select-DayOfMonth' => '%1$s (dag van de maand)',
	'UI:DashletGroupBy:MissingGroupBy' => 'Selecteer alstublieft het veld waarin de objecten gegroepeerd moeten worden',

	'UI:DashletGroupByPie:Label' => 'Cirkeldiagram',
	'UI:DashletGroupByPie:Description' => 'Cirkeldiagram',
	'UI:DashletGroupByBars:Label' => 'Staafdiagram',
	'UI:DashletGroupByBars:Description' => 'Staafdiagram',
	'UI:DashletGroupByTable:Label' => 'Groepeer aan de hand van (tabel)',
	'UI:DashletGroupByTable:Description' => 'Lijst (Gegroepeerd aan de hand van een veld)',

	'UI:DashletHeaderStatic:Label' => 'Kopje',
	'UI:DashletHeaderStatic:Description' => 'Geeft een horizontale separator weer',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titel',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacten',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icoon',

	'UI:DashletHeaderDynamic:Label' => 'Koje met gegevens',
	'UI:DashletHeaderDynamic:Description' => 'Kopje met stats (gegroepeerd aan de hand van...)',
	'UI:DashletHeaderDynamic:Prop-Title' => 'Titel',
	'UI:DashletHeaderDynamic:Prop-Title:Default' => 'Contacten',
	'UI:DashletHeaderDynamic:Prop-Icon' => 'Icoon',
	'UI:DashletHeaderDynamic:Prop-Subtitle' => 'Subtitel',
	'UI:DashletHeaderDynamic:Prop-Subtitle:Default' => 'Contacten',
	'UI:DashletHeaderDynamic:Prop-Query' => 'Query',
	'UI:DashletHeaderDynamic:Prop-GroupBy' => 'Gegroepeerd aan de hand van',
	'UI:DashletHeaderDynamic:Prop-Values' => 'Waarden',

	'UI:DashletBadge:Label' => 'Badge',
	'UI:DashletBadge:Description' => 'Object Icoon met nieuw/zoek naar',
	'UI:DashletBadge:Prop-Class' => 'Klasse',

	'DayOfWeek-Sunday' => 'Zondag',
	'DayOfWeek-Monday' => 'Maandag',
	'DayOfWeek-Tuesday' => 'Dinsdag',
	'DayOfWeek-Wednesday' => 'Woensdag',
	'DayOfWeek-Thursday' => 'Donderdag',
	'DayOfWeek-Friday' => 'Vrijdag',
	'DayOfWeek-Saturday' => 'Zaterdag',
	'Month-01' => 'Januari',
	'Month-02' => 'Februari',
	'Month-03' => 'Maart',
	'Month-04' => 'April',
	'Month-05' => 'Mei',
	'Month-06' => 'Juni',
	'Month-07' => 'Juli',
	'Month-08' => 'Augustus',
	'Month-09' => 'September',
	'Month-10' => 'Oktober',
	'Month-11' => 'November',
	'Month-12' => 'December',

	'UI:Menu:ShortcutList' => 'Maak een Snelkoppeling aan...',
	'UI:ShortcutRenameDlg:Title' => 'Hernoem de snelkoppeling',
	'UI:ShortcutListDlg:Title' => 'Maak een snelkoppeling voor de lijst aan',
	'UI:ShortcutDelete:Confirm' => 'Bevestig dat u de snelkoppeling(en) wilt verwijderen.',
	'Menu:MyShortcuts' => 'Mijn Snelkoppeling',
	'Class:Shortcut' => 'Snelkoppeling',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Naam',
	'Class:Shortcut/Attribute:name+' => 'Label gebruikt in het menu en in de titel van de pagina',
	'Class:ShortcutOQL' => 'Zoekresultaat snelkoppeling',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Query',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL die de lijst van objecten om naar te zoeken defineerd',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatisch verversen',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Onbruikbaar',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Custom rate',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Interval van het automatisch verversen (seconden)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec+' => 'Het toegestane minimum is 5 seconden',

	'UI:FillAllMandatoryFields' => 'Vul alstublieft de verplichte velden in.',
	
	'UI:CSVImportConfirmTitle' => 'Bevestig alstublieft de actie',
	'UI:CSVImportConfirmMessage' => 'Weet u zeker dat u dit wilt doen?',
	'UI:CSVImportError_items' => 'Errors: %1$d',
	'UI:CSVImportCreated_items' => 'Aangemaakt: %1$d',
	'UI:CSVImportModified_items' => 'Bewerkt: %1$d',
	'UI:CSVImportUnchanged_items' => 'Onveranderd: %1$d',

	'UI:Button:Remove' => 'Verwijder',
	'UI:AddAnExisting_Class' => 'Voeg objecten van type %1$s toe...',
	'UI:SelectionOf_Class' => 'Selectie van objecten van type %1$s',

	'UI:AboutBox' => 'Over iTop...',
	'UI:About:Title' => 'Over iTop',
	'UI:About:DataModel' => 'Data model',
	'UI:About:Support' => 'Support informatie',
	'UI:About:Licenses' => 'Licenties',
	'UI:About:Modules' => 'Geïnstalleerde modules',
));
?>
