<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('SK SK', 'Slovak', 'Slovenčina', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Vitajte v '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nesprávne prihlasovacie meno/heslo, prosím skúste znova.',
	'UI:Login:IdentifyYourself' => 'Identifikujte sa pred pokračovaním',
	'UI:Login:UserNamePrompt' => 'Užívateľské meno',
	'UI:Login:PasswordPrompt' => 'Heslo',
	'UI:Login:ForgotPwd' => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm' => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' can send you an email in which you will find instructions to follow to reset your account.~~',
	'UI:Login:ResetPassword' => 'Send now!~~',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s~~',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login~~',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.~~',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.~~',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated to a person.~~',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.~~',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject' => 'Reset your '.ITOP_APPLICATION_SHORT.' password~~',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your '.ITOP_APPLICATION_SHORT.' password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',
	'UI:ResetPwd-Title' => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready' => 'The password has been changed.~~',
	'UI:ResetPwd-Login' => 'Click here to login...~~',

	'UI:Login:About' => 'O účte',
	'UI:Login:ChangeYourPassword' => 'Zmeň heslo',
	'UI:Login:OldPasswordPrompt' => 'Staré heslo',
	'UI:Login:NewPasswordPrompt' => 'Nové heslo',
	'UI:Login:RetypeNewPasswordPrompt' => 'Znova zadaj nové heslo',
	'UI:Login:IncorrectOldPassword' => 'Chyba: staré heslo je nesprávne',
	'UI:LogOffMenu' => 'Odhlásenie',
	'UI:LogOff:ThankYou' => 'Ďakujeme za používanie '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain' => 'Kliknite sem pre nové prihlásenie...',
	'UI:ChangePwdMenu' => 'Zmeniť heslo...',
	'UI:Login:PasswordChanged' => 'Heslo úspešne nastavené !',
	'UI:Login:PasswordNotChanged' => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nové heslo a znova zadané nové heslo sa nezhodujú !',
	'UI:Button:Login' => 'Vstup do '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted' => 'Prístup do '.ITOP_APPLICATION_SHORT.'u je obmedzený. Kontaktujte prosím '.ITOP_APPLICATION_SHORT.' administrátora.',
	'UI:Login:Error:AccessAdmin' => 'Prístup je vyhradený len pre ľudí, ktorí majú oprávnenia od administrátora. Kontaktujte prosím '.ITOP_APPLICATION_SHORT.' administrátora.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided~~',
]);