<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @traductor   Miguel Turrubiates <miguel_tf@yahoo.com> 
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
        'Menu:ProblemManagement' => 'Administración de Problemas',
        'Menu:ProblemManagement+' => 'Administración de Problemas',
        'Menu:Problem:Overview' => 'Resumen de Problemas',
        'Menu:Problem:Overview+' => 'Resumen de Problemas',
        'Menu:NewProblem' => 'Nuevo Problema',
        'Menu:NewProblem+' => 'Nuevo Problema',
        'Menu:SearchProblems' => 'Búsqueda de Problemas',
        'Menu:SearchProblems+' => 'Búsqueda de Problemas',
        'Menu:Problem:Shortcuts' => 'Acceso Rápido',
        'Menu:Problem:MyProblems' => 'Problemas Asignados a Mí',
        'Menu:Problem:MyProblems+' => 'Problemas Asignados a Mí',
        'Menu:Problem:OpenProblems' => 'Problemas Abiertos',
        'Menu:Problem:OpenProblems+' => 'Problemas Abiertos',
	'UI-ProblemManagementOverview-ProblemByService' => 'Problemas por Servicio',
	'UI-ProblemManagementOverview-ProblemByService+' => 'Problemas por Servicio',
	'UI-ProblemManagementOverview-ProblemByPriority' => 'Problemas por Prioridad',
	'UI-ProblemManagementOverview-ProblemByPriority+' => 'Problemas por Prioridad',
	'UI-ProblemManagementOverview-ProblemUnassigned' => 'Problemas Sin Asignación',
	'UI-ProblemManagementOverview-ProblemUnassigned+' => 'Problemas Sin Asignación',
	'UI:ProblemMgmtMenuOverview:Title' => 'Panel de Control de Administración de Problemas',
	'UI:ProblemMgmtMenuOverview:Title+' => 'Panel de Control de Administración de Problemas',

));
//
// Class: Problem
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
        'Class:Problem' => 'Problema',
        'Class:Problem+' => 'Problema',
        'Class:Problem/Attribute:status' => 'Estatus',
        'Class:Problem/Attribute:status+' => 'Estatus',
        'Class:Problem/Attribute:status/Value:new' => 'Nuevo',
        'Class:Problem/Attribute:status/Value:new+' => 'Nuevo',
        'Class:Problem/Attribute:status/Value:assigned' => 'Asignado',
        'Class:Problem/Attribute:status/Value:assigned+' => 'Asignado',
        'Class:Problem/Attribute:status/Value:resolved' => 'Solucionado',
        'Class:Problem/Attribute:status/Value:resolved+' => 'Solucionado',
        'Class:Problem/Attribute:status/Value:closed' => 'Cerrado',
        'Class:Problem/Attribute:status/Value:closed+' => 'Cerrado',
        'Class:Problem/Attribute:service_id' => 'Servicio',
        'Class:Problem/Attribute:service_id+' => 'Servicio',
        'Class:Problem/Attribute:service_name' => 'Identificación del Servicio',
        'Class:Problem/Attribute:service_name+' => 'Identificación del Servicio',
        'Class:Problem/Attribute:servicesubcategory_id' => 'Subcategoría',
        'Class:Problem/Attribute:servicesubcategory_id+' => 'Subcategoría del Servicio',
        'Class:Problem/Attribute:servicesubcategory_name' => 'Subcategoría del Servicio',
        'Class:Problem/Attribute:servicesubcategory_name+' => 'Subcategoría del Servicio',
        'Class:Problem/Attribute:product' => 'Producto',
        'Class:Problem/Attribute:product+' => 'Producto',
        'Class:Problem/Attribute:impact' => 'Impacto',
        'Class:Problem/Attribute:impact+' => 'Impacto',
        'Class:Problem/Attribute:impact/Value:1' => 'Un Departamento',
        'Class:Problem/Attribute:impact/Value:1+' => 'Un Departamento',
        'Class:Problem/Attribute:impact/Value:2' => 'Un Servicio',
        'Class:Problem/Attribute:impact/Value:2+' => 'Un Servicio',
        'Class:Problem/Attribute:impact/Value:3' => 'Una Person',
        'Class:Problem/Attribute:impact/Value:3+' => 'Una Persona',
        'Class:Problem/Attribute:urgency' => 'Urgencia',
        'Class:Problem/Attribute:urgency+' => 'Urgencia',
        'Class:Problem/Attribute:urgency/Value:1' => 'Crítico',
        'Class:Problem/Attribute:urgency/Value:1+' => 'Crítico',
        'Class:Problem/Attribute:urgency/Value:2' => 'Alto',
        'Class:Problem/Attribute:urgency/Value:2+' => 'Alto',
        'Class:Problem/Attribute:urgency/Value:3' => 'Medio',
        'Class:Problem/Attribute:urgency/Value:3+' => 'Medio',
        'Class:Problem/Attribute:urgency/Value:3' => 'Bajo',
        'Class:Problem/Attribute:urgency/Value:3+' => 'Bajo',
        'Class:Problem/Attribute:priority' => 'Prioridad',
        'Class:Problem/Attribute:priority+' => 'Prioridad',
        'Class:Problem/Attribute:priority/Value:1' => 'Crítico',
        'Class:Problem/Attribute:priority/Value:1+' => 'Crítico',
        'Class:Problem/Attribute:priority/Value:2' => 'Alto',
        'Class:Problem/Attribute:priority/Value:2+' => 'Alto',
        'Class:Problem/Attribute:priority/Value:3' => 'Medio',
        'Class:Problem/Attribute:priority/Value:3+' => 'Medio',
        'Class:Problem/Attribute:priority/Value:4' => 'Bajo',
        'Class:Problem/Attribute:priority/Value:4+' => 'Bajo',
        'Class:Problem/Attribute:related_change_id' => 'Cambio Relacionado',
        'Class:Problem/Attribute:related_change_id+' => 'Cambio Relacionado',
        'Class:Problem/Attribute:related_change_ref' => 'Cambio Relacionado',
        'Class:Problem/Attribute:related_change_ref+' => 'Cambio Relacionado',
        'Class:Problem/Attribute:assignment_date' => 'Fecha de Asignación',
        'Class:Problem/Attribute:assignment_date+' => 'Fecha de Asignación',
        'Class:Problem/Attribute:resolution_date' => 'Fecha de Solución',
        'Class:Problem/Attribute:resolution_date+' => 'Fecha de Solución',
        'Class:Problem/Attribute:knownerrors_list' => 'Errores Conocidos',
        'Class:Problem/Attribute:knownerrors_list+' => 'Errores Conocidos',
	      'Class:Problem/Attribute:related_request_list' => 'Requerimientos Relacionados',
	      'Class:Problem/Attribute:related_request_list+' => 'Requerimientos Relacionados',
        'Class:Problem/Stimulus:ev_assign' => 'Asignar',
        'Class:Problem/Stimulus:ev_assign+' => 'Asignar',
        'Class:Problem/Stimulus:ev_reassign' => 'Reasignar',
        'Class:Problem/Stimulus:ev_reassign+' => 'Reasignar',
        'Class:Problem/Stimulus:ev_resolve' => 'Solucionar',
        'Class:Problem/Stimulus:ev_resolve+' => 'Solucionar',
        'Class:Problem/Stimulus:ev_close' => 'Cerrar',
        'Class:Problem/Stimulus:ev_close+' => 'Cerrar',
));

?>
