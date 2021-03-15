<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', array(
	'Class:UserLocal' => 'Usuário local',
	'Class:UserLocal+' => '',
	'Class:UserLocal/Attribute:password' => 'Senha',
	'Class:UserLocal/Attribute:password+' => '',

	'Class:UserLocal/Attribute:expiration' => 'Expiração de senha',
	'Class:UserLocal/Attribute:expiration+' => 'Status de expiraçãoo de senha (requer uma extensão para fazer efeito)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Pode expirar',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nunca expira',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expirada',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Renovação de senha',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Quando a senha foi trocada antiormente',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'A senha deve ter no mínimo 8 caracteres e incluir letras maiúsculas, minúsculas, números e símbolos.',

	'UserLocal:password:expiration' => 'O campo abaixo requer uma extensão'
));
