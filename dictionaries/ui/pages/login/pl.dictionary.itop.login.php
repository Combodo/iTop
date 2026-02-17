<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('PL PL', 'Polish', 'Polski', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Witamy w '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Nieprawidłowy login/hasło, spróbuj ponownie.',
	'UI:Login:IdentifyYourself' => 'Zidentyfikuj się przed wejściem',
	'UI:Login:UserNamePrompt' => 'Login',
	'UI:Login:PasswordPrompt' => 'Hasło',
	'UI:Login:ForgotPwd' => 'Zapomniałeś hasła?',
	'UI:Login:ForgotPwdForm' => 'Resetowanie hasła',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' może wysłać Ci wiadomość e-mail, w której znajdziesz instrukcje dotyczące resetowania hasła.',
	'UI:Login:ResetPassword' => 'Wyślij !',
	'UI:Login:ResetPwdFailed' => 'Nie udało się wysłać e-maila: %1$s',
	'UI:Login:SeparatorOr' => 'Lub',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\'nie jest prawidłowym loginem',
	'UI:ResetPwd-Error-NotPossible' => 'konta zewnętrzne nie pozwalają na resetowanie hasła.',
	'UI:ResetPwd-Error-FixedPwd' => 'konto nie pozwala na resetowanie hasła.',
	'UI:ResetPwd-Error-NoContact' => 'konto nie jest powiązane z osobą.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'konto nie jest powiązane z osobą mającą atrybut e-mail. Skontaktuj się z administratorem.',
	'UI:ResetPwd-Error-NoEmail' => 'brak adresu e-mail. Skontaktuj się z administratorem.',
	'UI:ResetPwd-Error-Send' => 'problem techniczny dotyczący transportu poczty elektronicznej. Skontaktuj się z administratorem.',
	'UI:ResetPwd-EmailSent' => 'Sprawdź swoją skrzynkę e-mail i postępuj zgodnie z instrukcjami. Jeśli nie otrzymasz wiadomości e-mail, sprawdź wpisany login.',
	'UI:ResetPwd-EmailSubject' => 'Reset hasła '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody' => '<body><p>Poprosiłeś o zresetowanie hasła '.ITOP_APPLICATION_SHORT.'.</p><p>Proszę skorzystać z tego linku (jednorazowe użycie), <a href="%1$s">wpisz nowe hasło</a></p>.',
	'UI:ResetPwd-Title' => 'Zresetuj hasło',
	'UI:ResetPwd-Error-InvalidToken' => 'Przepraszamy, albo hasło zostało już zresetowane, albo otrzymałeś kilka e-maili. Upewnij się, że używasz linku podanego w ostatniej otrzymanej wiadomości e-mail.',
	'UI:ResetPwd-Error-EnterPassword' => 'Wprowadź nowe hasło do konta \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'Hasło zostało zmienione.',
	'UI:ResetPwd-Login' => 'Kliknij tutaj aby się zalogować...',

	'UI:Login:About' => ITOP_APPLICATION.' Obsługiwane przez Combodo',
	'UI:Login:ChangeYourPassword' => 'Zmień swoje hasło',
	'UI:Login:OldPasswordPrompt' => 'Stare hasło',
	'UI:Login:NewPasswordPrompt' => 'Nowe hasło',
	'UI:Login:RetypeNewPasswordPrompt' => 'Powtórz nowe hasło',
	'UI:Login:IncorrectOldPassword' => 'Błąd: stare hasło jest nieprawidłowe',
	'UI:LogOffMenu' => 'Wyloguj',
	'UI:LogOff:ThankYou' => 'Dziękujemy za użycie '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain' => 'Kliknij tutaj, aby zalogować się ponownie...',
	'UI:ChangePwdMenu' => 'Zmień hasło...',
	'UI:Login:PasswordChanged' => 'Hasło ustawione pomyślnie!',
	'UI:Login:PasswordNotChanged' => 'Błąd: Hasło jest takie samo!',
	'UI:Login:RetypePwdDoesNotMatch' => 'Nowe hasło i powtórzone nowe hasło nie pasują!',
	'UI:Button:Login' => 'Wejdź do '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted' => ITOP_APPLICATION_SHORT.' dostęp jest ograniczony. Prosimy o kontakt z administratorem '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin' => 'Dostęp ograniczony do osób z uprawnieniami administratora. Prosimy o kontakt z administratorem '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:WrongOrganizationName' => 'Nieznana organizacja',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Wiele kontaktów ma ten sam adres e-mail',
	'UI:Login:Error:NoValidProfiles' => 'Nie podano prawidłowego profilu',
]);