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
 * @author   Stephan Rosenke <stephan.rosenke@itomig.de>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Relation:impacts/Description' => 'Elemente betroffen von ...',
	'Relation:impacts/VerbUp' => 'Auswirkung ...',
	'Relation:impacts/VerbDown' => 'Elemente betroffen von ...',
	'Relation:depends on/Description' => 'Elemente, von denen dieses Element abhängt.',
	'Relation:depends on/VerbUp' => 'Hängt ab von ...',
	'Relation:depends on/VerbDown' => 'Wirkt auf ...',
));


// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//////////////////////////////////////////////////////////////////////
// Note: The classes have been grouped by categories: bizmodel
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

//
// Class: Organization
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Organization' => 'Organisation',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Name',
	'Class:Organization/Attribute:name+' => 'Gemeinsamer Name',
	'Class:Organization/Attribute:code' => 'Kennziffer',
	'Class:Organization/Attribute:code+' => 'Organisationskennziffer (D-U-N-S, Siret)',
	'Class:Organization/Attribute:status' => 'Status',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Organization/Attribute:parent_id' => 'Mutterfirma',
	'Class:Organization/Attribute:parent_id+' => 'Dachorganisation',
	'Class:Organization/Attribute:parent_name' => 'Name der Mutterfirma',
	'Class:Organization/Attribute:parent_name+' => 'Name der Mutterfirma',
));


//
// Class: Location
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Location' => 'Standort',
	'Class:Location+' => 'Jeder Typ von Standort: Region, Land, Stadt, Seite, Gebäude, Flur, Raum, Rack,...',
	'Class:Location/Attribute:name' => 'Name',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Status',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Aktiv',
	'Class:Location/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Location/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Location/Attribute:org_id' => 'Organisation',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Name der Organisation',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Adresse',
	'Class:Location/Attribute:address+' => 'Postanschrift',
	'Class:Location/Attribute:postal_code' => 'Postleitzahl',
	'Class:Location/Attribute:postal_code+' => 'Postleitzahl',
	'Class:Location/Attribute:city' => 'Stadt',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Land',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Standort der Mutterfirma',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Name der Mutterfirma',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Kontakte',
	'Class:Location/Attribute:contact_list+' => 'Kontakte, die auf dieser Seite aufgelistet sind',
	'Class:Location/Attribute:infra_list' => 'Infrastruktur',
	'Class:Location/Attribute:infra_list+' => 'CIs, die auf dieser Seite aufgelistet sind',
));
//
// Class: Group
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Group' => 'Gruppe',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Name',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Status',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementation',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementation',
	'Class:Group/Attribute:status/Value:obsolete' => 'Veraltet',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Veraltet',
	'Class:Group/Attribute:status/Value:production' => 'Produktion',
	'Class:Group/Attribute:status/Value:production+' => 'Produktion',
	'Class:Group/Attribute:org_id' => 'Organisation',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Name',
	'Class:Group/Attribute:owner_name+' => 'Allgemeiner Name',
	'Class:Group/Attribute:description' => 'Beschreibung',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Typ',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Muttergruppe',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Name',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'Verbundene CIs',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkGroupToCI' => 'Gruppe/CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Gruppe',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Name',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CI-Status',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Grund',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));

//
// Class: Contact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Contact' => 'Kontakt',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Name',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Status',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Aktiv',
	'Class:Contact/Attribute:status/Value:active+' => 'Aktiv',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inaktiv',
	'Class:Contact/Attribute:org_id' => 'Organisation',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organisation',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefonnummer',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Standort',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Standort',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CIs',
	'Class:Contact/Attribute:ci_list+' => 'CIs, die den Kontakt betreffen',
	'Class:Contact/Attribute:contract_list' => 'Verträge',
	'Class:Contact/Attribute:contract_list+' => 'Verträge, die diesen Kontakt betreffen',
	'Class:Contact/Attribute:service_list' => 'Services',
	'Class:Contact/Attribute:service_list+' => 'Services, die diesen Kontakt betreffen',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => 'Tickets, die diesen Kontakt betreffen',
	'Class:Contact/Attribute:team_list' => 'Teams',
	'Class:Contact/Attribute:team_list+' => 'Teams, denen dieser Kontakt zugehörig ist',
	'Class:Contact/Attribute:finalclass' => 'Typ',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Person' => 'Person',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Vorname',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'Mitarbeiter-ID/Nummer',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Team' => 'Team',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Mitglieder',
	'Class:Team/Attribute:member_list+' => 'Kontakte, die Teil des Teams sind',
));

