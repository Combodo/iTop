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
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:UserLocal' => 'Utente '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Utente autenticato da '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Scadenza della password',
	'Class:UserLocal/Attribute:expiration+' => 'Stato della scadenza della password (richiede un\'estensione per avere effetto)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Può scadere',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Scaduta',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Non scade',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Password monouso',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'La password non può essere cambiata dall\'utente.',
	'Class:UserLocal/Attribute:password' => 'Password',
	'Class:UserLocal/Attribute:password+' => 'stringa di autenticazione utente',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Rinnovo della password',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Quando è stata cambiata l\'ultima volta la password',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Impostare la scadenza della password su "Password monouso" non è consentito per il proprio utente',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'La password deve essere di almeno 8 caratteri e includere lettere maiuscole, minuscole, numeri e caratteri speciali.',
	'UserLocal:password:expiration' => 'I campi sottostanti richiedono un\'estensione',
]);
