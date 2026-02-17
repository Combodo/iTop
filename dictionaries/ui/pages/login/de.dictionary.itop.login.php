<?php

/**
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DE DE', 'German', 'Deutsch', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' Login',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome' => 'Willkommen bei '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Ungültiges Passwort oder Login-Daten. Bitte versuchen Sie es erneut.',
	'UI:Login:IdentifyYourself' => 'Bitte identifizieren Sie sich, bevor Sie fortfahren.',
	'UI:Login:UserNamePrompt' => 'Benutzername',
	'UI:Login:PasswordPrompt' => 'Passwort',
	'UI:Login:ForgotPwd' => 'Neues Passwort zusenden',
	'UI:Login:ForgotPwdForm' => 'Neues Passwort zusenden',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' kann Ihnen eine Mail mit Anweisungen senden, wie Sie Ihren Account/Passwort zurücksetzen können',
	'UI:Login:ResetPassword' => 'Jetzt senden!',
	'UI:Login:ResetPwdFailed' => 'Konnte keine E-Mail versenden: %1$s',
	'UI:Login:SeparatorOr' => 'oder',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' ist kein gültiger Login',
	'UI:ResetPwd-Error-NotPossible' => 'Passwort-Reset bei externem Benutzerkonto nicht möglich',
	'UI:ResetPwd-Error-FixedPwd' => 'das Benutzerkonto erlaubt keinen Passwort-Reset. ',
	'UI:ResetPwd-Error-NoContact' => 'das Benutzerkonto ist nicht mit einer Person verknüpft. ',
	'UI:ResetPwd-Error-NoEmailAtt' => 'das Benutzerkonto ist nicht mit einer Person verknüpft, die eine Mailadresse besitzt. Bitte wenden Sie sich an Ihren Administrator. ',
	'UI:ResetPwd-Error-NoEmail' => 'die E-Mail-Adresse dieses Accounts fehlt. Bitte kontaktieren Sie Ihren Administrator.',
	'UI:ResetPwd-Error-Send' => 'Beim Versenden der E-Mail trat ein technisches Problem auf. Bitte kontaktieren Sie Ihren Administrator.',
	'UI:ResetPwd-EmailSent' => 'Bitte schauen Sie in Ihre Mailbox und folgen Sie den Anweisungen.',
	'UI:ResetPwd-EmailSubject' => 'Zurücksetzen Ihres '.ITOP_APPLICATION_SHORT.'-Passworts',
	'UI:ResetPwd-EmailBody' => '<body><p>Sie haben das Zurücksetzen Ihres '.ITOP_APPLICATION_SHORT.' Passworts angefordert.</p><p>Bitte folgen Sie diesem Link (funktioniert nur einmalig) : <a href="%1$s">neues Passwort eingeben</a></p>.',
	'UI:ResetPwd-Title' => 'Passwort zurücksetzen',
	'UI:ResetPwd-Error-InvalidToken' => 'Entschuldigung, aber entweder das Passwort wurde bereits zurückgesetzt, oder Sie haben mehrere E-Mails für das Zurücksetzen erhalten. Bitte nutzen Sie den link in der letzten Mail, die Sie erhalten haben.',
	'UI:ResetPwd-Error-EnterPassword' => 'Geben Sie ein neues Passwort für das Konto \'%1$s\' ein.',
	'UI:ResetPwd-Ready' => 'Das Passwort wurde geändert. ',
	'UI:ResetPwd-Login' => 'Klicken Sie hier um sich einzuloggen...',

	'UI:Login:About' => 'iTop Powered by Combodo',
	'UI:Login:ChangeYourPassword' => 'Ändern Sie Ihr Passwort',
	'UI:Login:OldPasswordPrompt' => 'Altes Passwort',
	'UI:Login:NewPasswordPrompt' => 'Neues Passwort',
	'UI:Login:RetypeNewPasswordPrompt' => 'Wiederholen Sie Ihr neues Passwort',
	'UI:Login:IncorrectOldPassword' => 'Fehler: das alte Passwort ist ungültig',
	'UI:LogOffMenu' => 'Abmelden',
	'UI:LogOff:ThankYou' => 'Vielen Dank dafür, dass Sie '.ITOP_APPLICATION_SHORT.' benutzen!',
	'UI:LogOff:ClickHereToLoginAgain' => 'Klicken Sie hier, um sich wieder anzumelden...',
	'UI:ChangePwdMenu' => 'Passwort ändern...',
	'UI:Login:PasswordChanged' => 'Passwort erfolgreich gesetzt!',
	'UI:Login:PasswordNotChanged' => 'Fehler: Das Passwort das gleiche!',
	'UI:Login:RetypePwdDoesNotMatch' => 'Neues Passwort und das wiederholte Passwort stimmen nicht überein!',
	'UI:Button:Login' => 'in '.ITOP_APPLICATION_SHORT.' anmelden',
	'UI:Login:Error:AccessRestricted' => 'Der '.ITOP_APPLICATION_SHORT.'-Zugang ist gesperrt. Bitte kontaktieren Sie Ihren '.ITOP_APPLICATION_SHORT.'-Administrator.',
	'UI:Login:Error:AccessAdmin' => 'Zugang nur für Personen mit Administratorrechten. Bitte kontaktieren Sie Ihren '.ITOP_APPLICATION_SHORT.'-Administrator.',
	'UI:Login:Error:WrongOrganizationName' => 'Unbekannte Organisation',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Mehrere Kontakte mit gleicher E-Mail-Adresse',
	'UI:Login:Error:NoValidProfiles' => 'Kein gültiges Profil ausgewählt',
]);