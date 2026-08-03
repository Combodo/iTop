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
	'Class:UserLocal/Attribute:password' => 'Пароль',
	'Class:UserLocal/Attribute:password+' => 'Строка аутентификации пользователя',
	'Class:UserLocal/Attribute:expiration' => 'Срок действия пароля',
	'Class:UserLocal/Attribute:expiration+' => 'Статус срока действия пароля (требуется расширение для эффекта)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Органиченный',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Неограниченный',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Истёкший',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Одноразовый пароль',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Пароль не может быть изменён пользователем.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Дата изменения пароля',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Когда пароль был изменен в последний раз',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Пароль должен содержать не менее 12 символов и включать прописные, строчные, числовые и специальные символы.',
	'UserLocal:password:expiration' => 'Поля требуют наличия доп. расширения',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Установка срока действия пароля "Одноразовый пароль" для своей собственной учётной записи не разрешена',
]);
