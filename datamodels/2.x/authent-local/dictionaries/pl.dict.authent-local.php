<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */

// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+

//
// Class: UserLocal
//

Dict::Add('PL PL', 'Polish', 'Polski', array(
	'Class:UserLocal' => 'Użytkownik '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Użytkownik uwierzytelniony przez '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Hasło',
	'Class:UserLocal/Attribute:password+' => 'Ciąg uwierzytelniania użytkownika',

	'Class:UserLocal/Attribute:expiration' => 'Wygaśnięcie hasła',
	'Class:UserLocal/Attribute:expiration+' => 'Stan wygaśnięcia hasła (wymaga rozszerzenia, aby zadziałało)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Może wygasnąć',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nigdy nie wygasa',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Wygasło',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Odnowienie hasła',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Kiedy ostatnio zmieniano hasło',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Hasło musi mieć co najmniej 8 znaków i zawierać duże, małe litery, cyfry i znaki specjalne.',

	'UserLocal:password:expiration' => 'Poniższe pola wymagają rozszerzenia'
));
