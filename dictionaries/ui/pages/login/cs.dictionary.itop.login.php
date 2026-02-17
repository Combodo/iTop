<?php

/**
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login~~',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Vítejte v '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nesprávné uživatelské jméno nebo heslo. Zkuste to prosím znovu.',
	'UI:Login:IdentifyYourself' => 'Před pokračováním se prosím identifikujte.',
	'UI:Login:UserNamePrompt' => 'Uživatelské jméno',
	'UI:Login:PasswordPrompt' => 'Heslo',
	'UI:Login:ForgotPwd' => 'Zapomněli jste své heslo?',
	'UI:Login:ForgotPwdForm' => 'Zapomenuté heslo',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' vám může zaslat instrukce pro obnovení vašeho hesla.',
	'UI:Login:ResetPassword' => 'Zaslat nyní!',
	'UI:Login:ResetPwdFailed' => 'Chyba při odesílání emailu: %1$s',
	'UI:Login:SeparatorOr' => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' není platné uživatelské jméno',
	'UI:ResetPwd-Error-NotPossible' => 'obnova hesla u externích účtů není možná.',
	'UI:ResetPwd-Error-FixedPwd' => 'obnova hesla u tohoto účtu není povolená.',
	'UI:ResetPwd-Error-NoContact' => 'účet není spojen s žádnou osobou.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'účet není spojen s osobou s uvedenou emailovou adresou. Kontaktujte administrátora.',
	'UI:ResetPwd-Error-NoEmail' => 'chybí emailová adresa. Kontaktujte administrátora.',
	'UI:ResetPwd-Error-Send' => 'technický problém při odesílání emailu. Kontaktujte administrátora.',
	'UI:ResetPwd-EmailSent' => 'Zkontrolujte prosím svoji emailovou schránku a postupujte podle pokynů. Pokud žádný email neobdržíte, zkontrolujte prosím zadané uživatelské jméno.',
	'UI:ResetPwd-EmailSubject' => 'Obnovení hesla pro '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody' => '<body><p>Vyžádali jste obovení hesla pro '.ITOP_APPLICATION_SHORT.'.</p><p>Pokračujte kliknutím na následující <a href="%1$s">jednorázový odkaz</a> a zadejte nové heslo.</p>',
	'UI:ResetPwd-Title' => 'Obnovení hesla',
	'UI:ResetPwd-Error-InvalidToken' => 'Omlouváme se, ale heslo již bylo obnoveno nebo jste obdrželi více emailů. Ujistěte se, že používate odkaz z posledního emailu který jste obdrželi.',
	'UI:ResetPwd-Error-EnterPassword' => 'Vložte nové heslo k účtu \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Heslo bylo obnoveno.',
	'UI:ResetPwd-Login' => 'Pro přihlášení klikněte zde...',

	'UI:Login:About' => '',
	'UI:Login:ChangeYourPassword' => 'Změnit heslo',
	'UI:Login:OldPasswordPrompt' => 'Původní heslo',
	'UI:Login:NewPasswordPrompt' => 'Nové heslo',
	'UI:Login:RetypeNewPasswordPrompt' => 'Znovu nové heslo',
	'UI:Login:IncorrectOldPassword' => 'Chyba: původní heslo je nesprávné',
	'UI:LogOffMenu' => 'Odhlásit',
	'UI:LogOff:ThankYou' => 'Děkujeme za užívání '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain' => 'Klikněte zde pro nové přihlášení...',
	'UI:ChangePwdMenu' => 'Změnit heslo',
	'UI:Login:PasswordChanged' => 'Heslo nastaveno úspěšně!',
	'UI:Login:PasswordNotChanged' => 'Chyba: heslo je stejné jako přechozí!',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nová hesla se neshodují!',
	'UI:Button:Login' => 'Přihlásit',
	'UI:Login:Error:AccessRestricted' => 'Přístup je omezen. Kontaktujte administrátora.',
	'UI:Login:Error:AccessAdmin' => 'Přístup vyhrazen osobám s administrátorskými právy. Kontaktujte administrátora.',
	'UI:Login:Error:WrongOrganizationName' => 'Neznámá organizace',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Více kontaktů má stejný email',
	'UI:Login:Error:NoValidProfiles' => 'Není zadán platný profil',
]);