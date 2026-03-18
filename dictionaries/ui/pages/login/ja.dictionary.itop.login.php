<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('JA JP', 'Japanese', '日本語', [
	'UI:Login:Title'                  => ITOP_APPLICATION_SHORT.' login~~',
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo',
	'UI:Login:Welcome'                => ITOP_APPLICATION_SHORT.'へようこそ',
	'UI:Login:IncorrectLoginPassword' => 'ログイン/パスワードが正しくありません。再度入力ください。',
	'UI:Login:IdentifyYourself'       => '続けて作業を行う前に認証を受けてください。',
	'UI:Login:UserNamePrompt'         => 'ユーザー名',
	'UI:Login:PasswordPrompt'         => 'パスワード',
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

	'UI:Login:About'                               => '',
	'UI:Login:ChangeYourPassword'                  => 'パスワードを変更してください',
	'UI:Login:OldPasswordPrompt'                   => '古いパスワード',
	'UI:Login:NewPasswordPrompt'                   => '新しいパスワード',
	'UI:Login:RetypeNewPasswordPrompt'             => '新しいパスワードを再度入力してください。',
	'UI:Login:IncorrectOldPassword'                => 'エラー：既存パスワードが正しくありません。',
	'UI:LogOffMenu'                                => 'ログオフ',
	'UI:LogOff:ThankYou'                           => ITOP_APPLICATION_SHORT.'をご利用いただき、ありがとうございます。',
	'UI:LogOff:ClickHereToLoginAgain'              => '再度ログインするにはここをクリックしてください...',
	'UI:ChangePwdMenu'                             => 'パスワードを変更する...',
	'UI:Login:PasswordChanged'                     => 'パスワードは変更されました。',
	'UI:Login:PasswordNotChanged'                  => 'Error: Password is the same!~~',
	'UI:Login:RetypePwdDoesNotMatch'               => '2度入力された新しいパスワードが一致しません!',
	'UI:Button:Login'                              => ITOP_APPLICATION_SHORT.'へ入る',
	'UI:Login:Error:AccessRestricted'              => ITOP_APPLICATION_SHORT.'へのアクセスは制限されています。'.ITOP_APPLICATION_SHORT.'管理者に問い合わせしてください。',
	'UI:Login:Error:AccessAdmin'                   => '管理者権限をもつユーザにアクセスが制限されています。'.ITOP_APPLICATION_SHORT.'管理者に問い合わせしてください。',
	'UI:Login:Error:WrongOrganizationName'         => 'Unknown organization~~',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => 'Multiple contacts have the same e-mail~~',
	'UI:Login:Error:NoValidProfiles'               => 'No valid profile provided~~',
]);
