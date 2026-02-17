<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'UI:Login:Title' => 'Aanmelden in '.ITOP_APPLICATION_SHORT,
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Welkom in '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Ongeldige gebruikersnaam of wachtwoord, probeer opnieuw.',
	'UI:Login:IdentifyYourself' => 'Identificeer jezelf voordat je verder gaat',
	'UI:Login:UserNamePrompt' => 'Gebruikersnaam',
	'UI:Login:PasswordPrompt' => 'Wachtwoord',
	'UI:Login:ForgotPwd' => 'Wachtwoord vergeten?',
	'UI:Login:ForgotPwdForm' => 'Wachtwoord vergeten',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' kan je een e-mail sturen waarin de instructies voor het resetten van jouw account staan.',
	'UI:Login:ResetPassword' => 'Stuur nu!',
	'UI:Login:ResetPwdFailed' => 'E-mail sturen mislukt: %1$s',
	'UI:Login:SeparatorOr' => 'Of',

	'UI:ResetPwd-Error-WrongLogin' => '"%1$s" is geen geldige login',
	'UI:ResetPwd-Error-NotPossible' => 'Het wachtwoord van externe accounts kan niet gereset worden.',
	'UI:ResetPwd-Error-FixedPwd' => 'Deze account staat het resetten van het wachtwoord niet toe.',
	'UI:ResetPwd-Error-NoContact' => 'Deze account is niet gelinkt aan een persoon.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'Deze account is niet gelinkt aan een persoon waarvan een e-mailadres gekend is. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-Error-NoEmail' => 'Er ontbreekt een e-mailadres. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-Error-Send' => 'Er is een technisch probleem bij het verzenden van de e-mail. Neem contact op met jouw beheerder.',
	'UI:ResetPwd-EmailSent' => 'Kijk in jouw mailbox (eventueel bij ongewenste mail) en volg de instructies...',
	'UI:ResetPwd-EmailSubject' => 'Reset jouw '.ITOP_APPLICATION_SHORT.'-wachtwoord',
	'UI:ResetPwd-EmailBody' => '<body><p>Je hebt een reset van jouw '.ITOP_APPLICATION_SHORT.'-wachtwoord aangevraagd.</p><p>Klik op deze link (eenmalig te gebruiken) om <a href="%1$s">een nieuw wachtwoord in te voeren</a></p>.',
	'UI:ResetPwd-Title' => 'Reset wachtwoord',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry. Jouw wachtwoord is al gereset, of je hebt al meerdere e-mails ontvangen. Zorg ervoor dat je de link in de laatst ontvangen e-mail gebruikt.',
	'UI:ResetPwd-Error-EnterPassword' => 'Voer het nieuwe wachtwoord voor de account "%1$s" in.',
	'UI:ResetPwd-Ready' => 'Het wachtwoord is veranderd',
	'UI:ResetPwd-Login' => 'Klik hier om in te loggen',
	'UI:Login:About' => ITOP_APPLICATION,
	'UI:Login:ChangeYourPassword' => 'Verander jouw wachtwoord',
	'UI:Login:OldPasswordPrompt' => 'Oud wachtwoord',
	'UI:Login:NewPasswordPrompt' => 'Nieuw wachtwoord',
	'UI:Login:RetypeNewPasswordPrompt' => 'Herhaal nieuwe wachtwoord',
	'UI:Login:IncorrectOldPassword' => 'Fout: het oude wachtwoord is incorrect',
	'UI:LogOffMenu' => 'Log uit',
	'UI:LogOff:ThankYou' => 'Bedankt voor het gebruiken van '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain' => 'Klik hier om in te loggen',
	'UI:ChangePwdMenu' => 'Verander wachtwoord',
	'UI:Login:PasswordChanged' => 'Wachtwoord met succes aangepast',
	'UI:Login:PasswordNotChanged' => 'Fout: Wachtwoord is hetzelfde!',

	'UI:Login:RetypePwdDoesNotMatch' => 'Het nieuwe wachtwoord en de herhaling van het nieuwe wachtwoord komen niet overeen',
	'UI:Button:Login' => 'Ga naar '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted' => 'Geen toegang tot '.ITOP_APPLICATION_SHORT.'.Neem contact op met een '.ITOP_APPLICATION_SHORT.'-beheerder.',
	'UI:Login:Error:AccessAdmin' => 'Alleen toegankelijk voor mensen met beheerdersrechten. Neem contact op met een '.ITOP_APPLICATION_SHORT.'-beheerder',
	'UI:Login:Error:WrongOrganizationName' => 'Onbekende organisatie',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Meerdere contacten hebben hetzelfde e-mailadres',
	'UI:Login:Error:NoValidProfiles' => 'Geen geldig profiel opgegeven',
]);