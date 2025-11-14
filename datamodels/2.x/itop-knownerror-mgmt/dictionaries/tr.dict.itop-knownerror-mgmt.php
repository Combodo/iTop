<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Izzet Sirin <izzet.sirin@htr.com.tr>
 *
 */
Dict::Add('TR TR', 'Turkish', 'Türkçe', [
	'Class:KnownError' => 'Bilinen hata',
	'Class:KnownError+' => 'Hata bilinen hatalara kaydedildi',
	'Class:KnownError/Attribute:name' => 'Adı',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => 'Müşteri',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => 'Müşteri Adı',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'İlgili problem',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => 'Referans',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Belirtisi',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => 'Ana sebep',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => 'Ara çözüm',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => 'Çözüm',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'Hata kodu',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'Etki alanı',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Uygulama',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Masaüstü',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Ağ',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Sunucu',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'Üretici',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'Versiyon',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'KKler',
	'Class:KnownError/Attribute:ci_list+' => 'The configuration items that are potentially impacted by this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Dokümanlar',
	'Class:KnownError/Attribute:document_list+' => 'All the documents linked to this known error~~',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', [
	'Class:lnkErrorToFunctionalCI' => 'Hata / İşlevsel CI bağla',
	'Class:lnkErrorToFunctionalCI+' => ' Bilinen bir hatayla ilgili alt bilgi',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI Adı',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Hata',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Hata Adı',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Sebep',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '~~',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', [
	'Class:lnkDocumentToError' => 'Belge / hata bağla',
	'Class:lnkDocumentToError+' => 'Bir belge ile bilinen bir hata arasındaki bağlantı',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Belge',
	'Class:lnkDocumentToError/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Belge Adı',
	'Class:lnkDocumentToError/Attribute:document_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Hata',
	'Class:lnkDocumentToError/Attribute:error_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Hata Adı',
	'Class:lnkDocumentToError/Attribute:error_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:link_type' => 'Bağlantı tipi',
	'Class:lnkDocumentToError/Attribute:link_type+' => '~~',
]);

Dict::Add('TR TR', 'Turkish', 'Türkçe', [
	'Menu:ProblemManagement' => 'Problem Yönetimi',
	'Menu:ProblemManagement+' => 'Problem Yönetimi',
	'Menu:Problem:Shortcuts' => 'Kısayollar',
	'Menu:NewError' => 'Yeni bilinen hata',
	'Menu:NewError+' => 'Yeni bilinen hata yatarımı',
	'Menu:SearchError' => 'Bilinen hataları ara',
	'Menu:SearchError+' => 'Bilinen hataları ara',
	'Menu:Problem:KnownErrors' => 'Tüm bilinen hatalar',
	'Menu:Problem:KnownErrors+' => 'Tüm bilinen hatalar',
]);
