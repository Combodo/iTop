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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:UserLocal' => 'Utilisateur iTop',
	'Class:UserLocal+' => 'Utilisateur authentifié par iTop',
	'Class:UserLocal/Attribute:password' => 'Mot de passe',
	'Class:UserLocal/Attribute:password+' => '',

	'Class:UserLocal/Attribute:expiration' => 'Validité du mot de passe',
	'Class:UserLocal/Attribute:expiration+' => 'Statut du mot de passe (nécessite une extension pour avoir un effet)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'à durée limitée',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'à validité permanente',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'à changer',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Mot de passe changé le',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Dernière date à laquelle le mot de passe a été changé',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Le mot de passe doit contenir au moins 8 caractères, avec minuscule, majuscule, nombre et caractère spécial.',

	'UserLocal:password:expiration' => 'Les champs ci-dessous nécessitent une extension'
));
