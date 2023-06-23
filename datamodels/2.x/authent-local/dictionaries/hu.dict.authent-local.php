<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.' felhasználó',
	'Class:UserLocal+' => '',
	'Class:UserLocal/Attribute:password' => 'Jelszó',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:expiration' => 'Jelszó lejárati ideje',
	'Class:UserLocal/Attribute:expiration+' => 'Jelszó lejárati státusz (bővítmény szükséges hozzá)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Lejár',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Soha nem jár le',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Lejárt',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Egyszeri jelszó',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'A felhasználó nem változtathat jelszót.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Jelszó megújítás ideje',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'A jelszó legutóbbi módosításának időpontja',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'A jelszónak legalább 8 karakterből kell állnia, és tartalmaznia kell nagybetűket, kisbetűket, numerikus és speciális karaktereket.',
	'UserLocal:password:expiration' => 'Az alábbi mezőkhöz egy bővítmény szükséges',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'A jelszó lejárati idejének beállítása "Egyszeri jelszóra" nem engedélyezett a saját Felhasználó számára.',
));
