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

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:KnownError' => 'Errori conosciuti',
	'Class:KnownError+' => 'Errori documentati per problemi noti',
	'Class:KnownError/Attribute:name' => 'Nome',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Cliente ',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Nome del cliente',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Problema correlato',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ref',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Sintomo',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Causa principale',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Soluzione temporanea',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Solutione',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Codice di errore',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Dominio',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Applicazione',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Applicazione',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Venditore',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Modello',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Versione',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Documenti',
	'Class:KnownError/Attribute:document_list+' => '',
));


//
// Class: lnkInfraError
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkInfraError' => 'InfraErrorLinks',
	'Class:lnkInfraError+' => 'Infra relativi a un errore noto',
	'Class:lnkInfraError/Attribute:infra_id' => 'CI',
	'Class:lnkInfraError/Attribute:infra_id+' => '',
	'Class:lnkInfraError/Attribute:infra_name' => 'Nome CI',
	'Class:lnkInfraError/Attribute:infra_name+' => '',
	'Class:lnkInfraError/Attribute:infra_status' => 'CI Stato',
	'Class:lnkInfraError/Attribute:infra_status+' => '',
	'Class:lnkInfraError/Attribute:error_id' => 'Errore',
	'Class:lnkInfraError/Attribute:error_id+' => '',
	'Class:lnkInfraError/Attribute:error_name' => 'Nome Errore',
	'Class:lnkInfraError/Attribute:error_name+' => '',
	'Class:lnkInfraError/Attribute:reason' => 'Motivo',
	'Class:lnkInfraError/Attribute:reason+' => '',
));

//
// Class: lnkDocumentError
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:lnkDocumentError' => 'DocumentsErrorLinks',
	'Class:lnkDocumentError+' => 'Un collegamento tra un documento e un errore noto',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Documento',
	'Class:lnkDocumentError/Attribute:doc_id+' => '',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Nome Documento',
	'Class:lnkDocumentError/Attribute:doc_name+' => '',
	'Class:lnkDocumentError/Attribute:error_id' => 'Errore',
	'Class:lnkDocumentError/Attribute:error_id+' => '',
	'Class:lnkDocumentError/Attribute:error_name' => 'Nome Errore',
	'Class:lnkDocumentError/Attribute:error_name+' => '',
	'Class:lnkDocumentError/Attribute:link_type' => 'Informazione',
	'Class:lnkDocumentError/Attribute:link_type+' => '',
));

Dict::Add('IT IT', 'Italian', 'Italian', array(
	'Menu:ProblemManagement' => 'Gestione dei Problemi',
	'Menu:ProblemManagement+' => 'Gestione dei Problemi',
	'Menu:Problem:Shortcuts' => 'Scorciatoia',
	'Menu:NewError' => 'Nuovo errore conosciuto',
	'Menu:NewError+' => 'Creazione di un Nuovo Errore Conosciuto',
	'Menu:SearchError' => 'Ricerca per Errori Conosciuti',
	'Menu:SearchError+' => 'Ricerca per Errori Conosciuti',
        'Menu:Problem:KnownErrors' => 'Tutti gli errori conosciuti',
        'Menu:Problem:KnownErrors+' => 'Tutti gli errori conosciuti',
));
?>
