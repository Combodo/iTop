<?php
// Copyright (C) 2010-2019 Combodo SARL
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
 * @author Jeffrey Bostoen - <jbostoen.itop@outlook.com> (2018 - 2020)
 * 
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
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
//
// Class: KnownError
//
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:KnownError' => 'Gekende fout',
	'Class:KnownError+' => 'Gedocumenteerde fout voor een gekend probleem',
	'Class:KnownError/Attribute:name' => 'Naam',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Organisatie',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Naam klant',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Gerelateerd probleem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ref. gerelateerd probleem',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptoom',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Hoofdoorzaak',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Work around',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Oplossing',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Foutcode',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Domein',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Applicatie',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Applicatie',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Netwerk',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Netwerk',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Verkoper',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Versie',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'Configuratie-items',
	'Class:KnownError/Attribute:ci_list+' => 'Alle configuratie-items gerelateerd aan deze gekende fout',
	'Class:KnownError/Attribute:document_list' => 'Documenten',
	'Class:KnownError/Attribute:document_list+' => 'Alle documenten gerelateerd aan deze gekende fout',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkErrorToFunctionalCI' => 'Link Fout / Functioneel CI',
	'Class:lnkErrorToFunctionalCI+' => 'Infrastructuur gelinkt aan een gekende fout',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Naam CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Fout',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Naam fout',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Reden',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:lnkDocumentToError' => 'Link Document / Fout',
	'Class:lnkDocumentToError+' => 'Een link tussen een document en een gekende fout',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Document',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Naam document',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Fout',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Naam fout',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Soort link',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

//
// Class: FAQ
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'Veelgestelde vragen',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Samenvatting',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Omschrijving',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Categorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'Naam categorie',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'Foutcode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Zoektermen',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domeinen',
));

//
// Class: FAQCategory
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:FAQCategory' => 'FAQ-categorie',
	'Class:FAQCategory+' => 'Categorie voor de FAQ',
	'Class:FAQCategory/Attribute:name' => 'Naam',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ\'s',
	'Class:FAQCategory/Attribute:faq_list+' => 'Alle veelgestelde vragen gerelateerd aan deze categorie',
));
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:ProblemManagement' => 'Probleem Management',
	'Menu:ProblemManagement+' => 'Probleem Management',
	'Menu:Problem:Shortcuts' => 'Snelkoppelingen',
	'Menu:NewError' => 'Nieuwe gekende fout',
	'Menu:NewError+' => 'Maak een nieuwe gekende fout aan',
	'Menu:SearchError' => 'Zoek naar gekende fouten',
	'Menu:SearchError+' => 'Zoek naar gekende fouten',
	'Menu:Problem:KnownErrors' => 'Alle gekende fouten',
	'Menu:Problem:KnownErrors+' => 'Alle gekende fouten',
	'Menu:FAQCategory' => 'FAQ-categorieën',
	'Menu:FAQCategory+' => 'Alle FAQ-categorieën',
	'Menu:FAQ' => 'FAQ\'s',
	'Menu:FAQ+' => 'Alle FAQ\'s',

	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'Veelgestelde vragen',
	'Brick:Portal:FAQ:Title+' => '<p>Gehaast?</p><p>Bekijk deze lijst van veelgestelde vragen. Misschien staat er al een antwoord tussen.</p>',
));
