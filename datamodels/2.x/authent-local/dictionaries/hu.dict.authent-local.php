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
Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.' felhasználó',
	'Class:UserLocal+' => 'Rendszeren belül létrehozott felhasználó',
	'Class:UserLocal/Attribute:expiration' => 'Jelszó lejárati ideje',
	'Class:UserLocal/Attribute:expiration+' => 'Jelszó lejárati státusz (bővítmény szükséges hozzá)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Lejár',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Lejárt',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Soha nem jár le',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Egyszeri jelszó',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'A felhasználó nem változtathat jelszót.',
	'Class:UserLocal/Attribute:password' => 'Jelszó',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Jelszó megújítás ideje',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'A jelszó legutóbbi módosításának időpontja',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'A jelszó lejárati idejének beállítása "Egyszeri jelszóra" nem engedélyezett a saját Felhasználó számára.',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'A jelszónak legalább 8 karakterből kell állnia, és tartalmaznia kell nagybetűket, kisbetűket, numerikus és speciális karaktereket.',
	'UserLocal:password:expiration' => 'Az alábbi mezőkhöz egy bővítmény szükséges',
]);