//
// Class: lnkTeamToContact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkTeamToContact' => 'Team-Mitglieder',
	'Class:lnkTeamToContact+' => 'Mitglieder des Teams',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Team',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Mitglieder',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Standort',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Telefonnummer',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Rolle',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Document' => 'Dokument',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Name',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organisation',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => 'Organisationsname',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => 'Beschreibung',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Typ',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Vertrag',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Netzwerkkarte',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Präsentation',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Training',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'White Paper',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Arbeitsanweisungen',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Status',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Entwurf',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Veraltet',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Veröffentlicht',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CIs',
	'Class:Document/Attribute:ci_list+' => 'CIs, die sich auf dieses Dokument beziehen',
	'Class:Document/Attribute:contract_list' => 'Verträge',
	'Class:Document/Attribute:contract_list+' => 'Verträge, die sich auf dieses Dokument beziehen',
	'Class:Document/Attribute:service_list' => 'Services',
	'Class:Document/Attribute:service_list+' => 'Services, die sich auf dieses Dokument beziehen',
	'Class:Document/Attribute:ticket_list' => 'Tickets',
	'Class:Document/Attribute:ticket_list+' => 'Tickets, die sich auf dieses Dokument beziehen',
	'Class:Document:PreviewTab' => 'Vorschau',
));

//
// Class: ExternalDoc
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ExternalDoc' => 'Externes Dokument',
	'Class:ExternalDoc+' => 'Das Dokument ist auf einem anderen Webserver verfügbar',
	'Class:ExternalDoc/Attribute:url' => 'URL',
	'Class:ExternalDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Note' => 'Hinweis',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Text',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FileDoc' => 'Dokument (Datei)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Inhalt',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Licence' => 'Lizenz',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Anbieter',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'Besitzer',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => 'Name',
	'Class:Licence/Attribute:org_name+' => 'Allgemeiner Name',
	'Class:Licence/Attribute:product' => 'Produkt',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Name',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Starttermin',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Fristende',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Schlüssel',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Umfang',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Nutzungseinschränkungen',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Nutzung',
	'Class:Licence/Attribute:usage_list+' => 'Anwendungsinstanzen, die diese Lizenz benutzen',
));

//
// Class: Subnet
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Subnet' => 'Subnetz',
	'Class:Subnet+' => '',
	'Class:Subnet/Name' => '%1$s / %2$s',
	//'Class:Subnet/Attribute:name' => 'Name',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organisation',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Beschreibung',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'Subnetz-Maske',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Name',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Beschreibung',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Anwendungsbereich',
	'Class:Patch/Attribute:target_sw+' => 'Angestrebte Software (OS oder Anwendung)',
	'Class:Patch/Attribute:version' => 'Version',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Typ',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Anwendung',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Sicherheit',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Service Pack',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Geräte',
	'Class:Patch/Attribute:ci_list+' => 'Geräte, auf denen der Patch installiert ist',
));

//
// Class: Software
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Name',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Beschreibung',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Installationen',
	'Class:Software/Attribute:instance_list+' => 'Instanzen dieser Software',
	'Class:Software/Attribute:finalclass' => 'Typ',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Application' => 'Anwendung',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Name',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Beschreibung',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Installationen',
	'Class:Application/Attribute:instance_list+' => 'Instanzen dieser Anwendung',
));

