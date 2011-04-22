<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

//////////////////////////////////////////////////////////////////////
// Relations
//////////////////////////////////////////////////////////////////////
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Relation:impacts/Description' => 'Elementi impattati da...',
	'Relation:impacts/VerbUp' => 'Impatto...',
	'Relation:impacts/VerbDown' => 'Elementi impattati da...',
	'Relation:depends on/Description' => 'Elementi di questo elemento dipende da',
	'Relation:depends on/VerbUp' => 'Dipende da...',
	'Relation:depends on/VerbDown' => 'Impatto...',
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

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:Organization' => 'Organizzazione',
	'Class:Organization+' => '',
	'Class:Organization/Attribute:name' => 'Cognome',
	'Class:Organization/Attribute:name+' => 'Nome',
	'Class:Organization/Attribute:code' => 'Codice',
	'Class:Organization/Attribute:code+' => 'Codice dell\'organizzazione (Siret, DUNS,...)',
	'Class:Organization/Attribute:status' => 'Stato',
	'Class:Organization/Attribute:status+' => '',
	'Class:Organization/Attribute:status/Value:active' => 'Attivo',
	'Class:Organization/Attribute:status/Value:active+' => 'Attivo',
	'Class:Organization/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Organization/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Organization/Attribute:parent_id' => 'Parent',
	'Class:Organization/Attribute:parent_id+' => 'Parent organization',
	'Class:Organization/Attribute:parent_name' => 'Parent name',
	'Class:Organization/Attribute:parent_name+' => 'Name of the parent organization',
));


//
// Class: Location
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Location' => 'Posizione',
	'Class:Location+' => 'Qualsiasi tipo di posizione: Regione, Paese, Città, Sito, Edificio, Piano, Stanza, Rack,,...',
	'Class:Location/Attribute:name' => 'Nome',
	'Class:Location/Attribute:name+' => '',
	'Class:Location/Attribute:status' => 'Stato',
	'Class:Location/Attribute:status+' => '',
	'Class:Location/Attribute:status/Value:active' => 'Attivo',
	'Class:Location/Attribute:status/Value:active+' => 'Attivo',
	'Class:Location/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Location/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Location/Attribute:org_id' => 'Organizzazione proprietaria',
	'Class:Location/Attribute:org_id+' => '',
	'Class:Location/Attribute:org_name' => 'Nome dell\'organizzazione',
	'Class:Location/Attribute:org_name+' => '',
	'Class:Location/Attribute:address' => 'Indirizzo',
	'Class:Location/Attribute:address+' => 'Indirizzo postale',
	'Class:Location/Attribute:postal_code' => 'Codice avviamento postale',
	'Class:Location/Attribute:postal_code+' => 'CAP/codice avviamento postale',
	'Class:Location/Attribute:city' => 'Città',
	'Class:Location/Attribute:city+' => '',
	'Class:Location/Attribute:country' => 'Paese',
	'Class:Location/Attribute:country+' => '',
	'Class:Location/Attribute:parent_id' => 'Parent location',
	'Class:Location/Attribute:parent_id+' => '',
	'Class:Location/Attribute:parent_name' => 'Parent name',
	'Class:Location/Attribute:parent_name+' => '',
	'Class:Location/Attribute:contact_list' => 'Contatti',
	'Class:Location/Attribute:contact_list+' => 'Contatti posizionati su questo sito',
	'Class:Location/Attribute:infra_list' => 'Infrastrutture',
	'Class:Location/Attribute:infra_list+' => 'CIs posizionati su questo sito',
));
//
// Class: Group
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Group' => 'Groppo',
	'Class:Group+' => '',
	'Class:Group/Attribute:name' => 'Nome',
	'Class:Group/Attribute:name+' => '',
	'Class:Group/Attribute:status' => 'Stato',
	'Class:Group/Attribute:status+' => '',
	'Class:Group/Attribute:status/Value:implementation' => 'Implementazione',
	'Class:Group/Attribute:status/Value:implementation+' => 'Implementazione',
	'Class:Group/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:obsolete+' => 'Obsoleto',
	'Class:Group/Attribute:status/Value:production' => 'Produzione',
	'Class:Group/Attribute:status/Value:production+' => 'Produzione',
	'Class:Group/Attribute:org_id' => 'Organizazione',
	'Class:Group/Attribute:org_id+' => '',
	'Class:Group/Attribute:owner_name' => 'Cognome',
	'Class:Group/Attribute:owner_name+' => 'Nome',
	'Class:Group/Attribute:description' => 'Descrizione',
	'Class:Group/Attribute:description+' => '',
	'Class:Group/Attribute:type' => 'Tipo',
	'Class:Group/Attribute:type+' => '',
	'Class:Group/Attribute:parent_id' => 'Parent Group',
	'Class:Group/Attribute:parent_id+' => '',
	'Class:Group/Attribute:parent_name' => 'Nome',
	'Class:Group/Attribute:parent_name+' => '',
	'Class:Group/Attribute:ci_list' => 'CIs collegati',
	'Class:Group/Attribute:ci_list+' => '',
));

//
// Class: lnkGroupToCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkGroupToCI' => 'Groppo / CI',
	'Class:lnkGroupToCI+' => '',
	'Class:lnkGroupToCI/Attribute:group_id' => 'Groppo',
	'Class:lnkGroupToCI/Attribute:group_id+' => '',
	'Class:lnkGroupToCI/Attribute:group_name' => 'Nome',
	'Class:lnkGroupToCI/Attribute:group_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_id' => 'CI',
	'Class:lnkGroupToCI/Attribute:ci_id+' => '',
	'Class:lnkGroupToCI/Attribute:ci_name' => 'Nome',
	'Class:lnkGroupToCI/Attribute:ci_name+' => '',
	'Class:lnkGroupToCI/Attribute:ci_status' => 'CI Stato',
	'Class:lnkGroupToCI/Attribute:ci_status+' => '',
	'Class:lnkGroupToCI/Attribute:reason' => 'Motivo',
	'Class:lnkGroupToCI/Attribute:reason+' => '',
));


