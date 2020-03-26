<?php
/**
 * Localized data
 *
 * @author    Robert Deng <denglx@gmail.com>
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//
// Class: UserLocal
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:UserLocal' => 'iTop 用户',
	'Class:UserLocal+' => '用户由 iTop 验证身份',
	'Class:UserLocal/Attribute:password' => '密码',
	'Class:UserLocal/Attribute:password+' => '用于验证用户身份的字符串',

	'Class:UserLocal/Attribute:expiration' => '密码过期',
	'Class:UserLocal/Attribute:expiration+' => '密码过期状态 (需要一个扩展才能生效)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => '允许过期',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => '永不过期',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => '已过期',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '',
	'Class:UserLocal/Attribute:password_renewed_date' => '密码更新',
	'Class:UserLocal/Attribute:password_renewed_date+' => '上次修改密码的时间',

	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => '密码必须至少8 个字符,包含大小写、数字和特殊字符.',

	'UserLocal:password:expiration' => 'The fields below require an extension~~'
));
