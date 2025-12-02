<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author ITOMIG GmbH <martin.raenker@itomig.de>
 *
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:KnownError' => 'Known Error',
	'Class:KnownError+' => 'Dokumentierter Fehler für ein Problem',
	'Class:KnownError/Attribute:name' => 'Name',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Kunde',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Kundenname',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Zugehöriges Problem',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Referenz',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Grundursache',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Workaround',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Lösung',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Fehlercode',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Bereich',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Anwendung',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Netzwerk',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Anbieter',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Modell',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => 'Alle CIs, die mit diesem Known Error verknüpft sind',
	'Class:KnownError/Attribute:document_list' => 'Dokumente',
	'Class:KnownError/Attribute:document_list+' => 'Alle Dokumente, die mit diesem Known Error verknüpft sind',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:lnkErrorToFunctionalCI' => 'Verknüpfung KnownError/FunctionalCI',
	'Class:lnkErrorToFunctionalCI+' => '',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI-Name',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Fehler',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Known Error-Name',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Begründung',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:lnkDocumentToError' => 'Verknüpfung Dokumente/KnownError',
	'Class:lnkDocumentToError+' => '',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Dokumentname',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Known Error',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Known Error-Name',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Verknüpfungstyp',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
]);

Dict::Add('DE DE', 'German', 'Deutsch', [
	'Menu:ProblemManagement' => 'Problem Management',
	'Menu:ProblemManagement+' => 'Problem Management',
	'Menu:Problem:Shortcuts' => 'Shortcuts',
	'Menu:NewError' => 'Neuer Known Error',
	'Menu:NewError+' => 'Neuen Known Error dokumentieren',
	'Menu:SearchError' => 'Nach Known Error suchen',
	'Menu:SearchError+' => 'Nach dokumentierten Fehlern suchen',
	'Menu:Problem:KnownErrors' => 'Alle Known Errors',
	'Menu:Problem:KnownErrors+' => 'Alle Known Errors',
]);