//
// Class: DBServer
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DBServer' => 'Datenbank (DBMS)',
	'Class:DBServer+' => 'Datenbank-Software',
	'Class:DBServer/Attribute:instance_list' => 'Installationen',
	'Class:DBServer/Attribute:instance_list+' => 'Installationen des Datenbankservers',
));

//
// Class: lnkPatchToCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkPatchToCI' => 'Patch-Verwendung',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CI-Status',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:FunctionalCI' => 'Funktionales CI',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Name',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Status',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Implementierung',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Veraltet',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Produktion',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organisation',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Organisation',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Business criticality',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Hoch',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Niedrig',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Medium',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'Kontakte',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Kontakte für dieses CI',
	'Class:FunctionalCI/Attribute:document_list' => 'Dokumente',
	'Class:FunctionalCI/Attribute:document_list+' => 'Dokumentation für dieses CI',
	'Class:FunctionalCI/Attribute:solution_list' => 'Anwendungslösungen',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Anwendungslösungen, die dieses CI benutzen',
	'Class:FunctionalCI/Attribute:contract_list' => 'Verträge',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Verträge, die dieses CI unterstützen',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Tickets, die das CI betreffen',
	'Class:FunctionalCI/Attribute:finalclass' => 'Typ',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:SoftwareInstance' => 'Software-Instanz',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Name' => '%1$s - %2$s',
	'Class:SoftwareInstance/Attribute:device_id' => 'Gerät',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Gerät',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Lizenz',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Lizenz',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_id' => 'Software',
	'Class:SoftwareInstance/Attribute:software_id+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Version',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Beschreibung',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ApplicationInstance' => 'Anwendungsinstanz',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Name' => '%1$s - %2$s',
	'Class:ApplicationInstance/Attribute:software_id' => 'Software',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => 'Name',
	'Class:ApplicationInstance/Attribute:software_name+' => '',

));

//
// Class: DBServerInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DBServerInstance' => 'Datenbank-Server-Instanz',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Name' => '%1$s - %2$s',
	'Class:DBServerInstance/Attribute:software_id' => 'Software',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'Software Name',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Datenbanken',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Datenbanken-Quellen',
));

//
// Class: DatabaseInstance
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:DatabaseInstance' => 'Datenbank-Instanz',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Name' => '%1$s - %2$s',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Datenbank-Server',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Datenbank-Version',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Beschreibung',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ApplicationSolution' => 'Anwendungslösung',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Beschreibung',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'CIs, aus der sich die Lösung zusammensetzt',
	'Class:ApplicationSolution/Attribute:process_list' => 'Business-Prozesse',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Business-Prozesse, die auf dieser Lösung basieren',
));

//
// Class: BusinessProcess
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:BusinessProcess' => 'Business-Prozess',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Beschreibung',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Anwendungslösungen',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Anwendungslösungen, auf die der Prozess angewiesen ist',
));

//
// Class: ConnectableCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:ConnectableCI' => 'Verknüpfbares CI',
	'Class:ConnectableCI+' => 'Physisches CI',
	'Class:ConnectableCI/Attribute:brand' => 'Hersteller',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Modell',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Seriennummer',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Referenzierter Asset',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NetworkInterface' => 'Netzwerk-Interface',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Name' => '%1$s - %2$s',
	'Class:NetworkInterface/Attribute:device_id' => 'Gerät',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Gerät',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Logical Type',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Backup',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Logical',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Port',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primary',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secondary',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Physical Type',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'IP-Adresse',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'Subnetz-Maske',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'MAC-Adresse',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Speed',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'unbekannt',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Angeschlossen an',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Angeschlossenes Interface',
	'Class:NetworkInterface/Attribute:connected_name' => 'Angeschlossen an',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Angeschlosses Gerät',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Link type',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
));

