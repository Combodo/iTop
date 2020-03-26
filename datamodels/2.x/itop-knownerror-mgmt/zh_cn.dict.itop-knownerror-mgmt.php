<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2018 Combodo SARL
 * @license    http://opensource.org/licenses/AGPL-3.0
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
// Class: KnownError
//
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:KnownError' => '已知错误',
	'Class:KnownError+' => '记录一个已知错误',
	'Class:KnownError/Attribute:name' => '名称',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => '客户',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:cust_name' => '客户名称',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => '相关问题',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:problem_ref' => '问题编号',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => '现象',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => '问题根源',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => '解决过程',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => '解决方案',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => '错误代码',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => '类型',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => '应用',
	'Class:KnownError/Attribute:domain/Value:Application+' => '应用',
	'Class:KnownError/Attribute:domain/Value:Desktop' => '桌面',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '桌面',
	'Class:KnownError/Attribute:domain/Value:Network' => '网络',
	'Class:KnownError/Attribute:domain/Value:Network+' => '网络',
	'Class:KnownError/Attribute:domain/Value:Server' => '服务器',
	'Class:KnownError/Attribute:domain/Value:Server+' => '服务器',
	'Class:KnownError/Attribute:vendor' => '厂商',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => '型号',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => '版本',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => '配置项',
	'Class:KnownError/Attribute:ci_list+' => '该已知错误相关的所有配置项',
	'Class:KnownError/Attribute:document_list' => '文档',
	'Class:KnownError/Attribute:document_list+' => '该已知错误相关的所有文档',
));

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkErrorToFunctionalCI' => '关联 已知问题/功能配置项',
	'Class:lnkErrorToFunctionalCI+' => '已知问题和功能配置项之间的关联',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => '配置项',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => '配置项名称',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => '已知问题',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => '已知问题名称',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => '原因',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
));

//
// Class: lnkDocumentToError
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:lnkDocumentToError' => '关联 文档/已知问题',
	'Class:lnkDocumentToError+' => '文档和已知问题之间的关联',
	'Class:lnkDocumentToError/Attribute:document_id' => '文档',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => '文档名称',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => '已知问题',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => '已知问题名称',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => '关联类型',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
));

//
// Class: FAQ
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'FAQ',
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
	'Class:FAQ/Attribute:error_code' => '错误代码',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => '关键字',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domains~~',
));

//
// Class: FAQCategory
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:FAQCategory' => 'FAQ 类别',
	'Class:FAQCategory+' => 'FAQ 类别',
	'Class:FAQCategory/Attribute:name' => '名称',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => '该类别FAQ 相关的所有常见问题',
));
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Menu:ProblemManagement' => '问题管理',
	'Menu:ProblemManagement+' => '问题管理',
	'Menu:Problem:Shortcuts' => '快捷方式',
	'Menu:NewError' => '新建已知问题',
	'Menu:NewError+' => '新建已知问题',
	'Menu:SearchError' => '搜索已知问题',
	'Menu:SearchError+' => '搜索已知问题',
	'Menu:Problem:KnownErrors' => '所有已知错误',
	'Menu:Problem:KnownErrors+' => '所有已知错误',
	'Menu:FAQCategory' => 'FAQ 类别',
	'Menu:FAQCategory+' => '所有FAQ 类别',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => '所有FAQ',

	'Brick:Portal:FAQ:Menu' => 'FAQ',
	'Brick:Portal:FAQ:Title' => 'FAQ',
	'Brick:Portal:FAQ:Title+' => '<p>需要帮助?</p><p>查阅列表中的常见问题,或许可以立即找到令您满意的答案.</p>',
));
