<?php

/**
 * @copyright Copyright (C) 2010-2026 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('DA DA', 'Danish', 'Dansk', [
	'UI:Login:Title'                  => ITOP_APPLICATION_SHORT.' login~~',
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome'                => 'Velkommen til '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Ukorrekt login/adgangskode, venligst prøv igen.',
	'UI:Login:IdentifyYourself'       => 'Identificer dig før du fortsætter',
	'UI:Login:UserNamePrompt'         => 'Bruger Navn',
	'UI:Login:PasswordPrompt'         => 'Adgangskode',
	'UI:Login:ForgotPwd'              => 'Forgot your password?~~',
	'UI:Login:ForgotPwdForm'          => 'Forgot your password~~',
	'UI:Login:ForgotPwdForm+'         => ITOP_APPLICATION_SHORT.' can send you an email in which you will find instructions to follow to reset your account.~~',
	'UI:Login:ResetPassword'          => 'Send now!~~',
	'UI:Login:ResetPwdFailed'         => 'Failed to send an email: %1$s~~',
	'UI:Login:SeparatorOr'            => 'Or~~',

	'UI:ResetPwd-Error-WrongLogin'    => '\'%1$s\' is not a valid login~~',
	'UI:ResetPwd-Error-NotPossible'   => 'external accounts do not allow password reset.~~',
	'UI:ResetPwd-Error-FixedPwd'      => 'the account does not allow password reset.~~',
	'UI:ResetPwd-Error-NoContact'     => 'the account is not associated to a person.~~',
	'UI:ResetPwd-Error-NoEmailAtt'    => 'the account is not associated to a person having an email attribute. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-NoEmail'       => 'missing an email address. Please Contact your administrator.~~',
	'UI:ResetPwd-Error-Send'          => 'email transport technical issue. Please Contact your administrator.~~',
	'UI:ResetPwd-EmailSent'           => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.~~',
	'UI:ResetPwd-EmailSubject'        => 'Reset your '.ITOP_APPLICATION_SHORT.' password~~',
	'UI:ResetPwd-EmailBody'           => '<body><p>You have requested to reset your '.ITOP_APPLICATION_SHORT.' password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.~~',
	'UI:ResetPwd-Title'               => 'Reset password~~',
	'UI:ResetPwd-Error-InvalidToken'  => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.~~',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.~~',
	'UI:ResetPwd-Ready'               => 'The password has been changed.~~',
	'UI:ResetPwd-Login'               => 'Click here to login...~~',

	'UI:Login:About'                               => 'Om',
	'UI:Login:ChangeYourPassword'                  => 'Skift Adgangskode',
	'UI:Login:OldPasswordPrompt'                   => 'Gammel Adgangskode',
	'UI:Login:NewPasswordPrompt'                   => 'Ny Adgangskode',
	'UI:Login:RetypeNewPasswordPrompt'             => 'Gentag ny adgangskode',
	'UI:Login:IncorrectOldPassword'                => 'Fejl: den gamle adgangskode er forkert',
	'UI:LogOffMenu'                                => 'Log ud',
	'UI:LogOff:ThankYou'                           => 'Tak for at du brugte '.ITOP_APPLICATION_SHORT,
	'UI:LogOff:ClickHereToLoginAgain'              => 'Klik her for at logge ind igen...',
	'UI:ChangePwdMenu'                             => 'Skift Adgangskode...',
	'UI:Login:PasswordChanged'                     => 'Adgangskode oprettet med success!',
	'UI:Login:PasswordNotChanged'                  => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch'               => 'Ny adgangskode og gentaget adgangskode passer ikke sammen!',
	'UI:Button:Login'                              => 'Enter '.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => ITOP_APPLICATION_SHORT.' adgang er begrænset. Venligst, kontakt en '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:AccessAdmin'                   => 'Adgang er begrænset til administratorer. Venligst, kontakt en '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
]);
