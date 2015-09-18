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
 * Localized data.
 *
 * @author      Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author      Daniel Rokos <daniel.rokos@itopportal.cz>
 * @copyright   Copyright (C) 2010-2014 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:KnownError' => 'Známá chyba',
    'Class:KnownError+' => 'Pro známou příčinu zdokumentovaná chyba',
    'Class:KnownError/Attribute:name' => 'Název',
    'Class:KnownError/Attribute:name+' => '',
    'Class:KnownError/Attribute:org_id' => 'Zákazník',
    'Class:KnownError/Attribute:org_id+' => '',
    'Class:KnownError/Attribute:cust_name' => 'Název zákazníka',
    'Class:KnownError/Attribute:cust_name+' => '',
    'Class:KnownError/Attribute:problem_id' => 'Související problém',
    'Class:KnownError/Attribute:problem_id+' => '',
    'Class:KnownError/Attribute:problem_ref' => 'ID Souvisejícího problému',
    'Class:KnownError/Attribute:problem_ref+' => '',
    'Class:KnownError/Attribute:symptom' => 'Příznak',
    'Class:KnownError/Attribute:symptom+' => '',
    'Class:KnownError/Attribute:root_cause' => 'Primární příčina',
    'Class:KnownError/Attribute:root_cause+' => '',
    'Class:KnownError/Attribute:workaround' => 'Náhradní řešení (workaround)',
    'Class:KnownError/Attribute:workaround+' => '',
    'Class:KnownError/Attribute:solution' => 'Řešení',
    'Class:KnownError/Attribute:solution+' => '',
    'Class:KnownError/Attribute:error_code' => 'Kód chyby',
    'Class:KnownError/Attribute:error_code+' => '',
    'Class:KnownError/Attribute:domain' => 'Oblast',
    'Class:KnownError/Attribute:domain+' => '',
    'Class:KnownError/Attribute:domain/Value:Application' => 'Aplikace',
    'Class:KnownError/Attribute:domain/Value:Application+' => '',
    'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
    'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
    'Class:KnownError/Attribute:domain/Value:Network' => 'Síť',
    'Class:KnownError/Attribute:domain/Value:Network+' => '',
    'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
    'Class:KnownError/Attribute:domain/Value:Server+' => '',
    'Class:KnownError/Attribute:vendor' => 'Dodavatel',
    'Class:KnownError/Attribute:vendor+' => '',
    'Class:KnownError/Attribute:model' => 'Model',
    'Class:KnownError/Attribute:model+' => '',
    'Class:KnownError/Attribute:version' => 'Verze',
    'Class:KnownError/Attribute:version+' => '',
    'Class:KnownError/Attribute:ci_list' => 'Konfigurační položky',
    'Class:KnownError/Attribute:ci_list+' => 'Všechny konfigurační položky vztahující se k této známé chybě',
    'Class:KnownError/Attribute:document_list' => 'Dokumenty',
    'Class:KnownError/Attribute:document_list+' => 'Všechny dokumenty spojené s touto známou chybou',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkErrorToFunctionalCI' => 'Spojení (Chyba / Funkční konfigurační položka)',
    'Class:lnkErrorToFunctionalCI+' => 'Konfigurační položky vztahující se k chybě',
    'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'Konfigurační položka',
    'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
    'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Název konfigurační položky',
    'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
    'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Chyba',
    'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
    'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Název chyby',
    'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
    'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Důvod',
    'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:lnkDocumentToError' => 'Spojení (Dokument / Chyba)',
    'Class:lnkDocumentToError+' => 'Spojení mezi dokumentem a známou chybou',
    'Class:lnkDocumentToError/Attribute:document_id' => 'Dokument',
    'Class:lnkDocumentToError/Attribute:document_id+' => '',
    'Class:lnkDocumentToError/Attribute:document_name' => 'Název dokumentu',
    'Class:lnkDocumentToError/Attribute:document_name+' => '',
    'Class:lnkDocumentToError/Attribute:error_id' => 'Chyba',
    'Class:lnkDocumentToError/Attribute:error_id+' => '',
    'Class:lnkDocumentToError/Attribute:error_name' => 'Název chyby',
    'Class:lnkDocumentToError/Attribute:error_name+' => '',
    'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
    'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

//
// Class: FAQ
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:FAQ' => 'FAQ',
    'Class:FAQ+' => 'Často kladené dotazy',
    'Class:FAQ/Attribute:title' => 'Název',
    'Class:FAQ/Attribute:title+' => '',
    'Class:FAQ/Attribute:summary' => 'Shrnutí',
    'Class:FAQ/Attribute:summary+' => '',
    'Class:FAQ/Attribute:description' => 'Popis',
    'Class:FAQ/Attribute:description+' => '',
    'Class:FAQ/Attribute:category_id' => 'Kategorie',
    'Class:FAQ/Attribute:category_id+' => '',
    'Class:FAQ/Attribute:category_name' => 'Název kategorie',
    'Class:FAQ/Attribute:category_name+' => '',
    'Class:FAQ/Attribute:error_code' => 'Kód chyby',
    'Class:FAQ/Attribute:error_code+' => '',
    'Class:FAQ/Attribute:key_words' => 'Klíčová slova',
    'Class:FAQ/Attribute:key_words+' => '',
));

//
// Class: FAQCategory
//

Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Class:FAQCategory' => 'Kategorie FAQ',
    'Class:FAQCategory+' => 'Kategorie pro FAQ',
    'Class:FAQCategory/Attribute:name' => 'Název',
    'Class:FAQCategory/Attribute:name+' => '',
    'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
    'Class:FAQCategory/Attribute:faq_list+' => 'Všechny často kladené dotazy v této kategorii',
));
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
    'Menu:ProblemManagement' => 'Správa problémů',
    'Menu:ProblemManagement+' => 'Správa problémů',
    'Menu:Problem:Shortcuts' => 'Odkazy',
    'Menu:NewError' => 'Nová známá chyba',
    'Menu:NewError+' => 'Vytvoření nové známé chyby',
    'Menu:SearchError' => 'Hledat známé chyby',
    'Menu:SearchError+' => 'Hledat známé chyby',
    'Menu:Problem:KnownErrors' => 'Všechny známé chyby',
    'Menu:Problem:KnownErrors+' => 'Všechny známé chyby',
    'Menu:FAQCategory' => 'Kategorie FAQ',
    'Menu:FAQCategory+' => '',
    'Menu:FAQ' => 'FAQ',
    'Menu:FAQ+' => 'FAQ - Často kladené dotazy',
));
