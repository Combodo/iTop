<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('PT BR', 'Brazilian', 'Brazilian', [
	'Class:UserLocal' => 'Usuário local',
	'Class:UserLocal+' => '',
	'Class:UserLocal/Attribute:expiration' => 'Expiração da senha',
	'Class:UserLocal/Attribute:expiration+' => 'Status de expiração de senha (Requer uma extensão para fazer efeito)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Senha expira',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Senha expirada',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Senha nunca expira',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'One-Time Password (OTP)',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Senha não pode ser alterada pelo usuário',
	'Class:UserLocal/Attribute:password' => 'Senha',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Data da última alteração de senha',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Quando a senha foi alterada anteriormente',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Definir a expiração da senha para One-Time Password (OTP) não é permitido para o seu próprio usuário',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'A senha deve ter no mínimo 8 caracteres e incluir letras maiúsculas, minúsculas, números e símbolos',
	'UserLocal:password:expiration' => 'O campo abaixo requer uma extensão',
]);
