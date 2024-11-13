<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
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

//////////////////////////////////////////////////////////////////////
// Classes in 'bizmodel'
//////////////////////////////////////////////////////////////////////
//

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
// Class: FAQ
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => '常见问题',
	'Class:FAQ/Attribute:title' => '标题',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => '概要',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => '描述',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => '类别',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => '类别名称',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => '错误编码',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => '关键字',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => '范围',
]);

//
// Class: FAQCategory
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:FAQCategory' => 'FAQ 类别',
	'Class:FAQCategory+' => 'FAQ 类别',
	'Class:FAQCategory/Attribute:name' => '名称',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => '此类别 FAQ 相关的所有常见问题',
]);
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Menu:ProblemManagement' => '问题管理',
	'Menu:ProblemManagement+' => '问题管理',
	'Menu:Problem:Shortcuts' => '快捷方式',
	'Menu:FAQCategory' => 'FAQ 类别',
	'Menu:FAQCategory+' => '所有 FAQ 类别',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => '所有 FAQ',
	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => '常见问题',
	'Brick:Portal:FAQ:Title+' => '<p>需要帮助?</p><p>查阅列表中的常见问题, 或许可以立即找到令您满意的答案.</p>',
]);
