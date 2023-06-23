<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:KnownError' => 'Ismert hiba',
	'Class:KnownError+' => '',
	'Class:KnownError/Attribute:name' => 'Név',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Ügyfél',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Ügyfél neve',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Kapcsolódó probléma',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Referencia',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Hibajelenség',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Gyökérok',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Elkerülő megoldás',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Megoldás',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Hibakód',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Hibatartomány',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Alkalmazás',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Hálózat',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Szerver',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Gyártó',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modell',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Verzió',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CI-k',
	'Class:KnownError/Attribute:ci_list+' => 'All the configuration items that are related to this known error~~',
	'Class:KnownError/Attribute:document_list' => 'Dokumentumok',
	'Class:KnownError/Attribute:document_list+' => 'All the documents linked to this known error~~',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
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
));

//
// Class: lnkDocumentToError
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:lnkDocumentToError' => 'Dokumentumok / Hibák',
	'Class:lnkDocumentToError+' => 'A link between a document and a known error~~',
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
));

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Menu:ProblemManagement' => 'Problémakezelés',
	'Menu:ProblemManagement+' => '',
	'Menu:Problem:Shortcuts' => 'Gyorsgomb',
	'Menu:NewError' => 'Új ismert hiba',
	'Menu:NewError+' => '',
	'Menu:SearchError' => 'Ismert hiba keresés',
	'Menu:SearchError+' => '',
	'Menu:Problem:KnownErrors' => 'Összes ismert hiba',
	'Menu:Problem:KnownErrors+' => '',
));
