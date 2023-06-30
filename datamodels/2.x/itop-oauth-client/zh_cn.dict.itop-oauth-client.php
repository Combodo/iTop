<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Menu:CreateMailbox' => '创建收件箱...',
	'Menu:OAuthClient' => 'OAuth 客户端',
	'Menu:OAuthClient+' => '',
	'Menu:GenerateTokens' => '生成访问令牌...',
	'Menu:RegenerateTokens' => '重新生成访问令牌...',

	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Mailbox creation~~',

	'itop-oauth-client:UsedForSMTP' => 'This OAuth client is used for SMTP~~',
	'itop-oauth-client:TestSMTP' => '发送测试邮件',
	'itop-oauth-client:MissingOAuthClient' => 'Missing Oauth client for user name %1$s~~',
	'itop-oauth-client:Message:MissingToken' => '在使用OAuth 客户端之前需要提前生成访问令牌',
	'itop-oauth-client:Message:RegenerateToken' => 'Regenerate access token to to take into account the changes~~',
	'itop-oauth-client:Message:TokenCreated' => '已创建访问令牌',
	'itop-oauth-client:Message:TokenRecreated' => '访问令牌已重新生成',
	'itop-oauth-client:Message:TokenError' => '由于服务端错误,访问令牌生成失败',

	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'The combination Login (%1$s) and Use for SMTP (%2$s) has already be used for OAuth Client~~',

	'OAuthClient:baseinfo' => '基本信息',
	'OAuthClient:scope' => 'Scope',
]);

//
// Class: OAuthClient
//

Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'Class:OAuthClient' => 'OAuth 客户端',
	'Class:OAuthClient/Attribute:provider' => '供应商',
	'Class:OAuthClient/Attribute:provider+' => '',
	'Class:OAuthClient/Attribute:name' => '登录',
	'Class:OAuthClient/Attribute:name+' => 'In general, this is your email address~~',
	'Class:OAuthClient/Attribute:status' => '状态',
	'Class:OAuthClient/Attribute:status+' => 'After creation, use the action "Generate access token" to be able to use this OAuth client~~',
	'Class:OAuthClient/Attribute:status/Value:active' => '访问令牌已生成',
	'Class:OAuthClient/Attribute:status/Value:inactive' => '没有访问令牌',
	'Class:OAuthClient/Attribute:description' => '描述',
	'Class:OAuthClient/Attribute:description+' => '',
	'Class:OAuthClient/Attribute:client_id' => '客户端 id',
	'Class:OAuthClient/Attribute:client_id+' => 'A long string of characters provided by your OAuth2 provider~~',
	'Class:OAuthClient/Attribute:client_secret' => '客户端密钥',
	'Class:OAuthClient/Attribute:client_secret+' => 'Another long string of characters provided by your OAuth2 provider~~',
	'Class:OAuthClient/Attribute:refresh_token' => '刷新访问令牌',
	'Class:OAuthClient/Attribute:refresh_token+' => '',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => '刷新访问令牌时效',
	'Class:OAuthClient/Attribute:refresh_token_expiration+' => '',
	'Class:OAuthClient/Attribute:token' => 'Access token',
	'Class:OAuthClient/Attribute:token+' => '',
	'Class:OAuthClient/Attribute:token_expiration' => '访问令牌过期',
	'Class:OAuthClient/Attribute:token_expiration+' => '',
	'Class:OAuthClient/Attribute:redirect_url' => '重定向 url',
	'Class:OAuthClient/Attribute:redirect_url+' => <<<EOF
This url must be copied in the OAuth2 configuration of the provider
Erase the field to recalculate default value
EOF
,
	'Class:OAuthClient/Attribute:mailbox_list' => 'Mailbox list',
	'Class:OAuthClient/Attribute:mailbox_list+' => '',
]);

//
// Class: OAuthClientAzure
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OAuthClientAzure' => 'Microsoft Azure OAuth 客户端',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)',
	'Class:OAuthClientAzure/Attribute:scope' => 'Scope',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Usually default selection is appropriate',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Advanced scope',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'As soon as you enter something here it takes precedence on the "Scope" selection which is then ignored',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Used scope',
	'Class:OAuthClientAzure/Attribute:used_scope+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => '简单',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => '高级',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => '用于 SMTP',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'At least one OAuth client must have this flag to "Yes", if you want iTop to use it for sending mails',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => '是',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => '否',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Class:OAuthClientGoogle' => 'Google OAuth 客户端',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Scope',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Usually default selection is appropriate',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP+' => '',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP+' => '',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Advanced scope',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'As soon as you enter something here it takes precedence on the "Scope" selection which is then ignored',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Used scope',
	'Class:OAuthClientGoogle/Attribute:used_scope+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => '简单',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple+' => '',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => '高级',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced+' => '',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => '用于 SMTP',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'At least one OAuth client must have this flag to "Yes", if you want iTop to use it for sending mails',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => '是',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => '否',
));