//
// Class: Contact
//

Dict::Add('IT IT', 'Italiano', 'Italiano', array(
	'Class:Contact' => 'Contatto',
	'Class:Contact+' => '',
	'Class:Contact/Attribute:name' => 'Nome',
	'Class:Contact/Attribute:name+' => '',
	'Class:Contact/Attribute:status' => 'Stato',
	'Class:Contact/Attribute:status+' => '',
	'Class:Contact/Attribute:status/Value:active' => 'Attivo',
	'Class:Contact/Attribute:status/Value:active+' => 'Attivo',
	'Class:Contact/Attribute:status/Value:inactive' => 'Inattivo',
	'Class:Contact/Attribute:status/Value:inactive+' => 'Inattivo',
	'Class:Contact/Attribute:org_id' => 'Organizzazione',
	'Class:Contact/Attribute:org_id+' => '',
	'Class:Contact/Attribute:org_name' => 'Organizzazione',
	'Class:Contact/Attribute:org_name+' => '',
	'Class:Contact/Attribute:email' => 'Email',
	'Class:Contact/Attribute:email+' => '',
	'Class:Contact/Attribute:phone' => 'Telefono',
	'Class:Contact/Attribute:phone+' => '',
	'Class:Contact/Attribute:location_id' => 'Posizione',
	'Class:Contact/Attribute:location_id+' => '',
	'Class:Contact/Attribute:location_name' => 'Posizione',
	'Class:Contact/Attribute:location_name+' => '',
	'Class:Contact/Attribute:ci_list' => 'CIs',
	'Class:Contact/Attribute:ci_list+' => 'CIs relativi al contatto',
	'Class:Contact/Attribute:contract_list' => 'Contratti',
	'Class:Contact/Attribute:contract_list+' => 'Contratti relativi al contatto',
	'Class:Contact/Attribute:service_list' => 'Servizi',
	'Class:Contact/Attribute:service_list+' => 'Servizi relativi al contatto',
	'Class:Contact/Attribute:ticket_list' => 'Tickets',
	'Class:Contact/Attribute:ticket_list+' => 'Tickets relativi al contatto',
	'Class:Contact/Attribute:team_list' => 'Squadre',
	'Class:Contact/Attribute:team_list+' => 'Squadre di appartenenza',
	'Class:Contact/Attribute:finalclass' => 'Tipo',
	'Class:Contact/Attribute:finalclass+' => '',
));

//
// Class: Person
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Person' => 'Persona',
	'Class:Person+' => '',
	'Class:Person/Attribute:first_name' => 'Nome',
	'Class:Person/Attribute:first_name+' => '',
	'Class:Person/Attribute:employee_id' => 'ID dipendente',
	'Class:Person/Attribute:employee_id+' => '',
));

//
// Class: Team
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Team' => 'Squadra',
	'Class:Team+' => '',
	'Class:Team/Attribute:member_list' => 'Membri',
	'Class:Team/Attribute:member_list+' => 'Contatti che appartengono a questa Squadra',
));

//
// Class: lnkTeamToContact
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkTeamToContact' => 'Membri della squadra',
	'Class:lnkTeamToContact+' => 'membri della squadra',
	'Class:lnkTeamToContact/Attribute:team_id' => 'Squadra',
	'Class:lnkTeamToContact/Attribute:team_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_id' => 'Membri',
	'Class:lnkTeamToContact/Attribute:contact_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_location_id' => 'Posizione',
	'Class:lnkTeamToContact/Attribute:contact_location_id+' => '',
	'Class:lnkTeamToContact/Attribute:contact_email' => 'Email',
	'Class:lnkTeamToContact/Attribute:contact_email+' => '',
	'Class:lnkTeamToContact/Attribute:contact_phone' => 'Telefono',
	'Class:lnkTeamToContact/Attribute:contact_phone+' => '',
	'Class:lnkTeamToContact/Attribute:role' => 'Ruolo',
	'Class:lnkTeamToContact/Attribute:role+' => '',
));

