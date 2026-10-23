<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
Dict::Add('UK UA', 'Ukrainian', 'Українська', array(
	'Class:UserLocal' => 'Користувач '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Користувач, що автентифікується через '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Пароль',
	'Class:UserLocal/Attribute:password+' => 'Рядок автентифікації користувача',
	'Class:UserLocal/Attribute:expiration' => 'Термін дії пароля',
	'Class:UserLocal/Attribute:expiration+' => 'Статус терміну дії пароля (потрібне розширення для ефекту)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Обмежений',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Необмежений',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Закінчився',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Одноразовий пароль',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Пароль не може бути змінений користувачем.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Дата зміни пароля',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Коли пароль було змінено останній раз',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Пароль повинен містити не менше 8 символів і включати великі літери, малі літери, цифри та спеціальні символи.',
	'UserLocal:password:expiration' => 'Поля вимагають наявності додаткового розширення',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Встановлення терміну дії пароля на "Одноразовий пароль" не дозволено для вашого власного облікового запису користувача',
));
