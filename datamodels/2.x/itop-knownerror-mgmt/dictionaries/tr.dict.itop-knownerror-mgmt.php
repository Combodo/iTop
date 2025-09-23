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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:KnownError' => 'Bilinen hata',
	'Class:KnownError+' => 'Hata bilinen hatalara kaydedildi',
	'Class:KnownError/Attribute:name' => 'Adı',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Müşteri',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Müşteri Adı',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'İlgili problem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Referans',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Belirtisi',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Ana sebep',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Ara çözüm',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Çözüm',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Hata kodu',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Etki alanı',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Uygulama',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Uygulama',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Masaüstü',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Masaüstü',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Ağ',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Ağ',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Sunucu',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Sunucu',
	'Class:KnownError/Attribute:vendor' => 'Üretici',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Versiyon',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'KKler',
	'Class:KnownError/Attribute:ci_list+' => 'All the configuration items that are related to this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Dokümanlar',
	'Class:KnownError/Attribute:document_list+' => 'All the documents linked to this known error~~',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
));

//
// Class: lnkDocumentToError
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
));

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:ProblemManagement' => 'Problem Yönetimi',
	'Menu:ProblemManagement+' => 'Problem Yönetimi',
	'Menu:Problem:Shortcuts' => 'Kısayollar',
	'Menu:NewError' => 'Yeni bilinen hata',
	'Menu:NewError+' => 'Yeni bilinen hata yatarımı',
	'Menu:SearchError' => 'Bilinen hataları ara',
	'Menu:SearchError+' => 'Bilinen hataları ara',
	'Menu:Problem:KnownErrors' => 'Tüm bilinen hatalar',
	'Menu:Problem:KnownErrors+' => 'Tüm bilinen hatalar',
));
