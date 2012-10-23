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

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
        'Menu:ProblemManagement' => 'Gestión de problemas',
        'Menu:ProblemManagement+' => 'Gestión de problemas',
        'Menu:Problem:Overview' => 'Visión general',
        'Menu:Problem:Overview+' => 'Visión general',
        'Menu:NewProblem' => 'Nuevo problema',
        'Menu:NewProblem+' => 'Nuevo problema',
        'Menu:SearchProblems' => 'Busqueda de problemas',
        'Menu:SearchProblems+' => 'Busqueda de problemas',
        'Menu:Problem:KnownErrors' => 'Errores',
        'Menu:Problem:KnownErrors+' => 'Errores',
        'Menu:Problem:Shortcuts' => 'Atajo',
        'Menu:Problem:MyProblems' => 'Problemas assignado a mi',
        'Menu:Problem:MyProblems+' => 'Problemas assignado a mi',
        'Menu:Problem:OpenProblems' => 'Todas las problemas abiertos',
        'Menu:Problem:OpenProblems+' => 'Todas las problemas abiertos',

));

// Class: Problem
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
        'Class:Problem' => 'Problema',
        'Class:Problem+' => '',
        'Class:Problem/Attribute:status' => 'Estado',
        'Class:Problem/Attribute:status+' => '',
        'Class:Problem/Attribute:status/Value:new' => 'Nuevo',
        'Class:Problem/Attribute:status/Value:new+' => '',
        'Class:Problem/Attribute:status/Value:assigned' => 'Assignada',
        'Class:Problem/Attribute:status/Value:assigned+' => '',
        'Class:Problem/Attribute:status/Value:resolved' => 'Resuelto',
        'Class:Problem/Attribute:status/Value:resolved+' => '',
        'Class:Problem/Attribute:status/Value:closed' => 'Cerrado',
        'Class:Problem/Attribute:status/Value:closed+' => '',
        'Class:Problem/Attribute:org_id' => 'Cliente',
        'Class:Problem/Attribute:org_id+' => '',
        'Class:Problem/Attribute:org_name' => 'Nombre',
        'Class:Problem/Attribute:org_name+' => '',
        'Class:Problem/Attribute:service_id' => 'Servicio',
        'Class:Problem/Attribute:service_id+' => '',
        'Class:Problem/Attribute:service_name' => 'Identificación del Servicio',
        'Class:Problem/Attribute:service_name+' => '',
        'Class:Problem/Attribute:servicesubcategory_id' => 'Elemento de Servicio',
        'Class:Problem/Attribute:servicesubcategory_id+' => '',
        'Class:Problem/Attribute:servicesubcategory_name' => 'Identificación dele elemento de Servicio',
        'Class:Problem/Attribute:servicesubcategory_name+' => '',
        'Class:Problem/Attribute:product' => 'Producto',
        'Class:Problem/Attribute:product+' => '',
        'Class:Problem/Attribute:impact' => 'Impacto',
        'Class:Problem/Attribute:impact+' => '',
        'Class:Problem/Attribute:impact/Value:1' => 'Un Departamento',
        'Class:Problem/Attribute:impact/Value:1+' => '',
        'Class:Problem/Attribute:impact/Value:2' => 'Un Servico',
        'Class:Problem/Attribute:impact/Value:2+' => '',
        'Class:Problem/Attribute:impact/Value:3' => 'Una persona',
        'Class:Problem/Attribute:impact/Value:3+' => '',
        'Class:Problem/Attribute:urgency' => 'Urgenca',
        'Class:Problem/Attribute:urgency+' => '',
        'Class:Problem/Attribute:urgency/Value:1' => 'Alto',
        'Class:Problem/Attribute:urgency/Value:1+' => 'Alto',
        'Class:Problem/Attribute:urgency/Value:2' => 'Medio',
        'Class:Problem/Attribute:urgency/Value:2+' => 'Medio',
        'Class:Problem/Attribute:urgency/Value:3' => 'Bajo',
        'Class:Problem/Attribute:urgency/Value:3+' => 'Bajo',
        'Class:Problem/Attribute:priority' => 'Priority',
        'Class:Problem/Attribute:priority+' => '',
        'Class:Problem/Attribute:priority/Value:1' => 'Alto',
        'Class:Problem/Attribute:priority/Value:1+' => '',
        'Class:Problem/Attribute:priority/Value:2' => 'Medio',
        'Class:Problem/Attribute:priority/Value:2+' => '',
        'Class:Problem/Attribute:priority/Value:3' => 'Bajo',
        'Class:Problem/Attribute:priority/Value:3+' => '',
        'Class:Problem/Attribute:workgroup_id' => 'Grupo de Travajo',
        'Class:Problem/Attribute:workgroup_id+' => '',
        'Class:Problem/Attribute:workgroup_name' => 'Identificación de Grupo de Trabajo',
        'Class:Problem/Attribute:workgroup_name+' => '',
        'Class:Problem/Attribute:agent_id' => 'Agent',
        'Class:Problem/Attribute:agent_id+' => '',
        'Class:Problem/Attribute:agent_name' => 'Agent',
        'Class:Problem/Attribute:agent_name+' => '',
        'Class:Problem/Attribute:agent_email' => 'Agent Email',
        'Class:Problem/Attribute:agent_email+' => '',
        'Class:Problem/Attribute:related_change_id' => 'Modificación Relacionada',
        'Class:Problem/Attribute:related_change_id+' => '',
        'Class:Problem/Attribute:related_change_ref' => 'Modificación Relacionada',
        'Class:Problem/Attribute:related_change_ref+' => '',
        'Class:Problem/Attribute:close_date' => 'Cerrada',
        'Class:Problem/Attribute:close_date+' => '',
        'Class:Problem/Attribute:last_update' => 'Última Actualización',
        'Class:Problem/Attribute:last_update+' => '',
        'Class:Problem/Attribute:assignment_date' => 'Asignada',
        'Class:Problem/Attribute:assignment_date+' => '',
        'Class:Problem/Attribute:resolution_date' => 'Fecha de Resolución',
        'Class:Problem/Attribute:resolution_date+' => '',
        'Class:Problem/Attribute:knownerrors_list' => 'Errores',
        'Class:Problem/Attribute:knownerrors_list+' => '',
        'Class:Problem/Stimulus:ev_assign' => 'Asignar',
        'Class:Problem/Stimulus:ev_assign+' => '',
        'Class:Problem/Stimulus:ev_reassign' => 'Re-asignar',
        'Class:Problem/Stimulus:ev_reassign+' => '',
        'Class:Problem/Stimulus:ev_resolve' => 'Marcar como Resuelto',
        'Class:Problem/Stimulus:ev_resolve+' => '',
        'Class:Problem/Stimulus:ev_close' => 'Cerrar',
        'Class:Problem/Stimulus:ev_close+' => '',
));

?>
