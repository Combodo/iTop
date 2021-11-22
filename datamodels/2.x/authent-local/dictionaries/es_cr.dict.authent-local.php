<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * Spanish Localized data
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
//
// Class: UserLocal
//
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', array(
	'Class:UserLocal' => 'Usuario de '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Usuario Autenticado vía '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Contraseña',
	'Class:UserLocal/Attribute:password+' => 'Contraseña',

	'Class:UserLocal/Attribute:expiration' => 'Expiración de contraseña',
	'Class:UserLocal/Attribute:expiration+' => 'Estatus de expiración de contraseña (requiere de una extensión para que tenga efecto)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Puede expirar',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nunca expirar',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expirado',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Renovación de contraseña',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Cuando fue el último cambio de contraseña',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'La contraseña debe ser de al menos 8 caracteres e incluír mayúsculas, minúsculas, números y caracteres especiales.',

	'UserLocal:password:expiration' => 'El siguiente campo requiere una extensión'
));
