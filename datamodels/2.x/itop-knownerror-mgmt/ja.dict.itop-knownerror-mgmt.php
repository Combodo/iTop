<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:KnownError' => '既知のエラー',
	'Class:KnownError+' => '既知の課題として文書化されたエラー',
	'Class:KnownError/Attribute:name' => '名前',
	'Class:KnownError/Attribute:name+' => '',
	'Class:KnownError/Attribute:org_id' => '顧客',
	'Class:KnownError/Attribute:org_id+' => '',
	'Class:KnownError/Attribute:problem_id' => '関連する問題',
	'Class:KnownError/Attribute:problem_id+' => '',
	'Class:KnownError/Attribute:symptom' => '現象',
	'Class:KnownError/Attribute:symptom+' => '',
	'Class:KnownError/Attribute:root_cause' => '根本的な原因',
	'Class:KnownError/Attribute:root_cause+' => '',
	'Class:KnownError/Attribute:workaround' => '回避策',
	'Class:KnownError/Attribute:workaround+' => '',
	'Class:KnownError/Attribute:solution' => '解決策',
	'Class:KnownError/Attribute:solution+' => '',
	'Class:KnownError/Attribute:error_code' => 'エラーコード',
	'Class:KnownError/Attribute:error_code+' => '',
	'Class:KnownError/Attribute:domain' => 'ドメイン',
	'Class:KnownError/Attribute:domain+' => '',
	'Class:KnownError/Attribute:domain/Value:Application' => 'アプリケーション',
	'Class:KnownError/Attribute:domain/Value:Application+' => 'アプリケーション',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'デスクトップ',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => 'デスクトップ',
	'Class:KnownError/Attribute:domain/Value:Network' => 'ネットワーク',
	'Class:KnownError/Attribute:domain/Value:Network+' => 'ネットワーク',
	'Class:KnownError/Attribute:domain/Value:Server' => 'サーバ',
	'Class:KnownError/Attribute:domain/Value:Server+' => 'サーバ',
	'Class:KnownError/Attribute:vendor' => 'ベンダー',
	'Class:KnownError/Attribute:vendor+' => '',
	'Class:KnownError/Attribute:model' => 'モデル',
	'Class:KnownError/Attribute:model+' => '',
	'Class:KnownError/Attribute:version' => 'バージョン',
	'Class:KnownError/Attribute:version+' => '',
	'Class:KnownError/Attribute:ci_list' => 'CI',
	'Class:KnownError/Attribute:ci_list+' => '',
	'Class:KnownError/Attribute:document_list' => '文書',
	'Class:KnownError/Attribute:document_list+' => '',
	'Class:lnkErrorToFunctionalCI' => 'リンク エラー/機能的CI',
	'Class:lnkErrorToFunctionalCI+' => '既知のエラーに関連するインフラ',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'エラー',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => '理由',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
	'Class:lnkDocumentToError' => 'リンク 文書/エラー',
	'Class:lnkDocumentToError+' => '文書と既知のエラー間のリンク',
	'Class:lnkDocumentToError/Attribute:document_id' => '文書',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'エラー',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'リンクタイプ',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
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
	'Class:FAQ/Attribute:error_code' => 'エラーコード',
	'Class:FAQ/Attribute:error_code+' => '',
	'Class:FAQ/Attribute:key_words' => 'キーワード',
	'Class:FAQ/Attribute:key_words+' => '',
	'Class:FAQCategory' => 'FAQカテゴリ',
	'Class:FAQCategory+' => 'FAQのためのカテゴリ',
	'Class:FAQCategory/Attribute:name' => '名前',
	'Class:FAQCategory/Attribute:name+' => '',
	'Class:FAQCategory/Attribute:faq_list' => 'FAQ',
	'Class:FAQCategory/Attribute:faq_list+' => '',
	'Class:KnownError/Attribute:cust_name' => '顧客名',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_ref' => '参照',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI名',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'エラー名',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'エラー名',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:FAQ/Attribute:category_name' => 'カテゴリ名',
	'Class:FAQ/Attribute:category_name+' => '',
	'Menu:ProblemManagement' => '問題管理',
	'Menu:ProblemManagement+' => '問題管理',
	'Menu:Problem:Shortcuts' => 'ショートカット',
	'Menu:NewError' => '新規既知のエラー',
	'Menu:NewError+' => '新規既知のエラーの作成',
	'Menu:SearchError' => '既知のエラー検索',
	'Menu:SearchError+' => '既知のエラー検索',
	'Menu:Problem:KnownErrors' => '全ての既知のエラー',
	'Menu:Problem:KnownErrors+' => '全ての既知のエラー',
	'Menu:FAQCategory' => 'FAQカテゴリ',
	'Menu:FAQCategory+' => '全てのFAQカテゴリ',
	'Menu:FAQ' => 'FAQ',
	'Menu:FAQ+' => '全FAQ',
));
?>