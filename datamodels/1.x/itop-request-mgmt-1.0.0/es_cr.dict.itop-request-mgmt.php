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

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Menu:RequestManagement' => 'Servicio de ayuda',
	'Menu:RequestManagement+' => 'Servicio de ayuda',
	'Menu:UserRequest:Overview' => 'Visión General',
	'Menu:UserRequest:Overview+' => 'Visión General',
	'Menu:UserRequest:MyRequests' => 'Solicitudes asignadas a mí',
	'Menu:UserRequest:MyRequests+' => 'Solicitudes asignadas a mí (como Agente)',
	'Menu:UserRequest:EscalatedRequests' => 'Solicitudes Escaladas',
	'Menu:UserRequest:EscalatedRequests+' => 'Solicitudes Escaladas',
	'Menu:UserRequest:OpenRequests' => 'Todas las Solicitudes Abiertas',
	'Menu:UserRequest:OpenRequests+' => 'Todas las Solicitudes Abiertas',
));

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
// Class: UserRequest
//

Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:UserRequest' => 'Solicitud por parte de Usuario',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:freeze_reason' => 'Razón de Suspensión',
	'Class:UserRequest/Attribute:freeze_reason+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Asignar',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_freeze' => 'Marcar como Suspendida',
	'Class:UserRequest/Stimulus:ev_freeze+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Re-asignar',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'Tiempo Fuera del incidente',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Marcar como resuelto',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Cerrar',
	'Class:UserRequest/Stimulus:ev_close+' => '',
));

?>
