<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * @author Hipska (2018)
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2019)
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
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
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:AuditCategory' => 'Auditcategorie',
	'Class:AuditCategory+' => 'Een onderdeel van de gehele audit',
	'Class:AuditCategory/Attribute:name' => 'Naam categorie',
	'Class:AuditCategory/Attribute:name+' => 'Afkorting van de naam van deze categorie',
	'Class:AuditCategory/Attribute:description' => 'Audit categorie beschrijving',
	'Class:AuditCategory/Attribute:description+' => 'Uitgebreide beschrijving van deze Audit categorie',
	'Class:AuditCategory/Attribute:definition_set' => 'Definitieset',
	'Class:AuditCategory/Attribute:definition_set+' => 'OQL-expressie die de set van objecten naar audit definieert',
	'Class:AuditCategory/Attribute:rules_list' => 'Auditregels',
	'Class:AuditCategory/Attribute:rules_list+' => 'Auditregels voor deze categorie',
));

//
// Class: AuditRule
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:AuditRule' => 'Auditregel',
	'Class:AuditRule+' => 'Een regel voor het controleren van een bepaalde Auditcategorie',
	'Class:AuditRule/Attribute:name' => 'Naam regel',
	'Class:AuditRule/Attribute:name+' => 'Naam van de regel',
	'Class:AuditRule/Attribute:description' => 'Beschrijving',
	'Class:AuditRule/Attribute:description+' => 'Uitgebreide beschrijving van deze Auditregel',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tagklasse',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Objectklasse',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Veldcode',
	'Class:AuditRule/Attribute:query' => 'Query om uit te voeren',
	'Class:AuditRule/Attribute:query+' => 'De OQL-expressie voor het uitvoeren',
	'Class:AuditRule/Attribute:valid_flag' => 'Geldige objecten?',
	'Class:AuditRule/Attribute:valid_flag+' => 'Waar als de regel de geldige objecten vindt, anders onwaar',
	'Class:AuditRule/Attribute:valid_flag/Value:true' => 'Waar',
	'Class:AuditRule/Attribute:valid_flag/Value:true+' => 'Waar',
	'Class:AuditRule/Attribute:valid_flag/Value:false' => 'Onwaar',
	'Class:AuditRule/Attribute:valid_flag/Value:false+' => 'Onwaar',
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
	'Class:Query+' => 'Een query is een definie voor een dataset die op een dynamische manier wordt samengesteld',
	'Class:Query/Attribute:name' => 'Naam',
	'Class:Query/Attribute:name+' => 'Identificeert de query',
	'Class:Query/Attribute:description' => 'Beschrijving',
	'Class:Query/Attribute:description+' => 'Uitgebreide beschrijving voor de query (doel, gebruik, enz.)',
	'Class:QueryOQL/Attribute:fields' => 'Velden',
	'Class:QueryOQL/Attribute:fields+' => 'Kommagescheiden lijst van attributen (of alias.attribuut) om te exporteren',
	'Class:QueryOQL' => 'OQL-query',
	'Class:QueryOQL+' => 'Een query gebaseerd op de Object Query Language',
	'Class:QueryOQL/Attribute:oql' => 'Expressie',
	'Class:QueryOQL/Attribute:oql+' => 'OQL-expressie',
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
	'Class:User+' => 'Login voor gebruiker',
	'Class:User/Attribute:finalclass' => 'Accounttype',
	'Class:User/Attribute:finalclass+' => '',
	'Class:User/Attribute:contactid' => 'Contact',
	'Class:User/Attribute:contactid+' => 'Contactpersoon',
	'Class:User/Attribute:org_id' => 'Organisatie',
	'Class:User/Attribute:org_id+' => 'Organisatie van de gerelateerde persoon',
	'Class:User/Attribute:last_name' => 'Achternaam',
	'Class:User/Attribute:last_name+' => 'Naam van de overeenkomende persoon',
	'Class:User/Attribute:first_name' => 'Voornaam',
	'Class:User/Attribute:first_name+' => 'Voornaam van de overeenkomende persoon',
	'Class:User/Attribute:email' => 'E-mailadres',
	'Class:User/Attribute:email+' => 'E-mailadres van de overeenkomende persoon',
	'Class:User/Attribute:login' => 'Login',
	'Class:User/Attribute:login+' => 'Login (gebruikersnaam) waarmee deze persoon zich kan aanmelden',
	'Class:User/Attribute:language' => 'Taal',
	'Class:User/Attribute:language+' => 'Taal van de gebruiker',
	'Class:User/Attribute:language/Value:EN US' => 'Engels',
	'Class:User/Attribute:language/Value:EN US+' => 'Engels (V.S.)',
	'Class:User/Attribute:language/Value:FR FR' => 'Frans',
	'Class:User/Attribute:language/Value:FR FR+' => 'Frans (Frankrijk)',
	'Class:User/Attribute:profile_list' => 'Profielen',
	'Class:User/Attribute:profile_list+' => 'Rollen waarmee rechten verleend zijn aan deze account.',
	'Class:User/Attribute:allowed_org_list' => 'Mijn organisaties',
	'Class:User/Attribute:allowed_org_list+' => 'De eindgebruiker heeft toestemming om data te bekijken van de gerelateerde organisaties. Als er geen organisatie is opgegeven, heeft de persoon toegang tot data van alle organisaties.',
	'Class:User/Attribute:status' => 'Status',
	'Class:User/Attribute:status+' => 'De gebruikersaccount kan in- of uitgeschakeld zijn.',
	'Class:User/Attribute:status/Value:enabled' => 'Ingeschakeld',
	'Class:User/Attribute:status/Value:disabled' => 'Uitgeschakeld',

	'Class:User/Error:LoginMustBeUnique' => 'Login moet uniek zijn - "%1s" is al in gebruik',
	'Class:User/Error:AtLeastOneProfileIsNeeded' => 'Minstens één profiel moet toegewezen zijn aan deze gebruiker',
	'Class:User/Error:AtLeastOneOrganizationIsNeeded' => 'Minstens één organisatie moet toegewezen zijn aan deze gebruiker',
	'Class:User/Error:OrganizationNotAllowed' => 'Organisatie is niet toegestaan.',
	'Class:User/Error:UserOrganizationNotAllowed' => 'De gebruikersaccount behoort niet tot de organisaties waar je zelf rechten voor hebt.',
	'Class:User/Error:PersonIsMandatory' => 'De persoon moet ingevuld zijn.',
	'Class:UserInternal' => 'Interne gebruiker',
	'Class:UserInternal+' => 'Gebruiker gedefinieerd in iTop',
));

//
// Class: URP_Profiles
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_Profiles' => 'Profiel',
	'Class:URP_Profiles+' => 'Gebruikersprofiel',
	'Class:URP_Profiles/Attribute:name' => 'Naam',
	'Class:URP_Profiles/Attribute:name+' => 'Naam van dit gebruikersprofiel',
	'Class:URP_Profiles/Attribute:description' => 'Beschrijving',
	'Class:URP_Profiles/Attribute:description+' => 'Beschrijving van dit profiel',
	'Class:URP_Profiles/Attribute:user_list' => 'Gebruikers',
	'Class:URP_Profiles/Attribute:user_list+' => 'Gebruikers met deze rol',
));

//
// Class: URP_Dimensions
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_Dimensions' => 'Dimensie',
	'Class:URP_Dimensions+' => 'Dimensie van de applicatie (definieert silo\'s)',
	'Class:URP_Dimensions/Attribute:name' => 'Naam',
	'Class:URP_Dimensions/Attribute:name+' => 'Naam van deze dimensie',
	'Class:URP_Dimensions/Attribute:description' => 'Beschrijving',
	'Class:URP_Dimensions/Attribute:description+' => 'Beschrijving van deze dimensie',
	'Class:URP_Dimensions/Attribute:type' => 'Type',
	'Class:URP_Dimensions/Attribute:type+' => 'Klassenaam of data type (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_UserProfile' => 'Gebruiker / Profiel',
	'Class:URP_UserProfile+' => 'Koppeling tussen gebruikers en profielen',
	'Class:URP_UserProfile/Attribute:userid' => 'Gebruiker',
	'Class:URP_UserProfile/Attribute:userid+' => 'De gebruiker gekoppeld aan dit profiel',
	'Class:URP_UserProfile/Attribute:userlogin' => 'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'De login (gebruikersnaam) van de gebruiker',
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
	'Class:URP_UserOrg' => 'Gebruiker / Organisatie',
	'Class:URP_UserOrg+' => 'Koppeling tussen gebruikers en organisaties',
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
	'Class:URP_ProfileProjection/Attribute:value' => 'Waarde-expressie',
	'Class:URP_ProfileProjection/Attribute:value+' => 'OQL-expressie (gebruikt $user) | constant |  | +attribute code',
	'Class:URP_ProfileProjection/Attribute:attribute' => 'Attribuut',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Code van doelattribuut (optioneel)',
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
	'Class:URP_ClassProjection/Attribute:class+' => 'Doelklasse',
	'Class:URP_ClassProjection/Attribute:value' => 'Waarde-expressie',
	'Class:URP_ClassProjection/Attribute:value+' => 'OQL-expressie (gebruikt $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => 'Attribuut',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'Code van doelattribuut (optioneel)',
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
	'Class:URP_ActionGrant/Attribute:class+' => 'Doelklasse',
	'Class:URP_ActionGrant/Attribute:permission' => 'Toestemming',
	'Class:URP_ActionGrant/Attribute:permission+' => 'Is dit toegestaan?',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'Nee',
	'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'Nee',
	'Class:URP_ActionGrant/Attribute:action' => 'Actie',
	'Class:URP_ActionGrant/Attribute:action+' => 'Actie om uit te voeren op een bepaalde klasse',
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
	'Class:URP_StimulusGrant/Attribute:class+' => 'Doelklasse',
	'Class:URP_StimulusGrant/Attribute:permission' => 'Toestemming',
	'Class:URP_StimulusGrant/Attribute:permission+' => 'Is dit toegestaan',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'Ja',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'Nee',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'Nee',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'Code van stimulus',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:URP_AttributeGrant' => 'attribute_permission',
	'Class:URP_AttributeGrant+' => 'Toestemming op het niveau van de attributen',
	'Class:URP_AttributeGrant/Attribute:actiongrantid' => 'Actie verleen',
	'Class:URP_AttributeGrant/Attribute:actiongrantid+' => 'Actie verleen',
	'Class:URP_AttributeGrant/Attribute:attcode' => 'Attribuut',
	'Class:URP_AttributeGrant/Attribute:attcode+' => 'Code van attribuut',
));

//
// Class: UserDashboard
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:UserDashboard' => 'Gebruikerdashboard',
	'Class:UserDashboard+' => '',
	'Class:UserDashboard/Attribute:user_id' => 'Gebruiker',
	'Class:UserDashboard/Attribute:user_id+' => '',
	'Class:UserDashboard/Attribute:menu_code' => 'Code menu',
	'Class:UserDashboard/Attribute:menu_code+' => '',
	'Class:UserDashboard/Attribute:contents' => 'Inhoud',
	'Class:UserDashboard/Attribute:contents+' => '',
));

//
// Expression to Natural language
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'j',
));


//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'BooleanLabel:yes' => 'Ja',
	'BooleanLabel:no' => 'Nee',
	'UI:Login:Title' => 'Aanmelden in '.ITOP_APPLICATION_SHORT,
	'Menu:WelcomeMenu' => 'Welkom', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenu+' => 'Welkom in '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage' => 'Welkom', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:WelcomeMenuPage+' => 'Welkom in '.ITOP_APPLICATION_SHORT, // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:WelcomeMenu:Title' => 'Welkom in '.ITOP_APPLICATION_SHORT,

	'UI:WelcomeMenu:LeftBlock' => '<p>'.ITOP_APPLICATION_SHORT.' is een compleet en open source portaal voor IT-operaties.</p>
<ul>Op maat van jouw IT-omgeving:
<li>Complete CMDB (Configuration Management Database) voor het documenteren en beheren van de IT-inventaris.</li>
<li>Incident Management-module voor het vinden van en communiceren over alle problemen die optreden .</li>
<li>Change Management-module voor het plannen en opvolgen van de veranderingen.</li>
<li>Database met gekende problemen om het oplossen van incidenten te versnellen.</li>
<li>Storingsmodule voor het documenteren van alle geplande storingen en voor het informeren van de juiste contacten.</li>
<li>Dashboards om snel een overzicht te krijgen.</li>
</ul>
<p>Alle modules kunnen volledig onafhankelijk van elkaar worden opgezet, stap voor stap.</p>',

	'UI:WelcomeMenu:RightBlock' => '<p>'.ITOP_APPLICATION_SHORT.' is gericht op serviceproviders. Het zorgt ervoor dat IT-engineers gemakkelijk meerdere klanten of organisaties kunnen beheren.
