<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Miguel Turrubiates <miguel_tf@yahoo.com>
 *
 */
Dict::Add('ES CR', 'Spanish', 'Español, Castellano', [
	'Class:UserLocal' => 'Usuario de '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Usuario Autenticado vía '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Expiración de contraseña',
	'Class:UserLocal/Attribute:expiration+' => 'Estatus de expiración de contraseña (requiere de una extensión para que tenga efecto)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Puede expirar',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expirado',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nunca expirar',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Contraseña de un solo uso',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'El usuario no puede cambiar la contraseña.',
	'Class:UserLocal/Attribute:password' => 'Contraseña',
	'Class:UserLocal/Attribute:password+' => 'Contraseña',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Renovación de contraseña',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Cuando fue el último cambio de contraseña',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Configurar expiración de contraseña para "ontraseña de un solo uso" no está permitido para su propio Usuario',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'La contraseña debe ser de al menos 8 caracteres e incluír mayúsculas, minúsculas, números y caracteres especiales.',
	'UserLocal:password:expiration' => 'El siguiente campo requiere una extensión',
]);
