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

	'Class:UserLocal/Attribute:expiration' => 'Срок действия пароля',
	'Class:UserLocal/Attribute:expiration+' => 'Статус срока действия пароля (требуется расширение для эффекта)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Органиченный',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Неограниченный',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Истёкший',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Дата изменения пароля',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Когда пароль был изменен в последний раз',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Пароль должен содержать не менее 8 символов и включать прописные, строчные, числовые и специальные символы.',

	'UserLocal:password:expiration' => 'Поля требуют наличия доп. расширения'
));