//
// Class: Document
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Document' => 'Documento',
	'Class:Document+' => '',
	'Class:Document/Attribute:name' => 'Nome',
	'Class:Document/Attribute:name+' => '',
	'Class:Document/Attribute:org_id' => 'Organizzazione',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:org_name' => 'Nome dell\'organizzazione',
	'Class:Document/Attribute:org_name+' => '',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:description' => 'Descrizione',
	'Class:Document/Attribute:description+' => '',
	'Class:Document/Attribute:type' => 'Tipo',
	'Class:Document/Attribute:type+' => '',
	'Class:Document/Attribute:type/Value:contract' => 'Contratto',
	'Class:Document/Attribute:type/Value:contract+' => '',
	'Class:Document/Attribute:type/Value:networkmap' => 'Network Map',
	'Class:Document/Attribute:type/Value:networkmap+' => '',
	'Class:Document/Attribute:type/Value:presentation' => 'Presentazine',
	'Class:Document/Attribute:type/Value:presentation+' => '',
	'Class:Document/Attribute:type/Value:training' => 'Formazione',
	'Class:Document/Attribute:type/Value:training+' => '',
	'Class:Document/Attribute:type/Value:whitePaper' => 'Foglio bianco',
	'Class:Document/Attribute:type/Value:whitePaper+' => '',
	'Class:Document/Attribute:type/Value:workinginstructions' => 'Istruzioni di lavoro',
	'Class:Document/Attribute:type/Value:workinginstructions+' => '',
	'Class:Document/Attribute:status' => 'Stato',
	'Class:Document/Attribute:status+' => '',
	'Class:Document/Attribute:status/Value:draft' => 'Draft',
	'Class:Document/Attribute:status/Value:draft+' => '',
	'Class:Document/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:Document/Attribute:status/Value:obsolete+' => '',
	'Class:Document/Attribute:status/Value:published' => 'Pubblicato',
	'Class:Document/Attribute:status/Value:published+' => '',
	'Class:Document/Attribute:ci_list' => 'CIs',
	'Class:Document/Attribute:ci_list+' => 'CIs relativo a questo documento',
	'Class:Document/Attribute:contract_list' => 'Contratti',
	'Class:Document/Attribute:contract_list+' => 'Contratti relativi a questo documento',
	'Class:Document/Attribute:service_list' => 'Servizi',
	'Class:Document/Attribute:service_list+' => 'Servizi relativi a questo documento',
	'Class:Document/Attribute:ticket_list' => 'Tickets',
	'Class:Document/Attribute:ticket_list+' => 'Tickets relativi a questo documento',
	'Class:Document:PreviewTab' => 'Anteprima',
));

//
// Class: WebDoc
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:WebDoc' => 'Documento Web',
	'Class:WebDoc+' => 'Documento disponibile su un altro server web',
	'Class:WebDoc/Attribute:url' => 'Url',
	'Class:WebDoc/Attribute:url+' => '',
));

//
// Class: Note
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Note' => 'Note',
	'Class:Note+' => '',
	'Class:Note/Attribute:note' => 'Testo',
	'Class:Note/Attribute:note+' => '',
));

//
// Class: FileDoc
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:FileDoc' => 'Documento (file)',
	'Class:FileDoc+' => '',
	'Class:FileDoc/Attribute:contents' => 'Contenuti',
	'Class:FileDoc/Attribute:contents+' => '',
));

//
// Class: Licence
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Licence' => 'Licenza',
	'Class:Licence+' => '',
	'Class:Licence/Attribute:provider' => 'Provider',
	'Class:Licence/Attribute:provider+' => '',
	'Class:Licence/Attribute:org_id' => 'Proprietario',
	'Class:Licence/Attribute:org_id+' => '',
	'Class:Licence/Attribute:org_name' => 'Cognome',
	'Class:Licence/Attribute:org_name+' => 'Nome',
	'Class:Licence/Attribute:product' => 'Prodotto',
	'Class:Licence/Attribute:product+' => '',
	'Class:Licence/Attribute:name' => 'Nome',
	'Class:Licence/Attribute:name+' => '',
	'Class:Licence/Attribute:start' => 'Data di inizio',
	'Class:Licence/Attribute:start+' => '',
	'Class:Licence/Attribute:end' => 'Data di fine',
	'Class:Licence/Attribute:end+' => '',
	'Class:Licence/Attribute:licence_key' => 'Key',
	'Class:Licence/Attribute:licence_key+' => '',
	'Class:Licence/Attribute:scope' => 'Scopo',
	'Class:Licence/Attribute:scope+' => '',
	'Class:Licence/Attribute:usage_limit' => 'Limiti d\'uso',
	'Class:Licence/Attribute:usage_limit+' => '',
	'Class:Licence/Attribute:usage_list' => 'Uso',
	'Class:Licence/Attribute:usage_list+' => 'Istanze di applicazioni che usano questa licenza',
));


//
// Class: Subnet
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Subnet' => 'Subnet',
	'Class:Subnet+' => '',
	//'Class:Subnet/Attribute:name' => 'Nome',
	//'Class:Subnet/Attribute:name+' => '',
	'Class:Subnet/Attribute:org_id' => 'Organizzazione proprietaria',
	'Class:Subnet/Attribute:org_id+' => '',
	'Class:Subnet/Attribute:description' => 'Descrizione',
	'Class:Subnet/Attribute:description+' => '',
	'Class:Subnet/Attribute:ip' => 'IP',
	'Class:Subnet/Attribute:ip+' => '',
	'Class:Subnet/Attribute:ip_mask' => 'IP Mask',
	'Class:Subnet/Attribute:ip_mask+' => '',
));

//
// Class: Patch
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Patch' => 'Patch',
	'Class:Patch+' => '',
	'Class:Patch/Attribute:name' => 'Nome',
	'Class:Patch/Attribute:name+' => '',
	'Class:Patch/Attribute:description' => 'Descrizione',
	'Class:Patch/Attribute:description+' => '',
	'Class:Patch/Attribute:target_sw' => 'Scopo dell\'applicazione',
	'Class:Patch/Attribute:target_sw+' => 'Software bersaglio (OS or applicazione)',
	'Class:Patch/Attribute:version' => 'Versione',
	'Class:Patch/Attribute:version+' => '',
	'Class:Patch/Attribute:type' => 'Tipo',
	'Class:Patch/Attribute:type+' => '',
	'Class:Patch/Attribute:type/Value:application' => 'Applicazione',
	'Class:Patch/Attribute:type/Value:application+' => '',
	'Class:Patch/Attribute:type/Value:os' => 'OS',
	'Class:Patch/Attribute:type/Value:os+' => '',
	'Class:Patch/Attribute:type/Value:security' => 'Securirezza',
	'Class:Patch/Attribute:type/Value:security+' => '',
	'Class:Patch/Attribute:type/Value:servicepack' => 'Service Pack',
	'Class:Patch/Attribute:type/Value:servicepack+' => '',
	'Class:Patch/Attribute:ci_list' => 'Dispositivi',
	'Class:Patch/Attribute:ci_list+' => 'Dispositivi dove la patch è installata',
));

