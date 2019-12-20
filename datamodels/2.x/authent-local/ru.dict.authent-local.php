<?php
/**
 * Локализация интерфейса Combodo iTop подготовлена сообществом iTop по-русски http://community.itop-itsm.ru.
 *
 * @author      Vladimir Kunin <v.b.kunin@gmail.com>
 * @link        http://community.itop-itsm.ru  iTop Russian Community
 * @link        https://github.com/itop-itsm-ru/itop-rus
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 */
//
// Class: UserLocal
//
Dict::Add('RU RU', 'Russian', 'Русский', array(
	'Class:UserLocal' => 'Пользователь iTop',
	'Class:UserLocal+' => 'Пользователь, аутентифицируемый через iTop',
	'Class:UserLocal/Attribute:password' => 'Пароль',
	'Class:UserLocal/Attribute:password+' => 'Строка аутентификации пользователя',

	'Class:UserLocal/Attribute:expiration' => 'Password expiration~~',
	'Class:UserLocal/Attribute:expiration+' => 'Password expiration status (requires an extension to have an effect)~~',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Can expire~~',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Never expire~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Expired~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Password renewal~~',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'When the password was last changed~~',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.~~',
));
