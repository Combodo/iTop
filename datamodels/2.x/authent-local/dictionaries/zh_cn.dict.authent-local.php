<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author Robert Deng <denglx@gmail.com>
 *
 */
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:UserLocal' => ITOP_APPLICATION_SHORT.' 用户',
	'Class:UserLocal+' => '用户由'.ITOP_APPLICATION_SHORT.'验证身份',
	'Class:UserLocal/Attribute:expiration' => '密码过期',
	'Class:UserLocal/Attribute:expiration+' => '密码过期状态 (需要一个扩展才能生效)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => '允许过期',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => '已过期',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => '永不过期',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => '一次性密码',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => '用户不允许修改密码.',
	'Class:UserLocal/Attribute:password' => '密码',
	'Class:UserLocal/Attribute:password+' => '用于验证用户身份的字符串',
	'Class:UserLocal/Attribute:password_renewed_date' => '密码更新',
	'Class:UserLocal/Attribute:password_renewed_date+' => '上次修改密码的时间',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => '不允许用户为自己设置 "一次性密码" 的失效期限',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => '密码必须至少8个字符, 包含大小写, 数字和特殊字符.',
	'UserLocal:password:expiration' => '下面的区域需要插件扩展',
]);