//
// Class: Software
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Software' => 'Software',
	'Class:Software+' => '',
	'Class:Software/Attribute:name' => 'Nome',
	'Class:Software/Attribute:name+' => '',
	'Class:Software/Attribute:description' => 'Descrizione',
	'Class:Software/Attribute:description+' => '',
	'Class:Software/Attribute:instance_list' => 'Installazioni',
	'Class:Software/Attribute:instance_list+' => 'Istanze di questo software',
	'Class:Software/Attribute:finalclass' => 'Tipo',
	'Class:Software/Attribute:finalclass+' => '',
));

//
// Class: Application
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Application' => 'Applicazione',
	'Class:Application+' => '',
	'Class:Application/Attribute:name' => 'Nome',
	'Class:Application/Attribute:name+' => '',
	'Class:Application/Attribute:description' => 'Descrizione',
	'Class:Application/Attribute:description+' => '',
	'Class:Application/Attribute:instance_list' => 'Installazioni',
	'Class:Application/Attribute:instance_list+' => 'Istanze di questa applicazione',
));

//
// Class: DBServer
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DBServer' => 'Database',
	'Class:DBServer+' => 'Database server SW',
	'Class:DBServer/Attribute:instance_list' => 'Installazioni',
	'Class:DBServer/Attribute:instance_list+' => 'Istanze di questo database server',
));

//
// Class: lnkPatchToCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkPatchToCI' => 'Utilizzo di Patch',
	'Class:lnkPatchToCI+' => '',
	'Class:lnkPatchToCI/Attribute:patch_id' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_id+' => '',
	'Class:lnkPatchToCI/Attribute:patch_name' => 'Patch',
	'Class:lnkPatchToCI/Attribute:patch_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_id' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_id+' => '',
	'Class:lnkPatchToCI/Attribute:ci_name' => 'CI',
	'Class:lnkPatchToCI/Attribute:ci_name+' => '',
	'Class:lnkPatchToCI/Attribute:ci_status' => 'CI Stato',
	'Class:lnkPatchToCI/Attribute:ci_status+' => '',
));

//
// Class: FunctionalCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:FunctionalCI' => 'CI Funzionale',
	'Class:FunctionalCI+' => '',
	'Class:FunctionalCI/Attribute:name' => 'Nome',
	'Class:FunctionalCI/Attribute:name+' => '',
	'Class:FunctionalCI/Attribute:status' => 'Stato',
	'Class:FunctionalCI/Attribute:status+' => '',
	'Class:FunctionalCI/Attribute:status/Value:implementation' => 'Implementazione',
	'Class:FunctionalCI/Attribute:status/Value:implementation+' => '',
	'Class:FunctionalCI/Attribute:status/Value:obsolete' => 'Obsoleto',
	'Class:FunctionalCI/Attribute:status/Value:obsolete+' => '',
	'Class:FunctionalCI/Attribute:status/Value:production' => 'Produzione',
	'Class:FunctionalCI/Attribute:status/Value:production+' => '',
	'Class:FunctionalCI/Attribute:org_id' => 'Organizzazione proprietaria',
	'Class:FunctionalCI/Attribute:org_id+' => '',
	'Class:FunctionalCI/Attribute:owner_name' => 'Organizzazione proprietaria',
	'Class:FunctionalCI/Attribute:owner_name+' => '',
	'Class:FunctionalCI/Attribute:importance' => 'Criticità di business',
	'Class:FunctionalCI/Attribute:importance+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:high' => 'Alta',
	'Class:FunctionalCI/Attribute:importance/Value:high+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:low' => 'Bassa',
	'Class:FunctionalCI/Attribute:importance/Value:low+' => '',
	'Class:FunctionalCI/Attribute:importance/Value:medium' => 'Media',
	'Class:FunctionalCI/Attribute:importance/Value:medium+' => '',
	'Class:FunctionalCI/Attribute:contact_list' => 'Contatti',
	'Class:FunctionalCI/Attribute:contact_list+' => 'Contatti per questo CI',
	'Class:FunctionalCI/Attribute:document_list' => 'Documenti',
	'Class:FunctionalCI/Attribute:document_list+' => 'Documentazione per questo CI',
	'Class:FunctionalCI/Attribute:solution_list' => 'Soluzioni applicative',
	'Class:FunctionalCI/Attribute:solution_list+' => 'Soluzioni applicative che utilizzano questo CI',
	'Class:FunctionalCI/Attribute:contract_list' => 'Contratti',
	'Class:FunctionalCI/Attribute:contract_list+' => 'Contratti a sostegno di questo CI',
	'Class:FunctionalCI/Attribute:ticket_list' => 'Tickets',
	'Class:FunctionalCI/Attribute:ticket_list+' => 'Tickets relativi al CI',
	'Class:FunctionalCI/Attribute:finalclass' => 'Tipo',
	'Class:FunctionalCI/Attribute:finalclass+' => '',
));

