<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Menu:CreateMailbox' => '创建邮箱...',
	'Menu:OAuthClient' => 'OAuth客户端',
	'Menu:OAuthClient+' => '~~',
	'Menu:GenerateTokens' => '生成访问令牌...',
	'Menu:RegenerateTokens' => '重新生成访问令牌...',
	'itop-oauth-client/Operation:CreateMailBox/Title' => '邮箱创建',
	'itop-oauth-client:UsedForSMTP' => '此OAuth客户端用户SMTP',
	'itop-oauth-client:TestSMTP' => '发送测试邮件',
	'itop-oauth-client:MissingOAuthClient' => '没有Oauth客户端给用户%1$s',
	'itop-oauth-client:Message:MissingToken' => '使用OAuth客户端前生成访问令牌',
	'itop-oauth-client:Message:RegenerateToken' => '重新生成访问令牌以适用更改',
	'itop-oauth-client:Message:TokenCreated' => '访问令牌已生成',
	'itop-oauth-client:Message:TokenRecreated' => '访问令牌已重新生成',
	'itop-oauth-client:Message:TokenError' => '由于服务错误没有生成访问令牌',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => '此组合登录 (%1$s) 和使用于SMTP (%2$s) 已经在OAuth客户端使用',
	'OAuthClient:baseinfo' => '基本信息',
	'OAuthClient:scope' => '范围',
]);

//
// Class: OAuthClient
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OAuthClient' => 'OAuth客户端',
	'Class:OAuthClient/Attribute:provider' => '提供商',
	'Class:OAuthClient/Attribute:provider+' => '~~',
	'Class:OAuthClient/Attribute:name' => '登录',
	'Class:OAuthClient/Attribute:name+' => '~~',
	'Class:OAuthClient/Attribute:scope' => '范围',
	'Class:OAuthClient/Attribute:scope+' => '~~',
	'Class:OAuthClient/Attribute:status' => '状态',
	'Class:OAuthClient/Attribute:status+' => '创建后, 通过 "生成访问令牌" 来使用此OAuth 客户端',
	'Class:OAuthClient/Attribute:status/Value:active' => '已生成访问令牌',
	'Class:OAuthClient/Attribute:status/Value:inactive' => '没有访问令牌',
	'Class:OAuthClient/Attribute:description' => '备注',
	'Class:OAuthClient/Attribute:description+' => '~~',
	'Class:OAuthClient/Attribute:client_id' => '客户端编号',
	'Class:OAuthClient/Attribute:client_id+' => '~~',
	'Class:OAuthClient/Attribute:client_secret' => '客户端密码',
	'Class:OAuthClient/Attribute:client_secret+' => '~~',
	'Class:OAuthClient/Attribute:refresh_token' => '刷新令牌',
	'Class:OAuthClient/Attribute:refresh_token+' => '~~',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => '刷新令牌有效期',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '~~',
	'Class:OAuthClient/Attribute:token' => '访问令牌',
	'Class:OAuthClient/Attribute:token+' => '~~',
	'Class:OAuthClient/Attribute:token_expiration' => '访问令牌有效期',
	'Class:OAuthClient/Attribute:token_expiration+' => '~~',
	'Class:OAuthClient/Attribute:redirect_url' => '重定向URL',
	'Class:OAuthClient/Attribute:redirect_url+' => '~~',
	'Class:OAuthClient/Attribute:mailbox_list' => '邮箱列表',
	'Class:OAuthClient/Attribute:mailbox_list+' => '~~'
]);

//
// Class: OAuthClientAzure
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OAuthClientAzure' => '用于微软Azure的OAuth客户端',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => '范围',
	'Class:OAuthClientAzure/Attribute:scope+' => '通常情况下使用默认选择最合适',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '~~',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => '高级范围',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => '您在此输入的内容将优先于 "范围" 选择并导致其被忽略',
	'Class:OAuthClientAzure/Attribute:used_scope' => '使用范围',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => '精简',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => '高级',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => '使用于SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => '如果您需要系统使用其发送邮件, 则至少需要有一个OAuth客户端标记为 "是"',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => '是',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => '否',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OAuthClientGoogle' => '用于Google的OAuth客户端',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => '范围',
	'Class:OAuthClientGoogle/Attribute:scope+' => '通常情况下使用默认选择最合适',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '~~',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => '高级范围',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => '您在此输入的内容将优先于 "范围" 选择并导致其被忽略',
	'Class:OAuthClientGoogle/Attribute:used_scope' => '使用范围',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => '精简',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => '高级',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => '使用与SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => '如果您需要系统使用其发送邮件, 则至少需要有一个OAuth客户端标记为 "是"',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => '是',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => '否',
));
