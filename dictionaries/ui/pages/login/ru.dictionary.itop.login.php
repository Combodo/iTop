<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('RU RU', 'Russian', 'Русский', [
	'UI:Login:Title' => 'Вход в '.ITOP_APPLICATION_SHORT,
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Добро пожаловать в '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Неправильный логин/пароль. Пожалуйста, попробуйте еще раз.',
	'UI:Login:IdentifyYourself' => 'Пожалуйста, представьтесь',
	'UI:Login:UserNamePrompt' => 'Имя пользователя',
	'UI:Login:PasswordPrompt' => 'Пароль',
	'UI:Login:ForgotPwd' => 'Забыли пароль?',
	'UI:Login:ForgotPwdForm' => 'Восстановление пароля',
	'UI:Login:ForgotPwdForm+' => 'Введите свой логин для входа в систему и нажмите "Отправить". '.ITOP_APPLICATION_SHORT.' отправит email с инструкциями по восстановлению пароля на ваш электронный адрес.',
	'UI:Login:ResetPassword' => 'Отправить',
	'UI:Login:ResetPwdFailed' => 'Не удалось отправить email: %1$s',
	'UI:Login:SeparatorOr' => 'или',

	'UI:ResetPwd-Error-WrongLogin' => 'учетная запись с логином "%1$s" не найдена.',
	'UI:ResetPwd-Error-NotPossible' => 'восстановление пароля для внешних учётных записей недоступно.',
	'UI:ResetPwd-Error-FixedPwd' => 'восстановление пароля для данной учётной записи недоступно. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoContact' => 'данная учетная запись не ассоциирована с персоной. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'аккаунт не ассоциирован с персоной, имеющей атрибут электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-NoEmail' => 'отсутствует адрес электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-Error-Send' => 'технические проблемы с отправкой электронной почты. Пожалуйста, обратитесь к администратору.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Восстановление пароля',
	'UI:ResetPwd-EmailBody' => '<body><p>Вы запросили восстановление пароля '.ITOP_APPLICATION_SHORT.'.</p><p>Пожалуйста, воспользуйтесь <a href="%1$s">этой ссылкой</a> для задания нового пароля.</p></body>',
	'UI:ResetPwd-Title' => 'Восстановление пароля',
	'UI:ResetPwd-Error-InvalidToken' => 'Извините, недействительная ссылка. Если вы запрашивали восстановление пароля несколько раз подряд, пожалуйста, убедитесь, что используете ссылку из последнего полученного письма.',
	'UI:ResetPwd-Error-EnterPassword' => 'Введите новый пароль для учетной записи пользователя \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Пароль успешно изменён.',
	'UI:ResetPwd-Login' => 'Войти...',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Изменение пароля',
	'UI:Login:OldPasswordPrompt' => 'Старый пароль',
	'UI:Login:NewPasswordPrompt' => 'Новый пароль',
	'UI:Login:RetypeNewPasswordPrompt' => 'Повторите новый пароль',
	'UI:Login:IncorrectOldPassword' => 'Ошибка: старый пароль неверный',
	'UI:LogOffMenu' => 'Выход',
	'UI:LogOff:ThankYou' => 'Спасибо за использование '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain' => 'Нажмите здесь, чтобы снова войти...',
	'UI:ChangePwdMenu' => 'Изменить пароль...',
	'UI:Login:PasswordChanged' => 'Пароль успешно изменён!',
	'UI:Login:PasswordNotChanged' => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Пароли не совпадают',
	'UI:Button:Login' => 'Войти',
	'UI:Login:Error:AccessRestricted' => 'Доступ к '.ITOP_APPLICATION_SHORT.' ограничен. Пожалуйста, свяжитесь с администратором '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin' => 'Доступ ограничен для лиц с административными привилегиями. Пожалуйста, свяжитесь с администратором '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:WrongOrganizationName' => 'Неизвестная организация',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Несколько контактов имеют один и тот же адрес электронной почты',
	'UI:Login:Error:NoValidProfiles' => 'Нет допустимого профиля',
]);