<ul>'.ITOP_APPLICATION_SHORT.' zorgt dankzij een uitgebreide set van bedrijfsprocessen voor een reeks voordelen:
<li>De efficientië van het IT-management versterkt.</li> 
<li>De prestaties van IT-operaties verbetert.</li> 
<li>De klanttevredenheid verhoogt en leidinggevenden inzicht biedt in hun bedrijfsperformantie.</li>
</ul>
</p>
<p>'.ITOP_APPLICATION_SHORT.' is klaar om geïntegreerd te worden met jouw huidige infrastructuur rond IT-management.</p>
<p>
<ul>De adoptie van dit IT-operationele portaal zal je helpen met:
<li>Het beter beheren van een steeds complexere IT-omgeving.</li>
<li>Het implementeren van ITIL-processen op jouw eigen tempo.</li>
<li>Het beheren van het belangrijkste middel: documentatie.</li>
</ul>
</p>',
	'UI:WelcomeMenu:AllOpenRequests' => 'Open aanvragen: %1$d',
	'UI:WelcomeMenu:MyCalls' => 'Mijn aanvragen',
	'UI:WelcomeMenu:OpenIncidents' => 'Open incidenten: %1$d',
	'UI:WelcomeMenu:AllConfigItems' => 'Configuratie-items: %1$d',
	'UI:WelcomeMenu:MyIncidents' => 'Aan mij toegewezen incidenten',
	'UI:AllOrganizations' => ' Alle Organisaties ',
	'UI:YourSearch' => 'Jouw zoekopdracht',
	'UI:LoggedAsMessage' => 'Ingelogd als %1$s',
	'UI:LoggedAsMessage+Admin' => 'Ingelogd als %1$s (Beheerder)',
	'UI:Button:Logoff' => 'Log uit',
	'UI:Button:GlobalSearch' => 'Zoek',
	'UI:Button:Search' => ' Zoek ',
	'UI:Button:Query' => ' Query ',
	'UI:Button:Ok' => 'Ok',
	'UI:Button:Save' => 'Opslaan',
	'UI:Button:Cancel' => 'Annuleer',
	'UI:Button:Close' => 'Sluiten',
	'UI:Button:Apply' => 'Pas toe',
	'UI:Button:Back' => ' << Vorige ',
	'UI:Button:Restart' => ' |<< Herstarten ',
	'UI:Button:Next' => ' Volgende >> ',
	'UI:Button:Finish' => ' Afronden ',
	'UI:Button:DoImport' => ' Importeer!',
	'UI:Button:Done' => ' Klaar ',
	'UI:Button:SimulateImport' => ' Simuleer de Import ',
	'UI:Button:Test' => 'Test!',
	'UI:Button:Evaluate' => ' Evalueer ',
	'UI:Button:Evaluate:Title' => ' Evalueer (Ctrl+Enter)',
	'UI:Button:AddObject' => ' Voeg toe... ',
	'UI:Button:BrowseObjects' => ' Bladeren... ',
	'UI:Button:Add' => ' Voeg toe ',
	'UI:Button:AddToList' => ' << Voeg toe ',
	'UI:Button:RemoveFromList' => ' Verwijder >> ',
	'UI:Button:FilterList' => ' Filter... ',
	'UI:Button:Create' => ' Maak aan ',
	'UI:Button:Delete' => ' Verwijder ! ',
	'UI:Button:Rename' => ' Hernoem... ',
	'UI:Button:ChangePassword' => ' Verander wachtwoord ',
	'UI:Button:ResetPassword' => ' Stel wachtwoord opnieuw in ',
	'UI:Button:Insert' => 'Invoegen',
	'UI:Button:More' => 'Meer',
	'UI:Button:Less' => 'Minder',
	'UI:Button:Wait' => 'Please wait while updating fields~~',
	'UI:Treeview:CollapseAll' => 'Collapse All~~',
	'UI:Treeview:ExpandAll' => 'Expand All~~',

	'UI:SearchToggle' => 'Zoek',
	'UI:ClickToCreateNew' => 'Maak een %1$s aan',
	'UI:SearchFor_Class' => 'Zoek naar %1$s objecten',
	'UI:NoObjectToDisplay' => 'Geen object om weer te geven.',
	'UI:Error:SaveFailed' => 'Het object kan niet bewaard worden:',
	'UI:Error:MandatoryTemplateParameter_object_id' => 'Parameter "object_id" is verplicht als "link_attr" is opgegeven. Controleer de definitie van het weergavesjabloon.',
	'UI:Error:MandatoryTemplateParameter_target_attr' => 'Parameter "target_attr" is verplicht als "link_attr" is opgegeven. Controleer de definitie van het weergavesjabloon.',
	'UI:Error:MandatoryTemplateParameter_group_by' => 'Parameter "group_by" is verplicht. Controleer de definitie van het weergavesjabloon.',
	'UI:Error:InvalidGroupByFields' => 'Ongeldige lijst van velden waarop gegroepeerd moet worden: "%1$s".',
	'UI:Error:UnsupportedStyleOfBlock' => 'Fout: de stijl "%1$s" wordt niet ondersteund voor dit blok.',
	'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'Incorrecte linkdefinitie: de klasse %1$s om objecten te beheren werd niet gevonden als externe sleutel (key) in de klasse %2$s',
	'UI:Error:Object_Class_Id_NotFound' => 'Object: %1$s:%2$d niet gevonden',
	'UI:Error:WizardCircularReferenceInDependencies' => 'Fout: cirkelverwijzing in de afhankelijke variabelen tussen de velden. Controleer het datamodel.',
	'UI:Error:UploadedFileTooBig' => 'Het geüploade bestand is te groot. De maximale grootte is %1$s. Contacteer jouw '.ITOP_APPLICATION_SHORT.'-beheerder om deze limiet aan te passen. (Controleer de PHP-configuratie voor "upload_max_filesize" en "post_max_size" op de server).',
	'UI:Error:UploadedFileTruncated.' => 'Het geüploade bestand is ingekort!',
	'UI:Error:NoTmpDir' => 'De tijdelijke opslagruimte is niet gedefinieerd.',
	'UI:Error:CannotWriteToTmp_Dir' => 'Niet mogelijk om het tijdelijke bestand naar een tijdelijke map weg te schrijven. upload_tmp_dir = "%1$s".',
	'UI:Error:UploadStoppedByExtension_FileName' => 'Upload gestopt door bestandsextensie. (Oorspronkelijke bestandsnaam = "%1$s").',
	'UI:Error:UploadFailedUnknownCause_Code' => 'Uploaden van bestand mislukt, oorzaak onbekend. (Foutcode = "%1$s").',

	'UI:Error:1ParametersMissing' => 'Fout: de volgende parameter moet worden opgegeven voor deze actie: %1$s.',
	'UI:Error:2ParametersMissing' => 'Fout: de volgende parameters moeten worden opgegeven voor deze actie: %1$s and %2$s.',
	'UI:Error:3ParametersMissing' => 'Fout: de volgende parameters moeten worden opgegeven voor deze actie: %1$s, %2$s and %3$s.',
	'UI:Error:4ParametersMissing' => 'Fout: de volgende parameters moeten worden opgegeven voor deze actie: %1$s, %2$s, %3$s and %4$s.',
	'UI:Error:IncorrectOQLQuery_Message' => 'Fout: incorrecte OQL-query: %1$s',
	'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'Er trad een fout op tijdens het uitvoeren van deze query: %1$s',
	'UI:Error:ObjectAlreadyUpdated' => 'Fout: het object is al aangepast.',
	'UI:Error:ObjectCannotBeUpdated' => 'Fout: het object kan niet worden aangepast.',
	'UI:Error:ObjectsAlreadyDeleted' => 'Fout: objecten zijn al verwijderd',
	'UI:Error:BulkDeleteNotAllowedOn_Class' => 'Je bent niet gemachtigd om meerdere objecten in klasse "%1$s") in één keer te verwijderen.',
	'UI:Error:DeleteNotAllowedOn_Class' => 'Je bent niet gemachtigd om objecten van de klasse "%1$s" te verwijderen',
	'UI:Error:BulkModifyNotAllowedOn_Class' => 'Je bent niet gemachtigd om meerdere objecten (klasse %1$s) in één keer aan te passen',
	'UI:Error:ObjectAlreadyCloned' => 'Fout: het object is al gekloond!',
	'UI:Error:ObjectAlreadyCreated' => 'Fout: het object is al aangemaakt!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'Fout: ongeldige stimulus "%1$s" op object %2$s in fase "%3$s".',
	'UI:Error:InvalidDashboardFile' => 'Fout: ongeldig dashboard-bestand',
	'UI:Error:InvalidDashboard' => 'Fout: ongeldig dashboard',
	'UI:Error:MaintenanceMode' => 'Toepassing is momenteel in onderhoud',
	'UI:Error:MaintenanceTitle' => 'Onderhoud',

	'UI:GroupBy:Count' => 'Aantal',
	'UI:GroupBy:Count+' => 'Aantal objecten',
	'UI:CountOfObjects' => '%1$d objecten voldoen aan de criteria.',
	'UI_CountOfObjectsShort' => '%1$d objecten.',
	'UI:NoObject_Class_ToDisplay' => 'Geen %1$s om weer te geven',
	'UI:History:LastModified_On_By' => 'Laatst bewerkt op %1$s door %2$s.',
	'UI:HistoryTab' => 'Geschiedenis',
	'UI:NotificationsTab' => 'Meldingen',
	'UI:History:BulkImports' => 'Geschiedenis',
	'UI:History:BulkImports+' => 'Lijst van CSV-imports (nieuwste import eerst)',
	'UI:History:BulkImportDetails' => 'Veranderingen volgend op CSV-import uitgevoerd op %1$s (door %2$s)',
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
	'UI:Menu:Manage' => 'Beheer...',
	'UI:Menu:EMail' => 'E-mail',
	'UI:Menu:CSVExport' => 'CSV Export...',
	'UI:Menu:Modify' => 'Bewerk...',
	'UI:Menu:Delete' => 'Verwijder...',
	'UI:Menu:BulkDelete' => 'Verwijder...',
	'UI:UndefinedObject' => 'Ongedefinieerd',
	'UI:Document:OpenInNewWindow:Download' => 'Open in nieuw venster: %1$s, Download: %2$s',
	'UI:SplitDateTime-Date' => 'datum',
	'UI:SplitDateTime-Time' => 'tijd',
	'UI:TruncatedResults' => '%1$d objecten weergegeven buiten %2$d',
	'UI:DisplayAll' => 'Toon alles',
	'UI:CollapseList' => 'Inklappen',
	'UI:CountOfResults' => '%1$d object(en)',
	'UI:ChangesLogTitle' => 'Changes log (%1$d):',
	'UI:EmptyChangesLogTitle' => 'Changes log is leeg',
	'UI:SearchFor_Class_Objects' => 'Zoek naar %1$s Objecten',
	'UI:OQLQueryBuilderTitle' => 'OQL-query Builder',
	'UI:OQLQueryTab' => 'OQL-query',
	'UI:SimpleSearchTab' => 'Eenvoudig zoeken',
	'UI:Details+' => 'Details',
	'UI:SearchValue:Any' => '* Ieder *',
	'UI:SearchValue:Mixed' => '* gemengd *',
	'UI:SearchValue:NbSelected' => '# geselecteerd',
	'UI:SearchValue:CheckAll' => 'Vink alles aan',
	'UI:SearchValue:UncheckAll' => 'Vink alles uit',
	'UI:SelectOne' => '-- selecteer --',
	'UI:Login:Welcome' => 'Welkom in '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Ongeldige gebruikersnaam of wachtwoord, probeer opnieuw.',
	'UI:Login:IdentifyYourself' => 'Identificeer jezelf voordat je verder gaat',
	'UI:Login:UserNamePrompt' => 'Gebruikersnaam',
	'UI:Login:PasswordPrompt' => 'Wachtwoord',
	'UI:Login:ForgotPwd' => 'Wachtwoord vergeten?',
	'UI:Login:ForgotPwdForm' => 'Wachtwoord vergeten',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' kan je een e-mail sturen waarin de instructies voor het resetten van jouw account staan.',
	'UI:Login:ResetPassword' => 'Stuur nu!',
	'UI:Login:ResetPwdFailed' => 'E-mail sturen mislukt: %1$s',
	'UI:Login:SeparatorOr' => 'Of',

	'UI:ResetPwd-Error-WrongLogin' => '"%1$s" is geen geldige login',
	'UI:ResetPwd-Error-NotPossible' => 'Het wachtwoord van externe accounts kan niet gereset worden.',
	'UI:ResetPwd-Error-FixedPwd' => 'Deze account staat het resetten van het wachtwoord niet toe.',
	'UI:ResetPwd-Error-NoContact' => 'Deze account is niet gelinkt aan een persoon.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'Deze account is niet gelinkt aan een persoon waarvan een e-mailadres gekend is. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-Error-NoEmail' => 'Er ontbreekt een e-mailadres. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-Error-Send' => 'Er is een technisch probleem bij het verzenden van de e-mail. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-EmailSent' => 'Kijk in jouw mailbox (eventueel bij ongewenste mail) en volg de instructies...',
	'UI:ResetPwd-EmailSubject' => 'Reset jouw '.ITOP_APPLICATION_SHORT.'-wachtwoord',
	'UI:ResetPwd-EmailBody' => '<body><p>Je hebt een reset van jouw '.ITOP_APPLICATION_SHORT.'-wachtwoord aangevraagd.</p><p>Klik op deze link (eenmalig te gebruiken) om <a href="%1$s">een nieuw wachtwoord in te voeren</a></p>.',

	'UI:ResetPwd-Title' => 'Reset wachtwoord',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry. Jouw wachtwoord is al gereset, of je hebt al meerdere e-mails ontvangen. Zorg ervoor dat je de link in de laatst ontvangen e-mail gebruikt.',
	'UI:ResetPwd-Error-EnterPassword' => 'Voer het nieuwe wachtwoord voor de account "%1$s" in.',
	'UI:ResetPwd-Ready' => 'Het wachtwoord is veranderd',
	'UI:ResetPwd-Login' => 'Klik hier om in te loggen',

	'UI:Login:About' => ITOP_APPLICATION,
	'UI:Login:ChangeYourPassword' => 'Verander jouw wachtwoord',
	'UI:Login:OldPasswordPrompt' => 'Oud wachtwoord',
	'UI:Login:NewPasswordPrompt' => 'Nieuw wachtwoord',
	'UI:Login:RetypeNewPasswordPrompt' => 'Herhaal nieuwe wachtwoord',
	'UI:Login:IncorrectOldPassword' => 'Fout: het oude wachtwoord is incorrect',
	'UI:LogOffMenu' => 'Log uit',
	'UI:LogOff:ThankYou' => 'Bedankt voor het gebruiken van '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain' => 'Klik hier om in te loggen',
	'UI:ChangePwdMenu' => 'Verander wachtwoord',
	'UI:Login:PasswordChanged' => 'Wachtwoord met succes aangepast',
	'UI:AccessRO-All' => ITOP_APPLICATION.' is alleen-lezen',
	'UI:AccessRO-Users' => ITOP_APPLICATION.' is alleen-lezen voor eindgebruikers',
	'UI:ApplicationEnvironment' => 'Omgeving van de applicatie: %1$s',
	'UI:Login:RetypePwdDoesNotMatch' => 'Het nieuwe wachtwoord en de herhaling van het nieuwe wachtwoord komen niet overeen',
	'UI:Button:Login' => 'Ga naar '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted' => 'Geen toegang tot '.ITOP_APPLICATION_SHORT.'. Neem contact op met een '.ITOP_APPLICATION_SHORT.'-beheerder.',
	'UI:Login:Error:AccessAdmin' => 'Alleen toegankelijk voor mensen met beheerdersrechten. Neem contact op met een '.ITOP_APPLICATION_SHORT.'-beheerder',
	'UI:Login:Error:WrongOrganizationName' => 'Onbekende organisatie',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Meerdere contacten hebben hetzelfde e-mailadres',
	'UI:Login:Error:NoValidProfiles' => 'Geen geldig profiel opgegeven',
	'UI:CSVImport:MappingSelectOne' => '-- Selecteer --',
	'UI:CSVImport:MappingNotApplicable' => '-- Negeer dit veld --',
	'UI:CSVImport:NoData' => 'Lege dataset..., voeg data toe',
	'UI:Title:DataPreview' => 'Datavoorbeeld',
	'UI:CSVImport:ErrorOnlyOneColumn' => 'Fout: De data bevat slechts één kolom. Is het juiste scheidingsteken geselecteerd?',
	'UI:CSVImport:FieldName' => 'Veld %1$d',
	'UI:CSVImport:DataLine1' => 'Dataregel 1',
	'UI:CSVImport:DataLine2' => 'Dataregel 2',
	'UI:CSVImport:idField' => 'id (Primaire sleutel (key))',
	'UI:Title:BulkImport' => ITOP_APPLICATION_SHORT.' - Bulk import',
	'UI:Title:BulkImport+' => 'CSV Import Wizard',
	'UI:Title:BulkSynchro_nbItem_ofClass_class' => 'Synchronisatie van %1$d objecten van klasse "%2$s"',
	'UI:CSVImport:ClassesSelectOne' => '-- selecteer een --',
	'UI:CSVImport:ErrorExtendedAttCode' => 'Interne fout: "%1$s" is een incorrecte code omdat "%2$s" geen externe sleutel (key) van klasse "%3$s" is',
	'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d objecten(s) zullen onveranderd blijven.',
	'UI:CSVImport:ObjectsWillBeModified' => '%1$d objecten(s) zullen worden aangepast.',
	'UI:CSVImport:ObjectsWillBeAdded' => '%1$d objecten(s) zullen worden toegevoegd.',
	'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d objecten(s) zullen fouten bevatten.',
	'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d objecten(s) zijn onveranderd gebleven.',
	'UI:CSVImport:ObjectsWereModified' => '%1$d objecten(s) zijn aangepast.',
	'UI:CSVImport:ObjectsWereAdded' => '%1$d objecten(s) zijn toegevoegd.',
	'UI:CSVImport:ObjectsHadErrors' => 'bij %1$d objecten(s) traden fouten op.',
	'UI:Title:CSVImportStep2' => 'Stap 2 van 5: Opties voor CSV-gegevens',
	'UI:Title:CSVImportStep3' => 'Stap 3 van 5: Data mapping',
	'UI:Title:CSVImportStep4' => 'Stap 4 van 5: Import simulatie',
	'UI:Title:CSVImportStep5' => 'Stap 5 van 5: Import compleet',
	'UI:CSVImport:LinesNotImported' => 'Regels die niet konden worden geladen:',
	'UI:CSVImport:LinesNotImported+' => 'De volgende regels zijn niet geïmporteerd omdat ze fouten bevatten',
	'UI:CSVImport:SeparatorComma+' => ', (komma)',
	'UI:CSVImport:SeparatorSemicolon+' => '; (puntkomma)',
	'UI:CSVImport:SeparatorTab+' => 'tab',
	'UI:CSVImport:SeparatorOther' => 'ander:',
	'UI:CSVImport:QualifierDoubleQuote+' => '" (dubbele quote)',
	'UI:CSVImport:QualifierSimpleQuote+' => '\' (enkele quote)',
	'UI:CSVImport:QualifierOther' => 'anders:',
	'UI:CSVImport:TreatFirstLineAsHeader' => 'Eerste regel bevat kolomtitels (kolomkop)',
	'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'Sla %1$s regels aan het begin van het bestand over',
	'UI:CSVImport:CSVDataPreview' => 'CSV-voorbeeld',
	'UI:CSVImport:SelectFile' => 'Selecteer het bestand om te importeren:',
	'UI:CSVImport:Tab:LoadFromFile' => 'Vanuit bestand importeren',
	'UI:CSVImport:Tab:CopyPaste' => 'Kopieer en plak data',
	'UI:CSVImport:Tab:Templates' => 'Sjablonen',
	'UI:CSVImport:PasteData' => 'Plak data om te importeren:',
	'UI:CSVImport:PickClassForTemplate' => 'Kies sjabloon om te downloaden: ',
	'UI:CSVImport:SeparatorCharacter' => 'Scheidingsteken:',
	'UI:CSVImport:TextQualifierCharacter' => 'Teken dat rond tekst staat:',
	'UI:CSVImport:CommentsAndHeader' => 'Opmerkingen en kolomtitel',
	'UI:CSVImport:SelectClass' => 'Selecteer de klasse om te importeren:',
	'UI:CSVImport:AdvancedMode' => 'Geavanceerde mode',
	'UI:CSVImport:AdvancedMode+' => 'In geavanceerde mode kan de "id" (primaire sleutel (key)) van de objecten gebruikt worden om deze te updaten en te hernoemen. De kolom "id" (indien beschikbaar) kan alleen worden gebruikt als zoekcriterium en kan niet worden gecombineerd met andere zoekcriteria.',
	'UI:CSVImport:SelectAClassFirst' => 'Om de mapping te configureren, moet je eerst een klasse selecteren.',
	'UI:CSVImport:HeaderFields' => 'Velden',
	'UI:CSVImport:HeaderMappings' => 'Mappings',
	'UI:CSVImport:HeaderSearch' => 'Zoek?',
	'UI:CSVImport:AlertIncompleteMapping' => 'Selecteer een mapping voor ieder veld',
	'UI:CSVImport:AlertMultipleMapping' => 'Zorg dat er voor elk veld slechts één mapping is',
	'UI:CSVImport:AlertNoSearchCriteria' => 'Selecteer tenminste een zoekcriterium.',
	'UI:CSVImport:Encoding' => 'Tekstencodering',
	'UI:UniversalSearchTitle' => 'iTop - Universele zoekopdracht',
	'UI:UniversalSearch:Error' => 'Fout: %1$s',
	'UI:UniversalSearch:LabelSelectTheClass' => 'Selecteer de klasse om te zoeken: ',

	'UI:CSVReport-Value-Modified' => 'Aangepast',
	'UI:CSVReport-Value-SetIssue' => 'Kon niet worden aangepast - reden: %1$s',
	'UI:CSVReport-Value-ChangeIssue' => 'Kon niet worden aangepast naar %1$s - reden: %2$s',
	'UI:CSVReport-Value-NoMatch' => 'Geen match',
	'UI:CSVReport-Value-Missing' => 'Ontbrekende verplichte waarde',
	'UI:CSVReport-Value-Ambiguous' => 'Onduidelijk: gevonden %1$s objecten',
	'UI:CSVReport-Row-Unchanged' => 'onveranderd',
	'UI:CSVReport-Row-Created' => 'gemaakt',
	'UI:CSVReport-Row-Updated' => ' %1$d rijen aangepast',
	'UI:CSVReport-Row-Disappeared' => 'verdwenen, %1$d rijen aangepast',
	'UI:CSVReport-Row-Issue' => 'Probleem: %1$s',
	'UI:CSVReport-Value-Issue-Null' => 'Null niet toegestaan',
	'UI:CSVReport-Value-Issue-NotFound' => 'Object niet gevonden',
	'UI:CSVReport-Value-Issue-FoundMany' => ' %1$d Matches gevonden',
	'UI:CSVReport-Value-Issue-Readonly' => 'Het attribuut \'%1$s\' is alleen-lezen en kan niet worden aangepast (huidige waarde: %2$s,voorgestelde waarde: %3$s)',
	'UI:CSVReport-Value-Issue-Format' => 'Input %1$s verwerken mislukt',
	'UI:CSVReport-Value-Issue-NoMatch' => 'Onverwachte waarde voor attribuut \'%1$s\': geen match gevonden, controleer spelling',
	'UI:CSVReport-Value-Issue-Unknown' => 'Onverwachte waarde voor attribuut \'%1$s\': %2$s',
	'UI:CSVReport-Row-Issue-Inconsistent' => 'Attributen komen niet met elkaar overeeen: %1$s',
	'UI:CSVReport-Row-Issue-Attribute' => 'Onverwachte attribuutwaarden',
	'UI:CSVReport-Row-Issue-MissingExtKey' => 'Kon niet worden aangemaakt door het ontbreken van externe code(s): %1$s',
	'UI:CSVReport-Row-Issue-DateFormat' => 'Verkeerde datumformaat',
	'UI:CSVReport-Row-Issue-Reconciliation' => 'Verbeteren mislukt',
	'UI:CSVReport-Row-Issue-Ambiguous' => 'Onduidelijke verbetering',
	'UI:CSVReport-Row-Issue-Internal' => 'Interne fout: %1$s, %2$s',

	'UI:CSVReport-Icon-Unchanged' => 'Onveranderd',
	'UI:CSVReport-Icon-Modified' => 'Aangepast',
	'UI:CSVReport-Icon-Missing' => 'Ontbrekend',
	'UI:CSVReport-Object-MissingToUpdate' => 'Ontbrekend object: zal worden aangepast',
	'UI:CSVReport-Object-MissingUpdated' => 'Ontbrekend object: werd aangepast',
	'UI:CSVReport-Icon-Created' => 'Aangemaakt',
	'UI:CSVReport-Object-ToCreate' => 'Object zal worden aangemaakt',
	'UI:CSVReport-Object-Created' => 'Object aangemaakt',
	'UI:CSVReport-Icon-Error' => 'Fout',
	'UI:CSVReport-Object-Error' => 'Fout: %1$s',
	'UI:CSVReport-Object-Ambiguous' => 'Onduidelijk: %1$s',
	'UI:CSVReport-Stats-Errors' => '%1$.0f %% van de geladen objecten bevatten fouten en zullen worden genegeerd',
	'UI:CSVReport-Stats-Created' => '%1$.0f %% van de geladen objecten zullen worden gemaakt',
	'UI:CSVReport-Stats-Modified' => '%1$.0f %% zullen worden aangepast.',

	'UI:CSVExport:AdvancedMode' => 'Geavanceerde mode',
	'UI:CSVExport:AdvancedMode+' => 'In geavanceerde mode worden verscheidene kolommen toegevoegd aan de export: id van het object, id van de externe codes en hun reconciliation-attributen.',
	'UI:CSVExport:LostChars' => 'Tekstcoderingsprobleem',
	'UI:CSVExport:LostChars+' => 'Het gedownloade bestand zal worden gecodeerd in %1$s. '.ITOP_APPLICATION_SHORT.' heeft een aantal karakters gedetecteerd die niet compatibel zijn met dit formaat. Deze karakters zullen worden vervangen door een ander karakter (bijvoorbeeld karakters met accent kunnen het accent verliezen), of ze zullen worden verwijderd. Je kan data kopiëren en plakken van jouw webbrowser. Ook kan je de beheerder contacteren om de codes te veranderen (Zie parameter \'csv_file_default_charset\').',

	'UI:Audit:Title' => ITOP_APPLICATION_SHORT.' - CMDB Audit',
	'UI:Audit:InteractiveAudit' => 'Interactieve Audit',
	'UI:Audit:HeaderAuditRule' => 'Auditregel',
	'UI:Audit:HeaderNbObjects' => '# objecten',
	'UI:Audit:HeaderNbErrors' => '# fouten',
	'UI:Audit:PercentageOk' => '% Ok',
	'UI:Audit:ErrorIn_Rule_Reason' => 'OQL-fout in de regel %1$s: %2$s.',
	'UI:Audit:ErrorIn_Category_Reason' => 'OQL-fout in de categorie %1$s: %2$s.',

	'UI:RunQuery:Title' => ITOP_APPLICATION_SHORT.' - Evaluatie van OQL-query',
	'UI:RunQuery:QueryExamples' => 'Voorbeelden van query\'s',
	'UI:RunQuery:HeaderPurpose' => 'Doel',
	'UI:RunQuery:HeaderPurpose+' => 'Uitleg over de query',
	'UI:RunQuery:HeaderOQLExpression' => 'OQL-expressie',
	'UI:RunQuery:HeaderOQLExpression+' => 'De query in OQL syntax',
	'UI:RunQuery:ExpressionToEvaluate' => 'Expressie om te evalueren: ',
	'UI:RunQuery:MoreInfo' => 'Meer informatie over de query: ',
	'UI:RunQuery:DevelopedQuery' => 'Herschreven query-expressie: ',
	'UI:RunQuery:SerializedFilter' => 'Geserialiseerde filter: ',
	'UI:RunQuery:DevelopedOQL' => 'Ontwikkelde OQL',
	'UI:RunQuery:DevelopedOQLCount' => 'Ontwikkelde OQL voor aantal',
	'UI:RunQuery:ResultSQLCount' => 'Resulterende SQL voor aantal',
	'UI:RunQuery:ResultSQL' => 'Resulterende SQL',
	'UI:RunQuery:Error' => 'Er trad een fout op tijdens het uitvoeren van deze query: %1$s',
	'UI:Query:UrlForExcel' => 'URL om te gebruiken voor MS Excel-webquery\'s',
	'UI:Query:UrlV1' => 'De lijst van velden is leeg gelaten. De pagina <em>export-V2.php</em> kan niet aangeroepen worden zonder deze informatie.Daarom verwijst de onderstaande link naar de oude export-pagina: <em>export.php</em>. Deze verouderde versie heeft enkele beperkingen: de lijst van geëxporteerde velden kan verschillen afhankelijk van het gekozen export-formaat en het datamodel van iTop. Als je wil dat de lijst van geëxporteerde kolommen hetzelfde blijft over lange tijd, dan moet je een waarde opgeven voor het attribuut "Velden" en de pagina <em>export-V2.php</em> gebruiken.',
	'UI:Schema:Title' => ITOP_APPLICATION_SHORT.' objecten-schema',
	'UI:Schema:CategoryMenuItem' => 'Categorie <b>%1$s</b>',
	'UI:Schema:Relationships' => 'Relaties',
	'UI:Schema:AbstractClass' => 'Abstracte klasse: objecten van deze klasse kunnen niet worden geïnstantieerd.',
	'UI:Schema:NonAbstractClass' => 'Niet abstracte klasse: objecten van deze klasse kunnen worden geïnstantieerd.',
	'UI:Schema:ClassHierarchyTitle' => 'Hiërarchie van de klasses',
	'UI:Schema:AllClasses' => 'Alle klasses',
	'UI:Schema:ExternalKey_To' => 'Externe sleutel (key) voor %1$s',
	'UI:Schema:Columns_Description' => 'Kolommen: <em>%1$s</em>',
	'UI:Schema:Default_Description' => 'Standaard: "%1$s"',
	'UI:Schema:NullAllowed' => 'Null toegestaan',
	'UI:Schema:NullNotAllowed' => 'Null niet toegestaan',
	'UI:Schema:Attributes' => 'Attributen',
	'UI:Schema:AttributeCode' => 'Attribuutcode',
	'UI:Schema:AttributeCode+' => 'Interne code van het attribuut',
	'UI:Schema:Label' => 'Label',
	'UI:Schema:Label+' => 'Label van het attribuut',
	'UI:Schema:Type' => 'Type',

	'UI:Schema:Type+' => 'Datatype van het attribuut',
	'UI:Schema:Origin' => 'Oorsprong',
	'UI:Schema:Origin+' => 'De basisklasse waarin dit attribuut is gedefinieerd',
	'UI:Schema:Description' => 'Beschrijving',
	'UI:Schema:Description+' => 'Beschrijving van het attribuut',
	'UI:Schema:AllowedValues' => 'Toegelaten waarden',
	'UI:Schema:AllowedValues+' => 'Regels voor de mogelijke waarden van dit attribuut',
	'UI:Schema:MoreInfo' => 'Meer informatie',
	'UI:Schema:MoreInfo+' => 'Meer informatie over het veld gedefinieerd in de database',
	'UI:Schema:SearchCriteria' => 'Zoekcriteria',
	'UI:Schema:FilterCode' => 'Filtercode',
	'UI:Schema:FilterCode+' => 'Code van deze zoekcriteria',
	'UI:Schema:FilterDescription' => 'Beschrijving',
	'UI:Schema:FilterDescription+' => 'Beschrijving van deze zoekcriteria',
	'UI:Schema:AvailOperators' => 'Beschikbare medewerkers',
	'UI:Schema:AvailOperators+' => 'Mogelijke medewerkers voor deze zoekcriteria',
	'UI:Schema:ChildClasses' => 'Subklassen',
	'UI:Schema:ReferencingClasses' => 'Verwijzende klasses',
	'UI:Schema:RelatedClasses' => 'Gerelateerde klasses',
	'UI:Schema:LifeCycle' => 'Levenscyclus',
	'UI:Schema:Triggers' => 'Triggers',
	'UI:Schema:Relation_Code_Description' => 'Relatie <em>%1$s</em> (%2$s)',
	'UI:Schema:RelationDown_Description' => 'Omlaag: %1$s',
	'UI:Schema:RelationUp_Description' => 'Omhoog: %1$s',
	'UI:Schema:RelationPropagates' => '%1$s: propageert naar %2$d levels, query: %3$s',
	'UI:Schema:RelationDoesNotPropagate' => '%1$s: propageert niet (%2$d levels), query: %3$s',
	'UI:Schema:Class_ReferencingClasses_From_By' => 'Verwijzing naar %1$s door de klasse "%2$s" via het veld "%3$s"',
	'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s is gelinkt met %2$s via %3$s::<em>%4$s</em>',
	'UI:Schema:Links:1-n' => 'Klasses verwijzend naar %1$s (1:n links):',
	'UI:Schema:Links:n-n' => 'Klasses gelinkt aan %1$s (n:n links):',
	'UI:Schema:Links:All' => 'Weergave van alle gerelateerde klasses',
	'UI:Schema:NoLifeCyle' => 'Er is geen levenscyclus gedefinieerd voor deze klasse.',
	'UI:Schema:LifeCycleTransitions' => 'Overgangen',
	'UI:Schema:LifeCyleAttributeOptions' => 'Opties van attribuut',
	'UI:Schema:LifeCycleHiddenAttribute' => 'Verborgen',
	'UI:Schema:LifeCycleReadOnlyAttribute' => 'Alleen lezen',
	'UI:Schema:LifeCycleMandatoryAttribute' => 'Verplicht',
	'UI:Schema:LifeCycleAttributeMustChange' => 'Moet worden aangepast',
	'UI:Schema:LifeCycleAttributeMustPrompt' => 'Gebruiker zal gevraagd worden om de waarde aan te passen',
	'UI:Schema:LifeCycleEmptyList' => 'lege lijst',
	'UI:Schema:ClassFilter' => 'Klasse:',
	'UI:Schema:DisplayLabel' => 'Weergavelabel:',
	'UI:Schema:DisplaySelector/LabelAndCode' => 'Label en code',
	'UI:Schema:DisplaySelector/Label' => 'Label',
	'UI:Schema:DisplaySelector/Code' => 'Code',
	'UI:Schema:Attribute/Filter' => 'Filter',
	'UI:Schema:DefaultNullValue' => 'Standaardwaarde null : "%1$s"',
	'UI:LinksWidget:Autocomplete+' => 'Typ de eerste 3 karakters...',
	'UI:Edit:TestQuery' => 'Test query',
	'UI:Combo:SelectValue' => '--- selecteer een waarde ---',
	'UI:Label:SelectedObjects' => 'Geselecteerde objecten: ',
	'UI:Label:AvailableObjects' => 'Beschikbare objecten: ',
	'UI:Link_Class_Attributes' => '%1$s attributen',
	'UI:SelectAllToggle+' => '(De)selecteer alles',
	'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => 'Voeg %1$s objecten gelinkt met %2$s: %3$s toe',
	'UI:AddObjectsOf_Class_LinkedWith_Class' => 'Voeg %1$s objecten toe om te linken met de %2$s',
	'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => 'Manage %1$s objecten gelinkt met %2$s: %3$s',
	'UI:AddLinkedObjectsOf_Class' => 'Voeg %1$s toe...',
	'UI:RemoveLinkedObjectsOf_Class' => 'Verwijder geselecteerde objecten',
	'UI:Message:EmptyList:UseAdd' => 'De lijst is leeg, gebruik de "Voeg toe..." knop om elementen toe te voegen.',
	'UI:Message:EmptyList:UseSearchForm' => 'Gebruik het bovenstaande zoekveld om te zoeker naar objecten die je wilt toevoegen.',
	'UI:Wizard:FinalStepTitle' => 'Laatste stap: bevestiging',
	'UI:Title:DeletionOf_Object' => 'Verwijderen van %1$s',
	'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => 'Verwijderen van %1$d objecten van klasse "%2$s"',
	'UI:Delete:NotAllowedToDelete' => 'Je bent niet gemachtigd om dit object te verwijderen',
	'UI:Delete:NotAllowedToUpdate_Fields' => 'Je bent niet gemachtigd om het/de volgende veld(en) aan te passen: %1$s',
	'UI:Error:ActionNotAllowed' => 'Je bent niet gemachtigd om deze actie uit te voeren.',
	'UI:Error:NotEnoughRightsToDelete' => 'Dit object kon niet worden verwijderd omdat de huidige gebruiker niet de juiste rechten heeft',
	'UI:Error:CannotDeleteBecause' => 'Dit object kon niet worden verwijderd. Reden: %1$s',
	'UI:Error:CannotDeleteBecauseOfDepencies' => 'Dit object kon niet worden verwijderd omdat er eerst enkele handmatige handelingen moeten worden verricht',
	'UI:Error:CannotDeleteBecauseManualOpNeeded' => 'Dit object kon niet worden verwijderd omdat er eerst enkele handmatige handelingen moeten worden verricht',
	'UI:Archive_User_OnBehalfOf_User' => '%1$s vanwege %2$s',
	'UI:Delete:Deleted' => 'verwijderd',
	'UI:Delete:AutomaticallyDeleted' => 'automatisch verwijderd',
	'UI:Delete:AutomaticResetOf_Fields' => 'automatische reset van veld(en): %1$s',
	'UI:Delete:CleaningUpRefencesTo_Object' => 'Bezig met het opschonen van alle verwijzingen naar %1$s...',
	'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => 'Bezig met het opschonen van %1$d objecten van klasse "%2$s"...',
	'UI:Delete:Done+' => 'Wat er is gebeurd...',
	'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s verwijderd',
	'UI:Delete:ConfirmDeletionOf_Name' => 'Verwijderen van %1$s',
	'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => 'Verwijderen van %1$d objecten van klasse "%2$s"',
	'UI:Delete:CannotDeleteBecause' => 'Kon niet worden verwijderd: %1$s',
	'UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible' => 'Zou automatisch moeten verwijderd worden, maar dat is niet mogelijk: %1$s',
	'UI:Delete:MustBeDeletedManuallyButNotPossible' => 'Moet handmatig verwijderd worden, maar dat is niet mogelijk: %1$s',
	'UI:Delete:WillBeDeletedAutomatically' => 'Zal automatisch verwijderd worden',
	'UI:Delete:MustBeDeletedManually' => 'Moet handmatig verwijderd worden',
	'UI:Delete:CannotUpdateBecause_Issue' => 'Zou automatisch moeten geüpdatet worden, maar: %1$s',
	'UI:Delete:WillAutomaticallyUpdate_Fields' => 'Zal automatisch aangepast worden (reset: %1$s)',
	'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$d objecten/links verwijzen naar %2$s',
	'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$d objecten/links verwijzen naar sommige objecten die verwijderd worden',
	'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'Elke verdere verwijzing moet verwijderd worden om de integriteit van de database te verzekeren',
	'UI:Delete:Consequence+' => 'Wat er zal gebeuren',
	'UI:Delete:SorryDeletionNotAllowed' => 'Sorry, je bent niet gemachtigd om dit object te verwijderen. Voor uitgebreide uitleg, zie hierboven',
	'UI:Delete:PleaseDoTheManualOperations' => 'Verricht eerst de handmatige handelingen die hierboven staan voordat je dit object verwijdert',
	'UI:Delect:Confirm_Object' => 'Bevestig dat je  %1$s wil verwijderen.',
	'UI:Delect:Confirm_Count_ObjectsOf_Class' => 'Bevestig dat je de volgende %1$d objecten van klasse %2$s wilt verwijderen.',
	'UI:WelcomeToITop' => 'Welkom in '.ITOP_APPLICATION,
	'UI:DetailsPageTitle' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s details',
	'UI:ErrorPageTitle' => ITOP_APPLICATION_SHORT.' - Fout',
	'UI:ObjectDoesNotExist' => 'Sorry, dit object bestaat niet (of je bent niet gemachtigd het te bekijken).',
	'UI:ObjectArchived' => 'Dit object werd gearchiveerd. Gelieve de Archief-mode in te schakelen of je beheerder te contacteren.',
	'Tag:Archived' => 'Gearchiveerd',
	'Tag:Archived+' => 'Kan enkel bekeken worden in Archief-mode',
	'Tag:Obsolete' => 'Buiten dienst',
	'Tag:Obsolete+' => 'Uitgesloten uit de impactanalyse en onzichtbaar in zoekresultaten',
	'Tag:Synchronized' => 'Gesynchroniseerd',
	'ObjectRef:Archived' => 'Gearchiveerd',
	'ObjectRef:Obsolete' => 'Buiten dienst',
	'UI:SearchResultsPageTitle' => ITOP_APPLICATION_SHORT.' - Zoekresultaten',
	'UI:SearchResultsTitle' => 'Zoekresultaten',
	'UI:SearchResultsTitle+' => 'Volledige tekst - zoekresultaten',
	'UI:Search:NoSearch' => 'Geen zoekopdracht',
	'UI:Search:NeedleTooShort' => 'De zoekopdracht "%1$s" is te kort. Typ minstens %2$d karakters.',
	'UI:Search:Ongoing' => 'Zoeken naar "%1$s"',
	'UI:Search:Enlarge' => 'Vergroot de zoekopdracht',
	'UI:FullTextSearchTitle_Text' => 'Resultaten voor "%1$s":',
	'UI:Search:Count_ObjectsOf_Class_Found' => '%1$d object(en) van klasse %2$s gevonden.',
	'UI:Search:NoObjectFound' => 'Geen object gevonden.',
	'UI:ModificationPageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - %1$s - %2$s aanpassing',
	'UI:ModificationTitle_Class_Object' => 'Aanpassen van %1$s: <span class="hilite">%2$s</span>',
	'UI:ClonePageTitle_Object_Class' => ITOP_APPLICATION_SHORT.' - Kloon %1$s - %2$s aanpassing',
	'UI:CloneTitle_Class_Object' => 'Klonen van %1$s: <span class="hilite">%2$s</span>',
	'UI:CreationPageTitle_Class' => ITOP_APPLICATION_SHORT.' - %1$s aanmaken',
	'UI:CreationTitle_Class' => '%1$s aanmaken',
	'UI:SelectTheTypeOf_Class_ToCreate' => 'Selecteer het type %1$s dat moet worden aangemaakt:',
	'UI:Class_Object_NotUpdated' => 'Geen verandering waargenomen, %1$s (%2$s) is <strong>niet</strong> aangepast.',
	'UI:Class_Object_Updated' => '%1$s (%2$s) aangepast.',
	'UI:BulkDeletePageTitle' => ITOP_APPLICATION_SHORT.' - Meerdere objecten verwijderen',
	'UI:BulkDeleteTitle' => 'Selecteer de objecten die je wilt verwijderen:',
	'UI:PageTitle:ObjectCreated' => 'Object Aangemaakt.',
	'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s aangemaakt.',
	'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => 'Bezig met het toepassen van %1$s op object: %2$s in fase %3$s tot doelfase: %4$s.',
	'UI:ObjectCouldNotBeWritten' => 'Het object kon niet geschreven worden: %1$s',
	'UI:PageTitle:FatalError' => ITOP_APPLICATION_SHORT.' - Fatale Fout',
	'UI:SystemIntrusion' => 'Toegang geweigerd. Je hebt een actie aangevraagd waarvoor je niet gemachtigd bent.',
	'UI:FatalErrorMessage' => 'Fatale fout, '.ITOP_APPLICATION_SHORT.' kan niet doorgaan.',
	'UI:Error_Details' => 'Fout: %1$s.',

	'UI:PageTitle:ClassProjections' => ITOP_APPLICATION_SHORT.' gebruikersbeheer - klasse-projecties',
	'UI:PageTitle:ProfileProjections' => ITOP_APPLICATION_SHORT.' gebruikersbeheer - profiel-projecties',
	'UI:UserManagement:Class' => 'Klasse',
	'UI:UserManagement:Class+' => 'Klasse van objecten',
	'UI:UserManagement:ProjectedObject' => 'Object',
	'UI:UserManagement:ProjectedObject+' => 'Beschermd object',
	'UI:UserManagement:AnyObject' => '* elk *',
	'UI:UserManagement:User' => 'Gebruiker',
	'UI:UserManagement:User+' => 'Gebruiker bezig met de projectie',
	'UI:UserManagement:Profile' => 'Profiel',
	'UI:UserManagement:Profile+' => 'Profiel waarin de projectie is opgegeven',
	'UI:UserManagement:Action:Read' => 'Lezen',
	'UI:UserManagement:Action:Read+' => 'Lezen/weergeven van objecten',
	'UI:UserManagement:Action:Modify' => 'Aanpassen',
	'UI:UserManagement:Action:Modify+' => 'Maken/aanpassen van objecten',
	'UI:UserManagement:Action:Delete' => 'Verwijderen',
	'UI:UserManagement:Action:Delete+' => 'Verwijder van objecten',
	'UI:UserManagement:Action:BulkRead' => 'Meerdere objecten lezen',
	'UI:UserManagement:Action:BulkRead+' => 'Lezen/weergevan van meerdere objecten',
	'UI:UserManagement:Action:BulkModify' => 'Meerdere objecten aanpassen',
	'UI:UserManagement:Action:BulkModify+' => 'Aanpassen van meerdere objecten in één keer',
	'UI:UserManagement:Action:BulkDelete' => 'Meerdere objecten verwijderen',
	'UI:UserManagement:Action:BulkDelete+' => 'Verwijderen van meerdere objecten in één keer',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => 'Toegestane acties',
	'UI:UserManagement:Action' => 'Actie',
	'UI:UserManagement:Action+' => 'Actie uitgevoerd door de gebruiker',
	'UI:UserManagement:TitleActions' => 'Acties',
	'UI:UserManagement:Permission' => 'Toestemming',
	'UI:UserManagement:Permission+' => 'De autorisaties van de gebruiker',
	'UI:UserManagement:Attributes' => 'Attributen',
	'UI:UserManagement:ActionAllowed:Yes' => 'Ja',
	'UI:UserManagement:ActionAllowed:No' => 'Nee',
	'UI:UserManagement:AdminProfile+' => 'Beheerders hebben volledige lees- en schrijfrechten nodig in de database.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'Niet beschikbaar',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'Er is geen levenscyclus gedefinieerd voor deze klasse',
	'UI:UserManagement:GrantMatrix' => 'Rechtenmatrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => 'Link tussen %1$s en %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => 'Link tussen %1$s en %2$s',

	'Menu:AdminTools' => 'Beheerderstools', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools+' => 'Beheerderstools', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AdminTools?' => 'Tools die alleen toegankelijk zijn voor gebruikers met een beheerdersprofiel', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:SystemTools' => 'Systeem',

	'UI:ChangeManagementMenu' => 'Change Management',
	'UI:ChangeManagementMenu+' => 'Change Management',
	'UI:ChangeManagementMenu:Title' => 'Overzicht changes',
	'UI-ChangeManagementMenu-ChangesByType' => 'Changes aan de hand van soort',
	'UI-ChangeManagementMenu-ChangesByStatus' => 'Changes aan de hand van soort status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'Changes aan de hand van werkgroep',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'Nog niet toegewezen Changes',

	'UI:ConfigurationManagementMenu' => 'Configuratie Management',
	'UI:ConfigurationManagementMenu+' => 'Configuratie Management',
	'UI:ConfigurationManagementMenu:Title' => 'Infrastructuuroverzicht',
	'UI-ConfigurationManagementMenu-InfraByType' => 'Objecten van de infrastructuur aan de hand van soort',
	'UI-ConfigurationManagementMenu-InfraByStatus' => 'Objecten van de infrastructuur aan de hand van status',

	'UI:ConfigMgmtMenuOverview:Title' => 'Dashboard voor Configuratie Management',
	'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => 'Configuratie-items aan de hand van status',
	'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => 'Configuratie-items aan de hand van soort',

	'UI:RequestMgmtMenuOverview:Title' => 'Dashboard voor Request Management',
	'UI-RequestManagementOverview-RequestByService' => 'Gebruikersaanvragen aan de hand van service',
	'UI-RequestManagementOverview-RequestByPriority' => 'Gebruikersaanvragen aan de hand van prioriteit',
	'UI-RequestManagementOverview-RequestUnassigned' => 'Nog niet toegewezen gebruikersaanvragen',

	'UI:IncidentMgmtMenuOverview:Title' => 'Dashboard voor Incident Management',
	'UI-IncidentManagementOverview-IncidentByService' => 'Incidenten aan de hand van service',
	'UI-IncidentManagementOverview-IncidentByPriority' => 'Incidenten aan de hand van prioriteit',
	'UI-IncidentManagementOverview-IncidentUnassigned' => 'Nog niet toegewezen incidenten',

	'UI:ChangeMgmtMenuOverview:Title' => 'Dashboard voor Change Management',
	'UI-ChangeManagementOverview-ChangeByType' => 'Changes aan de hand van soort',
	'UI-ChangeManagementOverview-ChangeUnassigned' => 'Nog niet toegewezen Changes',
	'UI-ChangeManagementOverview-ChangeWithOutage' => 'Outages door changes',

	'UI:ServiceMgmtMenuOverview:Title' => 'Dashboard voor Service Management',
	'UI-ServiceManagementOverview-CustomerContractToRenew' => 'Klantencontracten die binnen 30 dagen vernieuwd moeten worden',
	'UI-ServiceManagementOverview-ProviderContractToRenew' => 'Providercontracten die binnen 30 dagen vernieuwd moeten worden',

	'UI:ContactsMenu' => 'Contacten',
	'UI:ContactsMenu+' => 'Contacten',
	'UI:ContactsMenu:Title' => 'Overzicht van contacten',
	'UI-ContactsMenu-ContactsByLocation' => 'Contacten aan de hand van locatie',
	'UI-ContactsMenu-ContactsByType' => 'Contacten aan de hand van soort',
	'UI-ContactsMenu-ContactsByStatus' => 'Contacten aan de hand van status',

	'Menu:CSVImportMenu' => 'CSV import', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:CSVImportMenu+' => 'In bulk aanmaken of aanpassen van objecten', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataModelMenu' => 'Datamodel', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataModelMenu+' => 'Overzicht van het Datamodel', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ExportMenu' => 'Exporteer', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ExportMenu+' => 'Exporteer de resultaten van elke query als HTML, CSV of XML', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:NotificationsMenu' => 'Meldingen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:NotificationsMenu+' => 'Configuratie van de meldingen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'UI:NotificationsMenu:Title' => 'Configuratie van <span class="hilite">Meldingen</span>',
	'UI:NotificationsMenu:Help' => 'Help',
	'UI:NotificationsMenu:HelpContent' => '<p>In '.ITOP_APPLICATION_SHORT.' zijn de meldingen volledig aan te passen. Ze zijn gebaseerd op twee sets van objecten: <i>triggers and actions</i>.</p>