//
// Class: Device
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Device' => 'Gerät',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Netzwerk-Interfaces',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Festplatte',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OS-Familie',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OS-Version',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Anwendungen',
	'Class:PC/Attribute:application_list+' => 'Auf diesem PC installierte Anwendungen',
	'Class:PC/Attribute:patch_list' => 'Patches',
	'Class:PC/Attribute:patch_list+' => 'Auf diesem PC installierte Patches',
));

//
// Class: MobileCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:MobileCI' => 'Mobile CI',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:MobilePhone' => 'Mobiltelefon',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Telefonnummer',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware-PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:InfrastructureCI' => 'Infrastruktur-CI',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Beschreibung',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Standort',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Standort',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Details zum Standort',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'Management-IP',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Default Gateway',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:NetworkDevice' => 'Netzwerk-Gerät',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Typ',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'WAN Accelerator',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator+' => '',
	'Class:NetworkDevice/Attribute:type/Value:firewall' => 'Firewall',
	'Class:NetworkDevice/Attribute:type/Value:firewall+' => '',
	'Class:NetworkDevice/Attribute:type/Value:hub' => 'Hub',
	'Class:NetworkDevice/Attribute:type/Value:hub+' => '',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer' => 'Load Balancer',
	'Class:NetworkDevice/Attribute:type/Value:loadbalancer+' => '',
	'Class:NetworkDevice/Attribute:type/Value:router' => 'Router',
	'Class:NetworkDevice/Attribute:type/Value:router+' => '',
	'Class:NetworkDevice/Attribute:type/Value:switch' => 'Switch',
	'Class:NetworkDevice/Attribute:type/Value:switch+' => '',
	'Class:NetworkDevice/Attribute:ios_version' => 'IOS Version',
	'Class:NetworkDevice/Attribute:ios_version+' => '',
	'Class:NetworkDevice/Attribute:ram' => 'RAM',
	'Class:NetworkDevice/Attribute:ram+' => '',
	'Class:NetworkDevice/Attribute:snmp_read' => 'SNMP Read',
	'Class:NetworkDevice/Attribute:snmp_read+' => '',
	'Class:NetworkDevice/Attribute:snmp_write' => 'SNMP Write',
	'Class:NetworkDevice/Attribute:snmp_write+' => '',
));

//
// Class: Server
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Festplatte',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'OS-Familie',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'OS-Version',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Anwendungen',
	'Class:Server/Attribute:application_list+' => 'Auf diesem Server installierte Anwendungen',
	'Class:Server/Attribute:patch_list' => 'Patches',
	'Class:Server/Attribute:patch_list+' => 'Auf diesem Server installierte Patches',
));

//
// Class: Printer
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:Printer' => 'Drucker',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Typ',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Drucker',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Technologie',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Tintenstrahldrucker',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laserdrucker',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkCIToDoc' => 'Dokumentation/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CI-Status',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Dokument',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Dokument',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Dokumententyp',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Dokumentenstatus',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkCIToContact' => 'CI/Contact',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CI-Status',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Kontakt',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Kontakt',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'Kontakt-Email',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Rolle',
	'Class:lnkCIToContact/Attribute:role+' => 'Rolle des Kontaktes diesen CI betreffend',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkSolutionToCI' => 'CI/Lösungen',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Anwendungslösung',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Anwendungslösung',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CI-Status',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utility',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utility des CI der Lösung',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:lnkProcessToSolution' => 'Business-Prozess/Lösung',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Anwendungslösung',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Anwendungslösung',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Prozess',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Prozess',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Grund',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Mehr Informationen auf dem Link zwischen Prozess und der Lösung',
));



//
// Class extensions
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
'Class:Subnet/Tab:IPUsage' => 'IP-Adressverwendung',
'Class:Subnet/Tab:IPUsage-explain' => 'Interfaces haben eine IP-Adresse aus dem Bereich von <em>%1$s</em> bis <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'Freie IPs',
'Class:Subnet/Tab:FreeIPs-count' => 'Freie IPs: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Auszug zehn freier IP-Adressen',
));