//
// Class: SoftwareInstance
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:SoftwareInstance' => 'Istanza software',
	'Class:SoftwareInstance+' => '',
	'Class:SoftwareInstance/Attribute:device_id' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_id+' => '',
	'Class:SoftwareInstance/Attribute:device_name' => 'Dispositivo',
	'Class:SoftwareInstance/Attribute:device_name+' => '',
	'Class:SoftwareInstance/Attribute:licence_id' => 'Licenza',
	'Class:SoftwareInstance/Attribute:licence_id+' => '',
	'Class:SoftwareInstance/Attribute:licence_name' => 'Licenza',
	'Class:SoftwareInstance/Attribute:licence_name+' => '',
	'Class:SoftwareInstance/Attribute:software_name' => 'Software',
	'Class:SoftwareInstance/Attribute:software_name+' => '',
	'Class:SoftwareInstance/Attribute:version' => 'Versione',
	'Class:SoftwareInstance/Attribute:version+' => '',
	'Class:SoftwareInstance/Attribute:description' => 'Descrizione',
	'Class:SoftwareInstance/Attribute:description+' => '',
));

//
// Class: ApplicationInstance
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ApplicationInstance' => 'Istanza dell\'applicazione',
	'Class:ApplicationInstance+' => '',
	'Class:ApplicationInstance/Attribute:software_id' => 'Software',
	'Class:ApplicationInstance/Attribute:software_id+' => '',
	'Class:ApplicationInstance/Attribute:software_name' => 'Nome',
	'Class:ApplicationInstance/Attribute:software_name+' => '',
));


//
// Class: DBServerInstance
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DBServerInstance' => 'Istanza del DB Server',
	'Class:DBServerInstance+' => '',
	'Class:DBServerInstance/Attribute:software_id' => 'Software',
	'Class:DBServerInstance/Attribute:software_id+' => '',
	'Class:DBServerInstance/Attribute:software_name' => 'Nome del Software',
	'Class:DBServerInstance/Attribute:software_name+' => '',
	'Class:DBServerInstance/Attribute:dbinstance_list' => 'Databases',
	'Class:DBServerInstance/Attribute:dbinstance_list+' => 'Sorgente del database',
));


//
// Class: DatabaseInstance
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:DatabaseInstance' => 'Istanza del database',
	'Class:DatabaseInstance+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_id' => 'Server del database',
	'Class:DatabaseInstance/Attribute:db_server_instance_id+' => '',
	'Class:DatabaseInstance/Attribute:db_server_instance_version' => 'Versione del database',
	'Class:DatabaseInstance/Attribute:db_server_instance_version+' => '',
	'Class:DatabaseInstance/Attribute:description' => 'Descrizione',
	'Class:DatabaseInstance/Attribute:description+' => '',
));

//
// Class: ApplicationSolution
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:ApplicationSolution' => 'Soluzione Applicativa',
	'Class:ApplicationSolution+' => '',
	'Class:ApplicationSolution/Attribute:description' => 'Descrizione',
	'Class:ApplicationSolution/Attribute:description+' => '',
	'Class:ApplicationSolution/Attribute:ci_list' => 'CIs',
	'Class:ApplicationSolution/Attribute:ci_list+' => 'CIs che compongono la soluzione applicativa',
	'Class:ApplicationSolution/Attribute:process_list' => 'Processi di business',
	'Class:ApplicationSolution/Attribute:process_list+' => 'Processi di business che si basano sulla soluzione',
));

//
// Class: BusinessProcess
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:BusinessProcess' => 'Processi di business',
	'Class:BusinessProcess+' => '',
	'Class:BusinessProcess/Attribute:description' => 'Descrizione',
	'Class:BusinessProcess/Attribute:description+' => '',
	'Class:BusinessProcess/Attribute:used_solution_list' => 'Soluzioni Applicative',
	'Class:BusinessProcess/Attribute:used_solution_list+' => 'Soluzioni applicative sui cui si basa il processo',
));

//
// Class: ConnectableCI
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:ConnectableCI' => 'CI collegabile',
	'Class:ConnectableCI+' => 'CI fisico',
	'Class:ConnectableCI/Attribute:brand' => 'Marca',
	'Class:ConnectableCI/Attribute:brand+' => '',
	'Class:ConnectableCI/Attribute:model' => 'Modello',
	'Class:ConnectableCI/Attribute:model+' => '',
	'Class:ConnectableCI/Attribute:serial_number' => 'Numero seriala',
	'Class:ConnectableCI/Attribute:serial_number+' => '',
	'Class:ConnectableCI/Attribute:asset_ref' => 'Asset di riferimento',
	'Class:ConnectableCI/Attribute:asset_ref+' => '',
));