<p><i><b>Triggers</b></i> bepalen wanneer er een melding is. Er zijn verschillende triggers als onderdeel van '.ITOP_APPLICATION_SHORT.' core, maar andere kunnen door middel van uitbreidingen worden toegevoegd.

<p>Sommige triggers worden uitgevoerd:</p>

<ol>
	<li>wanneer een object van de opgegeven klasse wordt <b>aangemaakt</b>, <b>bijgewerkt</b> of <b>verwijderd</b>.</li>
	<li>wanneer een object van een bepaalde klasse een opgegeven <b>fase</b> <b>intreedt</b> of <b>uittreedt</b>.</li>
	<li>wanneer een <b>drempelwaarde</b> op <b>TTO</b> of <b>TTR</b> is <b>bereikt</b>.</li>
</ol>
</p>
<p>
<i><b>Acties</b></i> bepalen de acties (zoals het versturen van meldingen) die uitgevoerd worden bij een bepaalde trigger. 
Op dit moment is er slechts een standaardactie: het verzenden van een e-mail. 
Per actie kan je ook het sjabloon instellen die gebruikt moet worden voor het versturen van de e-mail, maar ook andere e-mailparameters zoals de ontvangers, de prioriteit, enz. </p>
<p>Een <a href="../setup/email.test.php" target="_blank">speciale testpagina (email.test.php)</a> is beschikbaar voor het testen en oplossen van eventuele problemen met jouw PHP e-mailconfiguratie.</p>
<p>Acties moeten gekoppeld zijn aan triggers.
Bij die koppeling wordt aan elke actie een volgorde-nummer gegeven. Dit bepaalt in welke volgorde de acties moeten worden uitgevoerd.</p>',
	'UI:NotificationsMenu:Triggers' => 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => 'Beschikbare triggers',
	'UI:NotificationsMenu:OnCreate' => 'Wanneer een object is aangemaakt',
	'UI:NotificationsMenu:OnStateEnter' => 'Wanneer een object een bepaalde fase intreedt',
	'UI:NotificationsMenu:OnStateLeave' => 'Wanneer een object een bepaalde fase uittreedt',
	'UI:NotificationsMenu:Actions' => 'Acties',
	'UI:NotificationsMenu:AvailableActions' => 'Beschikbare acties',

	'Menu:TagAdminMenu' => 'Tags-configuratie',
	'Menu:TagAdminMenu+' => 'Beheer de tags',
	'UI:TagAdminMenu:Title' => 'Tags-configuratie',
	'UI:TagAdminMenu:NoTags' => 'Geen tags geconfigureerd',
	'UI:TagSetFieldData:Error' => 'Fout: %1$s',

	'Menu:AuditCategories' => 'Auditcategorieën', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:AuditCategories+' => 'Auditcategorieën', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:Notifications:Title' => 'Auditcategorieën', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:RunQueriesMenu' => 'Query\'s uitvoeren', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:RunQueriesMenu+' => 'Voer een query uit', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:QueryMenu' => 'Favoriete query\'s', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:QueryMenu+' => 'Favoriete query\'s', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:DataAdministration' => 'Databeheer', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataAdministration+' => 'Databeheer', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UniversalSearchMenu' => 'Globale zoekopdracht', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UniversalSearchMenu+' => 'Zoek naar alles...', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserManagementMenu' => 'Gebruikersbeheer', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserManagementMenu+' => 'Gebruikersbeheer', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:ProfilesMenu' => 'Profielen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu+' => 'Profielen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:ProfilesMenu:Title' => 'Profielen', // Duplicated into itop-welcome-itil (will be removed from here...)

	'Menu:UserAccountsMenu' => 'Gebruikersaccounts', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu+' => 'Gebruikersaccounts', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:UserAccountsMenu:Title' => 'Gebruikersaccounts', // Duplicated into itop-welcome-itil (will be removed from here...)	

	'UI:iTopVersion:Short' => '%1$s versie %2$s',
	'UI:iTopVersion:Long' => '%1$s versie %2$s-%3$s uitgegeven op %4$s',
	'UI:PropertiesTab' => 'Eigenschappen',

	'UI:OpenDocumentInNewWindow_' => 'Open dit document in een nieuw venster: %1$s',
	'UI:DownloadDocument_' => 'Download dit document: %1$s',
	'UI:Document:NoPreview' => 'Er is geen voorbeeld beschikbaar voor dit soort document',
	'UI:Download-CSV' => 'Download %1$s',

	'UI:DeadlineMissedBy_duration' => 'Gemist op %1$s',
	'UI:Deadline_LessThan1Min' => '< 1 min',
	'UI:Deadline_Minutes' => '%1$d min',
	'UI:Deadline_Hours_Minutes' => '%1$dh %2$dmin',
	'UI:Deadline_Days_Hours_Minutes' => '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'Help',
	'UI:PasswordConfirm' => '(Bevestig)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => 'Sla dit object op voordat je meer %1$s objecten toevoegt.',
	'UI:DisplayThisMessageAtStartup' => 'Geef dit bericht weer bij het opstarten',
	'UI:RelationshipGraph' => 'Grafische weergave',
	'UI:RelationshipList' => 'Lijst',
	'UI:RelationGroups' => 'Groepen',
	'UI:OperationCancelled' => 'Operatie afgebroken',
	'UI:ElementsDisplayed' => 'Filtering',
	'UI:RelationGroupNumber_N' => 'Groep #%1$d',
	'UI:Relation:ExportAsPDF' => 'Exporteer als PDF...',
	'UI:RelationOption:GroupingThreshold' => 'Drempelwaarde voor groeperen',
	'UI:Relation:AdditionalContextInfo' => 'Extra contextinfo',
	'UI:Relation:NoneSelected' => 'Geen',
	'UI:Relation:Zoom' => 'Zoom',
	'UI:Relation:ExportAsAttachment' => 'Exporteer als bijlage',
	'UI:Relation:DrillDown' => 'Details...',
	'UI:Relation:PDFExportOptions' => 'Opties voor PDF-export',
	'UI:Relation:AttachmentExportOptions_Name' => 'Opties voor bijlage naar %1$s',
	'UI:RelationOption:Untitled' => 'Naamloos',
	'UI:Relation:Key' => 'Sleutel (key)',
	'UI:Relation:Comments' => 'Opmerkingen',
	'UI:RelationOption:Title' => 'Titel',
	'UI:RelationOption:IncludeList' => 'Voeg lijst van objecten toe',
	'UI:RelationOption:Comments' => 'Opmerkingen',
	'UI:Button:Export' => 'Exporteer',
	'UI:Relation:PDFExportPageFormat' => 'Paginaformaat',
	'UI:PageFormat_A3' => 'A3',
	'UI:PageFormat_A4' => 'A4',
	'UI:PageFormat_Letter' => 'Letter (Amerikaans)',
	'UI:Relation:PDFExportPageOrientation' => 'Pagina-oriëntatie',
	'UI:PageOrientation_Portrait' => 'Portret',
	'UI:PageOrientation_Landscape' => 'Landschap',
	'UI:RelationTooltip:Redundancy' => 'Redundantie',
	'UI:RelationTooltip:ImpactedItems_N_of_M' => '# geïmpacteerde items: %1$d / %2$d',
	'UI:RelationTooltip:CriticalThreshold_N_of_M' => 'Kritieke drempelwaarde: %1$d / %2$d',
	'Portal:Title' => ITOP_APPLICATION_SHORT.' gebruikersportaal',
	'Portal:NoRequestMgmt' => 'Beste %1$s, je bent naar deze pagina doorverwezen omdat jouw account is geconfigureerd met het profiel "Portal user". Helaas is '.ITOP_APPLICATION_SHORT.' niet geïnstalleerd met de optie "Request Management". Neem contact op met jouw beheerder.',
	'Portal:Refresh' => 'Herlaad',
	'Portal:Back' => 'Vorige',
	'Portal:WelcomeUserOrg' => 'Welkom %1$s, van %2$s',
	'Portal:TitleDetailsFor_Request' => 'Details voor aanvraag',
	'Portal:ShowOngoing' => 'Laat lopende aanvragen zien',
	'Portal:ShowClosed' => 'Laat gesloten aanvragen zien',
	'Portal:CreateNewRequest' => 'Maak een nieuwe aanvraag aan',
	'Portal:CreateNewRequestItil' => 'Maak een nieuwe aanvraag aan',
	'Portal:CreateNewIncidentItil' => 'Maak een nieuw incidentrapport aan',
	'Portal:ChangeMyPassword' => 'Verander mijn wachtwoord',
	'Portal:Disconnect' => 'Disconnect',
	'Portal:OpenRequests' => 'Mijn lopende aanvragen',
	'Portal:ClosedRequests' => 'Mijn gesloten aanvragen',
	'Portal:ResolvedRequests' => 'Mijn opgeloste aanvragen',
	'Portal:SelectService' => 'Selecteer een service uit de catalogus:',
	'Portal:PleaseSelectOneService' => 'Selecteer een service',
	'Portal:SelectSubcategoryFrom_Service' => 'Selecteer een subcategorie voor de service %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'Selecteer een subcategorie',
	'Portal:DescriptionOfTheRequest' => 'Voeg een beschrijving voor jouw aanvraag toe:',
	'Portal:TitleRequestDetailsFor_Request' => 'Details voor de aanvraag %1$s:',
	'Portal:NoOpenRequest' => 'Geen aanvragen in deze categorie',
	'Portal:NoClosedRequest' => 'Geen aanvragen in deze categorie',
	'Portal:Button:ReopenTicket' => 'Heropen deze ticket',
	'Portal:Button:CloseTicket' => 'Sluit deze ticket',
	'Portal:Button:UpdateRequest' => 'Update de aanvraag',
	'Portal:EnterYourCommentsOnTicket' => 'Voeg opmerkingen over het oplossen van deze ticket toe:',
	'Portal:ErrorNoContactForThisUser' => 'Fout: de huidige gebruiker is niet gelinkt aan een persoon/contact. Neem contact op met jouw beheerder.',
	'Portal:Attachments' => 'Bijlagen',
	'Portal:AddAttachment' => ' Voeg bijlage toe ',
	'Portal:RemoveAttachment' => ' Verwijder bijlage ',
	'Portal:Attachment_No_To_Ticket_Name' => 'Bijlage #%1$d to %2$s (%3$s)',
	'Portal:SelectRequestTemplate' => 'Selecteer een sjabloon voor %1$s',
	'Enum:Undefined' => 'Ongedefinieerd',
	'UI:DurationForm_Days_Hours_Minutes_Seconds' => '%1$s dagen %2$s uren %3$s minuten %4$s seconden',
	'UI:ModifyAllPageTitle' => 'Bewerk alles',
	'UI:Modify_N_ObjectsOf_Class' => 'Bezig met het aanpassen van %1$d objecten van klasse %2$s',
	'UI:Modify_M_ObjectsOf_Class_OutOf_N' => 'Bezig met het aanpassen van %1$d objecten van klasse %2$s van de %3$d',
	'UI:Menu:ModifyAll' => 'Bewerk...',
	'UI:Button:ModifyAll' => 'Bewerk alles',
	'UI:Button:PreviewModifications' => 'Voorbeeld van de bewerkingen >>',
	'UI:ModifiedObject' => 'Object is aangepast',
	'UI:BulkModifyStatus' => 'Operatie',
	'UI:BulkModifyStatus+' => 'Status van de operatie',
	'UI:BulkModifyErrors' => 'Fouten (indien van toepassing)',
	'UI:BulkModifyErrors+' => 'Fouten die de bewerking verhinderen',
	'UI:BulkModifyStatusOk' => 'Ok',
	'UI:BulkModifyStatusError' => 'Fout',
	'UI:BulkModifyStatusModified' => 'Aangepast',
	'UI:BulkModifyStatusSkipped' => 'Overgeslagen',
	'UI:BulkModify_Count_DistinctValues' => '%1$d unieke waarden:',
	'UI:BulkModify:Value_Exists_N_Times' => '%1$s, %2$d keer',
	'UI:BulkModify:N_MoreValues' => '%1$d meer waarden...',
	'UI:AttemptingToSetAReadOnlyAttribute_Name' => 'Bezig met het instellen van het alleen-lezen veld: %1$s',
	'UI:FailedToApplyStimuli' => 'De actie is mislukt.',
	'UI:StimulusModify_N_ObjectsOf_Class' => '%1$s: Bezig met het bewerken van %2$d objecten van klasse %3$s',
	'UI:CaseLogTypeYourTextHere' => 'Typ jouw tekst hier:',
	'UI:CaseLog:Header_Date_UserName' => '%1$s - %2$s:',
	'UI:CaseLog:InitialValue' => 'Beginwaarde:',
	'UI:AttemptingToSetASlaveAttribute_Name' => 'Het veld %1$s is niet aanpasbaar omdat het onderdeel is van een datasynchronisatie. Waarde niet opgegeven',
	'UI:ActionNotAllowed' => 'Je hebt geen toestemming om deze actie op deze objecten uit te voeren.',
	'UI:BulkAction:NoObjectSelected' => 'Selecteer tenminste een object om deze actie uit te voeren',
	'UI:AttemptingToChangeASlaveAttribute_Name' => 'Het veld %1$s is niet aanpasbaar omdat het onderdeel is van een datasynchronisatie. Waarde blijft onveranderd',
	'UI:Pagination:HeaderSelection' => 'Totaal: %1$s objecten (%2$s objecten geselecteerd).',
	'UI:Pagination:HeaderNoSelection' => 'Totaal: %1$s objecten.',
	'UI:Pagination:PageSize' => '%1$s objecten per pagina',
	'UI:Pagination:PagesLabel' => 'Paginas:',
	'UI:Pagination:All' => 'Alles',
	'UI:HierarchyOf_Class' => 'Hierarchie van %1$s',
	'UI:Preferences' => 'Voorkeuren...',
	'UI:ArchiveModeOn' => 'Schakel Archief-mode in',
	'UI:ArchiveModeOff' => 'Schakel Archief-mode uit',
	'UI:ArchiveMode:Banner' => 'Archief-mode',
	'UI:ArchiveMode:Banner+' => 'Gearchiveerde objecten zijn zichtbaar, maar kunnen niet worden aangepast',
	'UI:FavoriteOrganizations' => 'Favoriete organisaties',
	'UI:FavoriteOrganizations+' => 'Duid in onderstaande lijst de organisaties aan die je wilt zien in de keuzelijst voor een snelle toegang. Dit is geen beveiligingsinstelling; objecten van elke organisatie zijn nog steed zichtbaar en toegankelijk door "Alle Organisaties" te selecteren in de keuzelijst.',
	'UI:FavoriteLanguage' => 'Taal van de gebruikersinterface',
	'UI:Favorites:SelectYourLanguage' => 'Selecteer jouw taal',
	'UI:FavoriteOtherSettings' => 'Overige instellingen',
	'UI:Favorites:Default_X_ItemsPerPage' => 'Standaardlengte voor lijsten: %1$s items per pagina',
	'UI:Favorites:ShowObsoleteData' => 'Toon "Buiten dienst"-data',
	'UI:Favorites:ShowObsoleteData+' => 'Toon "Buiten dienst"-data in zoekresultaten en in keuzelijsten.',
	'UI:NavigateAwayConfirmationMessage' => 'Bewerkingen zullen worden genegeerd.',
	'UI:CancelConfirmationMessage' => 'Je zult jouw aanpassingen verliezen. Wil je toch doorgaan?',
	'UI:AutoApplyConfirmationMessage' => 'Sommige veranderingen zijn nog niet doorgevoerd. Wil je dat '.ITOP_APPLICATION_SHORT.' deze meeneemt?',
	'UI:Create_Class_InState' => 'Maak %1$s aan in deze fase: ',
	'UI:OrderByHint_Values' => 'Sorteervolgorde: %1$s',
	'UI:Menu:AddToDashboard' => 'Voeg toe aan dashboard...',
	'UI:Button:Refresh' => 'Herlaad',
	'UI:Button:GoPrint' => 'Afdrukken...',
	'UI:ExplainPrintable' => 'Klik op het %1$s-icoon om items te verbergen op de afdruk.<br/>Gebruik de "Afdrukvoorbeeld"-functie van je browser indien nodig.<br/>Opmerking: deze hoofding en andere weergave-opties zullen niet worden afgedrukt.',
	'UI:PrintResolution:FullSize' => 'Volledig formaat',
	'UI:PrintResolution:A4Portrait' => 'A4 Portret',
	'UI:PrintResolution:A4Landscape' => 'A4 Landschap',
	'UI:PrintResolution:LetterPortrait' => 'Letter Portret',
	'UI:PrintResolution:LetterLandscape' => 'Letter Landschap',
	'UI:Toggle:StandardDashboard' => 'Standaard',
	'UI:Toggle:CustomDashboard' => 'Aangepast',

	'UI:ConfigureThisList' => 'Configureer deze lijst...',
	'UI:ListConfigurationTitle' => 'Configuratie van lijst',
	'UI:ColumnsAndSortOrder' => 'Kolommen en sorteervolgorde:',
	'UI:UseDefaultSettings' => 'Gebruik de standaard instellingen',
	'UI:UseSpecificSettings' => 'Gebruik de volgende instellingen:',
	'UI:Display_X_ItemsPerPage' => 'Geef %1$s items per pagina weer',
	'UI:UseSavetheSettings' => 'Sla de instellingen op',
	'UI:OnlyForThisList' => 'Alleen voor deze lijst',
	'UI:ForAllLists' => 'Standaard voor alle lijsten',
	'UI:ExtKey_AsLink' => '%1$s (Link)',
	'UI:ExtKey_AsFriendlyName' => '%1$s (Herkenbare naam)',
	'UI:ExtField_AsRemoteField' => '%1$s (%2$s)',
	'UI:Button:MoveUp' => 'Ga omhoog',
	'UI:Button:MoveDown' => 'Ga omlaag',

	'UI:OQL:UnknownClassAndFix' => 'Onbekende klasse "%1$s". Je zou "%2$s" kunnen proberen.',
	'UI:OQL:UnknownClassNoFix' => 'Onbekende klasse "%1$s"',

	'UI:Dashboard:Edit' => 'Bewerk deze pagina...',
	'UI:Dashboard:Revert' => 'Herstel de originele versie...',
	'UI:Dashboard:RevertConfirm' => 'Alle bewerkingen die zijn gemaakt aan de originele versie zullen verloren gaan. Bevestig dat je wilt doorgaan.',
	'UI:ExportDashBoard' => 'Exporteer naar een bestand',
	'UI:ImportDashBoard' => 'Importeer vanuit een bestand',
	'UI:ImportDashboardTitle' => 'Importeer vanuit een bestand',
	'UI:ImportDashboardText' => 'Selecteer een bestand van het dashboard om te importeren:',


	'UI:DashletCreation:Title' => 'Maak een nieuwe Dashlet aan',
	'UI:DashletCreation:Dashboard' => 'Dashboard',
	'UI:DashletCreation:DashletType' => 'Soort dashlet',
	'UI:DashletCreation:EditNow' => 'Bewerk het dashboard',

	'UI:DashboardEdit:Title' => 'Dashboard editor',
	'UI:DashboardEdit:DashboardTitle' => 'Titel',
	'UI:DashboardEdit:AutoReload' => 'Automatisch vernieuwen',
	'UI:DashboardEdit:AutoReloadSec' => 'Interval voor het automatisch vernieuwen (seconden)',
	'UI:DashboardEdit:AutoReloadSec+' => 'Het toegestane minimum is 5 seconden',

	'UI:DashboardEdit:Layout' => 'Layout',
	'UI:DashboardEdit:Properties' => 'Eigenschappen van dashboard',
	'UI:DashboardEdit:Dashlets' => 'Beschikbare Dashlets',
	'UI:DashboardEdit:DashletProperties' => 'Eigenschappen van dashlet',

	'UI:Form:Property' => 'Eigenschap',
	'UI:Form:Value' => 'Waarde',

	'UI:DashletUnknown:Label' => 'Onbekend',
	'UI:DashletUnknown:Description' => 'Onbekende dashlet (mogelijk verwijderd)',
	'UI:DashletUnknown:RenderText:View' => 'Kan deze dashlet niet weergeven.',
	'UI:DashletUnknown:RenderText:Edit' => 'Kan deze dashlet niet weergeven (klasse "%1$s"). Controleer bij je '.ITOP_APPLICATION_SHORT.'-beheerder of dit nog beschikbaar is.',
	'UI:DashletUnknown:RenderNoDataText:Edit' => 'Geen voorbeeld mogelijk van deze dashlet (klasse "%1$s").',
	'UI:DashletUnknown:Prop-XMLConfiguration' => 'Configuratie (getoond als ruwe XML)',

	'UI:DashletProxy:Label' => 'Proxy',
	'UI:DashletProxy:Description' => 'Proxy dashlet',
	'UI:DashletProxy:RenderNoDataText:Edit' => 'Geen voorbeeld mogelijk van deze dashlet van een derde partij (klasse "%1$s").',
	'UI:DashletProxy:Prop-XMLConfiguration' => 'Configuratie (getoond als ruwe XML)',

	'UI:DashletPlainText:Label' => 'Tekst',
	'UI:DashletPlainText:Description' => 'Gewone tekst (niet geformatteerd)',
	'UI:DashletPlainText:Prop-Text' => 'Tekst',
	'UI:DashletPlainText:Prop-Text:Default' => 'Voeg hier wat tekst toe...',

	'UI:DashletObjectList:Label' => 'Objectlijst',
	'UI:DashletObjectList:Description' => 'Objectlijst dashlet',
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
	'UI:DashletGroupBy:MissingGroupBy' => 'Selecteer het veld waarop de objecten gegroepeerd moeten worden',

	'UI:DashletGroupByPie:Label' => 'Cirkeldiagram',
	'UI:DashletGroupByPie:Description' => 'Cirkeldiagram',
	'UI:DashletGroupByBars:Label' => 'Staafdiagram',
	'UI:DashletGroupByBars:Description' => 'Staafdiagram',
	'UI:DashletGroupByTable:Label' => 'Groepeer aan de hand van (tabel)',
	'UI:DashletGroupByTable:Description' => 'Lijst (Gegroepeerd aan de hand van een veld)',

	// New in 2.5
	'UI:DashletGroupBy:Prop-Function' => 'Groepeerfunctie',
	'UI:DashletGroupBy:Prop-FunctionAttribute' => 'Functie-attribuut',
	'UI:DashletGroupBy:Prop-OrderDirection' => 'Volgorde',
	'UI:DashletGroupBy:Prop-OrderField' => 'Sorteren op',
	'UI:DashletGroupBy:Prop-Limit' => 'Limiet',

	'UI:DashletGroupBy:Order:asc' => 'Oplopend',
	'UI:DashletGroupBy:Order:desc' => 'Aflopend',

	'UI:GroupBy:count' => 'Aantal',
	'UI:GroupBy:count+' => 'Aantal items',
	'UI:GroupBy:sum' => 'Som',
	'UI:GroupBy:sum+' => 'Som van %1$s',
	'UI:GroupBy:avg' => 'Gemiddelde',
	'UI:GroupBy:avg+' => 'Gemiddelde van %1$s',
	'UI:GroupBy:min' => 'Minimum',
	'UI:GroupBy:min+' => 'Minimum van %1$s',
	'UI:GroupBy:max' => 'Maximum',
	'UI:GroupBy:max+' => 'Maximum van %1$s',
	// ---

	'UI:DashletHeaderStatic:Label' => 'Kolom',
	'UI:DashletHeaderStatic:Description' => 'Geeft een horizontale separator weer',
	'UI:DashletHeaderStatic:Prop-Title' => 'Titel',
	'UI:DashletHeaderStatic:Prop-Title:Default' => 'Contacten',
	'UI:DashletHeaderStatic:Prop-Icon' => 'Icoon',

	'UI:DashletHeaderDynamic:Label' => 'Kolom met gegevens',
	'UI:DashletHeaderDynamic:Description' => 'Kolom met statistieken (gegroepeerd aan de hand van...)',
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

	// Short version for the DatePicker
	'DayOfWeek-Sunday-Min' => 'Zo',
	'DayOfWeek-Monday-Min' => 'Ma',
	'DayOfWeek-Tuesday-Min' => 'Di',
	'DayOfWeek-Wednesday-Min' => 'Wo',
	'DayOfWeek-Thursday-Min' => 'Do',
	'DayOfWeek-Friday-Min' => 'Vr',
	'DayOfWeek-Saturday-Min' => 'Za',
	'Month-01-Short' => 'Jan',
	'Month-02-Short' => 'Feb',
	'Month-03-Short' => 'Maa',
	'Month-04-Short' => 'Apr',
	'Month-05-Short' => 'Mei',
	'Month-06-Short' => 'Jun',
	'Month-07-Short' => 'Jul',
	'Month-08-Short' => 'Aug',
	'Month-09-Short' => 'Sep',
	'Month-10-Short' => 'Okt',
	'Month-11-Short' => 'Nov',
	'Month-12-Short' => 'Dec',
	'Calendar-FirstDayOfWeek' => '0', // 0 = Sunday, 1 = Monday, etc...

	'UI:Menu:ShortcutList' => 'Maak een snelkoppeling aan...',
	'UI:ShortcutRenameDlg:Title' => 'Hernoem de snelkoppeling',
	'UI:ShortcutListDlg:Title' => 'Maak een snelkoppeling voor de lijst aan',
	'UI:ShortcutDelete:Confirm' => 'Bevestig dat je de snelkoppeling(en) wil verwijderen.',
	'Menu:MyShortcuts' => 'Mijn Snelkoppelingen', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Class:Shortcut' => 'Snelkoppelingen',
	'Class:Shortcut+' => '',
	'Class:Shortcut/Attribute:name' => 'Naam',
	'Class:Shortcut/Attribute:name+' => 'Label gebruikt in het menu en in de titel van de pagina',
	'Class:ShortcutOQL' => 'Zoekresultaat snelkoppeling',
	'Class:ShortcutOQL+' => '',
	'Class:ShortcutOQL/Attribute:oql' => 'Query',
	'Class:ShortcutOQL/Attribute:oql+' => 'OQL die de lijst van objecten om naar te zoeken definieert',
	'Class:ShortcutOQL/Attribute:auto_reload' => 'Automatisch vernieuwen',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:none' => 'Onbruikbaar',
	'Class:ShortcutOQL/Attribute:auto_reload/Value:custom' => 'Aangepast interval',
	'Class:ShortcutOQL/Attribute:auto_reload_sec' => 'Interval van het automatisch vernieuwen (seconden)',
	'Class:ShortcutOQL/Attribute:auto_reload_sec/tip' => 'De minimale waarde is %1$d seconden',

	'UI:FillAllMandatoryFields' => 'Vul de verplichte velden in.',
	'UI:ValueMustBeSet' => 'Gelieve een waarde op te geven',
	'UI:ValueMustBeChanged' => 'Gelieve de waarde te veranderen',
	'UI:ValueInvalidFormat' => 'Ongeldig formaat',

	'UI:CSVImportConfirmTitle' => 'Bevestig de actie',
	'UI:CSVImportConfirmMessage' => 'Weet je zeker dat je dit wilt doen?',
	'UI:CSVImportError_items' => 'Fouten: %1$d',
	'UI:CSVImportCreated_items' => 'Aangemaakt: %1$d',
	'UI:CSVImportModified_items' => 'Bewerkt: %1$d',
	'UI:CSVImportUnchanged_items' => 'Onveranderd: %1$d',
	'UI:CSVImport:DateAndTimeFormats' => 'Datum- en tijdformaat',
	'UI:CSVImport:DefaultDateTimeFormat_Format_Example' => 'Standaardformaat: %1$s (bv. %2$s)',
	'UI:CSVImport:CustomDateTimeFormat' => 'Aangepast formaat: %1$s',
	'UI:CSVImport:CustomDateTimeFormatTooltip' => 'Beschikbare variabelen:<table>
	<tr><td>Y</td><td>jaar (4 cijfers, bv. 2016)</td></tr>
	<tr><td>y</td><td>jaar (2 cijfers, bv. 16 voor 2016)</td></tr>
	<tr><td>m</td><td>maand (2 cijfers, bv. 01..12)</td></tr>
	<tr><td>n</td><td>maand (1 of 2 cijfers zonder 0 ervoor, bv. 1..12)</td></tr>
	<tr><td>d</td><td>dag (2 cijfers, bv. 01..31)</td></tr>
	<tr><td>j</td><td>dag (1 of 2 cijfers zonder 0 ervoor, bv. 1..31)</td></tr>
	<tr><td>H</td><td>uur (24 uur, 2 cijfers, bv. 00..23)</td></tr>
	<tr><td>h</td><td>uur (12 uur, 2 cijfers, bv. 01..12)</td></tr>
	<tr><td>G</td><td>uur (24 uur, 1 or 2 cijfers zonder 0 ervoor, bv. 0..23)</td></tr>
	<tr><td>g</td><td>uur (12 uur, 1 or 2 cijfers zonder 0 ervoor, bv. 1..12)</td></tr>
	<tr><td>a</td><td>uur, am of pm (kleine letters)</td></tr>
	<tr><td>A</td><td>uur, AM of PM (grote letters)</td></tr>
	<tr><td>i</td><td>minuten (2 cijfers, bv. 00..59)</td></tr>
	<tr><td>s</td><td>secondn (2 cijfers, bv. 00..59)</td></tr>
	</table>',

	'UI:Button:Remove' => 'Verwijder',
	'UI:AddAnExisting_Class' => 'Voeg objecten van type %1$s toe...',
	'UI:SelectionOf_Class' => 'Selectie van objecten van type %1$s',

	'UI:AboutBox' => 'Over '.ITOP_APPLICATION_SHORT.'...',
	'UI:About:Title' => 'Over '.ITOP_APPLICATION_SHORT,
	'UI:About:DataModel' => 'Datamodel',
	'UI:About:Support' => 'Support informatie',
	'UI:About:Licenses' => 'Licenties',
	'UI:About:InstallationOptions' => 'Installatie-opties',
	'UI:About:ManualExtensionSource' => 'Extensie',
	'UI:About:Extension_Version' => 'Versie: %1$s',
	'UI:About:RemoteExtensionSource' => 'Data',

	'UI:DisconnectedDlgMessage' => 'Je bent afgemeld. Je moet je opnieuw aanmelden om de toepassing verder te gebruiken.',
	'UI:DisconnectedDlgTitle' => 'Waarschuwing!',
	'UI:LoginAgain' => 'Opnieuw aanmelden',
	'UI:StayOnThePage' => 'Blijf op deze pagina',

	'ExcelExporter:ExportMenu' => 'Exporteer naar Excel',
	'ExcelExporter:ExportDialogTitle' => 'Exporteer als Excel-bestand',
	'ExcelExporter:ExportButton' => 'Exporteer',
	'ExcelExporter:DownloadButton' => 'Download %1$s',
	'ExcelExporter:RetrievingData' => 'Data aan het opvragen...',
	'ExcelExporter:BuildingExcelFile' => 'Excel-bestand aan het maken...',
	'ExcelExporter:Done' => 'Klaar.',
	'ExcelExport:AutoDownload' => 'Start het downloaden automatisch als de export klaar is.',
	'ExcelExport:PreparingExport' => 'Export aan het voorbereiden...',
	'ExcelExport:Statistics' => 'Statistieken',
	'portal:legacy_portal' => 'Portaal voor eindgebruikers',
	'portal:backoffice' => ITOP_APPLICATION_SHORT.' Back-Office User Interface',

	'UI:CurrentObjectIsLockedBy_User' => 'Het object is vergrendeld omdat het momenteel aangepast wordt door %1$s.',
	'UI:CurrentObjectIsLockedBy_User_Explanation' => 'Het object wordt aangepast door %1$s. Jouw wijzigingen kunnen niet opgeslagen worden omdat ze een conflict kunnen veroorzaken.',
	'UI:CurrentObjectLockExpired' => 'De vergrendeling om gelijktijdige wijzigingen te voorkomen, is opgeheven.',
	'UI:CurrentObjectLockExpired_Explanation' => 'De vergrendeling om gelijktijdige wijzigingen te voorkomen, is opgeheven. Je kan je wijzigingen niet meer opslaan aangezien andere gebruikers ondertussen dit object kunnen aanpassen.',
	'UI:ConcurrentLockKilled' => 'De vergrendeling voor gelijktijdige gebruikers is opgeheven.',
	'UI:Menu:KillConcurrentLock' => 'Verwijder de vergrendeling voor gelijktijdige gebruikers!',

	'UI:Menu:ExportPDF' => 'Exporteer als PDF...',
	'UI:Menu:PrintableVersion' => 'Printvriendelijke versie',

	'UI:BrowseInlineImages' => 'Afbeeldingen doorbladeren...',
	'UI:UploadInlineImageLegend' => 'Voeg een afbeelding toe',
	'UI:SelectInlineImageToUpload' => 'Selecteer een afbeelding om te uploaden',
	'UI:AvailableInlineImagesLegend' => 'Beschikbare afbeeldingen',
	'UI:NoInlineImage' => 'Er is geen afbeelding beschikbaar op de server. Gebruik de "Afbeeldingen doorbladeren..." knop hierboven om een afbeelding te kiezen op je toestel.',

	'UI:ToggleFullScreen' => 'Minimaliseren / Maximaliseren',
	'UI:Button:ResetImage' => 'Vorige afbeelding herstellen',
	'UI:Button:RemoveImage' => 'Afbeelding verwijderen',
	'UI:UploadNotSupportedInThisMode' => 'Het aanpassen van afbeeldingen of bestanden wordt niet ondersteund in deze mode.',

	'UI:Button:RemoveDocument' => 'Remove the document~~',

	// Search form
	'UI:Search:Toggle' => 'Inklappen / uitklappen',
	'UI:Search:AutoSubmit:DisabledHint' => 'Direct zoeken werd uitgeschakeld voor deze klasse.',
	'UI:Search:Obsolescence:DisabledHint' => '<span class="fas fa-eye-slash fa-1x"></span> Door jouw voorkeuren worden objecten met status "buiten dienst" verborgen',
	'UI:Search:NoAutoSubmit:ExplainText' => 'Voeg enkele criteria toe in het zoekveld of klik op de zoekknop om objecten te zien.',
	'UI:Search:Criterion:MoreMenu:AddCriteria' => 'Voeg nieuw criterium toe',
	// - Add new criteria button
	'UI:Search:AddCriteria:List:RecentlyUsed:Title' => 'Recent gebruikt',
	'UI:Search:AddCriteria:List:MostPopular:Title' => 'Meest populair',
	'UI:Search:AddCriteria:List:Others:Title' => 'Andere',
	'UI:Search:AddCriteria:List:RecentlyUsed:Placeholder' => 'Nog geen.',

	// - Criteria titles
	//   - Default widget
	'UI:Search:Criteria:Title:Default:Any' => '%1$s: alle',
	'UI:Search:Criteria:Title:Default:Empty' => '%1$s is leeg',
	'UI:Search:Criteria:Title:Default:NotEmpty' => '%1$s is niet leeg',
	'UI:Search:Criteria:Title:Default:Equals' => '%1$s gelijk aan %2$s',
	'UI:Search:Criteria:Title:Default:Contains' => '%1$s bevat %2$s',
	'UI:Search:Criteria:Title:Default:StartsWith' => '%1$s begint met %2$s',
	'UI:Search:Criteria:Title:Default:EndsWith' => '%1$s eindigt op %2$s',
	'UI:Search:Criteria:Title:Default:RegExp' => '%1$s bevat %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThan' => '%1$s > %2$s',
	'UI:Search:Criteria:Title:Default:GreaterThanOrEquals' => '%1$s >= %2$s',
	'UI:Search:Criteria:Title:Default:LessThan' => '%1$s < %2$s',
	'UI:Search:Criteria:Title:Default:LessThanOrEquals' => '%1$s <= %2$s',
	'UI:Search:Criteria:Title:Default:Different' => '%1$s ≠ %2$s',
	'UI:Search:Criteria:Title:Default:Between' => '%1$s tussen [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates' => '%1$s [%2$s]',
	'UI:Search:Criteria:Title:Default:BetweenDates:All' => '%1$s: Alles',
	'UI:Search:Criteria:Title:Default:BetweenDates:From' => '%1$s vanaf %2$s',
	'UI:Search:Criteria:Title:Default:BetweenDates:Until' => '%1$s tot %2$s',
	'UI:Search:Criteria:Title:Default:Between:All' => '%1$s: Any',
	'UI:Search:Criteria:Title:Default:Between:From' => '%1$s from %2$s',
	'UI:Search:Criteria:Title:Default:Between:Until' => '%1$s up to %2$s',
	//   - Numeric widget
	//   None yet
	//   - DateTime widget
	'UI:Search:Criteria:Title:DateTime:Between' => '%2$s <= 1$s <= %3$s',
	//   - Enum widget
	'UI:Search:Criteria:Title:Enum:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:Enum:In:Many' => '%1$s: %2$s en %3$s andere',
	'UI:Search:Criteria:Title:Enum:In:All' => '%1$s: Alle',
	//   - TagSet widget
	'UI:Search:Criteria:Title:TagSet:Matches' => '%1$s: %2$s',
	//   - External key widget
	'UI:Search:Criteria:Title:ExternalKey:Empty' => '%1$s is gedefinieerd',
	'UI:Search:Criteria:Title:ExternalKey:NotEmpty' => '%1$s is niet gedefinieerd',
	'UI:Search:Criteria:Title:ExternalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:ExternalKey:In:Many' => '%1$s: %2$s en %3$s andere',
	'UI:Search:Criteria:Title:ExternalKey:In:All' => '%1$s: Alle',
	//   - Hierarchical key widget
	'UI:Search:Criteria:Title:HierarchicalKey:Empty' => '%1$s is gedefinieerd',
	'UI:Search:Criteria:Title:HierarchicalKey:NotEmpty' => '%1$s is niet gedefinieerd',
	'UI:Search:Criteria:Title:HierarchicalKey:Equals' => '%1$s %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In' => '%1$s: %2$s',
	'UI:Search:Criteria:Title:HierarchicalKey:In:Many' => '%1$s: %2$s en %3$s andere',
	'UI:Search:Criteria:Title:HierarchicalKey:In:All' => '%1$s: Alle',

	// - Criteria operators
	//   - Default widget
	'UI:Search:Criteria:Operator:Default:Empty' => 'Is leeg',
	'UI:Search:Criteria:Operator:Default:NotEmpty' => 'Is niet leeg',
	'UI:Search:Criteria:Operator:Default:Equals' => 'Gelijk aan',
	'UI:Search:Criteria:Operator:Default:Between' => 'Tussen',
	//   - String widget
	'UI:Search:Criteria:Operator:String:Contains' => 'Bevat',
	'UI:Search:Criteria:Operator:String:StartsWith' => 'Begint met',
	'UI:Search:Criteria:Operator:String:EndsWith' => 'Eindigt in',
	'UI:Search:Criteria:Operator:String:RegExp' => 'Regex ',
	//   - Numeric widget
	'UI:Search:Criteria:Operator:Numeric:Equals' => 'Gelijk aan',  // => '=',
	'UI:Search:Criteria:Operator:Numeric:GreaterThan' => 'Groter',  // => '>',
	'UI:Search:Criteria:Operator:Numeric:GreaterThanOrEquals' => 'Groter of gelijk aan',  // > '>=',
	'UI:Search:Criteria:Operator:Numeric:LessThan' => 'Kleiner',  // => '<',
	'UI:Search:Criteria:Operator:Numeric:LessThanOrEquals' => 'Kleiner of gelijk aan',  // > '<=',
	'UI:Search:Criteria:Operator:Numeric:Different' => 'Verschillend van',  // => '≠',
	//   - Tag Set Widget
	'UI:Search:Criteria:Operator:TagSet:Matches' => 'Bevat',

	// - Other translations
	'UI:Search:Value:Filter:Placeholder' => 'Filter...',
	'UI:Search:Value:Search:Placeholder' => 'Zoek...',
	'UI:Search:Value:Autocomplete:StartTyping' => 'Begin te typen om mogelijke waarden te zien.',
	'UI:Search:Value:Autocomplete:Wait' => 'Even geduld...',
	'UI:Search:Value:Autocomplete:NoResult' => 'Geen resultaten.',
	'UI:Search:Value:Toggler:CheckAllNone' => 'Alles aan-/uitvinken',
	'UI:Search:Value:Toggler:CheckAllNoneFiltered' => 'Alle zichtbare aan-/uitvinken',

	// - Widget other translations
	'UI:Search:Criteria:Numeric:From' => 'Vanaf',
	'UI:Search:Criteria:Numeric:Until' => 'Tot',
	'UI:Search:Criteria:Numeric:PlaceholderFrom' => 'Alle',
	'UI:Search:Criteria:Numeric:PlaceholderUntil' => 'Alle',
	'UI:Search:Criteria:DateTime:From' => 'Vanaf',
	'UI:Search:Criteria:DateTime:FromTime' => 'Vanaf',
	'UI:Search:Criteria:DateTime:Until' => 'tot',
	'UI:Search:Criteria:DateTime:UntilTime' => 'tot',
	'UI:Search:Criteria:DateTime:PlaceholderFrom' => 'Elk tijdstip',
	'UI:Search:Criteria:DateTime:PlaceholderFromTime' => 'Elk tijdstip',
	'UI:Search:Criteria:DateTime:PlaceholderUntil' => 'Elk tijdstip',
	'UI:Search:Criteria:DateTime:PlaceholderUntilTime' => 'Elk tijdstip',
	'UI:Search:Criteria:HierarchicalKey:ChildrenIncluded:Hint' => 'Sub-objecten van geselecteerde objecten zullen mee opgenomen worden.',

	'UI:Search:Criteria:Raw:Filtered' => 'Gefilterd',
	'UI:Search:Criteria:Raw:FilteredOn' => 'Gefiltered op %1$s',
));

