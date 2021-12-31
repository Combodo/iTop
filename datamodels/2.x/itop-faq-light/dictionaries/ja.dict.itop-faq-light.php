<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: FAQ
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:FAQ' => 'FAQ',
	'Class:FAQ+' => 'よくある質問',
	'Class:FAQ/Attribute:title' => 'タイトル',
	'Class:FAQ/Attribute:title+' => '',
	'Class:FAQ/Attribute:summary' => '要約',
	'Class:FAQ/Attribute:summary+' => '',
	'Class:FAQ/Attribute:description' => '説明',
	'Class:FAQ/Attribute:description+' => '',
	'Class:FAQ/Attribute:category_id' => 'カテゴリ',
	'Class:FAQ/Attribute:category_id+' => '',
	'Class:FAQ/Attribute:category_name' => 'カテゴリ名',
	'Class:FAQ/Attribute:category_name+' => '',
	'Class:FAQ/Attribute:error_code' => 'エラーコード',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'キーワード',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQ/Attribute:domains' => 'Domains~~',
));

//
// Class: FAQCategory
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:FAQCategory' => 'FAQカテゴリ',
	'Class:FAQCategory+' => 'FAQのためのカテゴリ',
	'Class:FAQCategory/Attribute:name' => '名前',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => '',
));
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Menu:ProblemManagement' => '問題管理',
	'Menu:ProblemManagement+' => '問題管理',
	'Menu:Problem:Shortcuts' => 'ショートカット',
	'Menu:FAQCategory' => 'FAQカテゴリ',
	'Menu:FAQCategory+' => '全てのFAQカテゴリ',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => '全FAQ',
	'Brick:Portal:FAQ:Menu' => 'FAQ~~',
	'Brick:Portal:FAQ:Title' => 'Frequently Asked Questions~~',
	'Brick:Portal:FAQ:Title+' => '<p>In a hurry?</p><p>Check out the list of most common questions and (maybe) find the expected answer right away.</p>~~',
));
