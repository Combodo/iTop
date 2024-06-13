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
Dict::Add('PL PL', 'Polish', 'Polski', [
	'Class:UserLocal' => 'Użytkownik '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Użytkownik uwierzytelniony przez '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Wygaśnięcie hasła',
	'Class:UserLocal/Attribute:expiration+' => 'Stan wygaśnięcia hasła (wymaga rozszerzenia, aby zadziałało)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Może wygasnąć',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Wygasło',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nigdy nie wygasa',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Jednorazowe hasło',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Hasło nie może być zmienione przez użytkownika.',
	'Class:UserLocal/Attribute:password' => 'Hasło',
	'Class:UserLocal/Attribute:password+' => 'Ciąg uwierzytelniania użytkownika',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Odnowienie hasła',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Kiedy ostatnio zmieniano hasło',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Ustawienie wygaśnięcia hasła "Hasło jednorazowe" nie jest dozwolone dla własnego użytkownika',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Hasło musi mieć co najmniej 8 znaków i zawierać duże, małe litery, cyfry i znaki specjalne.',
	'UserLocal:password:expiration' => 'Poniższe pola wymagają rozszerzenia',
]);
