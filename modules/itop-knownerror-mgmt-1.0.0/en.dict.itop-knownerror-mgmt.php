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
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:KnownError' => 'Known Error',
	'Class:KnownError+' => 'Error documented for a known issue',
	'Class:KnownError/Attribute:name' => 'Name',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => 'Customer',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => 'Customer Name',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => 'Related Problem',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => 'Ref',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => 'Symptom',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => 'Root Cause',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => 'Workaround',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => 'Solution',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'Error Code',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'Domain',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'Application',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'Desktop',
	'Class:KnownError/Attribute:domain/Value:Network' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'Network',
	'Class:KnownError/Attribute:domain/Value:Server' => 'Server',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'Server',
	'Class:KnownError/Attribute:vendor' => 'Vendor',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'Model',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'Version',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CIs',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => 'Documents',
	'Class:KnownError/Attribute:document_list+' => '',
));


//
// Class: lnkInfraError
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkInfraError' => 'InfraErrorLinks',
	'Class:lnkInfraError+' => 'Infra related to a known error',
	'Class:lnkInfraError/Attribute:infra_id' => 'CI',
	'Class:lnkInfraError/Attribute:infra_id+' => '',
	'Class:lnkInfraError/Attribute:infra_name' => 'CI Name',
	'Class:lnkInfraError/Attribute:infra_name+' => '',
	'Class:lnkInfraError/Attribute:infra_status' => 'CI Status',
	'Class:lnkInfraError/Attribute:infra_status+' => '',
	'Class:lnkInfraError/Attribute:error_id' => 'Error',
	'Class:lnkInfraError/Attribute:error_id+' => '',
	'Class:lnkInfraError/Attribute:error_name' => 'Error name',
	'Class:lnkInfraError/Attribute:error_name+' => '',
	'Class:lnkInfraError/Attribute:reason' => 'Reason',
	'Class:lnkInfraError/Attribute:reason+' => '',
));

//
// Class: lnkDocumentError
//

Dict::Add('EN US', 'English', 'English', array(
	'Class:lnkDocumentError' => 'DocumentsErrorLinks',
	'Class:lnkDocumentError+' => 'A link between a document and a known error',
	'Class:lnkDocumentError/Attribute:doc_id' => 'Document',
	'Class:lnkDocumentError/Attribute:doc_id+' => '',
	'Class:lnkDocumentError/Attribute:doc_name' => 'Document Name',
	'Class:lnkDocumentError/Attribute:doc_name+' => '',
	'Class:lnkDocumentError/Attribute:error_id' => 'Error',
	'Class:lnkDocumentError/Attribute:error_id+' => '',
	'Class:lnkDocumentError/Attribute:error_name' => 'Error Name',
	'Class:lnkDocumentError/Attribute:error_name+' => '',
	'Class:lnkDocumentError/Attribute:link_type' => 'Information',
	'Class:lnkDocumentError/Attribute:link_type+' => '',
));


?>