//
// Expression to Natural language
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Expression:Operator:AND' => ' EN ',
	'Expression:Operator:OR' => ' OF ',
	'Expression:Operator:=' => ': ',

	'Expression:Unit:Short:DAY' => 'd',
	'Expression:Unit:Short:WEEK' => 'w',
	'Expression:Unit:Short:MONTH' => 'm',
	'Expression:Unit:Short:YEAR' => 'j',

	'Expression:Unit:Long:DAY' => 'dag(en)',
	'Expression:Unit:Long:HOUR' => 'ure(n)',
	'Expression:Unit:Long:MINUTE' => 'minute(n)',

	'Expression:Verb:NOW' => 'nu',
	'Expression:Verb:ISNULL' => ': ongedefinieerd (NULL)',
));

//
// iTop Newsroom menu
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'UI:Newsroom:NoNewMessage' => 'Geen nieuw bericht',
	'UI:Newsroom:MarkAllAsRead' => 'Markeer alle berichten als gelezen',
	'UI:Newsroom:ViewAllMessages' => 'Bekijk alle berichten',
	'UI:Newsroom:Preferences' => 'Voorkeuren voor Newsroom',
	'UI:Newsroom:ConfigurationLink' => 'Configuratie',
	'UI:Newsroom:ResetCache' => 'Maak cache leeg',
	'UI:Newsroom:DisplayMessagesFor_Provider' => 'Bekijk berichten van %1$s',
	'UI:Newsroom:DisplayAtMost_X_Messages' => 'Toon maximaal %1$s berichten in het %2$s menu.',
));