//
// Application Menu
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
'Menu:Catalogs' => 'Kataloge',
'Menu:Catalogs+' => 'Datentypen',
'Menu:Audit' => 'Audit',
'Menu:Audit+' => 'Audit',
'Menu:Organization' => 'Organisationen',
'Menu:Organization+' => 'Alle Organisationen',
'Menu:Application' => 'Anwendungen',
'Menu:Application+' => 'Alle Anwendungen',
'Menu:DBServer' => 'Datenbank-Server',
'Menu:DBServer+' => 'Datenbank-Server',
'Menu:Audit' => 'Audit',
'Menu:ConfigManagement' => 'Configuration Management',
'Menu:ConfigManagement+' => 'Configuration Management',
'Menu:ConfigManagementOverview' => 'Übersicht',
'Menu:ConfigManagementOverview+' => 'Übersicht',
'Menu:Contact' => 'Kontakte',
'Menu:Contact+' => 'Kontakte',
'Menu:Person' => 'Personen',
'Menu:Person+' => 'Alle Personen',
'Menu:Team' => 'Teams',
'Menu:Team+' => 'Alle Teams',
'Menu:Document' => 'Dokumente',
'Menu:Document+' => 'Alle Dokumente',
'Menu:Location' => 'Standorte',
'Menu:Location+' => 'Alle Standorte',
'Menu:ConfigManagementCI' => 'Configuration Items',
'Menu:ConfigManagementCI+' => 'Configuration Items',
'Menu:BusinessProcess' => 'Business-Prozesse',
'Menu:BusinessProcess+' => 'Alle Business-Prozesse',
'Menu:ApplicationSolution' => 'Anwendungslösungen',
'Menu:ApplicationSolution+' => 'Alle Anwendungslösungen',
'Menu:ConfigManagementSoftware' => 'Anwendungs-Management',
'Menu:Licence' => 'Lizenzen',
'Menu:Licence+' => 'Alle Lizenzen',
'Menu:Patch' => 'Patches',
'Menu:Patch+' => 'Alle Patches',
'Menu:ApplicationInstance' => 'Installierte Software',
'Menu:ApplicationInstance+' => 'Anwendungen und Datenbank-Server',
'Menu:ConfigManagementHardware' => 'Infrastruktur-Management',
'Menu:Subnet' => 'Subnetze',
'Menu:Subnet+' => 'Alle Subnetze',
'Menu:NetworkDevice' => 'Netzwerkgeräte',
'Menu:NetworkDevice+' => 'Alle Netzwerkgeräte',
'Menu:Server' => 'Server',
'Menu:Server+' => 'Alle Server',
'Menu:Printer' => 'Drucker',
'Menu:Printer+' => 'Alle Drucker',
'Menu:MobilePhone' => 'Mobiltelefone',
'Menu:MobilePhone+' => 'Alle Mobiltelefone',
'Menu:PC' => 'Rechner (PC)',
'Menu:PC+' => 'Alle Rechner (PC)',
'Menu:NewContact' => 'Neuer Kontakt',
'Menu:NewContact+' => 'Neuer Kontakt',
'Menu:SearchContacts' => 'Nach Kontakten suchen',
'Menu:SearchContacts+' => 'Nach Kontakten suchen',
'Menu:NewCI' => 'Neues CI',
'Menu:NewCI+' => 'Neues CI',
'Menu:SearchCIs' => 'Nach CIs suchen',
'Menu:SearchCIs+' => 'Nach CIs suchen',
'Menu:ConfigManagement:Devices' => 'Geräte',
'Menu:ConfigManagement:AllDevices' => 'Anzahl der Geräte: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'Software und Anwendungen',
'Menu:ConfigManagement:Misc' => 'Diverses',
'Menu:Group' => 'Gruppen von CIs',
'Menu:Group+' => 'Gruppen von CIs',
'Menu:ConfigManagement:Shortcuts' => 'Shortcuts',
'Menu:ConfigManagement:AllContacts' => 'Alle Kontakte: %1$d',
));
?>
