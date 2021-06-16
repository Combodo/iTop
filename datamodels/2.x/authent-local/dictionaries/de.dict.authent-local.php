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
/*
* @author ITOMIG GmbH <martin.raenker@itomig.de>

* @copyright     Copyright (C) 2021 Combodo SARL
* @licence	http://opensource.org/licenses/AGPL-3.0
*		
*/
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:UserLocal' => 'iTop-Benutzer',
	'Class:UserLocal+' => 'Benutzer, der von iTop authentifiziert wird',
	'Class:UserLocal/Attribute:password' => 'Passwort',
	'Class:UserLocal/Attribute:password+' => 'Benutzerpasswort',

	'Class:UserLocal/Attribute:expiration' => 'Passwortablauf',
	'Class:UserLocal/Attribute:expiration+' => 'Passwortablaufstatus (statusabhängige Effekte müssen per Extension implementiert werden)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'kann ablaufen',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'läuft nie ab',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'abgelaufen',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Letzte Passworterneuerung',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Letztes Änderungsdatum',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Das Passwort entspricht nicht dem in den Konfigurationsregeln hinterlegten RegEx-Ausdruck',

	'UserLocal:password:expiration' => 'Die folgenden Felder benötigen eine iTop Erweiterung'
));
