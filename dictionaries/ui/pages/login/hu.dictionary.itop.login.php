<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('HU HU', 'Hungarian', 'Magyar', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' bejelentkezés',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Üdvözli az '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nem megfelelő bejelentkezési név/jelszó, kérjük próbálja újra.',
	'UI:Login:IdentifyYourself' => 'Folytatás előtt azonosítsa magát',
	'UI:Login:UserNamePrompt' => 'Felhasználónév',
	'UI:Login:PasswordPrompt' => 'Jelszó',
	'UI:Login:ForgotPwd' => 'Elfelejtette a jelszavát?',
	'UI:Login:ForgotPwdForm' => 'Elfelejtett jelszó',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' küldhet Önnek egy emailt, amelyben utasításokat talál a fiókja visszaállításához.',
	'UI:Login:ResetPassword' => 'Küldje most!',
	'UI:Login:ResetPwdFailed' => 'Sikertelen email küldés: %1$s',
	'UI:Login:SeparatorOr' => 'Vagy',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' nem érvényes fiók',
	'UI:ResetPwd-Error-NotPossible' => 'a külső fiókok jelszava itt nem állítható vissza.',
	'UI:ResetPwd-Error-FixedPwd' => 'a fiók nem teszi lehetővé a jelszó visszaállítását.',
	'UI:ResetPwd-Error-NoContact' => 'a fiók nem személyhez tartozik',
	'UI:ResetPwd-Error-NoEmailAtt' => 'a fiók nem olyan személyhez tartozik amelynek van email címe. Keresse a rendszergazdát.',
	'UI:ResetPwd-Error-NoEmail' => 'hiányzik az email cím. Keresse a rendszergazdát.',
	'UI:ResetPwd-Error-Send' => 'email továbbítási hiba. Keresse a rendszergazdát',
	'UI:ResetPwd-EmailSent' => 'Kérjük, ellenőrizze az email postafiókját, és kövesse az utasításokat. Ha nem kap emailt, kérjük, ellenőrizze a beírt bejelentkezési adatait.',
	'UI:ResetPwd-EmailSubject' => 'Állítsa vissza az '.ITOP_APPLICATION_SHORT.' jelszavát',
	'UI:ResetPwd-EmailBody' => '<body><p>Ön vissza szeretné állítani az '.ITOP_APPLICATION_SHORT.' jelszavát.</p><p>Kattintson erre a linkre <a href="%1$s">új jelszó</a></p>.',
	'UI:ResetPwd-Title' => 'Jelszó visszaállítás',
	'UI:ResetPwd-Error-InvalidToken' => 'Sajnáljuk, de vagy már visszaállították a jelszót, vagy már több emailt is kapott. Kérjük, mindenképpen használja a legutolsó kapott emailben megadott linket.',
	'UI:ResetPwd-Error-EnterPassword' => 'Adja meg az új jelszavát a %1$s a fiókjának',
	'UI:ResetPwd-Ready' => 'A jelszó megváltozott',
	'UI:ResetPwd-Login' => 'Jelentkezzen be...',

	'UI:Login:About' => 'Névjegy',
	'UI:Login:ChangeYourPassword' => 'Jelszó változtatás',
	'UI:Login:OldPasswordPrompt' => 'Jelenlegi jelszó',
	'UI:Login:NewPasswordPrompt' => 'Új jelszó',
	'UI:Login:RetypeNewPasswordPrompt' => 'Jelszó megerősítése',
	'UI:Login:IncorrectOldPassword' => 'Hiba: a jelenlegi jelszó hibás',
	'UI:LogOffMenu' => 'Kilépés',
	'UI:LogOff:ThankYou' => 'Köszönjük, hogy az '.ITOP_APPLICATION_SHORT.'-ot használja!',
	'UI:LogOff:ClickHereToLoginAgain' => 'Ismételt bejelentkezéshez kattintson ide',
	'UI:ChangePwdMenu' => 'Jelszó módosítás...',
	'UI:Login:PasswordChanged' => 'Jelszó sikeresen beállítva!',
	'UI:Login:PasswordNotChanged' => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch' => 'A jelszavak nem egyeznek!',
	'UI:Button:Login' => 'Belépés az '.ITOP_APPLICATION_SHORT.' alkalmazásba',
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' hozzáférés korlátozva. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' rendszergazdához!',
	'UI:Login:Error:AccessAdmin' => 'Adminisztrátori hozzáférés korlátozott. Kérem forduljon az '.ITOP_APPLICATION_SHORT.' rendszergazdához!',
	'UI:Login:Error:WrongOrganizationName' => 'Ismeretlen szervezeti egység',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Több kapcsolattartónál ugyanez az emailcím',
	'UI:Login:Error:NoValidProfiles' => 'Érvénytelen a megadott profil',
]);