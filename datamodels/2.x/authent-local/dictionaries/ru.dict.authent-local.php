<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Vladimir Kunin <v.b.kunin@gmail.com>
 *
 */
Dict::Add('RU RU', 'Russian', 'Русский', [
	'Class:UserLocal' => 'Пользователь '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Пользователь, аутентифицируемый через '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:expiration' => 'Срок действия пароля',
	'Class:UserLocal/Attribute:expiration+' => 'Статус срока действия пароля (требуется расширение для эффекта)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Органиченный',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Истёкший',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Неограниченный',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'One-time Password~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Password cannot be changed by the user.~~',
	'Class:UserLocal/Attribute:password' => 'Пароль',
	'Class:UserLocal/Attribute:password+' => 'Строка аутентификации пользователя',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Дата изменения пароля',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Когда пароль был изменен в последний раз',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Setting password expiration to "One-time password" is not allowed for your own User~~',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Пароль должен содержать не менее 8 символов и включать прописные, строчные, числовые и специальные символы.',
	'UserLocal:password:expiration' => 'Поля требуют наличия доп. расширения',
]);