//
// Class: NetworkInterface
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:NetworkInterface' => 'Interfaccia di Rete',
	'Class:NetworkInterface+' => '',
	'Class:NetworkInterface/Attribute:device_id' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_id+' => '',
	'Class:NetworkInterface/Attribute:device_name' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:device_name+' => '',
	'Class:NetworkInterface/Attribute:logical_type' => 'Logical Type',
	'Class:NetworkInterface/Attribute:logical_type+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup' => 'Backup',
	'Class:NetworkInterface/Attribute:logical_type/Value:backup+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical' => 'Logical',
	'Class:NetworkInterface/Attribute:logical_type/Value:logical+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:port' => 'Porta',
	'Class:NetworkInterface/Attribute:logical_type/Value:port+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary' => 'Primario',
	'Class:NetworkInterface/Attribute:logical_type/Value:primary+' => '',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary' => 'Secondario',
	'Class:NetworkInterface/Attribute:logical_type/Value:secondary+' => '',
	'Class:NetworkInterface/Attribute:physical_type' => 'Tipo fisico',
	'Class:NetworkInterface/Attribute:physical_type+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm' => 'ATM',
	'Class:NetworkInterface/Attribute:physical_type/Value:atm+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet' => 'Ethernet',
	'Class:NetworkInterface/Attribute:physical_type/Value:ethernet+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay' => 'Frame Relay',
	'Class:NetworkInterface/Attribute:physical_type/Value:framerelay+' => '',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan' => 'VLAN',
	'Class:NetworkInterface/Attribute:physical_type/Value:vlan+' => '',
	'Class:NetworkInterface/Attribute:ip_address' => 'Indirizzo IP',
	'Class:NetworkInterface/Attribute:ip_address+' => '',
	'Class:NetworkInterface/Attribute:ip_mask' => 'Maschera IP',
	'Class:NetworkInterface/Attribute:ip_mask+' => '',
	'Class:NetworkInterface/Attribute:mac_address' => 'Indirizzo MAC',
	'Class:NetworkInterface/Attribute:mac_address+' => '',
	'Class:NetworkInterface/Attribute:speed' => 'Velocità',
	'Class:NetworkInterface/Attribute:speed+' => '',
	'Class:NetworkInterface/Attribute:duplex' => 'Duplex',
	'Class:NetworkInterface/Attribute:duplex+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:auto' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:auto+' => 'Auto',
	'Class:NetworkInterface/Attribute:duplex/Value:full' => 'Full',
	'Class:NetworkInterface/Attribute:duplex/Value:full+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:half' => 'Half',
	'Class:NetworkInterface/Attribute:duplex/Value:half+' => '',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown' => 'Sconisciuta',
	'Class:NetworkInterface/Attribute:duplex/Value:unknown+' => '',
	'Class:NetworkInterface/Attribute:connected_if' => 'Connesso a ',
	'Class:NetworkInterface/Attribute:connected_if+' => 'Interfaccia connessa',
	'Class:NetworkInterface/Attribute:connected_name' => 'Connesso a ',
	'Class:NetworkInterface/Attribute:connected_name+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id' => 'Dispositivo connesso',
	'Class:NetworkInterface/Attribute:connected_if_device_id+' => '',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name' => 'Dispositivo',
	'Class:NetworkInterface/Attribute:connected_if_device_id_name+' => '',
	'Class:NetworkInterface/Attribute:link_type' => 'Tipo di link',
	'Class:NetworkInterface/Attribute:link_type+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink' => 'Down link',
	'Class:NetworkInterface/Attribute:link_type/Value:downlink+' => '',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink' => 'Up link',
	'Class:NetworkInterface/Attribute:link_type/Value:uplink+' => '',
));



//
// Class: Device
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Device' => 'Dispositivo',
	'Class:Device+' => '',
	'Class:Device/Attribute:nwinterface_list' => 'Interfaccia di Rete',
	'Class:Device/Attribute:nwinterface_list+' => '',
));

//
// Class: PC
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:PC' => 'PC',
	'Class:PC+' => '',
	'Class:PC/Attribute:cpu' => 'CPU',
	'Class:PC/Attribute:cpu+' => '',
	'Class:PC/Attribute:ram' => 'RAM',
	'Class:PC/Attribute:ram+' => '',
	'Class:PC/Attribute:hdd' => 'Hard disk',
	'Class:PC/Attribute:hdd+' => '',
	'Class:PC/Attribute:os_family' => 'OS Family',
	'Class:PC/Attribute:os_family+' => '',
	'Class:PC/Attribute:os_version' => 'OS Version',
	'Class:PC/Attribute:os_version+' => '',
	'Class:PC/Attribute:application_list' => 'Applicazioni',
	'Class:PC/Attribute:application_list+' => 'Applicazioni installate su questo PC',
	'Class:PC/Attribute:patch_list' => 'Patches',
	'Class:PC/Attribute:patch_list+' => 'Patches installate su questo PC',
));

//
// Class: MobileCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:MobileCI' => 'CI Mobili',
	'Class:MobileCI+' => '',
));

//
// Class: MobilePhone
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:MobilePhone' => 'Cellulari',
	'Class:MobilePhone+' => '',
	'Class:MobilePhone/Attribute:number' => 'Numero di telefono',
	'Class:MobilePhone/Attribute:number+' => '',
	'Class:MobilePhone/Attribute:imei' => 'IMEI',
	'Class:MobilePhone/Attribute:imei+' => '',
	'Class:MobilePhone/Attribute:hw_pin' => 'Hardware PIN',
	'Class:MobilePhone/Attribute:hw_pin+' => '',
));

//
// Class: InfrastructureCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:InfrastructureCI' => 'CI Infrastrutture',
	'Class:InfrastructureCI+' => '',
	'Class:InfrastructureCI/Attribute:description' => 'Descrizione',
	'Class:InfrastructureCI/Attribute:description+' => '',
	'Class:InfrastructureCI/Attribute:location_id' => 'Posizione',
	'Class:InfrastructureCI/Attribute:location_id+' => '',
	'Class:InfrastructureCI/Attribute:location_name' => 'Posizione',
	'Class:InfrastructureCI/Attribute:location_name+' => '',
	'Class:InfrastructureCI/Attribute:location_details' => 'Dettagli di posizione',
	'Class:InfrastructureCI/Attribute:location_details+' => '',
	'Class:InfrastructureCI/Attribute:management_ip' => 'Gestione IP',
	'Class:InfrastructureCI/Attribute:management_ip+' => '',
	'Class:InfrastructureCI/Attribute:default_gateway' => 'Gateway di default',
	'Class:InfrastructureCI/Attribute:default_gateway+' => '',
));

