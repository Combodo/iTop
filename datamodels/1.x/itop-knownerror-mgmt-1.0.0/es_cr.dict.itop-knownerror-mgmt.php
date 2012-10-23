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
// Class: lnkDocumentError
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
        'Class:lnkDocumentError' => 'Error / Tiquete',
        'Class:lnkDocumentError+' => 'Error / Tiquete',
        'Class:lnkDocumentError/Attribute:doc_id' => 'Documento',
        'Class:lnkDocumentError/Attribute:doc_id+' => '',
        'Class:lnkDocumentError/Attribute:doc_name' => 'Identificación del Documento',
        'Class:lnkDocumentError/Attribute:doc_name+' => '',
        'Class:lnkDocumentError/Attribute:error_id' => 'Error',
        'Class:lnkDocumentError/Attribute:error_id+' => '',
        'Class:lnkDocumentError/Attribute:error_name' => 'Identificación del Error',
        'Class:lnkDocumentError/Attribute:error_name+' => '',
        'Class:lnkDocumentError/Attribute:link_type' => 'Información',
        'Class:lnkDocumentError/Attribute:link_type+' => '',
));

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
        'Menu:ProblemManagement' => 'Gestión de Errores',
        'Menu:ProblemManagement+' => 'Gestión de Errores',
        'Menu:NewError' => 'Nueva Error',
        'Menu:NewError+' => 'Nueva Error',
        'Menu:SearchError' => 'Búsqueda de errores ',
        'Menu:SearchError+' => 'Búsqueda de errores',
        'Menu:Problem:KnownErrors' => 'Todos los errores',
        'Menu:Problem:KnownErrors+' => 'Todos los errores',
));

?>
