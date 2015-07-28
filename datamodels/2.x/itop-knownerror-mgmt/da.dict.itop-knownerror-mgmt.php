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
 * @author	Erik Bøg <erik@boegmoeller.dk>

 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DA DA', 'Danish', 'Dansk', array(
	'Class:KnownError' => 'Known Error',
	'Class:KnownError+' => 'Dokumenterede fejl for et bestående Issue',
	'Class:KnownError/Attribute:name' => 'Navn',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Kunde',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Tilhørende problem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Grund årsag',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Workaround',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Løsning',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Fejlkode',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Område',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Anvendelse',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Anvendelse',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Netværk',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Netværk',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Leverandør',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Dokumenter',
	'Class:KnownError/Attribute:document_list+' => '',
	'Class:lnkErrorToFunctionalCI' => 'Sammenhæng Fejl/FunctionalCI',
	'Class:lnkErrorToFunctionalCI+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Fejl',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Årsag',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
	'Class:lnkDocumentToError' => 'Sammenhæng Dokumenter/Fejl',
	'Class:lnkDocumentToError+' => '',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Fejl',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Sammenhængstype',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => '',
	'Class:FAQ/Attribute:title' => 'Titel',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => 'Sammenfatning',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => 'Beskrivelse',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'Kategori',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:error_code' => 'Fejlkode',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'Nøgleord',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQCategory' => 'FAQ-Kategori',
	'Class:FAQCategory+' => '',
	'Class:FAQCategory/Attribute:name' => 'Navn',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs',
	'Class:FAQCategory/Attribute:faq_list+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Kundennavn',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Reference',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI-Navn',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Fejlnavn',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Dokumentnavn',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Fejlnavn',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:FAQ/Attribute:category_name' => 'Kategorinavn',
	'Class:FAQ/Attribute:category_name+' => '',
	'Menu:NewError' => 'Ny kendt fejl',
	'Menu:NewError+' => '',
	'Menu:SearchError' => 'Søg efter kendte fejl',
	'Menu:SearchError+' => '',
	'Menu:Problem:KnownErrors' => 'Alle kendte Fejl',
	'Menu:Problem:KnownErrors+' => 'Alle kendte Fejl',
	'Menu:FAQCategory' => 'FAQ-Kategorier',
	'Menu:FAQCategory+' => '',
	'Menu:FAQ' => 'FAQs',
	'Menu:FAQ+' => '',
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Genvej',
));
?>