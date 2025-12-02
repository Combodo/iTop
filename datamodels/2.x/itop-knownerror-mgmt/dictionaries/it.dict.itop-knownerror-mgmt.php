<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:KnownError' => 'Errori conosciuti',
	'Class:KnownError+' => 'Errori documentati per problemi noti',
	'Class:KnownError/Attribute:name' => 'Nome',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Cliente ',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Nome del cliente',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema correlato',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Ref',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Sintomo',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Causa principale',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Soluzione temporanea',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Soluzione',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Codice di errore',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Dominio',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Applicazione',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Venditore',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Modello',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Versione',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => 'The configuration items that are potentially impacted by this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Documenti',
	'Class:KnownError/Attribute:document_list+' => 'All the documents linked to this known error~~',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:lnkErrorToFunctionalCI' => 'Link Errore /CIFunzionale',
	'Class:lnkErrorToFunctionalCI+' => 'Infra impattata dall\'errore conosciuto',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI Nome',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Errore',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nome errore',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Ragione',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '~~',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:lnkDocumentToError' => 'Link Documento / Errore',
	'Class:lnkDocumentToError+' => 'Link tra il documento e l\'errore conosciuto',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Documenti',
	'Class:lnkDocumentToError/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Nome dei documenti',
	'Class:lnkDocumentToError/Attribute:document_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Errore',
	'Class:lnkDocumentToError/Attribute:error_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Nome errore',
	'Class:lnkDocumentToError/Attribute:error_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
	'Class:lnkDocumentToError/Attribute:link_type+' => '~~',
]);

Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Menu:ProblemManagement' => 'Gestione dei problemi',
	'Menu:ProblemManagement+' => 'Gestione dei problemi',
	'Menu:Problem:Shortcuts' => 'Scorciatoia',
	'Menu:NewError' => 'Nuovo errore conosciuto',
	'Menu:NewError+' => 'Creazione di un nuovo errore conosciuto',
	'Menu:SearchError' => 'Ricerca per errori conosciuti',
	'Menu:SearchError+' => 'Ricerca per errori conosciuti',
	'Menu:Problem:KnownErrors' => 'Tutti gli errori conosciuti',
	'Menu:Problem:KnownErrors+' => 'Tutti gli errori conosciuti',
]);
