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
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:KnownError' => 'Ismert hiba',
	'Class:KnownError+' => '',
	'Class:KnownError/Attribute:name' => 'Ismert hiba név',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Ügyfél',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Ügyfél név',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Kapcsolódó probléma',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Referenciaszám',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Hibajelenség',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Gyökér ok',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Elkerülő megoldás',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Megoldás',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Hibakód',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Hibatartomány',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Alkalmazás',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Hálózat',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Szerver',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Gyártó',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Modell',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Verzió',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'CI-k',
	'Class:KnownError/Attribute:ci_list+' => 'The configuration items that are potentially impacted by this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Dokumentumok',
	'Class:KnownError/Attribute:document_list+' => '',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:lnkErrorToFunctionalCI' => 'Hiba / Funkcionális CI',
	'Class:lnkErrorToFunctionalCI+' => 'Infra ismert hibához kapcsolódóan',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI név',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Hiba',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Hiba név',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Hibaok',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '~~',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:lnkDocumentToError' => 'Dokumentumok / Hibák',
	'Class:lnkDocumentToError+' => '',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Dokumentum',
	'Class:lnkDocumentToError/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Dokumentum név',
	'Class:lnkDocumentToError/Attribute:document_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Hiba',
	'Class:lnkDocumentToError/Attribute:error_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Hiba név',
	'Class:lnkDocumentToError/Attribute:error_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type',
	'Class:lnkDocumentToError/Attribute:link_type+' => '~~',
]);

Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Menu:ProblemManagement' => 'Problémakezelés',
	'Menu:ProblemManagement+' => '',
	'Menu:Problem:Shortcuts' => 'Gyorsgomb',
	'Menu:NewError' => 'Új ismert hiba',
	'Menu:NewError+' => '',
	'Menu:SearchError' => 'Ismert hiba keresés',
	'Menu:SearchError+' => '',
	'Menu:Problem:KnownErrors' => 'Ismert hibák',
	'Menu:Problem:KnownErrors+' => '',
]);
