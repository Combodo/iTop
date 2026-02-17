<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN GB', 'British English', 'British English', [
	'UI:Login:Title' => ITOP_APPLICATION_SHORT.' login',
	'UI:Login:Logo:AltText' => ITOP_APPLICATION_SHORT.' logo',
	'UI:Login:Welcome' => 'Welcome to '.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => 'Incorrect login/password, please try again.',
	'UI:Login:IdentifyYourself' => 'Identify yourself before continuing',
	'UI:Login:UserNamePrompt' => 'User Name',
	'UI:Login:PasswordPrompt' => 'Password',
	'UI:Login:ForgotPwd' => 'Forgot your password?',
	'UI:Login:ForgotPwdForm' => 'Forgot your password',
	'UI:Login:ForgotPwdForm+' => ITOP_APPLICATION_SHORT.' can send you an email in which you will find instructions to follow to reset your account.',
	'UI:Login:ResetPassword' => 'Send now!',
	'UI:Login:ResetPwdFailed' => 'Failed to send an email: %1$s',
	'UI:Login:SeparatorOr' => 'Or',

	'UI:ResetPwd-Error-WrongLogin' => '\'%1$s\' is not a valid login',
	'UI:ResetPwd-Error-NotPossible' => 'external accounts do not allow password reset.',
	'UI:ResetPwd-Error-FixedPwd' => 'the account does not allow password reset.',
	'UI:ResetPwd-Error-NoContact' => 'the account is not associated with a person.',
	'UI:ResetPwd-Error-NoEmailAtt' => 'the account is not associated to a person having an email attribute. Please contact your administrator.',
	'UI:ResetPwd-Error-NoEmail' => 'missing an email address. Please Contact your administrator.',
	'UI:ResetPwd-Error-Send' => 'email transport technical issue. Please Contact your administrator.',
	'UI:ResetPwd-EmailSent' => 'Please check your email box and follow the instructions. If you receive no email, please check the login you typed.',
	'UI:ResetPwd-EmailSubject' => 'Reset your '.ITOP_APPLICATION_SHORT.' password',
	'UI:ResetPwd-EmailBody' => '<body><p>You have requested to reset your '.ITOP_APPLICATION_SHORT.' password.</p><p>Please follow this link (single usage) to <a href="%1$s">enter a new password</a></p>.',
	'UI:ResetPwd-Title' => 'Reset password',
	'UI:ResetPwd-Error-InvalidToken' => 'Sorry, either the password has already been reset, or you have received several emails. Please make sure that you use the link provided in the very last email received.',
	'UI:ResetPwd-Error-EnterPassword' => 'Enter a new password for the account \'%1$s\'.',
	'UI:ResetPwd-Ready' => 'The password has been changed.',
	'UI:ResetPwd-Login' => 'Click here to log in...',

	'UI:Login:About'                               => ITOP_APPLICATION.' Powered by Combodo',
	'UI:Login:ChangeYourPassword' => 'Change Your Password',
	'UI:Login:OldPasswordPrompt' => 'Old password',
	'UI:Login:NewPasswordPrompt' => 'New password',
	'UI:Login:RetypeNewPasswordPrompt' => 'Retype new password',
	'UI:Login:IncorrectOldPassword' => 'Error: the old password is incorrect',
	'UI:LogOffMenu' => 'Log off',
	'UI:LogOff:ThankYou'                           => 'Thank you for using '.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain' => 'Click here to log in again...',
	'UI:ChangePwdMenu' => 'Change Password...',
	'UI:Login:PasswordChanged' => 'Password successfully set!',
	'UI:Login:PasswordNotChanged' => 'Error: Password is the same!',
	'UI:Login:RetypePwdDoesNotMatch' => 'New password and retyped new password do not match!',
	'UI:Button:Login'                              => 'Enter '.ITOP_APPLICATION,
	'UI:Login:Error:AccessRestricted'              => ITOP_APPLICATION_SHORT.' access to this page is restricted. Please, contact an '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:AccessAdmin'                   => 'Access restricted to people having administrator privileges. Please, contact an '.ITOP_APPLICATION_SHORT.' administrator.',
	'UI:Login:Error:WrongOrganizationName' => 'Unknown organisation',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail',
	'UI:Login:Error:NoValidProfiles' => 'No valid profile provided',
]);