<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 *
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.'-gebruiker',
	'Class:UserLocal+' => 'Gebruiker die aanmeldt met gegevens aangemaakt in het gebruikersbeheer van '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Wachtwoord verloopt',
	'Class:UserLocal/Attribute:expiration+' => 'Of het wachtwoord al dan niet verlopen is (vereist een extensie vooraleer dit werkt)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Kan verlopen',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Moet veranderd worden',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Verloopt nooit',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Eenmalig wachtwoord',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'De gebruiker kan dit wachtwoord niet veranderen.',
	'Class:UserLocal/Attribute:password' => 'Wachtwoord',
	'Class:UserLocal/Attribute:password+' => 'Het wachtwoord waarmee de gebruiker zich aanmeldt bij '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password_renewed_date' => 'Wachtwoord laatst aangepast',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Tijdstip waarop het wachtwoord het laatst aangepast werd.',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Je kan geen eenmalig wachtwoord instellen voor je eigen gebruiker.',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Het wachtwoord bestaat uit minstens 8 tekens en bestaat uit een mix van minstens 1 hoofdletter, kleine letter, cijfer en speciaal teken.',
	'UserLocal:password:expiration' => 'De velden hieronder vereisen een extensie.',
]);
