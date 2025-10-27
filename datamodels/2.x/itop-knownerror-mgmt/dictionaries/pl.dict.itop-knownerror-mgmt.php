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
Dict::Add('PL PL', 'Polish', 'Polski', [
	'Class:KnownError' => 'Znany błąd',
	'Class:KnownError+' => 'Udokumentowano błąd dotyczący znanego problemu',
	'Class:KnownError/Attribute:name' => 'Nazwa',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Klient',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Nazwa klienta',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Powiązany problem',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Powiązane informacje o problemie',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom (objaw)',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Przyczyna',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Obejście',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Rozwiązanie',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Kod błędu',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Domena',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplikacja',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Sieć',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Serwer',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Sprzedawca',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Wersja',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'Konfiguracje',
	'Class:KnownError/Attribute:ci_list+' => 'Wszystkie elementy konfiguracji związane z tym znanym błędem',
	'Class:KnownError/Attribute:document_list' => 'Dokumenty',
	'Class:KnownError/Attribute:document_list+' => 'Wszystkie dokumenty związane z tym znanym błędem',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', [
	'Class:lnkErrorToFunctionalCI' => 'Połączenie Błąd / Konfiguracja',
	'Class:lnkErrorToFunctionalCI+' => 'Konfiguracje związane ze znanym błędem',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'Konfiguracja',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'Nazwa konfiguracji',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Błąd',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Nazwa błędu',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Powód',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('PL PL', 'Polish', 'Polski', [
	'Class:lnkDocumentToError' => 'Połączenie Dokumenty / Błędy',
	'Class:lnkDocumentToError+' => 'Łącze między dokumentem a znanym błędem',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Dokument',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Nazwa dokumentu',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Błąd',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Nazwa błędu',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
]);

Dict::Add('PL PL', 'Polish', 'Polski', [
	'Menu:ProblemManagement' => 'Zarządzanie problemami',
	'Menu:ProblemManagement+' => 'Zarządzanie problemami',
	'Menu:Problem:Shortcuts' => 'Skróty',
	'Menu:NewError' => 'Nowy znany błąd',
	'Menu:NewError+' => 'Utworzenie nowego znanego błędu',
	'Menu:SearchError' => 'Wyszukaj znane błędy',
	'Menu:SearchError+' => 'Wyszukaj znane błędy',
	'Menu:Problem:KnownErrors' => 'Wszystkie znane błędy',
	'Menu:Problem:KnownErrors+' => 'Wszystkie znane błędy',
]);
