<?php

/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('JA JP', 'Japanese', '日本語', [
	'Class:KnownError' => '既知のエラー',
	'Class:KnownError+' => '既知の課題として文書化されたエラー',
	'Class:KnownError/Attribute:name' => '名前',
	'Class:KnownError/Attribute:name+' => 'This is expected to be a unique identifier within the Known Errors of this organization~~',
	'Class:KnownError/Attribute:org_id' => '顧客',
	'Class:KnownError/Attribute:org_id+' => 'Link the known error to the service provider in charge of handling them, or maybe to a customer organization if the error is specific to them~~',
	'Class:KnownError/Attribute:cust_name' => '顧客名',
	'Class:KnownError/Attribute:cust_name+' => '',
	'Class:KnownError/Attribute:problem_id' => '関連する問題',
	'Class:KnownError/Attribute:problem_id+' => 'The problem which couldn\'t be solved immediately and has led to the creation of this known error~~',
	'Class:KnownError/Attribute:problem_ref' => '参照',
	'Class:KnownError/Attribute:problem_ref+' => '',
	'Class:KnownError/Attribute:symptom' => '現象',
	'Class:KnownError/Attribute:symptom+' => 'What are the observable effects of this error?~~',
	'Class:KnownError/Attribute:root_cause' => '根本的な原因',
	'Class:KnownError/Attribute:root_cause+' => 'What is the underlying cause of this error?~~',
	'Class:KnownError/Attribute:workaround' => '回避策',
	'Class:KnownError/Attribute:workaround+' => 'How to bypass the effects of this error until a proper solution is found?~~',
	'Class:KnownError/Attribute:solution' => '解決策',
	'Class:KnownError/Attribute:solution+' => 'What is the permanent solution for this error?~~',
	'Class:KnownError/Attribute:error_code' => 'エラーコード',
	'Class:KnownError/Attribute:error_code+' => 'If a specific error code is associated to this known error, specify it here~~',
	'Class:KnownError/Attribute:domain' => 'ドメイン',
	'Class:KnownError/Attribute:domain+' => 'Choose the technical domain related to this known error?~~',
	'Class:KnownError/Attribute:domain/Value:Application' => 'アプリケーション',
	'Class:KnownError/Attribute:domain/Value:Application+' => '',
	'Class:KnownError/Attribute:domain/Value:Desktop' => 'デスクトップ',
	'Class:KnownError/Attribute:domain/Value:Desktop+' => '',
	'Class:KnownError/Attribute:domain/Value:Network' => 'ネットワーク',
	'Class:KnownError/Attribute:domain/Value:Network+' => '',
	'Class:KnownError/Attribute:domain/Value:Server' => 'サーバ',
	'Class:KnownError/Attribute:domain/Value:Server+' => '',
	'Class:KnownError/Attribute:vendor' => 'ベンダー',
	'Class:KnownError/Attribute:vendor+' => 'A free text field to identify the vendor of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:model' => 'モデル',
	'Class:KnownError/Attribute:model+' => 'The model of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:version' => 'バージョン',
	'Class:KnownError/Attribute:version+' => 'The version of the CI(s) concerned by this known error~~',
	'Class:KnownError/Attribute:ci_list' => 'CI',
	'Class:KnownError/Attribute:ci_list+' => 'The configuration items that are potentially impacted by this known error~~',
	'Class:KnownError/Attribute:document_list' => '文書',
	'Class:KnownError/Attribute:document_list+' => 'All the documents linked to this known error~~',
]);

//
// Class: lnkErrorToFunctionalCI
//

Dict::Add('JA JP', 'Japanese', '日本語', [
	'Class:lnkErrorToFunctionalCI' => 'リンク エラー/機能的CI',
	'Class:lnkErrorToFunctionalCI+' => '既知のエラーに関連するインフラ',
	'Class:lnkErrorToFunctionalCI/Name' => '%1$s / %2$s~~',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id' => 'CI',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name' => 'CI名',
	'Class:lnkErrorToFunctionalCI/Attribute:functionalci_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id' => 'エラー',
	'Class:lnkErrorToFunctionalCI/Attribute:error_id+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name' => 'エラー名',
	'Class:lnkErrorToFunctionalCI/Attribute:error_name+' => '',
	'Class:lnkErrorToFunctionalCI/Attribute:reason' => '理由',
	'Class:lnkErrorToFunctionalCI/Attribute:reason+' => '',
]);

//
// Class: lnkDocumentToError
//

Dict::Add('JA JP', 'Japanese', '日本語', [
	'Class:lnkDocumentToError' => 'リンク 文書/エラー',
	'Class:lnkDocumentToError+' => '文書と既知のエラー間のリンク',
	'Class:lnkDocumentToError/Name' => '%1$s / %2$s~~',
	'Class:lnkDocumentToError/Attribute:document_id' => '文書',
	'Class:lnkDocumentToError/Attribute:document_id+' => '',
	'Class:lnkDocumentToError/Attribute:document_name' => '文書名',
	'Class:lnkDocumentToError/Attribute:document_name+' => '',
	'Class:lnkDocumentToError/Attribute:error_id' => 'エラー',
	'Class:lnkDocumentToError/Attribute:error_id+' => '',
	'Class:lnkDocumentToError/Attribute:error_name' => 'エラー名',
	'Class:lnkDocumentToError/Attribute:error_name+' => '',
	'Class:lnkDocumentToError/Attribute:link_type' => 'リンクタイプ',
	'Class:lnkDocumentToError/Attribute:link_type+' => '',
]);

Dict::Add('JA JP', 'Japanese', '日本語', [
	'Menu:ProblemManagement' => '問題管理',
	'Menu:ProblemManagement+' => '問題管理',
	'Menu:Problem:Shortcuts' => 'ショートカット',
	'Menu:NewError' => '新規既知のエラー',
	'Menu:NewError+' => '新規既知のエラーの作成',
	'Menu:SearchError' => '既知のエラー検索',
	'Menu:SearchError+' => '既知のエラー検索',
	'Menu:Problem:KnownErrors' => '全ての既知のエラー',
	'Menu:Problem:KnownErrors+' => '全ての既知のエラー',
]);
