<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author ITOMIG GmbH <martin.raenker@itomig.de>
 *
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.'-Benutzer',
	'Class:UserLocal+' => 'Benutzer, der von '.ITOP_APPLICATION_SHORT.' authentifiziert wird',
	'Class:UserLocal/Attribute:expiration' => 'Passwortablauf',
	'Class:UserLocal/Attribute:expiration+' => 'Passwortablaufstatus (statusabhängige Effekte müssen per Extension implementiert werden)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'kann ablaufen',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'abgelaufen',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'läuft nie ab',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'einmaliges Passwort',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => '',
	'Class:UserLocal/Attribute:password' => 'Passwort',
	'Class:UserLocal/Attribute:password+' => 'Benutzerpasswort',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Letzte Passworterneuerung',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Letztes Änderungsdatum',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Das setzen des Passwortablaufs auf "Einmalpasswort" ist für den eigenen Benutzer nicht erlaubt.',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Das Passwort entspricht nicht dem in den Konfigurationsregeln hinterlegten RegEx-Ausdruck',
	'UserLocal:password:expiration' => 'Die folgenden Felder benötigen eine '.ITOP_APPLICATION_SHORT.' Erweiterung',
]);