//
// Class: NetworkDevice
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:NetworkDevice' => 'Dispositivi di rete',
	'Class:NetworkDevice+' => '',
	'Class:NetworkDevice/Attribute:type' => 'Tipo',
	'Class:NetworkDevice/Attribute:type+' => '',
	'Class:NetworkDevice/Attribute:type/Value:wanaccelerator' => 'Acceleratore WAN',
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
	'Class:NetworkDevice/Attribute:ios_version' => 'Versione IOS',
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

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:Server' => 'Server',
	'Class:Server+' => '',
	'Class:Server/Attribute:cpu' => 'CPU',
	'Class:Server/Attribute:cpu+' => '',
	'Class:Server/Attribute:ram' => 'RAM',
	'Class:Server/Attribute:ram+' => '',
	'Class:Server/Attribute:hdd' => 'Hard Disk',
	'Class:Server/Attribute:hdd+' => '',
	'Class:Server/Attribute:os_family' => 'Famiglia OS',
	'Class:Server/Attribute:os_family+' => '',
	'Class:Server/Attribute:os_version' => 'Versione OS',
	'Class:Server/Attribute:os_version+' => '',
	'Class:Server/Attribute:application_list' => 'Applicazioni',
	'Class:Server/Attribute:application_list+' => 'Applicazioni installate su questo server',
	'Class:Server/Attribute:patch_list' => 'Patches',
	'Class:Server/Attribute:patch_list+' => 'Patches installate su questo server',
));

//
// Class: Printer
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Printer' => 'Stampante',
	'Class:Printer+' => '',
	'Class:Printer/Attribute:type' => 'Tipo',
	'Class:Printer/Attribute:type+' => '',
	'Class:Printer/Attribute:type/Value:mopier' => 'Mopier',
	'Class:Printer/Attribute:type/Value:mopier+' => '',
	'Class:Printer/Attribute:type/Value:printer' => 'Stampante',
	'Class:Printer/Attribute:type/Value:printer+' => '',
	'Class:Printer/Attribute:technology' => 'Tecnologia',
	'Class:Printer/Attribute:technology+' => '',
	'Class:Printer/Attribute:technology/Value:inkjet' => 'Inkjet',
	'Class:Printer/Attribute:technology/Value:inkjet+' => '',
	'Class:Printer/Attribute:technology/Value:laser' => 'Laser',
	'Class:Printer/Attribute:technology/Value:laser+' => '',
	'Class:Printer/Attribute:technology/Value:tracer' => 'Tracer',
	'Class:Printer/Attribute:technology/Value:tracer+' => '',
));

//
// Class: lnkCIToDoc
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkCIToDoc' => 'Doc/CI',
	'Class:lnkCIToDoc+' => '',
	'Class:lnkCIToDoc/Attribute:ci_id' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_id+' => '',
	'Class:lnkCIToDoc/Attribute:ci_name' => 'CI',
	'Class:lnkCIToDoc/Attribute:ci_name+' => '',
	'Class:lnkCIToDoc/Attribute:ci_status' => 'CI Stato',
	'Class:lnkCIToDoc/Attribute:ci_status+' => '',
	'Class:lnkCIToDoc/Attribute:document_id' => 'Documento',
	'Class:lnkCIToDoc/Attribute:document_id+' => '',
	'Class:lnkCIToDoc/Attribute:document_name' => 'Documento',
	'Class:lnkCIToDoc/Attribute:document_name+' => '',
	'Class:lnkCIToDoc/Attribute:document_type' => 'Tipo di documento',
	'Class:lnkCIToDoc/Attribute:document_type+' => '',
	'Class:lnkCIToDoc/Attribute:document_status' => 'Stato del documento',
	'Class:lnkCIToDoc/Attribute:document_status+' => '',
));

//
// Class: lnkCIToContact
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkCIToContact' => 'CI/Contatto',
	'Class:lnkCIToContact+' => '',
	'Class:lnkCIToContact/Attribute:ci_id' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_id+' => '',
	'Class:lnkCIToContact/Attribute:ci_name' => 'CI',
	'Class:lnkCIToContact/Attribute:ci_name+' => '',
	'Class:lnkCIToContact/Attribute:ci_status' => 'CI Stato',
	'Class:lnkCIToContact/Attribute:ci_status+' => '',
	'Class:lnkCIToContact/Attribute:contact_id' => 'Contatto',
	'Class:lnkCIToContact/Attribute:contact_id+' => '',
	'Class:lnkCIToContact/Attribute:contact_name' => 'Contatto',
	'Class:lnkCIToContact/Attribute:contact_name+' => '',
	'Class:lnkCIToContact/Attribute:contact_email' => 'Contatto Email',
	'Class:lnkCIToContact/Attribute:contact_email+' => '',
	'Class:lnkCIToContact/Attribute:role' => 'Ruolo',
	'Class:lnkCIToContact/Attribute:role+' => 'Ruolo del contatto per quanto riguarda il CI',
));

//
// Class: lnkSolutionToCI
//

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Class:lnkSolutionToCI' => 'CI/Solutione',
	'Class:lnkSolutionToCI+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_id' => 'Soluzione applicativa',
	'Class:lnkSolutionToCI/Attribute:solution_id+' => '',
	'Class:lnkSolutionToCI/Attribute:solution_name' => 'Soluzione applicativa',
	'Class:lnkSolutionToCI/Attribute:solution_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_id' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_id+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_name' => 'CI',
	'Class:lnkSolutionToCI/Attribute:ci_name+' => '',
	'Class:lnkSolutionToCI/Attribute:ci_status' => 'CI Stato',
	'Class:lnkSolutionToCI/Attribute:ci_status+' => '',
	'Class:lnkSolutionToCI/Attribute:utility' => 'Utility',
	'Class:lnkSolutionToCI/Attribute:utility+' => 'Utility del CI nella soluzione applicativa',
));

