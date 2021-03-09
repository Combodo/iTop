<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
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

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:KnownError' => 'Znany błąd',
	'Class:KnownError+' => 'Udokumentowano błąd dotyczący znanego problemu',
	'Class:KnownError/Attribute:name' => 'Nazwa',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Klient',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Nazwa klienta',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Powiązany problem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Powiązane informacje o problemie',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom (objaw)',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Przyczyna',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Obejście',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Rozwiązanie',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Kod błędu',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Domena',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Aplikacja',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Aplikacja',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Sieć',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Sieć',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Serwer',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Serwer',
	'Class:KnownError/Attribute:vendor' => 'Sprzedawca',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Wersja',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'Konfiguracje',
	'Class:KnownError/Attribute:ci_list+' => 'Wszystkie elementy konfiguracji związane z tym znanym błędem',
	'Class:KnownError/Attribute:document_list' => 'Dokumenty',
	'Class:KnownError/Attribute:document_list+' => 'Wszystkie dokumenty związane z tym znanym błędem',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkErrorToFunctionalCI' => 'Połączenie Błąd / Konfiguracja',
	'Class:lnkErrorToFunctionalCI+' => 'Konfiguracje związane ze znanym błędem',
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
));

//
// Class: lnkDocumentToError
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:lnkDocumentToError' => 'Połączenie Dokumenty / Błędy',
	'Class:lnkDocumentToError+' => 'Łącze między dokumentem a znanym błędem',
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
));

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Menu:ProblemManagement' => 'Zarządzanie problemami',
	'Menu:ProblemManagement+' => 'Zarządzanie problemami',
	'Menu:Problem:Shortcuts' => 'Skróty',
	'Menu:NewError' => 'Nowy znany błąd',
	'Menu:NewError+' => 'Utworzenie nowego znanego błędu',
	'Menu:SearchError' => 'Wyszukaj znane błędy',
	'Menu:SearchError+' => 'Wyszukaj znane błędy',
	'Menu:Problem:KnownErrors' => 'Wszystkie znane błędy',
	'Menu:Problem:KnownErrors+' => 'Wszystkie znane błędy',
));
