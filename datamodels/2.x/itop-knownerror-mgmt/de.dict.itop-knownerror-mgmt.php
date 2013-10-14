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
 * @author	Stephan Rosenke <stephan.rosenke@itomig.de>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:KnownError' => 'Known Error',
	'Class:KnownError+' => 'Dokumentierter Fehler für ein Issue',
	'Class:KnownError/Attribute:name' => 'Name',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Kunde',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Zugehöriges Problem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Grundursache',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Workaround',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Lösung',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Fehlercode',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Bereich',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Anwendung',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Anwendung',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Netzwerk',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Netzwerk',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Anbieter',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modell',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Dokumente',
	'Class:KnownError/Attribute:document_list+' => '',
	'Class:lnkErrorToFunctionalCI' => 'Verknüpfung KnownError/FunctionalCI',
	'Class:lnkErrorToFunctionalCI+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Fehler',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Begründung',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
	'Class:lnkDocumentToError' => 'Verknüpfun Dokumente/KnownError',
	'Class:lnkDocumentToError+' => '',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Known Error',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Verknüpfungstyp',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => '',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Zusammenfassung',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Beschreibung',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategorie',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:error_code' => 'Fehlercode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Schlüsselwörter',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQCategory' => 'FAQ-Kategorie',
	'Class:FAQCategory+' => '',
	'Class:FAQCategory/Attribute:name' => 'Name',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => '',
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:NewError' => 'Neuer Known Error',
	'Menu:NewError+' => '',
	'Menu:SearchError' => 'Nach Known Error suchen',
	'Menu:SearchError+' => '',
	'Menu:Problem:KnownErrors' => 'Alle Known Errors',
	'Menu:Problem:KnownErrors+' => 'Alle Known Errors',
	'Menu:FAQCategory' => 'FAQ-Kategorien',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Kundenname',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Referenz',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI-Name',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Known Error-Name',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Dokumentname',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Known Error-Name',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:FAQ/Attribute:category_name' => 'Kategoriename',
	'Class:FAQ/Attribute:category_name+' => '',
));
?>
