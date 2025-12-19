<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'UI:Login:Title'                  => ITOP_APPLICATION_SHORT.'登录',
	'UI:Login:Logo:AltText'           => ITOP_APPLICATION_SHORT.' logo~~',
	'UI:Login:Welcome'                => '欢迎使用'.ITOP_APPLICATION_SHORT.'!',
	'UI:Login:IncorrectLoginPassword' => '用户名或密码错误, 请重试.',
	'UI:Login:IdentifyYourself'       => '请完成身份认证',
	'UI:Login:UserNamePrompt'         => '用户名',
	'UI:Login:PasswordPrompt'         => '密码',
	'UI:Login:ForgotPwd'              => '忘记密码?',
	'UI:Login:ForgotPwdForm'          => '忘记密码',
	'UI:Login:ForgotPwdForm+'         => ITOP_APPLICATION_SHORT.'将会给您发送一封密码重置邮件.',
	'UI:Login:ResetPassword'          => '立即发送!',
	'UI:Login:ResetPwdFailed'         => '邮件发送失败: %1$s',
	'UI:Login:SeparatorOr'            => '或',

	'UI:ResetPwd-Error-WrongLogin'    => '\'%1$s\' 用户名无效',
	'UI:ResetPwd-Error-NotPossible'   => '外部账号不允许重置密码.',
	'UI:ResetPwd-Error-FixedPwd'      => '此账号不允许重置密码.',
	'UI:ResetPwd-Error-NoContact'     => '此账号没有关联到人员.',
	'UI:ResetPwd-Error-NoEmailAtt'    => '此账号未关联邮箱地址,请联系管理员.',
	'UI:ResetPwd-Error-NoEmail'       => '缺少邮箱地址. 请联系管理员.',
	'UI:ResetPwd-Error-Send'          => '邮件发送存在技术原因. 请联系管理员.',
	'UI:ResetPwd-EmailSent'           => '请检查您的收件箱并根据指引进行操作. 如果您没有收到邮件, 请检查您登录时的输入是否存在错误.',
	'UI:ResetPwd-EmailSubject'        => '重置'.ITOP_APPLICATION_SHORT.'密码',
	'UI:ResetPwd-EmailBody'           => '<body><p>您已请求重置'.ITOP_APPLICATION_SHORT.'密码.</p><p>请点击这个链接 (一次性) <a href="%1$s">来输入新的密码</a></p>.',
	'UI:ResetPwd-Title'               => '重置密码',
	'UI:ResetPwd-Error-InvalidToken'  => '对不起, 密码已经被重置, 请检查是否收到了多封密码重置邮件. 请点击最新邮件里的链接.',
	'UI:ResetPwd-Error-EnterPassword' => '请输入 \'%1$s\' 的新密码.',
	'UI:ResetPwd-Ready'               => '密码已修改成功.',
	'UI:ResetPwd-Login'               => '点击这里登录...',

	'UI:Login:About'                               => ITOP_APPLICATION.'由 Combodo 创建',
	'UI:Login:ChangeYourPassword'                  => '修改您的密码',
	'UI:Login:OldPasswordPrompt'                   => '旧密码',
	'UI:Login:NewPasswordPrompt'                   => '新密码',
	'UI:Login:RetypeNewPasswordPrompt'             => '重复新密码',
	'UI:Login:IncorrectOldPassword'                => '错误: 旧密码错误',
	'UI:LogOffMenu'                                => '注销',
	'UI:LogOff:ThankYou'                           => '感谢使用'.ITOP_APPLICATION,
	'UI:LogOff:ClickHereToLoginAgain'              => '点击这里再次登录...',
	'UI:ChangePwdMenu'                             => '修改密码...',
	'UI:Login:PasswordChanged'                     => '密码已成功设置!',
	'UI:Login:PasswordNotChanged'                  => '错误!密码未改变!',
	'UI:Login:RetypePwdDoesNotMatch'               => '新密码输入不一致!',
	'UI:Button:Login'                              => '登录'.ITOP_APPLICATION_SHORT,
	'UI:Login:Error:AccessRestricted'              => ITOP_APPLICATION_SHORT.'访问被限制. 请联系管理员.',
	'UI:Login:Error:AccessAdmin'                   => '只有具有管理员权限的人才能访问. 请联系管理员.',
	'UI:Login:Error:WrongOrganizationName'         => '未知组织',
	'UI:Login:Error:MultipleContactsHaveSameEmail' => '多个联系人存在相同的邮箱',
	'UI:Login:Error:NoValidProfiles'               => '无效的资料',
]);
