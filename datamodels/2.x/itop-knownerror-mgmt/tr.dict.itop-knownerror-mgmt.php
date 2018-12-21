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
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//
// Class: KnownError
//
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
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Dokümanlar',
	'Class:KnownError/Attribute:document_list+' => '',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkErrorToFunctionalCI' => 'Link Error / FunctionalCI~~',
	'Class:lnkErrorToFunctionalCI+' => 'Infra related to a known error~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI name~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'Error~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'Error name~~',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => 'Reason~~',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '~~',
));

//
// Class: lnkDocumentToError
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentToError' => 'Link Documents / Errors~~',
	'Class:lnkDocumentToError+' => 'A link between a document and a known error~~',
	'Class:lnkDocumentToError/Attribute:document_id' => 'Document~~',
	'Class:lnkDocumentToError/Attribute:document_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:document_name' => 'Document Name~~',
	'Class:lnkDocumentToError/Attribute:document_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_id' => 'Error~~',
	'Class:lnkDocumentToError/Attribute:error_id+' => '~~',
	'Class:lnkDocumentToError/Attribute:error_name' => 'Error name~~',
	'Class:lnkDocumentToError/Attribute:error_name+' => '~~',
	'Class:lnkDocumentToError/Attribute:link_type' => 'link_type~~',
	'Class:lnkDocumentToError/Attribute:link_type+' => '~~',
));

//
// Class: FAQ
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FAQ' => 'FAQ~~',
	'Class:FAQ+' => 'Frequently asked questions~~',
	'Class:FAQ/Attribute:title' => 'Title~~',
	'Class:FAQ/Attribute:title+' => '~~',
	'Class:FAQ/Attribute:summary' => 'Summary~~',
	'Class:FAQ/Attribute:summary+' => '~~',
	'Class:FAQ/Attribute:description' => 'Description~~',
	'Class:FAQ/Attribute:description+' => '~~',
	'Class:FAQ/Attribute:category_id' => 'Category~~',
	'Class:FAQ/Attribute:category_id+' => '~~',
	'Class:FAQ/Attribute:category_name' => 'Category name~~',
	'Class:FAQ/Attribute:category_name+' => '~~',
	'Class:FAQ/Attribute:error_code' => 'Error code~~',
	'Class:FAQ/Attribute:error_code+' => '~~',
	'Class:FAQ/Attribute:key_words' => 'Key words~~',
	'Class:FAQ/Attribute:key_words+' => '~~',
	'Class:FAQ/Attribute:domains' => 'Domains~~',
));

//
// Class: FAQCategory
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:FAQCategory' => 'FAQ Category~~',
	'Class:FAQCategory+' => 'Category for FAQ~~',
	'Class:FAQCategory/Attribute:name' => 'Name~~',
	'Class:FAQCategory/Attribute:name+' => '~~',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQs~~',
	'Class:FAQCategory/Attribute:faq_list+' => 'All the frequently asked questions related to this category~~',
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
	'Menu:FAQCategory' => 'FAQ categories~~',
	'Menu:FAQCategory+' => 'All FAQ categories~~',
	'Menu:FAQ' => 'FAQs~~',
	'Menu:FAQ+' => 'All FAQs~~',

	'Brick:Portal:FAQ:Menu' => 'FAQ~~',
	'Brick:Portal:FAQ:Title' => 'Frequently Asked Questions~~',
	'Brick:Portal:FAQ:Title+' => '<p>In a hurry?</p><p>Check out the list of most common questions and (maybe) find the expected answer right away.</p>~~',
));
