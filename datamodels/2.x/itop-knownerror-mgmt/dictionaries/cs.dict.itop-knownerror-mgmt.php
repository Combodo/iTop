<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author Daniel Rokos <daniel.rokos@itopportal.cz>
 *
 */
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Class:KnownError' => 'Známá chyba',
	'Class:KnownError+' => 'Pro známou příčinu zdokumentovaná chyba',
	'Class:KnownError/Attribute:name' => 'Název',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Zákazník',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Název zákazníka',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Související problém',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'ID Souvisejícího problému',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Příznak',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Primární příčina',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Náhradní řešení (workaround)',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Řešení',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Kód chyby',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Oblast',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplikace',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Síť',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Dodavatel',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Verze',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'Konfigurační položky',
	'Class:KnownError/Attribute:ci_list+' => 'Všechny konfigurační položky vztahující se k této známé chybě',
	'Class:KnownError/Attribute:document_list' => 'Dokumenty',
	'Class:KnownError/Attribute:document_list+' => 'Všechny dokumenty spojené s touto známou chybou',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Class:lnkErrorToFunctionalCI' => 'Spojení (Chyba / Funkční konfigurační položka)',
	'Class:lnkErrorToFunctionalCI+' => 'Konfigurační položky vztahující se k chybě',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
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
]);

//
// Class: lnkDocumentToError
//

Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Class:lnkDocumentToError' => 'Spojení (Dokument / Chyba)',
	'Class:lnkDocumentToError+' => 'Spojení mezi dokumentem a známou chybou',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
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
]);

Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Menu:ProblemManagement' => 'Správa problémů',
	'Menu:ProblemManagement+' => 'Správa problémů',
	'Menu:Problem:Shortcuts' => 'Odkazy',
	'Menu:NewError' => 'Nová známá chyba',
	'Menu:NewError+' => 'Vytvoření nové známé chyby',
	'Menu:SearchError' => 'Hledat známé chyby',
	'Menu:SearchError+' => 'Hledat známé chyby',
	'Menu:Problem:KnownErrors' => 'Všechny známé chyby',
	'Menu:Problem:KnownErrors+' => 'Všechny známé chyby',
]);
