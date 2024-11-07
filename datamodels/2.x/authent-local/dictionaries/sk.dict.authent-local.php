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
Dict::Add('SK SK', 'Slovak', 'Slovenčina', [
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.' užívateľ',
	'Class:UserLocal+' => 'User authentified by '.ITOP_APPLICATION_SHORT.'~~',
	'Class:UserLocal/Attribute:expiration' => 'Password expiration~~',
	'Class:UserLocal/Attribute:expiration+' => 'Password expiration status (requires an extension to have an effect)~~',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Can expire~~',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expired~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Never expire~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'One-time Password~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Password cannot be changed by the user.~~',
	'Class:UserLocal/Attribute:password' => 'Heslo',
	'Class:UserLocal/Attribute:password+' => 'User authentication string~~',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Password renewed on~~',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'When the password was last changed~~',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Setting password expiration to "One-time password" is not allowed for your own User~~',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.~~',
	'UserLocal:password:expiration' => 'The fields below require an extension~~',
]);
