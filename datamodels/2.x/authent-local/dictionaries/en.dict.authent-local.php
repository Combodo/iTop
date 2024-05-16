<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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

Dict::Add('EN US', 'English', 'English', array(
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.' user',
	'Class:UserLocal+' => 'User authentified by '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Password',
	'Class:UserLocal/Attribute:password+' => 'User authentication string',

	'Class:UserLocal/Attribute:expiration' => 'Password expiration',
	'Class:UserLocal/Attribute:expiration+' => 'Password expiration status (requires an extension to have an effect)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Can expire',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Never expire',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expired',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'One-time Password',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Password cannot be changed by the user.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Password renewed on',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'When the password was last changed',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.',
	'UserLocal:password:expiration' => 'The fields below require an extension',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Setting password expiration to "One-time password" is not allowed for your own User',
));
