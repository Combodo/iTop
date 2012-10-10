<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
// Class: lnkInfraError
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkInfraError' => 'Altyapı hata bağlantısı',
	'Class:lnkInfraError+' => 'Alyapı ile ilgili bilinen hata',
	'Class:lnkInfraError/Attribute:infra_id' => 'KK',
	'Class:lnkInfraError/Attribute:infra_id+' => '',
	'Class:lnkInfraError/Attribute:infra_name' => 'KK Adı',
	'Class:lnkInfraError/Attribute:infra_name+' => '',
	'Class:lnkInfraError/Attribute:infra_status' => 'KK durumu',
	'Class:lnkInfraError/Attribute:infra_status+' => '',
	'Class:lnkInfraError/Attribute:error_id' => 'Hata',
	'Class:lnkInfraError/Attribute:error_id+' => '',
	'Class:lnkInfraError/Attribute:error_name' => 'Hata adı',
	'Class:lnkInfraError/Attribute:error_name+' => '',
	'Class:lnkInfraError/Attribute:reason' => 'Sebep',
	'Class:lnkInfraError/Attribute:reason+' => '',
));

//
// Class: lnkDocumentError
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkDocumentError' => 'Bağlı Doküman hatası',
	'Class:lnkDocumentError+' => 'Doküman ve bilinen hata bağlantısı',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Doküman',
	'Class:lnkDocumentError/Attribute:doc_id+' => '',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Doküman Adı',
	'Class:lnkDocumentError/Attribute:doc_name+' => '',
	'Class:lnkDocumentError/Attribute:error_id' => 'Hata',
	'Class:lnkDocumentError/Attribute:error_id+' => '',
	'Class:lnkDocumentError/Attribute:error_name' => 'Hata Adı',
	'Class:lnkDocumentError/Attribute:error_name+' => '',
	'Class:lnkDocumentError/Attribute:link_type' => 'Bilgi',
	'Class:lnkDocumentError/Attribute:link_type+' => '',
));

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Menu:NewError' => 'Yeni bilinen hata',
	'Menu:NewError+' => 'Yeni bilinen hata yatarımı',
	'Menu:SearchError' => 'Bilinen hataları ara',
	'Menu:SearchError+' => 'Bilinen hataları ara',
        'Menu:Problem:KnownErrors' => 'Tüm bilinen hatalar',
        'Menu:Problem:KnownErrors+' => 'Tüm bilinen hatalar',
));
?>