//
// Class: lnkProcessToSolution
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkProcessToSolution' => 'Processo di business/Solutione',
	'Class:lnkProcessToSolution+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_id' => 'Soluzione applicativa',
	'Class:lnkProcessToSolution/Attribute:solution_id+' => '',
	'Class:lnkProcessToSolution/Attribute:solution_name' => 'Soluzione applicativa',
	'Class:lnkProcessToSolution/Attribute:solution_name+' => '',
	'Class:lnkProcessToSolution/Attribute:process_id' => 'Processo',
	'Class:lnkProcessToSolution/Attribute:process_id+' => '',
	'Class:lnkProcessToSolution/Attribute:process_name' => 'Processo',
	'Class:lnkProcessToSolution/Attribute:process_name+' => '',
	'Class:lnkProcessToSolution/Attribute:reason' => 'Motivo',
	'Class:lnkProcessToSolution/Attribute:reason+' => 'Più informazioni tra il processo di business e la soluzione applicativa',
));



//
// Class extensions
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
'Class:Subnet/Tab:IPUsage' => 'Utilizzo IP',
'Class:Subnet/Tab:IPUsage-explain' => 'Iterfacce che hanno un IP nell\'intervallo: <em>%1$s</em> e <em>%2$s</em>',
'Class:Subnet/Tab:FreeIPs' => 'IP liberi',
'Class:Subnet/Tab:FreeIPs-count' => 'IP liberi: %1$s',
'Class:Subnet/Tab:FreeIPs-explain' => 'Qui c\'è un estratto di 10 indirizzi IP liberi',
));

//
// Application Menu
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
'Menu:Catalogs' => 'Cataloghi',
'Menu:Catalogs+' => 'Tipi di dato',
'Menu:Audit' => 'Audit',
'Menu:Audit+' => 'Audit',
'Menu:Organization' => 'Organizzazioni',
'Menu:Organization+' => 'Tutte le organizzazioni',
'Menu:Application' => 'Applicazioni',
'Menu:Application+' => 'Tutte le applicazioni',
'Menu:DBServer' => 'Database Servers',
'Menu:DBServer+' => 'Database Servers',
'Menu:Audit' => 'Audit',
'Menu:ConfigManagement' => 'Gestione delle Configurazioni',
'Menu:ConfigManagement+' => 'Gestione delle Configurazioni',
'Menu:ConfigManagementOverview' => 'Panoramica',
'Menu:ConfigManagementOverview+' => 'Panoramica',
'Menu:Contact' => 'Contatti',
'Menu:Contact+' => 'Contatti',
'Menu:Person' => 'Persone',
'Menu:Person+' => 'Tutte le persone',
'Menu:Team' => 'Teams',
'Menu:Team+' => 'Tutti i Teams',
'Menu:Document' => 'Documenti',
'Menu:Document+' => 'Tutti i Documenti',
'Menu:Location' => 'Posizioni',
'Menu:Location+' => 'Tutte le pozisioni',
'Menu:ConfigManagementCI' => 'Elementi di Configurazione (CI)',
'Menu:ConfigManagementCI+' => 'Elementi di Configurazione (CI)',
'Menu:BusinessProcess' => 'Processi di business',
'Menu:BusinessProcess+' => 'Tutti i processi di business',
'Menu:ApplicationSolution' => 'Soluzioni applicative',
'Menu:ApplicationSolution+' => 'Tutte le soluzioni applicative',
'Menu:Licence' => 'Licenze',
'Menu:Licence+' => 'Tutte le licenze',
'Menu:Patch' => 'Patches',
'Menu:Patch+' => 'Tutte le patches',
'Menu:ApplicationInstance' => 'Software Installati',
'Menu:ApplicationInstance+' => 'Apllicazioni e Database servers',
'Menu:ConfigManagementHardware' => 'Gestione Infrastrutture',
'Menu:Subnet' => 'Subnets',
'Menu:Subnet+' => 'Tutte le Subnets',
'Menu:NetworkDevice' => 'Dispositivi di rete',
'Menu:NetworkDevice+' => 'Tutti i dispositivi di rete',
'Menu:Server' => 'Server',
'Menu:Server+' => 'Tutti i Server',
'Menu:Printer' => 'Stampanti',
'Menu:Printer+' => 'Tutte le stampanti',
'Menu:MobilePhone' => 'Cellulari',
'Menu:MobilePhone+' => 'Tutti i cellulari',
'Menu:PC' => 'Personal Computers',
'Menu:PC+' => 'Tutti i Personal Computers',
'Menu:NewContact' => 'Nuovo Contatto',
'Menu:NewContact+' => 'Nuovo Contatto',
'Menu:SearchContacts' => 'Ricerca contatti',
'Menu:SearchContacts+' => 'Ricerca contatti',
'Menu:NewCI' => 'Nuovo CI',
'Menu:NewCI+' => 'Nuovo CI',
'Menu:SearchCIs' => 'Ricerca CIs',
'Menu:SearchCIs+' => 'Ricerca CIs',
'Menu:ConfigManagement:Devices' => 'Dispositvi',
'Menu:ConfigManagement:AllDevices' => 'Numero di dispositivi: %1$d',
'Menu:ConfigManagement:SWAndApps' => 'Software e Applicazioni',
'Menu:ConfigManagement:Misc' => 'Varie',
'Menu:Group' => 'Gruppi di CIs',
'Menu:Group+' => 'Gruppi di CIs',
'Menu:ConfigManagement:Shortcuts' => 'Scorciatoie',
'Menu:ConfigManagement:AllContacts' => 'Tutti i contatti: %1$d',
));
?>
