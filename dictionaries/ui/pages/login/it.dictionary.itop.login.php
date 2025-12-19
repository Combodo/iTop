<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('IT IT', 'Italian', 'Italiano', [
	'UI:Login:Title'                  => ITOP_APPLICATION_SHORT.' login',
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome'                => 'Benvenuti su '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Errato login/password, si prega di riprovare.',
	'UI:Login:IdentifyYourself'       => 'Identifica te stesso prima di continuare',
	'UI:Login:UserNamePrompt'         => 'Nome Utente',
	'UI:Login:PasswordPrompt'         => 'Password',
	'UI:Login:ForgotPwd'              => 'Hai dimenticato la password?',
	'UI:Login:ForgotPwdForm'          => 'Password dimenticata',
	'UI:Login:ForgotPwdForm+'         => ITOP_APPLICATION_SHORT.' può inviarti un\'email contenente le istruzioni da seguire per reimpostare il tuo account.',
	'UI:Login:ResetPassword'          => 'Invia ora!',
	'UI:Login:ResetPwdFailed'         => 'Impossibile inviare un\'email: %1$s',
	'UI:Login:SeparatorOr'            => 'O',

	'UI:ResetPwd-Error-WrongLogin'    => '\'%1$s\' non è un nome utente valido',
	'UI:ResetPwd-Error-NotPossible'   => 'gli account esterni non consentono la reimpostazione della password.',
	'UI:ResetPwd-Error-FixedPwd'      => 'l\'account non consente la reimpostazione della password.',
	'UI:ResetPwd-Error-NoContact'     => 'l\'account non è associato a una persona.',
	'UI:ResetPwd-Error-NoEmailAtt'    => 'l\'account non è associato a una persona con un attributo email. Per favore, contatta il tuo amministratore.',
	'UI:ResetPwd-Error-NoEmail'       => 'indirizzo email mancante. Per favore, contatta il tuo amministratore.',
	'UI:ResetPwd-Error-Send'          => 'problema tecnico nel trasporto dell\'email. Per favore, contatta il tuo amministratore.',
	'UI:ResetPwd-EmailSent'           => 'Controlla la tua casella email e segui le istruzioni. Se non ricevi alcuna email, verifica il nome utente che hai inserito.',
	'UI:ResetPwd-EmailSubject'        => 'Reimposta la password di '.ITOP_APPLICATION_SHORT,
	'UI:ResetPwd-EmailBody'           => '<body><p>Hai richiesto di reimpostare la password di '.ITOP_APPLICATION_SHORT.'.</p><p>Segui questo link (uso singolo) per <a href="%1$s">inserire una nuova password</a></p>.',
	'UI:ResetPwd-Title'               => 'Reimposta la password',
	'UI:ResetPwd-Error-InvalidToken'  => 'Spiacenti, o la password è già stata reimpostata, o hai ricevuto diverse email. Assicurati di utilizzare il link fornito nell\'ultima email ricevuta.',
	'UI:ResetPwd-Error-EnterPassword' => 'Inserisci una nuova password per l\'account \'%1$s\'.',
	'UI:ResetPwd-Ready'               => 'La password è stata cambiata.',
	'UI:ResetPwd-Login'               => 'Clicca qui per accedere...',

	'UI:Login:About'                               => ITOP_APPLICATION.' Sviluppato da Combodo',
	'UI:Login:ChangeYourPassword'                  => 'Cambia la tua password',
	'UI:Login:OldPasswordPrompt'                   => 'Vecchia password',
	'UI:Login:NewPasswordPrompt'                   => 'Nuova password',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Riscrivi la nuova password',
	'UI:Login:IncorrectOldPassword'                => 'Errore: la vecchia password non è corretta',
	'UI:LogOffMenu'                                => 'Log off',
	'UI:LogOff:ThankYou'                           => 'Grazie per aver scelto '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Clicca qui per effettuare il login di nuovo...',
	'UI:ChangePwdMenu'                             => 'Cambia Password...',
	'UI:Login:PasswordChanged'                     => 'Password impostata con successo!',
	'UI:Login:PasswordNotChanged'                  => 'Errore: La password è la stessa!',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Nuova password e la nuova password digitata nuovamente non corrispondono !',
	'UI:Button:Login'                              => 'Entra in '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => 'L\'accesso a '.ITOP_APPLICATION_SHORT.' è limitato. Si prega di contattare un amministratore '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:AccessAdmin'                   => 'Accesso limitato alle persone che hanno privilegi di amministratore. Si prega di contattare un amministratore '.ITOP_APPLICATION_SHORT.'.',
	'UI:Login:Error:WrongOrganizationName'         => 'Organizzazione sconosciuta',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Più contatti hanno la stessa e-mail',
	'UI:Login:Error:NoValidProfiles'               => 'Nessun profilo valido fornito',
]);
