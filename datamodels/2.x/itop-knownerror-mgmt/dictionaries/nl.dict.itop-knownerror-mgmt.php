<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author LinProfs <info@linprofs.com>
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 *
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:KnownError' => 'Gekende fout',
	'Class:KnownError+' => 'Gedocumenteerde fout voor een gekend probleem',
	'Class:KnownError/Attribute:name' => 'Naam',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Organisatie',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Naam klant',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Gerelateerd probleem',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Ref. gerelateerd probleem',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptoom',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Hoofdoorzaak',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Work around',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Oplossing',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Foutcode',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Domein',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Applicatie',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Netwerk',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Verkoper',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Versie',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'Configuratie-items',
	'Class:KnownError/Attribute:ci_list+' => 'Alle configuratie-items gerelateerd aan deze gekende fout',
	'Class:KnownError/Attribute:document_list' => 'Documenten',
	'Class:KnownError/Attribute:document_list+' => 'Alle documenten gerelateerd aan deze gekende fout',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:lnkErrorToFunctionalCI' => 'Link Fout / Functioneel CI',
	'Class:lnkErrorToFunctionalCI+' => 'Infrastructuur gelinkt aan een gekende fout',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
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
]);

//
// Class: lnkDocumentToError
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:lnkDocumentToError' => 'Link Document / Fout',
	'Class:lnkDocumentToError+' => 'Een link tussen een document en een gekende fout',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
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
]);

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Menu:ProblemManagement' => 'Probleem Management',
	'Menu:ProblemManagement+' => 'Probleem Management',
	'Menu:Problem:Shortcuts' => 'Snelkoppelingen',
	'Menu:NewError' => 'Nieuwe gekende fout',
	'Menu:NewError+' => 'Maak een nieuwe gekende fout aan',
	'Menu:SearchError' => 'Zoek naar gekende fouten',
	'Menu:SearchError+' => 'Zoek naar gekende fouten',
	'Menu:Problem:KnownErrors' => 'Alle gekende fouten',
	'Menu:Problem:KnownErrors+' => 'Alle gekende fouten',
]);
